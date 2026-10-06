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
        Schema::create('shift_times', function (Blueprint $table) {
            $table->id();
            $table->enum('shift', ['pagi', 'siang'])->unique();
            $table->time('start_time'); // Jam mulai shift
            $table->time('end_time'); // Jam selesai shift
            $table->timestamps();
        });

        // Insert default values
        DB::table('shift_times')->insert([
            ['shift' => 'pagi', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['shift' => 'siang', 'start_time' => '13:00:00', 'end_time' => '21:00:00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_times');
    }
};
