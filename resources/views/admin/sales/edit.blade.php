<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sales Instrumen - Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body {
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
            <div class="mb-8 w-full">
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Edit Sales Instrumen</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <form method="POST" action="{{ route('admin.sales.update', $sale->id) }}">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                        <ul class="text-sm text-red-400">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Tanggal <span class="text-red-400">*</span></label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $sale->tanggal->format('Y-m-d')) }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                required>
                        </div>

                        <div>
                            <label for="nama_barang" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Barang <span class="text-red-400">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $sale->nama_barang) }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                placeholder="e.g., Epiphone Slash AFD" required>
                        </div>

                        <div>
                            <label for="type" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Type <span class="text-red-400">*</span></label>
                            <select id="type" name="type"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                required>
                                <option value="">Select Type</option>
                                <option value="electric" {{ old('type', $sale->type) == 'electric' ? 'selected' : '' }}>Electric Guitar</option>
                                <option value="acoustic" {{ old('type', $sale->type) == 'acoustic' ? 'selected' : '' }}>Acoustic Guitar</option>
                                <option value="bass" {{ old('type', $sale->type) == 'bass' ? 'selected' : '' }}>Bass</option>
                                <option value="effect" {{ old('type', $sale->type) == 'effect' ? 'selected' : '' }}>Effect</option>
                                <option value="amplifier" {{ old('type', $sale->type) == 'amplifier' ? 'selected' : '' }}>Amplifier</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm text-[#9a9a9a] mb-4 uppercase tracking-wider">Sales & Fee PIC</label>
                            <div id="sales-repeater-container" class="space-y-4">
                                @php
                                    $salesList = $sale->sales_list;
                                    $feesList = $sale->fees_list;
                                @endphp
                                @foreach($salesList as $idx => $salesName)
                                <div class="flex flex-col sm:flex-row gap-4 items-start bg-[#0f0f0f] p-4 rounded-lg border border-[#4a4a4a] sales-row">
                                    <div class="flex-1 w-full">
                                        <label class="text-xs text-[#6a6a6a] uppercase">Nama Sales</label>
                                        <select name="sales[]" class="w-full bg-[#1a1a1a] border border-[#4a4a4a] rounded px-4 py-2 mt-1 focus:outline-none focus:border-blue-500">
                                            <option value="">Pilih Sales</option>
                                            @foreach($staffs as $s)
                                            <option value="{{ $s->nama }}" {{ $salesName == $s->nama ? 'selected' : '' }}>{{ $s->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-1 w-full">
                                        <label class="text-xs text-[#6a6a6a] uppercase">Fee (Rp)</label>
                                        <input type="text" class="fee-mask w-full bg-[#1a1a1a] border border-[#4a4a4a] rounded px-4 py-2 mt-1 focus:outline-none focus:border-blue-500" 
                                               value="{{ number_format($feesList[$idx] ?? 0, 0, ',', '.') }}" placeholder="Contoh: 50.000">
                                        <input type="hidden" name="fees[]" class="fee-real" value="{{ $feesList[$idx] ?? 0 }}">
                                    </div>
                                    <div class="pt-6">
                                        <button type="button" class="remove-sales text-red-500 hover:text-red-400 p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <button type="button" id="add-sales-btn" class="mt-4 text-sm text-blue-400 hover:text-blue-300 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Sales Lainnya
                            </button>
                        </div>

                        <div>
                            <label for="kondisi" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Kondisi</label>
                            <select id="kondisi" name="kondisi"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                                <option value="">Select Condition</option>
                                <option value="great" {{ old('kondisi', $sale->kondisi) == 'great' ? 'selected' : '' }}>Great</option>
                                <option value="good" {{ old('kondisi', $sale->kondisi) == 'good' ? 'selected' : '' }}>Good</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="keterangan" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="3"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                placeholder="e.g., Offline, Setup, Shopee + Packing Kayu, etc.">{{ old('keterangan', $sale->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button type="submit"
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Update
                        </button>
                        <a href="{{ route('admin.sales.index') }}"
                            class="bg-transparent border border-[#6a6a6a] text-[#9a9a9a] px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:text-white transition-all uppercase tracking-wider">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('sales-repeater-container');
            const addBtn = document.getElementById('add-sales-btn');

            function initMask(row) {
                const maskInput = row.querySelector('.fee-mask');
                const realInput = row.querySelector('.fee-real');

                maskInput.addEventListener('input', function() {
                    let rawValue = this.value.replace(/[^0-9]/g, '');
                    realInput.value = rawValue;
                    this.value = rawValue ? new Intl.NumberFormat('id-ID').format(rawValue) : '';
                });
            }

            // Init mask untuk semua baris yang sudah ada
            container.querySelectorAll('.sales-row').forEach(row => initMask(row));

            addBtn.addEventListener('click', function() {
                const rows = container.querySelectorAll('.sales-row');
                const newRow = rows[0].cloneNode(true);

                newRow.querySelector('select').value = '';
                newRow.querySelector('.fee-mask').value = '';
                newRow.querySelector('.fee-real').value = '';

                container.appendChild(newRow);
                initMask(newRow);
            });

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-sales')) {
                    const rows = container.querySelectorAll('.sales-row');
                    if (rows.length > 1) {
                        e.target.closest('.sales-row').remove();
                    } else {
                        alert("Minimal harus ada 1 sales.");
                    }
                }
            });
        });
    </script>

    @include('components.admin-footer')
</body>
</html>