<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->text('description')->nullable(); // Untuk Deskripsi (Text)
            $table->text('order_info')->nullable();  // Untuk Order Info (Text)
            $table->string('payment_image')->nullable(); // Untuk Payment Options (Path Gambar)
        });
    }
    
    public function down()
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->dropColumn(['description', 'order_info', 'payment_image']);
        });
    }
};
