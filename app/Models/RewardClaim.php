<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardClaim extends Model
{
    protected $fillable = [
        'member_id',
        'reward_id',
        'status',
        'requested_at',
        'fulfilled_at',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
