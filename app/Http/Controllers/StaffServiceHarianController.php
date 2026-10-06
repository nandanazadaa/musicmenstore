<?php

namespace App\Http\Controllers;

use App\Models\ServiceHarian;
use App\Models\Staff;
use App\Models\WhatsAppMessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\WhatsappService;


class StaffServiceHarianController extends Controller
{
    /**
     * 1. INPUT SERVICE (Tampilan Frontdesk)
     * Menampilkan daftar service yang diinput oleh staff ini
     */
    public function kelolaIndex(Request $request)
    {
        $staffLogin = Staff::where('user_id', Auth::id())->firstOrFail();
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // 1. Query TABEL (Hanya tugas teknisi login)
        $query = ServiceHarian::where('eksekutor', $staffLogin->nama)
            ->whereMonth('tanggal_masuk', $month)
            ->whereYear('tanggal_masuk', $year);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                    ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // 2. Query STATISTIK PERSONAL (Bulan Terpilih)
        $myMonthlyStats = ServiceHarian::where('eksekutor', $staffLogin->nama)
            ->whereMonth('tanggal_masuk', $month)
            ->whereYear('tanggal_masuk', $year)
            ->get();

        $myTotalJasa = $myMonthlyStats->where('status_pengerjaan', 'selesai')->sum('biaya_jasa');
        $myTotalBonus = $myMonthlyStats->sum('fee_staff');

        return view('staff.service-harian.kelola-index', compact('services', 'staffLogin', 'myTotalJasa', 'myTotalBonus', 'month', 'year'));
    }

    /**
     * 2. KELOLA SERVICE (Tampilan Teknisi)
     * Menampilkan daftar service yang ditugaskan kepada teknisi ini
     */
    // File: App\Http\Controllers\StaffServiceHarianController.php

    public function inputIndex(Request $request)
    {
        $staffLogin = Staff::where('user_id', Auth::id())->firstOrFail();

        // Ambil Filter Bulan & Tahun (Default ke bulan & tahun sekarang)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // 1. Query untuk TABEL (Semua Staff bisa lihat history)
        $query = ServiceHarian::whereMonth('tanggal_masuk', $month)
            ->whereYear('tanggal_masuk', $year);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                    ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // 2. Query STATISTIK PERSONAL (Khusus Staff Login pada bulan terpilih)
        $myStats = ServiceHarian::where('penerima', $staffLogin->nama)
            ->whereMonth('tanggal_masuk', $month)
            ->whereYear('tanggal_masuk', $year)
            ->get();

        $myOmset = $myStats->sum('biaya_jasa');
        $myBonus = $myStats->sum('fee_penerima');

        return view('staff.service-harian.input-index', compact('services', 'staffLogin', 'myOmset', 'myBonus', 'month', 'year'));
    }

    /**
     * Tampilan form create untuk Frontdesk
     */
    public function create()
    {
        $staff = $this->getAuthenticatedStaff();
        $instrumentOptions = $this->getInstrumentOptions();
        // Ambil semua staff untuk dipilih sebagai Teknisi (Eksekutor)
        $allStaffs = Staff::orderBy('nama')->get();

        return view('staff.service-harian.create', compact('instrumentOptions', 'staff', 'allStaffs'));
    }

    /**
     * Simpan data awal dari Frontdesk
     */
    // Bagian method store di StaffServiceHarianController.php
    // File: App\Http\Controllers\StaffServiceHarianController.php

