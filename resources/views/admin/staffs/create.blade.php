<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Add Staff - Musicmen Admin</title>
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
        #photoPreview {
            display: none;
        }
        #photoPreview.show {
            display: block;
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
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Add New Staff</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            <!-- Form -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <form method="POST" action="{{ route('admin.staffs.store') }}" enctype="multipart/form-data">
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
                            <label for="id_employee" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">ID Employee</label>
                            <input type="text" id="id_employee" value="Auto-generated" 
                                   class="w-full bg-[#2a2a2a] border border-[#4a4a4a] rounded-lg px-4 py-3 text-[#6a6a6a] cursor-not-allowed"
                                   disabled>
                            <p class="text-xs text-[#6a6a6a] mt-1">ID Employee akan otomatis dibuat saat staff ditambahkan</p>
                        </div>

                        <div>
                            <label for="nama" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>

                        <div>
                            <label for="nomor_telepon" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nomor Telepon</label>
                            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>

                        <div>
                            <label for="jabatan" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Jabatan</label>
                            <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                   required>
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Link User Account</label>
                            <select id="user_id" name="user_id" 
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                                <option value="">-- Select User Account (Optional) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-[#6a6a6a] mt-1">Pilih user account untuk menghubungkan staff dengan akun login. Wajib diisi jika staff ingin akses Attendance & Assignments.</p>
                        </div>

                        <div>
                            <label for="photo_profile" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Photo Profile</label>
                            <input type="file" id="photo_profile" name="photo_profile" accept="image/*" 
                                   class="block w-full text-sm text-[#9a9a9a] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#3a3a3a] file:cursor-pointer border border-[#4a4a4a] rounded-lg bg-[#0f0f0f] focus:outline-none focus:border-[#6a6a6a] transition-colors"
                                   onchange="previewPhoto(this)">
                            <div id="photoPreview" class="mt-4">
                                <img id="photoPreviewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-full border border-[#4a4a4a]">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <button type="submit" 
                                class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Create Staff
                        </button>
                        <a href="{{ route('admin.staffs.index') }}" 
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
        function previewPhoto(input) {
            const preview = document.getElementById('photoPreview');
            const previewImg = document.getElementById('photoPreviewImg');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.add('show');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.remove('show');
            }
        }
    </script>
</body>
</html>
