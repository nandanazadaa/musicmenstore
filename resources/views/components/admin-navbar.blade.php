<!-- Admin Navbar -->
<nav class="admin-navbar bg-[#1a1a1a] border-b border-[#4a4a4a] w-full fixed top-0 left-0 right-0 z-50 overflow-x-hidden">
    <div class="w-full px-4 sm:px-6 lg:px-8 max-w-full">
        <div class="flex items-center justify-between h-16">
            <!-- Left Side: Toggle Button (Mobile Only) -->
            <div class="flex items-center">
                <!-- Sidebar Toggle Button (Mobile) -->
                <button id="sidebar-toggle" class="lg:hidden text-white hover:text-[#9a9a9a] transition-colors mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Right Side -->
            <div class="flex items-center gap-4">
                <!-- View Website Link -->
                <a href="{{ url('/') }}" target="_blank" 
                   class="flex items-center gap-2 text-[#9a9a9a] hover:text-white transition-colors text-sm uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" />
                    </svg>
                    <span class="hidden sm:inline">View Website</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    .admin-navbar {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        margin-left: 0;
        transition: margin-left 0.3s ease-in-out;
        width: 100%;
    }

    @media (min-width: 1024px) {
        .admin-navbar {
            margin-left: 16rem; /* 64 = 256px = 16rem */
            width: calc(100% - 16rem);
        }
    }

    body {
        padding-top: 64px;
        overflow-x: hidden;
    }
</style>
