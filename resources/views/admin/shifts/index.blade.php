<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Schedule - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Shift Schedule</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('admin.shifts.create', ['week_start' => $weekStart]) }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Buat Jadwal Baru
                    </a>
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

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Week Filter -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 mb-6">
                <form method="GET" action="{{ route('admin.shifts.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
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
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-left text-sm text-[#9a9a9a] uppercase tracking-wider sticky left-0 z-10">Staff</th>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Senin</th>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Selasa</th>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Rabu</th>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Kamis</th>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Jumat</th>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Sabtu</th>
                                <th class="border border-[#4a4a4a] bg-[#0f0f0f] px-4 py-3 text-center text-sm text-[#9a9a9a] uppercase tracking-wider">Minggu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffs as $staff)
                                <tr class="hover:bg-[#2a2a2a] transition-colors">
                                    <td class="border border-[#4a4a4a] px-4 py-3 bg-[#0f0f0f] sticky left-0 z-10">
                                        <p class="text-white font-medium">{{ $staff->nama }}</p>
                                        <p class="text-[#6a6a6a] text-xs">{{ $staff->id_employee }}</p>
                                    </td>
                                    @php
                                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                    @endphp
                                    @foreach($days as $day)
                                        <td class="border border-[#4a4a4a] px-4 py-3 text-center">
                                    @php
                                        $schedule = isset($schedules[$staff->id]) && isset($schedules[$staff->id][$day]) ? $schedules[$staff->id][$day][0] ?? null : null;
                                    @endphp
                                            @if($schedule)
                                                @php
                                                    $shiftTime = $shiftTimes[$schedule->shift] ?? null;
                                                @endphp
                                                <div class="flex flex-col gap-1">
                                                    @if($schedule->shift === 'libur')
                                                        <span class="px-3 py-1 rounded text-xs font-semibold bg-red-900/30 border border-red-500/50 text-red-400">
                                                            Libur
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1 rounded text-xs font-semibold {{ $schedule->shift === 'pagi' ? 'bg-yellow-900/30 border border-yellow-500/50 text-yellow-400' : 'bg-blue-900/30 border border-blue-500/50 text-blue-400' }}">
                                                            {{ ucfirst($schedule->shift) }}
                                                        </span>
                                                        @if($shiftTime)
                                                            <span class="text-[#6a6a6a] text-xs">{{ \Carbon\Carbon::parse($shiftTime->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shiftTime->end_time)->format('H:i') }}</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-[#6a6a6a] text-xs">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="border border-[#4a4a4a] px-4 py-8 text-center text-[#9a9a9a]">Tidak ada staff</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            @if($staffs->count() > 0)
            <div class="mt-6 flex gap-4">
                <a href="{{ route('admin.shifts.create', ['week_start' => $weekStart]) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                    Buat/Edit Jadwal
                </a>
                <a href="{{ route('admin.shifts.times') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                    Setting Jam Shift
                </a>
                @if($schedules->count() > 0)
                <form method="POST" action="{{ route('admin.shifts.destroy', $weekStart) }}" 
                      class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal minggu ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                        Hapus Jadwal
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>