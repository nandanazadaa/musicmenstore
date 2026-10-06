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
        Schema::create('sales_instruments', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_barang');
            $table->enum('type', ['electric', 'acoustic', 'bass', 'amplifier', 'effect']);
            $table->string('sales'); // Nama sales person
            $table->decimal('jumlah_fee', 15, 2);
            $table->text('keterangan')->nullable();
            $table->enum('kondisi', ['great', 'good'])->nullable();
            $table->timestamps();
            
            // Index for better query performance
            $table->index('tanggal');
            $table->index('type');
            $table->index('sales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_instruments');
    }
};
