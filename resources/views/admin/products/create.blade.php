<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Musicmen Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html, body { overflow-x: hidden; max-width: 100vw; }
        * { box-sizing: border-box; }
        input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); }
        /* Hilangkan spinner pada input number */
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; margin: 0; 
        }
    </style>
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 w-full">
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4 uppercase tracking-tight">Add New Instrument</h1>
                <div class="h-px bg-gradient-to-r from-yellow-600 via-[#333] to-transparent w-full max-w-2xl"></div>
            </div>

            @if ($errors->any())
                <div class="mb-8 rounded-xl border border-red-500/40 bg-red-950/30 px-5 py-4 text-sm text-red-200">
                    <p class="font-bold uppercase tracking-wider text-red-400">Data belum tersimpan</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-2xl p-6 lg:p-10 shadow-2xl">
                <form id="productCreateForm" method="POST" action="{{ route('admin.products.store') }}" class="space-y-10">
                    @csrf

                    {{-- SECTION 01: IDENTITAS UNIT --}}
                    <div class="bg-[#151515] p-6 rounded-2xl border border-[#333]">
                        <h3 class="text-[10px] text-yellow-500 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <span class="w-8 h-[1px] bg-yellow-500"></span> 01. Identitas Unit
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold tracking-wider">Nama Barang *</label>
                                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-yellow-500 outline-none transition-all"
                                    required placeholder="Contoh: Fender Stratocaster American Professional II">
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold tracking-wider">Serial Number</label>
                                <input type="text" name="nomor_seri" value="{{ old('nomor_seri') }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white focus:border-yellow-500 outline-none transition-all"
                                    placeholder="ex: US2100456">
                            </div>
                            
                            {{-- Field yang sebelumnya hilang --}}
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Made In (Origin)</label>
                                <input type="text" name="tempat_pembuatan" value="{{ old('tempat_pembuatan') }}"
                                    placeholder="USA / Japan / China"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Tahun Pembuatan</label>
                                <input type="text" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}"
                                    placeholder="2024"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Warna Unit</label>
                                <input type="text" name="warna" value="{{ old('warna') }}"
                                    placeholder="ex: 3-Tone Sunburst"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kategori *</label>
                                <select name="type" class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none" required>
                                    <option value="electric" @selected(old('type', 'electric') === 'electric')>Electric Guitar</option>
                                    <option value="acoustic" @selected(old('type') === 'acoustic')>Acoustic Guitar</option>
                                    <option value="bass" @selected(old('type') === 'bass')>Electric Bass</option>
                                    <option value="effect" @selected(old('type') === 'effect')>Effect / Pedal</option>
                                    <option value="amplifier" @selected(old('type') === 'amplifier')>Amplifier</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-red-400 uppercase mb-2 font-bold tracking-widest">Harga Beli (Modal)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-[#444]">Rp</span>
                                    <input type="number" name="harga_pembelian" value="{{ old('harga_pembelian') }}" placeholder="0" 
                                        class="w-full bg-[#0a0a0a] border border-red-900/30 rounded-xl pl-8 pr-4 py-3 text-white focus:border-red-600 outline-none font-bold">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Tanggal Masuk</label>
                                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
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
                                <input type="text" name="kelengkapan" value="{{ old('kelengkapan') }}"
                                    placeholder="ex: Include Hardcase, COA, Manual Book"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none" required>
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Kondisi Barang</label>
                                <select name="kondisi" class="w-full bg-[#0f0f0f] border border-[#333] rounded-xl px-4 py-3 text-white outline-none">
                                    <option value="new" @selected(old('kondisi', 'good') === 'new')>New (Baru/Mulus)</option>
                                    <option value="great" @selected(old('kondisi') === 'great')>Great (Sangat Bagus)</option>
                                    <option value="good" @selected(old('kondisi', 'good') === 'good')>Good (Lecet Pemakaian)</option>
                                    <option value="need service" @selected(old('kondisi') === 'need service')>Need Service (Butuh Perbaikan)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @php
                        $oldPicNames = old('additional_pics', ['']);
                        $oldPicFees = old('additional_fees', ['']);
                        $oldPicNames = is_array($oldPicNames) ? $oldPicNames : [''];
                        $oldPicFees = is_array($oldPicFees) ? $oldPicFees : [''];
                        $picRowCount = max(count($oldPicNames), count($oldPicFees), 1);
                    @endphp

                    {{-- SECTION 03: PIC & FEE assignment --}}
                    <div class="bg-[#151515] p-6 rounded-2xl border border-[#333]">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-[10px] text-green-400 font-bold uppercase tracking-[0.2em] flex items-center gap-3">
                                <span class="w-8 h-[1px] bg-green-400"></span> 03. PIC & Fee Assignment
                            </h3>
                            <button type="button" id="addPicRow"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah PIC
                            </button>
                        </div>

                        <div id="picRowsContainer" class="space-y-4">
                            @for ($i = 0; $i < $picRowCount; $i++)
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center pic-row bg-[#0a0a0a] p-5 rounded-xl border border-[#222]">
                                    <div class="md:col-span-6">
                                        <label class="block text-[9px] text-[#555] uppercase mb-1 font-bold">Staff Member</label>
                                        <select name="additional_pics[]" class="w-full bg-[#151515] border border-[#333] rounded-lg px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none">
                                            <option value="">Pilih Staff</option>
                                            @foreach ($staffs as $staff)
                                                <option value="{{ $staff->nama }}" @selected(($oldPicNames[$i] ?? '') === $staff->nama)>{{ $staff->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:col-span-5">
                                        <label class="block text-[9px] text-[#555] uppercase mb-1 font-bold">Nominal Fee (Rp)</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-[#444]">Rp</span>
                                            <input type="number" name="additional_fees[]" value="{{ $oldPicFees[$i] ?? '' }}" placeholder="0" class="w-full bg-[#151515] border border-[#333] rounded-lg pl-8 pr-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none">
                                        </div>
                                    </div>
                                    <div class="md:col-span-1 text-center mt-4">
                                        <button type="button" class="remove-pic-row text-red-500 hover:text-red-400 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- SECTION 04: CATATAN & KETERANGAN --}}
                    <div class="bg-[#151515] p-6 rounded-2xl border border-[#333]">
                         <h3 class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                             <span class="w-8 h-[1px] bg-gray-400"></span> 04. Informasi Tambahan
                         </h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Catatan Kondisi (Internal)</label>
                                <textarea name="catatan" rows="3" placeholder="Contoh: Lecet sedikit di body belakang dekat neck" class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-gray-500">{{ old('catatan') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] text-[#6a6a6a] uppercase mb-2 font-bold">Keterangan Tambahan</label>
                                <textarea name="keterangan" rows="3" placeholder="Informasi lain untuk pembeli atau tim marketing..." class="w-full bg-[#0a0a0a] border border-[#333] rounded-xl px-4 py-3 text-white outline-none focus:border-gray-500">{{ old('keterangan') }}</textarea>
                            </div>
                         </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button id="saveProductButton" type="submit" class="flex-[2] bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-5 rounded-2xl uppercase tracking-widest transition-all shadow-lg shadow-yellow-900/20">
                            Save Product & Regist
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="flex-1 bg-transparent border border-[#333] text-[#6a6a6a] hover:text-white hover:border-[#444] text-center py-5 rounded-2xl transition-all flex items-center justify-center uppercase text-xs font-bold tracking-widest">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        const productCreateForm = document.getElementById('productCreateForm');
        const picRowsContainer = document.getElementById('picRowsContainer');
        const productDraftKey = 'musicmen.admin.products.create.draft';

        function saveProductDraft() {
            const draft = {};

            productCreateForm.querySelectorAll('input[name], select[name], textarea[name]').forEach(field => {
                if (field.name === '_token') return;

                if (field.name.endsWith('[]')) {
                    if (!Array.isArray(draft[field.name])) draft[field.name] = [];
                    draft[field.name].push(field.value);
                } else {
                    draft[field.name] = field.value;
                }
            });

            try {
                localStorage.setItem(productDraftKey, JSON.stringify(draft));
            } catch (error) {
                console.warn('Draft produk tidak dapat disimpan di browser.', error);
            }
        }

        function restoreProductDraft() {
            let draft;

            try {
                draft = JSON.parse(localStorage.getItem(productDraftKey) || 'null');
            } catch (error) {
                draft = null;
            }

            if (!draft) return;

            Object.keys(draft).forEach(name => {
                if (name.endsWith('[]')) return;

                const field = productCreateForm.elements[name];
                if (field && draft[name] !== undefined) field.value = draft[name];
            });

            const draftNames = Array.isArray(draft['additional_pics[]']) ? draft['additional_pics[]'] : [];
            const draftFees = Array.isArray(draft['additional_fees[]']) ? draft['additional_fees[]'] : [];
            const rowCount = Math.max(draftNames.length, draftFees.length);

            while (picRowsContainer.querySelectorAll('.pic-row').length < rowCount) {
                const firstRow = picRowsContainer.querySelector('.pic-row');
                const newRow = firstRow.cloneNode(true);
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                picRowsContainer.appendChild(newRow);
            }

            picRowsContainer.querySelectorAll('.pic-row').forEach((row, index) => {
                const picSelect = row.querySelector('select[name="additional_pics[]"]');
                const feeInput = row.querySelector('input[name="additional_fees[]"]');
                if (picSelect && draftNames[index] !== undefined) picSelect.value = draftNames[index];
                if (feeInput && draftFees[index] !== undefined) feeInput.value = draftFees[index];
            });
        }

        restoreProductDraft();
        productCreateForm.addEventListener('input', saveProductDraft);
        productCreateForm.addEventListener('change', saveProductDraft);

        // Fungsi Tambah PIC Row
        document.getElementById('addPicRow').addEventListener('click', function() {
            const container = document.getElementById('picRowsContainer');
            const rows = document.querySelectorAll('.pic-row');
            const newRow = rows[0].cloneNode(true);

            // Reset value pada baris baru
            newRow.querySelectorAll('input').forEach(i => i.value = '');
            newRow.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

            container.appendChild(newRow);
            saveProductDraft();
        });

        // Fungsi Hapus PIC Row (Event Delegation)
        document.getElementById('picRowsContainer').addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.remove-pic-row');
            if (deleteBtn) {
                const rows = document.querySelectorAll('.pic-row');
                if (rows.length > 1) {
                    deleteBtn.closest('.pic-row').remove();
                } else {
                    const firstRow = deleteBtn.closest('.pic-row');
                    firstRow.querySelector('select').selectedIndex = 0;
                    firstRow.querySelector('input').value = '';
                    saveProductDraft();
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Notice',
                        text: 'Minimal harus ada satu baris PIC.',
                        background: '#151515',
                        color: '#fff'
                    });
                }
            }
        });

        // Cegah double-submit yang dapat membuat data ganda atau memicu rate limit.
        document.getElementById('productCreateForm').addEventListener('submit', function() {
            saveProductDraft();
            const button = document.getElementById('saveProductButton');
            if (!button || button.disabled) return;

            button.disabled = true;
            button.textContent = 'MENYIMPAN...';
            button.classList.add('opacity-60', 'cursor-not-allowed');
        });
    </script>
</body>
</html>
