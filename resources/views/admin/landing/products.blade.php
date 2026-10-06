<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Section - Musicmen Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <div class="mb-8 w-full">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Products Section</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('admin.landing.products.import') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                                Import dari Landing Page
                            </button>
                        </form>
                        <button onclick="openCreateModal()"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                            Tambah Product
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 w-full mb-6">
                <h2 class="text-xl font-medium text-white mb-4">Section Title</h2>
                <form action="{{ route('admin.landing.products.header.update') }}" method="POST">
                    @csrf
                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-sm text-[#9a9a9a] mb-2">Title</label>
                            <input type="text" name="title" value="{{ $title }}"
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        </div>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-all uppercase tracking-wider">
                            Update Title
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 w-full mb-6">
                <h2 class="text-xl font-medium text-white mb-2">Global Payment Options</h2>
                <p class="text-sm text-[#9a9a9a] mb-4">Kelola ikon pembayaran yang akan muncul di semua detail produk.
                </p>

                <form action="{{ route('admin.landing.payments.store') }}" method="POST" enctype="multipart/form-data"
                    class="mb-6">
                    @csrf
                    <div
                        class="flex flex-col sm:flex-row gap-4 items-end bg-[#0f0f0f] p-4 rounded-lg border border-[#333]">
                        <div class="flex-1 w-full">
                            <label class="block text-xs text-[#9a9a9a] mb-2 uppercase tracking-widest">Tambah Ikon
                                Pembayaran</label>
                            <input type="file" name="icon" required
                                class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                            @include('components.upload-note', ['formats' => 'JPG, JPEG, PNG, SVG, WebP'])
                        </div>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-all font-bold">
                            UPLOAD
                        </button>
                    </div>
                </form>

                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
                    @php
                    $paymentIconsJson = \App\Models\LandingPageSetting::getValue('products', 'payment_icons', '[]');
                    $paymentIcons = json_decode($paymentIconsJson, true) ?: [];
                    @endphp
                    @forelse($paymentIcons as $icon)
                    <div class="relative group bg-[#0f0f0f] p-3 border border-[#4a4a4a] rounded-lg flex items-center justify-center h-16">
                        <img src="{{ asset($icon) }}" class="max-h-full max-w-full object-contain">

                        <form action="{{ route('admin.landing.payments.destroy') }}" method="POST"
                            class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE') <input type="hidden" name="icon_path" value="{{ $icon }}">
                            <button type="submit" onclick="return confirm('Hapus ikon ini?')"
                                class="bg-red-600 text-white rounded-full p-1 hover:bg-red-700 shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="col-span-full text-xs text-[#6a6a6a] italic">Belum ada ikon pembayaran global.</p>
                    @endforelse
                </div>
            </div>

            @include('components.admin-alerts')

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 w-full">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-medium text-white">Daftar Produk Landing</h2>
                        <p class="text-sm text-[#9a9a9a] mt-1">Cari dan kelola produk yang tampil di landing page.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.landing.products') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 w-full lg:max-w-4xl">
                        <div class="sm:col-span-2">
                            <label class="block text-xs text-[#9a9a9a] mb-2 uppercase tracking-wider">Search</label>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, bonus, deskripsi..."
                                class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white outline-none focus:border-[#6a6a6a]">
                        </div>
                        <div>
                            <label class="block text-xs text-[#9a9a9a] mb-2 uppercase tracking-wider">Category</label>
                            <select name="category" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white outline-none focus:border-[#6a6a6a]">
                                <option value="">Semua</option>
                                @foreach(['acoustic' => 'Acoustic', 'electric' => 'Electric', 'bass' => 'Bass', 'amplifier' => 'Amplifier', 'effect' => 'Effect'] as $value => $label)
                                    <option value="{{ $value }}" @selected(($category ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-[#9a9a9a] mb-2 uppercase tracking-wider">Kondisi</label>
                            <select name="kondisi" class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white outline-none focus:border-[#6a6a6a]">
                                <option value="">Semua</option>
                                @foreach(['new' => 'New', 'great' => 'Great', 'good' => 'Good', 'used' => 'Used'] as $value => $label)
                                    <option value="{{ $value }}" @selected(($kondisi ?? '') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-4 flex flex-wrap gap-3 justify-end">
                            <a href="{{ route('admin.landing.products') }}" class="px-5 py-2 border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] transition-all">Reset</a>
                            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white transition-all">Cari</button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Order
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Image
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Nama
                                    Barang</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Kondisi
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Category
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Harga
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Bonus
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                <td class="py-3 px-4 text-white text-sm">{{ $product->order }}</td>
                                <td class="py-3 px-4">
                                    @if ($product->image)
                                    <img src="{{ asset($product->image) }}"
                                        class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                    <span class="text-[#6a6a6a] text-xs">No image</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-white text-sm">{{ $product->nama_barang }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <span
                                        class="px-2 py-1 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-xs">{{ $product->kondisi_name }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <span
                                        class="px-2 py-1 bg-purple-900/30 border border-purple-500/50 rounded text-purple-400 text-xs">{{ $product->category_name }}</span>
                                </td>
                                <td class="py-3 px-4 text-white text-sm">{{ $product->formatted_price }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <span
                                        class="text-[#9a9a9a] text-xs">{{ Str::limit($product->bonus, 30) }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <button onclick="openEditModal({{ $product->id }})"
                                            class="text-blue-400 hover:text-blue-300 transition-colors"><svg
                                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path
                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                </path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                </path>
                                            </svg></button>
                                        <form method="POST"
                                            action="{{ route('admin.landing.products.destroy', $product->id) }}"
                                            class="inline" onsubmit="return confirm('Hapus product ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-400 hover:text-red-300 transition-colors"><svg
                                                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                </svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-8 px-4 text-center text-[#9a9a9a]">
                                    @if(($search ?? '') || ($category ?? '') || ($kondisi ?? ''))
                                        Tidak ada produk yang cocok dengan filter.
                                    @else
                                        Belum ada product
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </main>

    <div id="productModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-semibold text-white">Tambah Product</h2>
                <button onclick="closeModal()" class="text-[#9a9a9a] hover:text-white"><svg
                        xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <form id="productForm" method="POST" enctype="multipart/form-data">
                @csrf <div id="formMethod" style="display: none;"></div>
                <input type="hidden" name="product_id" id="product_id" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Nama Barang *</label>
                        <input type="text" name="nama_barang" id="nama_barang" required
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2">Kondisi *</label>
                        <select name="kondisi" id="kondisi" required
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                            <option value="new">New</option>
                            <option value="great">Great</option>
                            <option value="good">Good</option>
                            <option value="used">Used</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2">Harga *</label>
                        <input type="number" name="harga" id="harga" required
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                    </div>
                    <!-- Existing Images (for edit mode) -->
                    <div class="md:col-span-2" id="existingImagesContainer" style="display: none;">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Gambar Saat Ini</label>
                        <div id="existingImagesGrid" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4"></div>
                    </div>

                    <!-- Main Image (single, for backward compatibility) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Image Utama *</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                        @include('components.upload-note')
                        <div id="imagePreview" class="mt-2 hidden"><img id="previewImg" src=""
                                class="w-24 h-24 object-cover rounded border border-[#444]"></div>
                    </div>

                    <!-- Multiple Images -->
                    <div class="md:col-span-2">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Gambar Tambahan (Multiple)</label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                        @include('components.upload-note', ['extra' => 'Anda dapat memilih maksimal 10 gambar.'])
                        <div id="imagesPreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    </div>
                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2">Category *</label>
                        <select name="category" id="category" required
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                            <option value="acoustic">Acoustic</option>
                            <option value="electric">Electric</option>
                            <option value="bass">Bass</option>
                            <option value="amplifier">Amplifier</option>
                            <option value="effect">Effect</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2">Order</label>
                        <input type="number" name="order" id="order" value="0"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Bonus</label>
                        <textarea name="bonus" id="bonus" rows="2"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-[#9a9a9a] mb-2">Order Information</label>
                        <textarea name="order_info" id="order_info" rows="2"
                            class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white"></textarea>
                    </div>
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold uppercase transition-all">Simpan</button>
                    <button type="button" onclick="closeModal()"
                        class="bg-transparent border border-[#4a4a4a] text-white px-8 py-3 rounded-lg hover:bg-[#2a2a2a] transition-all">Batal</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.admin-footer')
    <script>
        // Pastikan data produk dipassing dengan benar ke JS
        const products = @json($productsForJs);
        const oldInput = @json(old());
        const hasValidationErrors = @json($errors->any());

        function openCreateModal() {
            // Reset Judul dan Action
            document.getElementById('modalTitle').textContent = 'Tambah Product';
            document.getElementById('productForm').action = '{{ route("admin.landing.products.store") }}';

            // Bersihkan Form
            document.getElementById('productForm').reset();
            document.getElementById('formMethod').innerHTML = ''; // Pastikan method PUT dibersihkan
            document.getElementById('product_id').value = '';

            // Sembunyikan container preview
            document.getElementById('existingImagesContainer').style.display = 'none';
            document.getElementById('existingImagesGrid').innerHTML = '';
            document.getElementById('imagesPreview').innerHTML = '';
            document.getElementById('imagePreview').classList.add('hidden');

            // Tampilkan Modal
            document.getElementById('productModal').classList.remove('hidden');
        }

        function openEditModal(id) {
            const p = products.find(i => i.id === id);
            if (!p) return;

            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('productForm').action = `{{ url('admin/landing/products') }}/${id}`;
            document.getElementById('product_id').value = id;

            // PERBAIKAN DISINI: Menghindari line-break pada innerHTML
            document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Isi nilai input
            document.getElementById('nama_barang').value = p.nama_barang;
            document.getElementById('kondisi').value = p.kondisi;
            document.getElementById('harga').value = p.harga;
            document.getElementById('category').value = p.category;
            document.getElementById('order').value = p.order;
            document.getElementById('bonus').value = p.bonus || '';
            document.getElementById('description').value = p.description || '';
            document.getElementById('order_info').value = p.order_info || '';

            // Tampilkan gambar yang sudah ada
            const existingContainer = document.getElementById('existingImagesContainer');
            const existingGrid = document.getElementById('existingImagesGrid');

            if (p.images && p.images.length > 0) {
                existingContainer.style.display = 'block';
                existingGrid.innerHTML = '';
                p.images.forEach((img) => {
                    const div = document.createElement('div');
                    div.className = 'relative existing-image-item';
                    div.innerHTML = `
                    <img src="/${img.image_path}" class="w-full h-32 object-cover rounded-lg border border-[#4a4a4a]">
                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs delete-existing-image" data-image-id="${img.id}">×</button>
                `;
                    existingGrid.appendChild(div);
                });
            } else {
                existingContainer.style.display = 'none';
            }

            // Preview image utama
            if (p.image) {
                document.getElementById('previewImg').src = '/' + p.image;
                document.getElementById('imagePreview').classList.remove('hidden');
            } else {
                document.getElementById('imagePreview').classList.add('hidden');
            }

            document.getElementById('imagesPreview').innerHTML = '';
            document.getElementById('productModal').classList.remove('hidden');
        }

        function restoreOldInput() {
            if (!hasValidationErrors || Object.keys(oldInput).length === 0) {
                return;
            }

            if (oldInput.product_id) {
                openEditModal(Number(oldInput.product_id));
            } else {
                openCreateModal();
            }

            [
                'nama_barang',
                'kondisi',
                'harga',
                'category',
                'order',
                'bonus',
                'description',
                'order_info',
            ].forEach((field) => {
                if (Object.prototype.hasOwnProperty.call(oldInput, field)) {
                    const input = document.getElementById(field);
                    if (input) {
                        input.value = oldInput[field] ?? '';
                    }
                }
            });
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        // --- Script pendukung tetap sama ---

        // Handle multiple images preview
        document.getElementById('images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagesPreview');
            preview.innerHTML = '';
            const files = Array.from(e.target.files);

            if (files.length > 10) {
                alert('Maksimal 10 gambar yang dapat diupload');
                e.target.value = '';
                return;
            }

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-[#4a4a4a]">
                        <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs remove-image" data-index="${index}">×</button>
                    `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Event Delegation untuk tombol hapus
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-image')) {
                const index = parseInt(e.target.getAttribute('data-index'));
                const input = document.getElementById('images');
                const dt = new DataTransfer();
                const files = Array.from(input.files);
                files.splice(index, 1);
                files.forEach(file => dt.items.add(file));
                input.files = dt.files;
                e.target.closest('.relative').remove();
            }

            if (e.target.classList.contains('delete-existing-image')) {
                const imageId = e.target.getAttribute('data-image-id');
                const imageItem = e.target.closest('.existing-image-item');
                if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                    imageItem.remove();
                    const form = document.getElementById('productForm');
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'deleted_images[]';
                    deleteInput.value = imageId;
                    form.appendChild(deleteInput);
                }
            }
        });

        restoreOldInput();
    </script>
</body>

</html>
