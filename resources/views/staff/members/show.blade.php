<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Detail - Musicmen Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Member Detail</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('staff.members.index') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Back
                    </a>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-[#9a9a9a] mb-1">Member ID</p>
                        <p class="text-white text-lg">{{ $member->member_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#9a9a9a] mb-1">Name</p>
                        <p class="text-white text-lg">{{ $member->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#9a9a9a] mb-1">Phone</p>
                        <div class="flex items-center gap-2">
                            <p class="text-white text-lg">{{ $member->phone ?? '-' }}</p>
                            @if($member->phone)
                            <button onclick="sendWhatsApp('{{ $member->phone }}', '{{ addslashes($member->name) }}')" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs uppercase transition-all flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                WhatsApp
                            </button>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-[#9a9a9a] mb-1">Email</p>
                        <p class="text-white text-lg">{{ $member->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#9a9a9a] mb-1">Total Visits</p>
                        <p class="text-white text-lg">{{ $member->visits_count }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#9a9a9a] mb-1">Status</p>
                        @php
                            $rank = $member->rank ?? 'bronze';
                            $status = $member->status ?? 'Bronze';
                            
                            $rankColors = [
                                'bronze' => 'text-amber-600',
                                'silver' => 'text-gray-300',
                                'gold' => 'text-yellow-400',
                                'diamond' => 'text-cyan-300',
                                'ruby' => 'text-red-400'
                            ];
                            
                            $rankBgColors = [
                                'bronze' => 'bg-amber-900/20 border-amber-700',
                                'silver' => 'bg-gray-800/20 border-gray-600',
                                'gold' => 'bg-yellow-900/20 border-yellow-700',
                                'diamond' => 'bg-cyan-900/20 border-cyan-700',
                                'ruby' => 'bg-red-900/20 border-red-700'
                            ];
                            
                            $color = $rankColors[$rank] ?? 'text-gray-400';
                            $bgColor = $rankBgColors[$rank] ?? 'bg-gray-800/20 border-gray-600';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $bgColor }} {{ $color }}">
                            {{ $status }}
                        </span>
                    </div>
                    @if($member->address)
                    <div class="md:col-span-2">
                        <p class="text-sm text-[#9a9a9a] mb-1">Address</p>
                        <p class="text-white">{{ $member->address }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        function formatPhoneForWhatsApp(phone) {
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            const formattedPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.substring(1) : cleanPhone;
            return formattedPhone.startsWith('62') ? formattedPhone : '62' + formattedPhone;
        }

        function sendWhatsApp(phone, name) {
            if (!phone) {
                alert('Nomor WhatsApp tidak tersedia');
                return;
            }

            const formattedPhone = formatPhoneForWhatsApp(phone);
            const message = `Halo ${name} 👋\n\nTerima kasih telah menjadi member Musicmen!`;
            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodedMessage}`;
            window.open(whatsappUrl, '_blank');
        }
    </script>
</body>
</html>

