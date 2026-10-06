<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    protected $fillable = [
        'user_id',
        'permission_key',
    ];

    /**
     * Get the user that owns the permission
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
