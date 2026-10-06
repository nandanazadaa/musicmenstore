<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Harian - Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <div class="w-full px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="serif-font text-3xl text-white mb-2">Service Harian</h1>
                    <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-32"></div>
                </div>
                <a href="{{ route('staff.service-harian.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all uppercase tracking-wider text-sm">Tambah Data</a>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-900/20 border border-green-500/50 rounded-lg text-sm text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 mb-4">
                <form method="GET" action="{{ route('staff.service-harian.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari customer, merk, tipe, dll..."
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        </div>

                        <!-- Filter Status Pengerjaan -->
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2">Status Pengerjaan</label>
                            <select name="status_pengerjaan" 
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                                <option value="">Semua</option>
                                <option value="proses" {{ request('status_pengerjaan') == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ request('status_pengerjaan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <!-- Filter Instrumen -->
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2">Instrumen</label>
                            <select name="instrumen" 
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                                <option value="">Semua</option>
                                <option value="electric" {{ request('instrumen') == 'electric' ? 'selected' : '' }}>Electric Guitar</option>
                                <option value="acoustic" {{ request('instrumen') == 'acoustic' ? 'selected' : '' }}>Acoustic Guitar</option>
                                <option value="bass" {{ request('instrumen') == 'bass' ? 'selected' : '' }}>Bass</option>
                                <option value="effect" {{ request('instrumen') == 'effect' ? 'selected' : '' }}>Effect</option>
                                <option value="amplifier" {{ request('instrumen') == 'amplifier' ? 'selected' : '' }}>Amplifier</option>
                            </select>
                        </div>

                        <!-- Filter Date From -->
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2">Tanggal Dari</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        </div>

                        <!-- Filter Date To -->
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2">Tanggal Sampai</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" 
                                class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider text-sm">
                            Filter
                        </button>
                        <a href="{{ route('staff.service-harian.index') }}" 
                           class="bg-transparent border border-red-600 text-red-400 px-6 py-2 rounded-lg hover:bg-red-900/20 hover:border-red-500 transition-all uppercase tracking-wider text-sm">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#4a4a4a] text-[#9a9a9a] uppercase tracking-wider text-xs">
                            <th class="py-3 px-3 text-left">Tanggal Masuk</th>
                            <th class="py-3 px-3 text-left">Customer</th>
                            <th class="py-3 px-3 text-left">Merk / Tipe</th>
                            <th class="py-3 px-3 text-left">Instrumen</th>
                            <th class="py-3 px-3 text-left">Jenis Service</th>
                            <th class="py-3 px-3 text-left">Biaya Sparepart</th>
                            <th class="py-3 px-3 text-left">Fee (Biaya Jasa)</th>
                            <th class="py-3 px-3 text-left">Total</th>
                            <th class="py-3 px-3 text-left">Status</th>
                            <th class="py-3 px-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="servicesTableBody">
                        @forelse($services as $svc)
                            <tr class="border-b border-[#2a2a2a] hover:bg-[#2a2a2a] service-row" data-id="{{ $svc->id }}">
                                <td class="py-3 px-3">{{ \Carbon\Carbon::parse($svc->tanggal_masuk)->format('d M Y') }}</td>
                                <td class="py-3 px-3">{{ $svc->nama_customer }}</td>
                                <td class="py-3 px-3">{{ $svc->merk }} @if($svc->tipe) / {{ $svc->tipe }} @endif</td>
                                <td class="py-3 px-3">{{ $svc->instrumen }}</td>
                                <td class="py-3 px-3">{{ $svc->jenis_service }}</td>
                                <td class="py-3 px-3">Rp {{ number_format($svc->biaya_sparepart,0,',','.') }}</td>
                                <td class="py-3 px-3 fee-cell" data-fee="{{ $svc->biaya_jasa }}">
                                    <span class="fee-value">Rp {{ number_format($svc->biaya_jasa,0,',','.') }}</span>
                                </td>
                                <td class="py-3 px-3 total-cell">Rp {{ number_format($svc->total_harga,0,',','.') }}</td>
                                <td class="py-3 px-3">
                                    @if($svc->status_pengerjaan === 'selesai')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-900/30 border border-green-600 text-green-300">Selesai</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-900/30 border border-yellow-600 text-yellow-300">Proses</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2">
                                        @if($svc->whatsapp)
                                        <button onclick="sendWhatsApp({{ $svc->id }}, '{{ $svc->whatsapp }}', '{{ addslashes($svc->nama_customer) }}', '{{ addslashes($svc->merk) }}', '{{ addslashes($svc->tipe ?? '') }}', '{{ addslashes($svc->instrumen) }}', '{{ addslashes($svc->jenis_service) }}', '{{ \Carbon\Carbon::parse($svc->tanggal_masuk)->format('d M Y') }}', '{{ $svc->status_pengerjaan }}')" 
                                                class="text-green-400 hover:text-green-300 transition-colors" 
                                                title="Kirim WhatsApp ke Customer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                        </button>
                                        @endif
                                        <a href="{{ route('staff.service-harian.edit', $svc->id) }}" 
                                           class="text-yellow-400 hover:text-yellow-300 transition-colors" 
                                           title="Edit Status">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-[#9a9a9a]">Belum ada data instrumen masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $services->links() }}</div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        // Format phone number for WhatsApp
        function formatPhoneForWhatsApp(phone) {
            // Remove all non-numeric characters
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            // If starts with 0, replace with 62 (Indonesia country code)
            const formattedPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
            // Remove leading 62 if already has it
            return formattedPhone.startsWith('62') ? formattedPhone : '62' + formattedPhone;
        }

        // Send WhatsApp message
        function sendWhatsApp(id, phone, namaCustomer, merk, tipe, instrumen, jenisService, tanggalMasuk, statusPengerjaan) {
            if (!phone) {
                alert('Nomor WhatsApp tidak tersedia');
                return;
            }

            // Format phone number
            const formattedPhone = formatPhoneForWhatsApp(phone);
            
            // Get message template from server
            fetch('{{ route("staff.service-harian.get-whatsapp-message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    nama_customer: namaCustomer,
                    merk: merk,
                    tipe: tipe || '',
                    instrumen: instrumen,
                    jenis_service: jenisService,
                    tanggal_masuk: tanggalMasuk,
                    status_pengerjaan: statusPengerjaan === 'selesai' ? 'Selesai' : 'Proses'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.message) {
                    // Encode message for URL
                    const encodedMessage = encodeURIComponent(data.message);
                    
                    // Open WhatsApp
                    const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodedMessage}`;
                    window.open(whatsappUrl, '_blank');
                } else {
                    alert('Error: ' + (data.message || 'Gagal mendapatkan template pesan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil template pesan');
            });
        }

        // Real-time update untuk fee
        let lastUpdateTime = '{{ $services->count() > 0 ? $services->first()->updated_at->toIso8601String() : now()->toIso8601String() }}';
        
        function updateFees() {
            fetch('{{ route("staff.service-harian.api.fees") }}?last_update=' + encodeURIComponent(lastUpdateTime))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.updates && data.updates.length > 0) {
                        data.updates.forEach(update => {
                            const row = document.querySelector(`tr.service-row[data-id="${update.id}"]`);
                            if (row) {
                                const feeCell = row.querySelector('.fee-cell');
                                const feeValue = row.querySelector('.fee-value');
                                if (feeCell && feeValue) {
                                    feeValue.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(update.biaya_jasa || 0);
                                    feeCell.setAttribute('data-fee', update.biaya_jasa || 0);
                                    
                                    // Update total
                                    const totalCell = row.querySelector('.total-cell') || feeCell.parentElement.nextElementSibling;
                                    if (totalCell) {
                                        const sparepart = parseFloat(update.biaya_sparepart || 0);
                                        const fee = parseFloat(update.biaya_jasa || 0);
                                        const total = sparepart + fee;
                                        totalCell.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                                    }
                                }
                            }
                        });
                        lastUpdateTime = data.last_update;
                    }
                })
                .catch(error => console.error('Error updating fees:', error));
        }

        // Polling realtime dinonaktifkan untuk mencegah request API berulang.
        // Muat ulang halaman untuk melihat fee terbaru.
    </script>
</body>
</html>
