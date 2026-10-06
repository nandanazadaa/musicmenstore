<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'staff_id',
        'title',
        'description',
        'status',
        'created_by',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the staff that owns the assignment
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the user who created the assignment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if assignment is unread
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }
}
