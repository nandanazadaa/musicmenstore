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
        Schema::create('landing_page_products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('kondisi');
            $table->string('image')->nullable();
            $table->text('bonus')->nullable();
            $table->decimal('harga', 15, 2);
            $table->enum('category', ['acoustic', 'electric', 'bass', 'amplifier', 'effect']);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_products');
    }
};
