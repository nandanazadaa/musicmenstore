<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pekerjaan Teknisi - Musicmen Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4a4a4a;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        <div class="w-full px-4 py-8 sm:py-10">

            <div class="mb-8 border-l-4 border-yellow-500 pl-4">
                <h1 class="serif-font text-3xl text-white uppercase tracking-wider">Antrean Pekerjaan Service (30%)</h1>
                <p class="text-[#9a9a9a] text-sm mt-1">Halo <b>{{ $staffLogin->nama }}</b>, selesaikan unit dan pantau
                    bonus pribadi Anda.</p>
            </div>

            {{-- WIDGET STATISTIK BONUS TEKNISI --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-[#1a1a1a] border border-[#333] p-5 rounded-2xl shadow-xl">
                    <p class="text-[10px] text-[#6a6a6a] uppercase font-bold tracking-widest mb-1">Total Jasa Dikerjakan
                        (Bulan Terpilih)</p>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Rp
                        {{ number_format($myTotalJasa, 0, ',', '.') }}</h2>
                </div>
                <div class="bg-[#1a1a1a] border border-yellow-900/20 p-5 rounded-2xl shadow-xl">
                    <p class="text-[10px] text-yellow-600 uppercase font-bold tracking-widest mb-1">Bonus Saya (30%)</p>
                    <h2 class="text-2xl font-bold text-yellow-500 tracking-tight">Rp
                        {{ number_format($myTotalBonus, 0, ',', '.') }}</h2>
                </div>
            </div>

            {{-- FORM FILTER --}}
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-4 mb-6 shadow-lg">
                <form method="GET" action="{{ route('staff.service-harian.kelola-index') }}"
                    class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama/Unit..."
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-sm text-white focus:border-yellow-500 outline-none">
                    </div>
                    <div>
                        <select name="month"
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-sm text-white focus:border-yellow-500 outline-none">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}"
                                    {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <select name="year"
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-sm text-white focus:border-yellow-500 outline-none">
                            @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-yellow-600 text-black py-2 rounded-lg text-[10px] font-bold uppercase hover:bg-yellow-500 transition-all">Filter</button>
                        <a href="{{ route('staff.service-harian.kelola-index') }}"
                            class="flex-1 bg-[#2a2a2a] text-[#9a9a9a] border border-[#4a4a4a] py-2 rounded-lg text-[10px] font-bold uppercase text-center flex items-center justify-center">Reset</a>
                    </div>
                </form>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead
                            class="bg-[#0f0f0f] text-white uppercase text-[10px] tracking-widest border-b border-[#333]">
                            <tr>
                                <th class="px-6 py-4">Unit & Customer</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4">Penyelesaian Service</th>
                                <th class="px-6 py-4 text-center">Pengambilan</th>
                                <th class="px-6 py-4 text-yellow-500 text-right">Fee (30%)</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2a2a2a]">
                            @forelse($services as $svc)
                                <tr class="hover:bg-[#222] transition-all">
                                    <td class="px-6 py-4">
                                        <div class="text-white font-bold text-base uppercase">{{ $svc->merk }}
                                            {{ $svc->tipe }}</div>
                                        <div class="text-[10px] text-[#6a6a6a]">Customer: <span
                                                class="text-[#9a9a9a]">{{ $svc->nama_customer }}</span></div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($svc->status_pengerjaan == 'pending')
                                            <span
                                                class="text-red-500 animate-pulse font-black text-[10px] uppercase italic">●
                                                Menunggu</span>
                                        @elseif($svc->status_pengerjaan == 'proses')
                                            <span class="text-yellow-500 font-black text-[10px] uppercase">●
                                                Diproses</span>
                                        @else
                                            <span class="text-green-500 font-black text-[10px] uppercase">●
                                                Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($svc->status_pengerjaan == 'selesai')
                                            <div class="text-white text-xs font-bold uppercase truncate max-w-[150px]">
                                                {{ $svc->keterangan ?? 'Sudah Dikerjakan' }}</div>
                                            <div class="text-[9px] text-green-500 italic mt-0.5">Notifikasi Terkirim ✅
                                            </div>
                                        @else
                                            <span class="text-[#444] italic text-xs">Menunggu Hasil Akhir...</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($svc->status_pengambilan == 'Sudah diambil')
                                            <span
                                                class="text-blue-400 font-bold text-[10px] uppercase tracking-tighter">✔
                                                Diambil</span>
                                        @else
                                            <span
                                                class="text-red-500 font-bold text-[10px] uppercase tracking-tighter italic">Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-yellow-500">Rp
                                        {{ number_format($svc->fee_staff, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center gap-2">
                                            {{-- BUTTON DETAIL --}}
                                            <button onclick="showDetail('{{ json_encode($svc) }}')"
                                                class="p-2 bg-blue-600/10 text-blue-500 border border-blue-600/30 rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>

                                            {{-- BUTTON WHATSAPP --}}
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $svc->whatsapp) }}"
                                                target="_blank"
                                                class="p-2 bg-green-600/10 text-green-500 border border-green-600/30 rounded-lg hover:bg-green-600 hover:text-white transition-all">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.319 1.592 5.448 0 9.886-4.438 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.438-9.889 9.886-.001 1.93.524 3.767 1.542 5.41l-.982 3.593 3.687-.968.325.192z" />
                                                </svg>
                                            </a>

                                            {{-- PERBAIKAN: TOMBOL UPDATE --}}
                                            @if ($svc->status_pengerjaan != 'selesai' || $svc->status_pengambilan != 'Sudah diambil')
                                                <a href="{{ route('staff.service-harian.edit', $svc->id) }}"
                                                    class="bg-yellow-600 text-black px-4 py-1.5 rounded-lg text-[10px] font-black uppercase hover:bg-yellow-400 transition-all shadow-lg shadow-yellow-900/20">
                                                    Update
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center text-[#444] italic">Belum ada tugas
                                        pengerjaan service bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-8">{{ $services->links() }}</div>
        </div>
    </main>

    {{-- MODAL DETAIL --}}
    {{-- MODAL DETAIL --}}
    <div id="modalDetail" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/80" onclick="closeModal()"></div>
            <div
                class="relative bg-[#1a1a1a] border border-[#4a4a4a] w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-[#333] flex justify-between items-center">
                    <h3 class="serif-font text-xl text-white uppercase tracking-wider">Rincian Lengkap Service</h3>
                    <button onclick="closeModal()" class="text-[#9a9a9a] hover:text-white text-2xl">&times;</button>
                </div>

                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    {{-- Info Utama --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-[9px] text-[#6a6a6a] uppercase font-bold tracking-widest">Customer</p>
                            <p id="det-customer" class="text-white text-xs font-medium"></p>
                        </div>
                        <div>
                            <p class="text-[9px] text-[#6a6a6a] uppercase font-bold tracking-widest">WhatsApp</p>
                            <p id="det-wa" class="text-white text-xs font-medium"></p>
                        </div>
                        <div>
                            <p class="text-[9px] text-[#6a6a6a] uppercase font-bold tracking-widest">Unit</p>
                            <p id="det-unit" class="text-white text-xs font-medium uppercase"></p>
                        </div>
                        <div>
                            <p class="text-[9px] text-[#6a6a6a] uppercase font-bold tracking-widest">Tgl Masuk</p>
                            <p id="det-tgl" class="text-white text-xs font-medium"></p>
                        </div>
                    </div>

                    <div class="h-px bg-[#333]"></div>

                    {{-- Tabel Rincian Biaya --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] text-yellow-500 uppercase font-bold tracking-widest mb-3">Rincian
                                Jasa</p>
                            <div id="det-list-jasa" class="space-y-2 text-[11px]">
                                {{-- Inject by JS --}}
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-400 uppercase font-bold tracking-widest mb-3">Rincian
                                Sparepart</p>
                            <div id="det-list-sparepart" class="space-y-2 text-[11px]">
                                {{-- Inject by JS --}}
                            </div>
                        </div>
                    </div>

                    {{-- Keterangan Teknisi --}}
                    <div class="bg-[#0f0f0f] p-4 rounded-lg border border-[#333]">
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-2">Catatan Teknisi:
                        </p>
                        <p id="det-keterangan" class="text-[#9a9a9a] text-xs leading-relaxed italic"></p>
                    </div>
                </div>

                <div class="p-6 bg-[#0f0f0f] border-t border-[#333] flex justify-between items-center">
                    <div class="text-left">
                        <p class="text-[9px] text-[#6a6a6a] uppercase font-bold">Total Tagihan</p>
                        <p id="det-total" class="text-xl text-white font-bold"></p>
                    </div>
                    <button onclick="closeModal()"
                        class="px-8 py-2 bg-[#2a2a2a] text-white text-xs font-bold uppercase rounded-lg hover:bg-[#333]">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @include('components.admin-footer')

    <script>
        function showDetail(data) {
            const svc = JSON.parse(data);
            const formatter = new Intl.NumberFormat('id-ID');

            // Isi Data Header
            document.getElementById('det-customer').innerText = svc.nama_customer;
            document.getElementById('det-wa').innerText = svc.whatsapp;
            document.getElementById('det-unit').innerText = `${svc.merk} ${svc.tipe || ''}`;
            document.getElementById('det-tgl').innerText = svc.tanggal_masuk;
            document.getElementById('det-keterangan').innerText = svc.keterangan || 'Tidak ada catatan tambahan.';
            document.getElementById('det-total').innerText = 'Rp ' + formatter.format(svc.total_harga);

            // Proses Rincian Jasa
            const jasaContainer = document.getElementById('det-list-jasa');
            jasaContainer.innerHTML = '';
            const rincianJasa = svc.rincian_jasa || [];

            if (rincianJasa.length > 0) {
                rincianJasa.forEach(item => {
                    jasaContainer.innerHTML += `
                <div class="flex justify-between border-b border-[#222] pb-1">
                    <span class="text-gray-400">${item.name}</span>
                    <span class="text-white font-medium">Rp ${formatter.format(item.amount)}</span>
                </div>`;
                });
            } else {
                jasaContainer.innerHTML = '<p class="text-[#444] italic">Tidak ada rincian jasa.</p>';
            }

            // Proses Rincian Sparepart
            const sparepartContainer = document.getElementById('det-list-sparepart');
            sparepartContainer.innerHTML = '';
            const rincianSparepart = svc.rincian_sparepart || [];

            if (rincianSparepart.length > 0) {
                rincianSparepart.forEach(item => {
                    sparepartContainer.innerHTML += `
                <div class="flex justify-between border-b border-[#222] pb-1">
                    <span class="text-gray-400">${item.name}</span>
                    <span class="text-white font-medium">Rp ${formatter.format(item.amount)}</span>
                </div>`;
                });
            } else {
                sparepartContainer.innerHTML = '<p class="text-[#444] italic">Tidak ada rincian sparepart.</p>';
            }

            // Tampilkan Modal
            document.getElementById('modalDetail').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalDetail').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</body>

</html>
