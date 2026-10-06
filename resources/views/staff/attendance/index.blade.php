<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Attendance - Musicmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Attendance</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('staff.attendance.history') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        View History
                    </a>
                </div>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Attendance Card -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8 mb-6">
                <div class="text-center">
                    <h2 class="text-2xl font-semibold mb-4">{{ now()->format('l, d F Y') }}</h2>

                    <!-- Token Display -->
                    @if(!$todayAttendance || !$todayAttendance->check_in_time)
                    <div class="bg-blue-900/20 border border-blue-500/50 rounded-lg p-4 mb-6">
                        <div class="bg-[#0f0f0f] border border-blue-500/30 rounded-lg p-4 text-center">
                            <p class="text-[#9a9a9a] text-xs mb-2">Token Absensi (Berubah setiap 1 menit):</p>
                            <p id="currentToken" class="text-white text-3xl font-bold font-mono tracking-wider mb-2">{{ $currentToken }}</p>
                            <p class="text-[#6a6a6a] text-xs">
                                Token akan berubah dalam: <span id="countdown" class="text-blue-400 font-bold">60</span> detik
                            </p>
                        </div>
                    </div>
                    
                    <!-- Location Status -->
                    <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#9a9a9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <p class="text-[#9a9a9a] text-sm">Status Lokasi</p>
                        </div>
                        <p id="locationStatus" class="text-[#6a6a6a] text-xs">Klik tombol Check In untuk mendapatkan lokasi Anda</p>
                    </div>
                    @endif

                    <!-- Check In Section -->
                    <div class="mb-6">
                        @if($todayAttendance && $todayAttendance->check_in_time)
                            <div class="bg-green-900/20 border border-green-500/50 rounded-lg p-4 mb-4">
                                <p class="text-green-400 text-sm mb-2">Checked In</p>
                                <p class="text-white text-xl font-semibold">{{ $todayAttendance->check_in_time }}</p>
                                @if($todayAttendance->check_in_address)
                                    <p class="text-[#9a9a9a] text-xs mt-2">{{ Str::limit($todayAttendance->check_in_address, 50) }}</p>
                                @endif
                                @if($todayAttendance->check_in_latitude && $todayAttendance->check_in_longitude)
                                    <a href="https://www.google.com/maps?q={{ $todayAttendance->check_in_latitude }},{{ $todayAttendance->check_in_longitude }}" 
                                       target="_blank" 
                                       class="text-blue-400 hover:text-blue-300 text-xs mt-1 inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        Lihat di Peta
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="mb-4">
                                <label for="tokenInput" class="block text-sm text-[#9a9a9a] mb-2">
                                    Masukkan Token Absensi:
                                </label>
                                <input type="text" 
                                       id="tokenInput" 
                                       maxlength="6" 
                                       placeholder="Masukkan 6 digit token"
                                       class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white text-center text-2xl font-bold font-mono tracking-widest uppercase focus:outline-none focus:border-[#6a6a6a] transition-all"
                                       style="letter-spacing: 0.5em;">
                                <p class="text-xs text-[#6a6a6a] mt-2 text-center">Token saat ini: <span id="tokenDisplay" class="font-mono font-bold">{{ $currentToken }}</span></p>
                            </div>
                            <button id="checkInBtn" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed w-full">
                                Check In
                            </button>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Recent Attendances -->
            @if($recentAttendances->count() > 0)
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <h3 class="text-xl font-semibold mb-4">Recent Attendances</h3>
                <div class="space-y-3">
                    @foreach($recentAttendances as $attendance)
                        <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                                <div>
                                    <p class="text-white font-medium">{{ $attendance->attendance_date->format('d M Y') }}</p>
                                    <div class="flex gap-4 mt-2 text-sm">
                                        @if($attendance->check_in_time)
                                            <span class="text-green-400">Check In: {{ substr($attendance->check_in_time, 0, 5) }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($attendance->check_in_address)
                                    <p class="text-[#6a6a6a] text-xs">{{ Str::limit($attendance->check_in_address, 50) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        async function readJsonResponse(response) {
            const text = await response.text();
            const contentType = response.headers.get('content-type') || '';

            if (!text.trim()) {
                throw new Error('Server tidak mengirim response. Silakan coba lagi atau hubungi admin.');
            }

            if (!contentType.includes('application/json')) {
                if (response.redirected || text.includes('<form') || text.includes('login')) {
                    throw new Error('Sesi login habis. Silakan login ulang.');
                }

                throw new Error('Server mengirim response yang tidak valid. Silakan coba lagi atau hubungi admin.');
            }

            try {
                return JSON.parse(text);
            } catch (error) {
                throw new Error('Response server tidak bisa dibaca. Silakan coba lagi atau hubungi admin.');
            }
        }

        // Auto uppercase token input
        const tokenInput = document.getElementById('tokenInput');
        if (tokenInput) {
            tokenInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
        }

        // Token diperbarui saat countdown habis. Jangan memakai timer kedua,
        // karena timer terpisah sebelumnya mengirim dua request pada waktu yang sama.
        let countdownInterval = null;
        let currentCountdown = {{ $nextTokenTime }};

        function updateToken() {
            fetch('{{ route("staff.attendance.getToken") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => readJsonResponse(response))
            .then(data => {
                if (data.success === false) {
                    throw new Error(data.error || 'Token absensi gagal diperbarui.');
                }

                const currentTokenEl = document.getElementById('currentToken');
                const tokenDisplayEl = document.getElementById('tokenDisplay');
                const countdownEl = document.getElementById('countdown');
                
                if (currentTokenEl) {
                    currentTokenEl.textContent = data.token;
                }
                if (tokenDisplayEl) {
                    tokenDisplayEl.textContent = data.token;
                }
                if (countdownEl) {
                    currentCountdown = data.nextTokenTime;
                    countdownEl.textContent = currentCountdown;
                }
            })
            .catch(error => {
                console.error('Error fetching token:', error);
            });
        }

        function startCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            
            countdownInterval = setInterval(function() {
                const countdownEl = document.getElementById('countdown');
                if (countdownEl) {
                    currentCountdown--;
                    if (currentCountdown <= 0) {
                        currentCountdown = 60;
                        if (document.visibilityState === 'visible') {
                            updateToken(); // Refresh hanya saat halaman sedang terlihat
                        }
                        startCountdown(); // Restart countdown
                    }
                    countdownEl.textContent = currentCountdown;
                }
            }, 1000);
        }

        // Start countdown on page load
        startCountdown();

        // Initial token update
        updateToken();

        // Jika pengguna kembali ke tab setelah lama ditinggalkan, ambil token
        // terbaru sekali saja. Tidak ada polling tambahan di background.
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                updateToken();
            }
        });

        // Get current location
        let currentLatitude = null;
        let currentLongitude = null;
        let currentAddress = null;

        function getLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('Geolocation tidak didukung oleh browser Anda.'));
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        currentLatitude = position.coords.latitude;
                        currentLongitude = position.coords.longitude;
                        
                        currentAddress = `${currentLatitude}, ${currentLongitude}`;
                        resolve({
                            latitude: currentLatitude,
                            longitude: currentLongitude,
                            address: currentAddress
                        });
                    },
                    (error) => {
                        console.error('Error getting location:', error);
                        let errorMessage = 'Tidak dapat mendapatkan lokasi.';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Akses lokasi ditolak. Silakan izinkan akses lokasi di pengaturan browser.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Informasi lokasi tidak tersedia.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Waktu permintaan lokasi habis.';
                                break;
                        }
                        reject(new Error(errorMessage));
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        }

        // Check In
        document.getElementById('checkInBtn')?.addEventListener('click', async function() {
            const token = tokenInput ? tokenInput.value.trim() : '';
            if (!token || token.length !== 6) {
                alert('Mohon masukkan token absensi yang valid (6 karakter)');
                tokenInput?.focus();
                return;
            }

            this.disabled = true;
            this.textContent = 'Mendapatkan lokasi...';

            let locationData = {
                latitude: null,
                longitude: null,
                address: null
            };

            // Try to get location
            try {
                const locationStatusEl = document.getElementById('locationStatus');
                if (locationStatusEl) {
                    locationStatusEl.textContent = 'Mendapatkan lokasi...';
                    locationStatusEl.className = 'text-yellow-400 text-xs';
                }
                
                locationData = await getLocation();
                
                if (locationStatusEl) {
                    locationStatusEl.textContent = `Lokasi: ${locationData.address || 'Tersedia'}`;
                    locationStatusEl.className = 'text-green-400 text-xs';
                }
                
                this.textContent = 'Memproses...';
            } catch (error) {
                console.error('Location error:', error);
                const locationStatusEl = document.getElementById('locationStatus');
                if (locationStatusEl) {
                    locationStatusEl.textContent = `Error: ${error.message}`;
                    locationStatusEl.className = 'text-red-400 text-xs';
                }
                
                // Continue with check-in even if location fails
                if (confirm('Tidak dapat mendapatkan lokasi. Apakah Anda ingin melanjutkan check-in tanpa lokasi?')) {
                    this.textContent = 'Memproses...';
                } else {
                    this.disabled = false;
                    this.textContent = 'Check In';
                    if (locationStatusEl) {
                        locationStatusEl.textContent = 'Check-in dibatalkan';
                        locationStatusEl.className = 'text-[#6a6a6a] text-xs';
                    }
                    return;
                }
            }

            fetch('{{ route("staff.attendance.checkIn") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    token: token,
                    latitude: locationData.latitude,
                    longitude: locationData.longitude,
                    address: locationData.address
                }),
                credentials: 'same-origin'
            })
            .then(async response => {
                const data = await readJsonResponse(response);
                
                if (!response.ok) {
                    // Error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Check-in Gagal',
                        text: data.error || 'Terjadi kesalahan saat check-in. Silakan coba lagi.',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Oke'
                    });
                    this.disabled = false;
                    this.textContent = 'Check In';
                    return;
                }
                
                if (data.success) {
                    // Success response
                    let message = 'Waktu check-in: ' + data.check_in_time;
                    if (data.point_change !== undefined) {
                        if (data.point_change > 0) {
                            message += '\nAnda mendapatkan +' + data.point_change + ' point.';
                        } else if (data.point_change < 0) {
                            message += '\nAnda terlambat dan mendapatkan ' + data.point_change + ' point.';
                        }
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Check-in Berhasil!',
                        text: message,
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: 'Oke'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Check-in Gagal',
                        text: data.error || 'Terjadi kesalahan saat check-in.',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Oke'
                    });
                    this.disabled = false;
                    this.textContent = 'Check In';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: error.message || 'Silakan coba lagi.',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Oke'
                });
                this.disabled = false;
                this.textContent = 'Check In';
            });
        });


    </script>
    @php
        use Illuminate\Support\Str;
    @endphp
</body>
</html>
