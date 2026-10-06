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
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('week_start_date'); // Tanggal mulai minggu (Senin)
            $table->date('week_end_date'); // Tanggal akhir minggu (Minggu)
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->enum('shift', ['pagi', 'malam']);
            $table->timestamps();
            
            // Unique constraint: satu staff hanya bisa punya satu shift per hari dalam satu minggu
            $table->unique(['week_start_date', 'staff_id', 'day'], 'unique_staff_day_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
    }
};
