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
        Schema::table('service_harians', function (Blueprint $table) {
            $table->decimal('fee_staff', 12, 2)->default(0)->after('biaya_jasa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_harians', function (Blueprint $table) {
            $table->dropColumn('fee_staff');
        });
    }
};
