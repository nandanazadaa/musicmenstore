<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar data bisa disimpan ke database
    protected $fillable = ['image', 'order'];
}