<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Staff Attendance - {{ $staff->nama }} - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Staff Attendance</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                        <p class="text-[#9a9a9a] mt-2">{{ $staff->nama }} ({{ $staff->id_employee }})</p>
                    </div>
                    <a href="{{ route('admin.attendance.index') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Back to Report
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider mb-2">Total Days</h3>
                    <p class="text-white text-3xl font-semibold">{{ $totalDays }}</p>
                </div>
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider mb-2">Present Days</h3>
                    <p class="text-green-400 text-3xl font-semibold">{{ $presentDays }}</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 mb-6">
                <form method="GET" action="{{ route('admin.attendance.staff', $staff->id) }}" class="flex flex-col sm:flex-row gap-4">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                    <button type="submit" 
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Filter
                    </button>
                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('admin.attendance.staff', $staff->id) }}" 
                           class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Attendance Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Date</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Check In</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Location</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Status</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4 text-white text-sm">{{ $attendance->attendance_date->format('d M Y') }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        @if($attendance->check_in_time)
                                            <span class="text-green-400">{{ $attendance->check_in_time }}</span>
                                        @else
                                            <span class="text-[#6a6a6a]">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        @if($attendance->check_in_address)
                                            <div class="flex flex-col gap-1">
                                                <span class="text-[#9a9a9a] text-xs">{{ Str::limit($attendance->check_in_address, 50) }}</span>
                                                @if($attendance->check_in_latitude && $attendance->check_in_longitude)
                                                    <a href="https://www.google.com/maps?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}" 
                                                       target="_blank" 
                                                       class="text-blue-400 hover:text-blue-300 text-xs inline-flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                            <circle cx="12" cy="10" r="3"></circle>
                                                        </svg>
                                                        Lihat di Peta
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-[#6a6a6a]">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        @if($attendance->check_in_time)
                                            <span class="px-2 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs">Present</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-900/30 border border-red-500/50 rounded text-red-400 text-xs">Absent</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <form method="POST" action="{{ route('admin.attendance.destroy', $attendance->id) }}" 
                                              class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Delete Attendance">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-4 text-center text-[#9a9a9a]">No attendance records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($attendances->hasPages())
                <div class="mt-6 flex items-center justify-center">
                    <div class="flex gap-2">
                        @if($attendances->onFirstPage())
                            <span class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $attendances->previousPageUrl() }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Previous</a>
                        @endif

                        @foreach($attendances->getUrlRange(1, $attendances->lastPage()) as $page => $url)
                            @if($page == $attendances->currentPage())
                                <span class="px-4 py-2 bg-[#2a2a2a] border border-[#6a6a6a] rounded-lg text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($attendances->hasMorePages())
                            <a href="{{ $attendances->nextPageUrl() }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Next</a>
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
    @php
        use Illuminate\Support\Str;
    @endphp
</body>
</html>
