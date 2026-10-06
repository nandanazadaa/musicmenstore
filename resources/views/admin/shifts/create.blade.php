<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shift Schedule - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Buat Jadwal Shift</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                        <p class="text-[#9a9a9a] mt-2">Minggu: {{ \Carbon\Carbon::parse($weekStart)->format('d M Y') }} - {{ \Carbon\Carbon::parse($weekEnd)->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.shifts.index', ['week_start' => $weekStart]) }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Kembali
                    </a>
                </div>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm text-red-400">{{ session('error') }}</p>
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

            <!-- Schedule Form -->
            <form method="POST" action="{{ route('admin.shifts.store') }}" id="scheduleForm">
                @csrf
                <input type="hidden" name="week_start_date" value="{{ $weekStart }}">
                <input type="hidden" name="week_end_date" value="{{ $weekEnd }}">

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full overflow-x-auto mb-6">
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
                                @php
                                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                @endphp
                                @forelse($staffs as $staff)
                                    <tr class="hover:bg-[#2a2a2a] transition-colors">
                                        <td class="border border-[#4a4a4a] px-4 py-3 bg-[#0f0f0f] sticky left-0 z-10">
                                            <p class="text-white font-medium">{{ $staff->nama }}</p>
                                            <p class="text-[#6a6a6a] text-xs">{{ $staff->id_employee }}</p>
                                        </td>
                                        @foreach($days as $day)
                                            <td class="border border-[#4a4a4a] px-4 py-3">
                                                @php
                                                    $existingSchedule = $existingSchedules[$staff->id][$day][0] ?? null;
                                                @endphp
                                                <select name="schedules[{{ $staff->id }}][{{ $day }}][shift]" 
                                                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-2 py-1 text-white text-sm focus:outline-none focus:border-[#6a6a6a] transition-all">
                                                    <option value="" {{ !$existingSchedule || $existingSchedule->shift === '' ? 'selected' : '' }}>-</option>
                                                    <option value="pagi" {{ $existingSchedule && $existingSchedule->shift === 'pagi' ? 'selected' : '' }}>
                                                        Pagi @if(isset($shiftTimes['pagi'])) ({{ \Carbon\Carbon::parse($shiftTimes['pagi']->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($shiftTimes['pagi']->end_time)->format('H:i') }}) @endif
                                                    </option>
                                                    <option value="siang" {{ $existingSchedule && $existingSchedule->shift === 'siang' ? 'selected' : '' }}>
                                                        Siang @if(isset($shiftTimes['siang'])) ({{ \Carbon\Carbon::parse($shiftTimes['siang']->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($shiftTimes['siang']->end_time)->format('H:i') }}) @endif
                                                    </option>
                                                    <option value="libur" {{ $existingSchedule && $existingSchedule->shift === 'libur' ? 'selected' : '' }}>
                                                        Libur
                                                    </option>
                                                </select>
                                                <input type="hidden" name="schedules[{{ $staff->id }}][{{ $day }}][staff_id]" value="{{ $staff->id }}">
                                                <input type="hidden" name="schedules[{{ $staff->id }}][{{ $day }}][day]" value="{{ $day }}">
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

                <div class="flex gap-4">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg transition-all uppercase tracking-wider font-semibold">
                        Simpan Jadwal
                    </button>
                    <a href="{{ route('admin.shifts.index', ['week_start' => $weekStart]) }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-8 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>