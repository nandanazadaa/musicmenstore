<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'member_id',
        'milestone',
        'nama_hadiah',
        'gambar_hadiah',
        'kata_kata',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
