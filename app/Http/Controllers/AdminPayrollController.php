<?php

namespace App\Http\Controllers;

use App\Models\SalarySlip;
use App\Models\Staff;
use App\Models\SalesInstrument;
use App\Models\ServiceHarian;
use App\Models\DailySale;
use App\Models\StaffAttendance;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WhatsappService;

class AdminPayrollController extends Controller
{
    /**
     * Helper: normalisasi nama agar pencocokan tidak terpengaruh
     * perbedaan huruf besar/kecil maupun spasi berlebih.
     */
    private function normalizeName(?string $name): string
    {
        return mb_strtolower(trim($name ?? ''));
    }

    private function calculateSalesInstrumentFee(string $staffName, Carbon $startDate, Carbon $endDate): int
    {
        $salesFee = 0;
        $cleanStaffName = $this->normalizeName($staffName);

        $salesInstruments = SalesInstrument::whereBetween('tanggal', [$startDate, $endDate])->get();
        foreach ($salesInstruments as $sale) {
            foreach ($sale->sales_list as $idx => $namaSales) {
                if ($this->normalizeName($namaSales) === $cleanStaffName && isset($sale->fees_list[$idx])) {
                    $salesFee += round((float) $sale->fees_list[$idx]);
                }
            }
        }

        return $salesFee;
    }

    public function index(Request $request)
    {
        $query = SalarySlip::with('staff');

        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        }

        if ($request->has('staff_id') && $request->staff_id) {
            $query->where('staff_id', $request->staff_id);
        }

        $salarySlips = $query->orderBy('month', 'desc')->orderBy('created_at', 'desc')->paginate(20);
        $staffs      = Staff::orderBy('nama')->get();

