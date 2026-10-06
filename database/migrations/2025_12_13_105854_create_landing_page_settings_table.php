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
        Schema::create('landing_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // header, main, about, brands, etc.
            $table->string('key'); // logo, subtitle, etc.
            $table->text('value')->nullable(); // path to image or text content
            $table->timestamps();
            
            $table->unique(['section', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_settings');
    }
};
