<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LandingPageProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'kondisi',
        'image',
        'bonus',
        'harga',
        'category',
        'order',
        'description',
        'order_info',
        'payment_image',
        'slug'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Get category name
     */
    public function getCategoryNameAttribute(): string
    {
        $categories = [
            'acoustic' => 'Acoustic',
            'electric' => 'Electric',
            'bass' => 'Bass',
            'amplifier' => 'Amplifier',
            'effect' => 'Effect',
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    /**
     * Get kondisi name (formatted)
     */
    public function getKondisiNameAttribute(): string
    {
        $kondisi = [
            'new' => 'New',
            'great' => 'Great',
            'good' => 'Good',
            'used' => 'Used',
        ];

        return $kondisi[$this->kondisi] ?? ucfirst($this->kondisi);
    }

    /**
     * Boot method untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->nama_barang);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('nama_barang') && empty($product->slug)) {
                $product->slug = Str::slug($product->nama_barang);
            }
        });
    }

    /**
     * Get all images for this product
     */
    public function images()
    {
        return $this->hasMany(LandingPageProductImage::class, 'landing_page_product_id')->orderBy('order');
    }
}
