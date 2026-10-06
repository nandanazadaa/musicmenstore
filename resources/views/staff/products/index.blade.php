<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Instrumen - Musicmen Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
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

        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 w-full">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl text-white mb-2 tracking-tight uppercase">Instrument
                            Registry</h1>
                        <div class="h-px bg-gradient-to-r from-yellow-600 via-[#333] to-transparent w-full max-w-2xl">
                        </div>
                    </div>
                    <a href="{{ route('staff.products.create') }}"
                        class="bg-yellow-600 text-black px-8 py-3 rounded-2xl hover:bg-yellow-500 transition-all font-bold text-xs uppercase tracking-widest">+
                        Add Unit</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-[#1a1a1a] border border-[#222] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-white/5 text-6xl font-black italic">UNIT</div>
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-[0.2em] mb-1">Total Unit Registered
                    </p>
                    <h3 class="text-3xl font-black text-white">{{ number_format($stats['total_unit']) }} <span
                            class="text-sm text-gray-600 italic">Units</span></h3>
                </div>

                <div
                    class="bg-[#1a1a1a] border border-[#222] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-white/5 text-6xl font-black italic">CASH</div>
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-[0.2em] mb-1">Total Cash Deposit
                    </p>
                    <h3 class="text-3xl font-black text-red-500">Rp
                        {{ number_format($stats['total_deposit'], 0, ',', '.') }}</h3>
                </div>

                <div
                    class="bg-[#1a1a1a] border border-[#222] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-white/5 text-6xl font-black italic">FEE</div>
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-[0.2em] mb-1">Total Commission Fee
                    </p>
                    <h3 class="text-3xl font-black text-yellow-600">Rp
                        {{ number_format($stats['total_fee'], 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="mb-6 bg-[#1a1a1a] p-6 rounded-2xl border border-[#222]">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-widest">Start
                            Date</label>
                        <input type="date" name="date_start" value="{{ $dateStart }}"
                            class="bg-[#0a0a0a] border border-[#333] text-white rounded-xl px-4 py-2 text-sm outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-widest">End
                            Date</label>
                        <input type="date" name="date_end" value="{{ $dateEnd }}"
                            class="bg-[#0a0a0a] border border-[#333] text-white rounded-xl px-4 py-2 text-sm outline-none focus:border-yellow-600">
                    </div>
                    <button type="submit"
                        class="bg-yellow-600 text-black font-bold px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-tighter">Filter
                        Data</button>
                    <a href="{{ url()->current() }}"
                        class="bg-[#222] text-white font-bold px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-tighter">Reset</a>
                </form>
            </div>



            <div class="bg-[#1a1a1a] border border-[#222] rounded-[2rem] p-8 w-full shadow-2xl">
                <form method="GET" action="{{ route('staff.products.index') }}" class="mb-10">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-5 py-3 text-sm text-white focus:border-yellow-600 outline-none transition-all"
                                placeholder="Cari Nama atau Serial Number...">
                        </div>
                        <div>
                            <select name="type" onchange="this.form.submit()"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-5 py-3 text-sm text-white outline-none">
                                <option value="">Kategori Produk</option>
                                @foreach (['electric', 'acoustic', 'effect', 'amplifier', 'bass'] as $t)
                                    <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                        {{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full bg-white text-black font-bold py-3 rounded-xl transition-all uppercase text-[10px] tracking-widest hover:bg-yellow-500">Apply
                            Search</button>
                    </div>
                </form>



                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="border-b border-[#222] text-[#444] uppercase text-[9px] font-bold tracking-[0.3em]">
                                <th class="py-6 px-4">Instrument Details</th>
                                <th class="py-6 px-4">Serial Number</th>
                                <th class="py-6 px-4">Origin / Year</th>
                                <th class="py-6 px-4">PIC & Fee Log</th>
                                <th class="py-6 px-4">Harga Pembelian</th>
                                <th class="py-6 px-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#151515]">
                            @forelse($products as $product)
                                <tr class="hover:bg-[#151515]/50 transition-all group">
                                    <td class="py-6 px-4">
                                        <div
                                            class="text-white font-bold text-sm uppercase tracking-tight leading-tight">
                                            {{ $product->nama_barang }}</div>
                                        <span
                                            class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-900/20 text-blue-400 border border-blue-800/30 uppercase mt-1 inline-block">{{ $product->type }}</span>
                                    </td>
                                    <td class="py-6 px-4">
                                        <div class="text-white font-mono text-xs uppercase tracking-widest opacity-80">
                                            {{ $product->nomor_seri ?? '—' }}</div>
                                    </td>
                                    <td class="py-6 px-4">
                                        <div class="text-white text-xs font-semibold uppercase">
                                            {{ $product->tempat_pembuatan ?? '—' }}</div>
                                        <div class="text-[10px] text-[#444] font-bold mt-1 tracking-widest italic">
                                            {{ $product->tahun_pembuatan ?? '?' }}</div>
                                    </td>
                                    <td class="py-6 px-4">
                                        <div class="space-y-1">
                                            @forelse($product->additionalPics as $ap)
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="text-[9px] text-white/60 font-bold uppercase tracking-tighter">
                                                        {{ $ap->pic_name }}</div>
                                                    <div class="h-px w-2 bg-[#333]"></div>
                                                    <div class="text-[9px] text-yellow-600 font-mono font-bold">Rp
                                                        {{ number_format($ap->fee_amount, 0, ',', '.') }}</div>
                                                </div>
                                            @empty
                                                <span
                                                    class="text-[#333] text-[9px] uppercase font-bold tracking-widest italic">—
                                                    No Record —</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="py-6 px-4">
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] font-bold text-red-400">Rp
                                                {{ number_format($product->harga_pembelian, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6 px-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('staff.products.show', $product->id) }}"
                                                class="p-2.5 bg-[#111] border border-[#222] rounded-xl text-blue-400 hover:text-white hover:border-blue-500 transition-all"
                                                title="View Detail"><svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg></a>

                                            @php
                                                $staff = \App\Models\Staff::where('user_id', Auth::id())->first();
                                                $canEdit =
                                                    ($staff &&
                                                        $product->additionalPics->contains('pic_name', $staff->nama)) ||
                                                    ($staff && $product->pic === $staff->nama);
                                            @endphp

                                            @if ($canEdit)
                                                <a href="{{ route('staff.products.edit', $product->id) }}"
                                                    class="p-2.5 bg-[#111] border border-[#222] rounded-xl text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all"
                                                    title="Edit"><svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg></a>
                                            @else
                                                <div
                                                    class="p-2.5 bg-[#0a0a0a] border border-[#1a1a1a] text-[#222] rounded-xl cursor-not-allowed">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <rect x="3" y="11" width="18" height="11"
                                                            rx="2" ry="2"></rect>
                                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="py-20 text-center text-[#222] uppercase tracking-[0.5em] text-[10px] font-bold">
                                        Instrument Records Empty</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">{{ $products->links() }}</div>
            </div>
        </div>
    </main>
    @include('components.admin-footer')
</body>

</html>
