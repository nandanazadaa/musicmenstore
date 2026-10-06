<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Salary Slip Detail - Musicmen Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body { overflow-x: hidden; max-width: 100vw; }
        * { box-sizing: border-box; }
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
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('staff.payroll.download', $salarySlip->id) }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                            Download PDF
                        </a>
                        <a href="{{ route('staff.payroll.index') }}"
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
                        <p class="text-white text-lg">{{ \Carbon\Carbon::parse($salarySlip->month . '-01')->format('F Y') }}</p>
                    </div>
                </div>

                {{-- Earnings --}}
                <div class="border-t border-[#4a4a4a] pt-6">
                    <h2 class="text-xl text-white mb-4">Earnings</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Basic Salary</span>
                            <span class="text-white">Rp {{ number_format($salarySlip->basic_salary, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Transport & Food Allowance</span>
                            <span class="text-white">Rp {{ number_format($salarySlip->transport_food_allowance, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Overtime Fee</span>
                            <span class="text-white">Rp {{ number_format($salarySlip->overtime_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Sales Fee</span>
                            <span class="text-white">Rp {{ number_format($salarySlip->sales_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Service Fee</span>
                            <span class="text-white">Rp {{ number_format($salarySlip->service_fee, 0, ',', '.') }}</span>
                        </div>

                        {{-- Additional Fee: tampilkan per item jika ada detail --}}
                        @if($salarySlip->others_fee > 0)
                            @if($salarySlip->others_fee_details && count($salarySlip->others_fee_details) > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-[#9a9a9a]">Additional Fee</span>
                                    <span class="text-white">Rp {{ number_format($salarySlip->others_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="ml-4 space-y-1 border-l border-[#3a3a3a] pl-4">
                                    @foreach($salarySlip->others_fee_details as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-[#6a6a6a]">↳ {{ $item['name'] }}</span>
                                        <span class="text-[#9a9a9a]">Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex justify-between">
                                    <span class="text-[#9a9a9a]">Additional Fee</span>
                                    <span class="text-white">Rp {{ number_format($salarySlip->others_fee, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        @endif

                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Insentif Kinerja ({{ $salarySlip->total_points ?? 0 }} point)</span>
                            <span class="text-white">Rp {{ number_format($salarySlip->performance_incentive ?? 0, 0, ',', '.') }}</span>
                        </div>

                        {{-- Gross Total --}}
                        <div class="flex justify-between border-t border-[#4a4a4a] pt-3 mt-3">
                            <span class="text-white font-semibold text-lg">Total Penghasilan</span>
                            <span class="text-white font-semibold text-lg">Rp {{ number_format($salarySlip->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Cash Advance --}}
                @if(($salarySlip->cash_advance ?? 0) > 0)
                <div class="border-t border-[#4a4a4a] pt-6 mt-6">
                    <h2 class="text-xl text-white mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Potongan
                    </h2>
                    <div class="bg-yellow-900/20 border border-yellow-700/40 rounded-lg px-4 py-3 flex justify-between items-start">
                        <div>
                            <span class="text-yellow-400 font-medium">Cash Advance</span>
                            @if($salarySlip->cash_advance_notes)
                            <p class="text-xs text-[#9a9a9a] mt-1">{{ $salarySlip->cash_advance_notes }}</p>
                            @endif
                        </div>
                        <span class="text-yellow-400 font-semibold">(Rp {{ number_format($salarySlip->cash_advance, 0, ',', '.') }})</span>
                    </div>
                </div>
                @endif

                {{-- Take-Home Pay --}}
                <div class="border-t border-[#4a4a4a] pt-6 mt-6">
                    <div class="flex justify-between items-center bg-green-900/20 border border-green-700/40 rounded-lg px-4 py-4">
                        <div>
                            <span class="text-green-400 font-bold text-xl">Take-Home Pay</span>
                            @if(($salarySlip->cash_advance ?? 0) > 0)
                            <p class="text-xs text-[#6a6a6a] mt-1">Setelah potongan Cash Advance</p>
                            @endif
                        </div>
                        <span class="text-green-400 font-bold text-xl">Rp {{ number_format($salarySlip->take_home_pay, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Bank Information --}}
                @if($salarySlip->bank_name || $salarySlip->account_name || $salarySlip->bank_account)
                <div class="border-t border-[#4a4a4a] pt-6 mt-6">
                    <h2 class="text-xl text-white mb-4">Bank Information</h2>
                    <div class="space-y-3">
                        @if($salarySlip->bank_name)
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Bank Name</span>
                            <span class="text-white">{{ $salarySlip->bank_name }}</span>
                        </div>
                        @endif
                        @if($salarySlip->account_name)
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Account Name</span>
                            <span class="text-white">{{ $salarySlip->account_name }}</span>
                        </div>
                        @endif
                        @if($salarySlip->bank_account)
                        <div class="flex justify-between">
                            <span class="text-[#9a9a9a]">Bank Account</span>
                            <span class="text-white">{{ $salarySlip->bank_account }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>