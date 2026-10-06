<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\OfficeSetting;
use App\Models\DailyAttendanceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class StaffAttendanceController extends Controller
{
    /**
     * Get office location from database
     */
    private function getOfficeLatitude()
    {
        return (float) OfficeSetting::getValue('office_latitude', '-6.2088');
    }

    private function getOfficeLongitude()
    {
        return (float) OfficeSetting::getValue('office_longitude', '106.8456');
    }

    private function getAllowedRadius()
    {
        return (float) OfficeSetting::getValue('allowed_radius', '500');
    }

    /**
     * Show attendance page
     */
    public function index()
    {
        // Only allow staff role
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk staff.');
        }

        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan. Silakan hubungi administrator untuk menghubungkan akun Anda dengan staff profile di menu Users Management > Staffs > Edit Staff > Link User Account.');
        }

        $todayAttendance = $staff->getTodayAttendance();
        $recentAttendances = $staff->attendances()
            ->orderBy('attendance_date', 'desc')
            ->limit(10)
            ->get();

        // Get current token (60 second interval - 1 minute)
        $currentToken = DailyAttendanceToken::getCurrentToken();
        $nextTokenTime = DailyAttendanceToken::getNextTokenTime();

        return view('staff.attendance.index', compact('staff', 'todayAttendance', 'recentAttendances', 'currentToken', 'nextTokenTime'));
    }

    /**
     * Get current token (API endpoint for real-time updates)
     */
    public function getCurrentToken()
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return response()->json([
                'success' => false,
                'error' => 'Sesi login habis atau akses ditolak. Silakan login ulang.',
            ], 403);
        }

        try {
            $token = DailyAttendanceToken::getCurrentToken();
            $nextTokenTime = DailyAttendanceToken::getNextTokenTime();

            return response()->json([
                'success' => true,
                'token' => $token,
                'nextTokenTime' => $nextTokenTime,
            ]);
        } catch (\Throwable $e) {
            Log::error('Attendance token refresh failed', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Token absensi gagal diperbarui. Silakan refresh halaman.',
            ], 500);
        }
    }

    /**
     * Check in
     */
    public function checkIn(Request $request)
    {
        try {
            // 1. Validasi Role
            if (!Auth::check() || Auth::user()->role !== 'staff') {
                return response()->json(['success' => false, 'error' => 'Sesi login habis atau akses ditolak. Silakan login ulang.'], 403);
            }

            // 2. Validasi Input
            $request->validate([
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'address' => 'nullable|string|max:500',
                'token' => 'required|string|size:6',
            ]);

            $user = Auth::user();
            $staff = Staff::where('user_id', $user->id)->first();

            if (!$staff) {
                return response()->json(['success' => false, 'error' => 'Profil staff tidak ditemukan. Hubungi administrator.'], 404);
            }

        // 3. Cek apakah sudah check-in hari ini
        $todayAttendance = $staff->getTodayAttendance();
        if ($todayAttendance && $todayAttendance->check_in_time) {
            return response()->json([
                'success' => false,
                'error' => 'Anda sudah melakukan check in hari ini.'
            ], 400);
        }

        // 4. Validasi Token Absensi
        if (!DailyAttendanceToken::validateToken($request->token)) {
            return response()->json([
                'success' => false,
                'error' => 'Token tidak valid atau sudah kadaluarsa.',
            ], 400);
        }

        // 5. Ambil Jadwal Shift Hari Ini
        $today = \Carbon\Carbon::today();
        $dayOfWeek = strtolower($today->format('l'));
        $weekStart = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d');

        $todaySchedule = \App\Models\ShiftSchedule::where('staff_id', $staff->id)
            ->where('week_start_date', $weekStart)
            ->where('day', $dayOfWeek)
            ->first();

        // Proteksi jika hari ini jadwalnya Libur
        if ($todaySchedule && $todaySchedule->shift === 'libur') {
            return response()->json(['success' => false, 'error' => 'Hari ini adalah hari libur Anda.'], 400);
        }

        // 6. Tentukan Jam Masuk Berdasarkan Shift
        $shiftTime = null;
        if ($todaySchedule && $todaySchedule->shift !== 'libur') {
            $shiftTime = \App\Models\ShiftTime::where('shift', $todaySchedule->shift)->first();
        }

        $checkInTime = now();
        $pointChange = 0;

        // 7. Hitung Poin Berdasarkan Ketepatan Waktu
        if ($shiftTime) {
            $startTime = $shiftTime->start_time;
            $startTimeStr = ($startTime instanceof \DateTime || $startTime instanceof \Carbon\Carbon)
                ? $startTime->format('H:i:s')
                : (is_string($startTime) ? $startTime : '09:00:00');

            $expectedStart = \Carbon\Carbon::parse($today->format('Y-m-d') . ' ' . $startTimeStr);
            $tolerance = 15; // Toleransi keterlambatan dalam menit
            $diffMinutes = $checkInTime->diffInMinutes($expectedStart, false);

            if ($diffMinutes <= $tolerance) {
                // Masuk tepat waktu atau masih dalam batas toleransi 15 menit
                $pointChange = 1;
            } else {
                // Terlambat lebih dari 15 menit
                $pointChange = -1;
            }
        } else {
            // Jika admin belum set jadwal, default kasih +1 poin agar staff tidak rugi
            $pointChange = 1;
        }

        // 8. Simpan ke Database (Tabel staff_attendances)
        $attendance = StaffAttendance::updateOrCreate(
            ['staff_id' => $staff->id, 'attendance_date' => today()],
            [
                'check_in_time' => $checkInTime->format('H:i:s'),
                'check_in_latitude' => $request->latitude,
                'check_in_longitude' => $request->longitude,
                'check_in_address' => $request->address ?? 'Location not provided',
                'check_in_token' => strtoupper($request->token),
                'point_change' => $pointChange,
            ]
        );

        // 9. UPDATE POIN STAFF (DENGAN PROTEKSI CEGAH MINUS)
        if ($pointChange > 0) {
            $staff->increment('points', $pointChange);
        } elseif ($pointChange < 0) {
            // Hanya kurangi jika poin saat ini masih lebih dari 0
            if ($staff->points > 0) {
                $staff->decrement('points', abs($pointChange));
            } else {
                // Jika poin sudah 0, pastikan tidak jadi negatif
                $staff->points = 0;
                $staff->save();
            }
        }

        // 10. Kirim Response
        $message = ($pointChange > 0)
            ? 'Check-in berhasil! Anda mendapatkan +1 point.'
            : 'Check-in berhasil! Anda terlambat dan poin dikurangi.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'check_in_time' => $attendance->check_in_time,
                'current_points' => $staff->points,
                'point_change' => $pointChange,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => collect($e->errors())->flatten()->first() ?: 'Data absensi tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Check-in attendance failed', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => config('app.debug')
                    ? 'Check-in gagal: '.$e->getMessage()
                    : 'Check-in gagal karena server sedang bermasalah. Silakan coba lagi atau hubungi admin.',
            ], 500);
        }
    }

    /**
     * Check out
     */
    public function checkOut(Request $request)
    {
        try {
            // Only allow staff role
            if (!Auth::check() || Auth::user()->role !== 'staff') {
                return response()->json(['success' => false, 'error' => 'Sesi login habis atau akses ditolak. Silakan login ulang.'], 403);
            }

        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return response()->json(['error' => 'Staff profile not found.'], 404);
        }

        $todayAttendance = $staff->getTodayAttendance();

        if (!$todayAttendance || !$todayAttendance->check_in_time) {
            return response()->json(['error' => 'Please check in first.'], 400);
        }

        if ($todayAttendance->check_out_time) {
            return response()->json(['error' => 'You have already checked out today.'], 400);
        }

        $todayAttendance->update([
            'check_out_time' => now()->format('H:i:s'),
            'check_out_latitude' => $request->latitude ?? null,
            'check_out_longitude' => $request->longitude ?? null,
            'check_out_address' => $request->address ?? 'Location not provided',
        ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-out successful!',
                'check_out_time' => $todayAttendance->check_out_time,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => collect($e->errors())->flatten()->first() ?: 'Data check-out tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Check-out attendance failed', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => config('app.debug')
                    ? 'Check-out gagal: '.$e->getMessage()
                    : 'Check-out gagal karena server sedang bermasalah. Silakan coba lagi atau hubungi admin.',
            ], 500);
        }
    }

    /**
     * Get attendance history
     */
    public function history(Request $request)
    {
        // Only allow staff role
        if (Auth::user()->role !== 'staff') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk staff.');
        }

        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan. Silakan hubungi administrator untuk menghubungkan akun Anda dengan staff profile di menu Users Management > Staffs > Edit Staff > Link User Account.');
        }

        $query = $staff->attendances()->orderBy('attendance_date', 'desc');

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        $attendances = $query->paginate(20);

        return view('staff.attendance.history', compact('staff', 'attendances'));
    }
}
