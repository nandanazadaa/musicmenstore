<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Main Section - Musicmen Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
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
                    <div class="w-full">
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Main Section</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                </div>
            </div>

            @include('components.admin-alerts')

            <!-- Content -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <form action="{{ route('admin.landing.main.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Logo Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#9a9a9a] mb-2">
                            Main Logo
                        </label>
                        
                        <!-- Current Logo Preview -->
                        <div class="mb-4">
                            <p class="text-xs text-[#6a6a6a] mb-2">Current Logo:</p>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4 inline-block">
                                <img src="{{ $logo }}" alt="Current Logo" class="max-w-[600px] max-h-[200px] object-contain">
                            </div>
                        </div>

                        <!-- File Input -->
                        <div class="relative">
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/*"
                                   class="block w-full text-sm text-[#9a9a9a] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#3a3a3a] file:cursor-pointer border border-[#4a4a4a] rounded-lg bg-[#0f0f0f] focus:outline-none focus:border-[#6a6a6a] transition-colors">
                            @include('components.upload-note', ['extra' => 'Rekomendasi ukuran logo: 900x300px.'])
                        </div>

                        <!-- Preview New Logo -->
                        <div id="logoPreview" class="mt-4 hidden">
                            <p class="text-xs text-[#6a6a6a] mb-2">New Logo Preview:</p>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4 inline-block">
                                <img id="previewImage" src="" alt="Preview" class="max-w-[600px] max-h-[200px] object-contain">
                            </div>
                        </div>
                    </div>

                    <!-- Subtitle Input -->
                    <div class="mb-6">
                        <label for="subtitle" class="block text-sm font-medium text-[#9a9a9a] mb-2">
                            Subtitle
                        </label>
                        <input type="text" 
                               name="subtitle" 
                               id="subtitle" 
                               value="{{ $subtitle }}"
                               class="w-full px-4 py-3 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white placeholder-[#6a6a6a] focus:outline-none focus:border-[#6a6a6a] transition-colors"
                               placeholder="Enter subtitle text">
                        <p class="mt-2 text-xs text-[#6a6a6a]">This text appears below the main logo in the hero section</p>
                    </div>

                    <!-- Slider Background Images -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#9a9a9a] mb-2">
                            Slider Background Images
                        </label>
                        <p class="text-xs text-[#6a6a6a] mb-4">Manage the background images for the hero slider. You can upload images or use URLs.</p>
                        
                        <!-- Current Slider Images -->
                        <div class="mb-4">
                            <p class="text-xs text-[#6a6a6a] mb-2">Current Slider Images:</p>
                            <div id="currentSliders" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                @foreach($sliderImages as $index => $imageUrl)
                                <div class="slider-item bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-2">
                                    <div class="relative aspect-video mb-2">
                                        <img src="{{ $imageUrl }}" alt="Slider {{ $index + 1 }}" class="w-full h-full object-cover rounded">
                                    </div>
                                    <input type="hidden" name="slider_urls[]" value="{{ $imageUrl }}">
                                    <button type="button" onclick="removeSlider(this)" class="w-full text-xs bg-red-900/20 border border-red-500/50 text-red-400 px-2 py-1 rounded hover:bg-red-900/30 transition-colors">
                                        Remove
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Add New Slider Images -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-[#6a6a6a] mb-2">Upload New Images:</p>
                                <input type="file" 
                                       name="slider_images[]" 
                                       id="slider_images" 
                                       accept="image/*"
                                       multiple
                                       class="block w-full text-sm text-[#9a9a9a] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#3a3a3a] file:cursor-pointer border border-[#4a4a4a] rounded-lg bg-[#0f0f0f] focus:outline-none focus:border-[#6a6a6a] transition-colors">
                                @include('components.upload-note', ['extra' => 'Bisa pilih beberapa gambar sekaligus untuk banner/slider.'])
                            </div>

                            <div>
                                <p class="text-xs text-[#6a6a6a] mb-2">Or Add Image URL:</p>
                                <div class="flex gap-2">
                                    <input type="url" 
                                           id="newSliderUrl" 
                                           placeholder="https://example.com/image.jpg"
                                           class="flex-1 px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white placeholder-[#6a6a6a] focus:outline-none focus:border-[#6a6a6a] transition-colors">
                                    <button type="button" onclick="addSliderUrl()" class="bg-[#2a2a2a] border border-[#4a4a4a] text-white px-4 py-2 rounded-lg hover:bg-[#3a3a3a] transition-colors">
                                        Add URL
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Preview New Uploaded Images -->
                        <div id="newSlidersPreview" class="mt-4 hidden">
                            <p class="text-xs text-[#6a6a6a] mb-2">New Images Preview:</p>
                            <div id="newSlidersContainer" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4"></div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" 
                                class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Update Section
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

        // Preview slider images before upload
        document.getElementById('slider_images').addEventListener('change', function(e) {
            const files = e.target.files;
            const container = document.getElementById('newSlidersContainer');
            container.innerHTML = '';
            
            if (files.length > 0) {
                document.getElementById('newSlidersPreview').classList.remove('hidden');
                
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-2';
                        div.innerHTML = `
                            <div class="relative aspect-video mb-2">
                                <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-full object-cover rounded">
                            </div>
                            <p class="text-xs text-[#6a6a6a] text-center">${file.name}</p>
                        `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                document.getElementById('newSlidersPreview').classList.add('hidden');
            }
        });

        // Add slider URL
        function addSliderUrl() {
            const urlInput = document.getElementById('newSliderUrl');
            const url = urlInput.value.trim();
            
            if (url) {
                const container = document.getElementById('currentSliders');
                const div = document.createElement('div');
                div.className = 'slider-item bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-2';
                div.innerHTML = `
                    <div class="relative aspect-video mb-2">
                        <img src="${url}" alt="Slider" class="w-full h-full object-cover rounded" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' viewBox=\\'0 0 400 300\\'%3E%3Crect fill=\\'%23333\\' width=\\'400\\' height=\\'300\\'/%3E%3Ctext fill=\\'%23999\\' font-family=\\'sans-serif\\' font-size=\\'14\\' x=\\'50%25\\' y=\\'50%25\\' text-anchor=\\'middle\\' dominant-baseline=\\'middle\\'%3EInvalid URL%3C/text%3E%3C/svg%3E'">
                    </div>
                    <input type="hidden" name="slider_urls[]" value="${url}">
                    <button type="button" onclick="removeSlider(this)" class="w-full text-xs bg-red-900/20 border border-red-500/50 text-red-400 px-2 py-1 rounded hover:bg-red-900/30 transition-colors">
                        Remove
                    </button>
                `;
                container.appendChild(div);
                urlInput.value = '';
            }
        }

        // Remove slider
        function removeSlider(button) {
            button.closest('.slider-item').remove();
        }
    </script>
</body>
</html>
