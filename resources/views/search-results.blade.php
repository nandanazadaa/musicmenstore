<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Musicmen Store</title>
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
            <span class="text-white">Search Results</span>
        </nav>
    </div>

    <!-- Search Header -->
    <section class="w-full py-12 px-6 lg:px-16">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-12">
                <h1 class="serif-font text-4xl md:text-5xl lg:text-6xl font-light tracking-tight text-white mb-4">
                    SEARCH RESULTS
                </h1>
                <div class="w-32 h-0.5 bg-white mx-auto mb-4"></div>
                <p class="text-lg text-[#9a9a9a] max-w-2xl mx-auto">
                    Results for "<span class="text-white font-medium">{{ $query }}</span>"
                    @if($totalResults > 0)
                        <span class="block mt-2 text-sm">Found {{ $totalResults }} {{ $totalResults == 1 ? 'item' : 'items' }}</span>
                    @endif
                </p>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    <section class="products-section w-full">
        @if($totalResults > 0)
            <!-- Landing Page Products -->
            @if(count($results['landing_products']) > 0)
                <div class="container mx-auto max-w-7xl px-6 lg:px-16 mb-12">
                    <h2 class="text-2xl font-light text-white mb-6">Products</h2>
                    <div class="category-products-grid">
                        @foreach($results['landing_products'] as $product)
                        <a href="{{ route('product.detail', $product['slug']) }}" class="product-card-link product-item">
                            <div class="product-card">
                                <div class="product-image-container">
                                    <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                                    @if($product['type'] === 'used-gear')
                                        <span class="product-badge">Used</span>
                                    @else
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
                    </div>
                </div>
            @endif

            <!-- Used Gear -->
            @if(count($results['used_gear']) > 0)
                <div class="container mx-auto max-w-7xl px-6 lg:px-16 mb-12">
                    <h2 class="text-2xl font-light text-white mb-6">Used Gear</h2>
                    <div class="category-products-grid">
                        @foreach($results['used_gear'] as $product)
                        <a href="{{ route('product.detail', $product['slug']) }}" class="product-card-link product-item">
                            <div class="product-card">
                                <div class="product-image-container">
                                    <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                                    <span class="product-badge">Used</span>
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
                    </div>
                </div>
            @endif

            <!-- Accessories -->
            @if(count($results['accessories']) > 0)
                <div class="container mx-auto max-w-7xl px-6 lg:px-16 mb-12">
                    <h2 class="text-2xl font-light text-white mb-6">Accessories</h2>
                    <div class="category-products-grid">
                        @foreach($results['accessories'] as $product)
                        <a href="{{ route('product.detail', $product['slug']) }}" class="product-card-link product-item">
                            <div class="product-card">
                                <div class="product-image-container">
                                    <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
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
                    </div>
                </div>
            @endif

            <!-- Merchandise -->
            @if(count($results['merchandise']) > 0)
                <div class="container mx-auto max-w-7xl px-6 lg:px-16 mb-12">
                    <h2 class="text-2xl font-light text-white mb-6">Merchandise</h2>
                    <div class="category-products-grid">
                        @foreach($results['merchandise'] as $product)
                        <a href="{{ route('product.detail', $product['slug']) }}" class="product-card-link product-item">
                            <div class="product-card">
                                <div class="product-image-container">
                                    <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
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
                    </div>
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="container mx-auto max-w-7xl px-6 lg:px-16 py-20">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-[#6a6a6a] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="text-2xl font-light text-white mb-2">No results found</h3>
                    <p class="text-[#9a9a9a] mb-6">We couldn't find any products matching "<span class="text-white">{{ $query }}</span>"</p>
                    <a href="{{ url('/') }}" class="inline-block bg-transparent border border-[#4a4a4a] text-white px-8 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Back to Home
                    </a>
                </div>
            </div>
        @endif
    </section>

    <style>
        .category-products-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fill, minmax(320px, 320px));
            justify-content: center;
            gap: 30px;
            overflow-x: visible !important;
            padding: 20px 0;
        }

        .category-products-grid .product-card-link {
            width: 100%;
            display: block;
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

        @media (max-width: 767px) {
            .category-products-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 20px 0;
            }

            .category-products-grid .product-card {
                min-width: 100% !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>

    @include('components.footer')
</body>

</html>

