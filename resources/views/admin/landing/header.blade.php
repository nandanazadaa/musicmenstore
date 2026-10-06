<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Header Section - Musicmen Admin</title>
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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Header Section</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                </div>
            </div>

            @include('components.admin-alerts')

            <!-- Content -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <form action="{{ route('admin.landing.header.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#9a9a9a] mb-2">
                            Header Logo
                        </label>
                        
                        <!-- Current Logo Preview -->
                        <div class="mb-4">
                            <p class="text-xs text-[#6a6a6a] mb-2">Current Logo:</p>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4 inline-block">
                                <img src="{{ $logo }}" alt="Current Logo" class="max-w-[180px] max-h-[70px] object-contain">
                            </div>
                        </div>

                        <!-- File Input -->
                        <div class="relative">
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/*"
                                   class="block w-full text-sm text-[#9a9a9a] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#3a3a3a] file:cursor-pointer border border-[#4a4a4a] rounded-lg bg-[#0f0f0f] focus:outline-none focus:border-[#6a6a6a] transition-colors">
                            @include('components.upload-note', ['extra' => 'Rekomendasi ukuran logo: 180x70px.'])
                        </div>

                        <!-- Preview New Logo -->
                        <div id="logoPreview" class="mt-4 hidden">
                            <p class="text-xs text-[#6a6a6a] mb-2">New Logo Preview:</p>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4 inline-block">
                                <img id="previewImage" src="" alt="Preview" class="max-w-[180px] max-h-[70px] object-contain">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" 
                                class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Update Logo
                        </button>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        // Preview logo before upload
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('logoPreview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                document.getElementById('logoPreview').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
