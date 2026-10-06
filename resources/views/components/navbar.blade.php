<div class="navbar-container">
    <header class="bg-[#0a0a0a] border-b border-[#2a2a2a] w-full relative z-40">
        <div class="w-full px-3 md:px-6 lg:px-8 py-3 flex justify-between items-center relative gap-2 md:gap-4">
            
            <button id="hamburgerBtn" class="hamburger flex-shrink-0 md:hidden" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="navbar-logo-container flex items-center flex-shrink-0">
                <a href="{{ url('/') }}" class="text-2xl font-bold tracking-[0.2em] serif-font">
                    @php
                        $headerLogo = \App\Models\LandingPageSetting::getValue('header', 'logo', 'images/logo3.png');
                    @endphp
                    <img src="{{ asset($headerLogo) }}" alt="MUSICMEN" 
                         class="navbar-logo w-[100px] md:w-[140px] lg:w-[180px] max-h-[50px] md:max-h-[70px] object-contain transition-all duration-300">
                </a>
            </div>

            <div class="desktop-search hidden md:flex flex-1 justify-center px-2 lg:px-6">
                <div class="relative w-full max-w-[300px] lg:max-w-lg transition-all duration-300">
                    <label for="desktopSearchInput" class="sr-only">Cari produk atau kategori</label>
                    <input type="text" 
                           id="desktopSearchInput"
                           name="search"
                           placeholder="Search..."
                           aria-label="Cari produk atau kategori"
                           class="w-full py-2.5 pl-4 pr-10 bg-[#1a1a1a] border border-[#3a3a3a] text-white placeholder-[#6a6a6a] focus:outline-none focus:border-[#5a5a5a] transition-colors vintage-text text-sm lg:text-base">
                    <button type="button"
                            id="desktopSearchButton"
                            onclick="handleSearchClick('desktopSearchInput')"
                            class="absolute right-0 top-0 mt-2.5 mr-3 text-[#6a6a6a] hover:text-white transition-colors"
                            aria-label="Tombol cari">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end flex-shrink-0 gap-2 md:gap-3">
                @php
                    $memberName = Session::get('member_name');
                    $isMemberLoggedIn = Session::has('member_id');
                @endphp

                @if($isMemberLoggedIn)
                    <a href="{{ route('member.profile') }}" 
                       class="hidden md:flex items-center bg-transparent border border-[#4a4a4a] text-white px-3 lg:px-5 py-2 lg:py-2.5 hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all duration-300 vintage-border whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-0 lg:mr-2" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                        <span class="text-sm tracking-wide hidden lg:inline">Hi, {{ Str::limit($memberName, 10) }}</span>
                        <span class="text-sm tracking-wide inline lg:hidden">Profile</span>
                    </a>
                @else
                    <button id="memberBtn"
                        class="hidden md:flex items-center bg-transparent border border-[#4a4a4a] text-white px-3 lg:px-5 py-2 lg:py-2.5 hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all duration-300 vintage-border whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                        <span class="text-sm tracking-wide">Member</span>
                    </button>
                @endif

                <button id="mobileSearchIconBtn"
                    class="md:hidden flex items-center justify-center bg-transparent text-white hover:bg-[#2a2a2a] transition-all duration-300 p-2 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                @if($isMemberLoggedIn)
                    <a href="{{ route('member.profile') }}" 
                       class="md:hidden flex items-center justify-center bg-transparent text-white hover:bg-[#2a2a2a] transition-all duration-300 p-2 rounded flex-shrink-0"
                       title="Hi, {{ $memberName }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </a>
                @else
                    <button id="mobileMemberIconBtn"
                        class="md:hidden flex items-center justify-center bg-transparent text-white hover:bg-[#2a2a2a] transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none">
                            <path fill="currentColor"
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
        
        <div id="mobileSearchInput" class="hidden md:hidden w-full px-6 py-3 bg-[#0a0a0a] border-t border-[#2a2a2a]">
            <div class="relative">
                <label for="mobileSearchField" class="sr-only">Cari produk atau kategori</label>
                <input type="text" 
                       placeholder="Search..." 
                       id="mobileSearchField"
                       name="search"
                       aria-label="Cari produk atau kategori"
                       class="w-full py-2.5 pl-4 pr-10 bg-[#1a1a1a] border border-[#3a3a3a] text-white placeholder-[#6a6a6a] focus:outline-none focus:border-[#5a5a5a] transition-colors vintage-text">
                <button type="button" 
                        id="closeMobileSearch"
                        aria-label="Tutup pencarian"
                        class="absolute right-0 top-0 mt-2.5 mr-3 text-[#6a6a6a] hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Desktop Navigation -->
    <nav class="desktop-nav bg-[#0a0a0a] border-t border-[#2a2a2a] w-full hidden md:block">
        <div class="w-full px-2 lg:px-8 py-3">
            
            <ul class="flex justify-center items-center gap-3 md:gap-5 lg:gap-10 text-[11px] lg:text-sm font-light uppercase tracking-wider vintage-text flex-nowrap whitespace-nowrap">
                
                <li class="relative group flex-shrink-0">
                    <a href="#" class="py-2 hover:text-[#b4b4b4] flex items-center transition-colors">
                        NEW GEAR
                        <svg class="w-3 md:w-3.5 h-3 md:h-3.5 ml-1 transition-transform group-hover:rotate-180" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div
                        class="absolute left-0 mt-3 w-80 bg-[#1a1a1a] border border-[#3a3a3a] shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 vintage-border">
                        <ul class="py-2">
                            @php
                                $categories = [
                                    'acoustic-guitars' => ['db' => 'acoustic', 'name' => 'Acoustic Guitars'],
                                    'electric-guitars' => ['db' => 'electric', 'name' => 'Electric Guitars'],
                                    'bass-guitars' => ['db' => 'bass', 'name' => 'Bass Guitars'],
                                    'amplifiers' => ['db' => 'amplifier', 'name' => 'Amplifiers'],
                                    'effect' => ['db' => 'effect', 'name' => 'Effect'],
                                ];
                            @endphp
                            @foreach($categories as $slug => $cat)
                                @php
                                    $products = \App\Models\LandingPageProduct::where('category', $cat['db'])
                                        ->where('kondisi', '!=', 'used')
                                        ->orderBy('order', 'asc')
                                        ->limit(1)
                                        ->get();
                                @endphp
                                <li>
                                    <a href="{{ route('category.show', $slug) }}"
                                        class="block px-5 py-2.5 text-xs lg:text-sm hover:bg-[#2a2a2a] hover:text-white transition-colors vintage-text">
                                        {{ $cat['name'] }}
                                    </a>
                                    
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>

                <li class="flex-shrink-0">
                    <a href="{{ route('used-gear') }}" class="py-2 hover:text-[#b4b4b4] transition-colors">USED GEAR</a>
                </li>

                <li class="relative group flex-shrink-0">
                    <a href="#" class="py-2 hover:text-[#b4b4b4] flex items-center transition-colors">
                        ITEM
                        <svg class="w-3 md:w-3.5 h-3 md:h-3.5 ml-1 transition-transform group-hover:rotate-180" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div
                        class="absolute left-0 mt-3 w-52 bg-[#1a1a1a] border border-[#3a3a3a] shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 vintage-border">
                        <ul class="py-2">
                            <li><a href="{{ route('accessories') }}"
                                    class="block px-5 py-2.5 text-xs lg:text-sm hover:bg-[#2a2a2a] hover:text-white transition-colors vintage-text">Accessories</a>
                            </li>
                            <li><a href="{{ route('merchandise') }}"
                                    class="block px-5 py-2.5 text-xs lg:text-sm hover:bg-[#2a2a2a] hover:text-white transition-colors vintage-text">Merchandise</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="flex-shrink-0">
                    <a href="{{ url('/') }}#gallery" onclick="scrollToSection(event, 'gallery');" class="py-2 hover:text-[#b4b4b4] transition-colors">GALLERY</a>
                </li>

                <li class="flex-shrink-0">
                    <a href="{{ url('/') }}#about" onclick="scrollToSection(event, 'about');" class="py-2 hover:text-[#b4b4b4] transition-colors">ABOUT</a>
                </li>

                <li class="flex-shrink-0">
                    <a href="{{ url('/') }}#sell-your-gear" onclick="scrollToSection(event, 'sell-your-gear');" class="py-2 hover:text-[#b4b4b4] transition-colors">SELL YOUR GEAR</a>
                </li>

                <li class="flex-shrink-0">
                    <a href="{{ url('/') }}#contact" onclick="scrollToSection(event, 'contact');" class="py-2 hover:text-[#b4b4b4] transition-colors">CONTACT</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobileMenuOverlay" class="mobile-menu-overlay"></div>

