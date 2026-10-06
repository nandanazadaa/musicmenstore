<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link class="flex items-center" rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Detail Dokumen SOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-[#0f0f0f] text-white">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <div class="flex flex-col min-h-screen">
        <main class="flex-1 ml-0 lg:ml-64 px-4 sm:px-6 lg:px-8 py-10">
            <div class="max-w-5xl mx-auto">

                <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <a href="{{ route('admin.sop.index') }}"
                            class="inline-flex items-center gap-2 text-xs uppercase tracking-wider font-bold text-[#9a9a9a] hover:text-white mb-2 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Daftar
                        </a>
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-[#1a1a1a] border border-[#4a4a4a] text-yellow-500 text-xs px-2 py-1 rounded font-bold uppercase tracking-wider">
                                {{ $sop->icon_visual }}
                            </div>
                            <h1 class="serif-font text-2xl text-white">{{ $sop->judul_sop }}</h1>
                        </div>
                        <p class="text-xs text-[#6a6a6a] uppercase mt-2">Diterbitkan oleh: {{ $sop->dibuat_oleh }}</p>
                    </div>

                    <a href="{{ route('admin.sop.edit', $sop->id) }}"
                        class="bg-transparent border border-[#4a4a4a] text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-[#2a2a2a] transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-yellow-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Regulasi
                    </a>
                </div>

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 space-y-4">
                    @if ($sop->deskripsi_singkat)
                        <div
                            class="p-3 bg-[#0f0f0f] border-l-2 border-yellow-500 rounded-r text-xs text-[#9a9a9a] italic">
                            {{ $sop->deskripsi_singkat }}
                        </div>
                    @endif

                    <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-5 text-gray-200 text-sm font-mono leading-relaxed whitespace-pre-wrap min-h-[250px] text-left">{{ trim($sop->isi_konten_sop) }}</div>

                    <div class="text-[10px] text-[#6a6a6a] uppercase tracking-wider text-right pt-2">
                        Terakhir Diperbarui: {{ $sop->updated_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                    </div>
                </div>

            </div>
        </main>
        @include('components.admin-footer')
    </div>
</body>

</html>