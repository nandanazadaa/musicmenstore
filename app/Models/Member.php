<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Member extends Model
{
protected $fillable = [
    'member_id',
    'name',
    'phone',
    'password',
    'otp_code', // Tambahkan ini
    'rank',
    'status',
    'address',
    'email',
];

protected $hidden = [
    'password',
];

public function setPasswordAttribute($value)
{
    $this->attributes['password'] = Hash::make($value);
}

public function checkPassword($password)
{
    return Hash::check($password, $this->password);
}

public function visits()
{
    return $this->hasMany(MemberVisit::class);
}

public function rewards()
{
    return $this->hasMany(Reward::class);
}

public function rewardClaims()
{
    return $this->hasMany(RewardClaim::class);
}

public function getVisitCountAttribute()
{
    return $this->visits()->count();
}

public function getEligibleForRewardAttribute()
{
    return $this->visit_count >= 5;
}

// Get rank based on visit count
// Member baru langsung Bronze (0 visit), Silver di 5, Gold di 10, Diamond di 15, Ruby di 20
public function getRankFromVisits($visitCount)
{
    if ($visitCount >= 20) {
        return ['rank' => 'ruby', 'status' => 'Ruby'];
    } elseif ($visitCount >= 15) {
        return ['rank' => 'diamond', 'status' => 'Diamond'];
    } elseif ($visitCount >= 10) {
        return ['rank' => 'gold', 'status' => 'Gold'];
    } elseif ($visitCount >= 5) {
        return ['rank' => 'silver', 'status' => 'Silver'];
    } else {
        // Member baru langsung Bronze
        return ['rank' => 'bronze', 'status' => 'Bronze'];
    }
}

// Update status and rank based on visit count
public function updateStatusFromVisits()
{
    $visitCount = $this->visits()->count();
    $rankData = $this->getRankFromVisits($visitCount);

    $this->update([
        'rank' => $rankData['rank'],
        'status' => $rankData['status'],
    ]);
}
}
