<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\Staff;
use App\Exports\DailySalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminDailySalesController extends Controller
{
    // app/Http/Controllers/AdminDailySalesController.php

    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        session(['daily-sales_last_viewed' => now()->toISOString()]);

        $query = DailySale::with('staff');

        // --- LOGIC DEFAULT BULAN INI ---
        // Jika input date_from atau date_to kosong, otomatis ambil rentang bulan berjalan
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to', now()->endOfMonth()->toDateString());

        // Terapkan filter tanggal ke query utama
        $query->whereBetween('sale_date', [$dateFrom, $dateTo]);

        // --- FILTER TAMBAHAN ---
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        // --- HITUNG RINGKASAN (STATS) BERDASARKAN FILTER ---
        $stats = [
            'total_sales'   => (clone $query)->sum(DB::raw('total_offline_sales + total_online_sales')),
            'total_deposit' => (clone $query)->sum('total_cash_deposit'),
            'total_laporan' => (clone $query)->count(),
        ];

        $dailySales = $query->orderBy('sale_date', 'desc')->paginate(20)->withQueryString();
        $staffs = Staff::orderBy('nama')->get();

        // Kirim dateFrom dan dateTo ke view agar input tanggal terisi otomatis
        return view('admin.daily-sales.index', compact('dailySales', 'staffs', 'stats', 'dateFrom', 'dateTo'));
    }

    public function show($id)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $dailySale = DailySale::with('staff')->findOrFail($id);

        return view('admin.daily-sales.show', compact('dailySale'));
    }

    public function edit($id)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $dailySale = DailySale::with('staff')->findOrFail($id);
        $staffs = Staff::orderBy('nama')->get();

        return view('admin.daily-sales.edit', compact('dailySale', 'staffs'));
    }

    public function update(Request $request, $id)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $dailySale = DailySale::findOrFail($id);

        // Validasi minimal
        $request->validate([
            'sale_date' => 'nullable|date',
            'shift' => 'nullable|string',
        ]);

        try {
            $data = [
                'sale_date' => $request->sale_date ?? $dailySale->sale_date,
                'shift' => $request->shift ?? $dailySale->shift,
                'shift_start' => $request->shift_start ? \Carbon\Carbon::parse($request->shift_start)->format('H:i:s') : null,
                'shift_end' => $request->shift_end ? \Carbon\Carbon::parse($request->shift_end)->format('H:i:s') : null,
                'total_offline_sales' => $request->total_offline_sales ? (float)$request->total_offline_sales : $dailySale->total_offline_sales,
                'total_online_sales' => $request->total_online_sales ? (float)$request->total_online_sales : $dailySale->total_online_sales,
                'shopee_sales' => $request->shopee_sales ? (float)$request->shopee_sales : $dailySale->shopee_sales,
                'tokopedia_sales' => $request->tokopedia_sales ? (float)$request->tokopedia_sales : $dailySale->tokopedia_sales,
                'cash' => $request->cash ? (float)$request->cash : $dailySale->cash,
                'qris' => $request->qris ? (float)$request->qris : $dailySale->qris,
                'transfer' => $request->transfer ? (float)$request->transfer : $dailySale->transfer,
                'cash_notes' => $request->cash_notes ?? $dailySale->cash_notes,
                'qris_notes' => $request->qris_notes ?? $dailySale->qris_notes,
                'transfer_notes' => $request->transfer_notes ?? $dailySale->transfer_notes,
                'cash_amount' => $request->cash_amount ? (float)$request->cash_amount : $dailySale->cash_amount,
                'expenses' => $request->expenses ? (float)$request->expenses : $dailySale->expenses,
                'cash_for_next_shift' => $request->cash_for_next_shift ? (float)$request->cash_for_next_shift : $dailySale->cash_for_next_shift,
                'total_cash_deposit' => $request->total_cash_deposit ? (float)$request->total_cash_deposit : $dailySale->total_cash_deposit,
                'notes' => $request->notes ?? $dailySale->notes,
                'statement' => $request->statement ?? $dailySale->statement,
            ];

            $dailySale->update($data);

            return redirect()->route('admin.daily-sales.index')->with('success', 'Form penjualan harian berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating daily sale: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        try {
            $dailySale = DailySale::findOrFail($id);
            $dailySale->delete();

            return redirect()->route('admin.daily-sales.index')->with('success', 'Form penjualan harian berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting daily sale: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function getNewDailySales(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lastUpdate = $request->input('last_update');

        // Gunakan select() untuk mengurangi beban memori RAM server
        $dailySales = DailySale::with(['staff:id,nama']) // Hanya ambil id dan nama staff
            ->select('id', 'staff_id', 'sale_date', 'shift', 'total_offline_sales', 'total_online_sales', 'total_cash_deposit', 'created_at', 'updated_at')
            ->where(function ($query) use ($lastUpdate) {
                $query->where('created_at', '>', $lastUpdate)
                    ->orWhere('updated_at', '>', $lastUpdate);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10) // Batasi jumlah data yang ditarik secara real-time
            ->get()
            ->map(function ($dailySale) {
                return [
                    'id' => $dailySale->id,
                    'staff_name' => $dailySale->staff->nama,
                    'sale_date' => $dailySale->sale_date->format('d M Y'),
                    'shift' => ucfirst($dailySale->shift ?? '-'),
                    // Hitung grand total di server agar JS tidak berat
                    'total_sales' => number_format($dailySale->total_offline_sales + $dailySale->total_online_sales, 0, ',', '.'),
                    'cash_deposit' => number_format($dailySale->total_cash_deposit, 0, ',', '.'),
                    'created_at' => $dailySale->created_at->format('d M Y H:i'),
                    'updated_at' => $dailySale->updated_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'daily_sales' => $dailySales,
            'last_update' => now()->toDateTimeString(),
        ]);
    }

    // Export Daily Sales to Excel
    public function export()
    {
        $fileName = 'laporan-penjualan-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new DailySalesExport, $fileName);
    }
}
