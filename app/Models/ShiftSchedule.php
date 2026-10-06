<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_start_date',
        'week_end_date',
        'staff_id',
        'day',
        'shift',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date',
    ];

    /**
     * Get the staff that owns the shift schedule
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get day name in Indonesian
     */
    public function getDayNameAttribute(): string
    {
        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];

        return $days[$this->day] ?? $this->day;
    }

    /**
     * Get shift name formatted
     */
    public function getShiftNameAttribute(): string
    {
        return ucfirst($this->shift);
    }

    // Tambahkan fungsi ini di dalam class ShiftSchedule
    public function isLibur(): bool
    {
        return strtolower($this->shift) === 'libur';
    }
}
