<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Musicmen Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html, body { overflow-x: hidden; max-width: 100vw; }
        * { box-sizing: border-box; }
        input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 w-full">
                <h1 class="serif-font text-3xl sm:text-4xl text-white mb-4 uppercase tracking-tight">Edit Instrument</h1>
                <div class="h-px bg-gradient-to-r from-yellow-600 via-[#333] to-transparent w-full max-w-2xl"></div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-2xl p-6 lg:p-10 shadow-2xl">
                <form method="POST" action="{{ route('admin.products.update', $product->id) }}" class="space-y-10">
                    @csrf
                    @method('PUT')

                    {{-- SECTION 01: IDENTITAS UNIT --}}
                    <div class="bg-[#151515] p-6 rounded-2xl border border-[#333]">
                        <h3 class="text-[10px] text-yellow-500 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <span class="w-8 h-[1px] bg-yellow-500"></span> 01. Identitas Unit
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold tracking-wider">Nama Barang *</label>
                                <input type="text" name="nama_barang" value="{{ old('nama_barang', $product->nama_barang) }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-yellow-500 outline-none transition-all" required>
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold tracking-wider">Serial Number</label>
                                <input type="text" name="nomor_seri" value="{{ old('nomor_seri', $product->nomor_seri) }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-yellow-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Made In (Origin)</label>
                                <input type="text" name="tempat_pembuatan" value="{{ old('tempat_pembuatan', $product->tempat_pembuatan) }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Tahun</label>
                                <input type="text" name="tahun_pembuatan" value="{{ old('tahun_pembuatan', $product->tahun_pembuatan) }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Warna</label>
                                <input type="text" name="warna" value="{{ old('warna', $product->warna) }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kategori *</label>
                                <select name="type" class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none" required>
                                    @foreach(['electric' => 'Electric Guitar', 'acoustic' => 'Acoustic Guitar', 'bass' => 'Electric Bass', 'effect' => 'Effect / Pedal', 'amplifier' => 'Amplifier'] as $key => $val)
                                        <option value="{{ $key }}" {{ old('type', $product->type) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-red-400 uppercase mb-2 font-bold tracking-widest">Harga Beli (Internal)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-[#444]">Rp</span>
                                    <input type="number" name="harga_pembelian" value="{{ old('harga_pembelian', round($product->harga_pembelian)) }}"
                                        class="w-full bg-[#0a0a0a] border border-red-900/30 rounded-xl pl-8 pr-4 py-3 text-white focus:border-red-600 outline-none font-bold">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Tanggal Masuk</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', $product->tanggal ? $product->tanggal->format('Y-m-d') : '') }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 02: KELENGKAPAN & KONDISI --}}
                    <div class="bg-[#151515] p-6 rounded-2xl border border-[#333]">
                        <h3 class="text-[10px] text-blue-400 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <span class="w-8 h-[1px] bg-blue-400"></span> 02. Kelengkapan & Kondisi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kelengkapan Barang *</label>
                                <input type="text" name="kelengkapan" value="{{ old('kelengkapan', $product->kelengkapan) }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none" required>
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kondisi</label>
                                <select name="kondisi" class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                                    @foreach(['new' => 'New', 'great' => 'Great', 'good' => 'Good', 'need service' => 'Need Service'] as $k => $v)
                                        <option value="{{ $k }}" {{ old('kondisi', $product->kondisi) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 03: PIC & FEE assignment --}}
                    <div class="bg-[#151515] p-6 rounded-2xl border border-[#333]">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-[10px] text-green-400 font-bold uppercase tracking-[0.2em] flex items-center gap-3">
                                <span class="w-8 h-[1px] bg-green-400"></span> 03. PIC & Fee Assignment
                            </h3>
                            <button type="button" id="addPicRow" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all">+ PIC</button>
                        </div>

                        <div id="picRowsContainer" class="space-y-4">
                            @forelse($product->additionalPics as $pic)
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center pic-row bg-[#0a0a0a] p-5 rounded-xl border border-[#222]">
                                    <div class="md:col-span-6">
                                        <select name="additional_pics[]" class="w-full bg-[#151515] border border-[#333] rounded-lg px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none">
                                            @foreach ($staffs as $staff)
                                                <option value="{{ $staff->nama }}" {{ $pic->pic_name == $staff->nama ? 'selected' : '' }}>{{ $staff->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:col-span-5">
                                        <input type="number" name="additional_fees[]" value="{{ $pic->fee_amount }}" placeholder="Rp" class="w-full bg-[#151515] border border-[#333] rounded-lg px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none">
                                    </div>
                                    <div class="md:col-span-1 text-center mt-2">
                                        <button type="button" class="remove-pic-row text-red-500">✖</button>
                                    </div>
                                </div>
                            @empty
                                {{-- Jika belum ada PIC, tampilkan satu baris kosong --}}
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center pic-row bg-[#0a0a0a] p-5 rounded-xl border border-[#222]">
                                    <div class="md:col-span-6">
                                        <select name="additional_pics[]" class="w-full bg-[#151515] border border-[#333] rounded-lg px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none">
                                            <option value="">Pilih Staff</option>
                                            @foreach ($staffs as $staff)
                                                <option value="{{ $staff->nama }}">{{ $staff->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:col-span-5">
                                        <input type="number" name="additional_fees[]" placeholder="Rp" class="w-full bg-[#151515] border border-[#333] rounded-lg px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none">
                                    </div>
                                    <div class="md:col-span-1"></div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- SECTION 04: NOTES --}}
                    <div class="bg-[#151515] p-6 rounded-2xl border border-[#333]">
                         <h3 class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-8">04. Informasi Tambahan</h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Catatan Kondisi</label>
                                <textarea name="catatan" rows="3" class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-gray-500">{{ old('catatan', $product->catatan) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-gray-500">{{ old('keterangan', $product->keterangan) }}</textarea>
                            </div>
                         </div>
                    </div>

                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-5 rounded-2xl uppercase tracking-widest transition-all shadow-lg">Update & Save Instrument</button>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        document.getElementById('addPicRow').addEventListener('click', function() {
            const container = document.getElementById('picRowsContainer');
            const row = document.querySelector('.pic-row').cloneNode(true);
            row.querySelectorAll('input').forEach(i => i.value = '');
            row.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
            container.appendChild(row);
        });

        document.getElementById('picRowsContainer').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-pic-row')) {
                const rows = document.querySelectorAll('.pic-row');
                if (rows.length > 1) e.target.closest('.pic-row').remove();
            }
        });
    </script>
</body>
</html>