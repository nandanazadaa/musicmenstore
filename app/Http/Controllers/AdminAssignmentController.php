<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Staff;
use Illuminate\Http\Request;

class AdminAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Assignment::with(['staff', 'creator']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('staff', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staffs = Staff::orderBy('nama')->get();
        return view('admin.assignments.create', compact('staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staffs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Assignment::create([
            'staff_id' => $request->staff_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.assignments.index')->with('success', 'Assignment berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assignment = Assignment::with(['staff', 'creator'])->findOrFail($id);
        return view('admin.assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $assignment = Assignment::findOrFail($id);
        $staffs = Staff::orderBy('nama')->get();
        return view('admin.assignments.edit', compact('assignment', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $assignment = Assignment::findOrFail($id);

        $request->validate([
            'staff_id' => 'required|exists:staffs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $assignment->update([
            'staff_id' => $request->staff_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.assignments.index')->with('success', 'Assignment berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();

        return redirect()->route('admin.assignments.index')->with('success', 'Assignment berhasil dihapus!');
    }

    /**
     * Get new assignments for real-time updates
     */
    public function getNewAssignments(Request $request)
    {
        $lastUpdate = $request->input('last_update', now()->subMinutes(5)->toDateTimeString());
        
        // Get all new/updated assignments first (without filters for real-time detection)
        $query = Assignment::with(['staff', 'creator'])
            ->where(function($q) use ($lastUpdate) {
                $q->where('created_at', '>', $lastUpdate)
                  ->orWhere('updated_at', '>', $lastUpdate);
            });

        // Apply filters only if provided (for filtered views)
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        
        if ($search != '') {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('staff', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($status != '') {
            $query->where('status', $status);
        }

        $assignments = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'assignments' => $assignments->map(function($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'staff_name' => $assignment->staff->nama,
                    'status' => $assignment->status,
                    'creator_name' => $assignment->creator->name,
                    'created_at' => $assignment->created_at->format('d M Y H:i'),
                    'created_at_raw' => $assignment->created_at->toDateTimeString(),
                    'is_new' => $assignment->created_at->gt(now()->subSeconds(10)),
                ];
            }),
            'last_update' => now()->toDateTimeString(),
        ]);
    }
}
