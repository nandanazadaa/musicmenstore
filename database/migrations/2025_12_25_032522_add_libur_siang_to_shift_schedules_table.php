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
        // Modify enum to include 'libur' and 'siang'
        DB::statement("ALTER TABLE shift_schedules MODIFY COLUMN shift ENUM('pagi', 'siang', 'malam', 'libur') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        DB::statement("ALTER TABLE shift_schedules MODIFY COLUMN shift ENUM('pagi', 'malam') NOT NULL");
    }
};
