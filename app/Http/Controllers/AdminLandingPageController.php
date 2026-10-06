<?php

namespace App\Http\Controllers;

use App\Models\LandingPageSetting;
use App\Models\Brand;
use App\Models\LandingPageProduct;
use App\Models\Gallery;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use App\Models\Service;

class AdminLandingPageController extends Controller
{
    private function uploadImageAsWebp($file, string $directory, string $basename, int $quality = 82): string
    {
        return ImageUploadService::saveAsWebp($file, $directory, $basename, $quality);
    }

    private function imageUploadMessages(): array
    {
        return [
            'image.uploaded' => 'Gambar gagal diupload. Biasanya ukuran file melewati limit server. Maksimal yang disarankan 10 MB per gambar.',
            'images.*.uploaded' => 'Salah satu gambar gagal diupload. Biasanya ukuran file melewati limit server. Maksimal yang disarankan 10 MB per gambar.',
            'logo.uploaded' => 'Logo gagal diupload. Biasanya ukuran file melewati limit server. Maksimal yang disarankan 10 MB.',
            'slider_images.*.uploaded' => 'Salah satu banner/slider gagal diupload. Biasanya ukuran file melewati limit server. Maksimal yang disarankan 10 MB per gambar.',
            'payment_image.uploaded' => 'Gambar pembayaran gagal diupload. Biasanya ukuran file melewati limit server. Maksimal yang disarankan 10 MB.',
            'image.max' => 'Ukuran gambar maksimal 10 MB.',
            'images.*.max' => 'Ukuran setiap gambar tambahan maksimal 10 MB.',
            'logo.max' => 'Ukuran logo maksimal 10 MB.',
            'slider_images.*.max' => 'Ukuran setiap banner/slider maksimal 10 MB.',
            'payment_image.max' => 'Ukuran gambar pembayaran maksimal 10 MB.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, GIF, SVG, atau WebP.',
            'images.*.mimes' => 'Format gambar tambahan harus JPG, JPEG, PNG, GIF, SVG, atau WebP.',
            'logo.mimes' => 'Format logo harus JPG, JPEG, PNG, GIF, SVG, atau WebP.',
            'slider_images.*.mimes' => 'Format banner/slider harus JPG, JPEG, PNG, GIF, SVG, atau WebP.',
            'payment_image.mimes' => 'Format gambar pembayaran harus JPG, JPEG, PNG, SVG, atau WebP.',
        ];
    }

    /**
     * Display Header section edit page
     */
    public function header()
    {
        $logoPath = LandingPageSetting::getValue('header', 'logo', 'images/logo4.png');
        $logo = asset($logoPath);
        return view('admin.landing.header', compact('logo'));
    }

    /**
     * Update Header section
     */
    public function updateHeader(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        if ($request->hasFile('logo')) {
            LandingPageSetting::setValue('header', 'logo', $this->uploadImageAsWebp($request->file('logo'), 'images', 'header-logo-' . time()));
        }

        return redirect()->route('admin.landing.header')
            ->with('success', 'Header logo updated successfully!');
    }

    /**
     * Display Main section edit page
     */
    public function main()
    {
        $logoPath = LandingPageSetting::getValue('main', 'logo', 'images/logo1.png');
        $logo = asset($logoPath);
        $subtitle = LandingPageSetting::getValue('main', 'subtitle', 'Your Destination for Authentic Guitars');

        // Get slider images (stored as JSON)
        $sliderImagesJson = LandingPageSetting::getValue('main', 'slider_images', null);

        // Ganti URL Unsplash dengan path lokal asset images/sliderX.jpeg
        $sliderImages = $sliderImagesJson ? json_decode($sliderImagesJson, true) : [
            asset('images/slider1.jpeg'),
            asset('images/slider2.jpeg'),
            asset('images/slider3.jpeg'),
            asset('images/slider4.jpeg'),
            asset('images/slider5.jpeg'),
        ];

        return view('admin.landing.main', compact('logo', 'subtitle', 'sliderImages'));
    }

