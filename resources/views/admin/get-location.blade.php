<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Get Current Location - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-[#0f0f0f] text-white flex flex-col min-h-screen">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-12 max-w-4xl mx-auto">
            <div class="mb-8">
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Get Current Location</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <!-- Current Office Location -->
                <div class="bg-blue-900/20 border border-blue-500/50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold mb-3 text-blue-400">Lokasi Toko Saat Ini</h3>
                    <div id="currentLocation" class="space-y-2 text-sm">
                        <p class="text-[#9a9a9a]">Memuat lokasi...</p>
                    </div>
                </div>

                <p class="text-[#9a9a9a] mb-6">
                    Klik tombol di bawah untuk mendapatkan koordinat GPS lokasi Anda saat ini. 
                    Koordinat ini akan digunakan untuk setting lokasi toko di sistem absensi.
                </p>

                <button id="getLocationBtn" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all mb-6">
                    Get My Current Location
                </button>

                <div id="locationResult" class="hidden">
                    <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-6 mb-4">
                        <h3 class="text-lg font-semibold mb-4">Koordinat GPS Anda:</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-[#9a9a9a]">Latitude:</label>
                                <input type="text" id="latitude" readonly 
                                       class="w-full bg-[#2a2a2a] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white font-mono mt-1">
                            </div>
                            <div>
                                <label class="text-sm text-[#9a9a9a]">Longitude:</label>
                                <input type="text" id="longitude" readonly 
                                       class="w-full bg-[#2a2a2a] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white font-mono mt-1">
                            </div>
                            <div>
                                <label class="text-sm text-[#9a9a9a]">Address:</label>
                                <textarea id="address" readonly rows="3"
                                          class="w-full bg-[#2a2a2a] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white mt-1"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-900/20 border border-blue-500/50 rounded-lg p-4 mb-4">
                        <p class="text-blue-400 text-sm mb-3">
                            <strong>Update Lokasi Toko:</strong> Klik tombol di bawah untuk langsung mengupdate lokasi toko dengan koordinat GPS Anda saat ini.
                        </p>
                        <div class="mb-3">
                            <label class="text-sm text-[#9a9a9a]">Radius yang Diizinkan (meter):</label>
                            <input type="number" id="radius" value="500" min="10" max="10000" 
                                   class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white mt-1">
                            <p class="text-xs text-[#6a6a6a] mt-1">Jarak maksimum dari lokasi toko untuk absensi (default: 500 meter)</p>
                        </div>
                        <button id="updateLocationBtn" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all w-full">
                            Update Lokasi Toko dengan Koordinat Ini
                        </button>
                    </div>

                    <div class="bg-yellow-900/20 border border-yellow-500/50 rounded-lg p-4 mb-4">
                        <p class="text-yellow-400 text-sm">
                            <strong>Atau:</strong> Salin koordinat di atas jika ingin menyimpannya untuk referensi.
                        </p>
                    </div>

                    <button id="copyBtn" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-all">
                        Copy Coordinates
                    </button>
                </div>

                <div id="errorMessage" class="hidden mt-4 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-red-400 text-sm"></p>
                </div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        // Load current office location on page load
        function loadCurrentLocation() {
            fetch('{{ route("admin.office.getLocation") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                const currentLocationDiv = document.getElementById('currentLocation');
                currentLocationDiv.innerHTML = `
                    <div class="space-y-2">
                        <p><span class="text-[#9a9a9a]">Latitude:</span> <span class="text-white font-mono">${data.latitude}</span></p>
                        <p><span class="text-[#9a9a9a]">Longitude:</span> <span class="text-white font-mono">${data.longitude}</span></p>
                        <p><span class="text-[#9a9a9a]">Radius:</span> <span class="text-white font-mono">${data.radius} meter</span></p>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error loading current location:', error);
            });
        }

        // Load current location when page loads
        loadCurrentLocation();

        document.getElementById('getLocationBtn').addEventListener('click', function() {
            const btn = this;
            const resultDiv = document.getElementById('locationResult');
            const errorDiv = document.getElementById('errorMessage');
            
            btn.disabled = true;
            btn.textContent = 'Getting location...';
            resultDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');

            if (!navigator.geolocation) {
                showError('Geolocation is not supported by your browser.');
                btn.disabled = false;
                btn.textContent = 'Get My Current Location';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;
                    
                    document.getElementById('address').value = `${lat}, ${lon}`;
                    resultDiv.classList.remove('hidden');

                    btn.disabled = false;
                    btn.textContent = 'Get My Current Location';
                },
                function(error) {
                    let errorMsg = 'Error getting location: ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg += 'Permission denied. Please allow location access.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg += 'Location information unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMsg += 'Location request timeout.';
                            break;
                        default:
                            errorMsg += 'Unknown error.';
                            break;
                    }
                    showError(errorMsg);
                    btn.disabled = false;
                    btn.textContent = 'Get My Current Location';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });

        document.getElementById('copyBtn').addEventListener('click', function() {
            const lat = document.getElementById('latitude').value;
            const lon = document.getElementById('longitude').value;
            const text = `Latitude: ${lat}\nLongitude: ${lon}`;
            
            navigator.clipboard.writeText(text).then(function() {
                const btn = this;
                const originalText = btn.textContent;
                btn.textContent = 'Copied!';
                btn.classList.add('bg-green-700');
                setTimeout(function() {
                    btn.textContent = originalText;
                    btn.classList.remove('bg-green-700');
                }, 2000);
            }.bind(this));
        });

        document.getElementById('updateLocationBtn').addEventListener('click', function() {
            const btn = this;
            const lat = document.getElementById('latitude').value;
            const lon = document.getElementById('longitude').value;
            const radius = document.getElementById('radius').value;
            const errorDiv = document.getElementById('errorMessage');
            const resultDiv = document.getElementById('locationResult');

            if (!lat || !lon) {
                showError('Please get your location first by clicking "Get My Current Location" button.');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Updating...';
            errorDiv.classList.add('hidden');

            fetch('{{ route("admin.office.updateLocation") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    latitude: parseFloat(lat),
                    longitude: parseFloat(lon),
                    radius: parseInt(radius)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Lokasi toko berhasil diupdate! Sekarang Anda bisa melakukan absensi dari lokasi ini.');
                    loadCurrentLocation(); // Reload current location display
                    btn.disabled = false;
                    btn.textContent = 'Update Lokasi Toko dengan Koordinat Ini';
                } else {
                    showError(data.message || 'Error updating location.');
                    btn.disabled = false;
                    btn.textContent = 'Update Lokasi Toko dengan Koordinat Ini';
                }
            })
            .catch(error => {
                showError('Error updating location: ' + error.message);
                btn.disabled = false;
                btn.textContent = 'Update Lokasi Toko dengan Koordinat Ini';
            });
        });

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.querySelector('p').textContent = message;
            errorDiv.classList.remove('hidden');
            const successDiv = document.getElementById('successMessage');
            if (successDiv) {
                successDiv.classList.add('hidden');
            }
        }

        function showSuccess(message) {
            let successDiv = document.getElementById('successMessage');
            if (!successDiv) {
                successDiv = document.createElement('div');
                successDiv.id = 'successMessage';
                successDiv.className = 'mt-4 p-4 bg-green-900/20 border border-green-500/50 rounded-lg';
                successDiv.innerHTML = '<p class="text-green-400 text-sm"></p>';
                document.querySelector('.bg-[#1a1a1a]').appendChild(successDiv);
            }
            successDiv.querySelector('p').textContent = message;
            successDiv.classList.remove('hidden');
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.classList.add('hidden');
        }
    </script>
</body>
</html>
