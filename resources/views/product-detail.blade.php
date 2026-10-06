<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ is_object($product) ? $product->nama_barang : ($product['name'] ?? 'Product Detail') }} - Musicmen Store</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<style>
    .product-card {
            min-width: 320px;
            max-width: 320px;
            width: 320px;
            height: 600px;
            min-height: 600px;
            max-height: 600px;
            background-color: #1a1a1a; /* Warna gelap senada dengan section service */
            border: 1px solid #525252; /* Border halus di pinggir */
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
</style>
<body>
    @include('components.navbar')

    <main class="min-h-screen bg-[#0a0a0a] text-white pt-24 pb-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <!-- Product Detail Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-16">
                <!-- Product Image -->
                <div class="product-image-section">
                    <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                        @if(is_object($product) && method_exists($product, 'images') && $product->images->count() > 0)
                            <!-- Multiple Images Slider -->
                            <div class="product-image-slider">
                                <div class="main-image-container mb-4">
                                    <img id="main-product-image" src="{{ asset($product->images->first()->image_path) }}" 
                                         alt="{{ $product->nama_barang }}"
                                         class="w-full h-auto rounded-lg object-cover cursor-pointer">
                                </div>
                                @if($product->images->count() > 1)
                                    <div class="thumbnail-container flex gap-2 overflow-x-auto pb-2">
                                        @foreach($product->images as $index => $image)
                                            <img src="{{ asset($image->image_path) }}" 
                                                 alt="{{ $product->nama_barang }} - Image {{ $index + 1 }}"
                                                 class="thumbnail-image w-20 h-20 object-cover rounded-lg border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all {{ $index === 0 ? 'border-blue-500' : '' }}"
                                                 data-image="{{ asset($image->image_path) }}">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @elseif(is_object($product) && $product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->nama_barang }}"
                                class="w-full h-auto rounded-lg object-cover">
                        @elseif(is_object($product))
                            <div class="w-full h-96 bg-[#2a2a2a] rounded-lg flex items-center justify-center">
                                <span class="text-[#6a6a6a]">No Image</span>
                            </div>
                        @else
                            @if(isset($product['image']))
                                <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] ?? 'Product' }}"
                                    class="w-full h-auto rounded-lg object-cover">
                            @else
                                <div class="w-full h-96 bg-[#2a2a2a] rounded-lg flex items-center justify-center">
                                    <span class="text-[#6a6a6a]">No Image</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

            
            
                <!-- Member Registration Modal -->
                <div id="memberModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
                    <div class="modal-container">
                        <button id="closeModal" class="modal-close" aria-label="Tutup modal registrasi">&times;</button>
                        <h2 id="modalTitle" class="modal-title">Member Registration</h2>
                        <div class="text-center py-4">
                            <p class="text-white mb-6">Daftar sebagai anggota untuk mendapatkan akses ke profil Anda dan fitur eksklusif lainnya.</p>
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

                <!-- Product Info -->
                <div class="product-info-section">
                    <div class="mb-6">
                        <h1 class="serif-font text-4xl md:text-5xl lg:text-6xl font-normal mb-4 text-white">
                            {{ is_object($product) ? $product->nama_barang : ($product['name'] ?? 'Product Name') }}
                        </h1>
                        <div class="w-24 h-0.5 bg-white mb-6"></div>
                    </div>

                    <h4 class="mb-3">Condition</h4>
                    <!-- Condition Badge -->
                    <div class="mb-6">
                        @if(is_object($product))
                            <span class="inline-block px-4 py-2 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-sm font-medium">
                                {{ $product->kondisi_name }}
                            </span>
                        @else
                            <span class="inline-block px-4 py-2 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-sm font-medium">
                                {{ $product['condition'] ?? 'Great Condition' }}
                            </span>
                        @endif
                    </div>

                    <h4 class="mb-3">Price</h4>

                    <!-- Price -->
                    <div class="mb-8">
                        <p class="text-3xl md:text-4xl font-semibold text-white mb-2">
                            @if(is_object($product))
                                {{ $product->formatted_price }}
                            @else
                                IDR {{ $product['price'] ?? '0' }}
                            @endif
                        </p>
                    </div>

                    <!-- Bonus/Description -->
                    @if(is_object($product) && $product->bonus)
    {{-- Hanya tampilkan "Included" jika kategori BUKAN merchandise --}}
    @if($product->category !== 'merchandise')
        <div class="mb-6 p-4 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg">
            <p class="text-[#d4d4d4] text-base leading-relaxed">
                <span class="font-semibold text-white">Included:</span> {{ $product->bonus }}
            </p>
        </div>
    @endif
@elseif(isset($product['description']) && $product['description'])
    <div class="mb-6 p-4 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg">
        <p class="text-[#d4d4d4] text-base leading-relaxed">
            {{ $product['description'] }}
        </p>
    </div>
