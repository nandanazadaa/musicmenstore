<aside id="admin-sidebar"
    class="admin-sidebar bg-[#1a1a1a] border-r border-[#4a4a4a] fixed left-0 w-64 z-40 transition-transform duration-300 ease-in-out overflow-hidden"
    style="top: 0; height: 100vh;">
    <div class="flex flex-col h-full">
        <div class="logo-section px-4 sm:px-6 lg:px-8 pt-16 pb-4 border-b border-[#4a4a4a]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo4.png') }}" alt="MUSICMEN" class="h-8 w-auto object-contain flex-shrink-0">
                <span class="text-white text-lg font-light serif-font tracking-wide">Admin Panel</span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-3">
                @if (Auth::user()->hasPermission('dashboard'))
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="admin-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Dashboard</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.sop.index') }}"
                            class="admin-menu-item {{ request()->routeIs('admin.sop.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">SOP Center</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role === 'staff')
                    <li>
                        <a href="{{ route('staff.sop.index') }}"
                            class="admin-menu-item {{ request()->routeIs('staff.sop.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">SOP Regulasi</span>
                        </a>
                    </li>
                @endif

                <li class="my-4">
                    <div class="h-px bg-[#4a4a4a]"></div>
                </li>

                {{-- Cek apakah admin atau staff yang punya salah satu akses service --}}
                @if (Auth::user()->role === 'admin' ||
                        Auth::user()->hasPermission('service_harian_input') ||
                        Auth::user()->hasPermission('service_harian_kelola'))
                    <li class="px-4 py-2">
                        <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">Operasional Service</p>
                    </li>

                    @if (Auth::user()->role === 'staff')
                        {{-- Menu Frontdesk - Muncul jika service_harian_input dicentang --}}
                        @if (Auth::user()->hasPermission('service_harian_input'))
                            <li>
                                <a href="{{ route('staff.service-harian.input-index') }}"
                                    class="admin-menu-item {{ request()->routeIs('staff.service-harian.input-index') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                        <path d="M15 12h-6" />
                                        <path d="M12 9v6" />
                                    </svg>
                                    <span class="text-sm uppercase tracking-wider">Input Service (5%)</span>
                                </a>
                            </li>
                        @endif

                        {{-- Menu Teknisi - Muncul jika service_harian_kelola dicentang --}}
                        @if (Auth::user()->hasPermission('service_harian_kelola'))
                            <li>
                                <a href="{{ route('staff.service-harian.kelola-index') }}"
                                    class="admin-menu-item {{ request()->routeIs('staff.service-harian.kelola-index') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
                                    </svg>
                                    <span class="text-sm uppercase tracking-wider">Kelola Service (30%)</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    {{-- Tampilan Rekap untuk Admin --}}
                    @if (Auth::user()->role === 'admin')
                        <li>
                            <a href="{{ route('admin.service-harian.index') }}"
                                class="admin-menu-item {{ request()->routeIs('admin.service-harian.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                    <path
                                        d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path d="M9 12l.01 0" />
                                    <path d="M13 12l2 0" />
                                    <path d="M9 16l.01 0" />
                                    <path d="M13 16l2 0" />
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Rekap Semua Service</span>
                            </a>
                        </li>
                    @endif
                @endif

                @if (Auth::user()->role === 'admin' && Auth::user()->hasPermission('assignments'))
                    <li>
                        <a href="{{ route('admin.assignments.index') }}"
                            class="admin-menu-item {{ request()->routeIs('admin.assignments.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Tugas</span>
                        </a>
                    </li>
                @endif

                <li class="my-4">
                    <div class="h-px bg-[#4a4a4a]"></div>
                </li>

                @if (Auth::user()->role === 'staff')
                    {{-- Cek apakah ada setidaknya satu menu staff yang aktif --}}
                    @php
                        $hasStaffMenu =
                            Auth::user()->hasPermission('attendance') ||
                            Auth::user()->hasPermission('shifts') ||
                            Auth::user()->hasPermission('leaves') ||
                            Auth::user()->hasPermission('daily_sales') ||
                            Auth::user()->hasPermission('payroll') ||
                            Auth::user()->hasPermission('assignments');
                    @endphp

                    @if ($hasStaffMenu)
                        <li class="px-4 py-2">
                            <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">Menu Staff</p>
                        </li>
                    @endif

                    @if (Auth::user()->hasPermission('attendance'))
                        <li>
                            <a href="{{ route('staff.attendance.index') }}"
                                class="admin-menu-item {{ request()->routeIs('staff.attendance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Absensi</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->hasPermission('shifts'))
                        <li>
                            <a href="{{ route('staff.shifts.index') }}"
                                class="admin-menu-item {{ request()->routeIs('staff.shifts.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                    </rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                    <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"></path>
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Jadwal Shift</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->hasPermission('leaves'))
                        <li>
                            <a href="{{ route('staff.leaves.index') }}"
                                class="admin-menu-item {{ request()->routeIs('staff.leaves.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Pengajuan Cuti</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->hasPermission('daily_sales'))
                        <li>
                            <a href="{{ route('staff.daily-sales.index') }}"
                                class="admin-menu-item {{ request()->routeIs('staff.daily-sales.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Form Penjualan Harian</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->hasPermission('payroll'))
                        <li>
                            <a href="{{ route('staff.payroll.index') }}"
                                class="admin-menu-item {{ request()->routeIs('staff.payroll.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Penerimaan Gaji</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->hasPermission('assignments'))
                        <li>
                            <a href="{{ route('staff.assignments.index') }}"
                                class="admin-menu-item {{ request()->routeIs('staff.assignments.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Penugasan</span>
                                <span id="assignment-badge"
                                    class="hidden absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">0</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasStaffMenu)
                        <li class="my-4">
                            <div class="h-px bg-[#4a4a4a]"></div>
                        </li>
                    @endif
                @endif

                @if (Auth::user()->role === 'admin')
                    <li class="px-4 py-2">
                        <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">Manajemen</p>
                    </li>

                    @if (Auth::user()->hasPermission('attendance_report'))
                        <li>
                            <a href="{{ route('admin.attendance.index') }}"
                                class="admin-menu-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Laporan Absensi</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ route('admin.shifts.index') }}"
                            class="admin-menu-item {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                </rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"></path>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Jadwal Shift</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.leaves.index') }}" id="leaves-menu-link"
                            class="admin-menu-item {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Pengajuan Cuti</span>
                            <span id="leaves-badge"
                                class="hidden absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.payroll.index') }}"
                            class="admin-menu-item {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Payroll / Slip Gaji</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.daily-sales.index') }}" id="daily-sales-menu-link"
                            class="admin-menu-item {{ request()->routeIs('admin.daily-sales.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Form Penjualan Harian</span>
                            <span id="daily-sales-badge"
                                class="hidden absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </a>
                    </li>

                    <li class="my-4">
                        <div class="h-px bg-[#4a4a4a]"></div>
                    </li>
                @endif

                @php
                    $canSeeProducts = Auth::user()->hasPermission('products');
                    $canSeeInventory = Auth::user()->hasPermission('inventory');
                    $canSeeSales = Auth::user()->hasPermission('sales');
                @endphp

                @if (Auth::user()->role === 'admin' || ($canSeeProducts || $canSeeInventory || $canSeeSales))
                    <li class="px-4 py-2">
                        <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">Produk & Penjualan</p>
                    </li>

                    @if (Auth::user()->role === 'admin' || $canSeeProducts)
                        <li>
                            <a href="{{ Auth::user()->role === 'admin' ? route('admin.products.index') : route('staff.products.index') }}"
                                id="products-menu-link"
                                class="admin-menu-item {{ request()->routeIs('*.products.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 17h-11v-14h-2" />
                                    <path d="M6 5l14 1l-1 7h-13" />
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Input Instrumen Masuk</span>
                                @if (Auth::user()->role === 'admin')
                                    <span id="pending-products-badge"
                                        class="hidden absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">0</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role === 'admin' || $canSeeInventory)
                        <li>
                            <a href="{{ route('staff.inventory.index') }}"
                                class="admin-menu-item {{ request()->routeIs('staff.inventory.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Inventory & Checkup</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role === 'admin' || $canSeeSales)
                        <li>
                            <a href="{{ Auth::user()->role === 'admin' ? route('admin.sales.index') : route('staff.sales.index') }}"
                                id="sales-instruments-menu-link"
                                class="admin-menu-item {{ request()->routeIs('*.sales.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                <span class="text-sm uppercase tracking-wider">Input Sales Instrumen</span>
                            </a>
                        </li>
                    @endif

                    <li class="my-4">
                        <div class="h-px bg-[#4a4a4a]"></div>
                    </li>
                @endif

                @if (Auth::user()->role === 'admin' ||
                        (Auth::user()->role === 'staff' &&
                            (Auth::user()->hasPermission('products') || Auth::user()->hasPermission('sales'))))
                    <li class="my-4">
                        <div class="h-px bg-[#4a4a4a]"></div>
                    </li>
                @endif

                @if (Auth::user()->role === 'admin' ||
                        (Auth::user()->role === 'staff' &&
                            (Auth::user()->hasPermission('members') || Auth::user()->hasPermission('rewards'))))
                    <li class="px-4 py-2">
                        <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">Members</p>
                    </li>
                @endif

                @if (Auth::user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.members.index') }}" id="members-menu-link"
                            class="admin-menu-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Members</span>
                            <span id="members-badge"
                                class="hidden absolute top-2 right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </a>
                    </li>
                @elseif(Auth::user()->role === 'staff' && Auth::user()->hasPermission('members'))
                    <li>
                        <a href="{{ route('staff.members.index') }}"
                            class="admin-menu-item {{ request()->routeIs('staff.members.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Members</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.rewards.index') }}"
                            class="admin-menu-item {{ request()->routeIs('admin.rewards.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                                </path>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Rewards</span>
                        </a>
                    </li>
                @elseif(Auth::user()->role === 'staff' && Auth::user()->hasPermission('rewards'))
                    <li>
                        <a href="{{ route('staff.rewards.index') }}"
                            class="admin-menu-item {{ request()->routeIs('staff.rewards.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                                </path>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Rewards</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role === 'admin' ||
                        (Auth::user()->role === 'staff' &&
                            (Auth::user()->hasPermission('members') || Auth::user()->hasPermission('rewards'))))
                    <li class="my-4">
                        <div class="h-px bg-[#4a4a4a]"></div>
                    </li>
                @endif

                @if (Auth::user()->role === 'admin' && Auth::user()->hasPermission('users_management'))
                    <li class="px-4 py-2">
                        <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">User Management</p>
                    </li>
                    <li>
                        <div class="users-management-menu">
                            <button type="button"
                                class="admin-menu-item {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.staffs.*') ? 'active' : '' }} w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all"
                                id="usersManagementMenuBtn">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <span class="text-sm uppercase tracking-wider">User Management</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 users-management-arrow transition-transform" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <ul class="users-management-submenu hidden pl-4 mt-1 space-y-1"
                                id="usersManagementSubmenu">
                                <li>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Users</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.staffs.index') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.staffs.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Staffs</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="my-4">
                        <div class="h-px bg-[#4a4a4a]"></div>
                    </li>
                @endif

                @if (Auth::user()->hasPermission('landing_page'))
                    <li class="px-4 py-2">
                        <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">Landing Page</p>
                    </li>
                    <li>
                        <div class="landing-page-menu">
                            <button type="button"
                                class="admin-menu-item {{ request()->routeIs('admin.landing.*') ? 'active' : '' }} w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all"
                                id="landingPageMenuBtn">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <span class="text-sm uppercase tracking-wider">Landing Page</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 landing-page-arrow transition-transform" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <ul class="landing-page-submenu hidden pl-4 mt-1 space-y-1" id="landingPageSubmenu">
                                <li>
                                    <a href="{{ route('admin.landing.header') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.header') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Header</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.main') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.main') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Main Section</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.about') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.about') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>About Section</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.brands') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.brands') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Brands Section</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.products') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.products') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Products Section</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.accessories') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.accessories') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Accessories Section</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.merchandise') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.merchandise') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Merchandise Section</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.gallery') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.gallery') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Gallery Section</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.service') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.service') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Service Section</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.landing.contact') }}"
                                        class="admin-menu-item {{ request()->routeIs('admin.landing.contact') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#6a6a6a]"></span>
                                        <span>Contact Section</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="my-4">
                        <div class="h-px bg-[#4a4a4a]"></div>
                    </li>
                @endif

                <li class="px-4 py-2">
                    <p class="text-xs text-[#6a6a6a] uppercase tracking-wider font-semibold">Settings & Utilities</p>
                </li>
                @if (Auth::user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.get-location') }}"
                            class="admin-menu-item {{ request()->routeIs('admin.get-location') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg text-[#9a9a9a] hover:text-white hover:bg-[#2a2a2a] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span class="text-sm uppercase tracking-wider">Get Location</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        <div class="p-4 border-t border-[#4a4a4a]">
            <button type="button" onclick="openProfileModal()"
                class="w-full flex items-center gap-3 mb-3 hover:bg-[#2a2a2a] p-2 rounded-lg transition-all text-left">
                <div
                    class="w-10 h-10 rounded-full bg-[#2a2a2a] border border-[#4a4a4a] flex items-center justify-center overflow-hidden">
                    @if (Auth::user()->staff && Auth::user()->staff->photo_profile)
                        <img src="{{ asset(Auth::user()->staff->photo_profile) }}"
                            class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[#9a9a9a] text-[10px] truncate">Klik untuk Edit Profil</p>
                </div>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full bg-transparent border border-[#4a4a4a] text-white px-4 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all text-sm uppercase tracking-wider flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>

<div id="profileModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-70" onclick="closeProfileModal()"></div>
        <div
            class="relative bg-[#1a1a1a] border border-[#4a4a4a] w-full max-w-md rounded-xl shadow-2xl overflow-hidden">
            <div class="p-6">
                <h3 class="text-white text-xl font-bold mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Data Pribadi & Rekening
                </h3>
                <form id="profileUpdateForm" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div class="flex justify-center mb-4">
                            <div class="relative group">
                                <img id="previewAvatar"
                                    src="{{ Auth::user()->staff && Auth::user()->staff->photo_profile ? asset(Auth::user()->staff->photo_profile) : 'https://ui-avatars.com/api/?name=' . Auth::user()->name }}"
                                    class="w-20 h-20 rounded-full border-2 border-yellow-500 object-cover">
                                <label
                                    class="absolute bottom-0 right-0 bg-yellow-500 p-1 rounded-full cursor-pointer hover:bg-yellow-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" name="photo_profile" class="hidden"
                                        onchange="previewImg(this)">
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] text-[#6a6a6a] uppercase">Nama Lengkap</label>
                            <input type="text" name="nama"
                                value="{{ Auth::user()->staff->nama ?? Auth::user()->name }}"
                                class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm focus:border-yellow-500 outline-none">
                        </div>
                        <div class="bg-[#0f0f0f] p-3 rounded-lg border border-[#333] mb-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[9px] text-[#6a6a6a] uppercase block">ID Karyawan</label>
                                    <span
                                        class="text-white text-sm font-mono">{{ Auth::user()->staff->id_employee ?? '-' }}</span>
                                </div>
                                <div>
                                    <label class="text-[9px] text-[#6a6a6a] uppercase block">Jabatan</label>
                                    <span
                                        class="text-yellow-500 text-sm font-bold">{{ Auth::user()->staff->jabatan ?? 'Staff' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                        </div>

                        <hr class="border-[#333] my-4">

                        <div class="space-y-3">
                            <label class="text-[10px] text-yellow-500 uppercase font-bold">Ganti Password</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="password" name="new_password" placeholder="Password Baru"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm focus:border-yellow-500 outline-none">
                                <input type="password" name="new_password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm focus:border-yellow-500 outline-none">
                            </div>
                            <p class="text-[9px] text-[#6a6a6a]">*Kosongkan jika tidak ingin mengganti password</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] text-[#6a6a6a] uppercase">Nama Bank (BCA/Mandiri)</label>
                                <input type="text" name="bank_name"
                                    value="{{ Auth::user()->staff->bank_name ?? '' }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm focus:border-yellow-500 outline-none"
                                    placeholder="Contoh: BCA">
                            </div>
                            <div>
                                <label class="text-[10px] text-[#6a6a6a] uppercase">Nomor Rekening</label>
                                <input type="text" name="bank_account"
                                    value="{{ Auth::user()->staff->bank_account ?? '' }}"
                                    class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm focus:border-yellow-500 outline-none"
                                    placeholder="12345678">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] text-[#6a6a6a] uppercase">Atas Nama Rekening</label>
                            <input type="text" name="account_name"
                                value="{{ Auth::user()->staff->account_name ?? '' }}"
                                class="w-full bg-[#0f0f0f] border border-[#333] rounded-lg px-4 py-2 text-white text-sm focus:border-yellow-500 outline-none"
                                placeholder="Nama di buku tabungan">
                        </div>
                    </div>
                    <div class="mt-6 flex gap-2">
                        <button type="button" onclick="updateProfile()"
                            class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-black font-bold py-2 rounded-lg text-sm transition-all uppercase">Simpan
                            Perubahan</button>
                        <button type="button" onclick="closeProfileModal()"
                            class="px-4 py-2 text-[#9a9a9a] text-sm hover:text-white">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

<style>
    .admin-sidebar {
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
        max-width: 16rem;
    }

    .admin-sidebar .logo-section {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    @media (min-width: 640px) {
        .admin-sidebar .logo-section {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .admin-sidebar .logo-section {
            padding-left: 2rem;
            padding-right: 2rem;
        }
    }

    .admin-menu-item.active {
        background-color: #2a2a2a;
        color: white;
        border-left: 3px solid #6a6a6a;
    }

    .landing-page-submenu,
    .users-management-submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-in-out;
    }

    .landing-page-submenu.open,
    .users-management-submenu.open {
        max-height: 500px;
    }

    .landing-page-arrow.rotate,
    .users-management-arrow.rotate {
        transform: rotate(180deg);
    }

    @media (max-width: 1023px) {
        .admin-sidebar {
            transform: translateX(-100%);
        }

        .admin-sidebar.open {
            transform: translateX(0);
        }
    }

    @media (min-width: 1024px) {
        .admin-sidebar {
            transform: translateX(0);
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-popup {
        background-color: #1a1a1a !important;
        border: 1px solid #4a4a4a !important;
    }

    .swal2-title {
        color: #ffffff !important;
    }

    .swal2-content {
        color: #d4d4d4 !important;
    }

    .swal2-confirm {
        background-color: #3b82f6 !important;
    }

    .swal2-confirm:hover {
        background-color: #2563eb !important;
    }

    .swal2-cancel {
        background-color: #4a4a4a !important;
        color: #ffffff !important;
    }

    .swal2-cancel:hover {
        background-color: #6a6a6a !important;
    }
</style>

<script>
    function openProfileModal() {
        document.getElementById('profileModal').classList.remove('hidden');
    }

    function closeProfileModal() {
        document.getElementById('profileModal').classList.add('hidden');
    }

    function previewImg(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = (e) => document.getElementById('previewAvatar').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    async function updateProfile() {
        const form = document.getElementById('profileUpdateForm');
        const formData = new FormData(form);
        const saveBtn = form.querySelector('button[onclick="updateProfile()"]');

        // Loading state
        saveBtn.disabled = true;
        saveBtn.textContent = 'Menyimpan...';

        try {
            const response = await fetch('{{ route('staff.update-my-profile') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Profil & Rekening diperbarui!',
                    background: '#1a1a1a',
                    color: '#fff'
                }).then(() => {
                    // OPSI 1: Refresh halaman agar semua komponen PHP terupdate (Paling Aman)
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Gagal update', 'error');
            }
        } catch (e) {
            console.error(e);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Simpan Perubahan';
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuConfig = {
            'service-harian': {
                badge: document.getElementById('service-harian-badge'),
                link: document.getElementById('service-harian-menu-link'),
                isActive: window.location.pathname.includes('/admin/service-harian') ||
                    window.location.pathname.includes('/staff/service-harian')
            },
            'products': {
                badge: document.getElementById('pending-products-badge'),
                link: document.getElementById('products-menu-link'),
                route: '{{ route('admin.products.index') }}',
                isActive: window.location.pathname.includes('/admin/products')
            },
            'daily-sales': {
                badge: document.getElementById('daily-sales-badge'),
                link: document.getElementById('daily-sales-menu-link'),
                route: '{{ route('admin.daily-sales.index') }}',
                isActive: window.location.pathname.includes('/admin/daily-sales') || window.location
                    .pathname.includes('/staff/daily-sales')
            },
            'sales-instruments': {
                badge: document.getElementById('sales-instruments-badge'),
                link: document.getElementById('sales-instruments-menu-link'),
                route: '{{ route('admin.sales.index') }}',
                isActive: window.location.pathname.includes('/admin/sales') || window.location.pathname
                    .includes('/staff/sales')
            },
            'leaves': {
                badge: document.getElementById('leaves-badge'),
                link: document.getElementById('leaves-menu-link'),
                route: '{{ route('admin.leaves.index') }}',
                isActive: window.location.pathname.includes('/admin/leaves')
            },
            'members': {
                badge: document.getElementById('members-badge'),
                link: document.getElementById('members-menu-link'),
                route: '{{ route('admin.members.index') }}',
                isActive: window.location.pathname.includes('/admin/members')
            },
            'assignments': {
                badge: document.getElementById('assignment-badge'),
                link: null,
                route: null,
                isActive: window.location.pathname.includes('/staff/assignments')
            }
        };

        Object.keys(menuConfig).forEach(menuKey => {
            const config = menuConfig[menuKey];
            if (config.link && !config.isActive) {
                config.link.addEventListener('click', function() {
                    markMenuAsViewed(menuKey);
                });
            }
        });

        Object.keys(menuConfig).forEach(menuKey => {
            const config = menuConfig[menuKey];
            if (config.isActive) {
                markMenuAsViewed(menuKey);
            }
        });

        function markMenuAsViewed(menuKey) {
            fetch('{{ route('notifications.mark-viewed') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    menu: menuKey
                }),
                credentials: 'same-origin'
            }).then(() => {
                const config = menuConfig[menuKey];
                if (config.badge) {
                    config.badge.classList.add('hidden');
                    config.badge.classList.remove('flex');
                    config.badge.classList.remove('animate-pulse');
                }
            }).catch(error => console.error('Error marking menu as viewed:', error));
        }

        // Polling notifikasi realtime dinonaktifkan. Sebelumnya komponen sidebar
        // memanggil endpoint notifications.all setiap 30 detik pada setiap halaman.
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebar-toggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        overlay.addEventListener('click', closeSidebar);

        document.addEventListener('click', function(e) {
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(e.target) && !e.target.closest('#sidebar-toggle')) {
                    closeSidebar();
                }
            }
        });

        // Landing Page Menu Dropdown
        const landingPageMenuBtn = document.getElementById('landingPageMenuBtn');
        const landingPageSubmenu = document.getElementById('landingPageSubmenu');
        const landingPageArrow = landingPageMenuBtn?.querySelector('.landing-page-arrow');

        if (landingPageMenuBtn && landingPageSubmenu) {
            const isActive = landingPageSubmenu.querySelector('.admin-menu-item.active');
            if (isActive) {
                landingPageSubmenu.classList.remove('hidden');
                landingPageSubmenu.classList.add('open');
                if (landingPageArrow) landingPageArrow.classList.add('rotate');
            }

            landingPageMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const isOpen = landingPageSubmenu.classList.contains('open');
                if (isOpen) {
                    landingPageSubmenu.classList.remove('open');
                    landingPageSubmenu.classList.add('hidden');
                    if (landingPageArrow) landingPageArrow.classList.remove('rotate');
                } else {
                    landingPageSubmenu.classList.remove('hidden');
                    landingPageSubmenu.classList.add('open');
                    if (landingPageArrow) landingPageArrow.classList.add('rotate');
                }
            });
        }

        // Users Management Menu Dropdown
        const usersManagementMenuBtn = document.getElementById('usersManagementMenuBtn');
        const usersManagementSubmenu = document.getElementById('usersManagementSubmenu');
        const usersManagementArrow = usersManagementMenuBtn?.querySelector('.users-management-arrow');

        if (usersManagementMenuBtn && usersManagementSubmenu) {
            const isActive = usersManagementSubmenu.querySelector('.admin-menu-item.active');
            if (isActive) {
                usersManagementSubmenu.classList.remove('hidden');
                usersManagementSubmenu.classList.add('open');
                if (usersManagementArrow) usersManagementArrow.classList.add('rotate');
            }

            usersManagementMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const isOpen = usersManagementSubmenu.classList.contains('open');
                if (isOpen) {
                    usersManagementSubmenu.classList.remove('open');
                    usersManagementSubmenu.classList.add('hidden');
                    if (usersManagementArrow) usersManagementArrow.classList.remove('rotate');
                } else {
                    usersManagementSubmenu.classList.remove('hidden');
                    usersManagementSubmenu.classList.add('open');
                    if (usersManagementArrow) usersManagementArrow.classList.add('rotate');
                }
            });
        }

        // Real-time assignment notification (for staff only)
        @if (Auth::user()->role === 'staff')
            const assignmentBadge = document.getElementById('assignment-badge');
            if (assignmentBadge) {
                function updateAssignmentCount() {
                    fetch('{{ route('staff.assignments.unreadCount') }}', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.count > 0) {
                                assignmentBadge.textContent = data.count;
                                assignmentBadge.classList.remove('hidden');
                            } else {
                                assignmentBadge.classList.add('hidden');
                            }
                        })
                        .catch(error => console.error('Error fetching assignment count:', error));
                }

                // Polling jumlah tugas realtime dinonaktifkan untuk mencegah
                // request API berulang pada setiap halaman staff.
            }
        @endif
    });
</script>
