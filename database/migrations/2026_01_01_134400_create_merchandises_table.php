<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_merchandises_table.php
    public function up()
    {
        Schema::create('merchandises', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->integer('harga');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchandises');
    }
};
