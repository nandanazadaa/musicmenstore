<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StaffMembersController extends Controller
{
    /**
     * Display a listing of members
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        // Check if staff has members permission
        if (!Auth::user()->hasPermission('members')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat members.');
        }

        $query = Member::withCount('visits');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('staff.members.index', compact('members'));
    }

    public function sendWelcomeWa($id, \App\Services\WhatsappService $waService)
    {
        // Cek Akses Staff (Opsional sesuai sistem Anda)
        if (Auth::user()->role !== 'staff') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $member = Member::findOrFail($id);

        // Pesan sesuai gambar yang Anda berikan
        $message = "Halo, *" . $member->name . "*\n\n" .
            "Selamat datang di Musicmen Membership\n\n" .
            "Terima kasih sudah bergabung. Sebagai member, kamu berhak mendapatkan :\n\n" .
            "✅ Free pick & holder\n" .
            "✅ Free restring & cleaning standar lifetime\n" .
            "✅ Free garansi setup setiap pembelian instrument\n" .
            "✅ Reward menarik setiap 5x kunjungan ke offline store\n" .
            "✅ Mendapatkan info gear update dan ekslusif promo\n\n" .
            "Apabila ada pertanyaan atau butuh rekomendasi gitar, bass atau service instrument langsung chat saja men.\n\n" .
            "Thanks Men!\n\n".
            "> _Pesan dikirim otomatis dari sistem Musicmen Store_";

        $response = $waService->sendMessage($member->phone, $message);
        $resArray = json_decode($response, true);

        if (isset($resArray['status']) && $resArray['status'] == true) {
            return response()->json(['success' => true, 'message' => 'Pesan Welcome WA berhasil dikirim!']);
        }

        return response()->json(['success' => false, 'message' => 'Gagal kirim WA: ' . ($resArray['reason'] ?? 'Error Provider')]);
    }

    /**
     * Show the form for creating a new member
     */
    public function create()
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        // Check if staff has members permission
        if (!Auth::user()->hasPermission('members')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat member.');
        }

        return view('staff.members.create');
    }

    /**
     * Store a newly created member
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        // Check if staff has members permission
        if (!Auth::user()->hasPermission('members')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat member.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:members,phone|max:20',
            'email' => 'nullable|email|unique:members,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'nullable|string',
        ]);

        // Generate Member ID
        $prefix = 'MM';
        $date = date('Ymd');
        $random = strtoupper(Str::random(4));
        $memberId = $prefix . $date . $random;

        // Ensure member_id is unique
        while (Member::where('member_id', $memberId)->exists()) {
            $random = strtoupper(Str::random(4));
            $memberId = $prefix . $date . $random;
        }

        $member = Member::create([
            'member_id' => $memberId,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password,
            'address' => $request->address,
            'rank' => 'bronze',
            'status' => 'Bronze',
        ]);

        return redirect()->route('staff.members.index')->with('success', 'Member berhasil ditambahkan! ID Member: ' . $member->member_id);
    }

    /**
     * Show the form for viewing member details
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        // Check if staff has members permission
        if (!Auth::user()->hasPermission('members')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat members.');
        }

        $member = Member::withCount('visits')->findOrFail($id);

        return view('staff.members.show', compact('member'));
    }

    /**
     * Record visit for a member
     */
    public function recordVisit(Request $request, $id)
    {
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak.');
        }

        // Check if staff has members permission
        if (!Auth::user()->hasPermission('members')) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses untuk record visit.');
        }

        $member = Member::findOrFail($id);

        $request->validate([
            'visit_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        MemberVisit::create([
            'member_id' => $member->id,
            'visit_date' => $request->visit_date,
            'notes' => $request->notes,
        ]);

        // Refresh member to get latest visit count
        $member->refresh();
        $visitCount = $member->visits()->count();

        // Update status and rank based on visit count
        $member->updateStatusFromVisits();
        $member->refresh();

        $message = 'Kunjungan berhasil dicatat! Total kunjungan: ' . $visitCount;
        $message .= ' - Status: ' . $member->status;

        if ($visitCount >= 5) {
            $message .= ' - Member sudah eligible untuk mendapatkan reward!';
        }

        return back()->with('success', $message);
    }

    /**
     * Get Member by Member ID (for quick search)
     */
    public function getByMemberId($memberId)
    {
        if (Auth::user()->role !== 'staff') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!Auth::user()->hasPermission('members')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $member = Member::where('member_id', $memberId)->withCount('visits')->first();

        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        return response()->json([
            'id' => $member->id,
            'member_id' => $member->member_id,
            'name' => $member->name,
            'visit_count' => $member->visits_count,
        ]);
    }
}
