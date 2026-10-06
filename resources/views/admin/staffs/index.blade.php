<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staffs - Musicmen Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body {
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
            <!-- Page Header -->
            <div class="mb-8 w-full">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Manage Staffs</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('admin.staffs.create') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5l0 14" /><path d="M5 12l14 0" />
                        </svg>
                        Add Staff
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Info Box -->
            <div class="mb-6 p-4 bg-blue-900/20 border border-blue-500/50 rounded-lg">
                <p class="text-sm text-blue-400">
                    <strong>Penting:</strong> Untuk staff dapat mengakses menu Attendance & Assignments, staff profile harus dihubungkan dengan User Account. 
                    Edit staff dan pilih User Account di field "Link User Account".
                </p>
            </div>

            <!-- Staffs Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.staffs.index') }}" class="mb-6">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                           placeholder="Search by ID Employee, name, phone, position...">
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Photo</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">ID Employee</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Nama</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Nomor Telepon</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Jabatan</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">User Account</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffs as $staff)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4">
                                        @if($staff->photo_profile)
                                            <img src="{{ asset($staff->photo_profile) }}" alt="{{ $staff->nama }}" 
                                                 class="w-16 h-16 object-cover rounded-full">
                                        @else
                                            <div class="w-16 h-16 bg-[#2a2a2a] rounded-full flex items-center justify-center text-[#6a6a6a] text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm font-mono">{{ $staff->id_employee }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $staff->nama }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $staff->nomor_telepon }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $staff->jabatan }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        @if($staff->user_id && $staff->user)
                                            <span class="px-2 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs">
                                                {{ $staff->user->name }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-red-900/30 border border-red-500/50 rounded text-red-400 text-xs">
                                                Not Linked
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.staffs.edit', $staff->id) }}" 
                                               class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Edit Staff">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.staffs.resetPoints', $staff->id) }}" 
                                                  class="inline" onsubmit="return confirm('Apakah Anda yakin ingin reset point absensi untuk staff ini?');">
                                                @csrf
                                                <button type="submit" class="text-blue-400 hover:text-blue-300 transition-colors" title="Reset Point Absensi">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                                        <path d="M21 3v5h-5"></path>
                                                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                                        <path d="M3 21v-5h5"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.staffs.destroy', $staff->id) }}" 
                                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this staff?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Delete Staff">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 px-4 text-center text-[#9a9a9a]">No staffs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($staffs->hasPages())
                <div class="mt-6 flex items-center justify-center">
                    <div class="flex gap-2">
                        @if($staffs->onFirstPage())
                            <span class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $staffs->previousPageUrl() }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Previous</a>
                        @endif

                        @foreach($staffs->getUrlRange(1, $staffs->lastPage()) as $page => $url)
                            @if($page == $staffs->currentPage())
                                <span class="px-4 py-2 bg-[#2a2a2a] border border-[#6a6a6a] rounded-lg text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($staffs->hasMorePages())
                            <a href="{{ $staffs->nextPageUrl() }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Next</a>
                        @else
                            <span class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>
