<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAssignmentController extends Controller
{
    /**
     * Display assignments for logged in staff
     */
    public function index(Request $request)
    {
        // Get staff based on logged in user
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan. Silakan hubungi administrator untuk menghubungkan akun Anda dengan staff profile di menu Users Management > Staffs > Edit Staff > Link User Account.');
        }

        $query = Assignment::where('staff_id', $staff->id)->with('creator');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('staff.assignments.index', compact('assignments', 'staff'));
    }

    /**
     * Show assignment details
     */
    public function show($id)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return redirect()->route('admin.dashboard')->with('error', 'Staff profile tidak ditemukan. Silakan hubungi administrator untuk menghubungkan akun Anda dengan staff profile di menu Users Management > Staffs > Edit Staff > Link User Account.');
        }

        $assignment = Assignment::where('id', $id)
            ->where('staff_id', $staff->id)
            ->with('creator')
            ->firstOrFail();

        // Mark as read
        if (!$assignment->read_at) {
            $assignment->update(['read_at' => now()]);
        }

        return view('staff.assignments.show', compact('assignment', 'staff'));
    }

    /**
     * Update assignment status
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return response()->json(['error' => 'Staff profile tidak ditemukan. Silakan hubungi administrator untuk menghubungkan akun Anda dengan staff profile di menu Users Management > Staffs > Edit Staff > Link User Account.'], 404);
        }

        $assignment = Assignment::where('id', $id)
            ->where('staff_id', $staff->id)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $assignment->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Get unread assignments count (for real-time notification)
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return response()->json(['count' => 0]);
        }

        $count = Assignment::where('staff_id', $staff->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get new assignments for real-time updates
     */
    public function getNewAssignments(Request $request)
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();

        if (!$staff) {
            return response()->json(['assignments' => [], 'last_update' => now()->toDateTimeString()]);
        }

        $lastUpdate = $request->input('last_update', now()->subMinutes(5)->toDateTimeString());
        
        // Get all new/updated assignments for this staff
        $query = Assignment::where('staff_id', $staff->id)
            ->with('creator')
            ->where(function($q) use ($lastUpdate) {
                $q->where('created_at', '>', $lastUpdate)
                  ->orWhere('updated_at', '>', $lastUpdate);
            });

        // Apply status filter if provided (for filtered views)
        $status = $request->input('status', '');
        if ($status != '') {
            $query->where('status', $status);
        }

        $assignments = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'assignments' => $assignments->map(function($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                    'status' => $assignment->status,
                    'creator_name' => $assignment->creator->name,
                    'created_at' => $assignment->created_at->format('d M Y H:i'),
                    'created_at_raw' => $assignment->created_at->toDateTimeString(),
                    'is_unread' => $assignment->isUnread(),
                    'is_new' => $assignment->created_at->gt(now()->subSeconds(10)),
                ];
            }),
            'last_update' => now()->toDateTimeString(),
        ]);
    }
}
