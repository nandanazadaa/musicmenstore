<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Create Salary Slip - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body { overflow-x: hidden; max-width: 100vw; }
        * { box-sizing: border-box; }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">

            <div class="mb-8 w-full">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Create Salary Slip</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('admin.payroll.index') }}"
                        class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Back
                    </a>
                </div>
            </div>

            {{-- ========== ALERTS ========== --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm font-semibold text-red-400 mb-2">⚠ Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside text-sm text-red-400 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm font-semibold text-red-400 mb-1">❌ Gagal menyimpan slip gaji:</p>
                    <p class="text-sm text-red-300 font-mono break-all">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.payroll.store') }}" class="space-y-6" id="payrollForm">
                @csrf

                {{-- ========== BASIC INFO ========== --}}
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="staff_id" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">
                                Staff <span class="text-red-400">*</span>
                            </label>
                            <select id="staff_id" name="staff_id"
                                class="w-full bg-[#0f0f0f] border @error('staff_id') border-red-500 @else border-[#4a4a4a] @enderror rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                required onchange="handleStaffChange()">
                                <option value="">Select Staff</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}"
                                        data-name="{{ $staff->nama }}"
                                        data-salary="{{ $staff->gaji_pokok ?? 0 }}"
                                        data-bank="{{ $staff->bank_name ?? '-' }}"
                                        data-acc-no="{{ $staff->bank_account ?? '-' }}"
                                        data-acc-name="{{ $staff->account_name ?? '-' }}"
                                        {{ old('staff_id') == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->nama }} ({{ $staff->id_employee }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="month" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">
                                Month <span class="text-red-400">*</span>
                            </label>
                            <input type="month" id="month" name="month"
                                value="{{ old('month', date('Y-m')) }}"
                                class="w-full bg-[#0f0f0f] border @error('month') border-red-500 @else border-[#4a4a4a] @enderror rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                required onchange="calculatePreview()">
                        </div>
                    </div>
                </div>

                {{-- ========== BANK INFO (VIEW ONLY) ========== --}}
                <div id="bank-info-panel" class="hidden bg-[#1a1a1a] border border-blue-900/30 rounded-lg p-4 sm:p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <h2 class="text-lg text-white font-medium">Bank Account Details (Transfer To)</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs text-[#6a6a6a] uppercase tracking-wider mb-1">Bank Name</p>
                            <p id="info-bank-name" class="text-white font-semibold">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#6a6a6a] uppercase tracking-wider mb-1">Account Number</p>
                            <p id="info-bank-acc" class="text-white font-semibold">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-[#6a6a6a] uppercase tracking-wider mb-1">Account Holder</p>
                            <p id="info-bank-owner" class="text-white font-semibold">-</p>
                        </div>
                    </div>
                </div>

                {{-- ========== SALARY COMPONENTS ========== --}}
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Salary Components</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="basic_salary" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">
                                Basic Salary <span class="text-red-400">*</span>
                            </label>
                            <input type="number" id="basic_salary" name="basic_salary"
                                value="{{ old('basic_salary', 0) }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                min="0" step="1" required oninput="updateLiveSummary()">
                        </div>
                        <div>
                            <label for="transport_food_allowance" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">
                                Transport & Food Allowance
                            </label>
                            <input type="number" id="transport_food_allowance" name="transport_food_allowance"
                                value="{{ old('transport_food_allowance', 0) }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                min="0" step="1" oninput="updateLiveSummary()">
                        </div>
                        <div>
                            <label for="overtime_fee" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">
                                Overtime Fee
                            </label>
                            <input type="number" id="overtime_fee" name="overtime_fee"
                                value="{{ old('overtime_fee', 0) }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                min="0" step="1" oninput="updateLiveSummary()">
                        </div>
                    </div>
                </div>

                {{-- ========== ADDITIONAL FEE ========== --}}
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl text-white">Additional Fee</h2>
                        <button type="button" onclick="addOthersItem()"
                            class="bg-[#2a2a2a] border border-[#4a4a4a] hover:border-[#6a6a6a] text-white px-4 py-2 rounded-lg text-sm uppercase tracking-wider transition-all flex items-center gap-2">
                            Add Item
                        </button>
                    </div>
                    <div id="others-fee-container" class="space-y-3"></div>
                    <div id="others-fee-empty" class="text-center py-6 text-[#6a6a6a] text-sm">
                        Belum ada fee tambahan.
                    </div>
                </div>

                {{-- ========== CASH ADVANCE ========== --}}
                <div class="bg-[#1a1a1a] border border-yellow-700/50 rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6 flex items-center gap-2">Cash Advance (Potongan)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <span class="absolute left-4 top-11 text-[#6a6a6a] text-sm">Rp</span>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase">Jumlah Cash Advance</label>
                            <input type="number" id="cash_advance" name="cash_advance"
                                value="{{ old('cash_advance', 0) }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:border-yellow-600 transition-all"
                                oninput="updateLiveSummary()">
                        </div>
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase">Keterangan</label>
                            <input type="text" name="cash_advance_notes" value="{{ old('cash_advance_notes') }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- ========== LIVE SUMMARY ========== --}}
                <div class="bg-[#1a1a1a] border border-green-700/50 rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-4">💰 Estimasi Slip Gaji</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-[#9a9a9a]"><span>Gross Total</span><span id="sum-gross">Rp 0</span></div>
                        <div class="flex justify-between text-yellow-400"><span>(-) Cash Advance</span><span id="sum-advance">Rp 0</span></div>
                        <div class="flex justify-between border-t border-[#3a3a3a] pt-2 mt-2">
                            <span class="text-green-400 font-bold text-base">Take-Home Pay</span>
                            <span id="sum-takehome" class="text-green-400 font-bold text-base">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pb-8">
                    <button type="submit" id="submitBtn" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg transition-all uppercase tracking-wider font-bold">
                        Save Salary Slip
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // ── STAFF CHANGE HANDLER ─────────────────────────────────────────────
        function handleStaffChange() {
            const select = document.getElementById('staff_id');
            const selected = select.options[select.selectedIndex];
            const bankPanel = document.getElementById('bank-info-panel');

            if (selected.value) {
                // Fill Basic Salary
                document.getElementById('basic_salary').value = selected.getAttribute('data-salary') || 0;
                
                // Fill View-Only Bank Info
                document.getElementById('info-bank-name').textContent = selected.getAttribute('data-bank');
                document.getElementById('info-bank-acc').textContent = selected.getAttribute('data-acc-no');
                document.getElementById('info-bank-owner').textContent = selected.getAttribute('data-acc-name');
                
                // Show Panel
                bankPanel.classList.remove('hidden');

                calculatePreview();
                updateLiveSummary();
            } else {
                bankPanel.classList.add('hidden');
            }
        }

        // ── DYNAMIC OTHERS FEE ───────────────────────────────────────────────
        let othersFeeIndex = 0;
        function addOthersItem() {
            const container = document.getElementById('others-fee-container');
            document.getElementById('others-fee-empty').classList.add('hidden');
            const div = document.createElement('div');
            div.className = 'others-fee-row flex items-center gap-3';
            div.innerHTML = `
                <input type="text" name="others_fee_items[${othersFeeIndex}][name]" placeholder="Keterangan" class="flex-1 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white text-sm" required>
                <input type="number" name="others_fee_items[${othersFeeIndex}][amount]" placeholder="0" class="w-44 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white text-sm" oninput="updateLiveSummary()" required>
                <button type="button" onclick="this.parentElement.remove(); updateLiveSummary();" class="text-red-400 px-2">X</button>
            `;
            container.appendChild(div);
            othersFeeIndex++;
        }

        // ── LIVE CALCULATION ────────────────────────────────────────────────
        function updateLiveSummary() {
            const basic = Math.round(parseFloat(document.getElementById('basic_salary').value)) || 0;
            const transport = parseFloat(document.getElementById('transport_food_allowance').value) || 0;
            const overtime = parseFloat(document.getElementById('overtime_fee').value) || 0;
            const advance = parseFloat(document.getElementById('cash_advance').value) || 0;
            
            let others = 0;
            document.querySelectorAll('[name*="[amount]"]').forEach(i => others += parseFloat(i.value) || 0);

            const gross = basic + transport + overtime + others;
            const takehome = Math.max(0, gross - advance);

            document.getElementById('sum-gross').textContent = 'Rp ' + gross.toLocaleString('id-ID');
            document.getElementById('sum-advance').textContent = 'Rp ' + advance.toLocaleString('id-ID');
            document.getElementById('sum-takehome').textContent = 'Rp ' + takehome.toLocaleString('id-ID');
        }

        // ── PREVIEW SALES FEE (Logic placeholder) ───────────────────────────
        function calculatePreview() {
            // Logic AJAX untuk preview fee sales/service bisa ditaruh di sini jika diperlukan
        }

        document.getElementById('payrollForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Processing...';
        });
    </script>
</body>
</html>