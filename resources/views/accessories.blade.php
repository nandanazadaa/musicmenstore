<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoryInfo['title'] }} - Musicmen Store</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-[#0a0a0a] text-white">
    @include('components.navbar')

    <!-- Breadcrumb -->
    <div class="container mx-auto max-w-7xl px-6 lg:px-16 pt-8 pb-4">
        <nav class="flex items-center space-x-2 text-sm text-[#6a6a6a]">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <a href="#" class="hover:text-white transition-colors">ITEM</a>
            <span>/</span>
            <span class="text-white">{{ $categoryInfo['title'] }}</span>
        </nav>
    </div>

    <!-- Category Header -->
    <section class="w-full py-12 px-6 lg:px-16">
    <div class="container mx-auto max-w-7xl">
        <div class="text-center mb-12">
            <h1 class="serif-font text-4xl md:text-5xl lg:text-6xl font-light tracking-tight text-white mb-4">
                {{ $categoryInfo['title'] }} </h1>
            <div class="w-32 h-0.5 bg-white mx-auto mb-4"></div>
            <p class="text-lg text-[#9a9a9a] max-w-2xl mx-auto">
                {{ $categoryInfo['description'] }} </p>
        </div>
    </div>
</section>


    <!-- Search and Filter Section -->
    <section class="w-full px-6 lg:px-16 pb-8">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Search Bar -->
                <div class="w-full md:w-1/2 lg:w-2/5">
                    <div class="relative">
                        <input type="text"
                            id="searchInput"
                            placeholder="Search products..."
                            class="w-full py-3 pl-12 pr-4 bg-[#1a1a1a] border border-[#3a3a3a] text-white placeholder-[#6a6a6a] focus:outline-none focus:border-[#5a5a5a] transition-colors rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-[#6a6a6a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="w-full md:w-auto flex flex-wrap gap-3 items-center">
                    <label class="text-sm text-[#9a9a9a]">Filter:</label>
                    <select id="priceFilter" class="px-4 py-2 bg-[#1a1a1a] border border-[#3a3a3a] text-white rounded-lg focus:outline-none focus:border-[#5a5a5a] transition-colors text-sm">
                        <option value="">All Prices</option>
                        <option value="low">Under 500K</option>
                        <option value="medium">500K - 1.5M</option>
                        <option value="high">Above 1.5M</option>
                    </select>
                    <select id="conditionFilter" class="px-4 py-2 bg-[#1a1a1a] border border-[#3a3a3a] text-white rounded-lg focus:outline-none focus:border-[#5a5a5a] transition-colors text-sm">
                        <option value="">All Conditions</option>
                        <option value="New">New</option>
                    </select>
                    <button id="resetFilter" class="px-4 py-2 bg-transparent border border-[#4a4a4a] text-white rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all text-sm">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Accessories Section -->
    <section class="accessories-section w-full">

        <!-- Grid Accessory Cards -->
        <div class="accessories-grid" id="accessoriesGrid">
            @if(count($filteredProducts) > 0)
            @foreach($filteredProducts as $slug => $product)
            <a href="{{ route('product.detail', $slug) }}" class="accessory-card-link">
                <div class="accessory-card product-item"
                    data-name="{{ strtolower($product['name']) }}"
                    data-price="{{ str_replace('.', '', $product['price']) }}"
                    data-condition="{{ $product['condition'] ?? '' }}"
                    data-description="{{ strtolower($product['description'] ?? '') }}">
                    <div class="accessory-card-inner">
                        <div class="accessory-card-front">
                            <div class="accessory-image-container">
                                <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}"
                                    class="accessory-image">
                                @if($product['condition'])
                                <span class="accessory-badge">{{ $product['condition'] }}</span>
                                @endif
                            </div>
                            <div class="accessory-info" style="text-align: center; align-items: center;">
                                <h3 class="accessory-name" style="text-align: center; width: 100%;">{{ $product['name'] }}</h3>
                                @if($product['description'])
                                <p class="accessory-description" style="text-align: center; width: 100%;">({{ $product['description'] }})</p>
                                @endif
                                <p class="accessory-price" style="text-align: center; width: 100%;">IDR {{ $product['price'] }}</p>
                            </div>
                        </div>
                        <div class="accessory-card-back" style="text-align: center;">
                            <h3 class="accessory-back-title" style="text-align: center;">{{ $product['name'] }}</h3>
                            <p class="accessory-back-description" style="text-align: center;">
                                @if($product['description'])
                                {{ $product['description'] }}
                                @else
                                Premium quality accessory for your musical needs. Perfect for enhancing your playing experience.
                                @endif
                            </p>
                            <p class="accessory-back-price" style="text-align: center;">IDR {{ $product['price'] }}</p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
            @else
            <div class="text-center py-20 empty-products" style="width: 100%; grid-column: 1 / -1;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-[#6a6a6a] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="text-2xl font-light text-white mb-2">No products</h3>
                <p class="text-[#9a9a9a]">No products available in this category.</p>
            </div>
            @endif
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="hidden text-center py-20" style="width: 100%; grid-column: 1 / -1;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-[#6a6a6a] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="text-2xl font-light text-white mb-2">No results</h3>
            <p class="text-[#9a9a9a]">No products match your filters.</p>
        </div>

        <!-- All Accessories Button -->
        <div class="text-center mt-12 pb-12">
            <a href="{{ route('accessories') }}"
                class="inline-block bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg uppercase tracking-wider hover:bg-white hover:text-[#0a0a0a] transition-all duration-300 font-medium text-sm">
                ALL ACCESSORIES
            </a>
        </div>
    </section>

    <style>
        .accessory-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            width: 100%;
            height: 100%;
        }

        .accessory-card-link:hover {
            text-decoration: none;
        }

        /* Desktop: Hover untuk flip - hanya untuk device dengan hover support */
        @media (hover: hover) and (pointer: fine) and (min-width: 769px) {
            .accessory-card-link:hover .accessory-card .accessory-card-inner {
                transform: rotateY(180deg);
            }
        }

        /* Mobile: Disable hover effect */
        @media (max-width: 768px) {
            .accessory-card-link:hover .accessory-card .accessory-card-inner {
                transform: none;
            }
        }

        /* Ensure card maintains aspect ratio within link */
        .accessory-card-link .accessory-card {
            width: 100%;
            height: 100%;
        }
    </style>

    @include('components.footer')

    <!-- Member Registration Modal -->
    <div id="memberModal" class="modal-overlay">
        <div class="modal-container">
            <button id="closeModal" class="modal-close">&times;</button>
            <h2 class="modal-title">Member Registration</h2>
            <div class="text-center py-4">
                <p class="text-white mb-6">Daftar sebagai anggota untuk mendapatkan akses ke profil Anda dan fitur eksklusif lainnya.</p>
                <a href="{{ route('member.register') }}"
                    class="inline-block bg-transparent border border-[#4a4a4a] text-white px-8 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                    Register Now
                </a>
                <p class="text-[#9a9a9a] text-sm mt-4">
                    Already have an account?
                    <a href="{{ route('member.login') }}" class="text-white hover:text-[#6a6a6a] transition-colors">Login</a>
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const priceFilter = document.getElementById('priceFilter');
            const conditionFilter = document.getElementById('conditionFilter');
            const resetFilter = document.getElementById('resetFilter');
            const accessoriesGrid = document.getElementById('accessoriesGrid');
            const noResults = document.getElementById('noResults');
            const productItems = document.querySelectorAll('.product-item');

            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const priceValue = priceFilter.value;
                const conditionValue = conditionFilter.value;
                let visibleCount = 0;

                productItems.forEach(item => {
                    const name = item.getAttribute('data-name') || '';
                    const description = item.getAttribute('data-description') || '';
                    const price = parseInt(item.getAttribute('data-price')) || 0;
                    const condition = item.getAttribute('data-condition') || '';

                    // Search filter
                    const matchesSearch = searchTerm === '' ||
                        name.includes(searchTerm) ||
                        description.includes(searchTerm);

                    // Price filter
                    let matchesPrice = true;
                    if (priceValue !== '') {
                        switch (priceValue) {
                            case 'low':
                                matchesPrice = price < 500000;
                                break;
                            case 'medium':
                                matchesPrice = price >= 500000 && price < 1500000;
                                break;
                            case 'high':
                                matchesPrice = price >= 1500000;
                                break;
                        }
                    }

                    // Condition filter
                    const matchesCondition = conditionValue === '' || condition === conditionValue;

                    // Show/hide product
                    if (matchesSearch && matchesPrice && matchesCondition) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide no results message
                const emptyProducts = document.querySelector('.empty-products');
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                    if (emptyProducts) {
                        emptyProducts.style.display = 'none';
                    }
                } else {
                    noResults.classList.add('hidden');
                    if (emptyProducts) {
                        emptyProducts.style.display = 'none';
                    }
                }
            }

            // Event listeners
            if (searchInput) searchInput.addEventListener('input', filterProducts);
            if (priceFilter) priceFilter.addEventListener('change', filterProducts);
            if (conditionFilter) conditionFilter.addEventListener('change', filterProducts);

            if (resetFilter) {
                resetFilter.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (priceFilter) priceFilter.value = '';
                    if (conditionFilter) conditionFilter.value = '';
                    filterProducts();
                });
            }

            // Mobile: Click to flip card, click back to navigate
            const accessoryCardLinks = document.querySelectorAll('.accessory-card-link');
            accessoryCardLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        const card = this.querySelector('.accessory-card');
                        if (card) {
                            // If card is not flipped, flip it and prevent navigation
                            if (!card.classList.contains('flipped')) {
                                e.preventDefault();
                                e.stopPropagation();
                                card.classList.add('flipped');
                                return false;
                            }
                            // If card is already flipped, allow navigation to proceed
                        }
                    }
                    // Desktop: allow navigation normally (hover will handle flip)
                });
            });
        });

        // Member Modal Functionality
        const memberModal = document.getElementById('memberModal');
        const closeModal = document.getElementById('closeModal');

        // Open Modal function - can be called from navbar script
        window.openMemberModal = function() {
            if (memberModal) {
                memberModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        };

        // Close Modal
        if (closeModal && memberModal) {
            closeModal.addEventListener('click', function() {
                memberModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        }

        // Close Modal when clicking outside
        if (memberModal) {
            memberModal.addEventListener('click', function(e) {
                if (e.target === memberModal) {
                    memberModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        }

        // Close Modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && memberModal && memberModal.classList.contains('active')) {
                memberModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        // Scroll Animation for Accessory Cards
        document.addEventListener('DOMContentLoaded', function() {
            const accessoryCards = document.querySelectorAll('.accessory-card');
            accessoryCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 100);
            });
        });
    </script>
    @endpush

    @stack('scripts')
</body>

</html>