<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nama_barang');
        });

        // Generate slug for existing products using DB facade
        $products = DB::table('landing_page_products')->whereNull('slug')->get();
        foreach ($products as $product) {
            $slug = Str::slug($product->nama_barang);
            // Ensure unique slug
            $uniqueSlug = $slug;
            $counter = 1;
            while (DB::table('landing_page_products')->where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $slug . '-' . $counter;
                $counter++;
            }
            
            DB::table('landing_page_products')
                ->where('id', $product->id)
                ->update(['slug' => $uniqueSlug]);
        }

        // Make slug unique and not nullable after generating slugs
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
