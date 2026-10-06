<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminLeaveController extends Controller
{
    /**
     * Display a listing of all leave requests
     */
    public function index(Request $request)
    {
        // Mark that admin has viewed this page - clear notification badge
        session(['leaves_last_viewed' => now()->toISOString()]);
        
        $query = LeaveRequest::with(['staff', 'approver']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by staff
        if ($request->has('staff_id') && $request->staff_id != '') {
            $query->where('staff_id', $request->staff_id);
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        $staffs = Staff::orderBy('nama')->get();

        return view('admin.leaves.index', compact('leaveRequests', 'staffs'));
    }

    /**
     * Display the specified leave request
     */
    public function show($id)
    {
        $leaveRequest = LeaveRequest::with(['staff', 'approver'])->findOrFail($id);

        return view('admin.leaves.show', compact('leaveRequest'));
    }

    /**
     * Approve a leave request
     */
    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan cuti ini sudah diproses sebelumnya.');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Pengajuan cuti berhasil disetujui!');
    }

    /**
     * Reject a leave request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan cuti ini sudah diproses sebelumnya.');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Pengajuan cuti berhasil ditolak!');
    }

    /**
     * Get new leave requests (API for real-time updates)
     */
    public function getNewLeaveRequests(Request $request)
    {
        $lastUpdate = $request->input('last_update', now()->subMinutes(5)->toDateTimeString());
        
        $leaveRequests = LeaveRequest::with(['staff', 'approver'])
            ->where(function($query) use ($lastUpdate) {
                $query->where('created_at', '>', $lastUpdate)
                      ->orWhere('updated_at', '>', $lastUpdate);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($leaveRequest) {
                return [
                    'id' => $leaveRequest->id,
                    'staff_name' => $leaveRequest->staff->nama,
                    'start_date' => $leaveRequest->start_date->format('d M Y'),
                    'end_date' => $leaveRequest->end_date->format('d M Y'),
                    'total_days' => $leaveRequest->total_days,
                    'status' => $leaveRequest->status,
                    'status_text' => $leaveRequest->status_text,
                    'status_badge' => $leaveRequest->status_badge,
                    'created_at' => $leaveRequest->created_at->format('d M Y H:i'),
                    'updated_at' => $leaveRequest->updated_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'leave_requests' => $leaveRequests,
            'last_update' => now()->toDateTimeString(),
        ]);
    }
}
