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
        Schema::create('reward_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('reward_id')->constrained('rewards')->onDelete('cascade');
            $table->string('status')->default('requested'); // requested, fulfilled
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('fulfilled_at')->nullable();
            $table->timestamps();
            $table->unique(['member_id', 'reward_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_claims');
    }
};
