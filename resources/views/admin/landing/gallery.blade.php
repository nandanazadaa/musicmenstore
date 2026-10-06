<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Gallery Section - Musicmen Admin</title>
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
            <div class="mb-8 w-full">
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2 uppercase tracking-widest">Gallery Section</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            @include('components.admin-alerts')

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 mb-8">
                <h2 class="text-xl mb-4 uppercase tracking-wider">Add New Image</h2>
                <form action="{{ route('admin.landing.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Select Image</label>
                            <input type="file" name="image" required class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white">
                            @include('components.upload-note', ['formats' => 'JPG, JPEG, PNG, GIF, WebP'])
                        </div>
                        <button type="submit" class="bg-white text-black px-8 py-2 rounded font-bold uppercase text-sm hover:bg-gray-200 transition-all">
                            Add to Gallery
                        </button>
                    </div>
                </form>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($galleries as $gallery)
                <div class="relative group rounded-lg border border-[#4a4a4a] overflow-hidden bg-[#0f0f0f]">
                    <img src="{{ asset($gallery->image) }}" class="w-full aspect-square object-cover">
                    
                    <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center gap-3 p-4">
                        
                        <form action="{{ route('admin.landing.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data" class="w-full">
                            @csrf
                            @method('PUT')
                            <label class="block text-[10px] uppercase text-center mb-1 text-gray-400">Change Image</label>
                            <input type="file" name="image" onchange="this.form.submit()" class="hidden" id="edit-{{ $gallery->id }}">
                            <p class="text-[10px] text-center text-gray-400 mb-2">Max 10 MB. JPG/PNG otomatis jadi WebP.</p>
                            <label for="edit-{{ $gallery->id }}" class="cursor-pointer bg-white text-black text-center block w-full py-1.5 rounded text-[10px] font-bold uppercase hover:bg-gray-200">
                                Replace
                            </label>
                        </form>
            
                        <div class="h-px bg-[#4a4a4a] w-full"></div>
            
                        <form action="{{ route('admin.landing.gallery.destroy', $gallery->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white w-full py-1.5 rounded text-[10px] font-bold uppercase hover:bg-red-700" onclick="return confirm('Delete this image?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</body>
</html>
