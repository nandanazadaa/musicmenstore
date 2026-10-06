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
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->date('attendance_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->string('check_in_address')->nullable();
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();
            $table->string('check_out_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['staff_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
    }
};
