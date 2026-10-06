<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_product_id',
        'image_path',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(LandingPageProduct::class, 'landing_page_product_id');
    }
}