<!-- Mobile Menu -->
<div id="mobileMenu" class="mobile-menu">
    <div class="p-6">
        <!-- Mobile Menu Header -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#2a2a2a]">
            <img src="{{ asset('images/logo3.png') }}" alt="MUSICMEN" class="filter brightness-0 invert"
                style="width: 100px; height: 60px; object-fit: contain;">
            <button id="closeMobileMenu" class="text-white hover:text-[#b4b4b4] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Search -->
        <div class="mb-6">
            <div class="relative">
                <label for="mobileMenuSearch" class="sr-only">Cari produk atau kategori</label>
                <input type="text" 
                       id="mobileMenuSearch"
                       name="search"
                       placeholder="Search..."
                       aria-label="Cari produk atau kategori"
                       class="w-full py-2.5 pl-4 pr-10 bg-[#1a1a1a] border border-[#3a3a3a] text-white placeholder-[#6a6a6a] focus:outline-none focus:border-[#5a5a5a] transition-colors vintage-text">
                <button type="button"
                        id="mobileMenuSearchButton"
                        onclick="handleSearchClick('mobileMenuSearch')"
                        aria-label="Tombol cari"
                        class="absolute right-0 top-0 mt-2.5 mr-3 text-[#6a6a6a] hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <nav class="mb-6">
            <ul class="space-y-2">
                <!-- NEW GEAR -->
                <li>
                    <button
                        class="mobile-menu-item w-full text-left py-3 px-4 text-sm uppercase tracking-wider vintage-text hover:bg-[#1a1a1a] transition-colors flex items-center justify-between"
                        onclick="toggleMobileSubmenu(this)">
                        NEW GEAR
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <ul class="mobile-submenu hidden pl-4 space-y-1">
                        <li><a href="{{ route('category.show', 'acoustic-guitars') }}"
                                class="block py-2 px-4 text-sm hover:bg-[#1a1a1a] transition-colors vintage-text">Acoustic
                                Guitars</a></li>
                        <li><a href="{{ route('category.show', 'electric-guitars') }}"
                                class="block py-2 px-4 text-sm hover:bg-[#1a1a1a] transition-colors vintage-text">Electric
                                Guitars</a></li>
                        <li><a href="{{ route('category.show', 'bass-guitars') }}"
                                class="block py-2 px-4 text-sm hover:bg-[#1a1a1a] transition-colors vintage-text">Bass
                                Guitars</a></li>
                        <li><a href="{{ route('category.show', 'amplifiers') }}"
                                class="block py-2 px-4 text-sm hover:bg-[#1a1a1a] transition-colors vintage-text">Amplifiers</a>
                        </li>
                        <li><a href="{{ route('category.show', 'effect') }}"
                                class="block py-2 px-4 text-sm hover:bg-[#1a1a1a] transition-colors vintage-text">Effect</a>
                        </li>
                    </ul>
                </li>

                <!-- USED GEAR (No Dropdown) -->
                <li>
                    <a href="{{ route('used-gear') }}"
                        class="block py-3 px-4 text-sm uppercase tracking-wider vintage-text hover:bg-[#1a1a1a] transition-colors">USED
                        GEAR</a>
                </li>

                <!-- ITEM -->
                <li>
                    <button
                        class="mobile-menu-item w-full text-left py-3 px-4 text-sm uppercase tracking-wider vintage-text hover:bg-[#1a1a1a] transition-colors flex items-center justify-between"
                        onclick="toggleMobileSubmenu(this)">
                        ITEM
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <ul class="mobile-submenu hidden pl-4 space-y-1">
                        <li><a href="{{ route('accessories') }}"
                                class="block py-2 px-4 text-sm hover:bg-[#1a1a1a] transition-colors vintage-text">Accessories</a>
                        </li>
                        <li><a href="{{ route('merchandise') }}"
                                class="block py-2 px-4 text-sm hover:bg-[#1a1a1a] transition-colors vintage-text">Merchandise</a>
                        </li>
                    </ul>
                </li>

                <!-- Other Menu Items -->
                <li><a href="{{ url('/') }}#gallery" onclick="closeMobileMenuFunc(); scrollToSection(event, 'gallery');"
                        class="block py-3 px-4 text-sm uppercase tracking-wider vintage-text hover:bg-[#1a1a1a] transition-colors">GALLERY</a>
                </li>
                <li><a href="{{ url('/') }}#about" onclick="closeMobileMenuFunc(); scrollToSection(event, 'about');"
                        class="block py-3 px-4 text-sm uppercase tracking-wider vintage-text hover:bg-[#1a1a1a] transition-colors">ABOUT</a>
                </li>
                <li><a href="{{ url('/') }}#sell-your-gear" onclick="closeMobileMenuFunc(); scrollToSection(event, 'sell-your-gear');"
                        class="block py-3 px-4 text-sm uppercase tracking-wider vintage-text hover:bg-[#1a1a1a] transition-colors">SELL
                        YOUR GEAR</a></li>
                <li><a href="{{ url('/') }}#contact" onclick="closeMobileMenuFunc(); scrollToSection(event, 'contact');"
                        class="block py-3 px-4 text-sm uppercase tracking-wider vintage-text hover:bg-[#1a1a1a] transition-colors">CONTACT</a>
                </li>
            </ul>
        </nav>

        <!-- Mobile Member Button -->
        @php
            $memberName = Session::get('member_name');
            $isMemberLoggedIn = Session::has('member_id');
        @endphp
        @if($isMemberLoggedIn)
            <a href="{{ route('member.profile') }}" 
               class="w-full flex items-center justify-center bg-transparent border border-[#4a4a4a] text-white px-5 py-3 hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all duration-300 vintage-border">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                <span class="text-sm tracking-wide">Hi, {{ $memberName }}</span>
            </a>
        @else
            <button id="mobileMemberBtn"
                class="w-full flex items-center justify-center bg-transparent border border-[#4a4a4a] text-white px-5 py-3 hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all duration-300 vintage-border">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                <span class="text-sm tracking-wide">Member</span>
            </button>
        @endif
    </div>
