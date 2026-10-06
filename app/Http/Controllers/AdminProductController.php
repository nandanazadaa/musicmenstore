<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Staff;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminProductController extends Controller
{
    // List Products
    // app/Http/Controllers/AdminProductController.php

    public function index(Request $request)
    {
        session(['products_last_viewed' => now()->toISOString()]);
        $query = Product::query();

        // --- LOGIC DEFAULT BULAN INI ---
        $dateStart = $request->input('date_start', now()->startOfMonth()->toDateString());
        $dateEnd   = $request->input('date_end', now()->endOfMonth()->toDateString());

        $query->whereBetween('tanggal', [$dateStart, $dateEnd]);

        // Search & Filter lainnya (tetap sama)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_barang', 'like', "%{$search}%");
        }

        // --- Hitung Stats Global Berdasarkan Filter ---
        // Gunakan query agregat agar halaman list tidak memuat semua produk ke memory.
        $stats = [
            'total_unit'    => (clone $query)->count(),
            'total_deposit' => (clone $query)->sum('harga_pembelian'),
            'total_fee'     => \App\Models\ProductPic::whereHas('product', function ($q) use ($dateStart, $dateEnd) {
                $q->whereBetween('tanggal', [$dateStart, $dateEnd]);
            })->sum('fee_amount')
        ];

        $products = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.products.index', compact('products', 'stats', 'dateStart', 'dateEnd'));
    }

    // Add to AdminProductController class
    public function checkPendingProducts(Request $request)
    {
        $lastCheck = $request->input('last_check', now()->subMinutes(5)->toISOString());

        // Get last viewed time from session
        $lastViewed = session('products_last_viewed', $lastCheck);

        // Get products created by staff that were created after last check
        // Products with input_system = 'Belum' are considered "pending" (need to be input to system)
        $newProducts = Product::where('created_at', '>', $lastCheck)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Count products that need to be input to system (input_system = 'Belum')
        // Only count products created AFTER admin last viewed the page
        $count = Product::where('input_system', 'Belum')
            ->where('created_at', '>', $lastViewed)
            ->count();

        return response()->json([
            'count' => $count,
            'new_products' => $newProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'nama_barang' => $product->nama_barang,
                    'created_at' => $product->created_at->format('d M Y H:i'),
                ];
            })
        ]);
    }

    /**
     * Get new products for real-time updates
     */
    public function getNewProducts(Request $request)
    {
        $lastUpdate = $request->input('last_update', now()->subMinutes(5)->toDateTimeString());

        // Get all new/updated products
        $query = Product::where(function ($q) use ($lastUpdate) {
            $q->where('created_at', '>', $lastUpdate)
                ->orWhere('updated_at', '>', $lastUpdate);
        });

        // Apply filters if provided
        $search = $request->input('search', '');
        $type = $request->input('type', '');
        $kondisi = $request->input('kondisi', '');
        $input_system = $request->input('input_system', '');

        if ($search != '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kelengkapan', 'like', "%{$search}%")
                    ->orWhere('harga_pembelian', 'like', "%{$search}%")
                    ->orWhere('fee', 'like', "%{$search}%");
            });
        }

        if ($type != '') {
            $query->where('type', $type);
        }

        if ($kondisi != '') {
            $query->where('kondisi', $kondisi);
        }

        if ($input_system != '') {
            $query->where('input_system', $input_system);
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'tanggal' => $product->tanggal ? \Carbon\Carbon::parse($product->tanggal)->format('d M Y') : '-',
                    'nama_barang' => $product->nama_barang,
                    'kelengkapan' => \Illuminate\Support\Str::limit($product->kelengkapan ?? '-', 30),
                    'harga_pembelian' => $product->harga_pembelian ?? '-',
                    'fee' => $product->fee ?? '-',
                    'type' => $product->type,
                    'pic' => $product->pic ?? '-',
                    'input_system' => $product->input_system ?? 'Belum',
                    'created_at' => $product->created_at->format('d M Y H:i'),
                    'created_at_raw' => $product->created_at->toDateTimeString(),
                ];
            }),
            'last_update' => now()->toDateTimeString(),
        ]);
    }

    // Show Create Form
    public function create()
    {
        $staffs = Staff::all();
        return view('admin.products.create', compact('staffs'));
    }

    // Store New Product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'                 => ['required', 'string', 'max:255'],
            'nomor_seri'                  => ['nullable', 'string', 'max:255', 'unique:products,nomor_seri'],
            'tempat_pembuatan'            => ['nullable', 'string', 'max:255'],
            'tahun_pembuatan'             => ['nullable', 'string', 'max:10'],
            'warna'                       => ['nullable', 'string', 'max:255'],
            'type'                        => ['required', 'in:electric,acoustic,effect,amplifier,bass'],
            'harga_pembelian'             => ['nullable', 'numeric', 'min:0'],
            'tanggal'                     => ['required', 'date'],
            'kelengkapan'                 => ['required', 'string'],
            'kondisi'                     => ['nullable', 'in:new,great,good,bad,need service'],
            'catatan'                     => ['nullable', 'string'],
            'keterangan'                  => ['nullable', 'string'],
            'additional_pics'             => ['nullable', 'array'],
            'additional_pics.*'           => ['nullable', 'string', 'max:255'],
            'additional_fees'             => ['nullable', 'array'],
            'additional_fees.*'           => ['nullable', 'numeric', 'min:0'],
        ], [
            'nomor_seri.unique' => 'Serial number tersebut sudah digunakan.',
        ]);

        $picNames = $validated['additional_pics'] ?? [];
        $picFees = $validated['additional_fees'] ?? [];
        unset($validated['additional_pics'], $validated['additional_fees']);

        try {
            $product = DB::transaction(function () use ($validated, $picNames, $picFees) {
                $data = $validated;

                // Nilai default harus sesuai enum pada tabel products.
                $data['nomor_seri']       = $data['nomor_seri'] ?? null;
                $data['tempat_pembuatan'] = $data['tempat_pembuatan'] ?? null;
                $data['tahun_pembuatan']  = $data['tahun_pembuatan'] ?? null;
                $data['warna']            = $data['warna'] ?? null;
                $data['kondisi']          = $data['kondisi'] ?? 'good';
                $data['catatan']          = $data['catatan'] ?? null;
                $data['keterangan']       = $data['keterangan'] ?? null;
                $data['harga_pembelian']  = $data['harga_pembelian'] ?? 0;
                $data['input_cashier']    = 'Pending';
                $data['price_hologram']   = 'Pending';
                $data['setting_setup']    = 'Pending';
                $data['input_system']     = 'Belum';

                $baseSlug = Str::slug($data['nama_barang']);
                $slug = $baseSlug;
                $count = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $data['slug'] = $slug;

                $map = [
                    // Kolom legacy `instrumen` menggunakan enum lowercase.
                    'electric' => 'elektrik',
                    'acoustic' => 'akustik',
                    'bass' => 'bass',
                ];
                // Untuk effect/amplifier, kolom `type` menjadi sumber kategori utama.
                $data['instrumen'] = $map[$data['type']] ?? null;
                $data['pic'] = 'Admin';

                $product = Product::create($data);

                foreach ($picNames as $index => $picName) {
                    if (filled($picName)) {
                        $product->additionalPics()->create([
                            'pic_name' => $picName,
                            'fee_amount' => $picFees[$index] ?? 0,
                        ]);
                    }
                }

                return $product;
            });

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk "' . $product->nama_barang . '" berhasil ditambahkan.');
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan produk dari panel admin.', [
                'user_id' => Auth::id(),
                'exception' => $e,
            ]);

            // Jangan tampilkan SQL mentah ke pengguna, tetapi berikan petunjuk
            // yang cukup agar masalah di database hosting bisa langsung dikenali.
            $errorText = strtolower($e->getMessage());
            $userMessage = match (true) {
                str_contains($errorText, 'instrumen') || str_contains($errorText, 'data truncated')
                    => 'Struktur kategori di database hosting belum sesuai dengan aplikasi. Jalankan migration terbaru di Hostinger.',
                str_contains($errorText, 'product_pics')
                    => 'Tabel PIC belum tersedia di database hosting. Jalankan migration terbaru di Hostinger.',
                str_contains($errorText, 'unknown column') || str_contains($errorText, 'doesn\'t exist')
                    => 'Struktur tabel products di hosting belum lengkap. Jalankan migration terbaru di Hostinger.',
                str_contains($errorText, 'duplicate entry')
                    => 'Nomor seri atau slug tersebut sudah digunakan oleh data lain.',
                default
                    => 'Produk belum tersimpan. Periksa log Laravel di Hostinger untuk detail error database.',
            };

            return back()->withInput()->withErrors([
                'store' => $userMessage,
            ]);
        }
    }
    // Show Edit Form
    // Show Edit Form
    public function edit($id)
    {
        // Eager load additionalPics agar data fee muncul
        $product = Product::with(['images', 'additionalPics'])->findOrFail($id);

        // Ambil data staff (cukup satu kali penulisan)
        $staffs = Staff::all();

        return view('admin.products.edit', compact('product', 'staffs'));
    }

    // Update Product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Validasi semua field yang mungkin dikirim
        $validated = $request->validate([
            'nama_barang'      => 'required|string|max:255',
            'type'             => 'required',
            'tanggal'          => 'required|date',
            'harga_pembelian'  => 'nullable|numeric',
            'tempat_pembuatan' => 'nullable|string',
            'tahun_pembuatan'  => 'nullable|string',
            'warna'            => 'nullable|string',
            'kondisi'          => 'nullable|string',
            'kelengkapan'      => 'required|string',
            'catatan'          => 'nullable|string',
            'keterangan'       => 'nullable|string',
            'setting_setup'    => 'nullable|string',
            'price_hologram'   => 'nullable|string',
            'input_cashier'    => 'nullable|string',
            'additional_pics'  => 'nullable|array',
            'additional_pics.*' => 'nullable|string|max:255',
            'additional_fees'  => 'nullable|array',
            'additional_fees.*' => 'nullable|numeric|min:0',
        ]);

        // 2. Pisahkan data utama dan data PIC
        $picNames = $validated['additional_pics'] ?? [];
        $picFees = $validated['additional_fees'] ?? [];
        unset($validated['additional_pics'], $validated['additional_fees']);
        $data = $validated;

        // 3. Logika Slug (Update jika nama berubah)
        if ($validated['nama_barang'] !== $product->nama_barang) {
            $baseSlug = Str::slug($validated['nama_barang']);
            $slug = $baseSlug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            $data['slug'] = $slug;
        }

        // 4. Update Data Utama
        $product->update($data);

        // 5. Sinkronisasi Multiple PIC & FEE
        if (!empty($picNames)) {
            // Hapus data PIC lama agar tidak duplikat
            $product->additionalPics()->delete();

            // Simpan ulang dari form
            foreach ($picNames as $index => $picName) {
                if (filled($picName)) {
                    $product->additionalPics()->create([
                        'pic_name'   => $picName,
                        'fee_amount' => $picFees[$index] ?? 0
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Data instrumen berhasil diperbarui oleh Admin!');
    }

    // 4. Update Data Utama (nomor_seri, warna, setting_setup, input_cashier, dll)


    // Delete Product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete image if exists
        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product berhasil dihapus!');
    }

    // Export Products to Excel
    public function export(Request $request)
    {
        // Tangkap filter dari request
        $filters = [
            'date_start' => $request->query('date_start'),
            'date_end'   => $request->query('date_end'),
            'search'     => $request->query('search'),
            'type'       => $request->query('type'),
        ];

        // Berikan nama file yang dinamis berdasarkan filter
        $fileName = 'products-export';
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $fileName .= '-' . $request->date_start . '-to-' . $request->date_end;
        }
        $fileName .= '.xlsx';

        // Kirim filter ke class ProductsExport
        return Excel::download(new ProductsExport($filters), $fileName);
    }

    /**
     * Mark products as viewed (clear notification badge)
     */
    public function markViewed()
    {
        // Mark that admin has viewed products page
        session(['products_last_viewed' => now()->toISOString()]);

        return response()->json([
            'success' => true,
            'message' => 'Products marked as viewed'
        ]);
    }
}
