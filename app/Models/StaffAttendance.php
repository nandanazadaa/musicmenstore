<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAttendance extends Model
{
    protected $fillable = [
        'staff_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_address',
        'check_in_token',
        'point_change',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_address',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    /**
     * Get the staff that owns the attendance
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     * Returns distance in meters
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
