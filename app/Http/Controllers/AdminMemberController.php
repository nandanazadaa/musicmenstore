<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberVisit;
use App\Models\Reward;
use App\Exports\MembersExport;
use App\Services\ImageUploadService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMemberController extends Controller
{
    // Generate Member ID
    private function generateMemberId()
    {
        $prefix = 'MM';
        $date = date('Ymd');
        $random = strtoupper(Str::random(4));
        return $prefix . $date . $random;
    }

    // Tambahkan di dalam class
    public function sendWelcomeWa($id, \App\Services\WhatsappService $waService)
    {
        $member = Member::findOrFail($id);

        $message = "Halo, *" . $member->name . "*\n\n" .
            "Selamat datang di Musicmen Membership\n\n" .
            "Terima kasih sudah bergabung. Sebagai member, kamu berhak mendapatkan :\n\n" .
            "✅ Free pick & holder\n" .
            "✅ Free restring & cleaning standar lifetime\n" .
            "✅ Free garansi setup setiap pembelian instrument\n" .
            "✅ Reward menarik setiap 5x kunjungan ke offline store\n" .
            "✅ Mendapatkan info gear update dan ekslusif promo\n\n" .
            "Apabila ada pertanyaan atau butuh rekomendasi gitar, bass atau service instrument langsung chat saja men.\n\n" .
            "Thanks Men!.\n\n" .
            "> _Pesan dikirim otomatis dari sistem Musicmen Store_";

        $response = $waService->sendMessage($member->phone, $message);
        $resArray = json_decode($response, true);

        if (isset($resArray['status']) && $resArray['status'] == true) {
            return response()->json(['success' => true, 'message' => 'Pesan WA berhasil dikirim!']);
        }

        return response()->json(['success' => false, 'message' => 'Gagal kirim WA: ' . ($resArray['reason'] ?? 'Error')]);
    }

    // List Members with Datatable
    public function index(Request $request)
    {
        // Mark that admin has viewed this page - clear notification badge
        session(['members_last_viewed' => now()->toISOString()]);

        $query = Member::withCount('visits');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Datatable pagination
        if ($request->ajax()) {
            $members = $query->orderBy('created_at', 'desc')->paginate(10);

            return response()->json([
                'data' => $members->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'member_id' => $member->member_id,
                        'name' => $member->name,
                        'phone' => $member->phone,
                        'email' => $member->email ?? '-',
                        'visit_count' => $member->visits_count,
                        'rank' => $member->rank ?? 'bronze',
                        'status' => $member->status ?? 'Bronze',
                        'eligible' => $member->visits_count >= 5,
                        'created_at' => $member->created_at->format('d M Y'),
                    ];
                }),
                'current_page' => $members->currentPage(),
                'last_page' => $members->lastPage(),
                'total' => $members->total(),
            ]);
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.members.index', compact('members'));
    }

    // Show Create Form
    public function create()
    {
        return view('admin.members.create');
    }

    // Store New Member
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:members,phone|max:20',
            'email' => 'nullable|email|unique:members,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'nullable|string',
        ]);

        $memberId = $this->generateMemberId();

        // Ensure member_id is unique
        while (Member::where('member_id', $memberId)->exists()) {
            $memberId = $this->generateMemberId();
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

        return redirect()->route('admin.members.index')->with('success', 'Member berhasil ditambahkan! ID Member: ' . $member->member_id);
    }

    // Show Edit Form
    public function edit($id)
    {
        $member = Member::withCount('visits')
            ->with(['rewards' => function ($q) {
                $q->orderBy('milestone', 'asc');
            }])
            ->findOrFail($id);

        // Update rank berdasarkan visit count
        $member->updateStatusFromVisits();
        $member->refresh();

        return view('admin.members.edit', compact('member'));
    }

    // Update Member
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:members,phone,' . $member->id . '|max:20',
            'email' => 'nullable|email|unique:members,email,' . $member->id . '|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'address' => 'nullable|string',
            // Input hadiah (opsional)
            'reward_milestone' => 'nullable|integer|min:5|multiple_of:5',
            'reward_nama' => 'nullable|string|max:255',
            'reward_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'reward_kata' => 'nullable|string',
        ]);

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }

        $member->update($updateData);

        /**
         * Simpan hadiah (opsional) bila field diisi.
         * Hanya diproses jika nama hadiah atau milestone dikirim.
         */
        if ($request->filled('reward_milestone') || $request->filled('reward_nama')) {
            $visitCount = $member->visits()->count();

            // Pastikan member sudah mencapai milestone yang dipilih
            if ($visitCount < $request->reward_milestone) {
                return back()->withErrors([
                    'reward_milestone' => 'Member belum mencapai ' . $request->reward_milestone . ' kunjungan (saat ini ' . $visitCount . ').'
                ])->withInput();
            }

            // Nama hadiah wajib jika mau simpan
            if (!$request->filled('reward_nama')) {
                return back()->withErrors([
                    'reward_nama' => 'Nama hadiah wajib diisi.'
                ])->withInput();
            }

            $rewardData = [
                'member_id' => $member->id,
                'milestone' => $request->reward_milestone,
                'nama_hadiah' => $request->reward_nama,
                'kata_kata' => $request->reward_kata,
            ];

            // Upload gambar bila ada
            if ($request->hasFile('reward_gambar')) {
                // Hapus gambar lama jika update existing reward
                $existing = Reward::where('member_id', $member->id)
                    ->where('milestone', $request->reward_milestone)
                    ->first();

                if ($existing && $existing->gambar_hadiah && file_exists(public_path($existing->gambar_hadiah))) {
                    @unlink(public_path($existing->gambar_hadiah));
                }

                $rewardData['gambar_hadiah'] = ImageUploadService::saveAsWebp(
                    $request->file('reward_gambar'),
                    'images',
                    'reward-' . time() . '-' . Str::random(8)
                );
            }

            Reward::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'milestone' => $request->reward_milestone,
                ],
                $rewardData
            );
        }

        return redirect()->route('admin.members.index')->with('success', 'Member berhasil diupdate!');
    }

    // Delete Member
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Member berhasil dihapus!');
    }

    // Record Visit
    public function recordVisit(Request $request, $id)
    {
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
            $message .= ' - Member sudah eligible untuk mendapatkan aksesori!';
        }

        return back()->with('success', $message);
    }

    // Get Member by Member ID (for quick search)
    public function getByMemberId($memberId)
    {
        $member = Member::where('member_id', $memberId)->withCount('visits')->first();

        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        return response()->json([
            'id' => $member->id,
            'member_id' => $member->member_id,
            'name' => $member->name,
            'phone' => $member->phone,
            'email' => $member->email,
            'visit_count' => $member->visits_count,
            'rank' => $member->rank ?? 'bronze',
            'status' => $member->status ?? 'Bronze',
            'eligible' => $member->visits_count >= 5,
        ]);
    }

    // Export Members to Excel
    public function export()
    {
        return Excel::download(new MembersExport, 'members-' . date('Y-m-d') . '.xlsx');
    }
}
