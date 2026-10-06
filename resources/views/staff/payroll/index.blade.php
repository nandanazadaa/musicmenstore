<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Slip Gaji - Musicmen Staff</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Slip Gaji</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                    </div>
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
                <form method="GET" action="{{ route('staff.payroll.index') }}"
                    class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Month</label>
                        <input type="month" name="month" value="{{ request('month') }}"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a]">
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
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Month
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Total
                                    Penghasilan</th>
                                <th
                                    class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider text-yellow-500">
                                    Potongan (Kasbon)</th>
                                <th
                                    class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider text-green-500">
                                    Take-Home Pay</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salarySlips as $slip)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4 text-white text-sm">
                                        {{ \Carbon\Carbon::parse($slip->month . '-01')->format('F Y') }}</td>
                                    <td class="py-3 px-4 text-[#9a9a9a] text-sm">Rp
                                        {{ number_format($slip->total, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-yellow-500/80 text-sm">
                                        {{ $slip->cash_advance > 0 ? '(Rp ' . number_format($slip->cash_advance, 0, ',', '.') . ')' : '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-green-400 text-sm font-bold">
                                        Rp {{ number_format($slip->take_home_pay, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('staff.payroll.show', $slip->id) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs uppercase transition-all">
                                                View
                                            </a>
                                            <a href="{{ route('staff.payroll.download', $slip->id) }}"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs uppercase transition-all">
                                                PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-4 text-center text-[#9a9a9a] text-sm">
                                        Belum ada slip gaji
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
</body>

</html>
