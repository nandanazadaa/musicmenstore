<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - Musicmen Store</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo4.png') }}" alt="MUSICMEN" class="h-16 mx-auto mb-4">
                <h1 class="serif-font text-3xl text-white mb-2">Member Login</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-24 mx-auto"></div>
            </div>

            <form method="POST" action="{{ route('member.login') }}">
                @csrf
            
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                        <ul class="text-sm text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            
                <div class="space-y-4">
                    <div>
                        <label for="phone" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nomor WhatsApp / HP</label>
                        <input type="number" id="phone" name="phone" value="{{ old('phone') }}" 
                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                               placeholder="Masukkan nomor HP" required autofocus inputmode="numeric">
                    </div>
            
                    <div>
                        <label for="password" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Password</label>
                        <input type="password" id="password" name="password" 
                               class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                               placeholder="Masukkan password" required>
                    </div>
                </div>
            
                <button type="submit" 
                        class="w-full mt-6 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-[#9a9a9a]">
                    Belum punya akun? 
                    <a href="{{ route('member.register') }}" class="text-white hover:text-[#6a6a6a] transition-colors">Daftar</a>
                </p>
                <a href="{{ url('/') }}" class="text-sm text-[#9a9a9a] hover:text-white transition-colors mt-2 inline-block">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
