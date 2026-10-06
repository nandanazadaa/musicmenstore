<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\ShiftSchedule;
use App\Models\ShiftTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminShiftController extends Controller
{
    /**
     * Display a listing of shift schedules
     */
    public function index(Request $request)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        // Get week filter (default to current week)
        $weekStart = $request->input('week_start', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $weekEnd = Carbon::parse($weekStart)->endOfWeek()->format('Y-m-d');

        // Get all staffs
        $staffs = Staff::orderBy('nama')->get();

        // Get all schedules for this week
        $schedules = ShiftSchedule::where('week_start_date', $weekStart)
            ->with('staff')
            ->get()
            ->groupBy('staff_id')
            ->map(function($staffSchedules) {
                return $staffSchedules->groupBy('day');
            });

        // Get shift times
        $shiftTimes = ShiftTime::all()->keyBy('shift');

        return view('admin.shifts.index', compact('staffs', 'schedules', 'weekStart', 'weekEnd', 'shiftTimes'));
    }

    /**
     * Show the form for creating a new shift schedule
     */
    public function create(Request $request)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        // Get week filter (default to current week)
        $weekStart = $request->input('week_start', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $weekEnd = Carbon::parse($weekStart)->endOfWeek()->format('Y-m-d');

        // Get all staffs
        $staffs = Staff::orderBy('nama')->get();

        // Get existing schedules for this week
        $existingSchedules = ShiftSchedule::where('week_start_date', $weekStart)
            ->get()
            ->groupBy('staff_id')
            ->map(function($staffSchedules) {
                return $staffSchedules->groupBy('day');
            });

        // Get shift times
        $shiftTimes = ShiftTime::all()->keyBy('shift');

        return view('admin.shifts.create', compact('staffs', 'weekStart', 'weekEnd', 'existingSchedules', 'shiftTimes'));
    }

    /**
     * Store a newly created shift schedule
     */
    public function store(Request $request)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $request->validate([
            'week_start_date' => 'required|date',
            'week_end_date' => 'required|date|after_or_equal:week_start_date',
            'schedules' => 'required|array',
        ]);

        $weekStart = $request->week_start_date;
        $weekEnd = $request->week_end_date;

        // Delete existing schedules for this week
        ShiftSchedule::where('week_start_date', $weekStart)->delete();

        // Create new schedules
        // Structure: schedules[staff_id][day][shift] and schedules[staff_id][day][staff_id] and schedules[staff_id][day][day]
        $createdCount = 0;
        foreach ($request->schedules as $staffId => $days) {
            if (!is_array($days)) continue;
            
            foreach ($days as $day => $scheduleData) {
                if (!is_array($scheduleData)) continue;
                
                if (!empty($scheduleData['shift']) && isset($scheduleData['staff_id']) && isset($scheduleData['day'])) {
                    try {
                        ShiftSchedule::create([
                            'week_start_date' => $weekStart,
                            'week_end_date' => $weekEnd,
                            'staff_id' => $scheduleData['staff_id'],
                            'day' => $scheduleData['day'],
                            'shift' => $scheduleData['shift'],
                        ]);
                        $createdCount++;
                    } catch (\Exception $e) {
                        // Skip if duplicate or error
                        Log::error('Error creating shift schedule: ' . $e->getMessage(), [
                            'staff_id' => $scheduleData['staff_id'] ?? null,
                            'day' => $scheduleData['day'] ?? null,
                            'shift' => $scheduleData['shift'] ?? null,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.shifts.index', ['week_start' => $weekStart])
            ->with('success', "Jadwal shift berhasil disimpan! ($createdCount jadwal dibuat)");
    }

    /**
     * Show the form for editing the specified shift schedule
     */
    public function edit($weekStart)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $weekStart = Carbon::parse($weekStart)->format('Y-m-d');
        $weekEnd = Carbon::parse($weekStart)->endOfWeek()->format('Y-m-d');

        // Get all staffs
        $staffs = Staff::orderBy('nama')->get();

        // Get existing schedules for this week
        $existingSchedules = ShiftSchedule::where('week_start_date', $weekStart)
            ->get()
            ->groupBy('staff_id')
            ->map(function($staffSchedules) {
                return $staffSchedules->groupBy('day');
            });

        // Get shift times
        $shiftTimes = ShiftTime::all()->keyBy('shift');

        return view('admin.shifts.edit', compact('staffs', 'weekStart', 'weekEnd', 'existingSchedules', 'shiftTimes'));
    }

    /**
     * Update the specified shift schedule
     */
    public function update(Request $request, $weekStart)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $request->validate([
            'week_start_date' => 'required|date',
            'week_end_date' => 'required|date|after_or_equal:week_start_date',
            'schedules' => 'required|array',
        ]);

        $weekStart = $request->week_start_date;
        $weekEnd = $request->week_end_date;

        // Delete existing schedules for this week
        ShiftSchedule::where('week_start_date', $weekStart)->delete();

        // Create new schedules
        // Structure: schedules[staff_id][day][shift] and schedules[staff_id][day][staff_id] and schedules[staff_id][day][day]
        foreach ($request->schedules as $staffId => $days) {
            foreach ($days as $day => $scheduleData) {
                if (!empty($scheduleData['shift']) && isset($scheduleData['staff_id']) && isset($scheduleData['day'])) {
                    try {
                        ShiftSchedule::create([
                            'week_start_date' => $weekStart,
                            'week_end_date' => $weekEnd,
                            'staff_id' => $scheduleData['staff_id'],
                            'day' => $scheduleData['day'],
                            'shift' => $scheduleData['shift'],
                        ]);
                    } catch (\Exception $e) {
                        // Skip if duplicate or error
                        Log::error('Error creating shift schedule: ' . $e->getMessage());
                    }
                }
            }
        }

        return redirect()->route('admin.shifts.index', ['week_start' => $weekStart])
            ->with('success', 'Jadwal shift berhasil diupdate!');
    }

    /**
     * Remove the specified shift schedule
     */
    public function destroy($weekStart)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $weekStart = Carbon::parse($weekStart)->format('Y-m-d');
        
        ShiftSchedule::where('week_start_date', $weekStart)->delete();

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Jadwal shift berhasil dihapus!');
    }
}
