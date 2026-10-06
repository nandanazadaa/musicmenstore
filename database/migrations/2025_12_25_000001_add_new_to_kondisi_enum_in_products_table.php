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
        // Mengubah ENUM untuk menambahkan 'new' ke tabel products
        // Di MySQL, kita perlu menggunakan raw SQL untuk mengubah ENUM
        DB::statement("ALTER TABLE products MODIFY COLUMN kondisi ENUM('great', 'good', 'bad', 'new', 'need service')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM tanpa 'new'
        // Update data dengan kondisi 'new' terlebih dahulu jika ada
        DB::table('products')
            ->where('kondisi', 'new')
            ->update(['kondisi' => 'good']);
        
        // Ubah kembali ENUM
        DB::statement("ALTER TABLE products MODIFY COLUMN kondisi ENUM('great', 'good', 'bad', 'need service')");
    }
};

