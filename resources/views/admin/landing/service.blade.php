<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Service Section - Musicmen Admin</title>
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

<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-full">
            <div class="mb-12">
                <h1 class="serif-font text-3xl text-white mb-2 uppercase tracking-widest">Service Section Management
                </h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            @include('components.admin-alerts')

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 mb-10">
                <h2 class="text-xl mb-6 uppercase tracking-wider">Section Header</h2>
                <form action="{{ route('admin.landing.service.header') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Title</label>
                            <input type="text" name="title" value="{{ $title }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white focus:border-white outline-none">
                        </div>
                        <div>
                            <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Subtitle</label>
                            <input type="text" name="subtitle" value="{{ $subtitle }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white focus:border-white outline-none">
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-white text-black px-6 py-2 rounded font-bold uppercase text-xs hover:bg-gray-200 transition-all">Update
                        Header</button>
                </form>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 mb-10">
                <h2 class="text-xl mb-6 uppercase tracking-wider">Add New Service Item</h2>
                <form action="{{ route('admin.landing.service.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Service Name</label>
                                <input type="text" name="title" required
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white focus:border-white outline-none">
                            </div>
                            <div>
                                <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Icon/Image</label>
                                <input type="file" name="icon" required
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white file:bg-[#2a2a2a] file:border-none file:text-white file:mr-4 file:rounded file:text-xs file:uppercase">
                                @include('components.upload-note')
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-[#9a9a9a] uppercase mb-2">Description</label>
                            <textarea name="description" rows="4" required
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-2 text-white focus:border-white outline-none"></textarea>
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-white text-black px-6 py-2 rounded font-bold uppercase text-xs hover:bg-gray-200 transition-all">Add
                        Service</button>
                </form>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#2a2a2a] text-[#9a9a9a] text-xs uppercase tracking-widest">
                            <th class="py-4 px-6">Icon</th>
                            <th class="py-4 px-6">Service Name</th>
                            <th class="py-4 px-6">Description</th>
                            <th class="py-4 px-6">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2a2a2a]">
                        @foreach ($services as $svc)
                            <tr>
                                <td class="py-4 px-6">
                                    <img src="{{ asset($svc->icon) }}"
                                        class="h-10 w-10 object-contain brightness-0 invert">
                                </td>
                                <td class="py-4 px-6 font-bold">{{ $svc->title }}</td>
                                <td class="py-4 px-6 text-[#9a9a9a] text-sm">{{ Str::limit($svc->description, 100) }}
                                </td>
                                <td class="py-4 px-6">
                                    <form action="{{ route('admin.landing.service.destroy', $svc->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus service ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-400 uppercase text-[10px] font-bold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>
