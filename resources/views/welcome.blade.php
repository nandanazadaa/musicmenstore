<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title>Musicmen Store - Your Destination for Authentic Guitars & Musical Instruments</title>
    <meta name="title" content="Musicmen Store - Your Destination for Authentic Guitars & Musical Instruments">
    <meta name="description"
        content="Musicmen Store adalah destinasi untuk para pecinta gitar dan bass yang mengutamakan kualitas, keaslian, dan pengalaman belanja premium. Berbasis di Yogyakarta, kami fokus pada seleksi instrumen original—dari unit second-hand premium, item langka, hingga lini produk kurasi yang dipilih dengan standar tinggi.">
    <meta name="keywords"
        content="gitar, bass, amplifier, efek, aksesoris musik, musicmen, yogyakarta, gitar original, bass original, musik store">
    <meta name="author" content="Musicmen Store">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="revisit-after" content="7 days">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Musicmen Store - Your Destination for Authentic Guitars">
    <meta property="og:description"
        content="Musicmen Store adalah destinasi untuk para pecinta gitar dan bass yang mengutamakan kualitas, keaslian, dan pengalaman belanja premium.">
    <meta property="og:image" content="{{ asset($mainLogo ?? 'images/icon-logo.png') }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="Musicmen Store">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="Musicmen Store - Your Destination for Authentic Guitars">
    <meta property="twitter:description"
        content="Musicmen Store adalah destinasi untuk para pecinta gitar dan bass yang mengutamakan kualitas, keaslian, dan pengalaman belanja premium.">
    <meta property="twitter:image" content="{{ asset($mainLogo ?? 'images/icon-logo.png') }}">
    <meta name="google-site-verification" content="f8D7XdWKa3XPhGDW-z6LUM94wtP6hm4sJ0ct-auXQHA" />

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url('/') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-logo.png') }}">

    @php
        $firstSliderImage = $sliderImages[0] ?? null;
        $viteManifestPath = public_path('build/manifest.json');
        $viteManifest = file_exists($viteManifestPath)
            ? json_decode(file_get_contents($viteManifestPath), true)
            : [];
        $viteCss = $viteManifest['resources/css/app.css']['file'] ?? null;
        $sameAsLinks = collect($socialMedia ?? [])
            ->pluck('link')
            ->filter()
            ->values();
    @endphp

    <!-- Preload Critical Resources -->
    <link rel="preload" href="{{ asset('css/style.css') }}" as="style">
    <link rel="preload" href="{{ asset($mainLogo ?? 'images/logo1.png') }}" as="image">
    <link rel="preload" href="{{ asset($headerLogo ?? 'images/icon-logo.png') }}" as="image">
    @if ($firstSliderImage)
        <link rel="preload" href="{{ $firstSliderImage }}" as="image" fetchpriority="high">
    @endif

    <!-- Stylesheets -->
    @if ($viteCss)
        <link rel="stylesheet" href="{{ asset('build/' . $viteCss) }}">
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "MusicStore",
            "name": "Musicmen Store",
            "description": "Musicmen Store adalah destinasi untuk para pecinta gitar dan bass yang mengutamakan kualitas, keaslian, dan pengalaman belanja premium.",
            "url": "{{ url('/') }}",
            "logo": "{{ asset($mainLogo ?? 'images/logo1.png') }}",
            "image": "{{ asset($mainLogo ?? 'images/logo1.png') }}",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Jl. Wates No.148 Km. 3,5 No, Onggobayan, Ngestiharjo",
                "addressLocality": "Kasihan",
                "addressRegion": "Bantul",
                "addressCountry": "ID",
                "postalCode": "55184"
            },
            "telephone": "08816707166",
            "priceRange": "$$",
            "openingHoursSpecification": {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": [
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday"
                ],
                "opens": "09:00",
                "closes": "18:00"
            },
            "sameAs": @json($sameAsLinks)
        }
    </script>

    @php
        use Illuminate\Support\Str;
    @endphp
</head>

