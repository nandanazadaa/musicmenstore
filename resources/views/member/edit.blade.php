<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Profile - Musicmen Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen">
    @include('components.navbar')

    <main class="container mx-auto max-w-4xl px-6 lg:px-8 py-12 mt-20">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div>
                    <h1 class="serif-font text-4xl md:text-5xl text-white mb-2">Edit Profile</h1>
                    <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                </div>
                <a href="{{ route('member.profile') }}" 
                   class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                    Back to Profile
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                <p class="text-sm text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                <p class="text-sm text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Profile Edit Form -->
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
            <form method="POST" action="{{ route('member.profile.update') }}">
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
                    <!-- Member ID (Read Only) -->
                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">ID Member</label>
                        <input type="text" value="{{ $member->member_id }}" 
                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white opacity-75 cursor-not-allowed"
                               disabled readonly>
                        <p class="text-xs text-[#6a6a6a] mt-1">ID Member tidak dapat diubah</p>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" 
                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                               required>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nomor HP</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $member->phone) }}" 
                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                               required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}" 
                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Alamat</label>
                        <textarea id="address" name="address" rows="3" 
                                  class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">{{ old('address', $member->address) }}</textarea>
                    </div>

                    <!-- Password (Optional) -->
                    <div>
                        <label for="password" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" id="password" name="password" 
                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                               placeholder="Minimal 6 karakter">
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button type="submit" 
                            class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Update Profile
                    </button>
                    <a href="{{ route('member.profile') }}" 
                       class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    @include('components.footer')
    @stack('scripts')
</body>
</html>
