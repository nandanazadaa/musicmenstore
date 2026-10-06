<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Form Penjualan Harian - Musicmen Admin</title>
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
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Edit Form Penjualan Harian</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('admin.daily-sales.index') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Back
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.daily-sales.update', $dailySale->id) }}" id="editForm" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Informasi Dasar</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="sale_date" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Tanggal</label>
                            <input type="date" id="sale_date" name="sale_date" value="{{ old('sale_date', $dailySale->sale_date->format('Y-m-d')) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        </div>

                        <div>
                            <label for="shift" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Shift</label>
                            <select id="shift" name="shift" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                                <option value="pagi" {{ old('shift', $dailySale->shift) == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                <option value="siang" {{ old('shift', $dailySale->shift) == 'siang' ? 'selected' : '' }}>Siang</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sales Information -->
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Informasi Penjualan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="total_offline_sales" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Total Offline Sales</label>
                            <input type="number" id="total_offline_sales" name="total_offline_sales" value="{{ old('total_offline_sales', $dailySale->total_offline_sales) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>

                        <div>
                            <label for="total_online_sales" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Total Online Sales</label>
                            <input type="number" id="total_online_sales" name="total_online_sales" value="{{ old('total_online_sales', $dailySale->total_online_sales) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>

                        <div>
                            <label for="shopee_sales" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Shopee Sales</label>
                            <input type="number" id="shopee_sales" name="shopee_sales" value="{{ old('shopee_sales', $dailySale->shopee_sales) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>

                        <div>
                            <label for="tokopedia_sales" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Tokopedia Sales</label>
                            <input type="number" id="tokopedia_sales" name="tokopedia_sales" value="{{ old('tokopedia_sales', $dailySale->tokopedia_sales) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>
                    </div>
                </div>

                <!-- Income Information -->
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Pemasukan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="cash" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Cash</label>
                            <input type="number" id="cash" name="cash" value="{{ old('cash', $dailySale->cash) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>

                        <div>
                            <label for="qris" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Qris</label>
                            <input type="number" id="qris" name="qris" value="{{ old('qris', $dailySale->qris) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>

                        <div>
                            <label for="transfer" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Transfer</label>
                            <input type="number" id="transfer" name="transfer" value="{{ old('transfer', $dailySale->transfer) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>
                    </div>
                </div>

                <!-- Deposit Information -->
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl text-white mb-6">Setoran</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="expenses" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Pengeluaran</label>
                            <input type="number" id="expenses" name="expenses" value="{{ old('expenses', $dailySale->expenses) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>

                        <div>
                            <label for="total_cash_deposit" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Total Cash Deposit</label>
                            <input type="number" id="total_cash_deposit" name="total_cash_deposit" value="{{ old('total_cash_deposit', $dailySale->total_cash_deposit) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all" min="0" step="0.01">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.daily-sales.index') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Update',
                text: 'Apakah Anda yakin ingin memperbarui data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
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
                    this.submit();
                }
            });
        });
    </script>
</body>
</html>

