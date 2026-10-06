<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('nomor_seri')->unique();
            $table->string('tempat_pembuatan')->nullable();
            $table->string('tahun_pembuatan');
            $table->enum('instrumen', ['elektrik', 'akustik', 'bass']);
            $table->string('warna');
            $table->text('kelengkapan')->nullable();
            $table->enum('kondisi', ['great', 'good', 'bad']);
            $table->enum('setting_setup', ['Done', 'Pending'])->default('Pending');
            $table->enum('price_hologram', ['Done', 'Pending'])->default('Pending');
            $table->enum('input_cashier', ['Done', 'Pending'])->default('Pending');
            $table->text('catatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('pelaksanaan')->default(false);
            $table->string('image')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
