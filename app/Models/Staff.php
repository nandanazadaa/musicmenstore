<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Staff extends Model
{
    protected $table = 'staffs';
    
    protected $fillable = [
        'id_employee',
        'nama',
        'nomor_telepon',
        'jabatan',
        'photo_profile',
        'user_id',
        'points',
    ];

    /**
     * Get the user associated with this staff
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staff) {
            if (empty($staff->id_employee)) {
                $staff->id_employee = self::generateEmployeeId();
            }
        });
    }

    /**
     * Generate unique Employee ID
     * Format: EMP-YYYYMMDD-XXXX (e.g., EMP-20251214-0001)
     */
    public static function generateEmployeeId()
    {
        $date = date('Ymd');
        $prefix = 'EMP-' . $date . '-';
        
        // Get the last employee ID with the same date prefix
        $lastStaff = self::where('id_employee', 'like', $prefix . '%')
            ->orderBy('id_employee', 'desc')
            ->first();
        
        if ($lastStaff) {
            $lastNumber = intval(substr($lastStaff->id_employee, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $newNumber;
    }

    /**
     * Get assignments for this staff
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get unread assignments count
     */
    public function getUnreadAssignmentsCount()
    {
        return $this->assignments()->whereNull('read_at')->count();
    }

    /**
     * Get attendances for this staff
     */
    public function attendances()
    {
        return $this->hasMany(StaffAttendance::class);
    }

    /**
     * Get leave requests for this staff
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Check if staff has checked in today
     */
    public function hasCheckedInToday()
    {
        return $this->attendances()
            ->whereDate('attendance_date', today())
            ->whereNotNull('check_in_time')
            ->exists();
    }

    /**
     * Get today's attendance
     */
    public function getTodayAttendance()
    {
        return $this->attendances()
            ->whereDate('attendance_date', today())
            ->first();
    }

    /**
     * Get shift schedules for this staff
     */
    public function shiftSchedules()
    {
        return $this->hasMany(ShiftSchedule::class);
    }

    /**
     * Get points value in Rupiah
     */
    public function getPointsValueAttribute()
    {
        return $this->points * 1000;
    }

    /**
     * Get formatted points value
     */
    public function getFormattedPointsValueAttribute()
    {
        return 'Rp ' . number_format($this->points_value, 0, ',', '.');
    }
}
