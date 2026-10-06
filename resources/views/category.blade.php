<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoryInfo['title'] }} - Musicmen Store</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-[#0a0a0a] text-white">
    @include('components.navbar')

    <!-- Breadcrumb -->
    <div class="container mx-auto max-w-7xl px-6 lg:px-16 pt-8 pb-4">
        <nav class="flex items-center space-x-2 text-sm text-[#6a6a6a]">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            @if(isset($categoryInfo['title']))
                @if($categoryInfo['title'] === 'Accessories' || $categoryInfo['title'] === 'Merchandise')
                <a href="#" class="hover:text-white transition-colors">ITEM</a>
                <span>/</span>
                @elseif(isset($category))
                <a href="#" class="hover:text-white transition-colors">NEW GEAR</a>
                <span>/</span>
                @endif
            @endif
            <span class="text-white">{{ $categoryInfo['title'] }}</span>
        </nav>
    </div>

    <!-- Category Header -->
    <section class="w-full py-12 px-6 lg:px-16">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-12">
                <h1 class="serif-font text-4xl md:text-5xl lg:text-6xl font-light tracking-tight text-white mb-4">
                    {{ $categoryInfo['title'] }}
                </h1>
                <div class="w-32 h-0.5 bg-white mx-auto mb-4"></div>
                <p class="text-lg text-[#9a9a9a] max-w-2xl mx-auto">
                    {{ $categoryInfo['description'] }}
                </p>
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
                        <option value="low">Under 5 Million</option>
                        <option value="medium">5 - 15 Million</option>
                        <option value="high">15 - 25 Million</option>
                        <option value="premium">Above 25 Million</option>
                    </select>
                    <select id="conditionFilter" class="px-4 py-2 bg-[#1a1a1a] border border-[#3a3a3a] text-white rounded-lg focus:outline-none focus:border-[#5a5a5a] transition-colors text-sm">
                        <option value="">All Conditions</option>
                        <option value="Great Condition">Great Condition</option>
                    </select>
                    <button id="resetFilter" class="px-4 py-2 bg-transparent border border-[#4a4a4a] text-white rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all text-sm">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section w-full">
        <!-- Product Cards Grid -->
        <div class="products-scroll category-products-grid {{ isset($categoryInfo['title']) && $categoryInfo['title'] === 'Merchandise' ? 'merchandise-grid' : '' }}" id="productsGrid" style="display: grid;">
            @if(count($filteredProducts) > 0)
                @foreach($filteredProducts as $slug => $product)
                <a href="{{ route('product.detail', $slug) }}" 
                   class="product-card-link product-item"
                   data-name="{{ strtolower($product['name']) }}"
                   data-price="{{ str_replace('.', '', $product['price']) }}"
                   data-condition="{{ $product['condition'] ?? '' }}"
                   data-description="{{ strtolower($product['description'] ?? '') }}">
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                            @if($product['condition'])
                            <span class="product-badge">{{ $product['condition'] }}</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $product['name'] }}</h3>
                            @if($product['description'])
                            <p class="product-description">({{ $product['description'] }})</p>
                            @endif
                            <p class="product-price">IDR {{ $product['price'] }}</p>
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
    </section>

    <style>
        .category-products-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fill, minmax(320px, 320px));
            justify-content: center;
            gap: 30px;
            overflow-x: visible !important;
            padding: 20px 6rem;
        }

        .category-products-grid .product-card-link {
            width: 100%;
            display: block;
        }

        .category-products-grid {
            align-items: stretch;
        }

        .category-products-grid .product-card-link {
            height: 100%;
            display: flex;
        }

        .category-products-grid .product-card {
            min-width: 320px !important;
            max-width: 320px !important;
            width: 320px !important;
            height: 600px !important;
            min-height: 600px !important;
            max-height: 600px !important;
            display: flex;
            flex-direction: column;
        }

        .category-products-grid .product-image-container {
            width: 100% !important;
            height: 450px !important;
            min-height: 450px !important;
            max-height: 450px !important;
            flex-shrink: 0;
        }
        
        /* Merchandise: Kotak seperti accessories - sama persis dengan accessories */
        .merchandise-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
        }
        
        .merchandise-grid .product-card {
            width: 100% !important;
            min-width: auto !important;
            max-width: none !important;
            height: auto !important;
            min-height: auto !important;
            max-height: none !important;
            aspect-ratio: 1 !important;
            display: flex !important;
            flex-direction: column !important;
        }
        
        .merchandise-grid .product-image-container {
            width: 100% !important;
            aspect-ratio: 1 !important;
            height: auto !important;
            min-height: auto !important;
            max-height: none !important;
            flex-shrink: 0 !important;
        }
        
        .merchandise-grid .product-image {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
        }
        
        .merchandise-grid .product-info {
            height: auto !important;
            min-height: auto !important;
            max-height: none !important;
            flex: 1 !important;
            padding: 20px !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
        }
        
        .merchandise-grid .product-name {
            height: auto !important;
            min-height: auto !important;
            max-height: none !important;
            margin-bottom: 5px !important;
        }
        
        .merchandise-grid .product-description {
            height: auto !important;
            min-height: auto !important;
            margin-bottom: 12px !important;
            overflow: visible !important;
            text-overflow: unset !important;
            white-space: normal !important;
        }

        .category-products-grid .product-image {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
        }

        .category-products-grid .product-info {
            height: 150px !important;
            min-height: 150px !important;
            max-height: 150px !important;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            text-align: center;
            flex-shrink: 0;
            padding: 20px 20px 24px 20px !important;
        }

        .category-products-grid .product-name {
            height: 44px;
            min-height: 44px;
            max-height: 44px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 5px;
            text-align: center;
            width: 100%;
        }

        .category-products-grid .product-description {
            height: 18px;
            min-height: 18px;
            margin-bottom: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-align: center;
            width: 100%;
        }

        .category-products-grid .product-price {
            margin-top: auto;
            margin-bottom: 0;
            padding-bottom: 0;
            text-align: center;
            width: 100%;
        }

        /* Untuk produk yang tidak ada description, tetap ada spacing */
        .category-products-grid .product-info:has(.product-name:not(:has(+ .product-description))) .product-price {
            margin-top: 20px;
        }

        /* Search and Filter Styling */
        #searchInput:focus {
            border-color: #6a6a6a;
        }

        #priceFilter:focus,
        #conditionFilter:focus {
            border-color: #6a6a6a;
        }

        #noResults {
            display: none;
        }

        #noResults.hidden {
            display: none !important;
        }

        #noResults:not(.hidden) {
            display: block;
        }

        @media (min-width: 1400px) {
            .category-products-grid {
                grid-template-columns: repeat(auto-fill, minmax(360px, 360px));
                gap: 40px;
                padding: 20px 8rem;
            }

            .category-products-grid .product-card {
                min-width: 360px !important;
                max-width: 360px !important;
                width: 360px !important;
                height: 650px !important;
                min-height: 650px !important;
                max-height: 650px !important;
            }

            .category-products-grid .product-image-container {
                height: 500px !important;
                min-height: 500px !important;
                max-height: 500px !important;
            }
            
            .merchandise-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
            }
            
            .merchandise-grid .product-card {
                width: 100% !important;
                min-width: auto !important;
                max-width: none !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
                aspect-ratio: 1 !important;
            }
            
            .merchandise-grid .product-image-container {
                aspect-ratio: 1 !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
            }

            .category-products-grid .product-info {
                height: 150px !important;
                min-height: 150px !important;
                max-height: 150px !important;
            }
        }

        @media (min-width: 1024px) and (max-width: 1399px) {
            .category-products-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 320px));
                gap: 30px;
                padding: 20px 6rem;
            }
            
            .merchandise-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .category-products-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 280px));
                gap: 25px;
                padding: 20px 3rem;
            }

            .category-products-grid .product-card {
                min-width: 280px !important;
                max-width: 280px !important;
                width: 280px !important;
                height: 550px !important;
                min-height: 550px !important;
                max-height: 550px !important;
            }

            .category-products-grid .product-image-container {
                height: 400px !important;
                min-height: 400px !important;
                max-height: 400px !important;
            }
            
            .merchandise-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
            }
            
            .merchandise-grid .product-card {
                width: 100% !important;
                min-width: auto !important;
                max-width: none !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
                aspect-ratio: 1 !important;
            }
            
            .merchandise-grid .product-image-container {
                aspect-ratio: 1 !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
            }

            .category-products-grid .product-info {
                height: 150px !important;
                min-height: 150px !important;
                max-height: 150px !important;
            }
        }

        @media (min-width: 640px) and (max-width: 767px) {
            .category-products-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 260px));
                gap: 20px;
                padding: 20px 2rem;
            }

            .category-products-grid .product-card {
                min-width: 260px !important;
                max-width: 260px !important;
                width: 260px !important;
                height: 500px !important;
                min-height: 500px !important;
                max-height: 500px !important;
            }

            .category-products-grid .product-image-container {
                height: 350px !important;
                min-height: 350px !important;
                max-height: 350px !important;
            }
            
            .merchandise-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)) !important;
            }
            
            .merchandise-grid .product-card {
                width: 100% !important;
                min-width: auto !important;
                max-width: none !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
                aspect-ratio: 1 !important;
            }
            
            .merchandise-grid .product-image-container {
                aspect-ratio: 1 !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
            }

            .category-products-grid .product-info {
                height: 150px !important;
                min-height: 150px !important;
                max-height: 150px !important;
            }
        }

        @media (max-width: 639px) {
            .category-products-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 20px 1.5rem;
            }

            .category-products-grid .product-card {
                min-width: 100% !important;
                max-width: 100% !important;
                width: 100% !important;
                height: 550px !important;
                min-height: 550px !important;
                max-height: 550px !important;
            }

            .category-products-grid .product-image-container {
                height: 400px !important;
                min-height: 400px !important;
                max-height: 400px !important;
            }
            
            .merchandise-grid {
                grid-template-columns: 1fr !important;
            }
            
            .merchandise-grid .product-card {
                width: 100% !important;
                min-width: auto !important;
                max-width: none !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
                aspect-ratio: 1 !important;
            }
            
            .merchandise-grid .product-image-container {
                aspect-ratio: 1 !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
            }

            .category-products-grid .product-info {
                height: 150px !important;
                min-height: 150px !important;
                max-height: 150px !important;
            }
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
            const productsGrid = document.getElementById('productsGrid');
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
                        switch(priceValue) {
                            case 'low':
                                matchesPrice = price < 5000000;
                                break;
                            case 'medium':
                                matchesPrice = price >= 5000000 && price < 15000000;
                                break;
                            case 'high':
                                matchesPrice = price >= 15000000 && price <= 25000000;
                                break;
                            case 'premium':
                                matchesPrice = price > 25000000;
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
            searchInput.addEventListener('input', filterProducts);
            priceFilter.addEventListener('change', filterProducts);
            conditionFilter.addEventListener('change', filterProducts);
            
            resetFilter.addEventListener('click', function() {
                searchInput.value = '';
                priceFilter.value = '';
                conditionFilter.value = '';
                filterProducts();
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

            // Also add direct event listeners for member buttons
            setTimeout(function() {
                const memberBtn = document.getElementById('memberBtn');
                const mobileMemberIconBtn = document.getElementById('mobileMemberIconBtn');
                const mobileMemberBtn = document.getElementById('mobileMemberBtn');

                if (memberBtn && !memberBtn.hasAttribute('data-listener-added')) {
                    memberBtn.setAttribute('data-listener-added', 'true');
                    memberBtn.addEventListener('click', function() {
                        window.openMemberModal();
                    });
                }

                if (mobileMemberIconBtn && !mobileMemberIconBtn.hasAttribute('data-listener-added')) {
                    mobileMemberIconBtn.setAttribute('data-listener-added', 'true');
                    mobileMemberIconBtn.addEventListener('click', function() {
                        window.openMemberModal();
                    });
                }

                if (mobileMemberBtn && !mobileMemberBtn.hasAttribute('data-listener-added')) {
                    mobileMemberBtn.setAttribute('data-listener-added', 'true');
                    mobileMemberBtn.addEventListener('click', function() {
                        window.openMemberModal();
                    });
                }
            }, 100);
        })();

        // Scroll Animation for Product Cards
        document.addEventListener('DOMContentLoaded', function() {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            });
        });
    </script>
    @endpush
    
    @stack('scripts')
</body>

</html>
