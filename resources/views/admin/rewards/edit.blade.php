<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Reward - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        * {
            box-sizing: border-box;
        }
        #imagePreview {
            max-width: 300px;
            max-height: 300px;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <!-- Page Header -->
            <div class="mb-8 w-full">
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Edit Reward</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            <!-- Form -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <form method="POST" action="{{ route('admin.rewards.update', $reward->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                            <ul class="text-sm text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <div>
                            <label for="member_id" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Member <span class="text-red-400">*</span></label>
                            <select id="member_id" name="member_id" 
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                    required>
                                <option value="">Pilih Member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" 
                                            data-visits="{{ $member->visits_count }}"
                                            {{ (old('member_id', $reward->member_id) == $member->id) ? 'selected' : '' }}>
                                        {{ $member->member_id }} - {{ $member->name }} ({{ $member->visits_count }} kunjungan)
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-[#6a6a6a]">Hanya menampilkan member yang sudah mencapai minimal 5 kunjungan</p>
                        </div>

                        <div>
                            <label for="milestone" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Milestone (Kelipatan 5) <span class="text-red-400">*</span></label>
                            <input type="number" id="milestone" name="milestone" 
                                   value="{{ old('milestone', $reward->milestone) }}"
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   placeholder="Contoh: 5, 10, 15, 20, dst" 
                                   min="5" step="5" required oninput="validateMilestone(this)">
                            <p class="mt-1 text-xs text-[#6a6a6a]">Input kelipatan 5 (5, 10, 15, 20, 25, dst). Bisa input milestone yang lebih besar dari kunjungan saat ini.</p>
                            <p id="milestoneError" class="mt-1 text-xs text-red-400 hidden"></p>
                        </div>

                        <div>
                            <label for="nama_hadiah" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Hadiah <span class="text-red-400">*</span></label>
                            <input type="text" id="nama_hadiah" name="nama_hadiah" value="{{ old('nama_hadiah', $reward->nama_hadiah) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   placeholder="Contoh: Voucher Diskon 10%" required>
                        </div>

                        <div>
                            <label for="gambar_hadiah" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Gambar Hadiah</label>
                            @if($reward->gambar_hadiah)
                                <div class="mb-4">
                                    <p class="text-sm text-[#9a9a9a] mb-2">Gambar saat ini:</p>
                                    <img src="{{ asset($reward->gambar_hadiah) }}" alt="{{ $reward->nama_hadiah }}" 
                                         class="w-32 h-32 object-cover rounded-lg border border-[#4a4a4a]">
                                </div>
                            @endif
                            <input type="file" id="gambar_hadiah" name="gambar_hadiah" accept="image/*"
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   onchange="previewImage(this)">
                            <p class="mt-1 text-xs text-[#6a6a6a]">Format: JPEG, PNG, JPG, GIF, SVG (Max: 2MB). Kosongkan jika tidak ingin mengubah gambar.</p>
                            <div id="imagePreviewContainer" class="mt-4 hidden">
                                <p class="text-sm text-[#9a9a9a] mb-2">Preview gambar baru:</p>
                                <img id="imagePreview" src="" alt="Preview" class="rounded-lg border border-[#4a4a4a]">
                            </div>
                        </div>

                        <div>
                            <label for="kata_kata" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Kata-kata</label>
                            <textarea id="kata_kata" name="kata_kata" rows="4" 
                                      class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                      placeholder="Contoh: Selamat! Anda telah mencapai milestone 5 kunjungan. Terima kasih atas loyalitas Anda!">{{ old('kata_kata', $reward->kata_kata) }}</textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <button type="submit" 
                                class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Update Reward
                        </button>
                        <a href="{{ route('admin.rewards.index') }}" 
                           class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        function validateMilestone(input) {
            const value = parseInt(input.value);
            const errorEl = document.getElementById('milestoneError');
            
            if (value && (value % 5 !== 0 || value < 5)) {
                errorEl.textContent = 'Milestone harus kelipatan 5 dan minimal 5';
                errorEl.classList.remove('hidden');
                input.setCustomValidity('Milestone harus kelipatan 5');
            } else {
                errorEl.classList.add('hidden');
                input.setCustomValidity('');
            }
        }
        
        function updateMilestoneOptions() {
            // Function ini tidak diperlukan lagi karena menggunakan input number
        }

        function previewImage(input) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            const preview = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        // Initialize milestone validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const milestoneInput = document.getElementById('milestone');
            if (milestoneInput && milestoneInput.value) {
                validateMilestone(milestoneInput);
            }
        });
    </script>
</body>
</html>
