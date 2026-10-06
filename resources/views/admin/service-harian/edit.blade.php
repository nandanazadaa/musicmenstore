<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Service Harian</title>
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
                    <h1 class="serif-font text-3xl text-white mb-2">Edit Service Harian</h1>
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

                <form method="POST" action="{{ route('admin.service-harian.update', $service->id) }}" class="space-y-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase">Penerima (Staff Depan - 5%)</label>
                            <select name="penerima" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                                <option value="">-- Pilih Penerima --</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->nama }}" {{ old('penerima', $service->penerima) == $staff->nama ? 'selected' : '' }}>
                                        {{ $staff->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase">Teknisi (Pengerjaan - 30%)</label>
                            <select name="eksekutor" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                                <option value="">-- Pilih Teknisi --</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->nama }}" {{ old('eksekutor', $service->eksekutor) == $staff->nama ? 'selected' : '' }}>
                                        {{ $staff->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $service->tanggal_masuk) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Customer</label>
                            <input type="text" name="nama_customer" value="{{ old('nama_customer', $service->nama_customer) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Whatsapp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $service->whatsapp) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white" placeholder="08xxxxxxxxxx">
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Merk</label>
                            <input type="text" name="merk" value="{{ old('merk', $service->merk) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Tipe</label>
                            <input type="text" name="tipe" value="{{ old('tipe', $service->tipe) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Instrumen</label>
                            <select name="instrumen" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                                <option value="" class="text-[#9a9a9a]">Pilih instrumen</option>
                                @foreach($instrumentOptions as $key => $label)
                                    <option value="{{ $key }}" {{ old('instrumen', $service->instrumen) === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Jenis Service</label>
                            <input type="text" name="jenis_service" value="{{ old('jenis_service', $service->jenis_service) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Biaya Sparepart</label>
                            <input type="number" step="1" id="biaya_sparepart" name="biaya_sparepart" value="{{ old('biaya_sparepart', round($service->biaya_sparepart)) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Biaya Jasa</label>
                            <input type="number" step="1" id="biaya_jasa" name="biaya_jasa" value="{{ old('biaya_jasa', round($service->biaya_jasa)) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                            <p class="text-xs text-[#6a6a6a] mt-1">Biaya jasa untuk service (bukan fee staff)</p>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Total Harga</label>
                            <input type="number" id="total_harga" name="total_harga" value="{{ old('total_harga', round($service->total_harga)) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white" readonly>
                            <p class="text-xs text-[#6a6a6a] mt-1">Otomatis: Biaya Sparepart + Biaya Jasa</p>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Fee Teknisi (30% dari Total Biaya Jasa)</label>
                            <input type="number" id="fee_staff" name="fee_staff" value="{{ old('fee_staff', round($service->fee_staff ?? 0)) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white" readonly>
                            <p class="text-xs text-[#6a6a6a] mt-1">Fee yang didapatkan teknisi (30%)</p>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Status Pengerjaan</label>
                            <select name="status_pengerjaan" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                                <option value="proses" {{ old('status_pengerjaan', $service->status_pengerjaan)==='proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ old('status_pengerjaan', $service->status_pengerjaan)==='selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $service->tanggal_selesai) }}" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                        </div>

                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Status Pengambilan</label>
                            <select name="status_pengambilan" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                                <option value="Belum diambil" {{ old('status_pengambilan', $service->status_pengambilan)==='Belum diambil' ? 'selected' : '' }}>Belum diambil</option>
                                <option value="Sudah diambil" {{ old('status_pengambilan', $service->status_pengambilan)==='Sudah diambil' ? 'selected' : '' }}>Sudah diambil</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">{{ old('keterangan', $service->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">Update Data</button>
                        <a href="{{ route('admin.service-harian.index') }}" class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider text-center">Batal</a>
                    </div>
                </form>
            </div>
        </main>

        @include('components.admin-footer')
    </div>
    
    <script>
        function calculateServiceFee() {
            const biayaSparepart = parseFloat(document.getElementById('biaya_sparepart').value) || 0;
            const biayaJasa = parseFloat(document.getElementById('biaya_jasa').value) || 0;
            
            const totalHarga = biayaSparepart + biayaJasa;
            const feeTeknisi = biayaJasa * 0.3; // 30%
            
            document.getElementById('total_harga').value = totalHarga.toFixed(0);
            document.getElementById('fee_staff').value = feeTeknisi.toFixed(0);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const biayaSparepartInput = document.getElementById('biaya_sparepart');
            const biayaJasaInput = document.getElementById('biaya_jasa');
            
            if (biayaSparepartInput) {
                biayaSparepartInput.addEventListener('input', calculateServiceFee);
            }
            if (biayaJasaInput) {
                biayaJasaInput.addEventListener('input', calculateServiceFee);
            }
            
            // Jalankan kalkulasi saat pertama kali load untuk sinkronisasi data lama
            calculateServiceFee();
        });
    </script>
</body>
</html>