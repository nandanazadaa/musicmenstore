<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StaffProductController extends Controller
{
    // app/Http/Controllers/StaffProductController.php

    public function index(Request $request)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $staff = Staff::where('user_id', Auth::id())->first();
        $query = Product::query();

        // --- LOGIC DEFAULT BULAN INI ---
        $dateStart = $request->input('date_start', now()->startOfMonth()->toDateString());
        $dateEnd   = $request->input('date_end', now()->endOfMonth()->toDateString());

        // Terapkan Filter Tanggal
        $query->whereBetween('tanggal', [$dateStart, $dateEnd]);

        // Filter Berdasarkan Staff yang Login
        $query->where(function ($q) use ($staff) {
            $q->where('pic', $staff->nama)
                ->orWhereHas('additionalPics', function ($query) use ($staff) {
                    $query->where('pic_name', $staff->nama);
                });
        });

        // Search functionality (Tetap sama)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('nomor_seri', 'like', "%{$search}%");
            });
        }

        // Filter by type & kondisi (Tetap sama)
        if ($request->filled('type')) $query->where('type', $request->type);

        // --- Hitung Ringkasan (Stats) berdasarkan filter yang aktif ---
        // Jangan load semua produk hanya untuk statistik; cukup agregasi di database.
        $stats = [
            'total_unit'    => (clone $query)->count(),
            'total_deposit' => (clone $query)->sum('harga_pembelian'),
            'total_fee'     => \App\Models\ProductPic::where('pic_name', $staff->nama)
                ->whereHas('product', function ($q) use ($dateStart, $dateEnd) {
                    $q->whereBetween('tanggal', [$dateStart, $dateEnd]);
                })->sum('fee_amount')
        ];

        $products = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('staff.products.index', compact('products', 'stats', 'dateStart', 'dateEnd'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        // Ambil data staff dari database untuk dropdown PIC
        $staffs = Staff::all();

        // Kirim variabel $staffs ke view
        return view('staff.products.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'type'        => 'required',
            'kelengkapan' => 'required',
        ]);

        try {
            // 1. Ambil data HANYA yang ada kolomnya di tabel products
            // Kita exclude staff_fee, additional_pics, dan additional_fees agar tidak menyebabkan error SQL
            $data = $request->except(['staff_fee', 'additional_pics', 'additional_fees']);

            // 2. Setup PIC Utama (Staff yang login)
            $staff = Staff::where('user_id', Auth::id())->first();
            $data['pic'] = $staff ? $staff->nama : Auth::user()->name;

            // 3. Gabungkan data default & formatting
            $data['tanggal']          = now()->toDateString();
            $data['harga_pembelian']  = round($request->harga_pembelian ?? 0);
            $data['nomor_seri']       = $request->nomor_seri ?? '-';
            $data['tempat_pembuatan'] = $request->tempat_pembuatan ?? '-';
            $data['tahun_pembuatan']  = $request->tahun_pembuatan ?? '-';
            $data['warna']            = $request->warna ?? '-';
            $data['catatan']          = $request->catatan ?? '-';
            $data['keterangan']       = $request->keterangan ?? '-';
            $data['kondisi']          = $request->kondisi ?? 'good';

            // 4. Hitung Total Fee dari inputan multiple untuk kolom 'fee' di tabel products
            $totalFee = 0;
            if ($request->has('additional_fees')) {
                foreach ($request->additional_fees as $f) {
                    $totalFee += round((float)$f);
                }
            }
            $data['fee'] = $totalFee;

            // 5. Mapping Nama Instrumen
            $map = [
                // Kolom legacy `instrumen` menggunakan enum lowercase.
                'electric' => 'elektrik',
                'acoustic' => 'akustik',
                'bass'     => 'bass',
            ];
            $data['instrumen'] = $map[$request->type] ?? null;

            // 6. Status Default System
            $data['input_system']   = 'Belum';
            // Sesuai enum products: status awal disimpan sebagai Pending.
            $data['setting_setup']  = 'Pending';
            $data['price_hologram'] = 'Pending';
            $data['input_cashier']  = 'Pending';
            $data['slug']           = Str::slug($request->nama_barang) . '-' . time();

            // 7. SIMPAN KE TABEL PRODUCTS (Utama)
            // Karena sudah menggunakan except(), error "Unknown Column" tidak akan muncul lagi
            $product = Product::create($data);

            // 8. SIMPAN KE TABEL ADDITIONAL PICS (Relasi)
            if ($request->has('additional_pics') && $product) {
                foreach ($request->additional_pics as $index => $picName) {
                    // Hanya simpan jika nama PIC tidak kosong
                    if (!empty($picName)) {
                        $product->additionalPics()->create([
                            'pic_name'   => $picName,
                            'fee_amount' => round((float)($request->additional_fees[$index] ?? 0))
                        ]);
                    }
                }
            }

            return redirect()->route('staff.products.index')->with('success', 'Unit dan Rincian Fee berhasil disimpan.');
        } catch (\Exception $e) {
            // Jika masih error, pesan ini akan memberitahu detailnya
            return redirect()->back()->withInput()->with('error', 'Gagal Simpan: ' . $e->getMessage());
        }
    }

    public function checkup(Request $request, $id)
    {
        // 1. Validasi input dari Modal Checkup
        $request->validate([
            'kondisi'        => 'required|in:new,great,good,need service',
            'setting_setup'  => 'required|in:Pending,Done',
            'price_hologram' => 'required|in:Pending,Done',
            'input_cashier'  => 'required|in:Pending,Done',
            'input_system'   => 'required|in:Belum,Sudah',
        ], [
            'kondisi.required' => 'Pilih kondisi unit terlebih dahulu.',
            'input_system.required' => 'Tentukan apakah data ingin di-lock (Completed) atau tetap Pending.',
        ]);

        try {
            // 2. Cari data produk
            $product = Product::findOrFail($id);

            // 3. Jalankan Update Data
            $product->update([
                'kondisi'        => $request->kondisi,
                'setting_setup'  => $request->setting_setup,
                'price_hologram' => $request->price_hologram,
                'input_cashier'  => $request->input_cashier,
                'input_system'   => $request->input_system, // Jika diset 'Sudah', maka tombol 'Check Now' hilang
            ]);

            // 4. Berikan feedback sukses
            $message = $request->input_system === 'Sudah'
                ? 'Unit "' . $product->nama_barang . '" berhasil di-checkup dan data telah DIKUNCI.'
                : 'Progress checkup unit "' . $product->nama_barang . '" berhasil disimpan sebagai Draft.';

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            // Log error jika diperlukan
            Log::error("Checkup Error: " . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        $product = Product::with('images')->findOrFail($id);

        return view('staff.products.show', compact('product'));
    }

    // app/Http/Controllers/StaffProductController.php

    public function edit($id)
    {
        $user = Auth::user();
        $product = Product::with('additionalPics')->findOrFail($id);
        $staff = Staff::where('user_id', $user->id)->first();

        // 1. Admin selalu punya akses
        if ($user->role === 'admin') {
            $staffs = Staff::all();
            return view('staff.products.edit', compact('product', 'staffs'));
        }

        // 2. Cek apakah staff terdaftar sebagai PIC di produk ini
        $isRegisteredPIC = $staff && $product->additionalPics->contains('pic_name', $staff->nama);
        $isCreator = $staff && $product->pic === $staff->nama;

        if (!$isRegisteredPIC && !$isCreator) {
            return redirect()->route('staff.products.index')
                ->with('error', 'Akses ditolak. Anda bukan salah satu PIC yang ditugaskan untuk instrumen ini.');
        }

        $staffs = Staff::all();
        return view('staff.products.edit', compact('product', 'staffs'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama_barang'      => 'required|string|max:255',
            'type'             => 'required',
            'harga_pembelian'  => 'nullable|numeric',
            'kelengkapan'      => 'required|string',
        ]);

        $data = $request->except(['additional_pics', 'additional_fees']);

        // PERBAIKAN: Default nilai jika inputan opsional dikosongkan
        $data['harga_pembelian']  = $request->harga_pembelian ?? 0;
        $data['tempat_pembuatan'] = $request->tempat_pembuatan ?? '';
        $data['tahun_pembuatan']  = $request->tahun_pembuatan ?? '';
        $data['warna']            = $request->warna ?? '';
        $data['catatan']          = $request->catatan ?? '';
        $data['keterangan']       = $request->keterangan ?? '';

        // Update Slug jika nama berubah
        if ($request->nama_barang !== $product->nama_barang) {
            $baseSlug = Str::slug($request->nama_barang);
            $slug = $baseSlug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            $data['slug'] = $slug;
        }

        // 4. Jalankan Update Produk
        $product->update($data);

        // 5. Sinkronisasi PIC & Fee (Staff Mode)
        if ($request->has('additional_pics')) {
            // Hapus data fee lama instrumen ini
            $product->additionalPics()->delete();

            // Simpan data fee baru dari inputan staff
            foreach ($request->additional_pics as $index => $picName) {
                if (!empty($picName)) {
                    $product->additionalPics()->create([
                        'pic_name'   => $picName,
                        'fee_amount' => $request->additional_fees[$index] ?? 0
                    ]);
                }
            }
        }

        return redirect()->route('staff.products.index')->with('success', 'Perubahan data instrumen berhasil disimpan!');
    }

    public function inventoryIndex(Request $request)
    {
        // Tambahkan with('additionalPics') agar data PIC & Fee ikut terbawa ke View
        $query = Product::with('additionalPics');

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', "%{$request->search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('staff.inventory.index', compact('products'));
    }
}
