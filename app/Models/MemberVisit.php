<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberVisit extends Model
{
    protected $fillable = [
        'member_id',
        'visit_date',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
