<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Member - Musicmen Admin</title>
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
    </style>
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <!-- Page Header -->
            <div class="mb-8 w-full">
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Edit Member</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            <!-- Member Card -->
            <div class="mb-8 max-w-2xl">
                @include('components.member-card', ['member' => $member])
            </div>

            <!-- Form -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <form method="POST" action="{{ route('admin.members.update', $member->id) }}" enctype="multipart/form-data">
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
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">ID Member</label>
                            <input type="text" value="{{ $member->member_id }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white opacity-75 cursor-not-allowed"
                                   disabled readonly>
                            <p class="text-xs text-[#6a6a6a] mt-1">ID Member tidak dapat diubah</p>
                        </div>

                        <div>
                            <label for="name" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nomor HP</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $member->phone) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        </div>

                        <div>
                            <label for="address" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Alamat</label>
                            <textarea id="address" name="address" rows="3" 
                                      class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">{{ old('address', $member->address) }}</textarea>
                        </div>

                        <div>
                            <label for="password" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   placeholder="Minimal 6 karakter">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   placeholder="Ulangi password baru">
                        </div>

                        <!-- Hadiah untuk kelipatan 5 -->
                        <div class="border border-[#4a4a4a] rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-lg font-semibold text-white">Input Hadiah Member</p>
                                    <p class="text-sm text-[#9a9a9a]">Member ini memiliki {{ $member->visits_count ?? 0 }} kunjungan.</p>
                                </div>
                                @if(($member->visits_count ?? 0) >= 5)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-900/20 border border-green-700 text-green-400">
                                        Eligible (>= 5 kunjungan)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-900/20 border border-red-700 text-red-400">
                                        Belum mencapai 5 kunjungan
                                    </span>
                                @endif
                            </div>

                            @if(($member->visits_count ?? 0) < 5)
                                <p class="text-sm text-[#9a9a9a]">Hadiah hanya bisa diinput setelah member mencapai minimal 5 kunjungan.</p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="reward_milestone" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Milestone (kelipatan 5)</label>
                                        <select id="reward_milestone" name="reward_milestone" 
                                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                                            <option value="">Pilih milestone</option>
                                            @for($i = 5; $i <= ($member->visits_count ?? 0); $i += 5)
                                                <option value="{{ $i }}" {{ old('reward_milestone') == $i ? 'selected' : '' }}>{{ $i }} kunjungan</option>
                                            @endfor
                                        </select>
                                        <p class="text-xs text-[#6a6a6a] mt-1">Pilih kelipatan 5 hingga total kunjungan saat ini.</p>
                                    </div>

                                    <div>
                                        <label for="reward_nama" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Hadiah</label>
                                        <input type="text" id="reward_nama" name="reward_nama" value="{{ old('reward_nama') }}" 
                                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                               placeholder="Contoh: Voucher Diskon 10%">
                                    </div>

                                    <div>
                                        <label for="reward_gambar" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Gambar Hadiah</label>
                                        <input type="file" id="reward_gambar" name="reward_gambar" accept="image/*"
                                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                                        <p class="text-xs text-[#6a6a6a] mt-1">Optional. Maks 2MB.</p>
                                    </div>

                                    <div>
                                        <label for="reward_kata" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Kata-kata</label>
                                        <textarea id="reward_kata" name="reward_kata" rows="3"
                                                  class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                                  placeholder="Ucapan atau deskripsi hadiah">{{ old('reward_kata') }}</textarea>
                                    </div>
                                </div>

                                @if($member->rewards && $member->rewards->count())
                                    <div class="mt-4">
                                        <p class="text-sm text-white font-semibold mb-2">Hadiah yang sudah tersimpan:</p>
                                        <div class="space-y-2">
                                            @foreach($member->rewards as $reward)
                                                <div class="flex items-center justify-between bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2">
                                                    <div>
                                                        <p class="text-white text-sm font-semibold">{{ $reward->nama_hadiah }}</p>
                                                        <p class="text-xs text-[#9a9a9a]">Milestone: {{ $reward->milestone }} | {{ $reward->kata_kata ?? '-' }}</p>
                                                    </div>
                                                    @if($reward->gambar_hadiah)
                                                        <img src="{{ asset($reward->gambar_hadiah) }}" alt="{{ $reward->nama_hadiah }}" class="w-10 h-10 object-cover rounded border border-[#4a4a4a]">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <button type="submit" 
                                class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Update Member
                        </button>
                        <a href="{{ route('admin.members.index') }}" 
                           class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>
