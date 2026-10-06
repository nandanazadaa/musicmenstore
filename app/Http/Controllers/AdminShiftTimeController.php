<?php

namespace App\Http\Controllers;

use App\Models\ShiftTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminShiftTimeController extends Controller
{
    /**
     * Display shift time settings
     */
    public function index()
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $shiftTimes = ShiftTime::all()->keyBy('shift');

        return view('admin.shifts.times', compact('shiftTimes'));
    }

    /**
     * Update shift times
     */
    public function update(Request $request)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $request->validate([
            'pagi_start' => 'required|date_format:H:i',
            'pagi_end' => 'required|date_format:H:i|after:pagi_start',
            'siang_start' => 'required|date_format:H:i',
            'siang_end' => 'required|date_format:H:i|after:siang_start',
        ]);

        // Update pagi shift
        $pagiShift = ShiftTime::where('shift', 'pagi')->first();
        if ($pagiShift) {
            $pagiShift->update([
                'start_time' => $request->pagi_start,
                'end_time' => $request->pagi_end,
            ]);
        } else {
            ShiftTime::create([
                'shift' => 'pagi',
                'start_time' => $request->pagi_start,
                'end_time' => $request->pagi_end,
            ]);
        }

        // Update siang shift
        $siangShift = ShiftTime::where('shift', 'siang')->first();
        if ($siangShift) {
            $siangShift->update([
                'start_time' => $request->siang_start,
                'end_time' => $request->siang_end,
            ]);
        } else {
            ShiftTime::create([
                'shift' => 'siang',
                'start_time' => $request->siang_start,
                'end_time' => $request->siang_end,
            ]);
        }

        return redirect()->route('admin.shifts.times')
            ->with('success', 'Jam shift berhasil diupdate!');
    }
}
