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
        // Drop unique constraint first
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['nomor_seri']);
        });
        
        // Then make it nullable
        Schema::table('products', function (Blueprint $table) {
            $table->string('nomor_seri')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Make not nullable first
            $table->string('nomor_seri')->nullable(false)->change();
        });
        
        // Then restore unique constraint
        Schema::table('products', function (Blueprint $table) {
            $table->unique('nomor_seri');
        });
    }
};
