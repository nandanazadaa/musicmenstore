<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Form Penjualan Harian - Musicmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Form Penjualan Harian
                        </h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                    </div>
                    <a href="{{ route('staff.daily-sales.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                        Create Form
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <h2 class="text-sm font-bold text-yellow-600 uppercase tracking-[0.2em] italic">
                    <i class="fa-solid fa-calendar-check mr-2"></i>
                    Statistik Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-gradient-to-br from-yellow-600 to-yellow-800 p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-white/10 text-6xl font-black italic">POINTS</div>
                    <p class="text-black/60 text-[10px] uppercase font-bold tracking-widest mb-1">Poin Kedisiplinan</p>
                    <h3 class="text-4xl font-black text-white">{{ number_format($stats['total_poin']) }}</h3>
                    <p class="text-[9px] text-white/80 mt-1 italic">+1 Tepat Waktu | -1 Rapelan</p>
                </div>

                <div class="bg-[#1a1a1a] border border-[#222] p-6 rounded-[2rem] shadow-xl relative overflow-hidden">
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mb-1">Total Penjualan Saya
                    </p>
                    <h3 class="text-2xl font-black text-white">Rp
                        {{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
                </div>

                <div
                    class="bg-[#1a1a1a] border border-[#222] p-6 rounded-[2rem] shadow-xl relative overflow-hidden text-green-500">
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mb-1">Total Deposit Saya</p>
                    <h3 class="text-2xl font-black">Rp {{ number_format($stats['total_deposit'], 0, ',', '.') }}</h3>
                </div>

                <div class="bg-[#1a1a1a] border border-[#222] p-6 rounded-[2rem] shadow-xl relative overflow-hidden">
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mb-1">Jumlah Laporan</p>
                    <h3 class="text-2xl font-black text-white">{{ $stats['total_laporan'] }} <span
                            class="text-xs text-gray-600">Form</span></h3>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#333] rounded-[2rem] p-6 mb-8 shadow-2xl">
                <form method="GET" action="{{ route('staff.daily-sales.index') }}"
                    class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                            class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-2.5 text-sm text-white focus:border-yellow-600 outline-none">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                            class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-2.5 text-sm text-white focus:border-yellow-600 outline-none">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-yellow-600 hover:bg-yellow-500 text-black font-black px-8 py-2.5 rounded-xl text-[10px] uppercase tracking-widest transition-all">
                            Filter
                        </button>
                        <a href="{{ route('staff.daily-sales.index') }}"
                            class="bg-[#222] hover:bg-[#333] text-white font-bold px-6 py-2.5 rounded-xl text-[10px] uppercase border border-[#444]">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Daily Sales List -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Date
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Shift
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Total
                                    Sales</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Cash
                                    Deposit</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailySales as $sale)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4 text-white text-sm">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ ucfirst($sale->shift ?? '-') }}</td>
                                    <td class="py-3 px-4 text-white text-sm">
                                        @php
                                            // Rumus Benar: Offline + Online
                                            $grandTotal = $sale->total_offline_sales + $sale->total_online_sales;
                                        @endphp
                                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">Rp
                                        {{ number_format($sale->total_cash_deposit, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('staff.daily-sales.show', $sale->id) }}"
                                            class="text-blue-400 hover:text-blue-300 transition-colors" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-[#9a9a9a]">No daily sales forms
                                        found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($dailySales->hasPages())
                    <div class="mt-6 flex items-center justify-center">
                        {{ $dailySales->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        // Show SweetAlert for session messages
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('
                                                                            success ') }}',
                icon: 'success',
                confirmButtonColor: '#10b981',
                background: '#1a1a1a',
                color: '#ffffff',
                customClass: {
                    popup: 'swal2-dark',
                    title: 'swal2-title-dark',
                    content: 'swal2-content-dark',
                    confirmButton: 'swal2-confirm-dark'
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('
                                                                            error ') }}',
                icon: 'error',
                confirmButtonColor: '#ef4444',
                background: '#1a1a1a',
                color: '#ffffff',
                customClass: {
                    popup: 'swal2-dark',
                    title: 'swal2-title-dark',
                    content: 'swal2-content-dark',
                    confirmButton: 'swal2-confirm-dark'
                }
            });
        @endif
    </script>
</body>

</html>
