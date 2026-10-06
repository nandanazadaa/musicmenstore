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
        Schema::create('daily_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->date('sale_date');
            $table->string('shift')->nullable(); // pagi/siang
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
            
            // Total Penjualan
            $table->decimal('total_offline_sales', 15, 2)->default(0);
            $table->decimal('total_online_sales', 15, 2)->default(0);
            $table->decimal('shopee_sales', 15, 2)->default(0);
            $table->decimal('tokopedia_sales', 15, 2)->default(0);
            
            // Pemasukan
            $table->decimal('cash', 15, 2)->default(0);
            $table->decimal('qris', 15, 2)->default(0);
            $table->decimal('transfer', 15, 2)->default(0);
            $table->text('cash_notes')->nullable();
            $table->text('qris_notes')->nullable();
            $table->text('transfer_notes')->nullable();
            
            // Uang Kas
            $table->decimal('cash_amount', 15, 2)->default(0); // Jumlah uang kas
            $table->text('cash_notes_detail')->nullable();
            
            // Pengeluaran
            $table->decimal('expenses', 15, 2)->default(0);
            $table->text('expense_notes')->nullable();
            
            // Setoran
            $table->decimal('cash_for_next_shift', 15, 2)->default(0);
            $table->decimal('total_cash_deposit', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('statement')->nullable();
            
            $table->timestamps();
            
            $table->index('sale_date');
            $table->index('staff_id');
            $table->unique(['staff_id', 'sale_date', 'shift']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sales');
    }
};
