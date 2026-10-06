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
            // Make old fields nullable that are no longer in the form
            if (Schema::hasColumn('products', 'tahun_pembuatan')) {
                $table->string('tahun_pembuatan')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'instrumen')) {
                $table->enum('instrumen', ['elektrik', 'akustik', 'bass'])->nullable()->change();
            }
            if (Schema::hasColumn('products', 'warna')) {
                $table->string('warna')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'setting_setup')) {
                $table->enum('setting_setup', ['Done', 'Pending'])->nullable()->change();
            }
            if (Schema::hasColumn('products', 'price_hologram')) {
                $table->enum('price_hologram', ['Done', 'Pending'])->nullable()->change();
            }
            if (Schema::hasColumn('products', 'input_cashier')) {
                $table->enum('input_cashier', ['Done', 'Pending'])->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Restore old fields to not nullable (with default values)
            if (Schema::hasColumn('products', 'tahun_pembuatan')) {
                $table->string('tahun_pembuatan')->nullable(false)->default('')->change();
            }
            if (Schema::hasColumn('products', 'instrumen')) {
                $table->enum('instrumen', ['elektrik', 'akustik', 'bass'])->nullable(false)->default('elektrik')->change();
            }
            if (Schema::hasColumn('products', 'warna')) {
                $table->string('warna')->nullable(false)->default('')->change();
            }
            if (Schema::hasColumn('products', 'setting_setup')) {
                $table->enum('setting_setup', ['Done', 'Pending'])->nullable(false)->default('Pending')->change();
            }
            if (Schema::hasColumn('products', 'price_hologram')) {
                $table->enum('price_hologram', ['Done', 'Pending'])->nullable(false)->default('Pending')->change();
            }
            if (Schema::hasColumn('products', 'input_cashier')) {
                $table->enum('input_cashier', ['Done', 'Pending'])->nullable(false)->default('Pending')->change();
            }
        });
    }
};
