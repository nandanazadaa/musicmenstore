<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_sop',
        'icon_visual',
        'deskripsi_singkat',
        'isi_konten_sop',
        'dibuat_oleh'
    ];
}