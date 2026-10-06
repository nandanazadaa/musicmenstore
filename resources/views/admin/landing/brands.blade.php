<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Edit Brands Section - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Brands Section</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                </div>
            </div>

            @include('components.admin-alerts')

            <!-- Section Header Form -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full mb-6">
                <h2 class="text-xl font-medium text-white mb-4">Section Header</h2>
                <form action="{{ route('admin.landing.brands.header.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Guitar Section Title</label>
                            <input type="text" name="title" value="{{ $title }}" class="w-full px-4 py-3 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white focus:outline-none focus:border-[#6a6a6a]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Guitar Section Subtitle</label>
                            <input type="text" name="subtitle" value="{{ $subtitle }}" class="w-full px-4 py-3 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white focus:outline-none focus:border-[#6a6a6a]">
                        </div>
                    
                        <div>
                            <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Accessories Section Title</label>
                            <input type="text" name="acc_title" value="{{ $acc_title }}" class="w-full px-4 py-3 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white focus:outline-none focus:border-[#6a6a6a]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Accessories Section Subtitle</label>
                            <input type="text" name="acc_subtitle" value="{{ $acc_subtitle }}" class="w-full px-4 py-3 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white focus:outline-none focus:border-[#6a6a6a]">
                        </div>
                    </div>

                    <!-- Logo Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#9a9a9a] mb-2">
                            Logo (Optional)
                        </label>
                        @if($logo)
                        <div class="mb-4">
                            <p class="text-xs text-[#6a6a6a] mb-2">Current Logo:</p>
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4 inline-block">
                                <img src="{{ asset($logo) }}" alt="Current Logo" class="max-w-[200px] max-h-[100px] object-contain">
                            </div>
                        </div>
                        @endif
                        <input type="file" 
                               name="logo" 
                               id="logo" 
                               accept="image/*"
                               class="block w-full text-sm text-[#9a9a9a] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#3a3a3a] file:cursor-pointer border border-[#4a4a4a] rounded-lg bg-[#0f0f0f] focus:outline-none focus:border-[#6a6a6a] transition-colors">
                        @include('components.upload-note')
                    </div>

                    <button type="submit" 
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Update Header
                    </button>
                </form>
            </div>

            <!-- Brands Management -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <!-- Tabs -->
                <div class="flex gap-4 mb-6 border-b border-[#4a4a4a]">
                    <button onclick="switchTab('guitar')" id="tab-guitar" class="tab-btn px-4 py-2 border-b-2 border-[#6a6a6a] text-white font-medium">
                        Guitar Brands
                    </button>
                    <button onclick="switchTab('accessories')" id="tab-accessories" class="tab-btn px-4 py-2 border-b-2 border-transparent text-[#9a9a9a] hover:text-white transition-colors">
                        Accessories Brands
                    </button>
                </div>

                <!-- Add Brand Form -->
                <div class="mb-6 p-4 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg">
                    <h3 class="text-lg font-medium text-white mb-4">Add New Brand</h3>
                    <form id="brandForm" action="{{ route('admin.landing.brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Type</label>
                                <select name="type" id="brandType" required
                                        class="w-full px-4 py-2 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg text-white focus:outline-none focus:border-[#6a6a6a] transition-colors">
                                    <option value="guitar" selected>Guitar</option>
                                    <option value="accessories">Accessories</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Name</label>
                                <input type="text" name="name" required
                                       class="w-full px-4 py-2 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg text-white focus:outline-none focus:border-[#6a6a6a] transition-colors"
                                       placeholder="Brand Name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Logo</label>
                                <input type="file" name="logo" required accept="image/*"
                                       class="block w-full text-sm text-[#9a9a9a] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#3a3a3a] file:cursor-pointer border border-[#4a4a4a] rounded-lg bg-[#1a1a1a] focus:outline-none focus:border-[#6a6a6a] transition-colors">
                                @include('components.upload-note')
                            </div>
                        </div>
                        <button type="submit" class="mt-4 bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Add Brand
                        </button>
                    </form>
                </div>

                <!-- Guitar Brands List -->
                <div id="guitar-brands" class="brands-list">
                    <h3 class="text-lg font-medium text-white mb-4">Guitar Brands ({{ $guitarBrands->count() }})</h3>
                    @if($guitarBrands->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($guitarBrands as $brand)
                        <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4">
                            <div class="mb-3">
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="w-full h-20 object-contain">
                            </div>
                            <p class="text-white text-sm font-medium mb-2 text-center">{{ $brand->name }}</p>
                            <div class="flex gap-2">
                                <button onclick="editBrand({{ $brand->id }}, '{{ $brand->type }}', {{ json_encode($brand->name) }})" 
                                        class="flex-1 bg-[#2a2a2a] border border-[#4a4a4a] text-white px-3 py-2 rounded hover:bg-[#3a3a3a] transition-colors text-xs">
                                    Edit
                                </button>
                                <form action="{{ route('admin.landing.brands.destroy', $brand->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-900/20 border border-red-500/50 text-red-400 px-3 py-2 rounded hover:bg-red-900/30 transition-colors text-xs">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-[#6a6a6a] text-sm">No guitar brands yet.</p>
                    @endif
                </div>

                <!-- Accessories Brands List -->
                <div id="accessories-brands" class="brands-list hidden">
                    <h3 class="text-lg font-medium text-white mb-4">Accessories Brands ({{ $accessoriesBrands->count() }})</h3>
                    @if($accessoriesBrands->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($accessoriesBrands as $brand)
                        <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4">
                            <div class="mb-3">
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="w-full h-20 object-contain">
                            </div>
                            <p class="text-white text-sm font-medium mb-2 text-center">{{ $brand->name }}</p>
                            <div class="flex gap-2">
                                <button onclick="editBrand({{ $brand->id }}, '{{ $brand->type }}', {{ json_encode($brand->name) }})" 
                                        class="flex-1 bg-[#2a2a2a] border border-[#4a4a4a] text-white px-3 py-2 rounded hover:bg-[#3a3a3a] transition-colors text-xs">
                                    Edit
                                </button>
                                <form action="{{ route('admin.landing.brands.destroy', $brand->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-900/20 border border-red-500/50 text-red-400 px-3 py-2 rounded hover:bg-red-900/30 transition-colors text-xs">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-[#6a6a6a] text-sm">No accessories brands yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Brand Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" style="display: none;">
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-medium text-white mb-4">Edit Brand</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Type</label>
                        <select name="type" id="editType" required
                                class="w-full px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white focus:outline-none focus:border-[#6a6a6a] transition-colors">
                            <option value="guitar">Guitar</option>
                            <option value="accessories">Accessories</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Name</label>
                        <input type="text" name="name" id="editName" required
                               class="w-full px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white focus:outline-none focus:border-[#6a6a6a] transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#9a9a9a] mb-2">Logo (Leave empty to keep current)</label>
                        <input type="file" name="logo" accept="image/*"
                               class="block w-full text-sm text-[#9a9a9a] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#3a3a3a] file:cursor-pointer border border-[#4a4a4a] rounded-lg bg-[#0f0f0f] focus:outline-none focus:border-[#6a6a6a] transition-colors">
                        @include('components.upload-note')
                    </div>
                </div>
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Update
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('components.admin-footer')

    <script>
        function switchTab(type) {
            // Hide all lists
            document.getElementById('guitar-brands').classList.add('hidden');
            document.getElementById('accessories-brands').classList.add('hidden');
            
            // Remove active from all tabs
            document.getElementById('tab-guitar').classList.remove('border-[#6a6a6a]', 'text-white');
            document.getElementById('tab-guitar').classList.add('border-transparent', 'text-[#9a9a9a]');
            document.getElementById('tab-accessories').classList.remove('border-[#6a6a6a]', 'text-white');
            document.getElementById('tab-accessories').classList.add('border-transparent', 'text-[#9a9a9a]');
            
            // Show selected list and activate tab
            if (type === 'guitar') {
                document.getElementById('guitar-brands').classList.remove('hidden');
                document.getElementById('tab-guitar').classList.add('border-[#6a6a6a]', 'text-white');
                document.getElementById('tab-guitar').classList.remove('border-transparent', 'text-[#9a9a9a]');
                document.getElementById('brandType').value = 'guitar';
            } else {
                document.getElementById('accessories-brands').classList.remove('hidden');
                document.getElementById('tab-accessories').classList.add('border-[#6a6a6a]', 'text-white');
                document.getElementById('tab-accessories').classList.remove('border-transparent', 'text-[#9a9a9a]');
                document.getElementById('brandType').value = 'accessories';
            }
        }

        function editBrand(id, type, name) {
            document.getElementById('editForm').action = '{{ url("admin/landing/brands") }}/' + id;
            document.getElementById('editType').value = type;
            document.getElementById('editName').value = name;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>
