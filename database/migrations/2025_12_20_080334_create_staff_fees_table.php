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
        Schema::create('staff_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->string('month'); // Format: YYYY-MM
            $table->string('fee_type'); // 'sales', 'service', 'others'
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('source_type')->nullable(); // 'sales_instrument', 'service_harian', 'daily_sales'
            $table->unsignedBigInteger('source_id')->nullable(); // ID dari source
            $table->timestamps();
            
            $table->index(['staff_id', 'month']);
            $table->index('fee_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_fees');
    }
};
