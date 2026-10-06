<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Member Profile - Musicmen Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-[#0f0f0f] text-white min-h-screen">
    @include('components.navbar')

    <main class="container mx-auto max-w-4xl px-6 lg:px-8 py-12 mt-20">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <div>
                    <h1 class="serif-font text-4xl md:text-5xl text-white mb-2">Member Profile</h1>
                    <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                </div>
                <a href="{{ route('member.profile.edit') }}"
                    class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                    Edit Profile
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                <p class="text-sm text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                <p class="text-sm text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Profile Data (Read-only) -->
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
            <div class="space-y-6">
                <!-- Member ID (Read Only) -->
                <div>
                    <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">ID Member</label>
                    <input type="text" value="{{ $member->member_id }}"
                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white opacity-75 cursor-not-allowed"
                        disabled readonly>
                    <p class="text-xs text-[#6a6a6a] mt-1">ID Member tidak dapat diubah</p>
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" value="{{ $member->name }}"
                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white opacity-75 cursor-not-allowed"
                        disabled readonly>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Nomor HP</label>
                    <input type="tel" value="{{ $member->phone }}"
                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white opacity-75 cursor-not-allowed"
                        disabled readonly>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Email</label>
                    <input type="email" value="{{ $member->email ?? '-' }}"
                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white opacity-75 cursor-not-allowed"
                        disabled readonly>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Alamat</label>
                    <textarea rows="3"
                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white opacity-75 cursor-not-allowed"
                        disabled readonly>{{ $member->address ?? '-' }}</textarea>
                </div>
            </div>

            <!-- Logout Button -->
            <div class="mt-8">
                <form method="POST" action="{{ route('member.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-transparent border border-red-500/50 text-red-400 px-6 py-3 rounded-lg hover:bg-red-900/20 hover:border-red-500 transition-all uppercase tracking-wider">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Member Card -->
        <div class="mb-8">
            @include('components.member-card', ['member' => $member])
        </div>

        <!-- Visit Progress Card -->
        <div class="mt-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
            <h3 class="text-lg text-white mb-4 uppercase tracking-wider">PROGRESS KUNJUNGAN</h3>
            <p class="text-sm text-[#9a9a9a] mb-6">Kunjungi website 5 kali untuk mendapatkan hadiah!</p>

            @php
                // Get current rank
                $currentRank = $member->rank ?? 'bronze';
                $currentStatus = $member->status ?? 'Bronze';

                // Rank colors
                $rankColors = [
                    'ruby' => ['bg' => 'bg-red-900/30', 'border' => 'border-red-600', 'text' => 'text-red-300'],
                    'diamond' => ['bg' => 'bg-cyan-900/30', 'border' => 'border-cyan-600', 'text' => 'text-cyan-300'],
                    'gold' => [
                        'bg' => 'bg-yellow-900/30',
                        'border' => 'border-yellow-600',
                        'text' => 'text-yellow-300',
                    ],
                    'silver' => ['bg' => 'bg-gray-400/30', 'border' => 'border-gray-400', 'text' => 'text-gray-300'],
                    'bronze' => [
                        'bg' => 'bg-orange-900/30',
                        'border' => 'border-orange-600',
                        'text' => 'text-orange-300',
                    ],
                ];
                $rankColor = $rankColors[$currentRank] ?? $rankColors['bronze'];
            @endphp

            <!-- Peringkat Kunjungan -->
            <div class="mb-6 text-center">
                <p class="text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Peringkat Kunjungan</p>
                <span
                    class="rank-badge inline-block px-4 py-2 rounded {{ $rankColor['bg'] }} border-2 {{ $rankColor['border'] }} {{ $rankColor['text'] }} font-bold text-base uppercase tracking-wider">
                    {{ $currentStatus }} MEMBERSHIP
                </span>
            </div>

            <!-- Guitar Icons -->
            <div class="flex justify-center items-center gap-4 md:gap-6 mb-6 flex-wrap">
                @php
                    // Ensure variables are set
                    $totalVisitCount = isset($totalVisitCount)
                        ? (int) $totalVisitCount
                        : (int) ($member->visits_count ?? ($member->visits()->count() ?? 0));
                    $targetVisits = isset($targetVisits) ? (int) $targetVisits : 5;
                    $displayCycleCount = isset($displayCycleCount) ? (int) $displayCycleCount : 0;

                    // Icon count mengikuti target (5 icon untuk target 5, 10 icon untuk target 10, dst)
                    $iconCount = $targetVisits;
                    $activeIconCount = min($displayCycleCount, $iconCount);
                @endphp
                @for ($i = 1; $i <= $iconCount; $i++)
                    <div class="guitar-icon-container relative" data-visit-index="{{ $i }}">
                        <img src="{{ asset('images/gitar-member.png') }}" alt="Guitar {{ $i }}"
                            class="guitar-icon w-12 h-12 md:w-16 md:h-16 transition-all duration-500 {{ $i <= $activeIconCount ? 'guitar-active' : 'guitar-inactive' }}"
                            data-active="{{ $i <= $activeIconCount ? 'true' : 'false' }}">
                        @if ($i <= $activeIconCount)
                            <div class="guitar-glow absolute inset-0"></div>
                        @endif
                    </div>
                @endfor
            </div>

            <!-- Progress Text -->
            <div class="text-center">
                <p class="text-white text-lg mb-2 font-bold">
                    <span id="visit-count-value" class="font-bold text-xl">{{ $totalVisitCount }}</span> / <span
                        id="visit-target-value">{{ $targetVisits }}</span> Kunjungan
                </p>
                <p id="visit-remaining-text" class="text-[#9a9a9a] text-sm">
                    @if ($hasClaimedCurrentCycle && $displayCycleCount >= $targetVisits)
                        Reward sudah di-claim. Progress akan lanjut ke cycle berikutnya.
                    @elseif($displayCycleCount < $targetVisits && $displayCycleCount > 0)
                        {{ $targetVisits - $displayCycleCount }} kunjungan lagi untuk mendapatkan hadiah
                    @elseif($displayCycleCount >= $targetVisits)
                        Anda sudah mencapai milestone! Silakan claim reward Anda.
                    @else
                        Progress akan dimulai dari cycle berikutnya.
                    @endif
                </p>
                <p class="text-xs text-[#6a6a6a] mt-2">
                    Total Kunjungan: <span id="total-visit-count">{{ $totalVisitCount }}</span> kali
                </p>

                <!-- Claim Reward Button -->
                <div id="claim-button-container" class="mt-4"
                    style="display: {{ isset($showClaimButton) && $showClaimButton ? 'block' : 'none' }};">
                    @if (isset($availableRewards) && $availableRewards->count() > 0)
                        <button onclick="openRewardModal()" class="claim-reward-btn">
                            Claim Reward
                        </button>
                    @endif
                </div>
                <div id="claim-message-container" class="mt-4"
                    style="display: {{ isset($hasClaimedCurrentCycle) && $hasClaimedCurrentCycle ? 'block' : 'none' }};">
                    <p class="text-sm text-[#6a6a6a] italic">
                        Reward untuk cycle ini sudah di-claim. Progress akan lanjut ke cycle berikutnya.
                    </p>
                </div>
            </div>
        </div>

        <!-- Reward Modal -->
        @if (isset($availableRewards) && $availableRewards->count() > 0)
            <div id="rewardModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center"
                style="display: none;">
                <div
                    class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl text-white font-bold uppercase tracking-wider">Hadiah Anda</h3>
                        <button onclick="closeRewardModal()"
                            class="text-white hover:text-[#9a9a9a] text-3xl leading-none">&times;</button>
                    </div>

                    <div class="space-y-6">
                        @foreach ($availableRewards as $reward)
                            <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-6">
                                <div class="flex flex-col md:flex-row gap-6">
                                    @if ($reward->gambar_hadiah)
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset($reward->gambar_hadiah) }}"
                                                alt="{{ $reward->nama_hadiah }}"
                                                class="w-full md:w-48 h-48 object-cover rounded-lg border border-[#4a4a4a]">
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <div class="mb-3">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-900/20 border border-yellow-700 text-yellow-400 mb-2">
                                                Milestone {{ $reward->milestone }} Kunjungan
                                            </span>
                                        </div>

                                        <h4 class="text-xl text-white font-bold mb-3">{{ $reward->nama_hadiah }}</h4>

                                        @if ($reward->kata_kata)
                                            <p class="text-[#9a9a9a] text-sm leading-relaxed">{{ $reward->kata_kata }}
                                            </p>
                                        @endif

                                        <div class="mt-4">
                                            <form method="POST" action="{{ route('member.reward.claim') }}">
                                                @csrf
                                                <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                                                <button type="submit"
                                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold px-4 py-2 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                                                    Claim Hadiah Ini
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button onclick="closeRewardModal()"
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Riwayat Hadiah -->
        @if (isset($rewardClaims) && $rewardClaims->count() > 0)
            <div class="mt-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
                <h3 class="text-lg text-white mb-4 uppercase tracking-wider">Riwayat Hadiah</h3>
                <div class="space-y-3">
                    @foreach ($rewardClaims as $claim)
                        <div
                            class="flex flex-col md:flex-row md:items-center justify-between bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 gap-2">
                            <div>
                                <p class="text-white text-sm font-semibold">
                                    {{ $claim->reward->nama_hadiah ?? 'Hadiah' }}</p>
                                <p class="text-xs text-[#9a9a9a]">
                                    Milestone: {{ $claim->reward->milestone ?? '-' }} | Diajukan:
                                    {{ $claim->requested_at ? \Carbon\Carbon::parse($claim->requested_at)->format('d M Y H:i') : '-' }}
                                </p>
                                @if ($claim->reward && $claim->reward->kata_kata)
                                    <p class="text-xs text-[#6a6a6a] mt-1">{{ $claim->reward->kata_kata }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                @if ($claim->status === 'fulfilled')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-900/20 border border-green-700 text-green-400">
                                        Sudah di-claim
                                        ({{ $claim->fulfilled_at ? \Carbon\Carbon::parse($claim->fulfilled_at)->format('d M Y') : '' }})
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-900/20 border border-yellow-700 text-yellow-400">
                                        Menunggu konfirmasi admin
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Member Info Card -->
        <div class="mt-6 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6">
            <h3 class="text-lg text-white mb-4 uppercase tracking-wider">Informasi Member</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-[#9a9a9a] mb-1">Tanggal Bergabung</p>
                    <p class="text-white">{{ $member->created_at->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-[#9a9a9a] mb-1">Terakhir Diupdate</p>
                    <p class="text-white">{{ $member->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
    </main>

    @include('components.footer')

    <style>
        .claim-reward-btn {
            background: linear-gradient(135deg, #f59e0b, #f97316, #ef4444);
            color: #0f0f0f;
            font-weight: 800;
            letter-spacing: 0.08em;
            padding: 0.9rem 2.6rem;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow:
                0 10px 30px rgba(249, 115, 22, 0.35),
                0 0 25px rgba(239, 68, 68, 0.35),
                0 0 0 2px rgba(255, 255, 255, 0.05);
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }

        .claim-reward-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.35), transparent 45%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .claim-reward-btn:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow:
                0 12px 35px rgba(249, 115, 22, 0.45),
                0 0 32px rgba(239, 68, 68, 0.45),
                0 0 0 2px rgba(255, 255, 255, 0.08);
            filter: brightness(1.05);
        }

        .claim-reward-btn:hover::after {
            opacity: 1;
        }

        .claim-reward-btn:active {
            transform: translateY(0) scale(0.99);
            filter: brightness(0.98);
        }

        .claim-reward-btn.glow {
            animation: rewardPulse 1.2s ease-in-out infinite;
        }

        @keyframes rewardPulse {

            0%,
            100% {
                box-shadow: 0 0 18px rgba(250, 204, 21, 0.45);
            }

            50% {
                box-shadow: 0 0 30px rgba(249, 115, 22, 0.6);
            }
        }

        .guitar-icon-container {
            position: relative;
            display: inline-block;
        }

        .guitar-icon {
            filter: grayscale(100%) brightness(0.3);
            transition: all 0.5s ease;
            opacity: 0.4;
        }

        .guitar-icon.guitar-active {
            filter: grayscale(0%) brightness(1) drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
            transform: scale(1.15);
            opacity: 1;
            animation: lightUp 0.6s ease-out;
        }

        .guitar-icon.guitar-inactive {
            filter: grayscale(100%) brightness(0.3);
            opacity: 0.4;
            transform: scale(1);
        }

        @keyframes lightUp {
            0% {
                filter: grayscale(100%) brightness(0.3);
                transform: scale(1);
                opacity: 0.4;
            }

            50% {
                filter: grayscale(0%) brightness(1.2) drop-shadow(0 0 15px rgba(255, 255, 255, 0.8));
                transform: scale(1.25);
            }

            100% {
                filter: grayscale(0%) brightness(1) drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
                transform: scale(1.15);
                opacity: 1;
            }
        }

        .guitar-glow {
            background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0.1) 50%, transparent 80%);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.6;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Stagger animation for active guitars */
        .guitar-icon-container[data-visit-index="1"] .guitar-glow {
            animation-delay: 0s;
        }

        .guitar-icon-container[data-visit-index="2"] .guitar-glow {
            animation-delay: 0.2s;
        }

        .guitar-icon-container[data-visit-index="3"] .guitar-glow {
            animation-delay: 0.4s;
        }

        .guitar-icon-container[data-visit-index="4"] .guitar-glow {
            animation-delay: 0.6s;
        }

        .guitar-icon-container[data-visit-index="5"] .guitar-glow {
            animation-delay: 0.8s;
        }

        /* Stagger lightUp animation for each guitar */
        .guitar-icon-container[data-visit-index="1"] .guitar-icon.guitar-active {
            animation-delay: 0s;
        }

        .guitar-icon-container[data-visit-index="2"] .guitar-icon.guitar-active {
            animation-delay: 0.1s;
        }

        .guitar-icon-container[data-visit-index="3"] .guitar-icon.guitar-active {
            animation-delay: 0.2s;
        }

        .guitar-icon-container[data-visit-index="4"] .guitar-icon.guitar-active {
            animation-delay: 0.3s;
        }

        .guitar-icon-container[data-visit-index="5"] .guitar-icon.guitar-active {
            animation-delay: 0.4s;
        }
    </style>

    <script>
        function openRewardModal() {
            const modal = document.getElementById('rewardModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeRewardModal() {
            const modal = document.getElementById('rewardModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('rewardModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeRewardModal();
                    }
                });
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeRewardModal();
                }
            });
        });

        // Realtime update visit count via polling
        document.addEventListener('DOMContentLoaded', function() {
            const targetVisitsEl = document.getElementById('visit-target-value');
            const countEl = document.getElementById('visit-count-value');
            const remainingEl = document.getElementById('visit-remaining-text');
            const iconsContainer = document.querySelector('.flex.justify-center.items-center.gap-4');

            let lastIconCount = 0;

            function updateIcons(count, totalCount, target) {
                // KUNCI: iconCount harus selalu 5 sesuai keinginan Anda
                const iconCount = 5;

                // count adalah displayCycleCount (1-5) yang dikirim dari controller
                const activeIconCount = count;

                const iconsContainer = document.querySelector('.flex.justify-center.items-center.gap-4');
                if (!iconsContainer) return;

                // Ambil semua container icon yang ada
                let iconContainers = document.querySelectorAll('.guitar-icon-container');

                // Jika jumlah icon di layar tidak sama dengan 5, kita reset dan buat ulang hanya 5
                if (iconContainers.length !== iconCount) {
                    iconsContainer.innerHTML = ''; // Kosongkan container

                    for (let i = 1; i <= iconCount; i++) {
                        const iconDiv = document.createElement('div');
                        iconDiv.className = 'guitar-icon-container relative';
                        iconDiv.setAttribute('data-visit-index', i);

                        // Gunakan path asset yang sesuai dengan project Anda
                        const imgUrl = "{{ asset('images/gitar-member.png') }}";

                        iconDiv.innerHTML = `
                <img src="${imgUrl}" 
                     alt="Guitar ${i}"
                     class="guitar-icon w-12 h-12 md:w-16 md:h-16 transition-all duration-500 guitar-inactive"
                     data-active="false">
            `;
                        iconsContainer.appendChild(iconDiv);
                    }
                    // Ambil ulang elemen yang baru dibuat
                    iconContainers = document.querySelectorAll('.guitar-icon-container');
                }

                // Update status nyala icon (1-5)
                iconContainers.forEach((container, idx) => {
                    const img = container.querySelector('img.guitar-icon');
                    let glow = container.querySelector('.guitar-glow');

                    // Icon aktif jika indeks (1-5) kurang dari atau sama dengan activeIconCount
                    const isActive = (idx + 1) <= activeIconCount;

                    if (img) {
                        img.classList.toggle('guitar-active', isActive);
                        img.classList.toggle('guitar-inactive', !isActive);
                        img.setAttribute('data-active', isActive.toString());
                    }

                    // Tambah atau hapus efek cahaya (glow)
                    if (isActive && !glow) {
                        const g = document.createElement('div');
                        g.className = 'guitar-glow absolute inset-0';
                        container.appendChild(g);
                    } else if (!isActive && glow) {
                        glow.remove();
                    }
                });
            }

            function updateTexts(count, totalCount, target, data) {
                // Target dinamis (5, 10, 15, dst)
                const targetVisits = target || 5;

                // count adalah displayCycleCount (progress dalam cycle aktif untuk icon)
                // totalCount adalah totalVisitCount (total kunjungan untuk display)
                if (countEl) countEl.textContent = totalCount; // Tampilkan total kunjungan
                if (targetVisitsEl) targetVisitsEl.textContent = targetVisits;
                if (remainingEl) {
                    if (totalCount < targetVisits && totalCount > 0) {
                        remainingEl.textContent =
                            `${targetVisits - totalCount} kunjungan lagi untuk mendapatkan hadiah`;
                        remainingEl.classList.remove('text-green-400');
                        remainingEl.classList.add('text-[#9a9a9a]');
                    } else if (totalCount >= targetVisits) {
                        // Cek apakah sudah claim
                        if (data && data.has_claimed_current_cycle) {
                            remainingEl.textContent =
                                'Reward sudah di-claim. Progress akan lanjut ke cycle berikutnya.';
                            remainingEl.classList.remove('text-green-400');
                            remainingEl.classList.add('text-[#9a9a9a]');
                        } else {
                            remainingEl.textContent = 'Anda sudah mencapai milestone! Silakan claim reward Anda.';
                            remainingEl.classList.remove('text-[#9a9a9a]');
                            remainingEl.classList.add('text-green-400');
                        }
                    } else {
                        remainingEl.textContent = 'Progress akan dimulai dari cycle berikutnya.';
                        remainingEl.classList.remove('text-green-400');
                        remainingEl.classList.add('text-[#9a9a9a]');
                    }
                }
                // Update total visit count
                const totalCountEl = document.getElementById('total-visit-count');
                if (totalCountEl && totalCount !== undefined) {
                    totalCountEl.textContent = totalCount;
                }
            }

            async function pollVisitCount() {
                try {
                    const res = await fetch("{{ route('member.visits.count') }}", {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    const count = parseInt(data.visit_count || 0, 10);
                    const totalCount = parseInt(data.total_visit_count || 0, 10);
                    const target = parseInt(data.target || 5, 10);

                    updateTexts(count, totalCount, target, data);
                    updateIcons(count, totalCount, target);

                    // Update target display
                    if (targetVisitsEl && target) {
                        targetVisitsEl.textContent = target;
                    }

                    // Update claim button visibility
                    const claimButtonContainer = document.getElementById('claim-button-container');
                    const claimMessageContainer = document.getElementById('claim-message-container');

                    if (data.show_claim_button) {
                        if (claimButtonContainer) claimButtonContainer.style.display = 'block';
                        if (claimMessageContainer) claimMessageContainer.style.display = 'none';
                    } else if (data.has_claimed_current_cycle) {
                        if (claimButtonContainer) claimButtonContainer.style.display = 'none';
                        if (claimMessageContainer) claimMessageContainer.style.display = 'block';
                    } else {
                        if (claimButtonContainer) claimButtonContainer.style.display = 'none';
                        if (claimMessageContainer) claimMessageContainer.style.display = 'none';
                    }
                } catch (e) {
                    console.error('Polling visit count failed', e);
                }
            }

            // initial update based on current markup
            const initialCount = parseInt(countEl?.textContent || '0', 10);
            const initialTotalCount = parseInt(document.getElementById('total-visit-count')?.textContent || '0',
                10);
            const initialTarget = parseInt(targetVisitsEl?.textContent || '5', 10);
            updateIcons(initialCount, initialTotalCount, initialTarget);
            updateTexts(initialCount, initialTotalCount, initialTarget, {});

            // Polling realtime dinonaktifkan untuk mencegah request API berulang.
            // Nilai awal tetap menggunakan data yang dirender oleh server.
        });
    </script>

    @stack('scripts')
</body>

</html>
