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
        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->string('month'); // Format: YYYY-MM (e.g., 2025-11)
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('transport_food_allowance', 15, 2)->default(0);
            $table->decimal('overtime_fee', 15, 2)->default(0);
            $table->decimal('sales_fee', 15, 2)->default(0); // From Sales Instruments
            $table->decimal('service_fee', 15, 2)->default(0); // From Service Harian + Daily Sales
            $table->decimal('others_fee', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->timestamps();
            
            $table->unique(['staff_id', 'month']);
            $table->index('month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_slips');
    }
};
