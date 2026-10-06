<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'staff_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the staff that owns the leave request
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the user who approved the leave request
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-900/30 border-yellow-500/50 text-yellow-400',
            'approved' => 'bg-green-900/30 border-green-500/50 text-green-400',
            'rejected' => 'bg-red-900/30 border-red-500/50 text-red-400',
            default => 'bg-gray-900/30 border-gray-500/50 text-gray-400',
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Unknown',
        };
    }
}