@endif

                    <!-- Category -->
                    @if(is_object($product))
                        <div class="mb-6">
                            <p class="text-sm text-[#9a9a9a] mb-2">Category</p>
                            <span class="inline-block px-4 py-2 bg-purple-900/30 border border-purple-500/50 rounded text-purple-400 text-sm">
                                {{ $product->category_name }}
                            </span>
                        </div>
                    @endif

                    <!-- WhatsApp Contact Button -->
                    <div class="mt-8">
                        <a href="https://wa.me/628816707166?text={{ urlencode('Halo Men, Apakah Produk Ini Masih Tersedia?' . (is_object($product) ? $product->nama_barang : ($product['name'] ?? 'this product'))) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg transition-all uppercase tracking-wider font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                            Contact via WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product Description Section -->
           @if(is_object($product) && ($product->description || ($product->category === 'merchandise' && $product->bonus)))
    <div class="mb-12 pt-8 border-t border-[#2a2a2a]">
        <h2 class="serif-font text-3xl md:text-4xl font-normal mb-6 text-white">Product Description</h2>
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 md:p-8">
            <p class="text-base md:text-lg text-[#d4d4d4] leading-relaxed vintage-text whitespace-pre-line">
                @if($product->category === 'merchandise')
                    {{-- Jika merchandise, ambil dari kolom bonus --}}
                    {{ $product->bonus }}
                @else
                    {{-- Jika produk biasa, ambil dari kolom description --}}
                    {{ $product->description }}
                @endif
            </p>
        </div>
    </div>
@endif

            <!-- Order Information Section -->
            @if(is_object($product) && $product->order_info)
                <div class="mb-12 pt-8 border-t border-[#2a2a2a]">
                    <h2 class="serif-font text-3xl md:text-4xl font-normal mb-6 text-white">Order Information</h2>
                    <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 md:p-8">
                        <p class="text-base md:text-lg text-[#d4d4d4] leading-relaxed whitespace-pre-line">
                            {!! nl2br(e($product->order_info)) !!}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Payment Options Section -->
            <div class="mb-8 pt-6 border-t border-[#2a2a2a]">
                <h2 class="serif-font text-xl md:text-2xl font-normal mb-4 text-white">Payment Options</h2>
                <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 md:p-5">
                    @php
                        // Mengambil data ikon pembayaran global dari tabel landing_page_settings
                        $paymentIconsJson = \App\Models\LandingPageSetting::getValue('products', 'payment_icons', '[]');
                        $paymentIcons = json_decode($paymentIconsJson, true) ?: [];
                    @endphp
            
                    @if(count($paymentIcons) > 0)
                        <div class="flex flex-wrap items-start justify-start gap-6">
                            @foreach($paymentIcons as $icon)
                                <img src="{{ asset($icon) }}" alt="Payment Method"
                                    class="h-8 md:h-10 w-auto object-contain opacity-90 hover:opacity-100 transition-opacity">
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-start justify-center">
                            <p class="text-sm text-[#d4d4d4] text-left">Cash / Bank Transfer</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-12 text-center">
                <a href="{{ url('/') }}"
                    class="inline-block bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg uppercase tracking-wider hover:bg-white hover:text-[#0a0a0a] transition-all duration-300 font-medium text-sm">
                    Back to Home
                </a>
            </div>
        </div>
    </main>

    @include('components.footer')

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
                        document.body.style.overflow = 'hidden';
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
                document.body.style.overflow = 'auto';
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
            return `${prefix}${timestamp}${random}`;
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
        // Product Image Slider
        const thumbnailImages = document.querySelectorAll('.thumbnail-image');
        const mainImage = document.getElementById('main-product-image');
        
        if (thumbnailImages.length > 0 && mainImage) {
            thumbnailImages.forEach((thumb, index) => {
                thumb.addEventListener('click', function() {
                    mainImage.src = this.getAttribute('data-image');
                    thumbnailImages.forEach(t => t.classList.remove('border-blue-500'));
                    this.classList.add('border-blue-500');
                });
            });
        }

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
                    const productName = card.querySelector('.product-name')?.textContent?.toLowerCase() || '';
                    const productDescription = card.querySelector('.product-description')?.textContent?.toLowerCase() || '';
                    
                    if (searchTerm === '' || productName.includes(searchTerm) || productDescription.includes(searchTerm)) {
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
                    const accessoryName = card.querySelector('.accessory-name')?.textContent?.toLowerCase() || '';
                    const accessoryDescription = card.querySelector('.accessory-back-description')?.textContent?.toLowerCase() || '';
                    
                    if (searchTerm === '' || accessoryName.includes(searchTerm) || accessoryDescription.includes(searchTerm)) {
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
                section.style.animationDelay = `${index * 0.1}s`;
                observer.observe(section);
            });

            // Add animation to product cards with stagger
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition =
                    `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;

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
                    `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;

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
                    `opacity 0.5s ease ${index * 0.05}s, transform 0.5s ease ${index * 0.05}s`;

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
                    `opacity 0.6s ease ${index * 0.15}s, transform 0.6s ease ${index * 0.15}s`;

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
                    `opacity 0.5s ease ${index * 0.05}s, transform 0.5s ease ${index * 0.05}s`;

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
