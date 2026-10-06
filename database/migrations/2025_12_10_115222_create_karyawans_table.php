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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique()->comment('Nomor Induk Pegawai');
            $table->string('nama');
            $table->string('email')->unique()->nullable();
            $table->string('telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jabatan')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
