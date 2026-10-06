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
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->decimal('performance_incentive', 15, 2)->default(0)->after('others_fee');
            $table->integer('total_points')->default(0)->after('performance_incentive'); // Total point untuk bulan tersebut
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dropColumn(['performance_incentive', 'total_points']);
        });
    }
};
