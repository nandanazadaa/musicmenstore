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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->integer('milestone')->comment('Kelipatan 5 dari visit count (5, 10, 15, 20, dst)');
            $table->string('nama_hadiah');
            $table->string('gambar_hadiah')->nullable();
            $table->text('kata_kata')->nullable();
            $table->timestamps();
            
            // Index untuk pencarian berdasarkan member dan milestone
            $table->index(['member_id', 'milestone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
