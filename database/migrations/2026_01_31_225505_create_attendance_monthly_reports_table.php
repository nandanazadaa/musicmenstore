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
        Schema::create('attendance_monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade'); 
            $table->string('month_year');
            $table->integer('total_points_achieved');
            $table->integer('total_presence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_monthly_reports');
    }
};
