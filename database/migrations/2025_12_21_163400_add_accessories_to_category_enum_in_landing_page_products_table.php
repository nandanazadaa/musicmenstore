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
        // MySQL tidak mendukung ALTER ENUM secara langsung
        // Kita perlu menggunakan DB::statement untuk mengubah enum
        DB::statement("ALTER TABLE landing_page_products MODIFY COLUMN category ENUM('acoustic', 'electric', 'bass', 'amplifier', 'effect', 'accessories') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum tanpa 'accessories'
        // Hapus dulu data dengan category 'accessories' jika ada
        DB::table('landing_page_products')->where('category', 'accessories')->delete();
        
        // Ubah kembali enum tanpa 'accessories'
        DB::statement("ALTER TABLE landing_page_products MODIFY COLUMN category ENUM('acoustic', 'electric', 'bass', 'amplifier', 'effect') NOT NULL");
    }
};
