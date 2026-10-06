<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class AdminStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Staff::with('user');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_employee', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $staffs = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'staff')->orderBy('name')->get();
        return view('admin.staffs.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'jabatan' => 'required|string|max:255',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $data = [
            'nama' => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
            'jabatan' => $request->jabatan,
            'user_id' => $request->user_id ?? null,
        ];

        // Handle photo upload
        if ($request->hasFile('photo_profile')) {
            $data['photo_profile'] = ImageUploadService::saveAsWebp(
                $request->file('photo_profile'),
                'images',
                'staff-' . time() . '-' . $request->nama
            );
        }

        Staff::create($data);

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = Staff::findOrFail($id);
        $users = User::where('role', 'staff')->orderBy('name')->get();
        return view('admin.staffs.edit', compact('staff', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'jabatan' => 'required|string|max:255',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $data = [
            'nama' => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
            'jabatan' => $request->jabatan,
        ];

        // Update user_id if provided
        if ($request->has('user_id') && $request->user_id) {
            $data['user_id'] = $request->user_id;
        } else {
            $data['user_id'] = null;
        }

        // Handle photo upload
        if ($request->hasFile('photo_profile')) {
            // Delete old photo
            if ($staff->photo_profile && file_exists(public_path($staff->photo_profile))) {
                unlink(public_path($staff->photo_profile));
            }
            
            $data['photo_profile'] = ImageUploadService::saveAsWebp(
                $request->file('photo_profile'),
                'images',
                'staff-' . time() . '-' . $request->nama
            );
        }

        $staff->update($data);

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $staff = Staff::findOrFail($id);
        
        // Delete photo if exists
        if ($staff->photo_profile && file_exists(public_path($staff->photo_profile))) {
            unlink(public_path($staff->photo_profile));
        }
        
        $staff->delete();

        return redirect()->route('admin.staffs.index')->with('success', 'Staff berhasil dihapus!');
    }

    /**
     * Reset points for staff attendance
     */
    public function resetPoints(string $id)
    {
        $staff = Staff::findOrFail($id);
        
        // Reset points to 0
        $staff->points = 0;
        $staff->save();
        
        // Reset all point_change in staff_attendances to 0 for this staff
        \App\Models\StaffAttendance::where('staff_id', $staff->id)
            ->update(['point_change' => 0]);

        return redirect()->route('admin.staffs.index')->with('success', 'Point absensi staff berhasil direset!');
    }
}
