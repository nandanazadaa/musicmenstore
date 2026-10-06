<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Attendance Report - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Attendance Report</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('admin.attendance.export', request()->query()) }}" 
                       class="bg-transparent border border-green-600 text-green-400 px-6 py-3 rounded-lg hover:bg-green-900/20 hover:border-green-500 transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export Excel
                    </a>

                 
                </div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl text-white">History Rekap Bulanan</h2>
                    
                    <form action="{{ route('admin.attendance.reset') }}" method="POST" onsubmit="confirmReset(event)">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all">
                            Reset & Rekap Poin Bulanan
                        </button>
                    </form>
                </div>
            </div>

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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider mb-2">Total Staff</h3>
                    <p class="text-white text-3xl font-semibold">{{ $totalStaffs }}</p>
                </div>
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider mb-2">Today Present</h3>
                    <p id="todayPresentCount" class="text-green-400 text-3xl font-semibold">{{ $todayPresent }}</p>
                </div>
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider mb-2">Today Absent</h3>
                    <p id="todayAbsentCount" class="text-red-400 text-3xl font-semibold">{{ $todayAbsent }}</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 mb-6">
                <form method="GET" action="{{ route('admin.attendance.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <select name="staff_id" 
                            class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        <option value="">All Staff</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                                {{ $staff->nama }} ({{ $staff->id_employee }})
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                    <select name="status" 
                            class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                    </select>
                    <button type="submit" 
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Filter
                    </button>
                    @if(request('staff_id') || request('start_date') || request('end_date') || request('status'))
                        <a href="{{ route('admin.attendance.index') }}" 
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
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Staff</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Check In</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Location</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Status</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors" data-attendance-id="{{ $attendance->id }}">
                                    <td class="py-3 px-4 text-white text-sm">{{ $attendance->attendance_date->format('d M Y') }}</td>
                                    <td class="py-3 px-4 text-white text-sm">
                                        <a href="{{ route('admin.attendance.staff', $attendance->staff_id) }}" 
                                           class="text-blue-400 hover:text-blue-300">
                                            {{ $attendance->staff->nama }}
                                        </a>
                                        <p class="text-[#6a6a6a] text-xs">{{ $attendance->staff->id_employee }}</p>
                                    </td>
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
                                                <span class="text-[#9a9a9a] text-xs">{{ Str::limit($attendance->check_in_address, 40) }}</span>
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
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.attendance.staff', $attendance->staff_id) }}" 
                                               class="text-blue-400 hover:text-blue-300 transition-colors" title="View Staff Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
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
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 px-4 text-center text-[#9a9a9a]">No attendance records found</td>
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

    <!-- Notification Toast -->
    <div id="notificationToast" class="fixed top-20 right-4 z-50 hidden">
        <div class="bg-green-900/90 border border-green-500/50 rounded-lg p-4 shadow-lg min-w-[300px] max-w-md">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <div class="flex-1">
                    <p class="text-white font-semibold text-sm">Absensi Baru</p>
                    <p id="notificationMessage" class="text-[#9a9a9a] text-xs mt-1"></p>
                </div>
                <button onclick="document.getElementById('notificationToast').classList.add('hidden')" class="text-[#9a9a9a] hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Konfirmasi sebelum Reset
        function confirmReset(event) {
            event.preventDefault();
            const form = event.target;
    
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Poin semua staff akan di-reset ke 0 dan data bulan lalu akan diarsipkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Rekap & Reset!',
                cancelButtonText: 'Batal',
                background: '#1a1a1a',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    
        // Tampilkan Alert Sukses dari Session
        @if(session('swal_success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('swal_success') }}",
                timer: 3000,
                showConfirmButton: false,
                background: '#1a1a1a',
                color: '#fff'
            });
        @endif
    
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                background: '#1a1a1a',
                color: '#fff'
            });
        @endif
    </script>

    <script>
        // Realtime attendance updates
        let lastUpdate = new Date(Date.now() - 60000).toISOString(); // 1 minute ago
        let attendanceTableBody = document.querySelector('tbody');
        let knownAttendanceIds = new Set();
        
        // Initialize known IDs from current table
        document.querySelectorAll('tbody tr[data-attendance-id]').forEach(row => {
            const id = row.getAttribute('data-attendance-id');
            if (id) knownAttendanceIds.add(id);
        });

        function updateAttendances() {
            fetch('{{ route("admin.attendance.api.new") }}?last_update=' + encodeURIComponent(lastUpdate), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update statistics
                    if (data.statistics) {
                        const todayPresentEl = document.getElementById('todayPresentCount');
                        const todayAbsentEl = document.getElementById('todayAbsentCount');
                        if (todayPresentEl && data.statistics.today_present !== undefined) {
                            todayPresentEl.textContent = data.statistics.today_present;
                        }
                        if (todayAbsentEl && data.statistics.today_absent !== undefined) {
                            todayAbsentEl.textContent = data.statistics.today_absent;
                        }
                    }

                    // Process new attendances
                    if (data.attendances && data.attendances.length > 0) {
                        data.attendances.forEach(attendance => {
                            // Skip if already in table
                            if (knownAttendanceIds.has(attendance.id.toString())) {
                                return;
                            }

                            // Add to known IDs
                            knownAttendanceIds.add(attendance.id.toString());

                            // Create new row
                            const newRow = document.createElement('tr');
                            newRow.setAttribute('data-attendance-id', attendance.id);
                            newRow.className = 'border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors animate-pulse';
                            
                            const statusBadge = attendance.status === 'present' 
                                ? '<span class="px-2 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs">Present</span>'
                                : '<span class="px-2 py-1 bg-red-900/30 border border-red-500/50 rounded text-red-400 text-xs">Absent</span>';
                            
                            const checkInTime = attendance.check_in_time 
                                ? `<span class="text-green-400">${attendance.check_in_time}</span>`
                                : '<span class="text-[#6a6a6a]">-</span>';
                            
                            let addressHtml = '<span class="text-[#6a6a6a]">-</span>';
                            if (attendance.check_in_address) {
                                const addressText = attendance.check_in_address.length > 40 
                                    ? attendance.check_in_address.substring(0, 40) + '...' 
                                    : attendance.check_in_address;
                                
                                if (attendance.check_in_latitude && attendance.check_in_longitude) {
                                    addressHtml = `
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[#9a9a9a] text-xs">${addressText}</span>
                                            <a href="https://www.google.com/maps?q=${attendance.check_in_latitude},${attendance.check_in_longitude}" 
                                               target="_blank" 
                                               class="text-blue-400 hover:text-blue-300 text-xs inline-flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                Lihat di Peta
                                            </a>
                                        </div>
                                    `;
                                } else {
                                    addressHtml = `<span class="text-[#9a9a9a] text-xs">${addressText}</span>`;
                                }
                            }

                            const staffDetailUrl = '{{ route("admin.attendance.staff", ":staffId") }}'.replace(':staffId', attendance.staff_id);
                            const deleteUrl = '{{ route("admin.attendance.destroy", ":id") }}'.replace(':id', attendance.id);
                            
                            newRow.innerHTML = `
                                <td class="py-3 px-4 text-white text-sm">${attendance.attendance_date}</td>
                                <td class="py-3 px-4 text-white text-sm">
                                    <a href="${staffDetailUrl}" 
                                       class="text-blue-400 hover:text-blue-300">
                                        ${attendance.staff_name}
                                    </a>
                                    <p class="text-[#6a6a6a] text-xs">${attendance.staff_employee_id}</p>
                                </td>
                                <td class="py-3 px-4 text-sm">${checkInTime}</td>
                                <td class="py-3 px-4 text-sm">${addressHtml}</td>
                                <td class="py-3 px-4 text-sm">${statusBadge}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <a href="${staffDetailUrl}" 
                                           class="text-blue-400 hover:text-blue-300 transition-colors" title="View Staff Details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        <form method="POST" action="${deleteUrl}" 
                                              class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?');">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Delete Attendance">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            `;

                            // Insert at the top of table (after header row if exists)
                            if (attendanceTableBody) {
                                // Check if there's an empty row message
                                const emptyRow = attendanceTableBody.querySelector('td[colspan]');
                                if (emptyRow) {
                                    emptyRow.closest('tr').remove();
                                }
                                
                                attendanceTableBody.insertBefore(newRow, attendanceTableBody.firstChild);
                                
                                // Remove animation class after 2 seconds
                                setTimeout(() => {
                                    newRow.classList.remove('animate-pulse');
                                }, 2000);

                                // Show notification
                                if (attendance.check_in_time) {
                                    showNotification(`${attendance.staff_name} melakukan check-in pada ${attendance.check_in_time}`);
                                }
                            }
                        });
                    }

                    // Update lastUpdate timestamp
                    if (data.last_update) {
                        lastUpdate = data.last_update;
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching new attendances:', error);
            });
        }

        function showNotification(message) {
            const toast = document.getElementById('notificationToast');
            const messageEl = document.getElementById('notificationMessage');
            
            if (toast && messageEl) {
                messageEl.textContent = message;
                toast.classList.remove('hidden');
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 5000);
            }
        }

        // Polling realtime dinonaktifkan untuk mencegah request API berulang
        // yang dapat memicu HTTP 429. Muat ulang halaman untuk data terbaru.
    </script>
</body>
</html>
