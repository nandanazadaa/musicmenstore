<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Salary Slip Detail - Musicmen Admin</title>
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
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Salary Slip Detail</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @if ($salarySlip->staff->nomor_telepon)
                            <button onclick="sendAutoWA({{ $salarySlip->id }})" id="btn-send-wa"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                <span id="text-wa">Kirim via WhatsApp (Auto)</span>
                            </button>
                        @endif
                        <a href="{{ route('admin.payroll.download', $salarySlip->id) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                            Download PDF
                        </a>
                        <a href="{{ route('admin.payroll.index') }}"
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">

                {{-- Staff Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-[#9a9a9a] mb-1">Staff</p>
                        <p class="text-white text-lg">{{ $salarySlip->staff->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#9a9a9a] mb-1">Month</p>
                        <p class="text-white text-lg">
                            {{ \Carbon\Carbon::parse($salarySlip->month . '-01')->format('F Y') }}</p>
                    </div>
                </div>

                {{-- Earnings --}}
                <div class="border-t border-[#4a4a4a] pt-6">
                    <h2 class="text-xl text-white mb-4">Earnings</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Basic Salary</span>
                            <span class="text-white">Rp
                                {{ number_format($salarySlip->basic_salary, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Transport & Food Allowance</span>
                            <span class="text-white">Rp
                                {{ number_format($salarySlip->transport_food_allowance, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Overtime Fee</span>
                            <span class="text-white">Rp
                                {{ number_format($salarySlip->overtime_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Sales Fee</span>
                            <span class="text-white">Rp {{ number_format($salarySlip->sales_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Service Fee</span>
                            <span class="text-white">Rp
                                {{ number_format($salarySlip->service_fee, 0, ',', '.') }}</span>
                        </div>

                        {{-- Additional Fee: tampilkan per item jika ada detail --}}
                        @if ($salarySlip->others_fee > 0)
                            @if ($salarySlip->others_fee_details && count($salarySlip->others_fee_details) > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-[#9a9a9a]">Additional Fee</span>
                                    <span class="text-white">Rp
                                        {{ number_format($salarySlip->others_fee, 0, ',', '.') }}</span>
                                </div>
                                {{-- Breakdown per item --}}
                                <div class="ml-4 space-y-1 border-l border-[#3a3a3a] pl-4">
                                    @foreach ($salarySlip->others_fee_details as $item)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-[#6a6a6a]">↳ {{ $item['name'] }}</span>
                                            <span class="text-[#9a9a9a]">Rp
                                                {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex justify-between">
                                    <span class="text-[#9a9a9a]">Additional Fee</span>
                                    <span class="text-white">Rp
                                        {{ number_format($salarySlip->others_fee, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        @endif

                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Insentif Kinerja ({{ $salarySlip->total_points ?? 0 }}
                                point)</span>
                            <span class="text-white">Rp
                                {{ number_format($salarySlip->performance_incentive ?? 0, 0, ',', '.') }}</span>
                        </div>

                        {{-- Gross Total --}}
                        <div class="flex justify-between border-t border-[#4a4a4a] pt-3 mt-3">
                            <span class="text-white font-semibold text-lg">Total Penghasilan</span>
                            <span class="text-white font-semibold text-lg">Rp
                                {{ number_format($salarySlip->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Cash Advance --}}
                @if (($salarySlip->cash_advance ?? 0) > 0)
                    <div class="border-t border-[#4a4a4a] pt-6 mt-6">
                        <h2 class="text-xl text-white mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Potongan
                        </h2>
                        <div class="space-y-3">
                            <div
                                class="flex justify-between items-start bg-yellow-900/20 border border-yellow-700/40 rounded-lg px-4 py-3">
                                <div>
                                    <span class="text-yellow-400 font-medium">Cash Advance</span>
                                    @if ($salarySlip->cash_advance_notes)
                                        <p class="text-xs text-[#9a9a9a] mt-1">{{ $salarySlip->cash_advance_notes }}
                                        </p>
                                    @endif
                                </div>
                                <span class="text-yellow-400 font-semibold">(Rp
                                    {{ number_format($salarySlip->cash_advance, 0, ',', '.') }})</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Take-Home Pay --}}
                <div class="border-t border-[#4a4a4a] pt-6 mt-6">
                    <div
                        class="flex justify-between items-center bg-green-900/20 border border-green-700/40 rounded-lg px-4 py-4">
                        <div>
                            <span class="text-green-400 font-bold text-xl">Take-Home Pay</span>
                            @if (($salarySlip->cash_advance ?? 0) > 0)
                                <p class="text-xs text-[#6a6a6a] mt-1">Setelah potongan Cash Advance</p>
                            @endif
                        </div>
                        <span class="text-green-400 font-bold text-xl">Rp
                            {{ number_format($salarySlip->take_home_pay, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Bank Information --}}
                <div class="border-t border-[#4a4a4a] pt-6 mt-6">
                    <h2 class="text-xl text-white mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Informasi Pembayaran (Bank)
                    </h2>
                    <div class="bg-[#0f0f0f] border border-[#3a3a3a] rounded-lg p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-[#6a6a6a] uppercase tracking-wider mb-1">Nama Bank</p>
                                <p class="text-white font-medium">
                                    {{ $salarySlip->bank_name ?? ($salarySlip->staff->bank_name ?? '-') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-[#6a6a6a] uppercase tracking-wider mb-1">Nomor Rekening</p>
                                <p class="text-white font-medium">
                                    {{ $salarySlip->bank_account ?? ($salarySlip->staff->bank_account ?? '-') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-[#6a6a6a] uppercase tracking-wider mb-1">Nama Pemilik</p>
                                <p class="text-white font-medium">
                                    {{ $salarySlip->account_name ?? ($salarySlip->staff->account_name ?? '-') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bank Information --}}

            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        function formatPhoneForWhatsApp(phone) {
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            const formattedPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
            return formattedPhone.startsWith('62') ? formattedPhone : '62' + formattedPhone;
        }

        function sendAutoWA(slipId) {
            const btn = document.getElementById('btn-send-wa');
            const text = document.getElementById('text-wa');

            // Loading state
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            text.innerText = 'Mengirim...';

            fetch(`/admin/payroll/${slipId}/send-auto-wa`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success');
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    text.innerText = 'Kirim via WhatsApp (Auto)';
                });
        }
    </script>
</body>

</html>
