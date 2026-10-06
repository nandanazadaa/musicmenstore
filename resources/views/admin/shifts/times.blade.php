<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Setting Jam Shift - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Setting Jam Shift</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('admin.shifts.index') }}" 
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

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Shift Times Form -->
            <form method="POST" action="{{ route('admin.shifts.times.update') }}">
                @csrf
                @method('PUT')

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 mb-6">
                    <h2 class="text-xl font-semibold mb-6">Atur Jam Shift</h2>

                    <!-- Shift Pagi -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-yellow-400">Shift Pagi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2">Jam Mulai</label>
                                <input type="time" 
                                       name="pagi_start" 
                                       value="{{ isset($shiftTimes['pagi']) ? \Carbon\Carbon::parse($shiftTimes['pagi']->start_time)->format('H:i') : '09:00' }}" 
                                       required
                                       class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                            </div>
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2">Jam Selesai</label>
                                <input type="time" 
                                       name="pagi_end" 
                                       value="{{ isset($shiftTimes['pagi']) ? \Carbon\Carbon::parse($shiftTimes['pagi']->end_time)->format('H:i') : '17:00' }}" 
                                       required
                                       class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Shift Siang -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-blue-400">Shift Siang</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2">Jam Mulai</label>
                                <input type="time" 
                                       name="siang_start" 
                                       value="{{ isset($shiftTimes['siang']) ? \Carbon\Carbon::parse($shiftTimes['siang']->start_time)->format('H:i') : '13:00' }}" 
                                       required
                                       class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                            </div>
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2">Jam Selesai</label>
                                <input type="time" 
                                       name="siang_end" 
                                       value="{{ isset($shiftTimes['siang']) ? \Carbon\Carbon::parse($shiftTimes['siang']->end_time)->format('H:i') : '21:00' }}" 
                                       required
                                       class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg transition-all uppercase tracking-wider font-semibold">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.shifts.index') }}" 
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
