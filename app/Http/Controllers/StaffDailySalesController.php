<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StaffDailySalesController extends Controller
{
    // app/Http/Controllers/StaffDailySalesController.php

    // app/Http/Controllers/StaffDailySalesController.php

    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $staff = Staff::where('user_id', $user->id)->firstOrFail();
        $query = DailySale::where('staff_id', $staff->id);

        // --- LOGIC DEFAULT BULAN INI ---
        // Jika input date_from atau date_to kosong, otomatis ambil rentang bulan berjalan
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to', now()->endOfMonth()->toDateString());

        // Terapkan filter ke query
        $query->whereBetween('sale_date', [$dateFrom, $dateTo]);

        // --- Hitung Ringkasan (Stats) berdasarkan filter tanggal ---
        $stats = [
            'total_poin'    => $staff->points, // Poin tetap total akumulasi permanen (tidak ikut filter)
            'total_sales'   => (clone $query)->sum(DB::raw('total_offline_sales + total_online_sales')),
            'total_deposit' => (clone $query)->sum('total_cash_deposit'),
            'total_laporan' => (clone $query)->count(),
        ];

        $dailySales = $query->orderBy('sale_date', 'desc')->paginate(20)->withQueryString();

        return view('staff.daily-sales.index', compact('dailySales', 'staff', 'stats', 'dateFrom', 'dateTo'));
    }

    public function create()
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) return redirect()->back()->with('error', 'Profil tidak ditemukan.');

        $today = \Carbon\Carbon::today();
        $weekStart = $today->copy()->startOfWeek()->format('Y-m-d');

        // Optimasi: Gabungkan pengambilan schedule dalam satu query
        $schedules = \App\Models\ShiftSchedule::where('staff_id', $staff->id)
            ->where('week_start_date', $weekStart)
            ->get();

        $todaySchedule = $schedules->where('day', strtolower($today->format('l')))->first();
        $scheduledShifts = $schedules->pluck('shift')->unique()->toArray();

        // Ambil shift times hanya kolom yang diperlukan
        $availableShifts = \App\Models\ShiftTime::select('shift', 'start_time', 'end_time')
            ->when(!empty($scheduledShifts), function ($q) use ($scheduledShifts) {
                return $q->whereIn('shift', $scheduledShifts);
            })
            ->get();

        return view('staff.daily-sales.create', compact('staff', 'todaySchedule', 'availableShifts'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $staff = \App\Models\Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        $request->validate([
            'sale_date' => 'required|date',
            'shift' => 'required|string',
        ]);

        try {
            return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $staff) {
                $data = [
                    'staff_id' => $staff->id,
                    'sale_date' => $request->sale_date,
                    'shift' => $request->shift,
                    'shift_start' => $request->shift_start ? \Carbon\Carbon::parse($request->shift_start)->format('H:i:s') : null,
                    'shift_end' => $request->shift_end ? \Carbon\Carbon::parse($request->shift_end)->format('H:i:s') : null,
                    'total_offline_sales' => (float)($request->total_offline_sales ?? 0),
                    'total_online_sales' => (float)($request->total_online_sales ?? 0),
                    'shopee_sales' => (float)($request->shopee_sales ?? 0),
                    'tokopedia_sales' => (float)($request->tokopedia_sales ?? 0),
                    'cash' => (float)($request->cash ?? 0),
                    'qris' => (float)($request->qris ?? 0),
                    'transfer' => (float)($request->transfer ?? 0),
                    'cash_amount' => (float)($request->cash_amount ?? 500000),
                    'expenses' => (float)($request->expenses ?? 0),
                    'cash_for_next_shift' => (float)($request->cash_for_next_shift ?? 500000),
                    'total_cash_deposit' => (float)($request->total_cash_deposit ?? 0),
                    'notes' => $request->notes,
                    'statement' => $request->statement,
                ];

                $existingSale = DailySale::where('staff_id', $staff->id)
                    ->where('sale_date', $data['sale_date'])
                    ->where('shift', $data['shift'])
                    ->first();

                if ($existingSale) {
                    $existingSale->update($data);
                    $message = 'Laporan penjualan berhasil diperbarui!';
                } else {
                    DailySale::create($data);

                    // LOGIKA POIN: Input laporan tepat waktu (hari ini) dapat +1
                    // Jika input tanggal kemarin (rapelan) dapat -1
                    $saleDate = \Carbon\Carbon::parse($data['sale_date']);
                    $today = \Carbon\Carbon::today();

                    $pointChange = $saleDate->isSameDay($today) ? 1 : -1;

                    // Update poin di tabel staffs untuk tampilan dashboard
                    $staff->increment('points', $pointChange);

                    $message = "Laporan penjualan berhasil disimpan! Poin Anda: " . ($pointChange > 0 ? '+1' : '-1');
                }

                return redirect()->route('staff.daily-sales.index')->with('success', $message);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        $dailySale = DailySale::where('id', $id)
            ->where('staff_id', $staff->id)
            ->firstOrFail();

        return view('staff.daily-sales.show', compact('dailySale', 'staff'));
    }
}
