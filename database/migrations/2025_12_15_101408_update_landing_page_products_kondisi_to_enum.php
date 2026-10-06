<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data first
        DB::table('landing_page_products')
            ->where('kondisi', 'Great Condition')
            ->orWhere('kondisi', 'like', '%Great%')
            ->update(['kondisi' => 'great']);
        
        DB::table('landing_page_products')
            ->where('kondisi', 'Good Condition')
            ->orWhere('kondisi', 'like', '%Good%')
            ->update(['kondisi' => 'good']);

        // Change column to enum
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->enum('kondisi', ['great', 'good'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_products', function (Blueprint $table) {
            $table->string('kondisi')->change();
        });
    }
};
