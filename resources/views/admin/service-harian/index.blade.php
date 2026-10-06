<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Service Harian - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        @media (max-width: 640px) {

            .col-penerima,
            .col-teknisi,
            .col-jasa {
                display: none;
            }
        }

        @media (max-width: 768px) {

            .col-penerima,
            .col-teknisi {
                display: none;
            }
        }

        .table-wrapper::-webkit-scrollbar {
            height: 4px;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: #4a4a4a;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-10">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="serif-font text-3xl text-white mb-2 uppercase tracking-widest">Service Harian</h1>
                    <p class="text-[#6a6a6a] text-sm">Monitoring total pendapatan service, pembagian fee, dan status
                        unit.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.service-harian.export') }}"
                        class="bg-transparent border border-green-600 text-green-400 px-4 py-2 rounded-lg hover:bg-green-900/20 text-xs font-bold uppercase flex items-center gap-2">
                        EXPORT EXCEL
                    </a>
                    <a href="{{ route('admin.service-harian.create') }}"
                        class="bg-white text-black px-4 py-2 rounded-lg text-xs font-bold uppercase hover:bg-gray-200">
                        + TAMBAH SERVICE
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-[#1a1a1a] border border-[#333] p-5 rounded-2xl shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-2 -top-2 text-white/5 text-5xl font-black italic">TOTAL</div>
                    <p class="text-[#666] text-[10px] uppercase font-bold tracking-widest mb-1">Total Omset Bruto</p>
                    <h3 class="text-xl font-black text-white">Rp {{ number_format($stats['total_omset'], 0, ',', '.') }}
                    </h3>
                    <p class="text-[9px] text-[#444] mt-1 italic">Incl. Sparepart & Jasa</p>
                </div>

                <div class="bg-[#1a1a1a] border border-[#333] p-5 rounded-2xl shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-2 -top-2 text-white/5 text-5xl font-black italic">WORK</div>
                    <p class="text-[#666] text-[10px] uppercase font-bold tracking-widest mb-1">Jasa & Sparepart</p>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-blue-400">Jasa: Rp
                            {{ number_format($stats['total_jasa'], 0, ',', '.') }}</span>
                        <span class="text-sm font-bold text-gray-400">Part: Rp
                            {{ number_format($stats['total_sparepart'], 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-[#1a1a1a] border border-[#333] p-5 rounded-2xl shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-2 -top-2 text-green-500/5 text-5xl font-black italic">TECH</div>
                    <p class="text-green-900 text-[10px] uppercase font-bold tracking-widest mb-1">Total Fee Teknisi
                        (30%)</p>
                    <h3 class="text-xl font-black text-green-500">Rp
                        {{ number_format($stats['total_fee_tech'], 0, ',', '.') }}</h3>
                    <p class="text-[9px] text-[#444] mt-1 italic">Berdasarkan Biaya Jasa</p>
                </div>

                <div class="bg-[#1a1a1a] border border-[#333] p-5 rounded-2xl shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-2 -top-2 text-blue-500/5 text-5xl font-black italic">FRONT</div>
                    <p class="text-blue-900 text-[10px] uppercase font-bold tracking-widest mb-1">Total Fee Frontdesk
                        (5%)</p>
                    <h3 class="text-xl font-black text-blue-500">Rp
                        {{ number_format($stats['total_fee_front'], 0, ',', '.') }}</h3>
                    <p class="text-[9px] text-[#444] mt-1 italic">Berdasarkan Biaya Jasa</p>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-4 mb-6">
                <form method="GET" action="{{ route('admin.service-harian.index') }}"
                    class="grid grid-cols-2 sm:grid-cols-6 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer/Unit..."
                        class="col-span-2 bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm outline-none">

                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-xs outline-none">

                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-xs outline-none">

                    <select name="status_pengerjaan"
                        class="bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm outline-none">
                        <option value="">Semua Status</option>
                        <option value="proses" {{ request('status_pengerjaan') == 'proses' ? 'selected' : '' }}>Proses
                        </option>
                        <option value="selesai" {{ request('status_pengerjaan') == 'selesai' ? 'selected' : '' }}>
                            Selesai</option>
                    </select>

                    <div class="col-span-2 sm:col-span-1 flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 px-4 py-2 rounded-lg text-white text-xs font-bold hover:bg-blue-700">FILTER</button>
                        <a href="{{ route('admin.service-harian.index') }}"
                            class="flex-1 bg-[#2a2a2a] px-4 py-2 rounded-lg text-white border border-[#4a4a4a] text-xs font-bold text-center flex items-center justify-center">RESET</a>
                    </div>
                </form>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl overflow-hidden shadow-2xl">
                <div class="table-wrapper overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead
                            class="bg-[#0f0f0f] text-[#9a9a9a] uppercase text-[10px] tracking-widest border-b border-[#333]">
                            <tr>
                                <th class="px-4 py-4">Customer & Unit</th>
                                <th class="px-4 py-4 col-penerima">Frontdesk (5%)</th>
                                <th class="px-4 py-4 col-teknisi">Teknisi (30%)</th>
                                <th class="px-4 py-4">Hasil Pengerjaan</th>
                                <th class="px-4 py-4">Total Tagihan</th>
                                <th class="px-4 py-4 text-center">Status</th>
                                <th class="px-4 py-4 text-center">WhatsApp</th>
                                <th class="px-4 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2a2a2a]">
                            @forelse($services as $svc)
                                <tr class="hover:bg-[#222] transition-all group">

                                    <td class="px-4 py-4">
                                        <div class="font-bold text-white uppercase text-xs">{{ $svc->nama_customer }}
                                        </div>
                                        <div class="text-blue-400 text-[10px] font-bold uppercase tracking-tighter">
                                            {{ $svc->merk }} {{ $svc->tipe }}</div>
                                        <div class="text-[#555] text-[9px] mt-0.5">Masuk:
                                            {{ \Carbon\Carbon::parse($svc->tanggal_masuk)->format('d/m/Y') }}</div>
                                    </td>

                                    <td class="px-4 py-4 col-penerima">
                                        <div class="text-white text-[11px]">{{ $svc->penerima ?? '-' }}</div>
                                        <div class="text-[10px] text-blue-500 font-bold">Rp
                                            {{ number_format($svc->fee_penerima, 0, ',', '.') }}</div>
                                    </td>

                                    <td class="px-4 py-4 col-teknisi">
                                        <div class="text-white text-[11px]">{{ $svc->eksekutor ?? '-' }}</div>
                                        <div class="text-[10px] text-green-500 font-bold">Rp
                                            {{ number_format($svc->fee_staff, 0, ',', '.') }}</div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="text-[#9a9a9a] text-[11px] max-w-[150px] truncate"
                                            title="{{ $svc->keterangan }}">
                                            {{ $svc->keterangan ?? 'Belum ada catatan teknisi' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 font-bold text-white text-xs">
                                        Rp {{ number_format($svc->total_harga, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col gap-1 items-center">
                                            @if ($svc->status_pengerjaan == 'selesai')
                                                <span
                                                    class="bg-green-900/20 text-green-500 border border-green-800/30 px-2 py-0.5 rounded text-[9px] font-black uppercase">Selesai</span>
                                            @else
                                                <span
                                                    class="bg-yellow-900/20 text-yellow-500 border border-yellow-800/30 px-2 py-0.5 rounded text-[9px] font-black uppercase animate-pulse">Proses</span>
                                            @endif

                                            @if ($svc->status_pengambilan == 'Sudah diambil')
                                                <span class="text-[8px] text-blue-400 font-bold uppercase italic">Sudah
                                                    Diambil</span>
                                            @else
                                                <span
                                                    class="text-[8px] text-red-500 font-bold uppercase italic tracking-tighter">Belum
                                                    Diambil</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <button
                                            onclick="sendWhatsApp('{{ $svc->id }}', '{{ $svc->whatsapp }}', '{{ $svc->nama_customer }}', '{{ $svc->merk }}', '{{ $svc->tipe }}', '{{ $svc->instrumen }}', '{{ $svc->jenis_service }}', '{{ $svc->tanggal_masuk }}', '{{ $svc->status_pengerjaan }}')"
                                            class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-all shadow-lg shadow-green-900/20">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.319 1.592 5.448 0 9.886-4.438 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.438-9.889 9.886-.001 1.93.524 3.767 1.542 5.41l-.982 3.593 3.687-.968.325.192z" />
                                            </svg>
                                        </button>
                                    </td>

                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.service-harian.edit', $svc->id) }}"
                                                class="text-yellow-500 hover:text-white transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.service-harian.destroy', $svc->id) }}"
                                                method="POST" onsubmit="return confirm('Hapus data service ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-white transition-colors">
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
                                    <td colspan="8" class="py-20 text-center text-[#444] italic">Belum ada data
                                        service harian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $services->links() }}
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        function formatPhoneForWhatsApp(phone) {
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            return cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : (cleanPhone.startsWith('62') ? cleanPhone :
                '62' + cleanPhone);
        }

        function sendWhatsApp(id, phone, namaCustomer, merk, tipe, instrumen, jenisService, tanggalMasuk,
            statusPengerjaan) {
            if (!phone) {
                alert('Nomor WhatsApp tidak tersedia');
                return;
            }
            const formattedPhone = formatPhoneForWhatsApp(phone);

            fetch('{{ route('admin.service-harian.get-whatsapp-message') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id,
                        nama_customer: namaCustomer,
                        merk,
                        tipe: tipe || '',
                        instrumen,
                        jenis_service: jenisService,
                        tanggal_masuk: tanggalMasuk,
                        status_pengerjaan: statusPengerjaan
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        window.open(`https://wa.me/${formattedPhone}?text=${encodeURIComponent(data.message)}`,
                            '_blank');
                    }
                })
                .catch(err => console.error('Error:', err));
        }
    </script>
</body>

</html>
