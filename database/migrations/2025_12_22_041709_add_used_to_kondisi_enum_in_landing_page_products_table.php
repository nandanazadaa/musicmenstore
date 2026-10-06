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
        // Mengubah ENUM untuk menambahkan 'used'
        // Di MySQL, kita perlu menggunakan raw SQL untuk mengubah ENUM
        DB::statement("ALTER TABLE landing_page_products MODIFY COLUMN kondisi ENUM('great', 'good', 'used') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM tanpa 'used'
        // Hapus data dengan kondisi 'used' terlebih dahulu jika ada
        DB::table('landing_page_products')
            ->where('kondisi', 'used')
            ->update(['kondisi' => 'good']);
        
        // Ubah kembali ENUM
        DB::statement("ALTER TABLE landing_page_products MODIFY COLUMN kondisi ENUM('great', 'good') NOT NULL");
    }
};
