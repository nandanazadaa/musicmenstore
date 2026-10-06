<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    protected $fillable = [
        'staff_id',
        'sale_date',
        'shift',
        'shift_start',
        'shift_end',
        'total_offline_sales',
        'total_online_sales',
        'shopee_sales',
        'tokopedia_sales',
        'cash',
        'qris',
        'transfer',
        'cash_notes',
        'qris_notes',
        'transfer_notes',
        'cash_amount',
        'cash_notes_detail',
        'expenses',
        'expense_notes',
        'cash_for_next_shift',
        'total_cash_deposit',
        'notes',
        'statement',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'shift_start' => 'datetime',
        'shift_end' => 'datetime',
        'total_offline_sales' => 'decimal:2',
        'total_online_sales' => 'decimal:2',
        'shopee_sales' => 'decimal:2',
        'tokopedia_sales' => 'decimal:2',
        'cash' => 'decimal:2',
        'qris' => 'decimal:2',
        'transfer' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'expenses' => 'decimal:2',
        'cash_for_next_shift' => 'decimal:2',
        'total_cash_deposit' => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function getTotalSalesAttribute()
    {
        return $this->total_offline_sales + $this->total_online_sales + $this->shopee_sales + $this->tokopedia_sales;
    }

    public function getTotalIncomeAttribute()
    {
        return $this->cash + $this->qris + $this->transfer;
    }
}
