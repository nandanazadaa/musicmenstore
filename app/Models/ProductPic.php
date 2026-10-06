<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPic extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'product_pics';

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'product_id',
        'pic_name',
        'fee_amount',
    ];

    /**
     * Relasi ke model Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}