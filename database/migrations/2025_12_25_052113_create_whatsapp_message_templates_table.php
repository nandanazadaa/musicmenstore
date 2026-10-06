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
        Schema::create('whatsapp_message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // 'service_harian' untuk service harian
            $table->text('message_template'); // Template pesan dengan placeholder seperti {nama_customer}, {merk}, dll
            $table->timestamps();
        });

        // Insert default template
        DB::table('whatsapp_message_templates')->insert([
            'type' => 'service_harian',
            'message_template' => 'Halo {nama_customer} 

Terima kasih sudah mempercayakan service {instrumen} {merk} {tipe} Anda kepada kami.

*Detail Service:*
• Tanggal Masuk: {tanggal_masuk}
• Jenis Service: {jenis_service}
• Status: {status_pengerjaan}

Kami akan menginformasikan update progress service Anda.

Terima kasih! ',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_message_templates');
    }
};
