<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Update Progress Service - Musicmen Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body { overflow-x: hidden; max-width: 100vw; }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">

    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-10">
            <div class="max-w-4xl mx-auto">

                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-2">
                        <h1 class="serif-font text-3xl text-white uppercase tracking-wider">Update Progress Service</h1>
                        <span class="inline-block px-3 py-1 rounded-full bg-yellow-900/30 border border-yellow-600 text-yellow-400 text-xs font-bold uppercase tracking-widest w-fit">
                            Teknisi: {{ $staff->nama }}
                        </span>
                    </div>
                    <div class="h-px bg-gradient-to-r from-yellow-500 via-[#4a4a4a] to-transparent w-full"></div>
                </div>

                <form id="updateServiceForm" method="POST" action="{{ route('staff.service-harian.update', $service->id) }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    {{-- Section 1: Info Customer --}}
                    <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-6">
                        <h3 class="text-blue-400 font-semibold uppercase text-xs tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-400 rounded-full"></span> Detail Order & Masalah
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-wider mb-1">Nama Customer</p>
                                <p class="text-white font-medium">{{ $service->nama_customer }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-wider mb-1">Unit Instrumen</p>
                                <p class="text-white font-medium uppercase">{{ $service->merk }} {{ $service->tipe }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-wider mb-1">Diterima Oleh (5%)</p>
                                <p class="text-blue-400 font-medium">{{ $service->penerima ?? 'Admin' }}</p>
                            </div>
                        </div>
                        <div class="bg-[#0f0f0f] border border-blue-900/20 p-4 rounded-lg italic text-xs text-gray-400">
                            "{{ $service->jenis_service }}"
                        </div>
                    </div>

                    {{-- Section 2: Rincian Pengerjaan & Biaya --}}
                    <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-6">
                        <h3 class="text-yellow-500 font-semibold uppercase text-xs tracking-widest mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span> Rincian Pengerjaan & Biaya
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            {{-- BLOK JASA SERVICE --}}
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <label class="text-xs text-yellow-500 font-bold uppercase tracking-widest">List Jasa Service</label>
                                    <button type="button" onclick="addRow('jasa-container')" class="text-[10px] bg-yellow-600 text-black px-2 py-1 rounded font-bold hover:bg-yellow-400">+ TAMBAH</button>
                                </div>
                                <div id="jasa-container" class="space-y-3">
                                    @php $rincianJasa = $service->rincian_jasa ?? [['name' => '', 'amount' => '']]; @endphp
                                    @foreach($rincianJasa as $index => $item)
                                        <div class="flex gap-2 item-row">
                                            <input type="text" name="jasa[{{ $index }}][name]" value="{{ $item['name'] }}" placeholder="Nama Jasa" class="flex-1 bg-[#0f0f0f] border border-[#333] rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-yellow-500">
                                            <input type="number" name="jasa[{{ $index }}][amount]" value="{{ (int)$item['amount'] }}" placeholder="Rp" class="w-32 bg-[#0f0f0f] border border-[#333] rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-yellow-500 input-val-jasa" oninput="calculateAll()">
                                            @if($index > 0)
                                                <button type="button" onclick="this.parentElement.remove(); calculateAll();" class="text-red-500 px-2 font-bold">&times;</button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- BLOK SPAREPART --}}
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <label class="text-xs text-blue-400 font-bold uppercase tracking-widest">List Sparepart / Part</label>
                                    <button type="button" onclick="addRow('sparepart-container')" class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded font-bold hover:bg-blue-400">+ TAMBAH</button>
                                </div>
                                <div id="sparepart-container" class="space-y-3">
                                    @php $rincianSparepart = $service->rincian_sparepart ?? [['name' => '', 'amount' => '']]; @endphp
                                    @foreach($rincianSparepart as $index => $item)
                                        <div class="flex gap-2 item-row">
                                            <input type="text" name="sparepart[{{ $index }}][name]" value="{{ $item['name'] }}" placeholder="Nama Part" class="flex-1 bg-[#0f0f0f] border border-[#333] rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-blue-500">
                                            <input type="number" name="sparepart[{{ $index }}][amount]" value="{{ (int)$item['amount'] }}" placeholder="Rp" class="w-32 bg-[#0f0f0f] border border-[#333] rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-blue-500 input-val-sparepart" oninput="calculateAll()">
                                            @if($index > 0)
                                                <button type="button" onclick="this.parentElement.remove(); calculateAll();" class="text-red-500 px-2 font-bold">&times;</button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- PREVIEW TOTAL --}}
                        <div class="grid grid-cols-2 gap-4 bg-[#0f0f0f] p-5 rounded-xl border border-yellow-900/20 shadow-inner mb-6">
                            <div class="border-r border-[#333]">
                                <p class="text-[10px] text-[#6a6a6a] uppercase tracking-widest font-bold">Bonus Anda (30% Jasa)</p>
                                <p id="preview_fee_teknisi" class="text-2xl text-green-400 font-bold">Rp 0</p>
                            </div>
                            <div class="pl-2">
                                <p class="text-[10px] text-[#6a6a6a] uppercase tracking-widest font-bold">Total Tagihan</p>
                                <p id="preview_total_akhir" class="text-2xl text-white font-bold">Rp 0</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase">Status Pengerjaan</label>
                                <select name="status_pengerjaan" id="status_pengerjaan" onchange="updateButtonText()" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white outline-none focus:border-blue-500">
                                    <option value="proses" {{ $service->status_pengerjaan == 'proses' ? 'selected' : '' }}>PROSES PENGERJAAN</option>
                                    <option value="selesai" {{ $service->status_pengerjaan == 'selesai' ? 'selected' : '' }}>SELESAI (SIAP AMBIL)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase">Status Pengambilan</label>
                                <select name="status_pengambilan" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white outline-none focus:border-blue-500">
                                    <option value="Belum diambil" {{ $service->status_pengambilan == 'Belum diambil' ? 'selected' : '' }}>BELUM DIAMBIL</option>
                                    <option value="Sudah diambil" {{ $service->status_pengambilan == 'Sudah diambil' ? 'selected' : '' }}>SUDAH DIAMBIL</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase">Keterangan Tambahan</label>
                                <textarea name="keterangan" rows="2" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white outline-none focus:border-blue-500" placeholder="Hasil pengerjaan teknisi...">{{ old('keterangan', $service->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row gap-4 pb-20">
                        <button type="submit" id="submitBtn" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-4 rounded-xl uppercase tracking-widest transition-all shadow-lg flex items-center justify-center gap-2">
                            <span id="btnText">Simpan Update</span>
                        </button>
                        <a href="{{ route('staff.service-harian.kelola-index') }}" class="flex-1 bg-transparent border border-[#4a4a4a] text-[#9a9a9a] text-center py-4 rounded-xl hover:bg-[#2a2a2a] uppercase tracking-widest text-sm flex items-center justify-center transition-all">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        // Inisialisasi index berdasarkan jumlah data yang ada di database
        let jasaIdx = {{ is_array($service->rincian_jasa) ? count($service->rincian_jasa) : 1 }};
        let spareIdx = {{ is_array($service->rincian_sparepart) ? count($service->rincian_sparepart) : 1 }};

        function addRow(containerId) {
            const container = document.getElementById(containerId);
            const type = containerId.split('-')[0];
            const idx = type === 'jasa' ? jasaIdx++ : spareIdx++;

            const div = document.createElement('div');
            div.className = 'flex gap-2 item-row';
            div.innerHTML = `
                <input type="text" name="${type}[${idx}][name]" placeholder="Nama Item" class="flex-1 bg-[#0f0f0f] border border-[#333] rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-yellow-500">
                <input type="number" name="${type}[${idx}][amount]" placeholder="Rp" class="w-32 bg-[#0f0f0f] border border-[#333] rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-yellow-500 input-val-${type}" oninput="calculateAll()">
                <button type="button" onclick="this.parentElement.remove(); calculateAll();" class="text-red-500 px-2 font-bold">&times;</button>
            `;
            container.appendChild(div);
        }

        function calculateAll() {
            let totalJasa = 0;
            let totalSparepart = 0;

            document.querySelectorAll('.input-val-jasa').forEach(input => {
                totalJasa += parseFloat(input.value) || 0;
            });

            document.querySelectorAll('.input-val-sparepart').forEach(input => {
                totalSparepart += parseFloat(input.value) || 0;
            });

            const feeTeknisi = totalJasa * 0.30;
            const totalAkhir = totalJasa + totalSparepart;

            const formatter = new Intl.NumberFormat('id-ID');
            document.getElementById('preview_fee_teknisi').innerText = 'Rp ' + formatter.format(Math.round(feeTeknisi));
            document.getElementById('preview_total_akhir').innerText = 'Rp ' + formatter.format(totalAkhir);
        }

        function updateButtonText() {
            const status = document.getElementById('status_pengerjaan').value;
            const btn = document.getElementById('submitBtn');
            const txt = document.getElementById('btnText');

            if (status === 'selesai') {
                txt.innerText = "Simpan & Kirim WA Selesai";
                btn.classList.replace('bg-yellow-600', 'bg-green-600');
                btn.classList.replace('hover:bg-yellow-500', 'hover:bg-green-500');
                btn.classList.add('text-white');
            } else {
                txt.innerText = "Simpan Update";
                btn.classList.replace('bg-green-600', 'bg-yellow-600');
                btn.classList.replace('hover:bg-green-500', 'hover:bg-yellow-500');
                btn.classList.remove('text-white');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            calculateAll();
            updateButtonText();
        });
    </script>
</body>
</html>