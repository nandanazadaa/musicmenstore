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
        Schema::table('products', function (Blueprint $table) {
            // Make tempat_pembuatan nullable
            $table->string('tempat_pembuatan')->nullable()->change();
            
            // Add new fields if they don't exist
            if (!Schema::hasColumn('products', 'price_hologram')) {
                $table->enum('price_hologram', ['Done', 'Pending'])->default('Pending')->after('setting_setup');
            }
            
            if (!Schema::hasColumn('products', 'input_cashier')) {
                $table->enum('input_cashier', ['Done', 'Pending'])->default('Pending')->after('price_hologram');
            }
            
            // Remove price column if exists
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('tempat_pembuatan')->nullable(false)->change();
            
            if (Schema::hasColumn('products', 'price_hologram')) {
                $table->dropColumn('price_hologram');
            }
            
            if (Schema::hasColumn('products', 'input_cashier')) {
                $table->dropColumn('input_cashier');
            }
            
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 15, 2)->nullable();
            }
        });
    }
};
