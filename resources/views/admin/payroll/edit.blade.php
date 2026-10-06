<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Salary Slip - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 w-full">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl text-white mb-2">Edit Salary Slip</h1>
                        <p class="text-[#6a6a6a]">Mengedit slip gaji periode {{ \Carbon\Carbon::parse($salarySlip->month)->format('F Y') }}</p>
                    </div>
                    <a href="{{ route('admin.payroll.index') }}" class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] transition-all uppercase tracking-wider">Back</a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.payroll.update', $salarySlip->id) }}" class="space-y-6" id="editPayrollForm">
                @csrf
                @method('PUT')

                {{-- INFO UNIT --}}
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-[#6a6a6a] uppercase mb-1">Staff Member</label>
                            <div class="text-white font-bold text-lg px-1">{{ $salarySlip->staff->nama }}</div>
                        </div>
                        <div>
                            <label class="block text-sm text-[#6a6a6a] uppercase mb-1">Periode</label>
                            <div class="text-white font-bold text-lg px-1">{{ \Carbon\Carbon::parse($salarySlip->month)->format('F Y') }}</div>
                        </div>
                    </div>
                </div>

                {{-- SALARY COMPONENTS --}}
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Update Salary Components</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="basic_salary" class="block text-sm text-[#9a9a9a] mb-2 uppercase">Basic Salary</label>
                            <input type="number" id="basic_salary" name="basic_salary" value="{{ (int)$salarySlip->basic_salary }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-[#6a6a6a] outline-none">
                        </div>
                        <div>
                            <label for="transport_food_allowance" class="block text-sm text-[#9a9a9a] mb-2 uppercase">Transport & Food</label>
                            <input type="number" id="transport_food_allowance" name="transport_food_allowance" value="{{ (int)$salarySlip->transport_food_allowance }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white outline-none">
                        </div>
                        <div>
                            <label for="overtime_fee" class="block text-sm text-[#9a9a9a] mb-2 uppercase">Overtime Fee</label>
                            <input type="number" id="overtime_fee" name="overtime_fee" value="{{ (int)$salarySlip->overtime_fee }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white outline-none">
                        </div>
                    </div>
                </div>

                {{-- OTHERS FEE --}}
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl text-white">Additional Fee</h2>
                        <button type="button" onclick="addOthersItem()" class="bg-[#2a2a2a] border border-[#4a4a4a] text-white px-4 py-2 rounded-lg text-sm uppercase transition-all">+ Add Item</button>
                    </div>
                    <div id="others-fee-container" class="space-y-3">
                        @foreach($salarySlip->others_fee_details ?? [] as $index => $item)
                        <div class="others-fee-row flex items-center gap-3">
                            <div class="flex-1">
                                <input type="text" name="others_fee_items[{{ $index }}][name]" value="{{ $item['name'] }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white text-sm" required>
                            </div>
                            <div class="w-44">
                                <input type="number" name="others_fee_items[{{ $index }}][amount]" value="{{ (int)$item['amount'] }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white text-sm" oninput="updateLiveSummary()" required>
                            </div>
                            <button type="button" onclick="this.parentElement.remove(); updateLiveSummary()" class="text-red-500 p-2">×</button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- CASH ADVANCE --}}
                <div class="bg-[#1a1a1a] border border-yellow-700/50 rounded-lg p-6">
                    <h2 class="text-xl text-white mb-6">Cash Advance (Kasbon)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="number" id="cash_advance" name="cash_advance" value="{{ (int)$salarySlip->cash_advance }}" class="w-full bg-[#0f0f0f] border border-yellow-600 rounded-lg px-4 py-3 text-white outline-none" oninput="updateLiveSummary()">
                        <input type="text" name="cash_advance_notes" value="{{ $salarySlip->cash_advance_notes }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white" placeholder="Notes...">
                    </div>
                </div>

                {{-- LIVE SUMMARY --}}
                <div class="bg-[#1a1a1a] border border-green-700/50 rounded-lg p-6">
                    <h2 class="text-xl text-white mb-4">💰 Updated Estimation</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-[#9a9a9a]"><span>Manual Gross (Basic+Transport+Overtime+Others)</span><span id="sum-gross">Rp 0</span></div>
                        <div class="flex justify-between text-[#9a9a9a]"><span>System Fees (Sales+Service+Incentive)</span><span>Rp {{ number_format($salarySlip->sales_fee + $salarySlip->service_fee + $salarySlip->performance_incentive, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-yellow-400 border-t border-[#333] pt-2 mt-2"><span>(-) Cash Advance</span><span id="sum-advance">Rp 0</span></div>
                        <div class="flex justify-between border-t border-[#333] pt-2 mt-2">
                            <span class="text-green-400 font-bold text-lg">New Take-Home Pay</span>
                            <span id="sum-takehome" class="text-green-400 font-bold text-lg">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pb-20">
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-black font-bold px-10 py-4 rounded-xl uppercase tracking-widest transition-all">Update Salary Slip</button>
                </div>
            </form>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        let othersIndex = {{ count($salarySlip->others_fee_details ?? []) }};
        const systemFees = {{ $salarySlip->sales_fee + $salarySlip->service_fee + $salarySlip->performance_incentive }};

        function addOthersItem() {
            const container = document.getElementById('others-fee-container');
            const div = document.createElement('div');
            div.className = 'others-fee-row flex items-center gap-3';
            div.innerHTML = `
                <div class="flex-1"><input type="text" name="others_fee_items[${othersIndex}][name]" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white text-sm" required></div>
                <div class="w-44"><input type="number" name="others_fee_items[${othersIndex}][amount]" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white text-sm" oninput="updateLiveSummary()" required></div>
                <button type="button" onclick="this.parentElement.remove(); updateLiveSummary()" class="text-red-500 p-2">×</button>
            `;
            container.appendChild(div);
            othersIndex++;
        }

        function fmt(num) { return 'Rp ' + Math.max(0, num).toLocaleString('id-ID'); }

        function updateLiveSummary() {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            const transport = parseFloat(document.getElementById('transport_food_allowance').value) || 0;
            const overtime = parseFloat(document.getElementById('overtime_fee').value) || 0;
            const advance = parseFloat(document.getElementById('cash_advance').value) || 0;
            
            let others = 0;
            document.querySelectorAll('[name*="[amount]"]').forEach(i => others += parseFloat(i.value) || 0);

            const manualGross = basic + transport + overtime + others;
            const totalTakeHome = (manualGross + systemFees) - advance;

            document.getElementById('sum-gross').textContent = fmt(manualGross);
            document.getElementById('sum-advance').textContent = fmt(advance);
            document.getElementById('sum-takehome').textContent = fmt(totalTakeHome);
        }

        ['basic_salary', 'transport_food_allowance', 'overtime_fee', 'cash_advance'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateLiveSummary);
        });

        window.onload = updateLiveSummary;
    </script>
</body>
</html>