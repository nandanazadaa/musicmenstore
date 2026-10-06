<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sales Instrumen - Staff</title>
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
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Detail Sales Instrumen</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <div class="flex gap-3">
                        @php
                        $user = Auth::user();
                        $staff = \App\Models\Staff::where('user_id', $user->id)->first();
                        $canEdit = $staff && $sale->sales === $staff->nama;
                        @endphp
                        @if($canEdit)
                        <a href="{{ route('staff.sales.edit', $sale->id) }}"
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Edit
                        </a>
                        @endif
                        <a href="{{ route('staff.sales.index') }}"
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5M12 19l-7-7 7-7"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
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

            <!-- Sales Details -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8 w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Tanggal</label>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white">
                                {{ \Carbon\Carbon::parse($sale->tanggal)->format('d M Y') }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Barang</label>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white">
                                {{ $sale->nama_barang }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Type</label>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3">
                                @if($sale->type)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                        @if($sale->type == 'electric') bg-blue-900/30 border border-blue-600 text-blue-300
                                        @elseif($sale->type == 'acoustic') bg-yellow-900/30 border border-yellow-600 text-yellow-300
                                        @elseif($sale->type == 'bass') bg-green-900/30 border border-green-600 text-green-300
                                        @elseif($sale->type == 'effect') bg-purple-900/30 border border-purple-600 text-purple-300
                                        @elseif($sale->type == 'amplifier') bg-red-900/30 border border-red-600 text-red-300
                                        @endif">
                                    {{ ucfirst($sale->type) }}
                                </span>
                                @else
                                <span class="text-[#6a6a6a]">-</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div class="md:col-span-2 mt-4">
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Rincian Sales & Fee</label>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-[#1a1a1a]">
                                        <tr>
                                            <th class="text-left p-3 text-[#6a6a6a]">Nama Sales</th>
                                            <th class="text-right p-3 text-[#6a6a6a]">Fee</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $salesDetail = is_array($sale->sales) ? $sale->sales : json_decode($sale->sales, true);
                                        $feesDetail = is_array($sale->jumlah_fee) ? $sale->jumlah_fee : json_decode($sale->jumlah_fee, true);
                                        @endphp
                                        @foreach($salesDetail as $index => $nama)
                                        <tr class="border-t border-[#4a4a4a]">
                                            <td class="p-3">{{ $nama }}</td>
                                            <td class="p-3 text-right text-green-400">Rp {{ number_format($feesDetail[$index], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-[#1a1a1a] font-bold">
                                        <tr>
                                            <td class="p-3">TOTAL</td>
                                            <td class="p-3 text-right text-green-400">Rp {{ number_format(array_sum($feesDetail), 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Kondisi</label>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3">
                                @if($sale->kondisi == 'great')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-900/30 border border-green-600 text-green-300">Great</span>
                                @elseif($sale->kondisi == 'good')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-900/30 border border-yellow-600 text-yellow-300">Good</span>
                                @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-800/60 border border-gray-500 text-gray-200">-</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Keterangan</label>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white min-h-[100px]">
                                {{ $sale->keterangan ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>

</html>