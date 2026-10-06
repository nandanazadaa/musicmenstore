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
        Schema::create('service_harians', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_masuk');
            $table->string('nama_customer');
            $table->string('whatsapp')->nullable();
            $table->string('merk');
            $table->string('tipe')->nullable();
            $table->string('instrumen');
            $table->string('jenis_service');
            $table->decimal('biaya_sparepart', 12, 2)->default(0);
            $table->decimal('biaya_jasa', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->string('eksekutor')->nullable();
            $table->enum('status_pengerjaan', ['proses', 'selesai'])->default('proses');
            $table->date('tanggal_selesai')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status_pengambilan', ['Belum diambil', 'Sudah diambil'])->default('Belum diambil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_harians');
    }
};
