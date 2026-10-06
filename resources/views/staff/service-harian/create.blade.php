<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Service Baru - Musicmen Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html,
        body {
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
        <div class="w-full px-4 sm:px-6 lg:px-8 py-10">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <h1 class="serif-font text-3xl text-white mb-2">Penerimaan Service Baru</h1>
                    <p class="text-[#9a9a9a]">Input data customer dan keluhan untuk diteruskan ke teknisi.</p>
                    <div class="h-px bg-gradient-to-r from-blue-500 via-[#4a4a4a] to-transparent w-full mt-4"></div>
                </div>

                <div id="error-message" class="hidden mb-4 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm text-red-400"></p>
                </div>

                <form id="serviceHarianForm"
                    class="space-y-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 sm:p-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 border-b border-[#333] pb-2">
                            <h3 class="text-blue-400 font-semibold uppercase text-xs tracking-widest">Informasi
                                Pelanggan</h3>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Customer
                                <span class="text-red-400">*</span></label>
                            <input type="text" name="nama_customer"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none transition-all"
                                placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nomor Whatsapp
                                <span class="text-red-400">*</span></label>
                            <input type="text" name="whatsapp" id="whatsapp"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none transition-all"
                                placeholder="08xxxxxxxxxx" required>
                        </div>

                        <div class="md:col-span-2 border-b border-[#333] pb-2 mt-4">
                            <h3 class="text-blue-400 font-semibold uppercase text-xs tracking-widest">Detail Instrumen &
                                Keluhan</h3>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Merk Instrumen
                                <span class="text-red-400">*</span></label>
                            <input type="text" name="merk"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none transition-all"
                                placeholder="Contoh: Fender, Ibanez" required>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Tipe</label>
                            <input type="text" name="tipe"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none transition-all"
                                placeholder="Contoh: Stratocaster">
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Kategori <span
                                    class="text-red-400">*</span></label>
                            <select name="instrumen"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none transition-all"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($instrumentOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Keluhan / Jenis
                                Service <span class="text-red-400">*</span></label>
                            <textarea name="jenis_service" rows="3"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-blue-500 outline-none transition-all"
                                placeholder="Jelaskan detail kerusakan..." required></textarea>
                        </div>

                        <div class="md:col-span-2 border-b border-[#333] pb-2 mt-4">
                            <h3 class="text-yellow-500 font-semibold uppercase text-xs tracking-widest">Penugasan
                                Teknisi</h3>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Pilih Teknisi
                                Eksekutor <span class="text-red-400">*</span></label>
                            <select name="eksekutor"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:border-yellow-500 outline-none transition-all"
                                required>
                                <option value="">-- Pilih Nama Teknisi --</option>
                                @foreach ($allStaffs as $s)
                                    <option value="{{ $s->nama }}">{{ $s->nama }}</option>
                                @endforeach
                            </select>
                            <div class="mt-4 bg-blue-900/20 border border-blue-500/30 p-4 rounded-lg">
                                <p class="text-xs text-blue-300 leading-relaxed">
                                    <strong>Info:</strong> Anda akan tercatat sebagai Penerima (Fee 5%).
                                </p>
                            </div>
                        </div>
                        <input type="hidden" name="tanggal_masuk" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" id="submitBtn"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl uppercase tracking-widest transition-all">
                            Hanya Simpan
                        </button>
                        <button type="button" onclick="submitAndWhatsApp()"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                            Simpan & Kirim WA (Proses)
                        </button>
                        <a href="{{ route('staff.service-harian.input-index') }}"
                            class="flex-1 bg-transparent border border-[#4a4a4a] text-[#9a9a9a] text-center py-4 rounded-xl hover:bg-[#2a2a2a] flex items-center justify-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    @include('components.admin-footer')

    <script>
        async function submitAndWhatsApp() {
            const form = document.getElementById('serviceHarianForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            // Tampilkan Loading SweetAlert
            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Menyimpan data & Mengirim WhatsApp Otomatis',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch('{{ route('staff.service-harian.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // BERHASIL: Langsung tampilkan pesan sukses tanpa buka tab baru
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        // Redirect ke halaman monitoring
                        window.location.href = '{{ route('staff.service-harian.input-index') }}';
                    });
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error!', 'Terjadi kesalahan sistem atau koneksi.', 'error');
            }
        }

        document.getElementById('serviceHarianForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const res = await fetch('{{ route('staff.service-harian.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            if (data.success) window.location.href = '{{ route('staff.service-harian.input-index') }}';
        });
    </script>
</body>

</html>