        return view('admin.payroll.index', compact('salarySlips', 'staffs'));
    }

    public function create()
    {
        $staffs = Staff::orderBy('nama')->get();
        return view('admin.payroll.create', compact('staffs'));
    }

    public function store(Request $request, WhatsappService $whatsappService)
    {
        // 1. Validasi Input Form
        $validator = Validator::make($request->all(), [
            'staff_id'                 => 'required|exists:staffs,id',
            'month'                    => 'required|date_format:Y-m',
            'basic_salary'             => 'required|numeric|min:0',
            'transport_food_allowance' => 'nullable|numeric|min:0',
            'overtime_fee'             => 'nullable|numeric|min:0',
            'cash_advance'             => 'nullable|numeric|min:0',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(' | ', $validator->errors()->all()));
        }
    
        DB::beginTransaction();
    
        try {
            $month     = $request->month;
            $staff     = Staff::findOrFail($request->staff_id);
            $startDate = Carbon::parse($month . '-01')->startOfMonth();
            $endDate   = Carbon::parse($month . '-01')->endOfMonth();
    
            // --- A. HITUNG SALES FEE (Dari Instrumen Terjual & Masuk) ---
            // FIX: pencocokan nama sebelumnya case-sensitive (array_search biasa),
            // sehingga "GUNAWAN" (input) tidak match dengan "Gunawan" (master staff).
            // Sekarang dinormalisasi (trim + lowercase) dan di-loop penuh supaya
            // semua kemunculan nama staff pada satu transaksi ikut terhitung,
            // bukan hanya kemunculan pertama seperti array_search().
            $cleanStaffName = $this->normalizeName($staff->nama);
            $salesFee = $this->calculateSalesInstrumentFee($staff->nama, $startDate, $endDate);
    
            $productFee = 0;
            $products = Product::whereBetween('tanggal', [$startDate, $endDate])->with('additionalPics')->get();
            foreach ($products as $product) {
                foreach ($product->additionalPics as $pic) {
                    // FIX: bandingkan nama secara case-insensitive juga
                    if ($this->normalizeName($pic->pic_name) === $cleanStaffName) {
                        $cleanFee = (int) preg_replace('/[^0-9]/', '', $pic->fee_amount ?? '0');
                        $productFee += $cleanFee;
                    }
                }
            }
            $totalSalesFeeCombined = $salesFee + $productFee;
    
            // --- B. HITUNG SERVICE FEE (DIREVISI: Gabungan Teknisi 30% & Penerima 5%) ---
            // FIX: gunakan whereRaw + LOWER(TRIM()) supaya pencocokan nama tidak
            // terpengaruh perbedaan kapitalisasi/spasi, sama seperti fix Sales Fee.
            // 1. Ambil fee sebagai Teknisi (Eksekutor) -> 30%
            $feeSebagaiTeknisi = ServiceHarian::whereRaw('LOWER(TRIM(eksekutor)) = ?', [$cleanStaffName])
                ->whereBetween('tanggal_masuk', [$startDate, $endDate])
                ->sum('fee_staff') ?? 0;
    
            // 2. Ambil fee sebagai Penerima (Frontdesk) -> 5%
            $feeSebagaiPenerima = ServiceHarian::whereRaw('LOWER(TRIM(penerima)) = ?', [$cleanStaffName])
                ->whereBetween('tanggal_masuk', [$startDate, $endDate])
                ->sum('fee_penerima') ?? 0;
    
            // Total akumulasi service fee
            $serviceFee = round((float) ($feeSebagaiTeknisi + $feeSebagaiPenerima));
    
            // --- C. HITUNG PERFORMANCE (Point System) ---
            $attendancePoints = (int) (StaffAttendance::where('staff_id', $staff->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->sum('point_change') ?? 0);
    
            $dailySalesPoints = 0;
            $dailySales = DailySale::where('staff_id', $staff->id)
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->get();
            foreach ($dailySales as $sale) {
                $dailySalesPoints += Carbon::parse($sale->sale_date)->isSameDay($sale->created_at) ? 1 : -1;
            }
    
            $totalPointsAchieved  = $attendancePoints + $dailySalesPoints;
            $performanceIncentive = $totalPointsAchieved * 1000;
    
            // --- D. ADDITIONAL FEE (Input Manual) ---
            $totalOthersFee   = 0;
            $othersFeeDetails = [];
            if ($request->has('others_fee_items') && is_array($request->others_fee_items)) {
                foreach ($request->others_fee_items as $item) {
                    if (!empty($item['name']) && isset($item['amount'])) {
                        $amount = round((float) $item['amount']);
                        $othersFeeDetails[] = ['name' => $item['name'], 'amount' => $amount];
                        $totalOthersFee += $amount;
                    }
                }
            }
    
            // --- E. KOMPONEN UTAMA ---
            $basicSalary        = round((float) ($request->basic_salary ?? 0));
            $transportAllowance = round((float) ($request->transport_food_allowance ?? 0));
            $overtimeFee        = round((float) ($request->overtime_fee ?? 0));
            $cashAdvance        = round((float) ($request->cash_advance ?? 0));
    
            $totalEarnings = $basicSalary + $transportAllowance + $overtimeFee
                + $totalSalesFeeCombined + $serviceFee + $totalOthersFee
                + $performanceIncentive;
    
            // --- F. SIMPAN DATA ---
            $salarySlip = SalarySlip::updateOrCreate(
                ['staff_id' => $staff->id, 'month' => $month],
                [
                    'basic_salary'             => $basicSalary,
                    'transport_food_allowance' => $transportAllowance,
                    'overtime_fee'             => $overtimeFee,
                    'sales_fee'                => $totalSalesFeeCombined,
                    'service_fee'              => $serviceFee, // Nilai gabungan 30% + 5%
                    'others_fee'               => $totalOthersFee,
                    'others_fee_details'       => $othersFeeDetails,
                    'cash_advance'             => $cashAdvance,
                    'cash_advance_notes'       => $request->cash_advance_notes ?? '',
                    'performance_incentive'    => $performanceIncentive,
                    'total_points'             => $totalPointsAchieved,
                    'total'                    => $totalEarnings,
                    'bank_name'                => $staff->bank_name ?? '-',
                    'bank_account'             => $staff->bank_account ?? '-',
                    'account_name'             => $staff->account_name ?? '-',
                ]
            );
    
            DB::commit();
    
            // --- G. NOTIFIKASI WHATSAPP ---
            try {
                if ($staff->nomor_telepon) {
                    $thp = number_format($totalEarnings - $cashAdvance, 0, ',', '.');
                    $monthName = Carbon::parse($month . '-01')->translatedFormat('F Y');
                    $downloadLink = config('app.url') . "/admin/payroll/{$salarySlip->id}/download";
    
                    $message = "Halo *{$staff->nama}* 👋\n\n Slip Gaji Anda untuk periode *{$monthName}* telah diterbitkan.\n\n" .
                        "• Periode: {$monthName}\n• Take Home Pay: *Rp {$thp}*\n\n🔗 {$downloadLink}\n\nTerima kasih! 🙏\n\n". "> _Pesan dikirim otomatis dari sistem Musicmen Store_";
    
                    $whatsappService->sendMessage($staff->nomor_telepon, $message);
                }
            } catch (\Exception $e) {
                Log::warning('WhatsApp Gaji Gagal Terkirim: ' . $e->getMessage());
            }
    
            return redirect()->route('admin.payroll.index')
                ->with('success', "Slip gaji {$staff->nama} periode {$month} berhasil diterbitkan!");
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payroll Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        $salarySlip = SalarySlip::with('staff')->findOrFail($id);
        return view('admin.payroll.show', compact('salarySlip'));
    }

    public function edit($id)
    {
        $salarySlip = SalarySlip::with('staff')->findOrFail($id);
        $staffs     = Staff::orderBy('nama')->get();
        return view('admin.payroll.edit', compact('salarySlip', 'staffs'));
    }

    public function update(Request $request, $id)
    {
        // 1. Cari data slip gaji
        $salarySlip = SalarySlip::findOrFail($id);

        // 2. Validasi input form
        $request->validate([
            'basic_salary'             => 'required|numeric|min:0',
            'transport_food_allowance' => 'nullable|numeric|min:0',
            'overtime_fee'             => 'nullable|numeric|min:0',
            'cash_advance'             => 'nullable|numeric|min:0',
        ]);

        try {
            // 3. Hitung ulang Additional Fee (Multi-item)
            $totalOthersFee = 0;
            $othersFeeDetails = [];

            if ($request->has('others_fee_items') && is_array($request->others_fee_items)) {
                foreach ($request->others_fee_items as $item) {
                    if (!empty($item['name']) && isset($item['amount'])) {
                        // Paksa ke angka bulat untuk presisi
                        $amount = round((float) $item['amount']);
                        $othersFeeDetails[] = [
                            'name'   => $item['name'],
                            'amount' => $amount
                        ];
                        $totalOthersFee += $amount;
                    }
                }
            }

            // 4. Ambil nilai komponen tetap dan bulatkan (Mencegah angka 19.998)
            $basicSalary        = round((float) $request->basic_salary);
            $transportAllowance = round((float) ($request->transport_food_allowance ?? 0));
            $overtimeFee        = round((float) ($request->overtime_fee ?? 0));
            $cashAdvance        = round((float) ($request->cash_advance ?? 0));

            // 5. Kalkulasi Total Gross (Pendapatan Kotor)
            // Catatan: sales_fee, service_fee, dan performance_incentive 
            // biasanya tidak diubah manual di form edit, jadi kita ambil dari data lama
            $totalEarnings = $basicSalary
                + $transportAllowance
                + $overtimeFee
                + round((float) $salarySlip->sales_fee)
                + round((float) $salarySlip->service_fee)
                + $totalOthersFee
                + round((float) $salarySlip->performance_incentive);

            // 6. Jalankan Update ke Database
            $salarySlip->update([
                'basic_salary'             => $basicSalary,
                'transport_food_allowance' => $transportAllowance,
                'overtime_fee'             => $overtimeFee,
                'others_fee'               => $totalOthersFee,
                'others_fee_details'       => $othersFeeDetails, // Otomatis tersimpan sebagai JSON
                'cash_advance'             => $cashAdvance,
                'cash_advance_notes'       => $request->cash_advance_notes ?? '',
                'total'                    => $totalEarnings,
            ]);

            return redirect()->route('admin.payroll.index')
                ->with('success', 'Slip gaji ' . $salarySlip->staff->nama . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Payroll Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function downloadPdf($id)
    {
        $salarySlip = SalarySlip::with('staff')->findOrFail($id);
        $pdf        = Pdf::loadView('admin.payroll.pdf', compact('salarySlip'));
        return $pdf->download('salary-slip-' . $salarySlip->staff->nama . '-' . $salarySlip->month . '.pdf');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $salarySlip = SalarySlip::findOrFail($id);
        $salarySlip->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Slip berhasil dihapus!']);
        }

        return redirect()->route('admin.payroll.index')->with('success', 'Slip gaji berhasil dihapus!');
    }

    public function sendAutoWa($id, WhatsappService $whatsappService)
    {
        $salarySlip = SalarySlip::with('staff')->findOrFail($id);

        if (!$salarySlip->staff->nomor_telepon) {
            return response()->json(['success' => false, 'message' => 'Nomor WA staff tidak ditemukan.']);
        }

        $monthName    = Carbon::parse($salarySlip->month . '-01')->format('F Y');
        $totalGaji    = number_format($salarySlip->total, 0, ',', '.');
        $downloadLink = config('app.url') . "/admin/payroll/{$salarySlip->id}/download";

        // Format Pesan Sesuai Contoh
        $message = "Halo *{$salarySlip->staff->nama}* 👋\n\n" .
            "Slip Gaji Anda untuk periode *{$monthName}* sudah siap.\n\n" .
            "*Rincian:*\n" .
            "• Periode: {$monthName}\n" .
            "• Total Gaji: Rp {$totalGaji}\n\n" .
            "Silakan download slip gaji Anda melalui link berikut:\n" .
            "{$downloadLink}\n\n" .
            "Terima kasih! 🙏 \n\n". 
            "> _Pesan dikirim otomatis dari sistem Musicmen Store_";

        $response = $whatsappService->sendMessage($salarySlip->staff->nomor_telepon, $message);
        $resArray = json_decode($response, true);

        return response()->json(
            isset($resArray['status']) && $resArray['status']
                ? ['success' => true,  'message' => 'WhatsApp berhasil dikirim!']
                : ['success' => false, 'message' => 'Gagal: ' . ($resArray['reason'] ?? 'Server Error')]
        );
    }

    public function previewSalesFee(Request $request)
    {
        $staffName = $request->get('staff_name');
        $month     = $request->get('month');

        if (!$staffName || !$month) {
            return response()->json(['sales' => [], 'total_fee' => 0]);
        }

        $salesData   = [];
        $productData = [];
        $totalFee    = 0;
        $y = date('Y', strtotime($month . '-01'));
        $m = date('m', strtotime($month . '-01'));

        // FIX: pencocokan nama dinormalisasi (trim + lowercase) supaya konsisten
        // dengan logic di store(), dan semua kemunculan nama pada satu transaksi
        // ikut dihitung (loop penuh, bukan array_search yang berhenti di kecocokan pertama).
        $cleanStaffName = $this->normalizeName($staffName);

        foreach (SalesInstrument::whereYear('tanggal', $y)->whereMonth('tanggal', $m)->orderBy('tanggal', 'desc')->get() as $sale) {
            $salesList = $sale->sales_list;
            $feesList  = $sale->fees_list;

            foreach ($salesList as $idx => $namaSales) {
                if ($this->normalizeName($namaSales) === $cleanStaffName && isset($feesList[$idx])) {
                    $fee       = $feesList[$idx];
                    $totalFee += $fee;
                    $salesData[] = [
                        'nama_barang' => $sale->nama_barang,
                        'tanggal'     => Carbon::parse($sale->tanggal)->format('d M Y'),
                        'fee'         => $fee,
                        'source'      => 'Sales',
                    ];
                }
            }
        }

        foreach (Product::whereYear('tanggal', $y)->whereMonth('tanggal', $m)->with('additionalPics')->orderBy('tanggal', 'desc')->get() as $product) {
            foreach ($product->additionalPics as $pic) {
                if ($this->normalizeName($pic->pic_name) === $cleanStaffName) {
                    $fee         = (int) preg_replace('/[^0-9]/', '', $pic->fee_amount ?? '0');
                    $totalFee   += $fee;
                    $productData[] = [
                        'nama_barang' => $product->nama_barang,
                        'tanggal'     => Carbon::parse($product->tanggal)->format('d M Y'),
                        'fee'         => $fee,
                        'source'      => 'Instrumen Masuk',
                    ];
                }
            }
        }

        return response()->json(['sales' => array_merge($salesData, $productData), 'total_fee' => $totalFee]);
    }
}
