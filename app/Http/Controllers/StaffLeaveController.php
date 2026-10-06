<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffLeaveController extends Controller
{
    /**
     * Display a listing of leave requests for the logged in staff
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan. Silakan hubungi administrator untuk menghubungkan akun Anda dengan staff profile.');
        }

        $query = LeaveRequest::where('staff_id', $staff->id)->with('approver');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('staff.leaves.index', compact('leaveRequests', 'staff'));
    }

    /**
     * Show the form for creating a new leave request
     */
    public function create()
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        return view('staff.leaves.create', compact('staff'));
    }

    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        LeaveRequest::create([
            'staff_id' => $staff->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('staff.leaves.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim!');
    }

    /**
     * Display the specified leave request
     */
    public function show($id)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan.');
        }

        $leaveRequest = LeaveRequest::where('id', $id)
            ->where('staff_id', $staff->id)
            ->with('approver')
            ->firstOrFail();

        return view('staff.leaves.show', compact('leaveRequest', 'staff'));
    }

    /**
     * Get updated leave requests (API for real-time updates)
     */
    public function getUpdatedLeaveRequests(Request $request)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return response()->json(['error' => 'Staff profile not found.'], 404);
        }

        $lastUpdate = $request->input('last_update', now()->subMinutes(5)->toDateTimeString());
        
        $leaveRequests = LeaveRequest::where('staff_id', $staff->id)
            ->with('approver')
            ->where(function($query) use ($lastUpdate) {
                $query->where('created_at', '>', $lastUpdate)
                      ->orWhere('updated_at', '>', $lastUpdate);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($leaveRequest) {
                return [
                    'id' => $leaveRequest->id,
                    'start_date' => $leaveRequest->start_date->format('d M Y'),
                    'end_date' => $leaveRequest->end_date->format('d M Y'),
                    'total_days' => $leaveRequest->total_days,
                    'status' => $leaveRequest->status,
                    'status_text' => $leaveRequest->status_text,
                    'status_badge' => $leaveRequest->status_badge,
                    'reason' => $leaveRequest->reason,
                    'rejection_reason' => $leaveRequest->rejection_reason,
                    'created_at' => $leaveRequest->created_at->format('d M Y H:i'),
                    'approved_at' => $leaveRequest->approved_at ? $leaveRequest->approved_at->format('d M Y H:i') : null,
                    'approver_name' => $leaveRequest->approver ? $leaveRequest->approver->name : null,
                    'updated_at' => $leaveRequest->updated_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'leave_requests' => $leaveRequests,
            'last_update' => now()->toDateTimeString(),
        ]);
    }
}
