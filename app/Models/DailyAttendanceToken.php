<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DailyAttendanceToken extends Model
{
    protected $fillable = [
        'token_date',
        'token',
    ];

    protected $casts = [
        'token_date' => 'date',
    ];

    /**
     * Get current token based on 60-second intervals (1 minute)
     * Token changes every 60 seconds (1 minute)
     */
    public static function getCurrentToken()
    {
        $now = now();
        $today = $now->format('Y-m-d');
        
        // Calculate which 60-second interval we're in (0-1439 intervals per day)
        $secondsSinceMidnight = $now->secondsSinceMidnight();
        $intervalNumber = intval($secondsSinceMidnight / 60);
        
        // Generate token based on date + interval number (deterministic but appears random)
        $seed = $today . '-' . $intervalNumber;
        $token = self::generateTokenFromSeed($seed);
        
        return $token;
    }

    /**
     * Get next token change time (in seconds)
     */
    public static function getNextTokenTime()
    {
        $now = now();
        $secondsSinceMidnight = $now->secondsSinceMidnight();
        $currentInterval = intval($secondsSinceMidnight / 60);
        $nextInterval = $currentInterval + 1;
        $nextIntervalSeconds = $nextInterval * 60;
        
        // Calculate seconds until next interval
        $secondsUntilNext = $nextIntervalSeconds - $secondsSinceMidnight;
        
        return $secondsUntilNext;
    }

    /**
     * Generate deterministic token from seed
     */
    private static function generateTokenFromSeed($seed)
    {
        // Use hash to create deterministic but random-looking token
        $hash = md5($seed);
        // Take first 6 characters and convert to uppercase alphanumeric
        $token = strtoupper(substr($hash, 0, 6));
        // Ensure it's alphanumeric only
        $token = preg_replace('/[^A-Z0-9]/', 'A', $token);
        return $token;
    }

    /**
     * Validate token for current 60-second interval (1 minute)
     */
    public static function validateToken($token)
    {
        $currentToken = self::getCurrentToken();
        return strtoupper($token) === $currentToken;
    }

    /**
     * Get or generate today's token (legacy method - kept for compatibility)
     */
    public static function getTodayToken()
    {
        return self::getCurrentToken();
    }
}
