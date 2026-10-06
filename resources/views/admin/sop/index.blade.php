<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Regulasi & SOP Toko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-[#0f0f0f] text-white">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <div class="flex flex-col min-h-screen">
        <main class="flex-1 ml-0 lg:ml-64 px-4 sm:px-6 lg:px-8 py-10">
            <div class="max-w-5xl mx-auto">

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h1 class="serif-font text-3xl text-white mb-2 uppercase italic">
                            REGULASI & <span class="text-yellow-500">SOP TOKO</span>
                        </h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-32 mb-1"></div>
                        <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">
                            Kelola instruksi kerja standar operasional cabang Musicmen
                        </p>
                    </div>

                    <a href="{{ route('admin.sop.create') }}"
                        class="bg-transparent border border-[#4a4a4a] text-white font-bold px-5 py-2.5 rounded-lg text-sm hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat SOP Baru
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-900/20 border border-green-500/50 rounded-lg text-sm text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    @forelse($sops as $sop)
                        <div
                            class="bg-[#1a1a1a] border border-[#4a4a4a] hover:border-[#6a6a6a] rounded-lg p-5 flex flex-col justify-between transition-all relative overflow-hidden group">

                            <div>
                                <div
                                    class="inline-flex items-center justify-center bg-[#0f0f0f] border border-[#4a4a4a] rounded-md px-2.5 py-1 text-[11px] text-yellow-500 font-bold mb-4 uppercase tracking-wider">
                                    {{ $sop->icon_visual }}
                                </div>

                                <h3
                                    class="text-white text-base font-bold uppercase tracking-wide mb-2 line-clamp-1 group-hover:text-yellow-500 transition-colors">
                                    {{ $sop->judul_sop }}
                                </h3>

                                <p class="text-[#9a9a9a] text-xs leading-relaxed mb-6 line-clamp-2">
                                    {{ $sop->deskripsi_singkat ?? 'Tidak ada deskripsi singkat untuk SOP ini.' }}
                                </p>
                            </div>

                            <div class="border-t border-[#2a2a2a] pt-4 flex items-center justify-between mt-auto">
                                <span class="text-[10px] text-[#5a5a5a] uppercase font-semibold">
                                    Oleh: {{ $sop->dibuat_oleh }}
                                </span>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.sop.show', $sop->id) }}"
                                        class="w-8 h-8 rounded-lg bg-[#0f0f0f] border border-[#4a4a4a] hover:border-[#6a6a6a] flex items-center justify-center text-[#9a9a9a] hover:text-white transition-all"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.sop.edit', $sop->id) }}"
                                        class="w-8 h-8 rounded-lg bg-[#0f0f0f] border border-[#4a4a4a] hover:border-yellow-600 flex items-center justify-center text-[#9a9a9a] hover:text-yellow-500 transition-all"
                                        title="Edit SOP">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form id="delete-form-{{ $sop->id }}"
                                        action="{{ route('admin.sop.destroy', $sop->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete('{{ $sop->id }}', '{{ $sop->judul_sop }}')"
                                            class="w-8 h-8 rounded-lg bg-[#0f0f0f] border border-[#4a4a4a] hover:border-red-600 flex items-center justify-center text-[#9a9a9a] hover:text-red-500 transition-all"
                                            title="Hapus SOP">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div
                            class="col-span-full bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-12 text-center text-[#6a6a6a]">
                            <p class="text-sm uppercase tracking-wider font-semibold">Belum ada dokumen SOP yang
                                diterbitkan.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </main>
        @include('components.admin-footer')
    </div>

    <style>
        .swal2-popup {
            background-color: #1a1a1a !important;
            border: 1px solid #4a4a4a !important;
        }

        .swal2-title {
            color: #ffffff !important;
        }

        .swal2-html-container {
            color: #d4d4d4 !important;
        }

        .swal2-confirm {
            background-color: #dc2626 !important;
            /* Warna Merah Hapus */
        }

        .swal2-confirm:hover {
            background-color: #b91c1c !important;
        }

        .swal2-cancel {
            background-color: #4a4a4a !important;
            color: #ffffff !important;
        }

        .swal2-cancel:hover {
            background-color: #6a6a6a !important;
        }
    </style>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    background: '#1a1a1a',
                    color: '#fff',
                    confirmButtonColor: '#3b82f6'
                });
            });
        </script>
    @endif
    <script>
        function confirmDelete(id, judul) {
            Swal.fire({
                title: 'Hapus SOP?',
                html: `Apakah Anda yakin ingin menghapus regulasi <br><b class="text-yellow-500">${judul}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form yang sesuai jika user menekan tombol konfirmasi
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
</body>

</html>
