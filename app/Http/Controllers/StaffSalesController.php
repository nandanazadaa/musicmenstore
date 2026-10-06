<?php

namespace App\Http\Controllers;

use App\Models\SalesInstrument;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffSalesController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        $query = SalesInstrument::query();

        // --- LOGIC DEFAULT BULAN INI ---
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to', now()->endOfMonth()->toDateString());

        $query->whereBetween('tanggal', [$dateFrom, $dateTo]);

        // Filter Search (Tetap sama)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter by type (Tetap sama)
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // --- HITUNG RINGKASAN (STATS) UNTUK STAFF ---
        // Count cukup dari database. Untuk total_fee tetap perlu accessor JSON,
        // jadi ambil kolom fee saja agar memory tetap kecil.
        $feeRows = (clone $query)->get(['jumlah_fee']);

        $stats = [
            'total_unit' => (clone $query)->count(),
            'total_fee'  => $feeRows->sum('total_fee'), // Menggunakan accessor getTotalFeeAttribute di model
        ];

        $sales = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('staff.sales.index', compact('sales', 'staff', 'stats', 'dateFrom', 'dateTo'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        // PERBAIKAN: Ambil data semua staff untuk dropdown
        $staffs = Staff::orderBy('nama')->get();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        // PERBAIKAN: Kirim $staffs ke view
        return view('staff.sales.create', compact('staff', 'staffs'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'tanggal'     => 'required|date',
            'nama_barang' => 'required|string',
            'sales'       => 'required|array',
            'fees'        => 'required|array',
            'type'        => 'required',
        ]);

        // 2. BERSIHKAN NAMA SALES DARI SPASI GAIB
        $cleanSales = array_filter(array_map(function ($val) {
            return !empty($val) ? trim((string)$val) : null;
        }, $request->sales));

        // 3. BERSIHKAN TITIK/KOMA DARI SETIAP FEE
        $cleanFees = array_map(function ($val) {
            return str_replace(['.', ','], '', $val);
        }, $request->fees);

        // Pasangkan index agar jika ada sales kosong tidak bergeser nilainya
        $cleanSales = array_values($cleanSales);
        $cleanFees  = array_slice($cleanFees, 0, count($cleanSales));

        // 4. Simpan data ke database
        SalesInstrument::create([
            'tanggal'     => $request->tanggal,
            'nama_barang' => $request->nama_barang,
            'type'        => $request->type,
            'sales'       => json_encode($cleanSales), // Simpan dalam kondisi super bersih
            'jumlah_fee'  => json_encode($cleanFees),
            'keterangan'  => $request->keterangan,
            'kondisi'     => $request->kondisi,
        ]);

        return redirect()->route('staff.sales.index')->with('success', 'Data berhasil disimpan!');
    }

    public function show($id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $sale = SalesInstrument::findOrFail($id);

        return view('staff.sales.show', compact('sale'));
    }

    public function edit($id)
    {
        $sale = SalesInstrument::findOrFail($id);
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();
        $staffs = Staff::orderBy('nama')->get(); // Untuk dropdown repeater

        // Parse sales list (Handle JSON atau String manual)
        $salesList = json_decode($sale->sales, true);
        if (!is_array($salesList)) {
            $salesList = $sale->sales ? explode(', ', $sale->sales) : [];
        }
        $salesList = array_map('trim', $salesList);

        // LOGIKA: Admin boleh edit, atau Staff yang namanya ada di list boleh edit
        $hasAccess = ($user->role === 'admin') || ($staff && in_array($staff->nama, $salesList));

        if (!$hasAccess) {
            return redirect()->route('staff.sales.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit data ini. Hanya PIC yang tercantum yang dapat mengedit.');
        }

        return view('staff.sales.edit', compact('sale', 'staff', 'staffs'));
    }

    public function update(Request $request, $id)
    {
        // 1. Cari data SalesInstrument
        $sale = SalesInstrument::findOrFail($id);
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        // 2. Validasi field
        $request->validate([
            'tanggal'     => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'sales'       => 'required|array',
            'fees'        => 'required|array',
            'type'        => 'required',
        ]);

        // 3. Keamanan: Cek izin edit
        $salesList = json_decode($sale->sales, true) ?: [];
        if (!is_array($salesList)) {
            $salesList = $sale->sales ? explode(', ', $sale->sales) : [];
        }
        $cleanExistingSales = array_map('trim', $salesList);
        $hasAccess = ($user->role === 'admin') || ($staff && in_array(trim($staff->nama), $cleanExistingSales));

        if (!$hasAccess) {
            return redirect()->route('staff.sales.index')->with('error', 'Akses ditolak.');
        }

        // 4. BERSIHKAN NAMA SALES BARU DARI SPASI GAIB
        $cleanSales = array_filter(array_map(function ($val) {
            return !empty($val) ? trim((string)$val) : null;
        }, $request->sales));

        // 5. Bersihkan nominal fee dari titik
        $cleanFees = array_map(function ($val) {
            return str_replace(['.', ','], '', $val);
        }, $request->fees);

        $cleanSales = array_values($cleanSales);
        $cleanFees  = array_slice($cleanFees, 0, count($cleanSales));

        // 6. Update data
        $sale->update([
            'tanggal'     => $request->tanggal,
            'nama_barang' => $request->nama_barang,
            'type'        => $request->type,
            'sales'       => json_encode($cleanSales), // Di-update dalam kondisi super bersih
            'jumlah_fee'  => json_encode($cleanFees),
            'keterangan'  => $request->keterangan,
            'kondisi'     => $request->kondisi,
        ]);

        return redirect()->route('staff.sales.index')->with('success', 'Data sales berhasil diperbarui!');
    }
}
