<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffFee extends Model
{
    protected $fillable = [
        'staff_id',
        'month',
        'fee_type',
        'amount',
        'description',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
