<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Instrument - Musicmen Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        input::placeholder {
            color: #444 !important;
        }

        select option {
            background: #1a1a1a;
            color: white;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 px-4 py-12">
        <div class="max-w-5xl mx-auto">
            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-xl mb-6 shadow-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-orange-600 text-white p-4 rounded-xl mb-6 shadow-lg">
                    {{ session('error') }}
                </div>
            @endif
            <h1 class="serif-font text-3xl sm:text-4xl text-white mb-2 uppercase tracking-tight">Add New Instrument</h1>
            <p class="text-[#6a6a6a] text-sm mb-8">Pendaftaran unit masuk baru. Detail teknis (Setup/Price Tag) akan
                diproses oleh Teknisi.</p>
            <div class="h-px bg-gradient-to-r from-yellow-600 via-[#333] to-transparent w-full mb-10"></div>

            <form method="POST" action="{{ route('staff.products.store') }}" class="space-y-6">
                @csrf


                {{-- SECTION 01: IDENTITAS UNIT --}}
                <div class="bg-[#151515] p-6 rounded-2xl border border-[#222] shadow-xl">
                    <h3
                        class="text-[10px] text-yellow-500 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-yellow-500"></span> 01. Identitas Unit
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-8">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold tracking-wider">Nama
                                Barang *</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                                placeholder="Contoh: Fender Stratocaster American Professional II"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-yellow-600 outline-none transition-all"
                                required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Serial
                                Number</label>
                            <input type="text" name="nomor_seri" placeholder="ex: US2100456"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-yellow-600 outline-none">
                        </div>

                        {{-- Field Tambahan sesuai Admin --}}
                        <div class="md:col-span-3">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2">Origin (Made In)</label>
                            <input type="text" name="tempat_pembuatan" placeholder="USA / Japan / China"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-yellow-600">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2">Tahun Pembuatan</label>
                            <input type="text" name="tahun_pembuatan" placeholder="2022"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-yellow-600">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2">Kategori Unit *</label>
                            <select name="type"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-yellow-600"
                                required>
                                <option value="electric">Electric Guitar</option>
                                <option value="acoustic">Acoustic Guitar</option>
                                <option value="bass">Electric Bass</option>
                                <option value="effect">Effect / Pedal</option>
                                <option value="amplifier">Amplifier</option>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2">Warna Unit</label>
                            <input type="text" name="warna" placeholder="ex: 3-Tone Sunburst"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-yellow-600">
                        </div>
                    </div>
                </div>

                {{-- SECTION 02: KELENGKAPAN & KONDISI --}}
                <div class="bg-[#151515] p-6 rounded-2xl border border-[#222] shadow-xl">
                    <h3
                        class="text-[10px] text-blue-400 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-blue-400"></span> 02. Kelengkapan & Harga
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-4">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kelengkapan Barang
                                *</label>
                            <input type="text" name="kelengkapan" placeholder="Contoh: Hardcase, Manual Book"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all"
                                required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kondisi
                                Awal</label>
                            <select name="kondisi"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-blue-500">
                                <option value="new">New</option>
                                <option value="great">Great</option>
                                <option value="good" selected>Good</option>
                                <option value="need service">Need Service</option>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-[10px] text-red-400 uppercase mb-2 font-bold tracking-widest">Harga
                                Pembelian (Internal)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-[#444]">Rp</span>
                                <input type="number" name="harga_pembelian" placeholder="0"
                                    class="w-full bg-[#0a0a0a] border border-red-900/30 rounded-xl pl-8 pr-4 py-3 text-white focus:border-red-600 outline-none font-bold">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 03: PIC & FEE (SINGLE) --}}
                <div class="bg-[#151515] p-6 rounded-2xl border border-[#222] shadow-xl">
                    <div class="flex justify-between items-center mb-8">
                        <h3
                            class="text-[10px] text-green-400 font-bold uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="w-8 h-[1px] bg-green-400"></span> 03. PIC & Fee Assignment
                        </h3>
                        <button type="button" id="add-staff-btn"
                            class="text-[10px] bg-green-900/20 text-green-400 border border-green-800/30 px-4 py-2 rounded-xl hover:bg-green-500 hover:text-black transition-all font-bold uppercase">
                            + Add More Staff
                        </button>
                    </div>

                    <div id="staff-fee-container" class="space-y-4">
                        {{-- Row pertama otomatis untuk Staff yang Login --}}
                        <div
                            class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-[#0a0a0a] p-4 rounded-xl border border-[#222]">
                            <div class="md:col-span-7">
                                <label class="block text-[9px] text-[#444] uppercase mb-2 font-bold">Staff Name
                                    (Auto-filled)</label>
                                <select name="additional_pics[]"
                                    class="w-full bg-[#111] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-green-500">
                                    @foreach ($staffs as $s)
                                        <option value="{{ $s->nama }}"
                                            {{ (Auth::user()->staff->nama ?? Auth::user()->name) == $s->nama ? 'selected' : '' }}>
                                            {{ $s->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-[9px] text-[#444] uppercase mb-2 font-bold">Fee Amount
                                    (Rp)</label>
                                <input type="number" name="additional_fees[]" value="0"
                                    class="w-full bg-[#111] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-green-500">
                            </div>
                            <div class="md:col-span-1 flex justify-center pb-2">
                                <span class="text-[#222]"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 04: ADDITIONAL INFORMATION --}}
                <div class="bg-[#151515] p-6 rounded-2xl border border-[#222] shadow-xl">
                    <h3
                        class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-gray-400"></span> 04. Informasi Tambahan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Catatan Kondisi
                                (Internal)</label>
                            <textarea name="catatan" rows="3" placeholder="Contoh: Lecet sedikit di body belakang"
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-gray-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Keterangan
                                Tambahan</label>
                            <textarea name="keterangan" rows="3" placeholder="Informasi tambahan lainnya..."
                                class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-gray-500"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit"
                        class="flex-[2] bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-5 rounded-2xl uppercase tracking-widest transition-all shadow-lg shadow-yellow-900/20">
                        Submit & Save Unit
                    </button>
                    <a href="{{ route('staff.products.index') }}"
                        class="flex-1 bg-transparent border border-[#333] text-[#6a6a6a] hover:text-white hover:border-[#444] text-center py-5 rounded-2xl transition-all flex items-center justify-center uppercase text-xs font-bold tracking-widest">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
    @include('components.admin-footer')

    <script>
        document.getElementById('add-staff-btn').addEventListener('click', function() {
            const container = document.getElementById('staff-fee-container');
            const newRow = document.createElement('div');
            newRow.className =
                'grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-[#0a0a0a] p-4 rounded-xl border border-[#222]';
            newRow.innerHTML = `
                <div class="md:col-span-7">
                    <select name="additional_pics[]" class="w-full bg-[#111] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-green-500">
                        <option value="">Select Staff</option>
                        @foreach ($staffs as $s)
                            <option value="{{ $s->nama }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4">
                    <input type="number" name="additional_fees[]" value="0" class="w-full bg-[#111] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-green-500">
                </div>
                <div class="md:col-span-1 flex justify-center pb-1">
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-400 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></button>
                </div>
            `;
            container.appendChild(newRow);
        });
    </script>
</body>

</html>
