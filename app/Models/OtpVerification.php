<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    protected $fillable = [
        'phone',
        'email',
        'otp_code',
        'expires_at',
        'is_verified',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Generate OTP code
     */
    public static function generateCode($length = 6)
    {
        return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Check if OTP is valid
     */
    public function isValid()
    {
        return !$this->is_verified && 
               $this->expires_at->isFuture() && 
               $this->attempts < 5;
    }

    /**
     * Mark as verified
     */
    public function markAsVerified()
    {
        $this->update([
            'is_verified' => true,
        ]);
    }

    /**
     * Increment attempts
     */
    public function incrementAttempts()
    {
        $this->increment('attempts');
    }
}
