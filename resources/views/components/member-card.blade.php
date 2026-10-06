@php
    use App\Models\LandingPageSetting;
    
    // Get member rank/status - use from database if available, otherwise calculate
    $visitCount = $member->visits()->count();
    if ($member->rank && $member->status) {
        $rankStatus = $member->status;
        $rankKey = $member->rank;
    } else {
        $rankData = $member->getRankFromVisits($visitCount);
        $rankStatus = $rankData['status'];
        $rankKey = $rankData['rank'];
        // Update member rank in database
        $member->updateStatusFromVisits();
        $member->refresh();
        $rankStatus = $member->status;
        $rankKey = $member->rank;
    }
    
    // Get Musicmen contact info
    $musicmenAddress = LandingPageSetting::getValue('contact', 'address_text', 'Jl. Wates No.148 Km. 3,5 No, Onggobayan, Ngestiharjo, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55184');
    $musicmenPhone = LandingPageSetting::getValue('contact', 'phone_text', '08816707166');
    
    // Rank colors for badge
    $rankColors = [
        'ruby' => ['bg' => 'bg-red-900/30', 'border' => 'border-red-600', 'text' => 'text-red-300'],
        'diamond' => ['bg' => 'bg-cyan-900/30', 'border' => 'border-cyan-600', 'text' => 'text-cyan-300'],
        'gold' => ['bg' => 'bg-yellow-900/30', 'border' => 'border-yellow-600', 'text' => 'text-yellow-300'],
        'silver' => ['bg' => 'bg-gray-400/30', 'border' => 'border-gray-400', 'text' => 'text-gray-300'],
        'bronze' => ['bg' => 'bg-orange-900/30', 'border' => 'border-orange-600', 'text' => 'text-orange-300'],
    ];
    
    $rankColor = $rankColors[$rankKey] ?? $rankColors['bronze'];
@endphp

<div class="member-card-container bg-[#0f0f0f] p-6 rounded-lg">
    <div class="member-card relative rounded-lg overflow-hidden" style="width: 100%; max-width: 1050px; aspect-ratio: 1050/600; background-image: url('{{ asset('images/tampilan-belakang.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; position: relative; margin: 0 auto;">
        <!-- Content Text Overlay -->
        <div class="absolute inset-0 p-8 sm:p-10 flex">
            <!-- Left Side - Member Info -->
            <div class="flex-1 flex flex-col justify-between pr-6">
                <div>
                    <!-- Member Name - Position: Top Left (sesuai contoh) -->
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4 uppercase tracking-wide leading-tight" style="text-shadow: 3px 3px 8px rgba(0,0,0,0.95), 0 0 15px rgba(0,0,0,0.7); font-family: Arial, sans-serif;">
                        {{ strtoupper($member->name) }}
                    </h2>
                    
                    <!-- Membership Rank - Position: Below Name -->
                    <div class="mb-6">
                        <span class="inline-block px-5 py-2.5 rounded {{ $rankColor['bg'] }} border-2 {{ $rankColor['border'] }} {{ $rankColor['text'] }} font-bold text-base sm:text-lg uppercase tracking-wider" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                            {{ $rankStatus }} MEMBERSHIP
                        </span>
                    </div>
                </div>
                
                <!-- Contact Info - Position: Bottom Left -->
                <div class="mt-auto space-y-2.5 max-w-[60%]">
                    <div class="flex items-center gap-2.5 text-white text-sm sm:text-base font-semibold" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.95), 0 0 10px rgba(0,0,0,0.7);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <span class="break-words">{{ $musicmenPhone }}</span>
                    </div>
                    <div class="flex items-start gap-2.5 text-white text-xs sm:text-sm font-semibold" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.95), 0 0 10px rgba(0,0,0,0.7);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="flex-1 leading-relaxed break-words" style="word-wrap: break-word; overflow-wrap: break-word;">{{ $musicmenAddress }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - ID Member - Position: Bottom Right (sesuai contoh) -->
            <div class="flex flex-col items-end justify-end" style="width: 40%; min-width: 180px;">
                <!-- Member ID -->
                <div class="text-right">
                    <p class="text-white font-bold text-lg sm:text-xl md:text-2xl uppercase tracking-wider" style="letter-spacing: 0.2em; text-shadow: 3px 3px 8px rgba(0,0,0,0.95), 0 0 15px rgba(0,0,0,0.7); font-family: Arial, sans-serif;">
                        {{ $member->member_id }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Download Button -->
    <div class="mt-4 flex justify-center">
        <button onclick="downloadMemberCard()" class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider text-sm">
            Download Kartu Member
        </button>
    </div>
</div>

<style>
    .member-card {
        background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
        position: relative;
        min-height: 300px;
    }
    
    @media print {
        .member-card-container {
            page-break-inside: avoid;
        }
        .member-card {
            min-height: 300px;
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function downloadMemberCard() {
        const card = document.querySelector('.member-card');
        const cardContainer = document.querySelector('.member-card-container');
        
        // Hide download button temporarily
        const downloadBtn = cardContainer.querySelector('button');
        const originalDisplay = downloadBtn.style.display;
        downloadBtn.style.display = 'none';
        
        // Use html2canvas to capture the card
        html2canvas(card, {
            backgroundColor: '#0f0f0f',
            scale: 3,
            logging: false,
            useCORS: true,
            allowTaint: true
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'kartu-member-{{ $member->member_id }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            downloadBtn.style.display = originalDisplay;
        }).catch(error => {
            console.error('Error generating card:', error);
            alert('Gagal mengunduh kartu member. Silakan gunakan fitur print browser (Ctrl+P / Cmd+P)');
            downloadBtn.style.display = originalDisplay;
        });
    }
</script>

