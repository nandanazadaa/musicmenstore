<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPermission;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // List Users
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Show Create Form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store New User
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,staff',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Save permissions (only for staff, admin has full access)
        if ($request->role === 'staff' && $request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_key' => $permission,
                ]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // Show Edit Form
    public function edit($id)
    {
        $user = User::with('permissions')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update User
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'password' => 'nullable|string|min:6|confirmed', // Laravel mencari password_confirmation
            'role' => 'required|in:admin,staff',
            'permissions' => 'nullable|array',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Cek jika field password diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Gunakan save() untuk model yang sudah ada

        // Update permissions (Logika tetap sama seperti milik Anda)
        if ($request->role === 'staff') {
            $user->permissions()->delete();
            if ($request->has('permissions')) {
                foreach ($request->permissions as $permission) {
                    UserPermission::create([
                        'user_id' => $user->id,
                        'permission_key' => $permission,
                    ]);
                }
            }
        } else {
            $user->permissions()->delete();
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate!');
    }

    // Delete User
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }

    // Export Users to Excel
    public function export()
    {
        return Excel::download(new UsersExport, 'users-' . date('Y-m-d') . '.xlsx');
    }
}
