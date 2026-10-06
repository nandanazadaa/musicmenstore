<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Merchandise Section - Musicmen Admin</title>
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

            @include('components.admin-alerts')

            <div class="mb-12">
                <h1 class="serif-font text-3xl text-white mb-6 uppercase tracking-widest">Merchandise Section Title</h1>
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                    <form action="{{ route('admin.landing.merchandise.header') }}" method="POST">
                        @csrf
                        <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-3">Judul Section (Contoh: MERCHANDISE)</label>
                        <div class="flex flex-col md:flex-row gap-4">
                            <input type="text" name="title" value="{{ $title }}"
                                class="flex-1 bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white focus:outline-none focus:border-white transition-all">
                            <button type="submit"
                                class="bg-white text-black px-8 py-3 rounded font-bold text-sm uppercase tracking-wider hover:bg-gray-200 transition-all shadow-lg">
                                Update Title
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mb-12">
                <h2 class="serif-font text-2xl text-white mb-6 uppercase tracking-widest">Add New Merchandise Item</h2>
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-8">
                    <form action="{{ route('admin.landing.merchandise.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-2">Nama Merchandise</label>
                                <input type="text" name="nama_barang" required
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white focus:outline-none focus:border-white transition-all"
                                    placeholder="Contoh: Musicmen Official T-Shirt">
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-2">Harga (IDR)</label>
                                <input type="number" name="harga" required
                                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white focus:outline-none focus:border-white transition-all"
                                    placeholder="Contoh: 150000">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-2">Deskripsi Singkat</label>
                            <textarea name="bonus" rows="4"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white focus:outline-none focus:border-white transition-all"
                                placeholder="High quality cotton 30s, available in S, M, L, XL..."></textarea>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pt-4 border-t border-[#2a2a2a]">
                            <div class="flex-1">
                                <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-3">Upload Image</label>
                                <input type="file" name="image" required
                                    class="block w-full text-sm text-[#9a9a9a]
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-[#2a2a2a] file:text-white
                                              hover:file:bg-[#3a3a3a] cursor-pointer">
                                @include('components.upload-note')
                            </div>

                            <button type="submit"
                                class="bg-white text-black px-10 py-4 rounded font-bold text-sm uppercase tracking-widest hover:bg-gray-200 transition-all shadow-xl self-end">
                                Add Merchandise
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg overflow-hidden">
                <div class="p-6 border-b border-[#4a4a4a]">
                    <h2 class="text-lg uppercase tracking-widest">Current Merchandise Items</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#2a2a2a] text-[#9a9a9a] text-xs uppercase tracking-widest">
                                <th class="py-4 px-6">Image</th>
                                <th class="py-4 px-6">Item Name</th>
                                <th class="py-4 px-6">Description</th>
                                <th class="py-4 px-6">Price</th>
                                <th class="py-4 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2a2a2a]">
                            @forelse($merchandise as $item)
                            <tr class="hover:bg-[#252525] transition-colors">
                                <td class="py-4 px-6">
                                    <div class="h-16 w-16 bg-[#0f0f0f] rounded border border-[#4a4a4a] p-1">
                                        <img src="{{ asset($item->image) }}" class="h-full w-full object-contain" id="img-{{ $item->id }}">
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-medium">{{ $item->nama_barang }}</td>
                                <td class="py-4 px-6 text-[#9a9a9a] text-xs max-w-xs">{{ $item->bonus }}</td>
                                <td class="py-4 px-6 font-mono text-white">IDR {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-right flex justify-end gap-2">
                                    <button onclick="openEditModal({{ $item }})"
                                        class="text-blue-500 hover:text-white border border-blue-500 hover:bg-blue-500 px-4 py-1 rounded text-xs uppercase transition-all">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.landing.merchandise.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus merchandise ini?')"
                                            class="text-red-500 hover:text-white border border-red-500 hover:bg-red-500 px-4 py-1 rounded text-xs uppercase transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="editModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black bg-opacity-75" onclick="closeEditModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8">
                    <h3 class="serif-font text-2xl text-white mb-6 uppercase tracking-widest">Edit Merchandise</h3>

                    <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-2">Nama Merchandise</label>
                            <input type="text" name="nama_barang" id="edit_nama" required
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white focus:outline-none focus:border-white">
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-2">Harga (IDR)</label>
                            <input type="number" name="harga" id="edit_harga" required
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white focus:outline-none focus:border-white">
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-2">Deskripsi</label>
                            <textarea name="bonus" id="edit_bonus" rows="3"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded px-4 py-3 text-white focus:outline-none focus:border-white"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-[#9a9a9a] mb-2">Ganti Gambar (Opsional)</label>
                            <div class="flex items-center gap-4">
                                <img id="edit_preview" src="" class="h-16 w-16 object-contain bg-[#0f0f0f] rounded border border-[#4a4a4a]">
                                <input type="file" name="image" class="text-xs text-[#9a9a9a]">
                            </div>
                            @include('components.upload-note')
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs uppercase tracking-widest text-[#9a9a9a] hover:text-white transition-all">Cancel</button>
                            <button type="submit" class="bg-white text-black px-6 py-2 rounded font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>


    <script>
        function openEditModal(item) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');

            // Isi data ke input modal
            document.getElementById('edit_nama').value = item.nama_barang;
            document.getElementById('edit_harga').value = item.harga;
            document.getElementById('edit_bonus').value = item.bonus;
            document.getElementById('edit_preview').src = `/${item.image}`;

            // Update Action URL Form
            form.action = `/admin/landing/merchandise/${item.id}`;

            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>

</html>
