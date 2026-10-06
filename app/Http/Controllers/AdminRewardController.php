<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Reward;
use App\Models\RewardClaim;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRewardController extends Controller
{
    /**
     * Display a listing of rewards
     */
    public function index(Request $request)
    {
        $query = Reward::with('member');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })->orWhere('nama_hadiah', 'like', "%{$search}%");
        }

        $rewards = $query->orderBy('created_at', 'desc')->paginate(10);

        $pendingClaims = RewardClaim::with(['member', 'reward'])
            ->where('status', 'requested')
            ->orderBy('requested_at', 'desc')
            ->get();

        $recentClaims = RewardClaim::with(['member', 'reward'])
            ->orderBy('requested_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.rewards.index', compact('rewards', 'pendingClaims', 'recentClaims'));
    }

    /**
     * Show the form for creating a new reward
     */
    public function create()
    {
        // Get members yang sudah mencapai kelipatan 5
        $members = Member::withCount('visits')
            ->having('visits_count', '>=', 5)
            ->orderBy('name')
            ->get();
        
        return view('admin.rewards.create', compact('members'));
    }

    /**
     * Store a newly created reward
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'milestone' => 'required|integer|min:5|multiple_of:5',
            'nama_hadiah' => 'required|string|max:255',
            'gambar_hadiah' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'kata_kata' => 'nullable|string',
        ]);

        // Check if member has reached the milestone
        $member = Member::withCount('visits')->findOrFail($request->member_id);
        if ($member->visits_count < $request->milestone) {
            return back()->withErrors(['milestone' => 'Member belum mencapai milestone ' . $request->milestone . ' kunjungan. Total kunjungan saat ini: ' . $member->visits_count]);
        }

        // Check if reward already exists for this member and milestone
        $existingReward = Reward::where('member_id', $request->member_id)
            ->where('milestone', $request->milestone)
            ->first();
        
        if ($existingReward) {
            return back()->withErrors(['milestone' => 'Hadiah untuk milestone ' . $request->milestone . ' sudah ada untuk member ini.']);
        }

        $data = [
            'member_id' => $request->member_id,
            'milestone' => $request->milestone,
            'nama_hadiah' => $request->nama_hadiah,
            'kata_kata' => $request->kata_kata,
        ];

        // Handle image upload
        if ($request->hasFile('gambar_hadiah')) {
            $data['gambar_hadiah'] = ImageUploadService::saveAsWebp(
                $request->file('gambar_hadiah'),
                'images',
                'reward-' . time() . '-' . Str::random(8)
            );
        }

        Reward::create($data);

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Hadiah berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified reward
     */
    public function edit($id)
    {
        $reward = Reward::with('member')->findOrFail($id);
        $members = Member::withCount('visits')
            ->having('visits_count', '>=', 5)
            ->orderBy('name')
            ->get();
        
        return view('admin.rewards.edit', compact('reward', 'members'));
    }

    /**
     * Update the specified reward
     */
    public function update(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);

        $request->validate([
            'member_id' => 'required|exists:members,id',
            'milestone' => 'required|integer|min:5|multiple_of:5',
            'nama_hadiah' => 'required|string|max:255',
            'gambar_hadiah' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'kata_kata' => 'nullable|string',
        ]);

        // Check if member has reached the milestone
        $member = Member::withCount('visits')->findOrFail($request->member_id);
        if ($member->visits_count < $request->milestone) {
            return back()->withErrors(['milestone' => 'Member belum mencapai milestone ' . $request->milestone . ' kunjungan. Total kunjungan saat ini: ' . $member->visits_count]);
        }

        // Check if reward already exists for this member and milestone (excluding current reward)
        $existingReward = Reward::where('member_id', $request->member_id)
            ->where('milestone', $request->milestone)
            ->where('id', '!=', $id)
            ->first();
        
        if ($existingReward) {
            return back()->withErrors(['milestone' => 'Hadiah untuk milestone ' . $request->milestone . ' sudah ada untuk member ini.']);
        }

        $data = [
            'member_id' => $request->member_id,
            'milestone' => $request->milestone,
            'nama_hadiah' => $request->nama_hadiah,
            'kata_kata' => $request->kata_kata,
        ];

        // Handle image upload
        if ($request->hasFile('gambar_hadiah')) {
            // Delete old image
            if ($reward->gambar_hadiah && file_exists(public_path($reward->gambar_hadiah))) {
                unlink(public_path($reward->gambar_hadiah));
            }
            
            $data['gambar_hadiah'] = ImageUploadService::saveAsWebp(
                $request->file('gambar_hadiah'),
                'images',
                'reward-' . time() . '-' . Str::random(8)
            );
        }

        $reward->update($data);

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Hadiah berhasil diupdate!');
    }

    /**
     * Remove the specified reward
     */
    public function destroy($id)
    {
        $reward = Reward::findOrFail($id);
        
        // Delete image if exists
        if ($reward->gambar_hadiah && file_exists(public_path($reward->gambar_hadiah))) {
            unlink(public_path($reward->gambar_hadiah));
        }
        
        $reward->delete();

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Hadiah berhasil dihapus!');
    }
}
