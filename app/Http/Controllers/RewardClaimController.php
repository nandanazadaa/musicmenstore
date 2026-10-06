<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RewardClaim;

class RewardClaimController extends Controller
{
    /**
     * Mark claim as fulfilled (admin)
     */
    public function fulfill(Request $request, $id)
    {
        $claim = RewardClaim::with(['member', 'reward'])->findOrFail($id);

        $claim->update([
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
        ]);

        return back()->with('success', 'Hadiah sudah ditandai sebagai claimed.');
    }
}
