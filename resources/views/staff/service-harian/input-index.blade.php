<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Semua Service - Musicmen Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        <div class="w-full px-4 py-8 sm:py-10">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="serif-font text-2xl sm:text-3xl text-white uppercase tracking-wider">Monitoring Semua Service</h1>
                    <p class="text-[#6a6a6a] text-xs sm:text-sm mt-1">Pantau unit masuk dan bonus penerimaan dari seluruh staff.</p>
                </div>
                <a href="{{ route('staff.service-harian.create') }}" class="bg-blue-600 px-6 py-3 rounded-lg text-xs uppercase font-bold text-white hover:bg-blue-700 shadow-lg shadow-blue-900/20">+ Input Baru</a>
            </div>

            {{-- WIDGET STATISTIK BONUS PERSONAL (5%) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-[#1a1a1a] border border-[#333] p-5 rounded-2xl shadow-xl">
                    <p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-widest mb-1">Omset Jasa Saya (Bulan Ini)</p>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Rp {{ number_format($myOmset, 0, ',', '.') }}</h2>
                    <p class="text-[9px] text-[#444] mt-1 italic">*Berdasarkan unit yang Anda terima</p>
                </div>
                <div class="bg-[#1a1a1a] border border-blue-900/20 p-5 rounded-2xl shadow-xl">
                    <p class="text-[10px] text-blue-500 uppercase font-bold tracking-widest mb-1">Total Bonus Saya (5%)</p>
                    <h2 class="text-2xl font-bold text-blue-400 tracking-tight">Rp {{ number_format($myBonus, 0, ',', '.') }}</h2>
                    <p class="text-[9px] text-blue-900/40 mt-1 italic">Target: Berikan pelayanan terbaik!</p>
                </div>
            </div>

            {{-- FORM FILTER --}}
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-4 mb-6 shadow-lg">
                <form method="GET" action="{{ route('staff.service-harian.input-index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama/Unit..." class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-sm text-white focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <select name="month" class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-sm text-white focus:border-blue-500 outline-none">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <select name="year" class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-sm text-white focus:border-blue-500 outline-none">
                            @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white border border-blue-500 py-2 rounded-lg text-xs font-bold uppercase hover:bg-blue-700">Filter</button>
                        <a href="{{ route('staff.service-harian.input-index') }}" class="flex-1 bg-[#2a2a2a] text-[#9a9a9a] border border-[#4a4a4a] py-2 rounded-lg text-xs font-bold uppercase text-center flex items-center justify-center">Reset</a>
                    </div>
                </form>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-sm text-[#9a9a9a] whitespace-nowrap">
                        <thead class="bg-[#0f0f0f] text-white uppercase text-[10px] tracking-widest border-b border-[#333]">
                            <tr>
                                <th class="px-6 py-4">Tgl Masuk</th>
                                <th class="px-6 py-4">Customer</th>
                                <th class="px-6 py-4">Unit Instrumen</th>
                                <th class="px-6 py-4 text-center">Status Kerja</th>
                                <th class="px-6 py-4 text-center">Pengambilan</th>
                                <th class="px-6 py-4 text-right">Fee (5%)</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2a2a2a]">
                            @forelse($services as $svc)
                                <tr class="hover:bg-[#252525] transition-all">
                                    <td class="px-6 py-4 text-xs">{{ \Carbon\Carbon::parse($svc->tanggal_masuk)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4"><span class="text-white font-bold block uppercase">{{ $svc->nama_customer }}</span><span class="text-[10px] text-[#6a6a6a]">{{ $svc->whatsapp }}</span></td>
                                    <td class="px-6 py-4"><div class="text-[#d4d4d4] font-bold">{{ $svc->merk }} {{ $svc->tipe }}</div><div class="text-[10px] text-blue-400 font-bold uppercase">{{ $svc->instrumen }}</div></td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($svc->status_pengerjaan == 'pending') <span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-red-900/30 text-red-500 border border-red-800 animate-pulse">Pending</span>
                                        @elseif($svc->status_pengerjaan == 'selesai') <span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-green-900/30 text-green-400 border border-green-800">Selesai</span>
                                        @else <span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-yellow-900/30 text-yellow-400 border border-yellow-800">Proses</span> @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($svc->status_pengambilan == 'Sudah diambil') <span class="bg-blue-900/20 text-blue-400 text-[9px] px-2 py-0.5 rounded border border-blue-500/20 font-bold uppercase">Sudah Diambil</span>
                                        @else <span class="bg-red-900/10 text-red-500/50 text-[9px] px-2 py-0.5 rounded border border-red-500/10 font-bold uppercase italic">Belum Diambil</span> @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-blue-400 font-bold">Rp {{ number_format($svc->fee_penerima, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="showDetail('{{ json_encode($svc) }}')" class="p-2 bg-blue-600/10 text-blue-500 border border-blue-600/30 rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></button>
                                            <button onclick="sendManualWA('{{ $svc->whatsapp }}', '{{ $svc->nama_customer }}', '{{ $svc->merk }}')" class="p-2 bg-green-600/10 text-green-500 border border-green-600/30 rounded-xl hover:bg-green-600 hover:text-white transition-all duration-300"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.319 1.592 5.448 0 9.886-4.438 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.438-9.889 9.886-.001 1.93.524 3.767 1.542 5.41l-.982 3.593 3.687-.968.325.192z" /></svg></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-8">{{ $services->links() }}</div>
        </div>
    </main>

    {{-- MODAL DETAIL --}}
    <div id="modalDetail" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/80 transition-opacity" onclick="closeModal()"></div>
            <div class="relative bg-[#1a1a1a] border border-[#4a4a4a] w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transition-all">
                <div class="p-6 border-b border-[#333] flex justify-between items-center"><h3 class="serif-font text-xl text-white uppercase tracking-wider">Detail Instrumen</h3><button onclick="closeModal()" class="text-[#9a9a9a] hover:text-white">&times;</button></div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div><p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-widest">Customer</p><p id="det-customer" class="text-white text-sm font-medium"></p></div>
                        <div><p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-widest">WhatsApp</p><p id="det-wa" class="text-white text-sm font-medium"></p></div>
                        <div><p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-widest">Unit</p><p id="det-unit" class="text-white text-sm font-medium uppercase"></p></div>
                        <div><p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-widest">Kategori</p><p id="det-kategori" class="text-blue-400 text-sm font-bold uppercase"></p></div>
                    </div>
                    <div class="pt-4 border-t border-[#333]"><p class="text-[10px] text-yellow-500 uppercase font-bold tracking-widest mb-2">Keluhan</p><div class="bg-[#0f0f0f] p-4 rounded-lg border border-[#333]"><p id="det-keluhan" class="text-[#9a9a9a] text-xs italic leading-relaxed"></p></div></div>
                    <div id="section-keterangan" class="pt-2"><p class="text-[10px] text-green-500 uppercase font-bold tracking-widest mb-2">Keterangan Teknisi</p><div class="bg-[#0f0f0f] p-4 rounded-lg border border-green-900/20"><p id="det-keterangan" class="text-[#9a9a9a] text-xs leading-relaxed"></p></div></div>
                </div>
                <div class="p-6 bg-[#0f0f0f] border-t border-[#333] flex justify-end"><button onclick="closeModal()" class="px-6 py-2 bg-[#2a2a2a] text-white text-xs font-bold uppercase rounded-lg hover:bg-[#333]">Tutup</button></div>
            </div>
        </div>
    </div>

    @include('components.admin-footer')
    <script>
        function showDetail(data) {
            const svc = JSON.parse(data);
            document.getElementById('det-customer').innerText = svc.nama_customer;
            document.getElementById('det-wa').innerText = svc.whatsapp;
            document.getElementById('det-unit').innerText = `${svc.merk} ${svc.tipe || ''}`;
            document.getElementById('det-kategori').innerText = svc.instrumen;
            document.getElementById('det-keluhan').innerText = svc.jenis_service;
            const ketSection = document.getElementById('section-keterangan');
            if (svc.keterangan) { ketSection.classList.remove('hidden'); document.getElementById('det-keterangan').innerText = svc.keterangan; } 
            else { ketSection.classList.add('hidden'); }
            document.getElementById('modalDetail').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() { document.getElementById('modalDetail').classList.add('hidden'); document.body.style.overflow = 'auto'; }
        async function sendManualWA(phone, name, unit) {
            Swal.fire({ title: 'Mengirim WhatsApp...', text: 'Sedang memproses API Fonnte', didOpen: () => { Swal.showLoading(); } });
            try {
                const response = await fetch('{{ route('staff.service-harian.send-manual-wa') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ phone, name, unit }) });
                const data = await response.json();
                if (data.success) Swal.fire({ icon: 'success', title: 'Terkirim!', timer: 2000, showConfirmButton: false });
                else Swal.fire('Gagal!', data.message, 'error');
            } catch (e) { Swal.fire('Error!', 'Sistem bermasalah.', 'error'); }
        }
        window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
    </script>
</body>
</html>