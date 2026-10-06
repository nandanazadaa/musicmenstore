<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    protected $fillable = [
        'staff_id',
        'month',
        'basic_salary',
        'transport_food_allowance',
        'overtime_fee',
        'sales_fee',
        'service_fee',
        'others_fee',
        'others_fee_details',
        'cash_advance',
        'cash_advance_notes',
        'performance_incentive',
        'total_points',
        'total',
        // Kolom bank (opsional, ada di tabel lama)
        'bank_name',
        'account_name',
        'bank_account',
    ];

    protected $casts = [
        'basic_salary'             => 'decimal:2',
        'transport_food_allowance' => 'decimal:2',
        'overtime_fee'             => 'decimal:2',
        'sales_fee'                => 'decimal:2',
        'service_fee'              => 'decimal:2',
        'others_fee'               => 'decimal:2',
        'others_fee_details'       => 'array',
        'cash_advance'             => 'decimal:2',
        'performance_incentive'    => 'decimal:2',
        'total_points'             => 'integer',
        'total'                    => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // ── Accessor: bank info diambil dari staff jika kolom tidak ada di salary_slips ──

    public function getBankNameDisplayAttribute(): string
    {
        return $this->bank_name ?? $this->staff?->bank_name ?? '-';
    }

    public function getBankAccountDisplayAttribute(): string
    {
        return $this->bank_account ?? $this->staff?->bank_account ?? '-';
    }

    public function getAccountNameDisplayAttribute(): string
    {
        return $this->account_name ?? $this->staff?->account_name ?? '-';
    }

    // ── Formatted total ──────────────────────────────────────────────────────

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total, 0, ',', '.');
    }

    /**
     * Take-home pay = total gaji - cash advance
     */
    public function getTakeHomePayAttribute(): float
    {
        return max(0, (float) $this->total - (float) $this->cash_advance);
    }

    public function getFormattedTakeHomePayAttribute(): string
    {
        return 'Rp ' . number_format($this->take_home_pay, 0, ',', '.');
    }
}