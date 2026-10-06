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
        Schema::table('otp_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('otp_verifications', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            $table->string('phone')->nullable()->change();
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('otp_verifications', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
