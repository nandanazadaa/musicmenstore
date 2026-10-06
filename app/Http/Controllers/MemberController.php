<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberVisit;
use App\Models\Reward;
use App\Models\RewardClaim;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    // Generate Member ID
    private function generateMemberId()
    {
        $prefix = 'MM';
        $date = date('Ymd');
        $random = strtoupper(Str::random(4));
        return $prefix . $date . $random;
    }

    // Show Register Form
    public function showRegisterForm()
    {
        return view('member.register');
    }

    // Register member (nama + email + password)
    public function register(Request $request)
    {
        // 1. Validasi Input Utama
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|unique:members,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            // 2. Generate Data
            $otp = rand(1000, 9999);
            $memberId = $this->generateMemberId();

            // 3. Simpan Member ke Database
            // PERBAIKAN: Berikan default value untuk kolom yang mungkin tidak ada di form registrasi
            $member = Member::create([
                'member_id' => $memberId,
                'name'      => $request->name,
                'phone'     => $request->phone,
                'password'  => $request->password,
                'otp_code'  => $otp,
                'rank'      => 'bronze',
                'status'    => 'Pending',
                'email'     => $request->email ?? '',     // Tambahkan ini jika di DB email NOT NULL
                'address'   => $request->address ?? '',   // Tambahkan ini jika di DB address NOT NULL
            ]);

            // 4. Format Nomor WhatsApp
            $targetPhone = $member->phone;
            if (str_starts_with($targetPhone, '0')) {
                $targetPhone = '62' . substr($targetPhone, 1);
            }

            // 5. Kirim Pesan via WA
            $wa = new WhatsappService();
            $message = "Halo *" . $member->name . "*!\n\n" .
                "Terima kasih telah mendaftar di *Musicmen Store*.\n" .
                "Kode OTP Anda adalah: *" . $otp . "*\n\n" .
                "Silakan masukkan kode tersebut di halaman verifikasi.\n\n" .
                "> _Pesan dikirim otomatis dari sistem Musicmen Store_";

            $wa->sendMessage($targetPhone, $message);

            // 6. Simpan Session
            Session::put('pending_member_id', $member->id);
            Session::put('pending_phone', $member->phone);

            return redirect()->route('member.otp.view')->with('success', 'Registrasi berhasil! Kode OTP telah dikirim.');
        } catch (\Exception $e) {
            // PERBAIKAN: Tangkap error agar tidak tampil halaman 500
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()]);
        }
    }
    public function showOtpForm()
    {
        if (!Session::has('pending_member_id')) return redirect()->route('member.register');
        return view('member.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $memberId = Session::get('pending_member_id');
        $member = Member::find($memberId);

        if (!$member) {
            return redirect()->route('member.register')->with('error', 'Sesi berakhir, silakan daftar ulang.');
        }

        if ($member->otp_code == $request->otp) {
            // 1. Update status jadi aktif
            $member->update([
                'otp_code' => null,
                'status' => 'Bronze'
            ]);

            // --- TAMBAHKAN LOGIKA PESAN SAMBUTAN DI SINI ---
            try {
                $targetPhone = $member->phone;
                if (str_starts_with($targetPhone, '0')) {
                    $targetPhone = '62' . substr($targetPhone, 1);
                }

                $wa = new \App\Services\WhatsappService();
                $welcomeMessage = "Halo, *" . $member->name . "*\n\n" .
                    "Selamat datang di *Musicmen Membership*\n\n" .
                    "Terima kasih sudah bergabung. Sebagai member, kamu berhak mendapatkan:\n\n" .
                    "✅ *Free pick & holder*\n" .
                    "✅ *Free restring & cleaning standar lifetime*\n" .
                    "✅ *Free garansi setup setiap pembelian instrument*\n" .
                    "✅ *Reward menarik setiap 5x kunjungan ke offline store*\n" .
                    "✅ *Mendapatkan info gear update dan eksklusif promo*\n\n" .
                    "Apabila ada pertanyaan atau butuh rekomendasi gitar, bass atau service instrument langsung chat saja men.\n\n" .
                    "Thanks Men!\n\n" .
                    "> _Pesan dikirim otomatis dari sistem Musicmen Store_";

                $wa->sendMessage($targetPhone, $welcomeMessage);
            } catch (\Exception $e) {
                // Log error jika WA gagal kirim agar user tetap bisa login
                \Illuminate\Support\Facades\Log::error("Gagal kirim pesan sambutan: " . $e->getMessage());
            }
            // ----------------------------------------------

            // 2. Login otomatis
            Session::put('member_id', $member->id);
            Session::put('member_name', $member->name);
            Session::put('member_member_id', $member->member_id);

            Session::forget(['pending_member_id', 'pending_phone']);

            return redirect()->route('member.profile')->with('success', 'Selamat! Akun Anda telah aktif.');
        }

        return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
    }

    public function resendOtp()
    {
        // 1. Ambil ID dari session
        $memberId = Session::get('pending_member_id');
        $member = Member::find($memberId);

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Sesi berakhir.'], 400);
        }

        // 2. Generate OTP baru
        $newOtp = rand(1000, 9999);
        $member->update(['otp_code' => $newOtp]);

        // 3. Format nomor
        $targetPhone = $member->phone;
        if (str_starts_with($targetPhone, '0')) {
            $targetPhone = '62' . substr($targetPhone, 1);
        }

        // 4. Kirim ulang via WA
        $wa = new WhatsappService();
        $message = "Halo *" . $member->name . "*!\n\n" .
            "Ini adalah kode OTP baru Anda: *" . $newOtp . "*\n\n" .
            "Mohon segera masukkan kode tersebut untuk verifikasi.";

        $wa->sendMessage($targetPhone, $message);

        return back()->with('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    }

    /**
     * Get visit count (for realtime polling)
     */
    public function visitCount()
    {
        $memberId = Session::get('member_id');
        if (!$memberId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $member = Member::withCount('visits')->find($memberId);
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        $totalVisitCount = (int) $member->visits_count;

        // Logika Icon (Tetap 1-5)
        $progressInCycle = $totalVisitCount % 5;
        $displayCycleCount = ($totalVisitCount > 0 && $progressInCycle == 0) ? 5 : $progressInCycle;

        // Logika Teks Target (Dinamis 5, 10, 15...)
        $targetVisits = (floor(($totalVisitCount - 1) / 5) + 1) * 5;
        if ($targetVisits == 0) $targetVisits = 5;

        $currentMilestone = $targetVisits;

        $cycleReward = Reward::where('member_id', $member->id)
            ->where('milestone', $currentMilestone)
            ->first();

        $hasClaimedCurrentCycle = false;
        if ($cycleReward) {
            $hasClaimedCurrentCycle = RewardClaim::where('member_id', $member->id)
                ->where('reward_id', $cycleReward->id)
                ->where(function ($q) {
                    $q->where('status', 'fulfilled')->orWhere('status', 'requested');
                })->exists();
        }

        $showClaimButton = ($totalVisitCount > 0 && $totalVisitCount == $targetVisits) && !$hasClaimedCurrentCycle;

        return response()->json([
            'visit_count' => $displayCycleCount,      // Untuk nyalakan icon (1-5)
            'total_visit_count' => $totalVisitCount, // Untuk teks angka depan (misal: 6)
            'target' => $targetVisits,               // Untuk teks angka belakang (misal: 10)
            'show_claim_button' => $showClaimButton,
            'has_claimed_current_cycle' => $hasClaimedCurrentCycle,
        ]);
    }

    // (OTP methods removed - register tanpa OTP)

    // Show Login Form
    public function showLoginForm()
    {
        return view('member.login');
    }

    // Login Member
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric', // Validasi phone
            'password' => 'required|string',
        ]);

        // Cari berdasarkan phone
        $member = Member::where('phone', $request->phone)->first();

        if ($member && $member->checkPassword($request->password)) {
            Session::put('member_id', $member->id);
            Session::put('member_name', $member->name);
            Session::put('member_member_id', $member->member_id);

            return redirect()->route('member.profile')->with('success', 'Login successful!');
        }

        return back()->withErrors(['phone' => 'Nomor HP atau password salah.'])->withInput();
    }

    // Logout Member
    public function logout()
    {
        Session::forget(['member_id', 'member_name', 'member_member_id']);
        return redirect('/')->with('success', 'Logout successful!');
    }

    // Show Profile (Read-only)
    public function profile()
    {
        $memberId = Session::get('member_id');

        if (!$memberId) {
            return redirect()->route('member.login')->with('error', 'Please login first.');
        }

        $member = Member::withCount('visits')->find($memberId);

        if (!$member) {
            Session::forget(['member_id', 'member_name', 'member_member_id']);
            return redirect()->route('member.login')->with('error', 'Member not found.');
        }

        $totalVisitCount = (int) $member->visits_count;

        // 1. Logika Icon Gitar (Tetap 5 Icon)
        // Gunakan modulo agar 6/10 tetap menyalakan 1 gitar saja
        $progressInCycle = $totalVisitCount % 5;
        if ($totalVisitCount > 0 && $progressInCycle == 0) {
            $displayCycleCount = 5; // Jika pas kelipatan 5, nyalakan semua (5)
        } else {
            $displayCycleCount = $progressInCycle;
        }

        // 2. Logika Teks Target (Dinamis: 5, 10, 15...)
        // Ini yang akan tampil sebagai "6 / 10 Kunjungan"
        $targetVisits = (floor(($totalVisitCount - 1) / 5) + 1) * 5;
        if ($targetVisits == 0) $targetVisits = 5;

        // 3. Logika Milestone & Claim
        // Milestone selalu kelipatan 5 terdekat yang harus dicapai
        $currentMilestone = $targetVisits;

        $cycleReward = Reward::where('member_id', $member->id)
            ->where('milestone', $currentMilestone)
            ->first();

        $hasClaimedCurrentCycle = false;
        if ($cycleReward) {
            $hasClaimedCurrentCycle = RewardClaim::where('member_id', $member->id)
                ->where('reward_id', $cycleReward->id)
                ->where(function ($q) {
                    $q->where('status', 'fulfilled')->orWhere('status', 'requested');
                })->exists();
        }

        // Tombol claim hanya muncul jika total kunjungan PAS mencapai targetVisits (kelipatan 5)
        $showClaimButton = ($totalVisitCount > 0 && $totalVisitCount == $targetVisits) && !$hasClaimedCurrentCycle;

        $availableRewards = collect();
        if ($showClaimButton && $cycleReward) {
            $availableRewards = collect([$cycleReward]);
        }

        $rewardClaims = RewardClaim::with('reward')
            ->where('member_id', $member->id)
            ->orderBy('requested_at', 'desc')
            ->get();

        return view('member.profile', compact(
            'member',
            'totalVisitCount',
            'displayCycleCount',
            'targetVisits',
            'showClaimButton',
            'hasClaimedCurrentCycle',
            'availableRewards',
            'rewardClaims'
        ));
    }

    /**
     * Member claim reward
     */
    public function claimReward(Request $request)
    {
        $memberId = Session::get('member_id');

        if (!$memberId) {
            return redirect()->route('member.login')->with('error', 'Please login first.');
        }

        $request->validate([
            'reward_id' => 'required|exists:rewards,id',
        ]);

        $member = Member::withCount('visits')->findOrFail($memberId);
        $reward = Reward::where('id', $request->reward_id)
            ->where('member_id', $member->id)
            ->firstOrFail();

        // Cek apakah sudah mencapai milestone
        if ($member->visits_count < $reward->milestone) {
            return back()->with('error', 'Kunjungan belum mencapai milestone hadiah.');
        }

        // Cek apakah sudah ada claim aktif
        $existingClaim = RewardClaim::where('member_id', $member->id)
            ->where('reward_id', $reward->id)
            ->where('status', 'requested')
            ->first();

        if ($existingClaim) {
            return back()->with('error', 'Hadiah ini sudah diajukan claim. Mohon tunggu konfirmasi admin.');
        }

        RewardClaim::updateOrCreate(
            [
                'member_id' => $member->id,
                'reward_id' => $reward->id,
            ],
            [
                'status' => 'requested',
                'requested_at' => now(),
            ]
        );

        return back()->with('success', 'Claim hadiah berhasil dikirim. Silakan tunjukkan ke admin saat di toko.');
    }

    // Show Edit Profile Form
    public function showEditForm()
    {
        $memberId = Session::get('member_id');

        if (!$memberId) {
            return redirect()->route('member.login')->with('error', 'Please login first.');
        }

        $member = Member::find($memberId);

        if (!$member) {
            Session::forget(['member_id', 'member_name', 'member_member_id']);
            return redirect()->route('member.login')->with('error', 'Member not found.');
        }

        return view('member.edit', compact('member'));
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $memberId = Session::get('member_id');

        if (!$memberId) {
            return redirect()->route('member.login')->with('error', 'Please login first.');
        }

        $member = Member::find($memberId);

        if (!$member) {
            return redirect()->route('member.login')->with('error', 'Member not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan validasi phone unik kecuali milik sendiri
            'phone' => 'required|numeric|unique:members,phone,' . $member->id,
            'email' => 'nullable|email|unique:members,email,' . $member->id . '|max:255',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email, // Email jadi opsional saat update
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }

        $member->update($updateData);

        // Update session
        Session::put('member_name', $member->name);

        return redirect()->route('member.profile')->with('success', 'Profile updated successfully!');
    }

    // Get all members (for admin)
    public function getAllMembers()
    {
        return Member::orderBy('created_at', 'desc')->get();
    }

    // Get new members (for realtime updates)
    public function getNewMembers(Request $request)
    {
        $lastCheck = $request->input('last_check', now()->subMinutes(5)->toDateTimeString());

        $newMembers = Member::where('created_at', '>', $lastCheck)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'members' => $newMembers->map(function ($member) {
                return [
                    'id' => $member->id,
                    'member_id' => $member->member_id,
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'email' => $member->email,
                    'created_at' => $member->created_at->format('d M Y H:i'),
                    'created_at_raw' => $member->created_at->toDateTimeString(),
                ];
            }),
            'last_check' => now()->toDateTimeString(),
        ]);
    }
}
