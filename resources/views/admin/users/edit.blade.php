<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit User - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 w-full">
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Edit User Account</h1>
                <div class="h-px bg-gradient-to-r from-yellow-500 via-[#4a4a4a] to-transparent w-full max-w-2xl"></div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-yellow-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-yellow-500 transition-all" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="role" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">User Role</label>
                                <select id="role" name="role" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white outline-none" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">New Password</label>
                                <input type="password" name="password" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white mb-4" placeholder="Minimal 6 karakter">
                                
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white" placeholder="Ulangi password baru">
                                <p class="text-[10px] text-[#6a6a6a] mt-2 italic">* Kosongkan kedua kolom di atas jika tidak ingin mengganti password.</p>
                            </div>
                        </div>

                        <div id="permissionsSection" style="display: {{ $user->role === 'staff' ? 'block' : 'none' }};" class="mt-8 border-t border-[#333] pt-6">
                            <label class="block text-sm text-[#9a9a9a] mb-4 uppercase tracking-widest font-bold text-yellow-500">Update Permission Keys</label>
                            
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-xl p-6">
                                @php $userPermissions = $user->getPermissionKeys(); @endphp
                                
                                <div class="mb-4">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="perm_dashboard" name="permissions[]" value="dashboard" class="w-4 h-4 rounded text-blue-600" {{ in_array('dashboard', $userPermissions) ? 'checked' : '' }}>
                                        <label for="perm_dashboard" class="text-white text-sm font-semibold">Dashboard Access</label>
                                    </div>
                                </div>

                                <div class="mb-6 border-t border-[#2a2a2a] pt-4">
                                    <p class="text-[10px] text-[#6a6a6a] uppercase tracking-widest mb-3">Role Service Harian</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-center gap-3 p-3 bg-[#1a1a1a] rounded-lg border {{ in_array('service_harian_input', $userPermissions) ? 'border-blue-500/50' : 'border-[#333]' }}">
                                            <input type="checkbox" id="perm_service_input" name="permissions[]" value="service_harian_input" class="w-4 h-4 rounded" {{ in_array('service_harian_input', $userPermissions) ? 'checked' : '' }}>
                                            <label for="perm_service_input" class="text-blue-400 text-xs"><strong>Frontdesk</strong> (Fee 5%)</label>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 bg-[#1a1a1a] rounded-lg border {{ in_array('service_harian_kelola', $userPermissions) ? 'border-yellow-500/50' : 'border-[#333]' }}">
                                            <input type="checkbox" id="perm_service_kelola" name="permissions[]" value="service_harian_kelola" class="w-4 h-4 rounded" {{ in_array('service_harian_kelola', $userPermissions) ? 'checked' : '' }}>
                                            <label for="perm_service_kelola" class="text-yellow-500 text-xs"><strong>Teknisi</strong> (Fee 30%)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-[#2a2a2a] pt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach(['attendance'=>'Absensi', 'shifts'=>'Shift', 'leaves'=>'Cuti', 'daily_sales'=>'Penjualan', 'payroll'=>'Gaji', 'assignments'=>'Tugas', 'products'=>'Instrumen', 'sales'=>'Sales', 'members'=>'Members', 'rewards'=>'Rewards', 'inventory' => 'Inventory & Checkup', 'landing_page'  => 'Landing Page',] as $k => $v)
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="perm_{{$k}}" name="permissions[]" value="{{$k}}" class="w-4 h-4 rounded" {{ in_array($k, $userPermissions) ? 'checked' : '' }}>
                                        <label for="perm_{{$k}}" class="text-[#9a9a9a] text-xs">{{$v}}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-12">
                        <button type="submit" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-4 rounded-xl uppercase tracking-widest">Update User</button>
                        <a href="{{ route('admin.users.index') }}" class="flex-1 bg-transparent border border-[#4a4a4a] text-center py-4 rounded-xl hover:bg-[#2a2a2a] uppercase text-sm flex items-center justify-center">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>