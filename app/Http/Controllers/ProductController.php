<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Daftar produk yang tersedia
     */
    private function getProducts()
    {
        return [
            'washburn-pxs10e-dlx-flame' => [
                'name' => 'Washburn PXS10E DLX Flame',
                'price' => '4.500.000',
                'image' => 'gitar1.jpg',
                'description' => null,
                'condition' => 'Great Condition',
                'category' => 'electric-guitars',
            ],
            'fender-stratocaster-american-standard-2007' => [
                'name' => 'Fender Stratocaster American Standard 2007',
                'price' => '17.500.000',
                'image' => 'gitar2.jpg',
                'description' => 'Include Hardcase + Manual Book',
                'condition' => 'Great Condition',
                'category' => 'electric-guitars',
            ],
            'kramer-striker-figured' => [
                'name' => 'Kramer Striker Figured',
                'price' => '3.000.000',
                'image' => 'gitar3.jpg',
                'description' => null,
                'condition' => 'Great Condition',
                'category' => 'electric-guitars',
            ],
            'starsun-ja-cherry-s' => [
                'name' => 'Starsun Ja Cherry S',
                'price' => '2.500.000',
                'image' => 'gitar4.jpg',
                'description' => 'Full Set',
                'condition' => 'Great Condition',
                'category' => 'electric-guitars',
            ],
            'gibson-les-paul-standard' => [
                'name' => 'Gibson Les Paul Standard',
                'price' => '25.000.000',
                'image' => 'gitar1.jpg',
                'description' => null,
                'condition' => 'Great Condition',
                'category' => 'electric-guitars',
            ],
            'epiphone-sg-standard' => [
                'name' => 'Epiphone SG Standard',
                'price' => '5.500.000',
                'image' => 'gitar2.jpg',
                'description' => null,
                'condition' => 'Great Condition',
                'category' => 'electric-guitars',
            ],
            'martin-d-28-acoustic' => [
                'name' => 'Martin D-28 Acoustic',
                'price' => '15.000.000',
                'image' => 'gitar3.jpg',
                'description' => null,
                'condition' => 'Great Condition',
                'category' => 'acoustic-guitars',
            ],
            'ibanez-rg-series' => [
                'name' => 'Ibanez RG Series',
                'price' => '8.500.000',
                'image' => 'gitar4.jpg',
                'description' => null,
                'condition' => 'Great Condition',
                'category' => 'electric-guitars',
            ],
        ];
    }

    /**
     * Menampilkan halaman kategori produk
     */
    public function category($category)
    {
        // Mapping kategori dari URL ke database category
        $categoryMap = [
            'acoustic-guitars' => [
                'db_category' => 'acoustic',
                'title' => 'Acoustic Guitars',
                'description' => 'Premium collection of high-quality acoustic guitars',
            ],
            'electric-guitars' => [
                'db_category' => 'electric',
                'title' => 'Electric Guitars',
                'description' => 'Premium collection of electric guitars',
            ],
            'bass-guitars' => [
                'db_category' => 'bass',
                'title' => 'Bass Guitars',
                'description' => 'Collection of the best bass guitars',
            ],
            'amplifiers' => [
                'db_category' => 'amplifier',
                'title' => 'Amplifiers',
                'description' => 'Quality amplifier collection',
            ],
            'effect' => [
                'db_category' => 'effect',
                'title' => 'Effect',
                'description' => 'Collection of effect pedals and accessories',
            ],
        ];

        if (!isset($categoryMap[$category])) {
            abort(404);
        }

        $categoryInfo = $categoryMap[$category];
        $dbCategory = $categoryInfo['db_category'];

        // Ambil produk dari database berdasarkan kategori
        try {
            $dbProducts = \App\Models\LandingPageProduct::where('category', $dbCategory)
                ->where('kondisi', '!=', 'used') // Exclude used gear
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Mapping data agar formatnya sesuai dengan yang diharapkan View
            $filteredProducts = $dbProducts->mapWithKeys(function ($item) {
                return [
                    $item->slug => [
                        'name' => $item->nama_barang,
                        'price' => number_format($item->harga, 0, ',', '.'),
                        'image' => $item->image,
                        'description' => $item->bonus,
                        'condition' => $item->kondisi_name,
                        'category' => $item->category_name,
                    ]
                ];
            })->toArray();
        } catch (\Exception $e) {
            // Fallback ke hardcoded data jika ada error
            $products = $this->getProducts();
            $filteredProducts = array_filter($products, function ($product) use ($category) {
                return isset($product['category']) && $product['category'] === $category;
            });
        }

        // Hapus 'db_category' dari categoryInfo sebelum dikirim ke view
        unset($categoryInfo['db_category']);

        return view('category', compact('filteredProducts', 'categoryInfo', 'category'));
    }

    /**
     * Menampilkan halaman detail produk
     */
    public function show($slug)
    {
        // Cari produk dari database LandingPageProduct berdasarkan slug
        $product = \App\Models\LandingPageProduct::where('slug', $slug)->first();

        // Jika tidak ditemukan di database, coba dari data hardcoded (untuk backward compatibility)
        if (!$product) {
            $products = $this->getProducts();
            $accessories = $this->getAccessories();
            $merchandise = $this->getMerchandise();
            $usedGear = $this->getUsedGear();
            $allProducts = array_merge($products, $accessories, $merchandise, $usedGear);

            if (!isset($allProducts[$slug])) {
                abort(404);
            }

            $product = $allProducts[$slug];
        }

        // 1. Ambil semua data yang dibutuhkan Footer & Detail dari LandingPageSetting
        $addressIconPath = \App\Models\LandingPageSetting::getValue('contact', 'address_icon', null);
        $phoneIconPath   = \App\Models\LandingPageSetting::getValue('contact', 'phone_icon', null);
        $socialIconPath  = \App\Models\LandingPageSetting::getValue('contact', 'social_icon', null);
        $addressText     = \App\Models\LandingPageSetting::getValue('contact', 'address_text', '');
        $phoneText       = \App\Models\LandingPageSetting::getValue('contact', 'phone_text', '');
        $socialLink      = \App\Models\LandingPageSetting::getValue('contact', 'social_link', '#');
        $mapsLink        = \App\Models\LandingPageSetting::getValue('contact', 'maps_link', '#');

        // 2. Masukkan 'mapsLink' ke dalam compact
        return view('product-detail', compact(
            'product',
            'slug',
            'addressIconPath',
            'phoneIconPath',
            'socialIconPath',
            'addressText',
            'phoneText',
            'socialLink',
            'mapsLink'
        ));
    }

    /**
     * Daftar accessories yang tersedia
     */
    private function getAccessories()
    {
        return [
            'daddario-strings' => [
                'name' => 'D\'Addario Strings',
                'price' => '150.000',
                'image' => 'dadario.png',
                'description' => 'Premium guitar strings',
                'condition' => 'New',
                'type' => 'accessories',
            ],
            'ernie-ball-strings' => [
                'name' => 'Ernie Ball Strings',
                'price' => '120.000',
                'image' => 'ernieball.png',
                'description' => 'High-quality guitar strings',
                'condition' => 'New',
                'type' => 'accessories',
            ],
            'dr-case' => [
                'name' => 'DR. Case Guitar Case',
                'price' => '1.500.000',
                'image' => 'drcase.png',
                'description' => 'Protective guitar case',
                'condition' => 'New',
                'type' => 'accessories',
            ],
            'sg-strings' => [
                'name' => 'SG Strings',
                'price' => '100.000',
                'image' => 'sgstring.png',
                'description' => 'Quality strings',
                'condition' => 'New',
                'type' => 'accessories',
            ],
            'valeton-pedal' => [
                'name' => 'Valeton Effect Pedal',
                'price' => '2.500.000',
                'image' => 'valeton.jpg',
                'description' => 'Multi-effect pedal',
                'condition' => 'New',
                'type' => 'accessories',
            ],
            'ivu-creator' => [
                'name' => 'IVU Creator Accessories',
                'price' => '500.000',
                'image' => 'ivu.png',
                'description' => 'Guitar accessories set',
                'condition' => 'New',
                'type' => 'accessories',
            ],
        ];
    }

    /**
     * Daftar merchandise yang tersedia
     */
    private function getMerchandise()
    {
        return [
            'musicmen-t-shirt' => [
                'name' => 'Musicmen T-Shirt',
                'price' => '250.000',
                'image' => 'logo1.png',
                'description' => 'Official Musicmen T-Shirt',
                'condition' => 'New',
                'type' => 'merchandise',
            ],
            'musicmen-cap' => [
                'name' => 'Musicmen Cap',
                'price' => '150.000',
                'image' => 'logo2.png',
                'description' => 'Official Musicmen Cap',
                'condition' => 'New',
                'type' => 'merchandise',
            ],
            'musicmen-sticker' => [
                'name' => 'Musicmen Sticker Pack',
                'price' => '50.000',
                'image' => 'logo3.png',
                'description' => 'Set of Musicmen stickers',
                'condition' => 'New',
                'type' => 'merchandise',
            ],
            'musicmen-tote-bag' => [
                'name' => 'Musicmen Tote Bag',
                'price' => '200.000',
                'image' => 'logo4.png',
                'description' => 'Official Musicmen tote bag',
                'condition' => 'New',
                'type' => 'merchandise',
            ],
        ];
    }

    /**
     * Menampilkan halaman accessories
     */
    public function accessories()
    {
        // Ambil Judul & Deskripsi dari database settings
        $title = \App\Models\LandingPageSetting::getValue('accessories', 'title', 'Accessories');
        $subtitle = \App\Models\LandingPageSetting::getValue('accessories', 'subtitle', 'Premium collection of guitar accessories and gear');

        try {
            $dbProducts = \App\Models\LandingPageProduct::where('category', 'accessories')
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            $filteredProducts = $dbProducts->mapWithKeys(function ($item) {
                return [
                    $item->slug => [
                        'name' => $item->nama_barang,
                        'price' => number_format($item->harga, 0, ',', '.'),
                        'image' => $item->image,
                        'description' => $item->bonus,
                        'condition' => $item->kondisi_name ?? 'New',
                        'type' => 'accessories',
                    ]
                ];
            })->toArray();
        } catch (\Exception $e) {
            $filteredProducts = $this->getAccessories(); // Fallback data lama
        }

        $categoryInfo = [
            'title' => $title, // Gunakan variabel dinamis
            'description' => $subtitle, // Gunakan variabel dinamis
        ];

        return view('accessories', compact('filteredProducts', 'categoryInfo'));
    }

    /**
     * Menampilkan halaman merchandise
     */
    public function merchandise()
    {
        try {
            $dbProducts = \App\Models\LandingPageProduct::where('category', 'merchandise')
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            $filteredProducts = $dbProducts->mapWithKeys(function ($item) {
                return [
                    $item->slug => [
                        'name' => $item->nama_barang,
                        'price' => number_format($item->harga, 0, ',', '.'),
                        'image' => $item->image,
                        'description' => $item->bonus,
                        'condition' => 'New',
                        'type' => 'merchandise',
                    ]
                ];
            })->toArray();
        } catch (\Exception $e) {
            $filteredProducts = $this->getMerchandise(); // fallback ke data lama
        }

        $categoryInfo = [
            'title' => 'Merchandise',
            'description' => 'Official Musicmen merchandise and apparel',
        ];

        return view('category', compact('filteredProducts', 'categoryInfo'));
    }

    /**
     * Daftar used gear yang tersedia
     */
    private function getUsedGear()
    {
        return [
            'fender-stratocaster-used-2005' => [
                'name' => 'Fender Stratocaster Used 2005',
                'price' => '12.500.000',
                'image' => 'fender.jpg',
                'description' => 'Include Hardcase',
                'condition' => 'Used',
                'type' => 'used-gear',
            ],
            'gibson-les-paul-used-2008' => [
                'name' => 'Gibson Les Paul Used 2008',
                'price' => '18.000.000',
                'image' => 'gipson.jpg',
                'description' => 'Excellent Condition',
                'condition' => 'Used',
                'type' => 'used-gear',
            ],
            'ibanez-rg-used-2010' => [
                'name' => 'Ibanez RG Used 2010',
                'price' => '8.500.000',
                'image' => 'ibanez.jpg',
                'description' => null,
                'condition' => 'Used',
                'type' => 'used-gear',
            ],
            'schecter-damien-used-2012' => [
                'name' => 'Schecter Damien Used 2012',
                'price' => '9.000.000',
                'image' => 'sechcter.jpg',
                'description' => 'Include Gigbag',
                'condition' => 'Used',
                'type' => 'used-gear',
            ],
            'yamaha-acoustic-used-2015' => [
                'name' => 'Yamaha Acoustic Used 2015',
                'price' => '3.500.000',
                'image' => 'yamaha.jpeg',
                'description' => null,
                'condition' => 'Used',
                'type' => 'used-gear',
            ],
            'cort-bass-used-2013' => [
                'name' => 'Cort Bass Used 2013',
                'price' => '5.500.000',
                'image' => 'cort.png',
                'description' => 'Great Condition',
                'condition' => 'Used',
                'type' => 'used-gear',
            ],
        ];
    }

    /**
     * Menampilkan halaman used gear
     */
    public function usedGear()
    {
        // Mengambil produk dari database yang kondisinya adalah 'used'
        $dbProducts = \App\Models\LandingPageProduct::where('kondisi', 'used')
            ->orderBy('order', 'asc')
            ->get();

        // Mapping data agar formatnya sesuai dengan yang diharapkan View
        $filteredProducts = $dbProducts->mapWithKeys(function ($item) {
            return [
                $item->slug => [
                    'name' => $item->nama_barang,
                    'price' => number_format($item->harga, 0, ',', '.'),
                    'image' => $item->image, // Pastikan path image benar
                    'description' => $item->bonus, // Menggunakan kolom bonus sebagai deskripsi singkat di card
                    'condition' => 'Used',
                    'type' => 'used-gear',
                ]
            ];
        });

        // Jika ingin menggabungkan dengan data hardcoded lama (Opsional)
        // $oldUsedGear = $this->getUsedGear();
        // $filteredProducts = collect($oldUsedGear)->merge($filteredProducts);

        return view('used-gear', compact('filteredProducts'));
    }

    /**
     * Search global untuk semua produk
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return redirect()->route('used-gear');
        }

        $results = [
            'landing_products' => [],
            'used_gear' => [],
            'accessories' => [],
            'merchandise' => [],
        ];

        // Search dari LandingPageProduct (semua kondisi)
        $landingProducts = \App\Models\LandingPageProduct::where('nama_barang', 'like', '%' . $query . '%')
            ->orWhere('bonus', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->orderBy('order', 'asc')
            ->get();

        foreach ($landingProducts as $product) {
            $results['landing_products'][] = [
                'name' => $product->nama_barang,
                'slug' => $product->slug,
                'price' => number_format($product->harga, 0, ',', '.'),
                'image' => $product->image,
                'description' => $product->bonus,
                'condition' => $product->kondisi_name,
                'category' => $product->category_name,
                'type' => $product->kondisi === 'used' ? 'used-gear' : 'product',
            ];
        }

        // Search dari used gear (hardcoded)
        $usedGear = $this->getUsedGear();
        foreach ($usedGear as $slug => $product) {
            if (
                stripos($product['name'], $query) !== false ||
                (isset($product['description']) && stripos($product['description'], $query) !== false)
            ) {
                $results['used_gear'][] = array_merge($product, ['slug' => $slug]);
            }
        }

        // Search dari accessories (hardcoded)
        $accessories = $this->getAccessories();
        foreach ($accessories as $slug => $product) {
            if (
                stripos($product['name'], $query) !== false ||
                (isset($product['description']) && stripos($product['description'], $query) !== false)
            ) {
                $results['accessories'][] = array_merge($product, ['slug' => $slug]);
            }
        }

        // Search dari merchandise (hardcoded)
        $merchandise = $this->getMerchandise();
        foreach ($merchandise as $slug => $product) {
            if (
                stripos($product['name'], $query) !== false ||
                (isset($product['description']) && stripos($product['description'], $query) !== false)
            ) {
                $results['merchandise'][] = array_merge($product, ['slug' => $slug]);
            }
        }

        $totalResults = count($results['landing_products']) +
            count($results['used_gear']) +
            count($results['accessories']) +
            count($results['merchandise']);

        return view('search-results', compact('results', 'query', 'totalResults'));
    }
}
