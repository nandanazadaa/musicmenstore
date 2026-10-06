<?php

namespace App\Http\Controllers;

use App\Models\SalesInstrument;
use App\Models\Staff;
use App\Exports\SalesInstrumentExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSalesController extends Controller
{
    // app/Http/Controllers/AdminSalesController.php

    // app/Http/Controllers/AdminSalesController.php

    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        if (Auth::user()->role === 'admin') {
            session(['sales-instruments_last_viewed' => now()->toISOString()]);
        }

        $query = SalesInstrument::query();

        // --- LOGIC AUTO-RESET BULAN INI ---
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to', now()->endOfMonth()->toDateString());

        $query->whereBetween('tanggal', [$dateFrom, $dateTo]);

        // Search & Filter (Tetap sama)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // --- HITUNG STATISTIK GLOBAL (SELURUH SALES) ---
        // Count cukup dari database. Untuk total_fee tetap perlu accessor JSON,
        // jadi ambil kolom fee saja agar memory jauh lebih kecil.
        $feeRows = (clone $query)->get(['jumlah_fee']);

        $stats = [
            'total_unit' => (clone $query)->count(),
            'total_fee'  => $feeRows->sum('total_fee'), // Mengambil accessor total_fee dari model
        ];

        $sales = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.sales.index', compact('sales', 'stats', 'dateFrom', 'dateTo'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $staffs = Staff::orderBy('nama')->get();

        return view('admin.sales.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string',
            'sales' => 'required|array',
            'fees' => 'required|array',
            'type' => 'required',
        ]);

        // Bersihkan format fee (hapus titik/koma)
        $cleanFees = array_map(function ($val) {
            return str_replace(['.', ','], '', $val);
        }, $request->fees);

        SalesInstrument::create([
            'tanggal' => $request->tanggal,
            'nama_barang' => $request->nama_barang,
            'type' => $request->type,
            'sales' => json_encode($request->sales),
            'jumlah_fee' => json_encode($cleanFees),
            'keterangan' => $request->keterangan,
            'kondisi' => $request->kondisi,
        ]);

        return redirect()->route('admin.sales.index')->with('success', 'Data berhasil disimpan!');
    }

    public function show(string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $sale = SalesInstrument::findOrFail($id);
        return view('admin.sales.show', compact('sale'));
    }

    public function edit(string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $sale = SalesInstrument::findOrFail($id);
        $staffs = Staff::orderBy('nama')->get();

        return view('admin.sales.edit', compact('sale', 'staffs'));
    }

    public function update(Request $request, string $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $sale = SalesInstrument::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'sales' => 'required|array',
            'fees' => 'required|array',
            'type' => 'required',
        ]);

        // Bersihkan format fee
        $cleanFees = array_map(function ($val) {
            return str_replace(['.', ','], '', $val);
        }, $request->fees);

        $sale->update([
            'tanggal' => $request->tanggal,
            'nama_barang' => $request->nama_barang,
            'type' => $request->type,
            'sales' => json_encode($request->sales),
            'jumlah_fee' => json_encode($cleanFees),
            'keterangan' => $request->keterangan,
            'kondisi' => $request->kondisi,
        ]);

        return redirect()->route('admin.sales.index')->with('success', 'Data sales berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $sale = SalesInstrument::findOrFail($id);
        $sale->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data sales instrument berhasil dihapus!'
            ]);
        }

        return redirect()->route('admin.sales.index')
            ->with('success', 'Data sales instrument berhasil dihapus!');
    }

    public function export()
    {
        return Excel::download(new SalesInstrumentExport, 'sales-instruments-' . date('Y-m-d') . '.xlsx');
    }
}
