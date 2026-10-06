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
        Schema::create('office_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'office_latitude', 'office_longitude', 'allowed_radius'
            $table->text('value');
            $table->timestamps();
        });

        // Insert default values
        DB::table('office_settings')->insert([
            ['key' => 'office_latitude', 'value' => '-6.2088', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'office_longitude', 'value' => '106.8456', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'allowed_radius', 'value' => '500', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_settings');
    }
};
