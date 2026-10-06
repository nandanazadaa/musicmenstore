<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAttendanceController extends Controller
{
    /**
     * Display attendance report/rekap for all staffs
     */
    public function index(Request $request)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $query = StaffAttendance::with('staff')->orderBy('attendance_date', 'desc');

        // Filter by staff
        if ($request->has('staff_id') && $request->staff_id != '') {
            $query->where('staff_id', $request->staff_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'present') {
                $query->whereNotNull('check_in_time');
            } elseif ($request->status === 'absent') {
                $query->whereNull('check_in_time');
            }
        }

        $attendances = $query->paginate(20);
        $staffs = Staff::orderBy('nama')->get();

        // Statistics
        $totalStaffs = Staff::count();
        $todayPresent = StaffAttendance::whereDate('attendance_date', today())
            ->whereNotNull('check_in_time')
            ->distinct()
            ->count('staff_id');
        $todayAbsent = $totalStaffs - $todayPresent;

        return view('admin.attendance.index', compact('attendances', 'staffs', 'totalStaffs', 'todayPresent', 'todayAbsent'));
    }

    // AdminAttendanceController.php

    public function monthlyReset(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $lastMonth = now()->subMonth();
        $monthLabel = $lastMonth->format('F Y'); // Contoh: "January 2026"

        $staffs = \App\Models\Staff::all();

        if ($staffs->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data staff untuk direkap.');
        }

        DB::beginTransaction();
        try {
            foreach ($staffs as $staff) {
                // Hitung kehadiran bulan lalu
                $presence = \App\Models\StaffAttendance::where('staff_id', $staff->id)
                    ->whereMonth('attendance_date', $lastMonth->month)
                    ->whereYear('attendance_date', $lastMonth->year)
                    ->whereNotNull('check_in_time')
                    ->count();

                // Simpan ke archive
                DB::table('attendance_monthly_reports')->insert([
                    'staff_id' => $staff->id,
                    'month_year' => $monthLabel,
                    'total_presence' => $presence,
                    'total_points_achieved' => $staff->points,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // RESET POIN STAFF KE 0
                $staff->update(['points' => 0]);
            }

            DB::commit();
            return redirect()->back()->with('swal_success', "Berhasil melakukan rekap $monthLabel dan reset poin staff.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }

    /**
     * Show detailed attendance for a specific staff
     */
    public function showStaff($staffId, Request $request)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $staff = Staff::findOrFail($staffId);

        $query = $staff->attendances()->orderBy('attendance_date', 'desc');

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        $attendances = $query->paginate(20);

        // Statistics for this staff
        $totalDays = $attendances->total();
        $presentDays = $staff->attendances()
            ->whereNotNull('check_in_time')
            ->count();

        return view('admin.attendance.staff', compact('staff', 'attendances', 'totalDays', 'presentDays'));
    }

    /**
     * Delete attendance record
     */
    public function destroy($id)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $attendance = StaffAttendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'Absensi berhasil dihapus!');
    }

    /**
     * Get new attendances (API for realtime updates)
     */
    public function getNewAttendances(Request $request)
    {
        // Only allow admin role
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        $lastUpdate = $request->input('last_update', now()->subMinutes(5)->toDateTimeString());

        // Get new attendances created or updated after lastUpdate
        $newAttendances = StaffAttendance::with('staff')
            ->where(function ($query) use ($lastUpdate) {
                $query->where('created_at', '>', $lastUpdate)
                    ->orWhere('updated_at', '>', $lastUpdate);
            })
            ->whereDate('attendance_date', today()) // Only today's attendances
            ->orderBy('created_at', 'desc')
            ->get();

        // Get updated statistics
        $totalStaffs = Staff::count();
        $todayPresent = StaffAttendance::whereDate('attendance_date', today())
            ->whereNotNull('check_in_time')
            ->distinct()
            ->count('staff_id');
        $todayAbsent = $totalStaffs - $todayPresent;

        // Format attendances for JSON response
        $formattedAttendances = $newAttendances->map(function ($attendance) {
            return [
                'id' => $attendance->id,
                'staff_id' => $attendance->staff_id,
                'staff_name' => $attendance->staff->nama,
                'staff_employee_id' => $attendance->staff->id_employee,
                'attendance_date' => $attendance->attendance_date->format('d M Y'),
                'check_in_time' => $attendance->check_in_time,
                'check_in_address' => $attendance->check_in_address,
                'check_in_latitude' => $attendance->check_in_latitude,
                'check_in_longitude' => $attendance->check_in_longitude,
                'status' => $attendance->check_in_time ? 'present' : 'absent',
                'created_at' => $attendance->created_at->toDateTimeString(),
                'updated_at' => $attendance->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'attendances' => $formattedAttendances,
            'statistics' => [
                'total_staffs' => $totalStaffs,
                'today_present' => $todayPresent,
                'today_absent' => $todayAbsent,
            ],
            'last_update' => now()->toDateTimeString(),
        ]);
    }

    // Export Attendance to Excel
    public function export(Request $request)
    {
        $query = StaffAttendance::with('staff')->orderBy('attendance_date', 'desc');

        if ($request->has('staff_id') && $request->staff_id != '') {
            $query->where('staff_id', $request->staff_id);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'present') {
                $query->whereNotNull('check_in_time');
            } elseif ($request->status === 'absent') {
                $query->whereNull('check_in_time');
            }
        }

        $fileName = 'rekap-absensi-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new AttendanceExport($query), $fileName);
    }
}
