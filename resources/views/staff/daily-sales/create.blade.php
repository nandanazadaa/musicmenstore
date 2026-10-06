<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Form Penjualan Harian - Musicmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body { overflow-x: hidden; max-width: 100vw; }
        * { box-sizing: border-box; }
        .input-disabled { background-color: #1a1a1a !important; color: #6a6a6a !important; cursor: not-allowed; }
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Form Penjualan Harian</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('staff.daily-sales.index') }}"
                        class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Back
                    </a>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 mb-6">
                <h2 class="text-lg text-white mb-4 font-semibold">Catatan Pengisian Form</h2>
                <ul class="list-disc list-inside space-y-2 text-sm text-[#9a9a9a]">
                    <li>Isi kolom dengan baik dan benar.</li>
                    <li>Penulisan nominal **hanya angka saja** (Contoh: 99000).</li>
                    <li>Total Penjualan Online akan terisi otomatis dari jumlah Shopee + Tokopedia.</li>
                    <li>Jangan melakukan submit 2x.</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('staff.daily-sales.store') }}" id="dailySalesForm" class="space-y-6">
                @csrf

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Informasi Dasar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="sale_date" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Hari dan Tanggal <span class="text-red-400">*</span></label>
                            <input type="date" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-white transition-all">
                        </div>
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Staff</label>
                            <input type="text" value="{{ $staff->nama }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-[#6a6a6a] cursor-not-allowed" readonly>
                        </div>
                        <div>
                            <label for="shift" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Jadwal Shift <span class="text-red-400">*</span></label>
                            <select id="shift" name="shift" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-white transition-all" required>
                                <option value="">Pilih Shift</option>
                                @foreach ($availableShifts as $shiftTime)
                                    @php $isToday = $todaySchedule && $todaySchedule->shift === $shiftTime->shift; @endphp
                                    <option value="{{ $shiftTime->shift }}" {{ old('shift') == $shiftTime->shift || ($isToday && !old('shift')) ? 'selected' : '' }}>
                                        {{ ucfirst($shiftTime->shift) }} ({{ \Carbon\Carbon::parse($shiftTime->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shiftTime->end_time)->format('H:i') }})
                                        @if($isToday) - Shift Hari Ini @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-[#9a9a9a] mb-2 uppercase">Mulai</label>
                                <input type="time" id="shift_start" name="shift_start" value="{{ old('shift_start') }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white">
                            </div>
                            <div>
                                <label class="block text-xs text-[#9a9a9a] mb-2 uppercase">Selesai</label>
                                <input type="time" id="shift_end" name="shift_end" value="{{ old('shift_end') }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Data Penjualan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-[#0f0f0f] p-4 rounded-lg border border-[#333]">
                            <label for="total_offline_sales" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Total Penjualan Offline <span class="text-red-400">*</span></label>
                            <input type="number" id="total_offline_sales" name="total_offline_sales" value="{{ old('total_offline_sales', 0) }}" class="w-full bg-[#1a1a1a] border border-[#4a4a4a] rounded px-4 py-3 text-white outline-none">
                        </div>
                        <div class="bg-[#0f0f0f] p-4 rounded-lg border border-[#333]">
                            <label class="block text-sm text-blue-400 mb-2 uppercase tracking-wider font-bold">Total Penjualan Online (Auto)</label>
                            <input type="number" id="total_online_sales" name="total_online_sales" value="{{ old('total_online_sales', 0) }}" class="w-full input-disabled border border-[#4a4a4a] rounded px-4 py-3 outline-none" readonly>
                        </div>
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="shopee_sales" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Penjualan Shopee</label>
                                <input type="number" id="shopee_sales" name="shopee_sales" value="{{ old('shopee_sales', 0) }}" class="online-calc w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white outline-none focus:border-orange-500">
                            </div>
                            <div>
                                <label for="tokopedia_sales" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Penjualan Tokopedia</label>
                                <input type="number" id="tokopedia_sales" name="tokopedia_sales" value="{{ old('tokopedia_sales', 0) }}" class="online-calc w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white outline-none focus:border-green-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Metode Pembayaran (Kas)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="cash" class="block text-sm text-[#9a9a9a] mb-2 uppercase uppercase">Tunai (Cash)</label>
                            <input type="number" id="cash" name="cash" value="{{ old('cash', 0) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white outline-none focus:border-white">
                        </div>
                        <div>
                            <label for="qris" class="block text-sm text-[#9a9a9a] mb-2 uppercase uppercase">QRIS</label>
                            <input type="number" id="qris" name="qris" value="{{ old('qris', 0) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white outline-none focus:border-white">
                        </div>
                        <div>
                            <label for="transfer" class="block text-sm text-[#9a9a9a] mb-2 uppercase uppercase">Transfer Bank</label>
                            <input type="number" id="transfer" name="transfer" value="{{ old('transfer', 0) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white outline-none focus:border-white">
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Uang Kas & Setoran</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="cash_for_next_shift" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Kas Shift Berikutnya</label>
                            <input type="number" id="cash_for_next_shift" name="cash_for_next_shift" value="{{ old('cash_for_next_shift', 500000) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white outline-none">
                        </div>
                        <div>
                            <label for="total_cash_deposit" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Total Uang Disetor ke Owner <span class="text-red-400">*</span></label>
                            <input type="number" id="total_cash_deposit" name="total_cash_deposit" value="{{ old('total_cash_deposit', 0) }}" class="w-full bg-[#0f0f0f] border border-white rounded px-4 py-3 text-white outline-none font-bold">
                        </div>
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Keterangan Tambahan</label>
                            <textarea id="notes" name="notes" rows="3" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white outline-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-4">Pernyataan</h2>
                    <textarea name="statement" rows="3" class="w-full bg-[#0f0f0f] border border-[#333] rounded px-4 py-3 text-xs text-[#9a9a9a]" readonly>{{ old('statement', 'Setoran ini saya buat dengan sebaik - baiknya berdasarkan sistem dan realtime penjualan selama shift berlangsung tanpa adanya penambahan, pengurangan, dan rekayasa transaksi penjualan serta dapat dipertanggungjawabkan.') }}</textarea>
                </div>

                <div class="flex justify-end gap-4 pb-12">
                    <button type="submit" id="submitBtn" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-12 py-4 rounded-lg font-bold uppercase tracking-widest transition-all">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shopeeInput = document.getElementById('shopee_sales');
            const tokopediaInput = document.getElementById('tokopedia_sales');
            const totalOnlineInput = document.getElementById('total_online_sales');
            const shiftSelect = document.getElementById('shift');
            const startInput = document.getElementById('shift_start');
            const endInput = document.getElementById('shift_end');
            const form = document.getElementById('dailySalesForm');
            const submitBtn = document.getElementById('submitBtn');

            // 1. Logika Penjumlahan Otomatis Shopee + Tokopedia
            function updateOnlineTotal() {
                const shopee = parseFloat(shopeeInput.value) || 0;
                const tokopedia = parseFloat(tokopediaInput.value) || 0;
                totalOnlineInput.value = shopee + tokopedia;
            }

            shopeeInput.addEventListener('input', updateOnlineTotal);
            tokopediaInput.addEventListener('input', updateOnlineTotal);

            // 2. Logika Auto-fill Jam Shift
            const shiftData = {
                @foreach($availableShifts as $st)
                    "{{ $st->shift }}": { 
                        "start": "{{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}", 
                        "end": "{{ \Carbon\Carbon::parse($st->end_time)->format('H:i') }}" 
                    },
                @endforeach
            };

            shiftSelect.addEventListener('change', function() {
                const val = this.value;
                if(shiftData[val]) {
                    startInput.value = shiftData[val].start;
                    endInput.value = shiftData[val].end;
                }
            });

            // 3. Konfirmasi & Proteksi Submit
            form.addEventListener('submit', function(e) {
                if (form.dataset.confirmed) return true;
                e.preventDefault();

                Swal.fire({
                    title: 'Kirim Laporan?',
                    text: 'Pastikan nominal Shopee & Tokopedia sudah benar.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Cek Kembali',
                    confirmButtonColor: '#16a34a',
                    background: '#1a1a1a',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitBtn.disabled = true;
                        submitBtn.innerText = 'MENGIRIM...';
                        form.dataset.confirmed = "true";
                        form.submit();
                    }
                });
            });
        });

        // Flash Messages
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", background: '#1a1a1a', color: '#fff' });
        @endif
        @if($errors->any())
            Swal.fire({ icon: 'error', title: 'Kesalahan Input', html: 'Cek kembali data Anda.', background: '#1a1a1a', color: '#fff' });
        @endif
    </script>
</body>
</html>