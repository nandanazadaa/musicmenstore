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
        // Update existing 'malam' to 'siang' in shift_schedules
        DB::table('shift_schedules')
            ->where('shift', 'malam')
            ->update(['shift' => 'siang']);

        // Modify enum to remove 'malam' and add 'siang' if not exists
        Schema::table('shift_schedules', function (Blueprint $table) {
            $table->enum('shift', ['pagi', 'siang'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'siang' back to 'malam'
        DB::table('shift_schedules')
            ->where('shift', 'siang')
            ->update(['shift' => 'malam']);

        Schema::table('shift_schedules', function (Blueprint $table) {
            $table->enum('shift', ['pagi', 'malam'])->change();
        });
    }
};