    /**
     * Update Main section
     */
    public function updateMain(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'subtitle' => 'nullable|string|max:255',
            'slider_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'slider_urls.*' => 'nullable|url|max:500',
        ], $this->imageUploadMessages());

        if ($request->hasFile('logo')) {
            LandingPageSetting::setValue('main', 'logo', $this->uploadImageAsWebp($request->file('logo'), 'images', 'main-logo-' . time()));
        }

        if ($request->has('subtitle')) {
            LandingPageSetting::setValue('main', 'subtitle', $request->subtitle);
        }

        // Handle slider images
        $sliderImages = [];

        // Process URL inputs first (for existing URLs or new URLs)
        if ($request->has('slider_urls')) {
            foreach ($request->slider_urls as $url) {
                if (!empty($url)) {
                    $sliderImages[] = $url;
                }
            }
        }

        // Process uploaded images (add to existing)
        if ($request->hasFile('slider_images')) {
            foreach ($request->file('slider_images') as $index => $file) {
                if ($file && $file->isValid()) {
                    $sliderImages[] = asset($this->uploadImageAsWebp($file, 'images', 'slider-' . time() . '-' . $index));
                }
            }
        }

        // Save slider images as JSON (only if we have images)
        if (!empty($sliderImages)) {
            LandingPageSetting::setValue('main', 'slider_images', json_encode($sliderImages));
        }

        return redirect()->route('admin.landing.main')
            ->with('success', 'Main section updated successfully!');
    }

    /**
     * Display About section edit page
     */
    public function about()
    {
        $logoPath = LandingPageSetting::getValue('about', 'logo', 'images/logo-about.png');
        $logo = asset($logoPath);
        $description = LandingPageSetting::getValue('about', 'description', 'Musicmen Store is a destination for guitar and bass enthusiasts who prioritize quality, authenticity, and a premium shopping experience. Based in Yogyakarta, we focus on original instrument selections—from premium second-hand units, rare items, to curated product lines chosen with high standards.

Every instrument at Musicmen goes through a meticulous selection process, detailed inspection, and professional setup to ensure that every guitar and bass not only looks perfect but is also ready to play with its best performance.

More than just a transaction place, Musicmen Store is a space that brings trust and comfort. We are committed to building long-term relationships with players, collectors, and the music community through friendly, transparent, and consistent service.

With the philosophy that every instrument has its own character and story, we help musicians find the right musical instrument—one that aligns with their playing style, needs, and musical journey.');

        return view('admin.landing.about', compact('logo', 'description'));
    }

    /**
     * Update About section
     */
    public function updateAbout(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'description' => 'nullable|string',
        ], $this->imageUploadMessages());

        if ($request->hasFile('logo')) {
            LandingPageSetting::setValue('about', 'logo', $this->uploadImageAsWebp($request->file('logo'), 'images', 'about-logo-' . time()));
        }

        if ($request->has('description')) {
            LandingPageSetting::setValue('about', 'description', $request->description);
        }

        return redirect()->route('admin.landing.about')
            ->with('success', 'About section updated successfully!');
    }

    /**
     * Display Brands section edit page
     */
    public function brands()
    {
        $title = LandingPageSetting::getValue('brands', 'title', 'GUITAR BRANDS');
        $subtitle = LandingPageSetting::getValue('brands', 'subtitle', 'Trusted Guitar Brands We Offer');

        $logo = LandingPageSetting::getValue('brands', 'logo', null);
        $guitarBrands = Brand::getGuitarBrands();
        $accessoriesBrands = Brand::getAccessoriesBrands();

        $acc_title = LandingPageSetting::getValue('brands', 'acc_title', 'ACCESSORIES BRANDS');
        $acc_subtitle = LandingPageSetting::getValue('brands', 'acc_subtitle', 'Trusted Accessories Brands We Offer');

        return view('admin.landing.brands', compact(
            'title',
            'subtitle',
            'acc_title',
            'acc_subtitle',
            'logo',
            'guitarBrands',
            'accessoriesBrands',
            'acc_title',
            'acc_subtitle'
        ));
    }

    /**
     * Update Brands section header
     */
    public function updateBrandsHeader(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'acc_title' => 'nullable|string|max:255', // Input baru
            'acc_subtitle' => 'nullable|string|max:255', // Input baru
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        if ($request->has('title')) {
            LandingPageSetting::setValue('brands', 'title', $request->title);
        }
        if ($request->has('subtitle')) {
            LandingPageSetting::setValue('brands', 'subtitle', $request->subtitle);
        }

        // Simpan title & subtitle khusus aksesoris
        if ($request->has('acc_title')) {
            LandingPageSetting::setValue('brands', 'acc_title', $request->acc_title);
        }
        if ($request->has('acc_subtitle')) {
            LandingPageSetting::setValue('brands', 'acc_subtitle', $request->acc_subtitle);
        }

        if ($request->hasFile('logo')) {
            LandingPageSetting::setValue('brands', 'logo', $this->uploadImageAsWebp($request->file('logo'), 'images', 'brands-logo-' . time()));
        }

        return redirect()->route('admin.landing.brands')
            ->with('success', 'Brands section headers updated successfully!');
    }

    /**
     * Store new brand
     */
    public function storeBrand(Request $request)
    {
        $request->validate([
            'type' => 'required|in:guitar,accessories',
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        $logoPath = $this->uploadImageAsWebp($request->file('logo'), 'images', 'brand-' . strtolower($request->name) . '-' . time());

        Brand::create([
            'type' => $request->type,
            'name' => $request->name,
            'logo' => $logoPath,
            'order' => 0,
        ]);

        return redirect()->route('admin.landing.brands')
            ->with('success', 'Brand added successfully!');
    }

    /**
     * Update brand
     */
    public function updateBrand(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:guitar,accessories',
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        $brand = Brand::findOrFail($id);

        $data = [
            'type' => $request->type,
            'name' => $request->name,
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($brand->logo && file_exists(public_path($brand->logo))) {
                unlink(public_path($brand->logo));
            }

            $data['logo'] = $this->uploadImageAsWebp($request->file('logo'), 'images', 'brand-' . strtolower($request->name) . '-' . time());
        }

        $brand->update($data);

        return redirect()->route('admin.landing.brands')
            ->with('success', 'Brand updated successfully!');
    }

    /**
     * Delete brand
     */
    public function destroyBrand($id)
    {
        $brand = Brand::findOrFail($id);

        // Delete logo file
        if ($brand->logo && file_exists(public_path($brand->logo))) {
            unlink(public_path($brand->logo));
        }

        $brand->delete();

        return redirect()->route('admin.landing.brands')
            ->with('success', 'Brand deleted successfully!');
    }

    /**
     * Display Products section edit page
     */
    public function products(Request $request)
    {
        $title = LandingPageSetting::getValue('products', 'title', 'PRODUCTS');
        $search = trim((string) $request->query('search', ''));
        $category = $request->query('category', '');
        $kondisi = $request->query('kondisi', '');

        // Filter agar kategori merchandise tidak muncul di sini
        $query = LandingPageProduct::with('images')
            ->whereNotIn('category', ['merchandise', 'accessories']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('bonus', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        if ($kondisi !== '') {
            $query->where('kondisi', $kondisi);
        }

        $products = $query->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $productsForJs = $products->getCollection()->values();

        return view('admin.landing.products', compact('products', 'productsForJs', 'title', 'search', 'category', 'kondisi'));
    }

    /**
     * Update Products section header
     */
    public function updateProductsHeader(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
        ]);

        if ($request->has('title')) {
            LandingPageSetting::setValue('products', 'title', $request->title);
        }

        return redirect()->route('admin.landing.products')
            ->with('success', 'Products section title updated successfully!');
    }

    /**
     * Store new product for landing page
     */
    public function storeProduct(Request $request)
    {
        $rules = [
            'nama_barang' => 'required|string|max:255',
            'kondisi' => 'required|in:great,good,used,new',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'bonus' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'category' => 'required|in:acoustic,electric,bass,amplifier,effect',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'order_info' => 'nullable|string',
            'payment_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
        ];

        // Validate multiple images if they exist
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $hasValidFiles = false;
            foreach ($images as $image) {
                if ($image && $image->isValid() && $image->getError() === UPLOAD_ERR_OK) {
                    $hasValidFiles = true;
                    break;
                }
            }

            if ($hasValidFiles) {
                $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240';
            }
        }

        $request->validate($rules, $this->imageUploadMessages());

        // Handle Main Image with compression
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImageAsWebp($request->file('image'), 'images', 'product-' . time(), 82);
        }

        // Handle Payment Image with compression
        $paymentImagePath = null;
        if ($request->hasFile('payment_image')) {
            $paymentImagePath = $this->uploadImageAsWebp($request->file('payment_image'), 'images/payments', 'pay-' . time(), 82);
        }

        $data = [
            'nama_barang' => $request->nama_barang,
            'kondisi' => $request->kondisi,
            'image' => $imagePath,
            'bonus' => $request->bonus,
            'harga' => $request->harga,
            'category' => $request->category,
            'order' => $request->order ?? 0,
            'description' => $request->description,
            'order_info' => $request->order_info,
            'payment_image' => $paymentImagePath,
        ];

        $product = LandingPageProduct::create($data);

        // Handle multiple images
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $validImages = array_filter($images, function ($image) {
                return $image && $image->isValid() && $image->getError() === UPLOAD_ERR_OK;
            });

            foreach ($validImages as $index => $image) {
                \App\Models\LandingPageProductImage::create([
                    'landing_page_product_id' => $product->id,
                    'image_path' => $this->uploadImageAsWebp($image, 'images', 'product-' . time() . '-' . $index, 82),
                    'order' => $index,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product added successfully!');
    }

    /**
     * Update product for landing page
     */
    public function updateProduct(Request $request, $id)
    {
        $rules = [
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kondisi' => 'required|in:great,good,used,new',
            'category' => 'required|in:acoustic,electric,bass,amplifier,effect',
            'order' => 'nullable|integer|min:0',
            'bonus' => 'nullable|string',
            'description' => 'nullable|string',
            'order_info' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'deleted_images' => 'nullable|array',
        ];

        if ($request->hasFile('images')) {
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240';
        }

        $request->validate($rules, $this->imageUploadMessages());

        $product = LandingPageProduct::findOrFail($id);

        $data = [
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'kondisi' => $request->kondisi,
            'category' => $request->category,
            'order' => $request->order ?? 0,
            'bonus' => $request->bonus,
            'description' => $request->description,
            'order_info' => $request->order_info,
        ];

        if ($request->hasFile('image')) {
            // Hapus file lama jika perlu
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $data['image'] = $this->uploadImageAsWebp($request->file('image'), 'images', 'product-' . time(), 82);
        }

        $product->update($data);

        if ($request->filled('deleted_images')) {
            $imagesToDelete = \App\Models\LandingPageProductImage::where('landing_page_product_id', $product->id)
                ->whereIn('id', $request->deleted_images)
                ->get();

            foreach ($imagesToDelete as $image) {
                if ($image->image_path && file_exists(public_path($image->image_path))) {
                    unlink(public_path($image->image_path));
                }
                $image->delete();
            }
        }

        if ($request->hasFile('images')) {
            $nextOrder = (int) ($product->images()->max('order') ?? 0) + 1;
            foreach ($request->file('images') as $index => $image) {
                if ($image && $image->isValid() && $image->getError() === UPLOAD_ERR_OK) {
                    \App\Models\LandingPageProductImage::create([
                        'landing_page_product_id' => $product->id,
                        'image_path' => $this->uploadImageAsWebp($image, 'images', 'product-' . time() . '-' . $index, 82),
                        'order' => $nextOrder + $index,
                    ]);
                }
            }
        }

        // Jika dari halaman accessories, balik ke accessories
        if ($product->category == 'accessories') {
            return redirect()->route('admin.landing.accessories')->with('success', 'Accessory updated successfully!');
        }

        return redirect()->route('admin.landing.products')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product from landing page
     */
    public function destroyProduct($id)
    {
        $product = LandingPageProduct::findOrFail($id);
        $category = $product->category; // Simpan kategori sebelum dihapus

        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        $product->delete();

        // Redirect kondisional
        if ($category === 'accessories') {
            return redirect()->route('admin.landing.accessories')->with('success', 'Accessory deleted successfully!');
        }
        return redirect()->route('admin.landing.products')->with('success', 'Product deleted successfully!');
    }

    /**
     * Import products from landing page
     */
    public function importProducts()
    {
        $products = [
            [
                'nama_barang' => 'Washburn PXS10E DLX Flame',
                'kondisi' => 'great',
                'image' => 'images/gitar1.jpg',
                'bonus' => null,
                'harga' => 4500000,
                'category' => 'electric',
                'order' => 1,
            ],
            [
                'nama_barang' => 'Fender Stratocaster American Standard 2007',
                'kondisi' => 'great',
                'image' => 'images/gitar2.jpg',
                'bonus' => 'Include Hardcase + Manual Book',
                'harga' => 17500000,
                'category' => 'electric',
                'order' => 2,
            ],
            [
                'nama_barang' => 'Kramer Striker Figured',
                'kondisi' => 'great',
                'image' => 'images/gitar3.jpg',
                'bonus' => null,
                'harga' => 3000000,
                'category' => 'electric',
                'order' => 3,
            ],
            [
                'nama_barang' => 'Starsun Ja Cherry S',
                'kondisi' => 'great',
                'image' => 'images/gitar4.jpg',
                'bonus' => 'Full Set',
                'harga' => 2500000,
                'category' => 'electric',
                'order' => 4,
            ],
            [
                'nama_barang' => 'Gibson Les Paul Standard',
                'kondisi' => 'great',
                'image' => 'images/gitar1.jpg',
                'bonus' => null,
                'harga' => 25000000,
                'category' => 'electric',
                'order' => 5,
            ],
            [
                'nama_barang' => 'Epiphone SG Standard',
                'kondisi' => 'great',
                'image' => 'images/gitar2.jpg',
                'bonus' => null,
                'harga' => 5500000,
                'category' => 'electric',
                'order' => 6,
            ],
            [
                'nama_barang' => 'Martin D-28 Acoustic',
                'kondisi' => 'great',
                'image' => 'images/gitar3.jpg',
                'bonus' => null,
                'harga' => 15000000,
                'category' => 'acoustic',
                'order' => 7,
            ],
            [
                'nama_barang' => 'Ibanez RG Series',
                'kondisi' => 'great',
                'image' => 'images/gitar4.jpg',
                'bonus' => null,
                'harga' => 8500000,
                'category' => 'electric',
                'order' => 8,
            ],
        ];

        $importedCount = 0;
        foreach ($products as $product) {
            LandingPageProduct::updateOrCreate(
                ['nama_barang' => $product['nama_barang']],
                $product
            );
            $importedCount++;
        }

        return redirect()->route('admin.landing.products')
            ->with('success', "Berhasil mengimpor $importedCount produk dari landing page!");
    }

    /**
     * Display Accessories section edit page
     */
    // AdminLandingPageController.php

    public function accessories()
    {
        // Ambil Judul Utama dari tabel setting
        $title = LandingPageSetting::getValue('accessories', 'title', 'ACCESSORIES');

        // TAMBAHKAN BARIS INI: Ambil Subtitle dari tabel setting
        $subtitle = LandingPageSetting::getValue('accessories', 'subtitle', 'Premium collection of guitar accessories and gear');

        // Ambil data item aksesoris
        $accessories = LandingPageProduct::where('category', 'accessories')
            ->orderBy('order')
            ->get();

        // PASTIKAN 'subtitle' ada di dalam compact
        return view('admin.landing.accessories', compact('title', 'subtitle', 'accessories'));
    }

    /**
     * Update Accessories Section Title
     */
    public function updateAccessories(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500', // Tambahkan validasi subtitle
        ]);

        if ($request->has('title')) {
            LandingPageSetting::setValue('accessories', 'title', $request->title);
        }

        // Simpan subtitle ke database settings
        if ($request->has('subtitle')) {
            LandingPageSetting::setValue('accessories', 'subtitle', $request->subtitle);
        }

        LandingPageSetting::setValue('accessories', 'title', $request->title);
        LandingPageSetting::setValue('accessories', 'subtitle', $request->subtitle); // Menyimpan subtitle

        return redirect()->route('admin.landing.accessories')
            ->with('success', 'Accessories settings updated successfully!');
    }

    /**
     * Method Store khusus untuk Accessories Item
     * (Menghubungkan form input Nama, Deskripsi, Harga, Image ke LandingPageProduct)
     */
    public function storeAccessory(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'bonus'       => 'nullable|string', // Digunakan untuk Deskripsi
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        $data = [
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'bonus'       => $request->bonus,
            'category'    => 'accessories',
            'kondisi'     => 'great', // Nilai default untuk aksesoris
            'order'       => 0,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImageAsWebp($request->file('image'), 'images', 'accessory-' . time(), 82);
        }

        LandingPageProduct::create($data);

        return redirect()->route('admin.landing.accessories')
            ->with('success', 'New accessory item added successfully!');
    }

    /**
     * Delete Accessory Item
     */
    public function destroyAccessory($id)
    {
        $item = LandingPageProduct::findOrFail($id);

        // Hapus file gambar dari server
        if ($item->image && file_exists(public_path($item->image))) {
            unlink(public_path($item->image));
        }

        $item->delete();

        return redirect()->route('admin.landing.accessories')
            ->with('success', 'Accessory item deleted successfully!');
    }

    /**
     * Display Gallery section edit page
     */
    public function gallery()
    {
        // Mengambil semua data gallery berdasarkan urutan
        $galleries = \App\Models\Gallery::orderBy('order')->get();
        return view('admin.landing.gallery', compact('galleries'));
    }

    public function storeGallery(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], $this->imageUploadMessages());

        if ($request->hasFile('image')) {
            \App\Models\Gallery::create([
                'image' => $this->uploadImageAsWebp($request->file('image'), 'images/gallery', 'gallery-' . time(), 82),
                'order' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Image added to gallery!');
    }

    public function updateGallery(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], $this->imageUploadMessages());

        $gallery = Gallery::findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus foto lama dari folder public
            if (file_exists(public_path($gallery->image))) {
                unlink(public_path($gallery->image));
            }

            $gallery->update([
                'image' => $this->uploadImageAsWebp($request->file('image'), 'images/gallery', 'gallery-' . time(), 82),
            ]);
        }

        return redirect()->back()->with('success', 'Gallery image updated successfully!');
    }

    public function destroyGallery($id)
    {
        $gallery = \App\Models\Gallery::findOrFail($id);
        // Hapus file fisik
        if (file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }
        $gallery->delete();

        return redirect()->back()->with('success', 'Image deleted from gallery!');
    }

    /**
     * Display Service section edit page
     */
    public function service()
    {
        // Mengambil data dari tabel landing_page_settings
        // Jika data tidak ada di database, gunakan nilai default (parameter ketiga)
        $title = LandingPageSetting::getValue('service', 'title', 'OUR SERVICES');
        $subtitle = LandingPageSetting::getValue('service', 'subtitle', 'Complete Services for Your Music Needs');

        // Mengambil semua data service untuk tabel list di bawahnya
        $services = \App\Models\Service::orderBy('order', 'asc')->get();

        // Pastikan variabel 'title' dan 'subtitle' masuk ke dalam view
        return view('admin.landing.service', compact('title', 'subtitle', 'services'));
    }

    public function updateServiceHeader(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
        ]);

        // Mengupdate nilai di database
        LandingPageSetting::setValue('service', 'title', $request->title);
        LandingPageSetting::setValue('service', 'subtitle', $request->subtitle);

        return redirect()->route('admin.landing.service')->with('success', 'Service Header updated successfully!');
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|image|mimes:png,jpg,jpeg,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        if ($request->hasFile('icon')) {
            Service::create([
                'title' => $request->title,
                'description' => $request->description,
                'icon' => $this->uploadImageAsWebp($request->file('icon'), 'images/services', 'svc-' . time()),
                'order' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Service added successfully!');
    }

    /**
     * Menyimpan ikon pembayaran baru secara global.
     */
    public function storePaymentIcon(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'icon' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        // Ambil data JSON lama dari tabel landing_page_settings
        $paymentIconsJson = \App\Models\LandingPageSetting::getValue('products', 'payment_icons', '[]');
        $paymentIcons = json_decode($paymentIconsJson, true) ?: [];

        if ($request->hasFile('icon')) {
            // Tambahkan path file baru ke dalam array
            $paymentIcons[] = $this->uploadImageAsWebp($request->file('icon'), 'images/payments', 'pay-icon-' . time() . '-' . rand(100, 999));

            // Simpan kembali ke database dalam format JSON
            \App\Models\LandingPageSetting::setValue('products', 'payment_icons', json_encode($paymentIcons));
        }

        return redirect()->back()->with('success', 'Ikon pembayaran global berhasil ditambahkan!');
    }

    /**
     * Menghapus ikon pembayaran global tertentu.
     */
    // AdminLandingPageController.php

    public function destroyPaymentIcon(Request $request)
    {
        // Validasi input
        $request->validate([
            'icon_path' => 'required|string'
        ]);

        $iconPath = $request->icon_path;

        // Ambil data JSON dari database
        $paymentIconsJson = \App\Models\LandingPageSetting::getValue('products', 'payment_icons', '[]');
        $paymentIcons = json_decode($paymentIconsJson, true) ?: [];

        // Filter array untuk membuang path yang dihapus
        $newPaymentIcons = array_values(array_filter($paymentIcons, function ($path) use ($iconPath) {
            return $path !== $iconPath;
        }));

        // Hapus file fisik dari server jika path valid
        // Gunakan public_path() karena file disimpan di folder public/images/payments
        $fullPath = public_path($iconPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Update database dengan array yang sudah dikurangi
        \App\Models\LandingPageSetting::setValue('products', 'payment_icons', json_encode($newPaymentIcons));

        return redirect()->back()->with('success', 'Ikon pembayaran berhasil dihapus!');
    }

    public function destroyService($id)
    {
        $service = Service::findOrFail($id);
        if (file_exists(public_path($service->icon))) {
            unlink(public_path($service->icon));
        }
        $service->delete();

        return redirect()->back()->with('success', 'Service deleted successfully!');
    }

    /**
     * Display Contact section edit page
     */
    public function contact()
    {
        // Mengambil data dari LandingPageSetting dengan nilai default
        $contactTitle = LandingPageSetting::getValue('contact', 'title', 'CONTACT US');
        $addressIcon = LandingPageSetting::getValue('contact', 'address_icon', 'map-pin');
        $addressText = LandingPageSetting::getValue('contact', 'address_text', 'Jl. Wates No.148 Km. 3,5 No, Onggobayan, Ngestiharjo, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55184');
        $phoneIcon = LandingPageSetting::getValue('contact', 'phone_icon', 'phone');
        $phoneText = LandingPageSetting::getValue('contact', 'phone_text', '08816707166');

        // Get social media (JSON array)
        $socialMediaJson = LandingPageSetting::getValue('contact', 'social_media', '[]');
        $socialMedia = json_decode($socialMediaJson, true) ?: [];

        // Get old single social media for backward compatibility
        $oldSocialIcon = LandingPageSetting::getValue('contact', 'social_icon', null);
        $oldSocialLink = LandingPageSetting::getValue('contact', 'social_link', 'https://instagram.com/musicmen');

        // If no social media array exists but old data exists, migrate it
        if (empty($socialMedia) && $oldSocialIcon) {
            $socialMedia = [[
                'icon' => $oldSocialIcon,
                'link' => $oldSocialLink
            ]];
        }

        $mapsLink = LandingPageSetting::getValue('contact', 'maps_link', '');
        $contactLogoPath = LandingPageSetting::getValue('contact', 'logo', 'images/logo4.png');

        // Get icon paths for display
        $addressIconPath = LandingPageSetting::getValue('contact', 'address_icon', null);
        $phoneIconPath = LandingPageSetting::getValue('contact', 'phone_icon', null);

        return view('admin.landing.contact', compact(
            'contactTitle',
            'addressIcon',
            'addressText',
            'phoneIcon',
            'phoneText',
            'socialMedia',
            'mapsLink',
            'contactLogoPath',
            'addressIconPath',
            'phoneIconPath'
        ));
    }

    /**
     * Convert Google Maps short URL to embed URL
     * Short URLs from maps.app.goo.gl need to be converted to embed format
     */
    private function convertMapsUrl($url)
    {
        if (empty($url)) return '';

        // Jika user menempelkan seluruh tag <iframe>, ekstrak bagian src-nya saja
        if (preg_match('/src="([^"]+)"/', $url, $match)) {
            $url = $match[1];
        }

        // Pastikan URL mengandung kata 'embed' agar bisa muncul di iframe
        // Jika user memasukkan URL Google Maps biasa (bukan embed), 
        // sistem ini akan mencoba memvalidasi atau membiarkannya, namun tetap memperingatkan di admin.
        return $url;
    }

    public function updateContact(Request $request)
    {
        try {
            $request->validate([
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
                'address_icon' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
                'phone_icon' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
                'title' => 'required|string|max:255',
                'address_text' => 'required|string',
                'phone_text' => 'required|string',
                'maps_link' => 'nullable|string',
                'social_media_icons.*' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
                'social_media_links.*' => 'nullable|url',
            ], array_merge($this->imageUploadMessages(), [
                'title.required' => 'Judul section wajib diisi.',
                'address_text.required' => 'Alamat wajib diisi.',
                'phone_text.required' => 'Nomor telepon wajib diisi.',
                'social_media_links.*.url' => 'Link social media harus berupa URL yang valid.',
            ]));

            // Pastikan folder images/contact ada
            if (!file_exists(public_path('images/contact'))) {
                mkdir(public_path('images/contact'), 0755, true);
            }

            // Update Text Settings
            LandingPageSetting::setValue('contact', 'title', $request->title);
            LandingPageSetting::setValue('contact', 'address_text', $request->address_text);
            LandingPageSetting::setValue('contact', 'phone_text', $request->phone_text);

            // Perbaikan: Konversi link maps sebelum disimpan
            $mapsLink = $this->convertMapsUrl($request->maps_link ?? '');
            LandingPageSetting::setValue('contact', 'maps_link', $mapsLink);

            // --- Sisa kode social media dan upload file (Tetap Sama) ---
            $socialMedia = [];
            if ($request->has('social_media_links')) {
                $links = $request->social_media_links ?? [];
                $icons = $request->social_media_icons ?? [];
                $existingIcons = $request->social_media_existing_icons ?? [];
                foreach ($links as $index => $link) {
                    if (!empty($link)) {
                        $iconPath = null;
                        if (isset($icons[$index]) && $icons[$index]) {
                            $iconPath = $this->uploadImageAsWebp($icons[$index], 'images/contact', 'contact-social-' . $index . '-' . time());
                        } elseif (isset($existingIcons[$index])) {
                            $iconPath = $existingIcons[$index];
                        }
                        if ($iconPath) $socialMedia[] = ['icon' => $iconPath, 'link' => $link];
                    }
                }
            }
            LandingPageSetting::setValue('contact', 'social_media', json_encode($socialMedia));

            $iconsToUpload = ['logo', 'address_icon', 'phone_icon'];
            foreach ($iconsToUpload as $iconName) {
                if ($request->hasFile($iconName)) {
                    LandingPageSetting::setValue(
                        'contact',
                        $iconName,
                        $this->uploadImageAsWebp($request->file($iconName), 'images/contact', 'contact-' . $iconName . '-' . time())
                    );
                }
            }

            return redirect()->back()->with('success', 'Pengaturan kontak berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengaturan kontak: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function merchandise()
    {
        $title = LandingPageSetting::getValue('merchandise', 'title', 'MERCHANDISE');

        // Ambil data item merchandise
        $merchandise = LandingPageProduct::where('category', 'merchandise')
            ->orderBy('order')
            ->get();

        return view('admin.landing.merchandise', compact('title', 'merchandise'));
    }

    /**
     * Update Merchandise Section Title
     */
    public function updateMerchandiseHeader(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
        ]);

        if ($request->has('title')) {
            LandingPageSetting::setValue('merchandise', 'title', $request->title);
        }

        return redirect()->route('admin.landing.merchandise')
            ->with('success', 'Merchandise section title updated successfully!');
    }

    // AdminLandingPageController.php

    public function updateMerchandise(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'bonus'       => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        $item = LandingPageProduct::findOrFail($id);

        $data = [
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'bonus'       => $request->bonus, // Digunakan sebagai deskripsi
        ];

        if ($request->hasFile('image')) {
            // Hapus foto lama
            if ($item->image && file_exists(public_path($item->image))) {
                unlink(public_path($item->image));
            }

            $data['image'] = $this->uploadImageAsWebp($request->file('image'), 'images', 'merch-' . time(), 82);
        }

        $item->update($data);

        return redirect()->route('admin.landing.merchandise')
            ->with('success', 'Merchandise updated successfully!');
    }

    /**
     * Store new Merchandise Item
     */
    public function storeMerchandise(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'bonus'       => 'nullable|string', // Digunakan sebagai deskripsi singkat
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ], $this->imageUploadMessages());

        $data = [
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'bonus'       => $request->bonus,
            'category'    => 'merchandise',
            'kondisi'     => 'new',
            'order'       => 0,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImageAsWebp($request->file('image'), 'images', 'merch-' . time(), 82);
        }

        LandingPageProduct::create($data);

        return redirect()->route('admin.landing.merchandise')
            ->with('success', 'New merchandise item added successfully!');
    }

    /**
     * Delete Merchandise Item
     */
    public function destroyMerchandise($id)
    {
        $item = LandingPageProduct::findOrFail($id);

        if ($item->image && file_exists(public_path($item->image))) {
            unlink(public_path($item->image));
        }

        $item->delete();

        return redirect()->route('admin.landing.merchandise')
            ->with('success', 'Merchandise item deleted successfully!');
    }
}
