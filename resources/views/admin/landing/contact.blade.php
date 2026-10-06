<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Contact Section - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        * {
            box-sizing: border-box;
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-8">
                <h1 class="serif-font text-3xl text-white mb-2 uppercase tracking-widest">Contact Section Management
                </h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            @include('components.admin-alerts')

            <form action="{{ route('admin.landing.contact.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <div class="space-y-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                        <h2 class="text-xl uppercase tracking-wider mb-4 border-b border-[#4a4a4a] pb-2">Main Content
                        </h2>

                        <div>
                            <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Section Title</label>
                            <input type="text" name="title" value="{{ $contactTitle }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white focus:border-white outline-none">
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Address Icon</label>
                                @if(isset($addressIconPath))
                                    <img src="{{ asset($addressIconPath) }}" class="h-8 w-8 mb-2 object-contain brightness-0 invert">
                                @endif
                                <input type="file" name="address_icon" class="w-full text-xs text-gray-400 file:bg-[#2a2a2a] file:text-white file:border-0 file:rounded file:px-4 file:py-1">
                                @include('components.upload-note')
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Address Text</label>
                                <input type="text" name="address_text" value="{{ $addressText }}"
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Phone Icon</label>
                                @if(isset($phoneIconPath))
                                    <img src="{{ asset($phoneIconPath) }}" class="h-8 w-8 mb-2 object-contain brightness-0 invert">
                                @endif
                                <input type="file" name="phone_icon" class="w-full text-xs text-gray-400 file:bg-[#2a2a2a] file:text-white file:border-0 file:rounded file:px-4 file:py-1">
                                @include('components.upload-note')
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Phone Number</label>
                                <input type="text" name="phone_text" value="{{ $phoneText }}"
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                        <h2 class="text-xl uppercase tracking-wider mb-4 border-b border-[#4a4a4a] pb-2">Media &
                            Location</h2>

                        <div>
                            <label class="block text-xs text-[#9a9a9a] uppercase mb-3">Social Media</label>
                            <div id="socialMediaContainer" class="space-y-4">
                                @if(!empty($socialMedia))
                                    @foreach($socialMedia as $index => $social)
                                        <div class="social-media-item bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4">
                                            <div class="flex items-start gap-4">
                                                <div class="flex-1">
                                                    <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Icon</label>
                                                    @if(isset($social['icon']))
                                                        <img src="{{ asset($social['icon']) }}" class="h-8 w-8 mb-2 object-contain brightness-0 invert">
                                                        <input type="hidden" name="social_media_existing_icons[]" value="{{ $social['icon'] }}">
                                                    @endif
                                                    <input type="file" name="social_media_icons[]" accept="image/*" class="w-full text-xs text-gray-400 file:bg-[#2a2a2a] file:text-white file:border-0 file:rounded file:px-4 file:py-1">
                                                    @include('components.upload-note')
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Link</label>
                                                    <input type="url" name="social_media_links[]" value="{{ $social['link'] ?? '' }}" placeholder="https://..." class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white">
                                                </div>
                                                <div class="flex items-end">
                                                    <button type="button" onclick="removeSocialMedia(this)" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="social-media-item bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-1">
                                                <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Icon</label>
                                                <input type="file" name="social_media_icons[]" accept="image/*" class="w-full text-xs text-gray-400 file:bg-[#2a2a2a] file:text-white file:border-0 file:rounded file:px-4 file:py-1">
                                                @include('components.upload-note')
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Link</label>
                                                <input type="url" name="social_media_links[]" placeholder="https://..." class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="removeSocialMedia(this)" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addSocialMedia()" class="mt-4 bg-[#2a2a2a] hover:bg-[#3a3a3a] text-white px-4 py-2 rounded text-sm">+ Tambah Social Media</button>
                        </div>

                        <div>
                            <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Google Maps Embed URL</label>
                            <p class="text-xs text-[#6a6a6a] mb-2">
                                <strong>Cara mendapatkan embed URL:</strong><br>
                                1. Buka Google Maps dan cari lokasi toko Anda<br>
                                2. Klik tombol "Bagikan" (Share)<br>
                                3. Pilih tab "Sematkan peta" (Embed a map)<br>
                                4. Salin URL dari atribut <code>src</code> pada kode iframe<br>
                                5. Tempelkan URL tersebut di bawah ini
                            </p>
                            <textarea name="maps_link" rows="4" placeholder="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d..."
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white focus:border-white outline-none font-mono text-xs">{{ $mapsLink }}</textarea>
                            <p class="text-xs text-yellow-400 mt-2">
                                ⚠️ Jangan gunakan short URL (maps.app.goo.gl). Gunakan embed URL langsung dari Google Maps.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Contact Logo</label>
                            <img src="{{ asset($contactLogoPath) }}"
                                class="h-12 mb-2 object-contain bg-[#2a2a2a] p-1 rounded border border-[#4a4a4a]">
                            <input type="file" name="logo"
                                class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-[#2a2a2a] file:text-white">
                            @include('components.upload-note')
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="bg-white text-black px-10 py-3 rounded font-bold uppercase tracking-widest hover:bg-gray-200 transition-all shadow-xl">
                        Save Contact Changes
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function addSocialMedia() {
            const container = document.getElementById('socialMediaContainer');
            const newItem = document.createElement('div');
            newItem.className = 'social-media-item bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4';
            newItem.innerHTML = `
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Icon</label>
                        <input type="file" name="social_media_icons[]" accept="image/*" class="w-full text-xs text-gray-400 file:bg-[#2a2a2a] file:text-white file:border-0 file:rounded file:px-4 file:py-1">
                        <p class="mt-2 text-xs text-[#6a6a6a] leading-relaxed">Format: JPG, JPEG, PNG, GIF, SVG, WebP. Maksimal 10 MB per file. JPG/PNG/GIF/WebP otomatis disimpan sebagai WebP.</p>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Link</label>
                        <input type="url" name="social_media_links[]" placeholder="https://..." class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeSocialMedia(this)" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">Hapus</button>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
        }

        function removeSocialMedia(button) {
            const container = document.getElementById('socialMediaContainer');
            if (container.children.length > 1) {
                button.closest('.social-media-item').remove();
            } else {
                alert('Minimal harus ada 1 social media');
            }
        }
    </script>
</body>

</html>