</div>

<style>
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
</style>

@push('scripts')
<script>
    // Mobile Menu Functionality
    document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileMemberBtn = document.getElementById('mobileMemberBtn');

    // Toggle Mobile Menu
    function openMobileMenu() {
        mobileMenu.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        hamburgerBtn.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenuFunc() {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        hamburgerBtn.classList.remove('active');
        document.body.style.overflow = 'auto';
        // Close all submenus
        document.querySelectorAll('.mobile-submenu').forEach(submenu => {
            submenu.style.maxHeight = '0';
            setTimeout(() => {
                submenu.classList.add('hidden');
            }, 300);
        });
        document.querySelectorAll('.mobile-menu-item svg').forEach(svg => {
            svg.style.transform = 'rotate(0deg)';
        });
    }

    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function() {
            if (mobileMenu.classList.contains('active')) {
                closeMobileMenuFunc();
            } else {
                openMobileMenu();
            }
        });
    }

    // Mobile Search Toggle
    const mobileSearchIconBtn = document.getElementById('mobileSearchIconBtn');
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    const closeMobileSearch = document.getElementById('closeMobileSearch');
    const mobileSearchField = document.getElementById('mobileSearchField');

    if (mobileSearchIconBtn && mobileSearchInput) {
        mobileSearchIconBtn.addEventListener('click', function() {
            mobileSearchInput.classList.remove('hidden');
            mobileSearchField.focus();
        });

        if (closeMobileSearch) {
            closeMobileSearch.addEventListener('click', function() {
                mobileSearchInput.classList.add('hidden');
                mobileSearchField.value = '';
            });
        }

        // Close search when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileSearchInput.contains(event.target) && 
                !mobileSearchIconBtn.contains(event.target) &&
                !mobileSearchInput.classList.contains('hidden')) {
                mobileSearchInput.classList.add('hidden');
                mobileSearchField.value = '';
            }
        });
    }

    if (closeMobileMenu) {
        closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
    }

    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenuFunc);
    }

    // Close mobile menu with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('active')) {
            closeMobileMenuFunc();
        }
    });

    // Toggle Mobile Submenu (make it globally available)
    window.toggleMobileSubmenu = function(button) {
        const submenu = button.nextElementSibling;
        const svg = button.querySelector('svg');

        if (submenu && submenu.classList.contains('mobile-submenu')) {
            const isHidden = submenu.classList.contains('hidden');

            // Close all other submenus
            document.querySelectorAll('.mobile-submenu').forEach(menu => {
                if (menu !== submenu) {
                    menu.classList.add('hidden');
                    menu.style.maxHeight = '0';
                }
            });
            document.querySelectorAll('.mobile-menu-item svg').forEach(icon => {
                if (icon !== svg) {
                    icon.style.transform = 'rotate(0deg)';
                }
            });

            // Toggle current submenu
            if (isHidden) {
                submenu.classList.remove('hidden');
                // Force reflow
                submenu.offsetHeight;
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                submenu.style.opacity = '1';
                svg.style.transform = 'rotate(180deg)';
            } else {
                submenu.style.maxHeight = '0';
                submenu.style.opacity = '0';
                setTimeout(() => {
                    submenu.classList.add('hidden');
                }, 300);
                svg.style.transform = 'rotate(0deg)';
            }
        }
    };

    // Mobile Member Button - Only if member is not logged in
    if (mobileMemberBtn) {
        mobileMemberBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeMobileMenuFunc();
            // Trigger member modal
            const memberModal = document.getElementById('memberModal');
            const memberIdInput = document.getElementById('memberId');
            if (memberModal && memberIdInput) {
                memberModal.classList.add('active');
                // Generate Member ID
                function generateMemberId() {
                    const prefix = 'MM';
                    const timestamp = Date.now().toString().slice(-6);
                    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                    return `${prefix}${timestamp}${random}`;
                }
                memberIdInput.value = generateMemberId();
                document.body.style.overflow = 'hidden';
            }
        });
    }

    // Desktop Member Button - Only if member is not logged in
    const memberBtn = document.getElementById('memberBtn');
    const memberModal = document.getElementById('memberModal');
    const memberIdInput = document.getElementById('memberId');
    
    if (memberBtn && memberModal) {
        memberBtn.addEventListener('click', function(e) {
            e.preventDefault();
            memberModal.classList.add('active');
            if (memberIdInput) {
                // Generate Member ID
                function generateMemberId() {
                    const prefix = 'MM';
                    const timestamp = Date.now().toString().slice(-6);
                    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                    return `${prefix}${timestamp}${random}`;
                }
                memberIdInput.value = generateMemberId();
            }
            document.body.style.overflow = 'hidden';
        });
    }

    // Mobile Member Icon Button - Only if member is not logged in
    const mobileMemberIconBtn = document.getElementById('mobileMemberIconBtn');
    if (mobileMemberIconBtn && memberModal) {
        mobileMemberIconBtn.addEventListener('click', function(e) {
            e.preventDefault();
            memberModal.classList.add('active');
            if (memberIdInput) {
                // Generate Member ID
                function generateMemberId() {
                    const prefix = 'MM';
                    const timestamp = Date.now().toString().slice(-6);
                    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                    return `${prefix}${timestamp}${random}`;
                }
                memberIdInput.value = generateMemberId();
            }
            document.body.style.overflow = 'hidden';
        });
    }

    // Smooth scroll to section function
    window.scrollToSection = function(event, sectionId) {
        // Check if we're on the home page
        const currentPath = window.location.pathname;
        if (currentPath !== '/' && currentPath !== '') {
            // If not on home page, navigate to home first, then scroll
            event.preventDefault();
            window.location.href = '/' + '#' + sectionId;
            return;
        }
        
        // If already on home page, smooth scroll
        event.preventDefault();
        const section = document.getElementById(sectionId);
        if (section) {
            const offsetTop = section.offsetTop - 100; // Offset untuk navbar
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    };

    // Handle anchor links on page load
    window.addEventListener('load', function() {
        const hash = window.location.hash;
        if (hash) {
            const sectionId = hash.substring(1);
            const section = document.getElementById(sectionId);
            if (section) {
                setTimeout(() => {
                    const offsetTop = section.offsetTop - 100;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }, 100);
            }
        }
    });

    // Global Search Functionality - Make it globally available
    window.handleSearch = function(query) {
        if (!query || query.trim() === '') {
            return;
        }
        const searchUrl = '{{ route("search") }}?q=' + encodeURIComponent(query.trim());
        window.location.href = searchUrl;
    };

    // Handle search button click - Make it globally available
    window.handleSearchClick = function(inputId) {
        const input = document.getElementById(inputId);
        if (input && input.value.trim() !== '') {
            window.handleSearch(input.value);
        }
    };

    // Initialize search functionality after DOM is ready
    function initSearch() {
        // Desktop Search
        const desktopSearchInput = document.getElementById('desktopSearchInput');
        const desktopSearchButton = document.getElementById('desktopSearchButton');
        
        if (desktopSearchInput) {
            // Handle Enter key
            desktopSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch(desktopSearchInput.value);
                }
            });

            // Handle search button click
            if (desktopSearchButton) {
                desktopSearchButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleSearch(desktopSearchInput.value);
                });
            }
        }

        // Mobile Search Field (in header)
        const mobileSearchField = document.getElementById('mobileSearchField');
        const mobileSearchButton = document.getElementById('mobileSearchButton');
        
        if (mobileSearchField) {
            mobileSearchField.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch(mobileSearchField.value);
                }
            });

            if (mobileSearchButton) {
                mobileSearchButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleSearch(mobileSearchField.value);
                });
            }
        }

        // Mobile Menu Search
        const mobileMenuSearch = document.getElementById('mobileMenuSearch');
        const mobileMenuSearchButton = document.getElementById('mobileMenuSearchButton');
        
        if (mobileMenuSearch) {
            mobileMenuSearch.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleSearch(mobileMenuSearch.value);
                }
            });

            if (mobileMenuSearchButton) {
                mobileMenuSearchButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleSearch(mobileMenuSearch.value);
                });
            }
        }
    }

    // Initialize search when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearch);
    } else {
       
        initSearch();
    }

    }); 
</script>
@endpush