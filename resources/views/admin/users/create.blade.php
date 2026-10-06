<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Add User - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        * {
            box-sizing: border-box;
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 w-full">
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Add New User</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Full
                                    Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Email
                                    Address</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none"
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="role"
                                    class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">User Role</label>
                                <select id="role" name="role"
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none"
                                    required>
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Password</label>
                                <input type="password" name="password"
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Confirm
                                    Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none"
                                    required>
                            </div>
                        </div>

                        <div id="permissionsSection" style="display: none;" class="mt-8 border-t border-[#333] pt-6">
                            <label class="block text-sm text-[#9a9a9a] mb-4 uppercase tracking-widest font-bold">Menu
                                Hak Akses (Staff)</label>

                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-xl p-6 space-y-8">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="perm_dashboard" name="permissions[]"
                                            value="dashboard" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500"
                                            {{ in_array('dashboard', old('permissions', [])) ? 'checked' : '' }}>
                                        <label for="perm_dashboard"
                                            class="text-white text-sm cursor-pointer font-bold uppercase">Akses
                                            Dashboard</label>
                                    </div>
                                </div>

                                <div class="border-t border-[#2a2a2a] pt-4">
                                    <p class="text-[10px] text-blue-400 uppercase tracking-widest mb-4 font-bold">
                                        Operasional Service (Pembagian Fee)</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div
                                            class="flex items-center gap-3 p-3 bg-[#1a1a1a] border border-[#333] rounded-lg">
                                            <input type="checkbox" id="perm_service_input" name="permissions[]"
                                                value="service_harian_input" class="w-4 h-4 rounded text-blue-600"
                                                {{ in_array('service_harian_input', old('permissions', [])) ? 'checked' : '' }}>
                                            <label for="perm_service_input"
                                                class="text-blue-400 text-xs cursor-pointer font-bold">INPUT SERVICE
                                                (Frontdesk - Fee 5%)</label>
                                        </div>
                                        <div
                                            class="flex items-center gap-3 p-3 bg-[#1a1a1a] border border-[#333] rounded-lg">
                                            <input type="checkbox" id="perm_service_kelola" name="permissions[]"
                                                value="service_harian_kelola" class="w-4 h-4 rounded text-yellow-600"
                                                {{ in_array('service_harian_kelola', old('permissions', [])) ? 'checked' : '' }}>
                                            <label for="perm_service_kelola"
                                                class="text-yellow-500 text-xs cursor-pointer font-bold">KELOLA SERVICE
                                                (Teknisi - Fee 30%)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-[#2a2a2a] pt-4">
                                    <p class="text-[10px] text-[#6a6a6a] uppercase tracking-widest mb-4 font-bold">Menu
                                        Staff Umum</p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-4 gap-x-6">
                                        @php
                                            $all_menus = [
                                                'attendance' => 'Absensi',
                                                'shifts' => 'Jadwal Shift',
                                                'leaves' => 'Pengajuan Cuti',
                                                'daily_sales' => 'Form Penjualan Harian',
                                                'payroll' => 'Penerimaan Gaji',
                                                'assignments' => 'Penugasan / Tugas',
                                                'products' => 'Input Instrumen Masuk',
                                                'inventory' => 'Inventory & Checkup', // TAMBAHKAN INI
                                                'sales' => 'Input Sales Instrumen',
                                                'members' => 'Members',
                                                'rewards' => 'Rewards',
                                                'landing_page' => 'Landing Page',
                                            ];
                                        @endphp
                                        @foreach ($all_menus as $key => $label)
                                            <div class="flex items-center gap-3">
                                                <input type="checkbox" id="perm_{{ $key }}"
                                                    name="permissions[]" value="{{ $key }}"
                                                    class="w-4 h-4 rounded text-blue-600"
                                                    {{ in_array($key, old('permissions', [])) ? 'checked' : '' }}>
                                                <label for="perm_{{ $key }}"
                                                    class="text-[#d4d4d4] text-xs cursor-pointer">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-12">
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl uppercase tracking-widest transition-all">Create
                            User</button>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex-1 bg-transparent border border-[#4a4a4a] text-center py-4 rounded-xl hover:bg-[#2a2a2a] uppercase text-sm flex items-center justify-center">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        const rs = document.getElementById('role');
        const ps = document.getElementById('permissionsSection');
        rs.addEventListener('change', () => ps.style.display = rs.value === 'staff' ? 'block' : 'none');
        if (rs.value === 'staff') ps.style.display = 'block';
    </script>
</body>

</html>
