<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPageProduct;

class LandingPageProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

        foreach ($products as $product) {
            LandingPageProduct::updateOrCreate(
                ['nama_barang' => $product['nama_barang']],
                $product
            );
        }
    }
}
