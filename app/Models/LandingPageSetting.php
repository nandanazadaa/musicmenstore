<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingPageSetting extends Model
{
    protected $fillable = [
        'section',
        'key',
        'value',
    ];

    /**
     * Get setting value by section and key
     */
    public static function getValue($section, $key, $default = null)
    {
        $settings = Cache::remember("landing_page_settings.{$section}", now()->addHour(), function () use ($section) {
            return self::where('section', $section)
                ->pluck('value', 'key')
                ->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set setting value
     */
    public static function setValue($section, $key, $value)
    {
        $setting = self::updateOrCreate(
            ['section' => $section, 'key' => $key],
            ['value' => $value]
        );

        Cache::forget("landing_page_settings.{$section}");

        return $setting;
    }
}
