<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\ShiftSchedule;
use App\Models\ShiftTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffShiftController extends Controller
{
    /**
     * Display shift schedule for logged in staff
     */
    public function index(Request $request)
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

        // Get week filter (default to current week)
        $weekStart = $request->input('week_start', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $weekEnd = Carbon::parse($weekStart)->endOfWeek()->format('Y-m-d');

        // Get schedules for this staff and week
        $schedules = ShiftSchedule::where('staff_id', $staff->id)
            ->where('week_start_date', $weekStart)
            ->orderByRaw("FIELD(day, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();

        // Get shift times
        $shiftTimes = ShiftTime::all()->keyBy('shift');

        return view('staff.shifts.index', compact('staff', 'schedules', 'weekStart', 'weekEnd', 'shiftTimes'));
    }

    /**
     * Get new schedules (API for realtime updates)
     */
    public function getNewSchedules(Request $request)
    {
        // Only allow staff role
        if (Auth::user()->role !== 'staff') {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return response()->json(['error' => 'Staff profile not found.'], 404);
        }

        $weekStart = $request->input('week_start', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $lastUpdate = $request->input('last_update', now()->subMinutes(5)->toDateTimeString());

        // Get schedules updated after lastUpdate
        $schedules = ShiftSchedule::where('staff_id', $staff->id)
            ->where('week_start_date', $weekStart)
            ->where(function($query) use ($lastUpdate) {
                $query->where('created_at', '>', $lastUpdate)
                      ->orWhere('updated_at', '>', $lastUpdate);
            })
            ->orderByRaw("FIELD(day, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();

        // Get shift times
        $shiftTimes = ShiftTime::all()->keyBy('shift');

        // Format schedules for JSON response
        $formattedSchedules = $schedules->map(function($schedule) use ($shiftTimes) {
            $shiftTime = $shiftTimes[$schedule->shift] ?? null;
            return [
                'id' => $schedule->id,
                'day' => $schedule->day,
                'day_name' => $schedule->day_name,
                'shift' => $schedule->shift,
                'shift_time' => $shiftTime ? [
                    'start' => \Carbon\Carbon::parse($shiftTime->start_time)->format('H:i'),
                    'end' => \Carbon\Carbon::parse($shiftTime->end_time)->format('H:i'),
                ] : null,
                'created_at' => $schedule->created_at->toDateTimeString(),
                'updated_at' => $schedule->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'schedules' => $formattedSchedules,
            'last_update' => now()->toDateTimeString(),
        ]);
    }
}
