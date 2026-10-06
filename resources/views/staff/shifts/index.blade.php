<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>My Shift Schedule - Musicmen</title>
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
            <!-- Page Header -->
            <div class="mb-8 w-full">
                <div class="mb-4">
                    <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Jadwal Shift Saya</h1>
                    <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    <p class="text-[#9a9a9a] mt-2">{{ $staff->nama }} ({{ $staff->id_employee }})</p>
                </div>
            </div>

            <!-- Week Filter -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 mb-6">
                <form method="GET" action="{{ route('staff.shifts.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Pilih Minggu:</label>
                        <input type="date" name="week_start" value="{{ $weekStart }}"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                            onchange="this.form.submit()">
                    </div>
                    <div class="text-sm text-[#9a9a9a]">
                        <p>Minggu: {{ \Carbon\Carbon::parse($weekStart)->format('d M Y') }} - {{ \Carbon\Carbon::parse($weekEnd)->format('d M Y') }}</p>
                    </div>
                </form>
            </div>

            <!-- Schedule Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full overflow-x-auto">
                <div class="min-w-full">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Hari</th>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $days = [
                            'monday' => 'Senin',
                            'tuesday' => 'Selasa',
                            'wednesday' => 'Rabu',
                            'thursday' => 'Kamis',
                            'friday' => 'Jumat',
                            'saturday' => 'Sabtu',
                            'sunday' => 'Minggu',
                            ];
                            $scheduleMap = $schedules->keyBy('day');
                            @endphp
                            @foreach($days as $dayKey => $dayName)
                            @php
                            $schedule = $scheduleMap[$dayKey] ?? null;
                            @endphp
                            <tr class="hover:bg-[#2a2a2a] transition-colors" data-day="{{ $dayKey }}" @if($schedule) data-schedule-id="{{ $schedule->id }}" @endif>
                                <td class="border border-[#4a4a4a] px-4 py-3 text-white font-medium">{{ $dayName }}</td>
                                <td class="border border-[#4a4a4a] px-4 py-3 text-center">
                                    @if($schedule)
                                    @php
                                    $shiftTime = $shiftTimes[$schedule->shift] ?? null;
                                    $isLibur = strtolower($schedule->shift) === 'libur';
                                    @endphp

                                    <div class="flex flex-col gap-1">
                                        <span class="px-4 py-2 rounded text-sm font-semibold 
                {{ $isLibur ? 'bg-red-900/30 border border-red-500/50 text-red-500' : 
                   ($schedule->shift === 'pagi' ? 'bg-yellow-900/30 border border-yellow-500/50 text-yellow-400' : 
                   'bg-blue-900/30 border border-blue-500/50 text-blue-400') }}">
                                            {{ ucfirst($schedule->shift) }}
                                        </span>

                                        @if(!$isLibur && $shiftTime)
                                        <span class="text-[#9a9a9a] text-xs">
                                            {{ \Carbon\Carbon::parse($shiftTime->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($shiftTime->end_time)->format('H:i') }}
                                        </span>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-[#6a6a6a] text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($schedules->count() === 0)
            <div id="emptyScheduleMessage" class="mt-6 p-4 bg-yellow-900/20 border border-yellow-500/50 rounded-lg">
                <p class="text-sm text-yellow-400">Belum ada jadwal shift untuk minggu ini. Silakan hubungi administrator.</p>
            </div>
            @endif
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        // Realtime schedule updates
        let lastUpdate = new Date(Date.now() - 60000).toISOString(); // 1 minute ago
        let knownScheduleIds = new Set();
        let scheduleTableBody = document.querySelector('tbody');

        // Ganti logika shiftBadge di dalam fungsi updateSchedules()
        const isLibur = schedule.shift.toLowerCase() === 'libur';
        const shiftBadge = isLibur ?
            'bg-red-900/30 border border-red-500/50 text-red-500' :
            (schedule.shift === 'pagi' ?
                'bg-yellow-900/30 border border-yellow-500/50 text-yellow-400' :
                'bg-blue-900/30 border border-blue-500/50 text-blue-400');

        const timeText = (!isLibur && schedule.shift_time) ?
            `<span class="text-[#9a9a9a] text-xs">${schedule.shift_time.start} - ${schedule.shift_time.end}</span>` :
            (isLibur ? '<span class="text-[#6a6a6a] text-[10px]">Enjoy your day!</span>' : '');

        // Initialize known IDs from current table
        if (scheduleTableBody) {
            document.querySelectorAll('tbody tr[data-schedule-id]').forEach(row => {
                const id = row.getAttribute('data-schedule-id');
                if (id) knownScheduleIds.add(id);
            });
        }

        function updateSchedules() {
            const weekStart = '{{ $weekStart }}';

            fetch(`{{ route("staff.shifts.api.new") }}?week_start=${weekStart}&last_update=${encodeURIComponent(lastUpdate)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.schedules && data.schedules.length > 0) {
                        const days = {
                            'monday': 'Senin',
                            'tuesday': 'Selasa',
                            'wednesday': 'Rabu',
                            'thursday': 'Kamis',
                            'friday': 'Jumat',
                            'saturday': 'Sabtu',
                            'sunday': 'Minggu',
                        };

                        data.schedules.forEach(schedule => {
                            // Check if schedule for this day already exists
                            const existingRow = document.querySelector(`tr[data-day="${schedule.day}"]`);

                            if (existingRow) {
                                // Update existing row
                                const scheduleId = existingRow.getAttribute('data-schedule-id');
                                if (scheduleId && knownScheduleIds.has(scheduleId)) {
                                    // Row already exists and we know about it, just update
                                    const shiftCell = existingRow.querySelector('td:last-child');
                                    if (shiftCell) {
                                        const shiftBadge = schedule.shift === 'pagi' ?
                                            'bg-yellow-900/30 border border-yellow-500/50 text-yellow-400' :
                                            'bg-blue-900/30 border border-blue-500/50 text-blue-400';

                                        const timeText = schedule.shift_time ?
                                            `<span class="text-[#9a9a9a] text-xs">${schedule.shift_time.start} - ${schedule.shift_time.end}</span>` :
                                            '';

                                        shiftCell.innerHTML = `
                                        <div class="flex flex-col gap-1">
                                            <span class="px-4 py-2 rounded text-sm font-semibold ${shiftBadge}">
                                                ${schedule.shift.charAt(0).toUpperCase() + schedule.shift.slice(1)}
                                            </span>
                                            ${timeText}
                                        </div>
                                    `;
                                        existingRow.setAttribute('data-schedule-id', schedule.id);
                                        existingRow.classList.add('animate-pulse');
                                        setTimeout(() => {
                                            existingRow.classList.remove('animate-pulse');
                                        }, 2000);
                                    }
                                    return;
                                } else {
                                    // Update existing empty row
                                    existingRow.setAttribute('data-schedule-id', schedule.id);
                                    knownScheduleIds.add(schedule.id.toString());

                                    const shiftCell = existingRow.querySelector('td:last-child');
                                    if (shiftCell) {
                                        const shiftBadge = schedule.shift === 'pagi' ?
                                            'bg-yellow-900/30 border border-yellow-500/50 text-yellow-400' :
                                            'bg-blue-900/30 border border-blue-500/50 text-blue-400';

                                        const timeText = schedule.shift_time ?
                                            `<span class="text-[#9a9a9a] text-xs">${schedule.shift_time.start} - ${schedule.shift_time.end}</span>` :
                                            '';

                                        shiftCell.innerHTML = `
                                        <div class="flex flex-col gap-1">
                                            <span class="px-4 py-2 rounded text-sm font-semibold ${shiftBadge}">
                                                ${schedule.shift.charAt(0).toUpperCase() + schedule.shift.slice(1)}
                                            </span>
                                            ${timeText}
                                        </div>
                                    `;
                                        existingRow.classList.add('animate-pulse');
                                        setTimeout(() => {
                                            existingRow.classList.remove('animate-pulse');
                                        }, 2000);
                                    }

                                    // Remove empty message
                                    const emptyMessage = document.getElementById('emptyScheduleMessage');
                                    if (emptyMessage) {
                                        emptyMessage.remove();
                                    }
                                    return;
                                }
                            }

                            // Skip if already processed
                            if (knownScheduleIds.has(schedule.id.toString())) {
                                return;
                            }

                            // Add to known IDs
                            knownScheduleIds.add(schedule.id.toString());

                            // Create new row
                            const newRow = document.createElement('tr');
                            newRow.setAttribute('data-schedule-id', schedule.id);
                            newRow.setAttribute('data-day', schedule.day);
                            newRow.className = 'hover:bg-[#2a2a2a] transition-colors animate-pulse';

                            const shiftBadge = schedule.shift === 'pagi' ?
                                'bg-yellow-900/30 border border-yellow-500/50 text-yellow-400' :
                                'bg-blue-900/30 border border-blue-500/50 text-blue-400';

                            const timeText = schedule.shift_time ?
                                `<span class="text-[#9a9a9a] text-xs">${schedule.shift_time.start} - ${schedule.shift_time.end}</span>` :
                                '';

                            newRow.innerHTML = `
                            <td class="border border-[#4a4a4a] px-4 py-3 text-white font-medium">${schedule.day_name}</td>
                            <td class="border border-[#4a4a4a] px-4 py-3 text-center">
                                <div class="flex flex-col gap-1">
                                    <span class="px-4 py-2 rounded text-sm font-semibold ${shiftBadge}">
                                        ${schedule.shift.charAt(0).toUpperCase() + schedule.shift.slice(1)}
                                    </span>
                                    ${timeText}
                                </div>
                            </td>
                        `;

                            // Insert at correct position based on day order
                            if (scheduleTableBody) {
                                const dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                const currentDayIndex = dayOrder.indexOf(schedule.day);

                                let inserted = false;
                                const rows = Array.from(scheduleTableBody.querySelectorAll('tr'));

                                for (let i = 0; i < rows.length; i++) {
                                    const rowDay = rows[i].getAttribute('data-day');
                                    if (rowDay) {
                                        const rowDayIndex = dayOrder.indexOf(rowDay);
                                        if (rowDayIndex > currentDayIndex) {
                                            scheduleTableBody.insertBefore(newRow, rows[i]);
                                            inserted = true;
                                            break;
                                        }
                                    }
                                }

                                if (!inserted) {
                                    // Check if there's an empty message row
                                    const emptyMessage = scheduleTableBody.querySelector('td[colspan]');
                                    if (emptyMessage) {
                                        emptyMessage.closest('tr').remove();
                                    }
                                    scheduleTableBody.appendChild(newRow);
                                }

                                // Remove empty message if exists
                                const emptyMessage = document.getElementById('emptyScheduleMessage');
                                if (emptyMessage) {
                                    emptyMessage.remove();
                                }

                                // Remove animation after 2 seconds
                                setTimeout(() => {
                                    newRow.classList.remove('animate-pulse');
                                }, 2000);
                            }
                        });
                    }

                    // Update lastUpdate timestamp
                    if (data.last_update) {
                        lastUpdate = data.last_update;
                    }
                })
                .catch(error => {
                    console.error('Error fetching new schedules:', error);
                });
        }

        // Polling realtime dinonaktifkan untuk mencegah request API berulang.
        // Muat ulang halaman untuk melihat jadwal terbaru.
    </script>
</body>

</html>
