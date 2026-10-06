<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instrument - Musicmen Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        input::placeholder { color: #444 !important; }
        select option { background: #1a1a1a; color: white; }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 px-4 py-12">
        <div class="max-w-5xl mx-auto">
            <h1 class="serif-font text-3xl sm:text-4xl text-white mb-2 uppercase tracking-tight">Edit Instrument</h1>
            <p class="text-[#6a6a6a] text-sm mb-8">Lengkapi histori dan detail instrumen untuk tim teknisi.</p>
            <div class="h-px bg-gradient-to-r from-yellow-600 via-[#333] to-transparent w-full mb-10"></div>

            <form method="POST" action="{{ route('staff.products.update', $product->id) }}" class="space-y-6">
                @csrf
                @method('PUT')
            
                <div class="bg-[#151515] p-6 rounded-2xl border border-[#222] shadow-xl">
                    <h3 class="text-[10px] text-yellow-500 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-yellow-500"></span> 01. Identitas Unit
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-8">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Nama Barang *</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang', $product->nama_barang) }}" 
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-yellow-600 outline-none" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Serial Number</label>
                            <input type="text" name="nomor_seri" value="{{ old('nomor_seri', $product->nomor_seri) }}" 
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-yellow-600 outline-none">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2">Made In (Origin)</label>
                            <input type="text" name="tempat_pembuatan" value="{{ old('tempat_pembuatan', $product->tempat_pembuatan) }}" 
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2">Tahun</label>
                            <input type="text" name="tahun_pembuatan" value="{{ old('tahun_pembuatan', $product->tahun_pembuatan) }}" 
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2">Kategori Unit *</label>
                            <select name="type" class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-yellow-600" required>
                                <option value="electric" {{ $product->type == 'electric' ? 'selected' : '' }}>Electric Guitar</option>
                                <option value="acoustic" {{ $product->type == 'acoustic' ? 'selected' : '' }}>Acoustic Guitar</option>
                                <option value="bass" {{ $product->type == 'bass' ? 'selected' : '' }}>Electric Bass</option>
                                <option value="effect" {{ $product->type == 'effect' ? 'selected' : '' }}>Effect / Pedal</option>
                                <option value="amplifier" {{ $product->type == 'amplifier' ? 'selected' : '' }}>Amplifier</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2">Warna</label>
                            <input type="text" name="warna" value="{{ old('warna', $product->warna) }}" 
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                        </div>
                    </div>
                </div>
            
                <div class="bg-[#151515] p-6 rounded-2xl border border-[#222] shadow-xl">
                    <h3 class="text-[10px] text-blue-400 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-blue-400"></span> 02. Kelengkapan & Kondisi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-4">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kelengkapan *</label>
                            <input type="text" name="kelengkapan" value="{{ old('kelengkapan', $product->kelengkapan) }}" 
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kondisi Barang</label>
                            <select name="kondisi" class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                                @foreach(['new' => 'New', 'great' => 'Great', 'good' => 'Good', 'need service' => 'Need Service'] as $k => $v)
                                    <option value="{{ $k }}" {{ old('kondisi', $product->kondisi) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-[10px] text-red-400 uppercase mb-2 font-bold tracking-widest">Harga Beli (Modal)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-[#444]">Rp</span>
                                <input type="number" name="harga_pembelian" value="{{ old('harga_pembelian', round($product->harga_pembelian)) }}" 
                                    class="w-full bg-[#0a0a0a] border border-red-900/30 rounded-xl pl-8 pr-4 py-3 text-white focus:border-red-600 outline-none font-bold">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 03: PIC & FEE assignment (Updateable) --}}
                <div class="bg-[#151515] p-6 rounded-2xl border border-[#222] shadow-xl">
                    <h3 class="text-[10px] text-green-400 font-bold uppercase tracking-[0.2em] mb-8">03. PIC & Fee assignment</h3>
                    <div id="picRowsContainer" class="space-y-4">
                        @foreach($product->additionalPics as $pic)
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center pic-row bg-[#0a0a0a] p-5 rounded-xl border border-[#222]">
                                <div class="md:col-span-6">
                                    <input type="text" name="additional_pics[]" value="{{ $pic->pic_name }}" class="w-full bg-[#151515] border border-[#333] rounded-lg px-4 py-2.5 text-sm text-gray-500" readonly>
                                </div>
                                <div class="md:col-span-6">
                                    <input type="number" name="additional_fees[]" value="{{ $pic->fee_amount }}" class="w-full bg-[#151515] border border-[#333] rounded-lg px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-[#151515] p-6 rounded-2xl border border-[#222] shadow-xl">
                    <h3 class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-8">04. Informasi Tambahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Catatan Internal</label>
                            <textarea name="catatan" rows="3" class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-gray-500">{{ old('catatan', $product->catatan) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-gray-500">{{ old('keterangan', $product->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-5 rounded-2xl uppercase tracking-widest transition-all shadow-lg">Save Changes</button>
            </form>
        </div>
    </main>
    @include('components.admin-footer')
</body>
</html>