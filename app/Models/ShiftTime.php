<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get shift time by shift name
     */
    public static function getByShift($shift)
    {
        return self::where('shift', $shift)->first();
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute(): string
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }
}
