<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Payroll / Slip Gaji - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Payroll / Slip Gaji</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                    </div>
                    <a href="{{ route('admin.payroll.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                        Create Salary Slip
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 mb-6">
                <form method="GET" action="{{ route('admin.payroll.index') }}"
                    class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Month</label>
                        <input type="month" name="month" value="{{ request('month') }}"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a]">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Staff</label>
                        <select name="staff_id"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a]">
                            <option value="">All Staff</option>
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}"
                                    {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Salary Slips Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Staff
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Month
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Gross
                                    Total</th>
                                <th
                                    class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider text-yellow-500">
                                    Potongan</th>
                                <th
                                    class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider text-green-500">
                                    THP (Net)</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salarySlips as $slip)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4 text-white text-sm">
                                        <div class="font-medium">{{ $slip->staff->nama }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">
                                        {{ \Carbon\Carbon::parse($slip->month . '-01')->format('F Y') }}</td>
                                    <td class="py-3 px-4 text-[#9a9a9a] text-sm">Rp
                                        {{ number_format($slip->total, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-yellow-500/80 text-sm">
                                        @if ($slip->cash_advance > 0)
                                            (Rp {{ number_format($slip->cash_advance, 0, ',', '.') }})
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-green-400 text-sm font-bold">
                                        Rp {{ number_format($slip->take_home_pay, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.payroll.show', $slip->id) }}"
                                                class="text-blue-400 hover:text-blue-300 transition-colors"
                                                title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            {{-- <a href="{{ route('admin.payroll.edit', $slip->id) }}" 
                                               class="text-yellow-500 hover:text-yellow-400 transition-colors" title="Edit Slip">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a> --}}
                                            <button onclick="sendAutoWA({{ $slip->id }})"
                                                class="text-green-500 hover:text-green-400 p-1" title="Kirim WA">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="currentColor">
                                                    <path
                                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                                </svg>
                                            </button>

                                            <button type="button"
                                                onclick="confirmDeleteSlip('{{ $slip->id }}', '{{ $slip->staff->nama }}', '{{ $slip->month }}')"
                                                class="text-red-500 hover:text-red-400 transition-colors"
                                                title="Hapus Slip">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>


                                            <form id="delete-form-{{ $slip->id }}"
                                                action="{{ route('admin.payroll.destroy', $slip->id) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-[#9a9a9a]">No salary slips found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    @if (session('error'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menambah Data',
                text: "{!! session('error') !!}",
                background: '#1a1a1a',
                color: '#fff'
            });
        </script>
    @endif
    <script>
        function confirmDeleteSlip(id, name, month) {
            const formattedMonth = new Date(month + '-01').toLocaleDateString('id-ID', {
                month: 'long',
                year: 'numeric'
            });

            Swal.fire({
                title: 'Hapus Slip Gaji?',
                text: `Anda akan menghapus slip gaji ${name} periode ${formattedMonth}. Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // red-500
                cancelButtonColor: '#4b5563', // gray-600
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#1a1a1a',
                color: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function formatPhoneForWhatsApp(phone) {
            // Remove all non-numeric characters
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            // If starts with 0, replace with 62 (Indonesia country code)
            const formattedPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
            // Remove leading 62 if already has it
            return formattedPhone.startsWith('62') ? formattedPhone : '62' + formattedPhone;
        }

        function sendSalarySlipViaWhatsApp(slipId, phone, staffName, month, total) {
            // Format phone number
            const formattedPhone = formatPhoneForWhatsApp(phone);

            // Generate download link
            const downloadLink = window.location.origin + '{{ route('admin.payroll.download', ':id') }}'.replace(':id',
                slipId);

            // Create message
            const message = `Halo ${staffName} 👋

Slip Gaji Anda untuk periode *${month}* sudah siap.

*Rincian:*
• Periode: ${month}
• Total Gaji: Rp ${total}

Silakan download slip gaji Anda melalui link berikut:
${downloadLink}

Terima kasih! 🙏`;

            // Encode message for URL
            const encodedMessage = encodeURIComponent(message);

            // Open WhatsApp
            const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodedMessage}`;
            window.open(whatsappUrl, '_blank');
        }
    </script>
</body>

</html>
