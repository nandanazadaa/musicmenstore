<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'type',
        'name',
        'logo',
        'order',
    ];

    /**
     * Get guitar brands
     */
    public static function getGuitarBrands()
    {
        return self::where('type', 'guitar')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get accessories brands
     */
    public static function getAccessoriesBrands()
    {
        return self::where('type', 'accessories')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }
}