    public function store(Request $request)
    {
        try {
            // ... (validasi tetap sama)
            $request->validate([
                'nama_customer' => 'required|string|max:255',
                'whatsapp'      => 'required|numeric',
                'merk'          => 'required|string|max:255',
                'tipe'          => 'nullable|string|max:255',
                'instrumen'     => 'required|string',
                'jenis_service' => 'required|string',
                'eksekutor'     => 'required|string',
            ]);

            $staff = Staff::where('user_id', Auth::id())->firstOrFail();

            $service = ServiceHarian::create([
                // ... (field create tetap sama)
                'tanggal_masuk'     => now(),
                'nama_customer'     => $request->nama_customer,
                'whatsapp'          => $request->whatsapp,
                'merk'              => $request->merk,
                'tipe'              => $request->tipe,
                'instrumen'         => $request->instrumen,
                'jenis_service'     => $request->jenis_service,
                'eksekutor'         => $request->eksekutor,
                'penerima'          => $staff->nama,
                'status_pengerjaan' => 'pending',
                'status_pengambilan' => 'Belum diambil',
                'biaya_sparepart'   => 0,
                'biaya_jasa'        => 0,
                'total_harga'       => 0,
            ]);

            // --- FORMAT PESAN PROSES SESUAI CONTOH ---
            $wa = new WhatsappService();
            $tglMasuk = date('d F Y', strtotime($service->tanggal_masuk));
            $unit = $service->merk . ' ' . ($service->tipe ?? '');

            $pesan = "Halo *{$service->nama_customer}*,\n\n" .
                "Terima kasih telah mempercayakan service *{$unit}* Anda kepada kami.\n\n" .
                "_*Detail Service:*_\n" .
                "• Tanggal Masuk: [{$tglMasuk}]\n" .
                "• Jenis Service: [{$service->jenis_service}]\n" .
                "• Status: *Proses pengerjaan*\n\n" .
                "Gear Anda saat ini sedang ditangani oleh teknisi. Kami akan menginformasikan kembali setelah proses service selesai atau jika ada update.\n\n" .
                "Terima kasih!\n\n" .
                "> _Pesan dikirim otomatis dari sistem Musicmen Store_";

            $wa->sendMessage($service->whatsapp, $pesan);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan dan notifikasi proses telah dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendManualWa(Request $request)
    {
        // Validasi data yang dikirim dari JavaScript
        $phone = $request->phone;
        $name = $request->name;
        $unit = $request->unit;

        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Nomor HP tidak ditemukan.']);
        }

        // Panggil Service WhatsApp
        $wa = new \App\Services\WhatsappService();

        // Template pesan Follow Up
        $message = "Halo *{$name}* 👋\n\nKami dari *Musicmen Store* ingin menginfokan bahwa unit *{$unit}* Anda saat ini sudah masuk dalam antrean teknisi kami.\n\nMohon ditunggu kabar selanjutnya ya. Terima kasih! 🙏\n\n" .
        "> _Pesan dikirim otomatis dari sistem Musicmen Store_";

        $res = $wa->sendMessage($phone, $message);
        $resArray = json_decode($res, true);

        // Cek respon dari Fonnte
        if (isset($resArray['status']) && $resArray['status'] == true) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => $resArray['reason'] ?? 'Gagal mengirim pesan melalui provider.'
        ]);
    }
    /**
     * Form edit untuk Teknisi memperbarui progress
     */
    public function edit($id)
    {
        $staff = $this->getAuthenticatedStaff();
        $service = ServiceHarian::where('id', $id)
            ->where(function ($q) use ($staff) {
                $q->where('eksekutor', $staff->nama)
                    ->orWhere('penerima', $staff->nama);
            })->firstOrFail();

        return view('staff.service-harian.edit', compact('service', 'staff'));
    }

    /**
     * Teknisi update biaya dan status selesai
     */
    // File: App\Http\Controllers\StaffServiceHarianController.php

    // File: App\Http\Controllers\StaffServiceHarianController.php

    public function update(Request $request, $id)
    {
        $service = ServiceHarian::findOrFail($id);
    
        // 1. Ambil rincian dari form dan filter yang ada nominalnya
        $rincianJasa = collect($request->jasa)->filter(fn($item) => !empty($item['amount']))->values()->all();
        $rincianSparepart = collect($request->sparepart)->filter(fn($item) => !empty($item['amount']))->values()->all();
    
        $totalJasa = collect($rincianJasa)->sum('amount');
        $totalSparepart = collect($rincianSparepart)->sum('amount');
        $totalHarga = $totalJasa + $totalSparepart;
    
        $statusLama = $service->status_pengerjaan;
    
        // 2. Simpan data ke database
        $service->update([
            'status_pengerjaan'  => $request->status_pengerjaan,
            'biaya_jasa'         => $totalJasa,
            'biaya_sparepart'    => $totalSparepart,
            'fee_penerima'       => $totalJasa * 0.05,
            'fee_staff'          => $totalJasa * 0.30,
            'total_harga'        => $totalHarga,
            'status_pengambilan' => $request->status_pengambilan,
            'keterangan'         => $request->keterangan ?? '-',
            'rincian_jasa'       => $rincianJasa,
            'rincian_sparepart'  => $rincianSparepart,
            'tanggal_selesai'    => ($request->status_pengerjaan == 'selesai') ? now() : $service->tanggal_selesai,
        ]);
    
        // 3. KIRIM WA DENGAN RINCIAN DETAIL
        if ($statusLama !== 'selesai' && $request->status_pengerjaan === 'selesai') {
            try {
                $wa = new \App\Services\WhatsappService();
                $unit = $service->merk . ' ' . ($service->tipe ?? '');
                $tglMasuk = date('d/m/Y', strtotime($service->tanggal_masuk));
    
                // --- LOGIKA MENYUSUN RINCIAN BIAYA ---
                $textRincian = "";
                
                // Tambahkan rincian Jasa
                foreach ($rincianJasa as $j) {
                    $textRincian .= "• " . ($j['name'] ?? 'Jasa Service') . " : Rp " . number_format($j['amount'], 0, ',', '.') . "\n";
                }
                
                // Tambahkan rincian Sparepart
                foreach ($rincianSparepart as $s) {
                    $textRincian .= "• " . ($s['name'] ?? 'Sparepart') . " : Rp " . number_format($s['amount'], 0, ',', '.') . "\n";
                }
    
                // --- SUSUN PESAN FINAL ---
                $pesanSelesai = "Halo *{$service->nama_customer}*,\n\n" .
                    "Kami informasikan bahwa service {$unit} Anda telah selesai dan sudah siap untuk diambil.\n\n" .
                    "Detail Service:\n" .
                    "• Tanggal Masuk : {$tglMasuk}\n" .
                    "• Jenis Service : {$service->jenis_service}\n\n" .
                    "Rincian Biaya:\n" .
                    $textRincian . "\n" . // Rincian detail masuk di sini
                    "Total Biaya : *Rp " . number_format($totalHarga, 0, ',', '.') . "*\n\n" .
                    "Pengambilan dapat dilakukan pada jam operasional store pukul 09.00-21.00 WIB.\n\n" .
                    "Thanks Men 🙏\n\n" .
                    "> _Pesan dikirim otomatis dari sistem Musicmen Store_";
    
                $wa->sendMessage($service->whatsapp, $pesanSelesai);
            } catch (\Exception $e) {
                Log::error("Gagal kirim WA Selesai: " . $e->getMessage());
            }
        }
    
        return redirect()->route('staff.service-harian.kelola-index')->with('success', 'Data berhasil diperbarui dan WA terkirim!');
    }


    /**
     * WhatsApp Template Helper
     */
    // Tambahkan di dalam class StaffServiceHarianController

    // public function getWhatsAppMessage(Request $request)
    // {
    //     $data = $request->all();
    //     $status = strtolower($data['status_pengerjaan'] ?? 'proses');

    //     $namaCustomer = $data['nama_customer'] ?? '';
    //     $merk         = $data['merk'] ?? '';
    //     $tipe         = $data['tipe'] ?? '';
    //     $totalHarga   = number_format($data['total_harga'] ?? 0, 0, ',', '.');

    //     if ($status === 'selesai') {
    //         // Template untuk status SELESAI
    //         $message = "Halo *{$namaCustomer}* 👋\n\n"
    //             . "Kami ingin menginformasikan bahwa servis instrumen Anda telah *SELESAI* ✅\n\n"
    //             . "🎸 *Unit:* {$merk} {$tipe}\n"
    //             . "💰 *Total Tagihan: Rp {$totalHarga}*\n\n"
    //             . "Instrumen Anda sudah bisa diambil di toko kami sekarang.\n\n"
    //             . "Terima kasih telah mempercayakan servis Anda kepada kami 🙏\n"
    //             . "— *Musicmen Store*";
    //     } else {
    //         // Template untuk status PROSES (saat input baru)
    //         $message = "Halo *{$namaCustomer}* 👋\n\n"
    //             . "Terima kasih telah mempercayakan servis instrumen Anda kepada kami.\n\n"
    //             . "🎸 *Unit:* {$merk} {$tipe}\n"
    //             . "⚙️ *Status: SEDANG DIPROSES*\n\n"
    //             . "Kami akan segera menghubungi Anda kembali apabila pekerjaan telah selesai.\n\n"
    //             . "Terima kasih 🙏\n"
    //             . "— *Musicmen Store*";
    //     }

    //     return response()->json(['success' => true, 'message' => $message]);
    // }

    /**
     * Private Helper: Ambil data profil staff yang login
     */
    private function getAuthenticatedStaff()
    {
        return Staff::where('user_id', Auth::id())->firstOrFail();
    }

    private function getInstrumentOptions()
    {
        return [
            'electric' => 'Electric Guitar',
            'acoustic' => 'Acoustic Guitar',
            'bass'     => 'Bass',
            'effect'   => 'Effect',
            'amplifier' => 'Amplifier',
        ];
    }
}
