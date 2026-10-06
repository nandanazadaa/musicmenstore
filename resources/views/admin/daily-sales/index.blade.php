<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Form Penjualan Harian - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html,
        body {
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
            <!-- Page Header -->
            <div class="mb-8 w-full">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Form Penjualan Harian
                        </h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                        <p class="text-[#9a9a9a] mt-2 text-sm">Data form penjualan harian dari semua staff</p>
                    </div>
                    <a href="{{ route('admin.daily-sales.export') }}"
                        class="bg-transparent border border-green-600 text-green-400 px-6 py-3 rounded-lg hover:bg-green-900/20 hover:border-green-500 transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>

            <p class="text-yellow-600 text-[10px] font-bold uppercase mt-1 tracking-widest italic mb-3">
                Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            </p> 

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div
                    class="bg-[#1a1a1a] border border-[#333] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-white/5 text-6xl font-black italic">SALES</div>
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mb-1">Total Penjualan Bruto
                    </p>
                    <h3 class="text-3xl font-black text-white">Rp
                        {{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
                    <p class="text-[9px] text-gray-600 mt-1 italic">Offline + Online</p>
                </div>

                <div
                    class="bg-[#1a1a1a] border border-[#333] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-green-500/5 text-6xl font-black italic">DEPOSIT</div>
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mb-1">Total Cash Deposit</p>
                    <h3 class="text-3xl font-black text-green-500">Rp
                        {{ number_format($stats['total_deposit'], 0, ',', '.') }}</h3>
                </div>

                <div
                    class="bg-[#1a1a1a] border border-[#333] p-6 rounded-[2rem] shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-blue-500/5 text-6xl font-black italic">FORMS</div>
                    <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mb-1">Jumlah Form Masuk</p>
                    <h3 class="text-3xl font-black text-white">{{ $stats['total_laporan'] }} <span
                            class="text-sm text-gray-600 italic">Laporan</span></h3>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-6 mb-6">
                <form method="GET" action="{{ route('admin.daily-sales.index') }}"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Pilih Staff</label>
                        <select name="staff_id"
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm outline-none">
                            <option value="">Semua Staff</option>
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}"
                                    {{ request('staff_id') == $staff->id ? 'selected' : '' }}>{{ $staff->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Shift</label>
                        <select name="shift"
                            class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm outline-none">
                            <option value="">Semua Shift</option>
                            <option value="pagi" {{ request('shift') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                            <option value="siang" {{ request('shift') == 'siang' ? 'selected' : '' }}>Siang</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-xs transition-all">FILTER</button>
                        <a href="{{ route('admin.daily-sales.index') }}"
                            class="flex-1 bg-[#2a2a2a] border border-[#444] text-white font-bold py-2 rounded-lg text-xs text-center transition-all">RESET</a>
                    </div>
                </form>
            </div>

            <!-- Daily Sales Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Date
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Staff
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Shift
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Total
                                    Sales</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Cash
                                    Deposit</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailySales as $sale)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4 text-white text-sm">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $sale->staff->nama }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ ucfirst($sale->shift ?? '-') }}</td>
                                    <td class="py-3 px-4 text-white text-sm">
                                        @php
                                            // Rumus Benar: Offline + Online
                                            $grandTotal = $sale->total_offline_sales + $sale->total_online_sales;
                                        @endphp
                                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">Rp
                                        {{ number_format($sale->total_cash_deposit, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.daily-sales.show', $sale->id) }}"
                                                class="text-blue-400 hover:text-blue-300 transition-colors"
                                                title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.daily-sales.edit', $sale->id) }}"
                                                class="text-yellow-400 hover:text-yellow-300 transition-colors"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                    </path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <button onclick="deleteDailySale({{ $sale->id }})"
                                                class="text-red-400 hover:text-red-300 transition-colors"
                                                title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-[#9a9a9a]">No daily sales forms
                                        found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($dailySales->hasPages())
                    <div class="mt-6 flex items-center justify-center">
                        {{ $dailySales->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        // Show SweetAlert for session messages
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('
                            success ') }}',
                icon: 'success',
                confirmButtonColor: '#10b981',
                background: '#1a1a1a',
                color: '#ffffff',
                customClass: {
                    popup: 'swal2-dark',
                    title: 'swal2-title-dark',
                    content: 'swal2-content-dark',
                    confirmButton: 'swal2-confirm-dark'
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('
                            error ') }}',
                icon: 'error',
                confirmButtonColor: '#ef4444',
                background: '#1a1a1a',
                color: '#ffffff',
                customClass: {
                    popup: 'swal2-dark',
                    title: 'swal2-title-dark',
                    content: 'swal2-content-dark',
                    confirmButton: 'swal2-confirm-dark'
                }
            });
        @endif

        // Delete function
        function deleteDailySale(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus form penjualan harian ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                background: '#1a1a1a',
                color: '#ffffff',
                customClass: {
                    popup: 'swal2-dark',
                    title: 'swal2-title-dark',
                    content: 'swal2-content-dark',
                    confirmButton: 'swal2-confirm-dark',
                    cancelButton: 'swal2-cancel-dark'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/daily-sales/${id}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Real-time updates for daily sales
        (function() {
            let lastUpdate = new Date().toISOString().slice(0, 19).replace('T', ' ');
            const dailySalesMap = new Map();
            const tbody = document.getElementById('dailySalesTableBody');

            // Initialize existing daily sales
            @foreach ($dailySales as $sale)
                dailySalesMap.set({
                    {
                        $sale - > id
                    }
                }, true);
            @endforeach

            function createDailySaleRow(dailySale) {
                const row = document.createElement('tr');
                row.className = 'border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors';
                row.setAttribute('data-sale-id', dailySale.id);
                row.innerHTML = `
                    <td class="py-3 px-4 text-white text-sm">${dailySale.sale_date}</td>
                    <td class="py-3 px-4 text-white text-sm">${dailySale.staff_name}</td>
                    <td class="py-3 px-4 text-white text-sm">${dailySale.shift}</td>
                    <td class="py-3 px-4 text-white text-sm">Rp ${dailySale.total_sales}</td>
                    <td class="py-3 px-4 text-white text-sm">Rp ${dailySale.cash_deposit}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <a href="/admin/daily-sales/${dailySale.id}" 
                               class="text-blue-400 hover:text-blue-300 transition-colors" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
                            <a href="/admin/daily-sales/${dailySale.id}/edit" 
                               class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </a>
                            <button onclick="deleteDailySale(${dailySale.id})" 
                                    class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                `;
                return row;
            }

            function checkForNewDailySales() {
                fetch(`{{ route('admin.daily-sales.api.new') }}?last_update=${encodeURIComponent(lastUpdate)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.daily_sales && data.daily_sales.length > 0) {
                            data.daily_sales.forEach(dailySale => {
                                if (!dailySalesMap.has(dailySale.id)) {
                                    // New daily sale - add to top of table
                                    const row = createDailySaleRow(dailySale);
                                    if (tbody && tbody.firstChild) {
                                        tbody.insertBefore(row, tbody.firstChild);
                                    } else if (tbody) {
                                        tbody.appendChild(row);
                                    }
                                    dailySalesMap.set(dailySale.id, true);

                                    // Show notification
                                    showNotification(
                                        `Form penjualan harian baru dari ${dailySale.staff_name}`,
                                        'info');
                                } else {
                                    // Update existing row
                                    const existingRow = document.querySelector(
                                        `tr[data-sale-id="${dailySale.id}"]`);
                                    if (existingRow) {
                                        const newRow = createDailySaleRow(dailySale);
                                        existingRow.replaceWith(newRow);
                                    }
                                }
                            });
                        }
                        lastUpdate = data.last_update;
                    })
                    .catch(error => {
                        console.error('Error checking for new daily sales:', error);
                    });
            }

            function showNotification(message, type = 'info') {
                const icon = type === 'info' ? 'info' : type === 'success' ? 'success' : 'error';
                Swal.fire({
                    title: message,
                    icon: icon,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#1a1a1a',
                    color: '#ffffff',
                    customClass: {
                        popup: 'swal2-dark',
                        title: 'swal2-title-dark',
                    }
                });
            }

            // Polling realtime dinonaktifkan untuk mencegah request API berulang.
            // Muat ulang halaman untuk melihat penjualan terbaru.
        })();
    </script>
</body>

</html>
