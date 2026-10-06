<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Instrumen - Musicmen Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @php
        use Illuminate\Support\Str;
    @endphp
    <style>
        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        * {
            box-sizing: border-box;
        }

        .new-product-highlight {
            animation: highlightNew 2s ease-out;
        }

        @keyframes highlightNew {
            0% {
                background-color: rgba(234, 179, 8, 0.3);
                transform: translateX(-10px);
                opacity: 0;
            }

            100% {
                background-color: transparent;
                transform: translateX(0);
                opacity: 1;
            }
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
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
    <script>
        try {
            localStorage.removeItem('musicmen.admin.products.create.draft');
        } catch (error) {
            // Abaikan jika browser memblokir localStorage.
        }
    </script>
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 w-full">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2 tracking-tight">Manage
                            Instrument</h1>
                        <div class="h-px bg-gradient-to-r from-yellow-600 via-[#333] to-transparent w-full max-w-2xl">
                        </div>
                    </div>


                    <div class="flex gap-3">
                        <a href="javascript:void(0)" onclick="exportFilteredData()"
                            class="bg-transparent border border-green-600 text-green-400 px-6 py-3 rounded-lg hover:bg-green-900/20 transition-all uppercase tracking-wider text-xs font-bold">Export
                            Excel</a>
                        <a href="{{ route('admin.products.create') }}"
                            class="bg-yellow-600 text-black px-6 py-3 rounded-lg hover:bg-yellow-500 transition-all uppercase tracking-wider font-bold text-xs">+
                            Add Product</a>
                    </div>
                </div>
            </div>

            <div class="mb-6 bg-[#1a1a1a] p-6 rounded-2xl border border-[#222]">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-widest">Start
                            Date</label>
                        <input type="date" name="date_start" value="{{ request('date_start') }}"
                            class="bg-[#0a0a0a] border border-[#333] text-white rounded-xl px-4 py-2 text-sm outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2 tracking-widest">End
                            Date</label>
                        <input type="date" name="date_end" value="{{ request('date_end') }}"
                            class="bg-[#0a0a0a] border border-[#333] text-white rounded-xl px-4 py-2 text-sm outline-none focus:border-yellow-600">
                    </div>
                    <button type="submit"
                        class="bg-yellow-600 text-black font-bold px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-tighter">Filter
                        Data</button>
                    <a href="{{ url()->current() }}"
                        class="bg-[#222] text-white font-bold px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-tighter">Reset</a>
                </form>
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

            <div class="bg-[#1a1a1a] border border-[#333] rounded-2xl p-6 w-full shadow-2xl">
                <form method="GET" action="{{ route('admin.products.index') }}" class="mb-8 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-2.5 text-sm text-white focus:border-yellow-600 outline-none"
                                placeholder="Cari Nama Unit atau SN...">
                        </div>
                        <div>
                            <select name="type" onchange="this.form.submit()"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-2.5 text-sm text-white outline-none">
                                <option value="">Semua Kategori</option>
                                @foreach (['electric', 'acoustic', 'effect', 'amplifier', 'bass'] as $t)
                                    <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                        {{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full bg-[#222] border border-[#444] hover:bg-[#333] text-white font-bold py-2 rounded-xl transition-all uppercase text-[10px] tracking-widest">Apply
                            Filter</button>
                    </div>
                </form>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="border-b border-[#222] text-[#555] uppercase text-[10px] font-bold tracking-[0.2em]">
                                <th class="py-5 px-4">Info Unit</th>
                                <th class="py-5 px-4">Serial Number</th>
                                <th class="py-5 px-4">Origin / Year</th>
                                <th class="py-5 px-4">PIC & Commission</th>
                                <th class="py-6 px-4">Harga Pembelian</th>
                                <th class="py-5 px-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="products-table-body">
                            @forelse($products as $product)
                                <tr class="border-b border-[#1d1d1d] hover:bg-[#1a1a1a]/50 transition-all group">
                                    <td class="py-5 px-4">
                                        <div class="text-white font-bold text-sm uppercase tracking-tight">
                                            {{ $product->nama_barang }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-[9px] bg-blue-900/20 text-blue-400 px-1.5 py-0.5 rounded font-bold uppercase">{{ $product->type }}</span>
                                            <span
                                                class="text-[9px] text-[#444] font-bold uppercase tracking-tighter">{{ $product->tanggal ? $product->tanggal->format('d M Y') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-5 px-4">
                                        <span
                                            class="text-white font-mono text-xs tracking-wider uppercase bg-[#0a0a0a] px-2 py-1 rounded border border-[#222]">
                                            {{ $product->nomor_seri ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="py-5 px-4">
                                        <div class="text-white text-xs font-semibold uppercase">
                                            {{ $product->tempat_pembuatan ?? '—' }}</div>
                                        <div class="text-[10px] text-[#555] font-bold mt-0.5 tracking-widest">
                                            {{ $product->tahun_pembuatan ?? '?' }}</div>
                                    </td>
                                    <td class="py-5 px-4">
                                        <div class="flex flex-col gap-1.5">
                                            @forelse($product->additionalPics as $ap)
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="text-[10px] text-white font-bold uppercase">{{ $ap->pic_name }}</span>
                                                    <span class="text-[10px] text-yellow-500 font-mono">Rp
                                                        {{ number_format($ap->fee_amount, 0, ',', '.') }}</span>
                                                </div>
                                            @empty
                                                <span class="text-[#333] text-[10px] italic">No PIC assigned</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="py-5 px-4">

                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-[10px] text-red-400 font-bold bg-red-900/10 px-1.5 py-0.5 rounded border border-red-900/20">
                                                Rp {{ number_format($product->harga_pembelian, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-5 px-4 text-center">
                                        <div
                                            class="flex justify-center items-center gap-2 opacity-40 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="p-2 bg-[#222] border border-[#333] rounded-xl text-yellow-500 hover:border-yellow-500 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.products.destroy', $product->id) }}"
                                                class="inline delete-product-form"
                                                data-product-name="{{ $product->nama_barang }}">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="delete-product-btn p-2 bg-[#222] border border-[#333] rounded-xl text-red-500 hover:border-red-500 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="py-20 text-center text-[#333] uppercase tracking-widest text-[10px] font-bold italic">
                                        No instrument found</td>
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

    <script>
        // Fungsi Export dengan Filter
        function exportFilteredData() {
            const dateStart = document.querySelector('input[name="date_start"]').value;
            const dateEnd = document.querySelector('input[name="date_end"]').value;
            const search = document.querySelector('input[name="search"]').value;
            const type = document.querySelector('select[name="type"]').value;

            let exportUrl = "{{ route('admin.products.export') }}";
            const params = new URLSearchParams({
                date_start: dateStart,
                date_end: dateEnd,
                search: search,
                type: type
            });

            window.location.href = `${exportUrl}?${params.toString()}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-product-btn')) {
                    const btn = e.target.closest('.delete-product-btn');
                    const form = btn.closest('form');
                    const name = form.getAttribute('data-product-name');

                    Swal.fire({
                        title: 'Hapus Produk?',
                        text: `Yakin ingin menghapus "${name}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#4a4a4a',
                        confirmButtonText: 'Ya, Hapus!',
                        background: '#1a1a1a',
                        color: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                }
            });

            // Polling realtime dinonaktifkan agar halaman tidak terus memanggil
            // endpoint /admin/products/api/new dan memicu HTTP 429.
            // Data terbaru tetap dapat dilihat setelah halaman dimuat ulang.
        });
    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                background: '#151515',
                color: '#fff',
                confirmButtonColor: '#ca8a04'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Waduh!',
                text: "{{ session('error') }}",
                background: '#151515',
                color: '#fff',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
</body>

</html>
