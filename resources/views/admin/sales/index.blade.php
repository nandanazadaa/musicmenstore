<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Sales Instrumen - Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        * {
            box-sizing: border-box;
        }

        .serif-font {
            font-family: 'DM Serif Display', serif;
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Input Sales Instrumen</h1>
                    <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.sales.export') }}"
                        class="bg-transparent border border-green-600 text-green-400 px-6 py-3 rounded-lg hover:bg-green-900/20 hover:border-green-500 transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export
                    </a>
                    <a href="{{ route('admin.sales.create') }}"
                        class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider whitespace-nowrap">
                        + Tambah Data
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-[#1a1a1a] border border-[#333] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-white/5 text-6xl font-black italic uppercase">Items</div>
                    <p class="text-[#666] text-[10px] uppercase font-bold tracking-widest mb-1">Total Instrumen Terjual
                    </p>
                    <h3 class="text-3xl font-black text-white">{{ number_format($stats['total_unit']) }} <span
                            class="text-sm text-[#444] italic">Units</span></h3>
                    <p class="text-[9px] text-yellow-600 mt-1 italic">Akumulasi seluruh tim</p>
                </div>

                <div
                    class="bg-[#1a1a1a] border border-[#333] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-green-500/5 text-6xl font-black italic uppercase">Fees
                    </div>
                    <p class="text-[#666] text-[10px] uppercase font-bold tracking-widest mb-1">Total Pengeluaran Komisi
                    </p>
                    <h3 class="text-3xl font-black text-green-500">Rp
                        {{ number_format($stats['total_fee'], 0, ',', '.') }}</h3>
                    <p class="text-[9px] text-[#444] mt-1 italic">Bulan berjalan</p>
                </div>

                <div
                    class="bg-[#1a1a1a] border border-[#333] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-blue-500/5 text-6xl font-black italic uppercase">Date
                    </div>
                    <p class="text-[#666] text-[10px] uppercase font-bold tracking-widest mb-1">Periode Laporan</p>
                    <h3 class="text-lg font-black text-white mt-2">
                        {{ \Carbon\Carbon::parse($dateFrom)->format('d M') }} -
                        {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                    </h3>
                    <p class="text-[9px] text-blue-400 mt-1 italic">Otomatis reset ganti bulan</p>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-6 mb-8">
                <form method="GET" action="{{ route('admin.sales.index') }}"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] uppercase font-bold text-[#666] mb-2">Search Items</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari unit..."
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-[#666] mb-2">Start Date</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-[#666] mb-2">End Date</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-xs uppercase transition-all">Filter</button>
                        <a href="{{ route('admin.sales.index') }}"
                            class="flex-1 bg-[#222] text-white py-2 rounded-lg text-xs text-center border border-[#444] uppercase flex items-center justify-center">Reset</a>
                    </div>
                </form>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <form method="GET" action="{{ route('admin.sales.index') }}" class="mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="lg:col-span-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                            placeholder="Cari barang atau keterangan...">

                        <select name="type" onchange="this.form.submit()"
                            class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a]">
                            <option value="">Semua Tipe</option>
                            <option value="electric" {{ request('type') == 'electric' ? 'selected' : '' }}>Electric
                            </option>
                            <option value="acoustic" {{ request('type') == 'acoustic' ? 'selected' : '' }}>Acoustic
                            </option>
                            <option value="bass" {{ request('type') == 'bass' ? 'selected' : '' }}>Bass</option>
                            <option value="effect" {{ request('type') == 'effect' ? 'selected' : '' }}>Effect</option>
                            <option value="amplifier" {{ request('type') == 'amplifier' ? 'selected' : '' }}>Amplifier
                            </option>
                        </select>

                        <div class="flex gap-2 lg:col-span-2">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="flex-1 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-3 py-2 text-white text-sm">
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="flex-1 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-3 py-2 text-white text-sm">
                            <button type="submit"
                                class="bg-[#4a4a4a] hover:bg-[#6a6a6a] px-4 rounded-lg transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse min-w-[800px]">
                        <thead>
                            <tr class="border-b border-[#4a4a4a] text-[#9a9a9a] text-xs uppercase tracking-widest">
                                <th class="text-left py-4 px-4">Tanggal</th>
                                <th class="text-left py-4 px-4">Nama Barang</th>
                                <th class="text-left py-4 px-4">Rincian Sales & Fee</th>
                                <th class="text-left py-4 px-4">Kondisi</th>
                                <th class="text-center py-4 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4a4a4a]/30">
                            @forelse($sales as $sale)
                                @php
                                    $salesList = $sale->sales_list;
                                    $feesList = $sale->fees_list;
                                @endphp

                                <tr class="hover:bg-[#2a2a2a]/30 transition-colors group">
                                    <td class="py-4 px-4 text-sm text-[#9a9a9a]">
                                        {{ \Carbon\Carbon::parse($sale->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="text-white font-medium mb-1">{{ $sale->nama_barang }}</div>
                                        <span
                                            class="text-[10px] px-2 py-0.5 rounded-full border border-[#4a4a4a] text-[#6a6a6a] uppercase">
                                            {{ $sale->type }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="space-y-1.5 w-fit">
                                            @foreach ($salesList as $idx => $namaPIC)
                                                <div
                                                    class="flex items-center justify-between gap-3 bg-[#0f0f0f] border border-[#4a4a4a]/50 px-2 py-0.5 rounded shadow-sm max-w-[180px] min-w-[140px]">
                                                    <span
                                                        class="text-[10px] text-[#9a9a9a] font-medium truncate uppercase tracking-tighter">
                                                        {{ trim($namaPIC) }}
                                                    </span>
                                                    <span
                                                        class="text-[10px] text-green-400 font-mono whitespace-nowrap">
                                                        Rp{{ number_format($feesList[$idx] ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-sm uppercase text-[#9a9a9a]">
                                        {{ $sale->kondisi ?? '-' }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex justify-center items-center gap-4">
                                            <a href="{{ route('admin.sales.show', $sale->id) }}"
                                                class="text-[#6a6a6a] hover:text-white transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('admin.sales.edit', $sale->id) }}"
                                                class="text-[#6a6a6a] hover:text-yellow-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            @if (Auth::user()->role === 'admin')
                                                <form method="POST"
                                                    action="{{ route('admin.sales.destroy', $sale->id) }}"
                                                    class="inline" onsubmit="return confirmDelete(event)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-400 hover:text-red-300 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2V6">
                                                            </path>
                                                            <path d="M8 6V4c0-1.1.9-2 2-2h4c1.1 0 2 .9 2 2v2"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-[#4a4a4a] italic text-sm">
                                        Belum ada data sales instrumen.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($sales->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6a6a6a',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil dihapus',
                                icon: 'success',
                                confirmButtonColor: '#4a4a4a',
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus data',
                                icon: 'error',
                                confirmButtonColor: '#4a4a4a'
                            });
                        });
                }
            });

            return false;
        }
    </script>
</body>

</html>