<body>
    <!-- Skip to main content link for accessibility -->

    @include('components.navbar')

    <main id="main-content" class="hero-section flex items-center justify-center relative" role="main">
        <!-- Slider Background -->
        <div class="slider-container">
            @foreach ($sliderImages as $index => $imageUrl)
                <div class="slide {{ $index === 0 ? 'active' : '' }}"
                    @if ($index !== 0)
                        data-bg="{{ $imageUrl }}"
                    @endif>
                    @if ($index === 0)
                        <img src="{{ $imageUrl }}" alt="Musicmen Store hero background" class="slide-image"
                            fetchpriority="high" loading="eager" decoding="async" width="1920" height="1280">
                    @endif
                </div>
            @endforeach
        </div>

        <div class="hero-title-container text-center relative z-10">
            <img src="{{ asset($mainLogo ?? 'images/logo1.png') }}"
                alt="Musicmen Store - Logo utama toko musik premium di Yogyakarta" class="mx-auto mb-6"
                style="max-width: 900px; width: 90%; height: auto; object-fit: contain;" loading="eager"
                fetchpriority="high" decoding="async" width="900" height="360">
            <div class="w-32 h-0.5 bg-white mx-auto mb-6" aria-hidden="true"></div>
            <p class="text-lg md:text-xl font-light text-[#d4d4d4] tracking-[0.1em] relative inline-block vintage-text">
                {{ $mainSubtitle ?? 'Your Destination for Authentic Guitars' }}
            </p>
            <div class="w-48 h-px bg-[#6a6a6a] mx-auto mt-6" aria-hidden="true"></div>
        </div>

        <!-- Carousel Indicators -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-20 flex gap-3">
            @foreach ($sliderImages as $index => $imageUrl)
                <span class="carousel-indicator {{ $index === 0 ? 'active' : '' }} w-14 h-0.5 rounded"
                    data-slide="{{ $index }}"></span>
            @endforeach
        </div>
    </main>

    <style>
        /* Skip to main content link - hidden by default, visible on focus */
        .skip-to-main-content {
            position: absolute;
            top: -40px;
            left: 0;
            background: #000;
            color: #fff;
            padding: 8px;
            text-decoration: none;
            z-index: 10000;
        }

        .skip-to-main-content:focus {
            top: 0;
        }

        /* Screen reader only class */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        Ooh,
        saya mengerti maksud Anda. Anda ingin agar keseluruhan overlay (layar hitamnya) yang menjadi area scroll,
        seolah-olah halaman itu sendiri yang memanjang ke bawah,
        bukan ada scrollbar kecil di dalam kotak popup-nya. Ini gaya scroll yang biasa dipakai oleh Bootstrap Modal. Jadi ketika kontennya panjang,
        user scroll layar seperti biasa (atas-bawah),
        dan popupnya ikut naik-turun. Berikut adalah perbaikan CSS-nya. Hapus/timpa CSS popup yang sebelumnya dengan yang ini: HTML <style>
        /* ... style lainnya ... */

        /* PERBAIKAN POPUP MODAL (STYLE HALAMAN SCROLL) */

        /* 1. Container Utama (Layar Hitam) */
        #memberInvitePopup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: rgba(0, 0, 0, 0.85);

            /* KUNCI UTAMA DISINI: */
            /* Pindahkan scroll ke container utama */
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            /* Supaya smooth di iPad/iPhone */

            /* Gunakan Flexbox untuk centering */
            display: flex;
            /* Pastikan item mulai dari atas supaya bisa di-scroll kalau panjang */
            align-items: flex-start;
            justify-content: center;

            /* Padding supaya tidak nempel pinggir layar HP */
            padding: 20px;
        }

        #memberInvitePopup.active {
            opacity: 1;
            visibility: visible;
        }

        /* 2. Kotak Konten Popup */
        .member-invite-container {
            position: relative;
            background-color: #1a1a1a;
            width: 100%;
            max-width: 480px;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid #333;
            top: auto !important;
            left: auto !important;
            transform: none !important;
            margin: auto;
            height: auto;
            max-height: none;
            /* Biarkan memanjang sesuai isi konten */
            overflow: visible;
            /* Tidak ada scroll di dalam kotak */
        }

        /* Penyesuaian Mobile */
        @media (max-width: 640px) {
            .member-invite-container {
                padding: 1.5rem 1rem;
                /* Tambah margin atas-bawah supaya enak dilihat pas scroll */
                margin-top: 20px;
                margin-bottom: 20px;
            }
        }

        #memberInvitePopup {
            /* Gunakan nilai yang sangat tinggi dan !important untuk memastikan ini menang */
            z-index: 99999 !important;
            position: fixed;
            /* Pastikan posisinya fixed relatif terhadap layar */
        }

        /* Opsional: Jika container dalamnya juga bermasalah, tambahkan ini */
        .member-invite-container {
            z-index: 100000 !important;
            /* Sedikit lebih tinggi dari overlaynya */
            position: relative;
        }

        .product-card {
            min-width: 320px;
            max-width: 320px;
            width: 320px;
            height: 600px;
            min-height: 600px;
            max-height: 600px;
            background-color: #1a1a1a;
            /* Warna gelap senada dengan section service */
            border: 1px solid #525252;
            /* Border halus di pinggir */
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease;
            flex-shrink: 0;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .product-card:hover {
            transform: translateY(-10px);
            border-color: #cacaca;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
        }

        .product-image-container {
            position: relative;
            width: 100%;
            height: 450px;
            min-height: 450px;
            max-height: 450px;
            background: #1a1a1a;
            overflow: hidden;
            border-radius: 12px 12px 0 0;
            flex-shrink: 0;
        }

        .product-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 12px 12px 0 0;
            -webkit-border-radius: 12px 12px 0 0;
            -moz-border-radius: 12px 12px 0 0;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            z-index: 10;
        }

        .product-info {
            padding: 20px 20px 24px 20px;
            background: #1a1a1a;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 150px;
            min-height: 150px;
            max-height: 150px;
            flex-shrink: 0;
        }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
            line-height: 1.4;
            text-align: center;
            width: 100%;
            height: 44px;
            min-height: 44px;
            max-height: 44px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-description {
            font-size: 13px;
            color: #fff;
            margin-bottom: 20px;
            font-weight: 300;
            text-align: center;
            width: 100%;
            height: 18px;
            min-height: 18px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-price {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            text-align: center;
            width: 100%;
            margin-top: auto;
            margin-bottom: 0;
        }

        .sell-your-gear-banner-box {
            padding-left: 15px;
            padding-right: 15px;
        }

        .sell-your-gear-divider {
            width: 80px;
            height: 2px;
            background-color: white;
        }

        /* Styling untuk teks BUY | SELL | TRADE | SERVICE */
        .gear-services-list {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            /* Jarak antar kata */
            flex-wrap: wrap;
            /* Agar aman di layar HP kecil */
            margin-top: 2rem;
            color: white;
            font-weight: 500;
            letter-spacing: 2px;
            font-size: 0.9rem;
        }

        .gear-services-list span.separator {
            color: #525252;
            /* Warna garis pemisah agar tidak terlalu kontras */
        }

        @media (max-width: 640px) {
            .gear-services-list {
                gap: 10px;
                font-size: 0.75rem;
            }
        }

        /* Container utama section service */
        .service-section {
            padding: 80px 24px;
            /* Memberikan jarak atas-bawah dan ruang kiri-kanan (24px) */
            background-color: #0a0a0a;
            /* Sesuaikan dengan tema */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Membatasi lebar konten agar tidak melebar ke ujung layar */
        .service-container {
            width: 100%;
            max-width: 1200px;
            /* Lebar maksimal standar yang nyaman di mata */
            margin: 0 auto;
        }

        .service-header {
            text-align: center;
            margin-bottom: 50px;
            padding: 0 15px;
        }

        /* Mengatur Grid agar rapi */
        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            /* Jarak antar kartu */
            width: 100%;
        }

        .service-card {
            padding: 40px 30px;
            background: #151515;
            border: 1px solid #333;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .service-card:hover {
            border-color: #ffffff;
            transform: translateY(-5px);
        }

        .service-description {
            color: #a0a0a0;
            line-height: 1.6;
            margin-top: 15px;
        }
    </style>

    <script defer>
        // Auto Slider Functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const totalSlides = slides.length;

        function showSlide(index) {
            const targetSlide = slides[index];
            const lazyBackground = targetSlide?.dataset.bg;

            if (lazyBackground) {
                targetSlide.style.backgroundImage = `url('${lazyBackground}')`;
                targetSlide.removeAttribute('data-bg');
            }

            // Remove active class from all slides and indicators
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => {
                indicator.classList.remove('active');
                indicator.style.backgroundColor = '#4a4a4a';
            });

            // Add active class to current slide and indicator
            targetSlide.classList.add('active');
            indicators[index].classList.add('active');
            indicators[index].style.backgroundColor = '#ffffff';
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        // Auto slide every 5 seconds
        if (totalSlides > 0) {
            setInterval(nextSlide, 5000);

            // Click on indicator to go to specific slide
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                });
            });
        }
    </script>

    <!-- About Us Section -->
    <section id="about" class="about-section w-full py-24 px-6 lg:px-16">
        <!-- Blurred Background -->
        <div class="about-background"></div>

        <!-- Content -->
        <div class="about-content container mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Section - About Text -->
                <div class="text-white">
                    <h2 class="serif-font text-5xl md:text-6xl lg:text-7xl font-normal mb-6 tracking-wide">ABOUT US
                    </h2>
                    <div class="w-28 h-0.5 bg-white mb-10"></div>
                    <p class="text-base md:text-lg leading-relaxed text-[#d4d4d4] vintage-text">
                        {!! nl2br(e($aboutDescription)) !!}
                    </p>
                </div>

                <!-- Right Section - Logo Image -->
                <div class="logo-container flex items-center justify-center">
                    <img src="{{ asset($aboutLogo) }}"
                        alt="Musicmen Store - Logo tentang kami, toko musik premium Yogyakarta"
                        class="w-full h-auto max-w-md object-contain filter brightness-0 invert opacity-90"
                        loading="lazy" width="400" height="auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Brand Guitars Section -->
    <section class="brand-section w-full">
        <div class="brand-header">
            <h2 class="serif-font">{{ $brandsTitle ?? 'GUITAR BRANDS' }}</h2>
            <div class="divider"></div>
            <p>{{ $brandsSubtitle ?? 'Trusted Guitar Brands We Offer' }}</p>
        </div>
        <div class="brand-grid">
            @forelse($guitarBrands as $brand)
                <div class="brand-item">
                    <img src="{{ asset($brand->logo) }}"
                        alt="Logo {{ $brand->name }} - Brand gitar yang tersedia di Musicmen Store" loading="lazy"
                        width="150" height="auto">
                </div>
            @empty
                <div class="brand-item">
                    <img src="{{ asset('images/fender.jpg') }}"
                        alt="Logo Fender - Brand gitar yang tersedia di Musicmen Store" loading="lazy" width="150"
                        height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/gipson.jpg') }}"
                        alt="Logo Gibson - Brand gitar yang tersedia di Musicmen Store" loading="lazy" width="150"
                        height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/ibanez.jpg') }}"
                        alt="Logo Ibanez - Brand gitar yang tersedia di Musicmen Store" loading="lazy" width="150"
                        height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/sechcter.jpg') }}"
                        alt="Logo Schecter - Brand gitar yang tersedia di Musicmen Store" loading="lazy"
                        width="150" height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/yamaha.jpeg') }}"
                        alt="Logo Yamaha - Brand gitar yang tersedia di Musicmen Store" loading="lazy" width="150"
                        height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/cort.png') }}"
                        alt="Logo Cort - Brand gitar yang tersedia di Musicmen Store" loading="lazy" width="150"
                        height="auto">
                </div>
            @endforelse
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section w-full">
        <!-- Header -->
        <div class="products-header flex flex-col md:flex-row justify-between items-center gap-6 px-6 lg:px-16 mb-10">
            <div class="order-2 md:order-1 relative w-full max-w-md">
                <input type="text" id="productSearchInput" placeholder="Search Products..."
                    class="w-full py-3 pl-5 pr-12 bg-white rounded-lg text-[#0a0a0a] border-none focus:ring-2 focus:ring-gray-400">
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="order-1 md:order-2 text-center md:text-right w-full md:w-auto">
                <h2
                    class="serif-font text-4xl md:text-6xl lg:text-7xl font-normal text-white uppercase tracking-wider">
                    {{ $productsTitle ?? 'PRODUCTS' }}
                </h2>
                <div class="w-24 h-0.5 bg-white mt-2 mx-auto md:ml-auto md:mr-0"></div>
            </div>
        </div>

        <!-- Scrollable Product Cards -->
        <div class="products-scroll" id="productsContainer">
            @forelse($products as $product)
                @php
                    $productSlug =
                        isset($product->slug) && $product->slug
                            ? $product->slug
                            : \Illuminate\Support\Str::slug($product->nama_barang);
                @endphp
                <a href="{{ route('product.detail', $productSlug) }}" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image-container">
                            @if ($product->image && file_exists(public_path($product->image)))
                                <img src="{{ asset($product->image) }}" alt="{{ $product->nama_barang }}"
                                    class="product-image" loading="lazy">
                            @else
                                <div
                                    class="product-image bg-[#1a1a1a] flex flex-col items-center justify-center border border-[#333]">
                                    <svg class="w-12 h-12 text-[#333] mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-[#444] text-[10px] uppercase font-bold tracking-tighter">Produk
                                        Tidak Tersedia</span>
                                </div>
                            @endif
                            <span class="product-badge">{{ $product->kondisi_name ?? 'Original' }}</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->nama_barang }}</h3>
                            <p class="product-price">{{ $product->formatted_price }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-data-msg w-full">Belum ada katalog produk yang diinputkan.</div>
            @endforelse
        </div>

        <!-- All Products Button -->
        <div class="text-center mt-12">
            <a href="{{ route('category.show', 'electric-guitars') }}"
                class="inline-block bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg uppercase tracking-wider hover:bg-white hover:text-[#0a0a0a] transition-all duration-300 font-medium text-sm">
                ALL PRODUCTS
            </a>
        </div>
    </section>

    <!-- Brand Accessories Section -->
    <section class="brand-section w-full">
        <div class="brand-header">
            <h2 class="serif-font">{{ $acc_title }}</h2>

            <div class="divider"></div>

            <p>{{ $acc_subtitle }}</p>
        </div>
        <div class="brand-grid">
            @forelse($accessoriesBrands as $brand)
                <div class="brand-item">
                    <img src="{{ asset($brand->logo) }}"
                        alt="Logo {{ $brand->name }} - Brand aksesoris musik yang tersedia di Musicmen Store"
                        loading="lazy" width="150" height="auto">
                </div>
            @empty
                <div class="brand-item">
                    <img src="{{ asset('images/drcase.png') }}"
                        alt="Logo DR.CASE - Brand aksesoris musik yang tersedia di Musicmen Store" loading="lazy"
                        width="150" height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/dadario.png') }}"
                        alt="Logo D'ADDARIO - Brand aksesoris musik yang tersedia di Musicmen Store" loading="lazy"
                        width="150" height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/ivu.png') }}"
                        alt="Logo IVU CREATOR - Brand aksesoris musik yang tersedia di Musicmen Store" loading="lazy"
                        width="150" height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/ernieball.png') }}"
                        alt="Logo ERNIE BALL - Brand aksesoris musik yang tersedia di Musicmen Store" loading="lazy"
                        width="150" height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/sgstring.png') }}"
                        alt="Logo SG STRING - Brand aksesoris musik yang tersedia di Musicmen Store" loading="lazy"
                        width="150" height="auto">
                </div>
                <div class="brand-item">
                    <img src="{{ asset('images/valeton.jpg') }}"
                        alt="Logo VALETON - Brand aksesoris musik yang tersedia di Musicmen Store" loading="lazy"
                        width="150" height="auto">
                </div>
            @endforelse
        </div>
    </section>

    <!-- Accessories Section -->
    <section class="accessories-section w-full">
        <div class="accessories-header">
            <div class="relative w-full max-w-md">

                <label for="accessorySearchInput" class="sr-only">Cari aksesoris</label>
                <input type="text" id="accessorySearchInput" name="accessorySearch"
                    placeholder="Search Accessories..." aria-label="Cari aksesoris"
                    class="w-full py-3 pl-5 pr-12 bg-white rounded-lg text-[#0a0a0a] placeholder-[#6a6a6a] focus:outline-none focus:ring-2 focus:ring-[#4a4a4a] transition-all">

                <button type="button"
                    class="absolute right-0 top-0 mt-3 mr-4 text-[#6a6a6a] hover:text-[#0a0a0a] transition-colors"
                    aria-label="Tombol cari aksesoris">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                </button>

            </div>

            <div class="text-right">
                <h2 class="serif-font text-5xl md:text-6xl lg:text-7xl font-normal tracking-wide text-white uppercase">
                    {{ $accTitle ?? 'ACCESSORIES' }}
                </h2>
                <div class="w-32 h-0.5 bg-white mt-4 ml-auto"></div>
            </div>
        </div>

        <div class="accessories-grid" id="accessoriesContainer">
            @forelse($accessories as $item)
                <div class="accessory-card">
                    <div class="accessory-card-inner">
                        <div class="accessory-card-front">
                            <div class="accessory-image-container">
                                @if ($item->image && file_exists(public_path($item->image)))
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->nama_barang }}"
                                        class="accessory-image" loading="lazy">
                                @else
                                    <div
                                        class="accessory-image bg-[#1a1a1a] flex items-center justify-center italic text-[#333] text-[10px]">
                                        No Image</div>
                                @endif
                            </div>
                            <div class="accessory-info">
                                <h3 class="accessory-name">{{ $item->nama_barang }}</h3>
                                <p class="accessory-price">IDR {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="accessory-card-back">
                            <h3 class="accessory-back-title">{{ $item->nama_barang }}</h3>
                            <p class="accessory-back-description">{{ $item->bonus ?? 'Item original terkurasi' }}</p>
                            <p class="accessory-back-price">IDR {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-data-msg">Aksesoris belum tersedia.</div>
            @endforelse
        </div>
    </section>

    <!-- Sell Your Gear Banner Section -->
    <section id="sell-your-gear" class="sell-your-gear-banner w-full py-20 px-6 lg:px-16">
        <div class="container mx-auto max-w-4xl">
            <div class="sell-your-gear-banner-content text-center sell-your-gear-banner-box">
                <h2 class="sell-your-gear-banner-title serif-font text-white mb-6 text-4xl md:text-5xl">
                    SELL YOUR GEAR
                </h2>
                <div class="sell-your-gear-divider mx-auto mb-8"></div>

                <p
                    class="sell-your-gear-banner-description text-[#d4d4d4] vintage-text mb-10 max-w-2xl mx-auto leading-relaxed">
                    Mau upgrade atau jual gitar & bass kesayangan anda? Kami siap memberikan harga yang terbaik sesuai
                    dengan kondisi, kelengkapan dan harga pasar yang berlaku.
                </p>

                <a href="https://wa.me/628816707166?text=Halo men%2C%20mau%20jual%20gitar%20atau%20gear."
                    target="_blank" rel="noopener noreferrer"
                    class="sell-your-gear-banner-btn inline-flex items-center px-8 py-3 border border-white hover:bg-white hover:text-black transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                    <span>Chat via WhatsApp</span>
                </a>

                <div class="gear-services-list">
                    <span>BUY</span>
                    <span class="separator">|</span>
                    <span>SELL</span>
                    <span class="separator">|</span>
                    <span>TRADE</span>
                    <span class="separator">|</span>
                    <span>SERVICE</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="gallery-section w-full">
        <div class="gallery-header px-4 sm:px-6 lg:px-8">
            <div class="inline-block">
                <h2 class="serif-font text-5xl md:text-6xl lg:text-7xl font-normal tracking-wide text-white uppercase">
                    GALLERY
                </h2>
                <div class="h-0.5 bg-gradient-to-r from-white via-gray-400 to-transparent w-full mt-2"></div>
            </div>
        </div>

        <div class="gallery-grid">
            @foreach ($galleries as $index => $gallery)
                <div class="gallery-item" data-image="{{ asset($gallery->image) }}" role="button" tabindex="0"
                    aria-label="Buka gambar galeri {{ $index + 1 }}">
                    <img src="{{ asset($gallery->image) }}" alt="Galeri Musicmen Store - Gambar {{ $index + 1 }}"
                        loading="lazy" width="400" height="400">
                    <div class="gallery-overlay" aria-hidden="true">
                        <span class="gallery-overlay-icon">+</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Service Section -->
    <section class="service-section w-full">
        <div class="service-container">
            <div class="service-header">
                <h2 class="serif-font text-4xl md:text-5xl text-white uppercase tracking-wider">{{ $title }}
                </h2>
                <div class="w-20 h-0.5 bg-white mx-auto my-6"></div>
                <p class="text-[#d4d4d4] max-w-2xl mx-auto">{{ $subtitle }}</p>
            </div>

            <div class="service-grid">
                @foreach ($services as $svc)
                    <div class="service-card">
                        <div class="service-icon mb-6 flex justify-center" aria-hidden="true">
                            <img src="{{ asset($svc->icon) }}" alt="Ikon layanan {{ $svc->title }}"
                                class="h-12 w-12 object-contain filter brightness-0 invert" loading="lazy"
                                width="48" height="48">
                        </div>
                        <h3 class="service-title text-xl text-white font-semibold uppercase">{{ $svc->title }}</h3>
                        <p class="service-description">{{ $svc->description }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    @include('components.footer')

    <!-- Lightbox -->
    <div id="lightboxOverlay" class="lightbox-overlay" role="dialog" aria-modal="true" aria-label="Galeri gambar">
        <button class="lightbox-close" id="lightboxClose" aria-label="Tutup galeri">&times;</button>
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Gambar sebelumnya">&#8249;</button>
        <div class="lightbox-content">
            <img id="lightboxImage" class="lightbox-image" src="" alt="Galeri Musicmen Store">
        </div>
        <button class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Gambar berikutnya">&#8250;</button>
    </div>

    <!-- Member Invitation Popup (Muncul saat pertama kali buka/refresh) -->
    <div id="memberInvitePopup" class="member-invite-popup" role="dialog" aria-modal="true"
        aria-labelledby="memberInviteTitle">
        <div class="member-invite-container">
            <button id="closeInvitePopup" class="member-invite-close"
                aria-label="Tutup popup undangan member">&times;</button>

            <div class="member-invite-icon"
                style="background: transparent; border: none; box-shadow: none; width: 100%; height: auto; border-radius: 0; padding: 0; margin-bottom: 1rem;">
                <img src="{{ asset('images/logo3.png') }}" alt="Musicmen Logo"
                    style="width: 120px; height: auto; object-fit: contain; margin: 0 auto;">
            </div>

            <h2 id="memberInviteTitle" class="member-invite-title">JOIN EXCLUSIVE MEMBERSHIP!</h2>
            <p class="member-invite-subtitle">Dapatkan keuntungan eksklusif dan jadi pelanggan prioritas setelah
                bergabung menjadi member.</p>

            <div class="member-invite-benefits">
                <ul>
                    <li>Free pick & holder</li>
                    <li>Free restring & cleaning standar lifetime</li>
                    <li>Free garansi setup setiap pembelian instrument</li>
                    <li>Reward menarik setiap 5x kunjungan ke offline store</li>
                    <li>Mendapatkan info gear update dan ekslusif promo</li>
                </ul>
            </div>

            <div class="member-invite-buttons">
                <button id="inviteRegisterBtn" class="btn-invite-register"
                    aria-label="Daftar sebagai member sekarang">Register Now</button>
                <button id="inviteLaterBtn" class="btn-invite-later" aria-label="Ingatkan saya nanti">Maybe
                    Later</button>
            </div>
        </div>
    </div>

    <!-- Member Registration Modal -->
    <div id="memberModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-container">
            <button id="closeModal" class="modal-close" aria-label="Tutup modal registrasi">&times;</button>
            <h2 id="modalTitle" class="modal-title">Member Registration</h2>
            <div class="text-center py-4">
                <p class="text-white mb-6">Daftar sebagai anggota untuk mendapatkan akses ke profil Anda dan fitur
                    eksklusif lainnya.</p>
                <a href="{{ route('member.register') }}"
                    class="inline-block bg-transparent border border-[#4a4a4a] text-white px-8 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                    Register Now
                </a>
                <p class="text-[#9a9a9a] text-sm mt-4">
                    Already have an account?
                    <a href="{{ route('member.login') }}"
                        class="text-white hover:text-[#6a6a6a] transition-colors">Login</a>
                </p>
            </div>
        </div>
    </div>


    <!-- WhatsApp Chat Popups -->
    <div id="chatPopup1" class="whatsapp-chat-popup">
        <div class="chat-popup-header">
            <div class="chat-popup-title">
                <img src="{{ asset('images/logo4.png') }}" alt="Musicmen"
                    style="width: 18px; height: 18px; object-fit: contain;" loading="lazy" width="18"
                    height="18">
                <span>Musicmen</span>
            </div>
            <button class="chat-popup-close" onclick="closeChatPopup('chatPopup1')"
                aria-label="Tutup popup chat">&times;</button>
        </div>
        <div class="chat-popup-content">
            Info Konsultasi dan Order bisa hubungi dibawah sini men!
        </div>
        <a href="https://wa.me/628816707166?text=Halo,%20Men%20Apakah Bisa%Bertanya%20Sesuatu" target="_blank"
            rel="noopener noreferrer" class="chat-popup-link">
            Chat Now
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>
    <!-- Floating WhatsApp Button -->
    <!-- Replace the WhatsApp number below with the correct number (format: 62xxxxxxxxxxx without + and space) -->
    <a href="https://wa.me/628816707166?text=Halo,%20Men%20Apakah Bisa%Bertanya%20Sesuatu" target="_blank"
        rel="noopener noreferrer" class="whatsapp-float" aria-label="Contact us via WhatsApp"
        title="Chat with us on WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
        </svg>
    </a>


    <script>
        // Member Invitation Popup (Appears on first visit/refresh)
        const memberInvitePopup = document.getElementById('memberInvitePopup');
        const closeInvitePopup = document.getElementById('closeInvitePopup');
        const inviteRegisterBtn = document.getElementById('inviteRegisterBtn');
        const inviteLaterBtn = document.getElementById('inviteLaterBtn');

        // Show popup when page is first opened or refreshed (with delay and localStorage check)
        if (memberInvitePopup) {
            // Function to check and show popup
            function checkAndShowPopup() {
                // Check if popup was closed recently (within last 5 minutes)
                const popupClosedTime = localStorage.getItem('memberPopupClosed');
                const now = Date.now();
                const fiveMinutesInMs = 5 * 60 * 1000; // 5 minutes in milliseconds

                // Only show popup if:
                // 1. Never closed before, OR
                // 2. Closed more than 5 minutes ago
                if (!popupClosedTime || (now - parseInt(popupClosedTime)) > fiveMinutesInMs) {
                    // Delay for smoother experience (2 seconds)
                    setTimeout(function() {
                        memberInvitePopup.classList.add('active');

                        // --- PERBAIKAN DI SINI ---
                        // Berikan tanda komentar (//) di depan baris ini agar layar tetap bisa discroll
                        // document.body.style.overflow = 'hidden'; 

                    }, 2000);
                }
            }

            // Check on page load
            window.addEventListener('load', checkAndShowPopup);

            // Check every 5 minutes automatically
            setInterval(checkAndShowPopup, 5 * 60 * 1000);

            // Close popup invitation
            function closeInvitePopupFunc() {
                memberInvitePopup.classList.remove('active');

                // --- PERBAIKAN DI SINI (Opsional) ---
                // Berikan tanda komentar juga disini karena kita tidak mengubahnya saat membuka
                // document.body.style.overflow = 'auto';

                // Save timestamp when popup is closed
                localStorage.setItem('memberPopupClosed', Date.now().toString());
            }

            if (closeInvitePopup) {
                closeInvitePopup.addEventListener('click', closeInvitePopupFunc);
            }

            if (inviteLaterBtn) {
                inviteLaterBtn.addEventListener('click', closeInvitePopupFunc);
            }

            // Close popup when clicking outside container
            memberInvitePopup.addEventListener('click', function(e) {
                if (e.target === memberInvitePopup) {
                    closeInvitePopupFunc();
                }
            });

            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && memberInvitePopup.classList.contains('active')) {
                    closeInvitePopupFunc();
                }
            });
        }

        // Member Modal Functionality
        const memberBtn = document.getElementById('memberBtn');
        const memberModal = document.getElementById('memberModal');
        const closeModal = document.getElementById('closeModal');
        const memberIdInput = document.getElementById('memberId');

        // Generate Member ID
        function generateMemberId() {
            const prefix = 'MM';
            const timestamp = Date.now().toString().slice(-6);
            const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            return ⁠ ${prefix}${timestamp}${random} ⁠;
        }

        // Register Now button - open registration modal
        if (inviteRegisterBtn && memberModal) {
            inviteRegisterBtn.addEventListener('click', function() {
                if (memberInvitePopup) {
                    memberInvitePopup.classList.remove('active');
                }
                // Open registration modal after popup is closed
                setTimeout(function() {
                    memberModal.classList.add('active');
                    if (memberIdInput) {
                        memberIdInput.value = generateMemberId();
                    }
                    document.body.style.overflow = 'hidden';
                }, 300);
            });
        }

        // Open Modal (Desktop) - Only if member is not logged in
        if (memberBtn && memberModal) {
            memberBtn.addEventListener('click', function(e) {
                e.preventDefault();
                memberModal.classList.add('active');
                if (memberIdInput) {
                    memberIdInput.value = generateMemberId();
                }
                document.body.style.overflow = 'hidden';
            });
        }

        // Open Modal (Mobile Icon) - Only if member is not logged in
        const mobileMemberIconBtn = document.getElementById('mobileMemberIconBtn');
        if (mobileMemberIconBtn && memberModal) {
            mobileMemberIconBtn.addEventListener('click', function(e) {
                e.preventDefault();
                memberModal.classList.add('active');
                if (memberIdInput) {
                    memberIdInput.value = generateMemberId();
                }
                document.body.style.overflow = 'hidden';
            });
        }

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


        // Accessory Card Flip Functionality for Mobile
        function isMobileDevice() {
            return window.innerWidth <= 768;
        }

        const accessoryCards = document.querySelectorAll('.accessory-card');

        accessoryCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Only handle click on mobile devices
                if (isMobileDevice()) {
                    e.stopPropagation();
                    // Toggle flip class
                    this.classList.toggle('flipped');

                    // Close other flipped cards
                    accessoryCards.forEach(otherCard => {
                        if (otherCard !== this && otherCard.classList.contains('flipped')) {
                            otherCard.classList.remove('flipped');
                        }
                    });
                }
            });
        });

        // Update on resize - remove flipped state when switching to desktop
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (!isMobileDevice()) {
                    accessoryCards.forEach(card => {
                        card.classList.remove('flipped');
                    });
                }
            }, 250);
        });

        // Gallery Lightbox Functionality
        const galleryItems = document.querySelectorAll('.gallery-item');
        const lightboxOverlay = document.getElementById('lightboxOverlay');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxClose = document.getElementById('lightboxClose');
        const lightboxPrev = document.getElementById('lightboxPrev');
        const lightboxNext = document.getElementById('lightboxNext');
        let currentImageIndex = 0;

        if (galleryItems.length > 0 && lightboxOverlay && lightboxImage) {
            const galleryImages = Array.from(galleryItems).map(item => item.getAttribute('data-image'));

            // Open lightbox
            galleryItems.forEach((item, index) => {
                item.addEventListener('click', function() {
                    currentImageIndex = index;
                    lightboxImage.src = galleryImages[currentImageIndex];
                    lightboxOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });

            // Close lightbox
            if (lightboxClose) {
                lightboxClose.addEventListener('click', function() {
                    lightboxOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            }

            // Close lightbox when clicking outside image
            lightboxOverlay.addEventListener('click', function(e) {
                if (e.target === lightboxOverlay) {
                    lightboxOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });

            // Navigate to previous image
            if (lightboxPrev) {
                lightboxPrev.addEventListener('click', function(e) {
                    e.stopPropagation();
                    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                    lightboxImage.src = galleryImages[currentImageIndex];
                });
            }

            // Navigate to next image
            if (lightboxNext) {
                lightboxNext.addEventListener('click', function(e) {
                    e.stopPropagation();
                    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                    lightboxImage.src = galleryImages[currentImageIndex];
                });
            }

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (lightboxOverlay.classList.contains('active')) {
                    if (e.key === 'Escape') {
                        lightboxOverlay.classList.remove('active');
                        document.body.style.overflow = 'auto';
                    } else if (e.key === 'ArrowLeft') {
                        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
                        lightboxImage.src = galleryImages[currentImageIndex];
                    } else if (e.key === 'ArrowRight') {
                        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
                        lightboxImage.src = galleryImages[currentImageIndex];
                    }
                }
            });
        }

        // WhatsApp Chat Popups Functionality
        function showChatPopups() {
            // Tampilkan popup pertama setelah 2 detik
            setTimeout(function() {
                const popup1 = document.getElementById('chatPopup1');
                if (popup1) {
                    popup1.classList.add('active');
                }
            }, 2000);

            // Tampilkan popup kedua setelah 4 detik
            setTimeout(function() {
                const popup2 = document.getElementById('chatPopup2');
                if (popup2) {
                    popup2.classList.add('active');
                }
            }, 4000);
        }

        function closeChatPopup(popupId) {
            const popup = document.getElementById(popupId);
            if (popup) {
                popup.classList.remove('active');
            }
        }

        // Tampilkan popup saat halaman dimuat atau di-refresh
        window.addEventListener('load', function() {
            showChatPopups();
        });

        // Make closeChatPopup globally available
        window.closeChatPopup = closeChatPopup;

        // Search Products Functionality
        const productSearchInput = document.getElementById('productSearchInput');
        const productsContainer = document.getElementById('productsContainer');

        if (productSearchInput && productsContainer) {
            productSearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const productCards = productsContainer.querySelectorAll('.product-card-link');
                let visibleCount = 0;

                productCards.forEach(card => {
                    const productName = card.querySelector('.product-name')?.textContent?.toLowerCase() ||
                        '';
                    const productDescription = card.querySelector('.product-description')?.textContent
                        ?.toLowerCase() || '';

                    if (searchTerm === '' || productName.includes(searchTerm) || productDescription
                        .includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Search Accessories Functionality
        const accessorySearchInput = document.getElementById('accessorySearchInput');
        const accessoriesContainer = document.getElementById('accessoriesContainer');

        if (accessorySearchInput && accessoriesContainer) {
            accessorySearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const accessoryCards = accessoriesContainer.querySelectorAll('.accessory-card');
                let visibleCount = 0;

                accessoryCards.forEach(card => {
                    const accessoryName = card.querySelector('.accessory-name')?.textContent
                        ?.toLowerCase() || '';
                    const accessoryDescription = card.querySelector('.accessory-back-description')
                        ?.textContent?.toLowerCase() || '';

                    if (searchTerm === '' || accessoryName.includes(searchTerm) || accessoryDescription
                        .includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Scroll Animation Functionality
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all sections and cards
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation classes to sections
            const sections = document.querySelectorAll('section');
            sections.forEach((section, index) => {
                section.classList.add('animate-on-scroll');
                section.style.animationDelay = ${index * 0.1}s;
                observer.observe(section);
            });

            // Add animation to product cards with stagger
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition =
                    opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s;

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            });

            // Add animation to accessory cards
            const accessoryCards = document.querySelectorAll('.accessory-card');
            accessoryCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                card.style.transition =
                    opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s;

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 100);
            });

            // Add animation to brand items
            const brandItems = document.querySelectorAll('.brand-item');
            brandItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition =
                    opacity 0.5s ease ${index * 0.05}s, transform 0.5s ease ${index * 0.05}s;

                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 100);
            });

            // Add animation to service cards
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition =
                    opacity 0.6s ease ${index * 0.15}s, transform 0.6s ease ${index * 0.15}s;

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 200);
            });

            // Add animation to gallery items
            const galleryItems = document.querySelectorAll('.gallery-item');
            galleryItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.9)';
                item.style.transition =
                    opacity 0.5s ease ${index * 0.05}s, transform 0.5s ease ${index * 0.05}s;

                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                }, 100);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
