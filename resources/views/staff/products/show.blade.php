<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Instrumen - Musicmen Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="serif-font text-3xl sm:text-4xl text-white">Detail Instrumen</h1>
                <a href="{{ route('staff.products.index') }}" class="bg-transparent border border-[#4a4a4a] px-4 py-2 rounded text-xs uppercase tracking-widest hover:bg-[#2a2a2a]">Back to List</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-8">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-white uppercase">{{ $product->nama_barang }}</h2>
                            <p class="text-sm text-yellow-500 font-mono mt-1">SN: {{ $product->nomor_seri ?? 'No Serial Number' }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                            <div><label class="block text-[10px] text-[#6a6a6a] uppercase font-bold">Origin</label><p class="text-sm text-white">{{ $product->tempat_pembuatan ?? '-' }}</p></div>
                            <div><label class="block text-[10px] text-[#6a6a6a] uppercase font-bold">Year</label><p class="text-sm text-white">{{ $product->tahun_pembuatan ?? '-' }}</p></div>
                            <div><label class="block text-[10px] text-[#6a6a6a] uppercase font-bold">Color</label><p class="text-sm text-white">{{ $product->warna ?? '-' }}</p></div>
                            <div><label class="block text-[10px] text-[#6a6a6a] uppercase font-bold">Condition</label><p class="text-sm text-white">{{ $product->kondisi ?? '-' }}</p></div>
                            <div><label class="block text-[10px] text-[#6a6a6a] uppercase font-bold">Category</label><p class="text-sm text-blue-400 uppercase font-bold">{{ $product->type }}</p></div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-[#333]">
                            <label class="block text-[10px] text-[#6a6a6a] uppercase font-bold mb-2">Kelengkapan</label>
                            <p class="text-sm text-[#9a9a9a] bg-[#0f0f0f] p-4 rounded-lg border border-[#222]">{{ $product->kelengkapan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-6">
                        <h3 class="text-xs text-white font-bold uppercase mb-4 tracking-widest">Technical Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-[#0f0f0f] rounded-lg border border-[#222]">
                                <span class="text-[10px] text-[#6a6a6a] uppercase">Setting & Setup</span>
                                <span class="text-[10px] font-bold uppercase {{ $product->setting_setup == 'Done' ? 'text-green-500' : 'text-red-500' }}">{{ ($product->setting_setup ?? 'Pending') === 'Pending' ? 'Not Yet' : $product->setting_setup }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-[#0f0f0f] rounded-lg border border-[#222]">
                                <span class="text-[10px] text-[#6a6a6a] uppercase">Price & Hologram</span>
                                <span class="text-[10px] font-bold uppercase {{ $product->price_hologram == 'Done' ? 'text-green-500' : 'text-red-500' }}">{{ ($product->price_hologram ?? 'Pending') === 'Pending' ? 'Not Yet' : $product->price_hologram }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-[#0f0f0f] rounded-lg border border-[#222]">
                                <span class="text-[10px] text-[#6a6a6a] uppercase">Cashier App</span>
                                <span class="text-[10px] font-bold uppercase {{ $product->input_cashier == 'Done' ? 'text-purple-400' : 'text-gray-600' }}">{{ ($product->input_cashier ?? 'Pending') === 'Pending' ? 'Not Yet' : $product->input_cashier }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-xl p-6">
                        <h3 class="text-xs text-white font-bold uppercase mb-4 tracking-widest">PIC & Fees</h3>
                        <div class="space-y-3">
                            @foreach($product->additionalPics as $ap)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-[#9a9a9a] uppercase">{{ $ap->pic_name }}</span>
                                    <span class="text-green-500 font-mono">Rp {{ number_format($ap->fee_amount, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('components.admin-footer')
</body>
</html>
