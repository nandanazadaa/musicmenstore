<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Add Member - Musicmen Staff</title>
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
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Add New Member</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            <!-- Form -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <form method="POST" action="{{ route('staff.members.store') }}">
                    @csrf

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
                            <label for="name" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nomor HP <span class="text-red-400">*</span></label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Email (Optional)</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        </div>

                        <div>
                            <label for="address" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Alamat (Optional)</label>
                            <textarea id="address" name="address" rows="3" 
                                      class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">{{ old('address') }}</textarea>
                        </div>

                        <div>
                            <label for="password" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Password <span class="text-red-400">*</span></label>
                            <input type="password" id="password" name="password" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Konfirmasi Password <span class="text-red-400">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <button type="submit" 
                                class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Create Member
                        </button>
                        <a href="{{ route('staff.members.index') }}" 
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

