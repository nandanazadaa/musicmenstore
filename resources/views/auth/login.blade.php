<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Musicmen Store</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-[#0a0a0a] text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-6">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo4.png') }}" alt="MUSICMEN" class="mx-auto mb-6" style="width: 200px; max-height: 80px; object-fit: contain;">
            <h1 class="serif-font text-3xl md:text-4xl font-light tracking-wide text-white mb-2">
                Admin Login
            </h1>
            <div class="w-24 h-0.5 bg-white mx-auto"></div>
        </div>

        <!-- Login Form -->
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-[#d4d4d4] mb-2 uppercase tracking-wider">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required 
                           autofocus
                           class="w-full px-4 py-3 bg-[#0a0a0a] border border-[#3a3a3a] text-white rounded-lg focus:outline-none focus:border-[#5a5a5a] transition-all vintage-text"
                           placeholder="admin@musicmen.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-[#d4d4d4] mb-2 uppercase tracking-wider">
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-4 py-3 bg-[#0a0a0a] border border-[#3a3a3a] text-white rounded-lg focus:outline-none focus:border-[#5a5a5a] transition-all vintage-text"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="remember" 
                           name="remember"
                           class="w-4 h-4 bg-[#0a0a0a] border-[#3a3a3a] rounded focus:ring-[#5a5a5a] focus:ring-2">
                    <label for="remember" class="ml-2 text-sm text-[#d4d4d4] vintage-text">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg uppercase tracking-wider hover:bg-white hover:text-[#0a0a0a] transition-all duration-300 font-medium text-sm">
                    Login
                </button>
            </form>
        </div>

        <!-- Back to Home Link -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-[#9a9a9a] hover:text-white transition-colors text-sm vintage-text">
                ← Back to Home
            </a>
        </div>
    </div>

    <style>
        /* Login Page Specific Styles */
        body {
            padding-top: 0 !important;
        }

        input[type="checkbox"] {
            accent-color: #ffffff;
        }

        input:focus {
            transform: scale(1.01);
        }

        /* Fade in animation */
        .bg-\[#1a1a1a\] {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</body>

</html>
