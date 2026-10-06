<?php

namespace App\Http\Controllers;

use App\Models\ServiceHarian;
use App\Models\Staff;
use App\Models\LandingPageProduct;
use App\Models\WhatsAppMessageTemplate;
use App\Exports\ServiceHarianExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminServiceHarianController extends Controller
{
    // app/Http/Controllers/AdminServiceHarianController.php

    public function index(Request $request)
    {
        session(['service-harian_last_viewed' => now()->toISOString()]);

        $query = ServiceHarian::query();

        // --- LOGIC DEFAULT BULAN INI ---
        // Jika tidak difilter, otomatis ambil range tanggal 1 s/d hari ini di bulan berjalan
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to', now()->endOfMonth()->toDateString());

        // Terapkan filter tanggal ke query utama
        $query->whereDate('tanggal_masuk', '>=', $dateFrom)
            ->whereDate('tanggal_masuk', '<=', $dateTo);

        // --- Pencarian & Filter Tambahan ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                    ->orWhere('merk', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status_pengerjaan')) {
            $query->where('status_pengerjaan', $request->status_pengerjaan);
        }

        // --- HITUNG RINGKASAN (STATS) BERDASARKAN QUERY YANG SUDAH TERFILTER ---
        $statsQuery = clone $query;

        $stats = [
            'total_omset'    => $statsQuery->sum('total_harga'),
            'total_jasa'     => $statsQuery->sum('biaya_jasa'),
            'total_sparepart' => $statsQuery->sum('biaya_sparepart'),
            'total_fee_front' => $statsQuery->sum('fee_penerima'),
            'total_fee_tech' => $statsQuery->sum('fee_staff'),
            'total_unit'     => $statsQuery->count(),
        ];

        $services = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Kirim dateFrom dan dateTo ke view agar input tanggal terisi otomatis
        return view('admin.service-harian.index', compact('services', 'stats', 'dateFrom', 'dateTo'));
    }
    /**
     * Method SHOW untuk mengatasi error "Call to undefined method"
     */
    public function show($id)
    {
        $service = ServiceHarian::findOrFail($id);
        // Anda bisa mengarahkan ke view detail jika ada, 
        // atau untuk sementara redirect ke edit
        return view('admin.service-harian.edit', [
            'service' => $service,
            'instrumentOptions' => $this->getInstrumentOptions(),
            'staffs' => Staff::orderBy('nama')->get(['id', 'nama'])
        ]);
    }

    public function getNewServices(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $lastUpdate = $request->input('last_update');
        $query = ServiceHarian::orderBy('created_at', 'desc');

        if ($lastUpdate) {
            $query->where('created_at', '>', $lastUpdate);
        } else {
            $query->limit(5);
        }

        return response()->json([
            'success' => true,
            'services' => $query->get(),
            'last_update' => now()->toIso8601String()
        ]);
    }

    public function updateFee(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate(['biaya_jasa' => 'required|numeric|min:0']);

        $service = ServiceHarian::findOrFail($id);
        $service->biaya_jasa = $request->biaya_jasa;

        // REKAP FEE KEDUANYA
        $service->fee_penerima = $request->biaya_jasa * 0.05; // 5% Frontdesk
        $service->fee_staff = $request->biaya_jasa * 0.30;    // 30% Teknisi

        $service->total_harga = ($service->biaya_sparepart ?? 0) + $request->biaya_jasa;
        $service->save();

        return response()->json(['success' => true]);
    }

    public function create()
    {
        $instrumentOptions = $this->getInstrumentOptions();
        $staffs = Staff::orderBy('nama')->get(['id', 'nama']);
        return view('admin.service-harian.create', compact('instrumentOptions', 'staffs'));
    }

    // File: AdminServiceHarianController.php

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $biayaJasa = $data['biaya_jasa'] ?? 0;
        $biayaSparepart = $data['biaya_sparepart'] ?? 0;

        // Perhitungan Fee Baru
        $data['fee_penerima'] = $biayaJasa * 0.05; // Staff Depan 5%
        $data['fee_staff'] = $biayaJasa * 0.30;    // Teknisi 30%
        $data['total_harga'] = $biayaSparepart + $biayaJasa;

        // Simpan siapa yang input (Frontdesk)
        $data['penerima'] = Auth::user()->name;

        ServiceHarian::create($data);
        return redirect()->route('admin.service-harian.index')->with('success', 'Service berhasil diinput.');
    }

    public function edit($id)
    {
        $service = ServiceHarian::findOrFail($id);
        $instrumentOptions = $this->getInstrumentOptions();
        $staffs = Staff::orderBy('nama')->get(['id', 'nama']);
        return view('admin.service-harian.edit', compact('service', 'instrumentOptions', 'staffs'));
    }

    public function update(Request $request, $id)
    {
        $service = ServiceHarian::findOrFail($id);
        $data = $this->validateData($request, $service->id);

        $biayaJasa = $request->biaya_jasa ?? 0;
        $biayaSparepart = $request->biaya_sparepart ?? 0;

        // RE-CALCULATE ALL FEES
        $data['fee_penerima'] = $biayaJasa * 0.05; // 5% untuk frontdesk
        $data['fee_staff']    = $biayaJasa * 0.30; // 30% untuk teknisi
        $data['total_harga']  = $biayaSparepart + $biayaJasa;

        $service->update($data);
        return redirect()->route('admin.service-harian.index')->with('success', 'Data service berhasil diperbarui.');
    }

    public function destroy($id)
    {
        ServiceHarian::findOrFail($id)->delete();
        return redirect()->route('admin.service-harian.index')->with('success', 'Service harian berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new ServiceHarianExport, 'service-harian-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Get WhatsApp message for service harian
     */
    public function getWhatsAppMessage(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $data = $request->all();

        // Format tanggal
        $tanggalMasuk = $data['tanggal_masuk'] ?? '';

        // Get message template and format it
        $message = WhatsAppMessageTemplate::formatMessage('service_harian', [
            'nama_customer' => $data['nama_customer'] ?? '',
            'merk' => $data['merk'] ?? '',
            'tipe' => $data['tipe'] ?? '',
            'instrumen' => $data['instrumen'] ?? '',
            'jenis_service' => $data['jenis_service'] ?? '',
            'tanggal_masuk' => $tanggalMasuk,
            'status_pengerjaan' => $data['status_pengerjaan'] ?? 'Proses',
        ]);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    private function validateData(Request $request, $id = null)
    {
        return $request->validate([
            'tanggal_masuk' => 'required|date',
            'nama_customer' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'merk' => 'required|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'instrumen' => 'required|string|max:255',
            'jenis_service' => 'required|string|max:255',
            'biaya_sparepart' => 'nullable|numeric|min:0',
            'biaya_jasa' => 'nullable|numeric|min:0',
            'eksekutor' => 'nullable|string|max:255',
            'status_pengerjaan' => 'required|in:proses,selesai',
            'tanggal_selesai' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'status_pengambilan' => 'required|in:Belum diambil,Sudah diambil',
        ]);
    }

    private function getInstrumentOptions()
    {
        // Return semua kategori instrumen seperti di input instrumen
        return [
            'electric' => 'Electric Guitar',
            'acoustic' => 'Acoustic Guitar',
            'bass' => 'Bass',
            'effect' => 'Effect',
            'amplifier' => 'Amplifier',
        ];
    }
}
