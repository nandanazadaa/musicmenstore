<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>SOP Regulasi Kerja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <div class="flex flex-col min-h-screen">
        <main class="flex-1 ml-0 lg:ml-64 px-4 sm:px-6 lg:px-8 py-10">
            <div class="max-w-5xl mx-auto">
                
                <!-- Header Section -->
                <div class="mb-6">
                    <h1 class="serif-font text-3xl text-white mb-2 uppercase italic">
                        SOP & <span class="text-yellow-500">REGULASI KERJA</span>
                    </h1>
                    <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-32 mb-1"></div>
                    <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">
                        Standar operasional prosedur dan instruksi kerja resmi Musicmen Store
                    </p>
                </div>

                <!-- SOP Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    @forelse($sops as $sop)
                        <div class="bg-[#1a1a1a] border border-[#4a4a4a] hover:border-[#6a6a6a] rounded-lg p-5 flex flex-col justify-between transition-all relative overflow-hidden group">
                            
                            <div>
                                <!-- Icon Badge (Freetext Kategori) -->
                                <div class="inline-flex items-center justify-center bg-[#0f0f0f] border border-[#4a4a4a] rounded-md px-2.5 py-1 text-[11px] text-yellow-500 font-bold mb-4 uppercase tracking-wider">
                                    {{ $sop->icon_visual }}
                                </div>

                                <!-- Title -->
                                <h3 class="text-white text-base font-bold uppercase tracking-wide mb-2 line-clamp-1 group-hover:text-yellow-500 transition-colors">
                                    {{ $sop->judul_sop }}
                                </h3>

                                <!-- Short Description -->
                                <p class="text-[#9a9a9a] text-xs leading-relaxed mb-6 line-clamp-2">
                                    {{ $sop->deskripsi_singkat ?? 'Tidak ada deskripsi singkat untuk SOP ini.' }}
                                </p>
                            </div>

                            <!-- Footer Card Info & Action -->
                            <div class="border-t border-[#2a2a2a] pt-4 flex items-center justify-between mt-auto">
                                <span class="text-[10px] text-[#5a5a5a] uppercase font-semibold">
                                    Oleh: {{ $sop->dibuat_oleh }}
                                </span>
                                
                                <!-- View Button Only -->
                                <a href="{{ route('staff.sop.show', $sop->id) }}" 
                                   class="w-full sm:w-auto px-4 py-1.5 rounded-lg bg-[#0f0f0f] border border-[#4a4a4a] hover:border-yellow-500 flex items-center justify-center gap-2 text-xs text-[#9a9a9a] hover:text-white transition-all uppercase tracking-wider font-bold" 
                                   title="Buka Dokumen">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Baca SOP
                                </a>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-full bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-12 text-center text-[#6a6a6a]">
                            <p class="text-sm uppercase tracking-wider font-semibold">Belum ada dokumen instruksi kerja operasional yang diterbitkan oleh admin.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </main>
        @include('components.admin-footer')
    </div>
</body>
</html>