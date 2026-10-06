<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function additionalPics() {
        return $this->hasMany(ProductPic::class);
    }

    public function images() {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    protected static function boot() {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->nama_barang) . '-' . Str::random(5);
            }
        });
    }
}