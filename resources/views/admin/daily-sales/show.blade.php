<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Form Penjualan Harian Detail - Musicmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body { overflow-x: hidden; max-width: 100vw; }
        * { box-sizing: border-box; }
        .serif-font { font-family: 'DM Serif Display', serif; }
    </style>
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Detail Penjualan Harian</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('staff.daily-sales.index') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] transition-all uppercase tracking-wider">
                        Back
                    </a>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8 space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-[#0f0f0f] p-4 rounded border border-[#333]">
                        <p class="text-xs text-[#9a9a9a] uppercase tracking-widest mb-1">Tanggal</p>
                        <p class="text-white text-lg font-medium">{{ \Carbon\Carbon::parse($dailySale->sale_date)->format('d M Y') }}</p>
                    </div>
                    <div class="bg-[#0f0f0f] p-4 rounded border border-[#333]">
                        <p class="text-xs text-[#9a9a9a] uppercase tracking-widest mb-1">Staff</p>
                        <p class="text-white text-lg font-medium">{{ $dailySale->staff->nama }}</p>
                    </div>
                    <div class="bg-[#0f0f0f] p-4 rounded border border-[#333]">
                        <p class="text-xs text-[#9a9a9a] uppercase tracking-widest mb-1">Shift</p>
                        <p class="text-white text-lg font-medium uppercase">{{ $dailySale->shift ?? '-' }}</p>
                    </div>
                </div>

                <div class="border-t border-[#333] pt-6">
                    <h2 class="text-xl text-white mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                        Rincian Penjualan
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-[#0f0f0f] rounded border-l-4 border-gray-500">
                                <span class="text-[#9a9a9a]">Total Offline</span>
                                <span class="text-white font-mono">Rp {{ number_format($dailySale->total_offline_sales, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-[#0f0f0f] rounded border-l-4 border-blue-500">
                                <span class="text-[#9a9a9a]">Total Online (Shopee + Tokped)</span>
                                <span class="text-white font-mono font-bold">Rp {{ number_format($dailySale->total_online_sales, 0, ',', '.') }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pl-4">
                                <div class="bg-[#0f0f0f] p-2 rounded border border-[#222] flex flex-col">
                                    <span class="text-[10px] text-[#6a6a6a] uppercase">Shopee</span>
                                    <span class="text-sm text-[#9a9a9a]">Rp {{ number_format($dailySale->shopee_sales, 0, ',', '.') }}</span>
                                </div>
                                <div class="bg-[#0f0f0f] p-2 rounded border border-[#222] flex flex-col">
                                    <span class="text-[10px] text-[#6a6a6a] uppercase">Tokopedia</span>
                                    <span class="text-sm text-[#9a9a9a]">Rp {{ number_format($dailySale->tokopedia_sales, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 bg-gradient-to-r from-green-900/40 to-[#0f0f0f] p-6 rounded-lg border border-green-500/50 mt-4">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div>
                                    <h3 class="text-xs text-green-400 font-bold uppercase tracking-[0.2em] mb-1">Grand Total Penjualan</h3>
                                    <p class="text-[10px] text-[#6a6a6a] italic">*Rumus: Total Offline + Total Online (Sudah include Shopee & Tokped)</p>
                                </div>
                                <div class="text-3xl font-bold text-green-400 font-mono">
                                    @php
                                        // Rumus Akurat: Offline + Online
                                        $grandTotal = $dailySale->total_offline_sales + $dailySale->total_online_sales;
                                    @endphp
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-[#333] pt-6">
                    <h2 class="text-xl text-white mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-yellow-600 rounded-full"></span>
                        Pemasukan & Setoran
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-[#0f0f0f] p-4 rounded border border-[#222] text-center">
                            <span class="text-[10px] text-[#6a6a6a] uppercase block mb-1">Cash</span>
                            <span class="text-white">Rp {{ number_format($dailySale->cash, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-[#0f0f0f] p-4 rounded border border-[#222] text-center">
                            <span class="text-[10px] text-[#6a6a6a] uppercase block mb-1">QRIS</span>
                            <span class="text-white">Rp {{ number_format($dailySale->qris, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-[#0f0f0f] p-4 rounded border border-[#222] text-center">
                            <span class="text-[10px] text-[#6a6a6a] uppercase block mb-1">Transfer</span>
                            <span class="text-white">Rp {{ number_format($dailySale->transfer, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-[#0f0f0f] p-4 rounded border border-[#333] flex justify-between items-center">
                        <p class="text-sm text-[#9a9a9a] uppercase font-bold tracking-widest">Total Cash Deposit (Ke Owner)</p>
                        <p class="text-white text-2xl font-bold font-mono text-blue-400">Rp {{ number_format($dailySale->total_cash_deposit, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($dailySale->notes)
                <div class="bg-[#0f0f0f] p-4 rounded border border-yellow-900/30">
                    <p class="text-xs text-yellow-500 uppercase font-bold mb-2">Keterangan Tambahan:</p>
                    <p class="text-[#d4d4d4] text-sm leading-relaxed">{{ $dailySale->notes }}</p>
                </div>
                @endif

                <div class="p-4 bg-[#222] rounded text-center italic text-[#666] text-[10px]">
                    "{{ $dailySale->statement }}"
                </div>

            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>