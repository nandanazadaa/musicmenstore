<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sales Instrumen - Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .serif-font { font-family: 'DM Serif Display', serif; }
    </style>
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-4xl">
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="serif-font text-3xl sm:text-4xl text-white mb-2">Detail Sales</h1>
                    <p class="text-[#6a6a6a] uppercase tracking-widest text-xs">ID Transaksi: #{{ $sale->id }}</p>
                    <div class="h-px bg-gradient-to-r from-[#4a4a4a] to-transparent w-full mt-4"></div>
                </div>
                <a href="{{ route('admin.sales.index') }}" class="text-[#9a9a9a] hover:text-white transition-all flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg overflow-hidden">
                <div class="p-6 border-b border-[#4a4a4a] bg-[#2a2a2a]/30">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div>
                            <span class="text-[10px] text-blue-400 border border-blue-400/50 px-2 py-0.5 rounded-full uppercase mb-2 inline-block">
                                {{ $sale->type }}
                            </span>
                            <h2 class="text-2xl font-bold">{{ $sale->nama_barang }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-[#9a9a9a] text-xs uppercase tracking-tighter">Tanggal Penjualan</p>
                            <p class="text-lg">{{ \Carbon\Carbon::parse($sale->tanggal)->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-8">
                    <div>
                        <h3 class="text-[#6a6a6a] text-xs uppercase tracking-[0.2em] mb-4">Rincian Sales & Komisi</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @php
                                $salesList = $sale->sales_list;
                                $feesList = $sale->fees_list;
                            @endphp
                            @foreach($salesList as $idx => $namaPIC)
                            <div class="flex justify-between items-center p-4 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg">
                                <span class="text-sm font-medium">{{ trim($namaPIC) }}</span>
                                <span class="text-green-400 font-mono">Rp{{ number_format($feesList[$idx] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-[#6a6a6a] text-xs uppercase tracking-[0.2em] mb-2">Kondisi Barang</h3>
                            <div class="inline-block px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded text-sm uppercase">
                                {{ $sale->kondisi ?? 'Tidak Ada Data' }}
                            </div>
                        </div>

                        <div>
                            <h3 class="text-[#6a6a6a] text-xs uppercase tracking-[0.2em] mb-2">Keterangan Tambahan</h3>
                            <p class="text-sm text-[#9a9a9a] leading-relaxed italic">
                                "{{ $sale->keterangan ?? 'Tidak ada keterangan.' }}"
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-[#0f0f0f] border-t border-[#4a4a4a] flex justify-end gap-4">
                    <a href="{{ route('admin.sales.edit', $sale->id) }}" 
                       class="px-6 py-2 bg-yellow-600/10 border border-yellow-600/50 text-yellow-500 rounded hover:bg-yellow-600 hover:text-white transition-all text-sm uppercase">
                        Edit Data
                    </a>
                </div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>