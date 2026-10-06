<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah ENUM untuk menambahkan 'new' ke tabel landing_page_products
        // Di MySQL, kita perlu menggunakan raw SQL untuk mengubah ENUM
        DB::statement("ALTER TABLE landing_page_products MODIFY COLUMN kondisi ENUM('great', 'good', 'used', 'new') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM tanpa 'new'
        // Update data dengan kondisi 'new' terlebih dahulu jika ada
        DB::table('landing_page_products')
            ->where('kondisi', 'new')
            ->update(['kondisi' => 'good']);
        
        // Ubah kembali ENUM
        DB::statement("ALTER TABLE landing_page_products MODIFY COLUMN kondisi ENUM('great', 'good', 'used') NOT NULL");
    }
};

