<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Rewards - Musicmen Staff</title>
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
            <!-- Page Header -->
            <div class="mb-8 w-full">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Rewards</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('staff.rewards.create') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5l0 14" /><path d="M5 12l14 0" />
                        </svg>
                        Add Reward
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Rewards Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <div class="mb-4">
                    <form method="GET" action="{{ route('staff.rewards.index') }}" class="flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="flex-1 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                               placeholder="Search by Member ID, Name, or Reward Name...">
                        <button type="submit" 
                                class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('staff.rewards.index') }}" 
                               class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Member</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Milestone</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Nama Hadiah</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Gambar</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Kata-kata</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rewards as $reward)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4 text-white text-sm">
                                        <div>
                                            <div class="font-semibold">{{ $reward->member->member_id }}</div>
                                            <div class="text-[#9a9a9a] text-xs">{{ $reward->member->name }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-900/20 border border-yellow-700 text-yellow-400">
                                            {{ $reward->milestone }} Kunjungan
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $reward->nama_hadiah }}</td>
                                    <td class="py-3 px-4">
                                        @if($reward->gambar_hadiah)
                                            <img src="{{ asset($reward->gambar_hadiah) }}" alt="{{ $reward->nama_hadiah }}" 
                                                 class="w-16 h-16 object-cover rounded-lg border border-[#4a4a4a]">
                                        @else
                                            <span class="text-[#6a6a6a] text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">
                                        <div class="max-w-xs truncate" title="{{ $reward->kata_kata }}">
                                            {{ $reward->kata_kata ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('staff.rewards.edit', $reward->id) }}" 
                                               class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Edit Reward">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-[#9a9a9a]">
                                        Tidak ada hadiah yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($rewards, 'hasPages') && $rewards->hasPages())
                <div class="mt-6 flex items-center justify-center">
                    {{ $rewards->links() }}
                </div>
                @endif
            </div>

            <!-- Pending Claims -->
            @if(isset($pendingClaims) && $pendingClaims->count() > 0)
            <div class="mt-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <h3 class="text-lg text-white mb-4 uppercase tracking-wider">Pending Reward Claims</h3>
                <div class="space-y-3">
                    @foreach($pendingClaims as $claim)
                        <div class="flex flex-col md:flex-row md:items-center justify-between bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 gap-3">
                            <div>
                                <p class="text-white text-sm font-semibold">{{ $claim->member->member_id ?? '' }} - {{ $claim->member->name ?? '' }}</p>
                                <p class="text-xs text-[#9a9a9a]">Hadiah: {{ $claim->reward->nama_hadiah ?? '-' }} | Milestone: {{ $claim->reward->milestone ?? '-' }}</p>
                                <p class="text-xs text-[#6a6a6a]">Diajukan: {{ $claim->requested_at ? \Carbon\Carbon::parse($claim->requested_at)->format('d M Y H:i') : '-' }}</p>
                            </div>
                            <form method="POST" action="{{ route('staff.rewards.claims.fulfill', $claim->id) }}">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    Tandai Sudah Claim
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Claims -->
            @if(isset($recentClaims) && $recentClaims->count() > 0)
            <div class="mt-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <h3 class="text-lg text-white mb-4 uppercase tracking-wider">Riwayat Claim Terbaru</h3>
                <div class="space-y-3">
                    @foreach($recentClaims as $claim)
                        <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3">
                            <p class="text-white text-sm font-semibold">{{ $claim->member->member_id ?? '' }} - {{ $claim->member->name ?? '' }}</p>
                            <p class="text-xs text-[#9a9a9a]">Hadiah: {{ $claim->reward->nama_hadiah ?? '-' }} | Milestone: {{ $claim->reward->milestone ?? '-' }}</p>
                            <p class="text-xs text-[#6a6a6a]">
                                Status: 
                                @if($claim->status === 'fulfilled')
                                    <span class="text-green-400">Sudah di-claim</span>
                                    @if($claim->fulfilled_at) ({{ \Carbon\Carbon::parse($claim->fulfilled_at)->format('d M Y H:i') }}) @endif
                                @else
                                    <span class="text-yellow-400">Menunggu</span>
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>

