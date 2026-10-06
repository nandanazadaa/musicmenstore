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
            // Add new fields
            if (!Schema::hasColumn('products', 'harga_pembelian')) {
                $table->string('harga_pembelian')->nullable()->after('kelengkapan');
            }
            
            if (!Schema::hasColumn('products', 'fee')) {
                $table->string('fee')->nullable()->after('harga_pembelian');
            }
            
            if (!Schema::hasColumn('products', 'pic')) {
                $table->string('pic')->nullable()->after('fee');
            }
            
            if (!Schema::hasColumn('products', 'input_system')) {
                $table->enum('input_system', ['Sudah', 'Belum'])->default('Belum')->after('input_cashier');
            }
            
            // Add type field if it doesn't exist
            if (!Schema::hasColumn('products', 'type')) {
                $table->enum('type', ['electric', 'acoustic', 'effect', 'amplifier', 'bass'])->nullable()->after('fee');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'harga_pembelian')) {
                $table->dropColumn('harga_pembelian');
            }
            
            if (Schema::hasColumn('products', 'fee')) {
                $table->dropColumn('fee');
            }
            
            if (Schema::hasColumn('products', 'pic')) {
                $table->dropColumn('pic');
            }
            
            if (Schema::hasColumn('products', 'input_system')) {
                $table->dropColumn('input_system');
            }
            
            if (Schema::hasColumn('products', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
