<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Penerbitan SOP Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <div class="flex flex-col min-h-screen">
        <main class="flex-1 ml-0 lg:ml-64 px-4 sm:px-6 lg:px-8 py-10">
            <div class="max-w-5xl mx-auto">
                
                <div class="mb-6">
                    <a href="{{ route('admin.sop.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-wider font-bold text-[#9a9a9a] hover:text-white mb-2 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Menu Utama
                    </a>
                    <h1 class="serif-font text-3xl text-white mb-2">Penerbitan SOP Baru</h1>
                    <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-32"></div>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                        <ul class="text-sm text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.sop.store') }}" class="space-y-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Judul SOP -->
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama / Judul SOP *</label>
                            <input type="text" name="judul_sop" value="{{ old('judul_sop') }}" placeholder="Contoh: SOP Buka Toko Cabang" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white outline-none focus:border-yellow-500 transition-all" required>
                        </div>

                        <!-- Ikon Visual Freetext -->
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Pilih Ikon Visual / Kategori (Freetext) *</label>
                            <input type="text" name="icon_visual" value="{{ old('icon_visual', '🏪 TOKO / OUTLET') }}" placeholder="Contoh: 🏪 TOKO / OUTLET atau 🔒 KEAMANAN" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white outline-none focus:border-yellow-500 transition-all" required>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Deskripsi Singkat (Opsional)</label>
                            <input type="text" name="deskripsi_singkat" value="{{ old('deskripsi_singkat') }}" placeholder="Tulis rangkuman 1 kalimat tentang inti regulasi ini..." class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white outline-none focus:border-yellow-500 transition-all">
                        </div>

                        <!-- Isi Konten Konten -->
                        <div class="md:col-span-2">
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Isi Konten Aturan SOP *</label>
                            <textarea name="isi_konten_sop" rows="10" placeholder="1. Datang tepat waktu 15 menit sebelum outlet buka&#10;2. Nyalakan seluruh saklar lampu utama...&#10;3. Lakukan bersih-bersih area etalase meja kasir..." class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white font-mono leading-relaxed outline-none focus:border-yellow-500 transition-all whitespace-pre-wrap" required>{{ old('isi_konten_sop') }}</textarea>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">Terbitkan SOP</button>
                        <a href="{{ route('admin.sop.index') }}" class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider text-center">Batal</a>
                    </div>
                </form>

            </div>
        </main>
        @include('components.admin-footer')
    </div>
</body>
</html>