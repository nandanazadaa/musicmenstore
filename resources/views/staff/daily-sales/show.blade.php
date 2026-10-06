<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Form Penjualan Harian Detail - Musicmen</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Form Penjualan Harian Detail</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('staff.daily-sales.index') }}"
                        class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Back
                    </a>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 space-y-6">
                <div>
                    <p class="text-sm text-[#9a9a9a] mb-1">Date</p>
                    <p class="text-white text-lg">{{ \Carbon\Carbon::parse($dailySale->sale_date)->format('d M Y') }}</p>
                </div>

                <div>
                    <p class="text-sm text-[#9a9a9a] mb-1">Total Sales</p>
                    <p class="text-white text-lg">
                        @php
                        // Rumus Benar: Offline + Online
                        $grandTotal = $dailySale->total_offline_sales + $dailySale->total_online_sales;
                        @endphp
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-[#9a9a9a] mb-1">Cash Deposit</p>
                    <p class="text-white text-lg">Rp {{ number_format($dailySale->total_cash_deposit, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>

</html>