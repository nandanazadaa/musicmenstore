<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MemberController;
use App\Services\ImageUploadService;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Menampilkan dashboard admin/staff
     */
    public function dashboard()
    {
        $memberController = new MemberController();
        $members = $memberController->getAllMembers();
        $totalMembers = $members->count();

        // Get total staff count
        $totalStaff = \App\Models\Staff::count();

        // Get monthly income data for chart (last 12 months)
        $monthlyIncome = $this->getMonthlyIncome();

        // Default values
        $staff = null;
        $todaySchedule = null;
        $todayAssignments = null;

        if (Auth::user()->role === 'staff') {
            // Coba cari staff berdasarkan user_id
            $staff = \App\Models\Staff::where('user_id', Auth::id())->first();

            // Fallback: cari berdasarkan email jika tidak ditemukan via user_id
            if (!$staff) {
                $staff = \App\Models\Staff::where('email', Auth::user()->email)->first();

                // Jika ditemukan via email, update user_id-nya agar konsisten ke depan
                if ($staff && !$staff->user_id) {
                    $staff->update(['user_id' => Auth::id()]);
                }
            }

            if ($staff) {
                // Get today's schedule
                $today = \Carbon\Carbon::today();
                $dayOfWeek = strtolower($today->format('l')); // monday, tuesday, etc
                $weekStart = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d');

                $todaySchedule = \App\Models\ShiftSchedule::where('staff_id', $staff->id)
                    ->where('week_start_date', $weekStart)
                    ->where('day', $dayOfWeek)
                    ->with('staff')
                    ->first();

                // Get shift times for today's schedule
                if ($todaySchedule) {
                    $shiftTime = \App\Models\ShiftTime::where('shift', $todaySchedule->shift)->first();
                    $todaySchedule->shift_time = $shiftTime;
                }

                // Get today's assignments (created today)
                $todayAssignments = \App\Models\Assignment::where('staff_id', $staff->id)
                    ->whereDate('created_at', $today)
                    ->with('creator')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }
        }

        return view('admin.dashboard', compact(
            'members',
            'totalMembers',
            'totalStaff',
            'monthlyIncome',
            'staff',
            'todaySchedule',
            'todayAssignments'
        ));
    }

    private function getMonthlyIncome()
    {
        $months = [];
        $incomes = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');

            $dailySalesIncome = (float) \App\Models\DailySale::whereYear('sale_date', $date->year)
                ->whereMonth('sale_date', $date->month)
                ->sum(DB::raw('cash + qris + transfer'));

            $serviceIncome = (float) \App\Models\ServiceHarian::whereYear('tanggal_masuk', $date->year)
                ->whereMonth('tanggal_masuk', $date->month)
                ->sum('total_harga');

            $months[] = $monthName;
            $incomes[] = $dailySalesIncome + $serviceIncome;
        }

        return [
            'months' => $months,
            'incomes' => $incomes,
        ];
    }

    public function updateMyProfile(Request $request)
    {
        $user = Auth::user();
        $staff = \App\Models\Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Data staff tidak ditemukan'], 404);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'photo_profile' => 'nullable|image|max:2048',
            'new_password' => 'nullable|min:6|confirmed', // 'confirmed' butuh field new_password_confirmation di form
        ]);

        // Update data profil staff
        $staff->nama = $request->nama;
        $staff->bank_name = $request->bank_name;
        $staff->bank_account = $request->bank_account;
        $staff->account_name = $request->account_name;

        // LANGSUNG GANTI PASSWORD (Tanpa cek password lama)
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        // Handle Upload Foto
        if ($request->hasFile('photo_profile')) {
            if ($staff->photo_profile && file_exists(public_path($staff->photo_profile))) {
                unlink(public_path($staff->photo_profile));
            }

            $staff->photo_profile = ImageUploadService::saveAsWebp(
                $request->file('photo_profile'),
                'images',
                'staff-' . time() . '-' . $request->nama
            );
        }

        $staff->save();

        // Sinkronisasi nama ke tabel users
        $user->name = $request->nama;
        $user->save();

        return response()->json(['success' => true]);
    }
}
