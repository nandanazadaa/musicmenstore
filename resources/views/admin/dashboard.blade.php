<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Musicmen Store</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div class="w-full">
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Dashboard</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-12 w-full">
                {{-- 
    GANTI BAGIAN STATS CARDS DI dashboard.blade.php
    Bagian: @if (Auth::user()->role === 'staff' && $staff) ... @endif
    Tambahkan fallback jika $staff null
--}}

                @if (Auth::user()->role === 'staff')
                    @if ($staff)
                        <!-- Points Card (Staff Only) -->
                        <div
                            class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 hover:border-[#6a6a6a] transition-all w-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider">My Points</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-3xl font-light text-white" id="staffPoints">
                                {{ number_format($staff->points, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-[#6a6a6a] mt-2">
                                Nilai: Rp {{ number_format($staff->points * 1000, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-[#4a4a4a] mt-1">1 Point = Rp 1.000 (Masuk ke Insentif Kinerja)</p>
                        </div>

                        <!-- Recent Attendance Card (Staff Only) -->
                        <div
                            class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 hover:border-[#6a6a6a] transition-all w-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider">Today's Attendance</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6a6a6a]"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            @php
                                $todayAttendance = $staff->getTodayAttendance();
                            @endphp
                            @if ($todayAttendance && $todayAttendance->check_in_time)
                                <p class="text-lg text-white">Check-in: {{ $todayAttendance->check_in_time }}</p>
                                @if ($todayAttendance->check_out_time)
                                    <p class="text-lg text-white mt-2">Check-out: {{ $todayAttendance->check_out_time }}
                                    </p>
                                @else
                                    <p class="text-sm text-[#6a6a6a] mt-2">Belum check-out</p>
                                @endif
                            @else
                                <p class="text-lg text-[#6a6a6a]">Belum check-in hari ini</p>
                            @endif
                        </div>

                        <!-- Jadwal Hari Ini Card (Staff Only) -->
                        <div
                            class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 hover:border-[#6a6a6a] transition-all w-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider">Jadwal Hari Ini</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                    </rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div id="today-schedule-content">
                                @if ($todaySchedule)
                                    <p class="text-lg text-white">{{ $todaySchedule->day_name }}</p>
                                    @if ($todaySchedule->isLibur())
                                        <p class="text-sm text-red-500 mt-2 uppercase font-bold tracking-widest">LIBUR
                                        </p>
                                        <p class="text-xs text-[#6a6a6a] mt-1">Selamat beristirahat, men!</p>
                                    @else
                                        <p class="text-sm text-blue-400 mt-2 uppercase font-semibold">
                                            {{ ucfirst($todaySchedule->shift) }}</p>
                                        @if ($todaySchedule->shift_time)
                                            <p class="text-sm text-[#9a9a9a] mt-1">
                                                {{ \Carbon\Carbon::parse($todaySchedule->shift_time->start_time)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($todaySchedule->shift_time->end_time)->format('H:i') }}
                                            </p>
                                        @endif
                                    @endif
                                @else
                                    <p class="text-lg text-[#6a6a6a]">Tidak ada jadwal hari ini</p>
                                @endif
                            </div>
                        </div>

                        <!-- Tugas Terbaru Card (Staff Only) -->
                        <div
                            class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 hover:border-[#6a6a6a] transition-all w-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider">Tugas Terbaru</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                            <div id="latest-task-content" class="space-y-2 max-h-48 overflow-y-auto">
                                @if ($todayAssignments && $todayAssignments->count() > 0)
                                    @foreach ($todayAssignments->take(3) as $assignment)
                                        <div class="latest-task-item" data-assignment-id="{{ $assignment->id }}">
                                            <p class="text-sm text-white font-medium">
                                                {{ Str::limit($assignment->title, 30) }}</p>
                                            <p class="text-xs text-[#6a6a6a] mt-1">
                                                {{ $assignment->created_at->format('H:i') }}</p>
                                            @if ($assignment->status === 'pending')
                                                <span
                                                    class="inline-block mt-1 px-2 py-0.5 bg-yellow-900/30 border border-yellow-500/50 rounded text-yellow-400 text-xs">Pending</span>
                                            @elseif ($assignment->status === 'in_progress')
                                                <span
                                                    class="inline-block mt-1 px-2 py-0.5 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-xs">In
                                                    Progress</span>
                                            @else
                                                <span
                                                    class="inline-block mt-1 px-2 py-0.5 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs">Completed</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-[#6a6a6a]">Belum ada tugas hari ini</p>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Staff record tidak ditemukan - tampilkan pesan warning --}}
                        <div class="col-span-full bg-[#1a1a1a] border border-yellow-700/50 rounded-lg p-6">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 flex-shrink-0"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                    </path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                <div>
                                    <p class="text-yellow-400 font-semibold">Data staff tidak ditemukan</p>
                                    <p class="text-[#9a9a9a] text-sm mt-1">
                                        Akun ini belum terhubung ke data staff. Hubungi admin untuk menghubungkan akun
                                        Anda.
                                        <br>
                                        <span class="text-xs text-[#6a6a6a]">User ID: {{ Auth::id() }} | Email:
                                            {{ Auth::user()->email }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if (Auth::user()->role === 'admin')
                    <!-- Total Products Card -->
                    <div
                        class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 hover:border-[#6a6a6a] transition-all w-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider">Total Products</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6a6a6a]"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17h-11v-14h-2" />
                                <path d="M6 5l14 1l-1 7h-13" />
                            </svg>
                        </div>
                        <p class="text-3xl font-light text-white">24</p>
                    </div>

                    <!-- Total Members Card -->
                    <div
                        class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 hover:border-[#6a6a6a] transition-all w-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider">Total Members</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6a6a6a]"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                        </div>
                        <p class="text-3xl font-light text-white">{{ $totalMembers ?? 0 }}</p>
                    </div>

                    <!-- Jumlah Staff Card -->
                    <div
                        class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 hover:border-[#6a6a6a] transition-all w-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[#9a9a9a] text-sm uppercase tracking-wider">Jumlah Staff</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6a6a6a]"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-light text-white">{{ $totalStaff ?? 0 }}</p>
                    </div>
                @endif
            </div>

            @if (Auth::user()->role === 'staff' && $staff)
                <!-- Staff Rules & Notes Section -->
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full mt-6">
                    <h2 class="serif-font text-xl sm:text-2xl text-white mb-4 sm:mb-6">Aturan Dasar dan Poin
                        Pelanggaran
                    </h2>

                    <div class="space-y-6">
                        <!-- Tujuan -->
                        <div class="bg-[#0f0f0f] border border-[#3a3a3a] rounded-lg p-4">
                            <h3 class="text-blue-400 font-semibold mb-2 uppercase text-sm">Tujuan</h3>
                            <p class="text-[#d4d4d4] text-sm leading-relaxed">
                                Aturan Dasar ini dibuat untuk menciptakan lingkungan kerja professional, memelihara
                                kedisplinan sekaligus sebagai bentuk pengawasan dan memastikan bahwa setiap tindakan
                                karyawan selaras dengan nilai - nilai store dan regulasi yang berlaku, memberikan
                                perlakuan yang setara bagi seluruh staf dan mendukung pencapaian visi strategis Store.
                            </p>
                        </div>

                        <!-- Pembagian Peran -->
                        <div class="bg-[#0f0f0f] border border-[#3a3a3a] rounded-lg p-4">
                            <h3 class="text-green-400 font-semibold mb-2 uppercase text-sm">Pembagian Peran</h3>
                            <p class="text-[#d4d4d4] text-sm leading-relaxed">
                                Setiap karyawan bertanggungjawab atas pelaksanaan kegiatan operasional dan kepatuhan
                                prosedur harian store menjadi tanggungjawab penuh karyawan dibawah koordinasi Head Store
                                atau Owner. sementara itu otoritas tertinggi dalam pengawasan, penegakan sanksi
                                disppliner, dan penentuan kebijakan strategis berada ditangan Owner Store.
                            </p>
                        </div>

                        <!-- Aturan Dasar -->
                        <div class="bg-[#0f0f0f] border border-[#3a3a3a] rounded-lg p-4">
                            <h3 class="text-white font-semibold mb-3 uppercase text-sm">Aturan Dasar</h3>
                            <ul class="space-y-2 text-[#d4d4d4] text-sm">
                                <li class="flex items-start gap-2">
                                    <span class="text-[#6a6a6a]">1.</span>
                                    <span>Setiap pelanggaran memiliki nilai poin tertentu.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-[#6a6a6a]">2.</span>
                                    <span>Jika karyawan mengumpulkan poin tertentu, akan diberikan Surat Peringatan
                                        (SP).</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-[#6a6a6a]">3.</span>
                                    <span>Poin akan di reset setiap 3 bulan jika tidak ada pelanggaran baru (di hitung
                                        dari hari setelah diberikan poin/teguran).</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Kategori Pelanggaran -->
                        <div class="bg-[#0f0f0f] border border-[#3a3a3a] rounded-lg p-4">
                            <h3 class="text-white font-semibold mb-3 uppercase text-sm">Kategori Pelanggaran dan Nilai
                                Poin</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-[#4a4a4a]">
                                            <th class="text-left py-2 px-3 text-[#9a9a9a] uppercase text-xs">No</th>
                                            <th class="text-left py-2 px-3 text-[#9a9a9a] uppercase text-xs">Jenis
                                                Pelanggaran</th>
                                            <th class="text-left py-2 px-3 text-[#9a9a9a] uppercase text-xs">Point</th>
                                            <th class="text-left py-2 px-3 text-[#9a9a9a] uppercase text-xs">Keterangan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-[#d4d4d4]">
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">1</td>
                                            <td class="py-2 px-3">Terlambat datang tanpa alasan jelas</td>
                                            <td class="py-2 px-3 text-yellow-400">2</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">2</td>
                                            <td class="py-2 px-3">Tidak hadir tanpa izin (1 hari)</td>
                                            <td class="py-2 px-3 text-yellow-400">5</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">3</td>
                                            <td class="py-2 px-3">Berpakaian tidak sesuai jadwal</td>
                                            <td class="py-2 px-3 text-yellow-400">1</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">4</td>
                                            <td class="py-2 px-3">Berpakaian tidak rapi</td>
                                            <td class="py-2 px-3 text-yellow-400">1</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">5</td>
                                            <td class="py-2 px-3">Main Hp keperluan pribadi saat melayani customer</td>
                                            <td class="py-2 px-3 text-yellow-400">2</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">6</td>
                                            <td class="py-2 px-3">Berbicara tidak sopan ke customer</td>
                                            <td class="py-2 px-3 text-yellow-400">3</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">7</td>
                                            <td class="py-2 px-3">Meninggalkan store tanpa izin atau sebelum jam kerja
                                                berakhir</td>
                                            <td class="py-2 px-3 text-yellow-400">4</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">8</td>
                                            <td class="py-2 px-3">Area kerja kotor/tidak rapi</td>
                                            <td class="py-2 px-3 text-yellow-400">2</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">9</td>
                                            <td class="py-2 px-3">Barang rusak karna kelalaian</td>
                                            <td class="py-2 px-3 text-yellow-400">5</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">dan mengganti kerusakan</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">10</td>
                                            <td class="py-2 px-3">Menggunakan uang kas tanpa izin</td>
                                            <td class="py-2 px-3 text-yellow-400">8</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">dan mengganti uang kas yang digunakan
                                            </td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">11</td>
                                            <td class="py-2 px-3">Bertengkar / berdebat kasar di store</td>
                                            <td class="py-2 px-3 text-yellow-400">10</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">12</td>
                                            <td class="py-2 px-3">Meminum Minuman Alkohol diarea store</td>
                                            <td class="py-2 px-3 text-yellow-400">11</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">dan membuang/memindahkan minuman
                                                alkohol tersebut jauh dari store</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">13</td>
                                            <td class="py-2 px-3">Tiduran diarea store saat jam kerja</td>
                                            <td class="py-2 px-3 text-yellow-400">3</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">-</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">14</td>
                                            <td class="py-2 px-3">Mengambil produk toko/ Inventaris Tanpa Izin owner
                                            </td>
                                            <td class="py-2 px-3 text-yellow-400">10</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">dan mengganti barang yang telah
                                                diambil</td>
                                        </tr>
                                        <tr class="border-b border-[#3a3a3a]">
                                            <td class="py-2 px-3">15</td>
                                            <td class="py-2 px-3">Manipulasi data penjualan / pencurian</td>
                                            <td class="py-2 px-3 text-red-400 font-semibold">15</td>
                                            <td class="py-2 px-3 text-[#9a9a9a]">dan mengembalikan barang yang sudah
                                                dicuri dengan keadaan seperti awal sebelum dicuri. serta bisa dilaporkan
                                                ke pihak yang berwajib</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Level Peringatan -->
                        <div class="bg-[#0f0f0f] border border-[#3a3a3a] rounded-lg p-4">
                            <h3 class="text-white font-semibold mb-3 uppercase text-sm">Level Peringatan dan Tindakan
                            </h3>
                            <div class="space-y-3">
                                <div class="bg-green-900/20 border border-green-700/50 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-green-400 font-semibold">Level 1 - Peringatan Awal</span>
                                        <span class="text-white font-semibold">10 Total Poin</span>
                                    </div>
                                    <p class="text-[#d4d4d4] text-sm">Teguran tertulis & pembinaan langsung oleh owner
                                    </p>
                                </div>
                                <div class="bg-yellow-900/20 border border-yellow-700/50 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-yellow-400 font-semibold">Level 2 - Peringatan Serius</span>
                                        <span class="text-white font-semibold">20 Total Poin</span>
                                    </div>
                                    <p class="text-[#d4d4d4] text-sm">Teguran keras, potongan gaji atau penurunan shift
                                        dan insentif</p>
                                </div>
                                <div class="bg-red-900/20 border border-red-700/50 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-red-400 font-semibold">Level 3 - Peringatan Akhir</span>
                                        <span class="text-white font-semibold">30 Total Poin</span>
                                    </div>
                                    <p class="text-[#d4d4d4] text-sm">Pemutusan Hubungan Kerja (PHK)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tugas Terbaru (Staff Only) -->
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full mt-6">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h2 class="serif-font text-xl sm:text-2xl text-white">Informasi Tugas Terbaru</h2>
                        <span class="text-xs text-[#6a6a6a]">Auto-update setiap 2 detik</span>
                    </div>
                    <div id="today-assignments-content" class="space-y-3">
                        @if ($todayAssignments && $todayAssignments->count() > 0)
                            @foreach ($todayAssignments as $assignment)
                                <div class="bg-[#0f0f0f] border border-[#3a3a3a] rounded-lg p-4 assignment-item"
                                    data-assignment-id="{{ $assignment->id }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-white font-semibold mb-1">{{ $assignment->title }}</h3>
                                            <p class="text-[#9a9a9a] text-sm mb-2">
                                                {{ Str::limit($assignment->description ?? '-', 100) }}</p>
                                            <div class="flex items-center gap-4 text-xs text-[#6a6a6a]">
                                                <span>Oleh: {{ $assignment->creator->name }}</span>
                                                <span>{{ $assignment->created_at->format('d M Y H:i') }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            @if ($assignment->status === 'pending')
                                                <span
                                                    class="px-2 py-1 bg-yellow-900/30 border border-yellow-500/50 rounded text-yellow-400 text-xs uppercase">Pending</span>
                                            @elseif ($assignment->status === 'in_progress')
                                                <span
                                                    class="px-2 py-1 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-xs uppercase">In
                                                    Progress</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs uppercase">Completed</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-[#9a9a9a] text-sm text-center py-4">Belum ada tugas hari ini</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->


            @if (Auth::user()->role === 'admin')
                <!-- New Members Realtime (Admin Only) -->
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full mt-6">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h2 class="serif-font text-xl sm:text-2xl text-white">Member Baru (Realtime)</h2>
                        <span class="text-xs text-[#6a6a6a]">Auto-update setiap 5 detik</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full" id="newMembersTable">
                            <thead>
                                <tr class="border-b border-[#4a4a4a]">
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">ID
                                        Member</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Nama</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Phone</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Email</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Tanggal Daftar</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="newMembersTableBody">
                                <tr>
                                    <td colspan="6" class="py-4 px-4 text-center text-[#9a9a9a] text-sm">Memuat
                                        data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if (Auth::user()->role === 'admin')
                <!-- Monthly Income Chart -->
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full mt-6">
                    <h2 class="serif-font text-xl sm:text-2xl text-white mb-4 sm:mb-6">Laporan Pemasukan Bulanan</h2>
                    <div class="w-full" style="height: 400px;">
                        <canvas id="monthlyIncomeChart"></canvas>
                    </div>
                </div>
            @endif

            <!-- Members List -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full mt-6">
                <h2 class="serif-font text-xl sm:text-2xl text-white mb-4 sm:mb-6">Recent Members</h2>
                @if (isset($members) && $members->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-[#4a4a4a]">
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">ID
                                        Member</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Nama</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Phone</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Email</th>
                                    <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">
                                        Tanggal Daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members->take(10) as $member)
                                    <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                        <td class="py-3 px-4 text-white text-sm">{{ $member->member_id }}</td>
                                        <td class="py-3 px-4 text-white text-sm">{{ $member->name }}</td>
                                        <td class="py-3 px-4 text-white text-sm">{{ $member->phone }}</td>
                                        <td class="py-3 px-4 text-white text-sm">{{ $member->email ?? '-' }}</td>
                                        <td class="py-3 px-4 text-white text-sm">
                                            {{ $member->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($members->count() > 10)
                        <p class="text-sm text-[#9a9a9a] mt-4">Menampilkan 10 dari {{ $members->count() }} members</p>
                    @endif
                @else
                    <p class="text-[#9a9a9a] text-sm">Belum ada member yang terdaftar.</p>
                @endif
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    @if (Auth::user()->role === 'admin')
        <script>
            // Realtime update untuk member baru
            // Simpan member yang sudah ditampilkan di localStorage agar tidak hilang setelah refresh
            function getStoredMemberIds() {
                const stored = localStorage.getItem('displayedMemberIds');
                return stored ? new Set(JSON.parse(stored)) : new Set();
            }

            function saveMemberIds(memberIds) {
                localStorage.setItem('displayedMemberIds', JSON.stringify(Array.from(memberIds)));
            }

            let displayedMemberIds = getStoredMemberIds();

            function formatWhatsAppMessage(name) {
                const message = `Halo, ${name}

Selamat datang di Musicmen Membership

Terima kasih sudah bergabung. Sebagai member, kamu berhak mendapatkan :

✅ Free pick & holder
✅ Free restring & cleaning standar lifetime
✅ Free garansi setup setiap pembelian instrument
✅ Reward menarik setiap 5x kunjungan ke offline store
✅ Mendapatkan info gear update dan ekslusif promo

Apabila ada pertanyaan atau butuh rekomendasi gitar, bass atau service instrument langsung chat saja men.

Thanks Men!`;
                return encodeURIComponent(message);
            }

            function sendWhatsApp(phone, name, memberId) {
                // Format phone number: remove all non-numeric characters
                const cleanPhone = phone.replace(/[^0-9]/g, '');
                // If starts with 0, replace with 62 (Indonesia country code)
                const formattedPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
                const message = formatWhatsAppMessage(name);
                const whatsappUrl = `https://wa.me/${formattedPhone}?text=${message}`;
                window.open(whatsappUrl, '_blank');

                // Hapus member dari tabel setelah WhatsApp dibuka
                setTimeout(() => {
                    const row = document.querySelector(`tr[data-member-id="${memberId}"]`);
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease-out';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            // Hapus dari localStorage juga
                            displayedMemberIds.delete(memberId);
                            saveMemberIds(displayedMemberIds);

                            // Jika tidak ada row lagi, tampilkan pesan
                            const tbody = document.getElementById('newMembersTableBody');
                            if (tbody.children.length === 0) {
                                tbody.innerHTML =
                                    '<tr><td colspan="6" class="py-4 px-4 text-center text-[#9a9a9a] text-sm">Belum ada member baru</td></tr>';
                            }
                        }, 300);
                    }
                }, 100);
            }

            // Load existing members from database on page load
            async function loadExistingMembers() {
                try {
                    const response = await fetch(
                        `{{ route('admin.members.new') }}?last_check=${encodeURIComponent(new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString())}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin'
                        });

                    if (!response.ok) return;

                    const data = await response.json();
                    const tbody = document.getElementById('newMembersTableBody');

                    if (data.members && data.members.length > 0) {
                        // Clear loading message
                        if (tbody.children.length === 1 && tbody.children[0].textContent.includes('Memuat')) {
                            tbody.innerHTML = '';
                        }

                        // Add all members that haven't been displayed yet
                        data.members.forEach(member => {
                            if (!displayedMemberIds.has(member.id)) {
                                displayedMemberIds.add(member.id);
                                addMemberRow(member, tbody);
                            } else {
                                // If already displayed, add it back to table
                                addMemberRow(member, tbody);
                            }
                        });

                        // Save to localStorage
                        saveMemberIds(displayedMemberIds);
                    }
                } catch (error) {
                    console.error('Error loading existing members:', error);
                }
            }

            function addMemberRow(member, tbody) {
                // Check if row already exists
                const existingRow = tbody.querySelector(`tr[data-member-id="${member.id}"]`);
                if (existingRow) return;

                const row = document.createElement('tr');
                row.className = 'border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors new-member-row';
                row.setAttribute('data-member-id', member.id);
                row.innerHTML = `
                <td class="py-3 px-4 text-white text-sm">${member.member_id}</td>
                <td class="py-3 px-4 text-white text-sm">${member.name}</td>
                <td class="py-3 px-4 text-white text-sm">${member.phone || '-'}</td>
                <td class="py-3 px-4 text-white text-sm">${member.email || '-'}</td>
                <td class="py-3 px-4 text-white text-sm">${member.created_at}</td>
                <td class="py-3 px-4">
                    ${member.phone ? `
                                            <button onclick="sendWhatsApp('${member.phone}', '${member.name.replace(/'/g, "\\'")}', ${member.id})" 
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs uppercase transition-all flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                                WhatsApp
                                            </button>
                                            ` : '<span class="text-[#6a6a6a] text-xs">No Phone</span>'}
                </td>
            `;
                tbody.insertBefore(row, tbody.firstChild);
            }

            async function fetchNewMembers() {
                try {
                    const response = await fetch(
                        `{{ route('admin.members.new') }}?last_check=${encodeURIComponent(lastCheckTime)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin'
                        });

                    if (!response.ok) return;

                    const data = await response.json();
                    const tbody = document.getElementById('newMembersTableBody');

                    if (data.members && data.members.length > 0) {
                        // Clear loading message
                        if (tbody.children.length === 1 && tbody.children[0].textContent.includes('Memuat')) {
                            tbody.innerHTML = '';
                        }

                        // Add new members to the top
                        data.members.forEach(member => {
                            if (!displayedMemberIds.has(member.id)) {
                                displayedMemberIds.add(member.id);
                                addMemberRow(member, tbody);
                                // Save to localStorage
                                saveMemberIds(displayedMemberIds);
                            }
                        });
                    } else if (tbody.children.length === 0 || (tbody.children.length === 1 && tbody.children[0].textContent
                            .includes('Memuat'))) {
                        tbody.innerHTML =
                            '<tr><td colspan="6" class="py-4 px-4 text-center text-[#9a9a9a] text-sm">Belum ada member baru</td></tr>';
                    }

                    // Update last check time
                    if (data.last_check) {
                        lastCheckTime = data.last_check;
                    }
                } catch (error) {
                    console.error('Error fetching new members:', error);
                }
            }

            // Polling member realtime dinonaktifkan untuk mencegah request API berulang.
            // Data member tetap menggunakan hasil render awal dari server.
        </script>
        <style>
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .new-member-row {
                background-color: rgba(34, 197, 94, 0.1);
            }
        </style>

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Monthly Income Chart
            const monthlyIncomeCtx = document.getElementById('monthlyIncomeChart');
            if (monthlyIncomeCtx) {
                const monthlyIncomeData = @json($monthlyIncome ?? ['months' => [], 'incomes' => []]);

                new Chart(monthlyIncomeCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyIncomeData.months,
                        datasets: [{
                            label: 'Pemasukan (Rp)',
                            data: monthlyIncomeData.incomes,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#9a9a9a'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Pemasukan: Rp ' + new Intl.NumberFormat('id-ID').format(context
                                            .parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#9a9a9a',
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                            maximumFractionDigits: 0
                                        }).format(value);
                                    }
                                },
                                grid: {
                                    color: '#3a3a3a'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#9a9a9a'
                                },
                                grid: {
                                    color: '#3a3a3a'
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endif

    @if (Auth::user()->role === 'staff' && $staff)
        <script>
            // Real-time update untuk jadwal hari ini dan tugas terbaru
            (function() {
                let lastScheduleUpdate = new Date(Date.now() - 60000).toISOString().slice(0, 19).replace('T', ' ');
                let lastAssignmentUpdate = new Date(Date.now() - 60000).toISOString().slice(0, 19).replace('T', ' ');
                const scheduleContent = document.getElementById('today-schedule-content');
                const assignmentsContent = document.getElementById('today-assignments-content');
                const latestTaskContent = document.getElementById('latest-task-content');
                const assignmentsMap = new Map();
                const latestTasksMap = new Map();

                // Initialize existing assignments
                @if ($todayAssignments)
                    @foreach ($todayAssignments as $assignment)
                        assignmentsMap.set({
                            {
                                $assignment - > id
                            }
                        }, true);
                        latestTasksMap.set({
                            {
                                $assignment - > id
                            }
                        }, true);
                    @endforeach
                @endif

                function getStatusBadge(status) {
                    if (status === 'pending') {
                        return '<span class="px-2 py-1 bg-yellow-900/30 border border-yellow-500/50 rounded text-yellow-400 text-xs uppercase">Pending</span>';
                    } else if (status === 'in_progress') {
                        return '<span class="px-2 py-1 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-xs uppercase">In Progress</span>';
                    } else {
                        return '<span class="px-2 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs uppercase">Completed</span>';
                    }
                }

                function getStatusBadgeSmall(status) {
                    if (status === 'pending') {
                        return '<span class="inline-block mt-1 px-2 py-0.5 bg-yellow-900/30 border border-yellow-500/50 rounded text-yellow-400 text-xs">Pending</span>';
                    } else if (status === 'in_progress') {
                        return '<span class="inline-block mt-1 px-2 py-0.5 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-xs">In Progress</span>';
                    } else {
                        return '<span class="inline-block mt-1 px-2 py-0.5 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs">Completed</span>';
                    }
                }

                function createLatestTaskItem(assignment) {
                    const div = document.createElement('div');
                    div.className = 'latest-task-item new-task-highlight';
                    div.setAttribute('data-assignment-id', assignment.id);
                    const title = assignment.title.length > 30 ? assignment.title.substring(0, 30) + '...' : assignment
                        .title;
                    const time = assignment.created_at.split(' ')[1] ? assignment.created_at.split(' ')[1].substring(0, 5) :
                        assignment.created_at;
                    div.innerHTML = `
                        <p class="text-sm text-white font-medium">${title}</p>
                        <p class="text-xs text-[#6a6a6a] mt-1">${time}</p>
                        ${getStatusBadgeSmall(assignment.status)}
                    `;
                    return div;
                }

                function updateLatestTasks() {
                    fetch(`{{ route('staff.assignments.api.new') }}?last_update=${encodeURIComponent(lastAssignmentUpdate)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.assignments && data.assignments.length > 0) {
                                // Filter only today's assignments
                                const today = new Date().toDateString();
                                const todayAssignments = data.assignments.filter(a => {
                                    if (!a.created_at_raw) return false;
                                    const assignmentDate = new Date(a.created_at_raw).toDateString();
                                    return assignmentDate === today;
                                });

                                // Sort by created_at desc and take only 3 latest
                                todayAssignments.sort((a, b) => {
                                    return new Date(b.created_at_raw) - new Date(a.created_at_raw);
                                });

                                const latest3 = todayAssignments.slice(0, 3);

                                if (latest3.length > 0 && latestTaskContent) {
                                    // Clear existing content if it shows "Belum ada tugas"
                                    if (latestTaskContent.children.length === 1 && latestTaskContent.children[0]
                                        .textContent.includes('Belum ada')) {
                                        latestTaskContent.innerHTML = '';
                                    }

                                    // Update or add tasks
                                    latest3.forEach((assignment, index) => {
                                        if (!latestTasksMap.has(assignment.id)) {
                                            latestTasksMap.set(assignment.id, true);
                                            const newItem = createLatestTaskItem(assignment);
                                            latestTaskContent.insertBefore(newItem, latestTaskContent
                                                .firstChild);

                                            // Keep only 3 items
                                            while (latestTaskContent.children.length > 3) {
                                                const lastChild = latestTaskContent.lastChild;
                                                if (lastChild && lastChild.getAttribute('data-assignment-id')) {
                                                    latestTasksMap.delete(parseInt(lastChild.getAttribute(
                                                        'data-assignment-id')));
                                                }
                                                latestTaskContent.removeChild(lastChild);
                                            }
                                        }
                                    });
                                }
                            } else if (latestTaskContent && latestTaskContent.children.length === 0) {
                                latestTaskContent.innerHTML =
                                    '<p class="text-sm text-[#6a6a6a]">Belum ada tugas hari ini</p>';
                            }
                            if (data.last_update) {
                                lastAssignmentUpdate = data.last_update;
                            }
                        })
                        .catch(error => {
                            console.error('Error updating latest tasks:', error);
                        });
                }

                function formatTime(timeString) {
                    if (!timeString) return '';
                    const date = new Date('1970-01-01T' + timeString + 'Z');
                    return date.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                }

                function updateTodaySchedule() {
                    const today = new Date();
                    const dayOfWeek = today.toLocaleDateString('en-US', {
                        weekday: 'long'
                    }).toLowerCase();
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay() + 1); // Monday
                    const weekStartStr = weekStart.toISOString().slice(0, 10);

                    fetch(`{{ route('staff.shifts.api.new') }}?week_start=${weekStartStr}&last_update=${encodeURIComponent(lastScheduleUpdate)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.schedules && data.schedules.length > 0) {
                                const todaySchedule = data.schedules.find(s => s.day === dayOfWeek);
                                if (todaySchedule && todaySchedule.shift_time) {
                                    const days = {
                                        'monday': 'Senin',
                                        'tuesday': 'Selasa',
                                        'wednesday': 'Rabu',
                                        'thursday': 'Kamis',
                                        'friday': 'Jumat',
                                        'saturday': 'Sabtu',
                                        'sunday': 'Minggu'
                                    };

                                    if (scheduleContent) {
                                        scheduleContent.innerHTML = `
                                        <p class="text-lg text-white">${days[todaySchedule.day] || todaySchedule.day_name}</p>
                                        <p class="text-sm text-blue-400 mt-2 uppercase">${todaySchedule.shift.charAt(0).toUpperCase() + todaySchedule.shift.slice(1)}</p>
                                        <p class="text-sm text-[#9a9a9a] mt-1">
                                            ${todaySchedule.shift_time.start} - ${todaySchedule.shift_time.end}
                                        </p>
                                    `;
                                    }
                                } else if (scheduleContent) {
                                    scheduleContent.innerHTML =
                                        '<p class="text-lg text-[#6a6a6a]">Tidak ada jadwal hari ini</p>';
                                }
                            }
                            if (data.last_update) {
                                lastScheduleUpdate = data.last_update;
                            }
                        })
                        .catch(error => {
                            console.error('Error updating schedule:', error);
                        });
                }

                function createAssignmentItem(assignment) {
                    const div = document.createElement('div');
                    div.className =
                        'bg-[#0f0f0f] border border-[#3a3a3a] rounded-lg p-4 assignment-item new-assignment-highlight';
                    div.setAttribute('data-assignment-id', assignment.id);
                    const description = assignment.description || '-';
                    const truncatedDesc = description.length > 100 ? description.substring(0, 100) + '...' : description;
                    div.innerHTML = `
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-white font-semibold mb-1">${assignment.title}</h3>
                                <p class="text-[#9a9a9a] text-sm mb-2">${truncatedDesc}</p>
                                <div class="flex items-center gap-4 text-xs text-[#6a6a6a]">
                                    <span>Oleh: ${assignment.creator_name}</span>
                                    <span>${assignment.created_at}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                ${getStatusBadge(assignment.status)}
                            </div>
                        </div>
                    `;
                    return div;
                }

                function updateTodayAssignments() {
                    fetch(`{{ route('staff.assignments.api.new') }}?last_update=${encodeURIComponent(lastAssignmentUpdate)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.assignments && data.assignments.length > 0) {
                                // Filter only today's assignments
                                const today = new Date().toDateString();
                                const todayAssignments = data.assignments.filter(a => {
                                    if (!a.created_at_raw) return false;
                                    const assignmentDate = new Date(a.created_at_raw).toDateString();
                                    return assignmentDate === today;
                                });

                                todayAssignments.forEach(assignment => {
                                    if (!assignmentsMap.has(assignment.id)) {
                                        assignmentsMap.set(assignment.id, true);
                                        const newItem = createAssignmentItem(assignment);
                                        if (assignmentsContent && assignmentsContent.children.length === 1 &&
                                            assignmentsContent.children[0].textContent.includes('Belum ada')) {
                                            assignmentsContent.innerHTML = '';
                                        }
                                        if (assignmentsContent) {
                                            assignmentsContent.insertBefore(newItem, assignmentsContent
                                                .firstChild);
                                        }
                                    }
                                });
                            }
                            if (data.last_update) {
                                lastAssignmentUpdate = data.last_update;
                            }
                        })
                        .catch(error => {
                            console.error('Error updating assignments:', error);
                        });
                }

                // Polling dashboard realtime dinonaktifkan untuk mencegah request API berulang.
                // Muat ulang dashboard untuk melihat tugas dan jadwal terbaru.
            })();
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const monthlyIncomeCtx = document.getElementById('monthlyIncomeChart');

                if (monthlyIncomeCtx) {
                    // Ambil data dari Laravel, berikan fallback jika null
                    const monthlyIncomeData = {
                        !!json_encode($monthlyIncome ?? ['months' => [], 'incomes' => []]) !!
                    };

                    // Jika data kosong, tampilkan placeholder agar chart tidak error
                    if (monthlyIncomeData.months.length === 0) {
                        monthlyIncomeData.months = ['No Data'];
                        monthlyIncomeData.incomes = [0];
                    }

                    new Chart(monthlyIncomeCtx, {
                        type: 'line',
                        data: {
                            labels: monthlyIncomeData.months,
                            datasets: [{
                                label: 'Pemasukan (Rp)',
                                data: monthlyIncomeData.incomes,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: 'rgb(34, 197, 94)',
                                pointBorderColor: '#fff',
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#9a9a9a',
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#1a1a1a',
                                    titleColor: '#fff',
                                    bodyColor: '#9a9a9a',
                                    borderColor: '#4a4a4a',
                                    borderWidth: 1,
                                    callbacks: {
                                        label: function(context) {
                                            return 'Pemasukan: Rp ' + new Intl.NumberFormat('id-ID').format(
                                                context.parsed.y);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#9a9a9a',
                                        callback: function(value) {
                                            if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(74, 74, 74, 0.2)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#9a9a9a',
                                        maxRotation: 45,
                                        minRotation: 45
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>

        <style>
            .new-assignment-highlight {
                animation: highlightNew 2s ease-out;
            }

            .new-task-highlight {
                animation: highlightNew 2s ease-out;
            }

            @keyframes highlightNew {
                0% {
                    background-color: rgba(234, 179, 8, 0.3);
                    transform: translateX(-10px);
                    opacity: 0;
                }

                50% {
                    background-color: rgba(234, 179, 8, 0.2);
                }

                100% {
                    background-color: transparent;
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            #latest-task-content {
                scrollbar-width: thin;
                scrollbar-color: #4a4a4a #1a1a1a;
            }

            #latest-task-content::-webkit-scrollbar {
                width: 4px;
            }

            #latest-task-content::-webkit-scrollbar-track {
                background: #1a1a1a;
            }

            #latest-task-content::-webkit-scrollbar-thumb {
                background: #4a4a4a;
                border-radius: 2px;
            }
        </style>
    @endif
</body>

</html>
