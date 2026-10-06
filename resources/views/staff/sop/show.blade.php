<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link class="flex items-center" rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Detail Regulasi Kerja</title>
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
                    <a href="{{ route('staff.sop.index') }}"
                        class="inline-flex items-center gap-2 text-xs uppercase tracking-wider font-bold text-[#9a9a9a] hover:text-white mb-3 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar SOP
                    </a>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#2a2a2a] pb-4">
                        <div>
                            <div
                                class="inline-flex items-center justify-center bg-[#1a1a1a] border border-[#4a4a4a] text-yellow-500 text-xs px-2.5 py-1 rounded font-bold uppercase tracking-wider mb-2">
                                {{ $sop->icon_visual }}
                            </div>
                            <h1 class="serif-font text-2xl lg:text-3xl text-white uppercase tracking-wide">
                                {{ $sop->judul_sop }}</h1>
                            <p class="text-xs text-[#6a6a6a] uppercase mt-2 font-semibold">Diterbitkan resmi oleh: <span
                                    class="text-[#9a9a9a]">{{ $sop->dibuat_oleh }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 space-y-4">
                    @if ($sop->deskripsi_singkat)
                        <div
                            class="p-3 bg-[#0f0f0f] border-l-2 border-yellow-500 rounded-r text-xs text-[#9a9a9a] italic">
                            Info Ringkas: {{ $sop->deskripsi_singkat }}
                        </div>
                    @endif

                    <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-5 text-gray-200 text-sm font-mono leading-relaxed whitespace-pre-wrap min-h-[250px] text-left">{{ trim($sop->isi_konten_sop) }}</div>

                    <div class="text-[10px] text-[#6a6a6a] uppercase tracking-wider text-right pt-2 font-semibold">
                        Terakhir Diperbarui: {{ $sop->updated_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                    </div>
                </div>

            </div>
        </main>
        @include('components.admin-footer')
    </div>
</body>

</html>