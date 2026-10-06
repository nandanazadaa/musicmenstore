<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sops')) {
            Schema::create('sops', function (Blueprint $table) {
                $table->id();
                $table->string('judul_sop');
                $table->string('icon_visual')->default('TOKO / OUTLET');
                $table->string('deskripsi_singkat')->nullable();
                $table->text('isi_konten_sop');
                $table->string('dibuat_oleh')->default('Admin');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sops');
    }
};
