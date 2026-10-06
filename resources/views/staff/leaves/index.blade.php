<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>My Leave Requests - Musicmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html,
        body {
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">My Leave Requests</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                    </div>
                    <a href="{{ route('staff.leaves.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                        Request Leave
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

            <div class="mb-8 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg overflow-hidden">
                <button onclick="document.getElementById('policy-content').classList.toggle('hidden')"
                    class="w-full flex justify-between items-center p-4 text-left hover:bg-[#2a2a2a] transition-all">
                    <h2 class="text-lg font-medium text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ketentuan & Kebijakan Cuti
                    </h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6a6a6a]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="policy-content" class="hidden p-6 border-t border-[#4a4a4a] bg-[#0f0f0f]/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                        <div class="space-y-2">
                            <h3 class="text-green-400 font-semibold uppercase tracking-wider">1. Cuti Tahunan</h3>
                            <ul class="text-[#9a9a9a] list-disc ml-4 space-y-1">
                                <li>Hak: <span class="text-white">7 hari/tahun</span> (Min. kerja 12 bulan).</li>
                                <li>Pengajuan <span class="text-white">H-3</span> sebelum tanggal cuti.</li>
                                <li>Maksimal <span class="text-white">3 hari berturut-turut</span>.</li>
                                <li>Tidak dapat diuangkan/diakumulasi.</li>
                            </ul>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-blue-400 font-semibold uppercase tracking-wider">2. Cuti Sakit</h3>
                            <ul class="text-[#9a9a9a] list-disc ml-4 space-y-1">
                                <li>Maksimal <span class="text-white">5 hari/tahun</span>.</li>
                                <li>> 2 hari wajib <span class="text-white">Surat Dokter</span>.</li>
                                <li>Tetap dihitung hari kerja sah.</li>
                            </ul>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-yellow-400 font-semibold uppercase tracking-wider">3. Izin Pribadi & Unpaid
                            </h3>
                            <ul class="text-[#9a9a9a] list-disc ml-4 space-y-1">
                                <li>Izin Pribadi: Maks <span class="text-white">2 hari/tahun</span>.</li>
                                <li>Unpaid Leave: Maks <span class="text-white">3 hari/tahun</span> (Izin Pimpinan).
                                </li>
                            </ul>
                        </div>

                        <div class="md:col-span-2 lg:col-span-3 pt-4 border-t border-[#4a4a4a]">
                            <h3 class="text-purple-400 font-semibold uppercase tracking-wider mb-2">4. Cuti Khusus</h3>
                            <div class="flex flex-wrap gap-4 text-[#9a9a9a]">
                                <span>💍 Menikah: <b class="text-white">2 Hari</b></span>
                                <span>🕯️ Keluarga Inti Meninggal: <b class="text-white">2 Hari</b></span>
                                <span>🕯️ Saudara Kandung Meninggal: <b class="text-white">1 Hari</b></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-red-900/10 border border-red-500/30 rounded text-xs text-red-400 italic">
                        * Cuti tanpa izin dianggap tidak masuk kerja. Pelanggaran dapat dikenakan sanksi.
                    </div>
                </div>
            </div>
            <!-- Leave Requests List -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <!-- Filter -->
                <form method="GET" action="{{ route('staff.leaves.index') }}" class="mb-6">
                    <select name="status" onchange="this.form.submit()"
                        class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                        </option>
                    </select>
                </form>

                <div class="space-y-4">
                    @forelse($leaveRequests as $leaveRequest)
                        <div
                            class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-6 hover:border-[#6a6a6a] transition-colors">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <h3 class="text-white text-lg font-medium">
                                            {{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') }} -
                                            {{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M Y') }}
                                        </h3>
                                        <span
                                            class="px-2 py-1 {{ $leaveRequest->status_badge }} border rounded text-xs uppercase">
                                            {{ $leaveRequest->status_text }}
                                        </span>
                                    </div>
                                    <p class="text-[#9a9a9a] text-sm mb-3">
                                        <span class="text-white font-medium">Total Days:</span>
                                        {{ $leaveRequest->total_days }} day(s)
                                    </p>
                                    <p class="text-[#9a9a9a] text-sm mb-3">{{ Str::limit($leaveRequest->reason, 150) }}
                                    </p>
                                    <div class="flex items-center gap-4 text-xs text-[#6a6a6a]">
                                        <span>Submitted: {{ $leaveRequest->created_at->format('d M Y H:i') }}</span>
                                        @if ($leaveRequest->approved_at)
                                            <span>•</span>
                                            <span>Processed:
                                                {{ $leaveRequest->approved_at->format('d M Y H:i') }}</span>
                                            @if ($leaveRequest->approver)
                                                <span>•</span>
                                                <span>By: {{ $leaveRequest->approver->name }}</span>
                                            @endif
                                        @endif
                                    </div>
                                    @if ($leaveRequest->rejection_reason)
                                        <div
                                            class="mt-3 p-3 bg-red-900/20 border border-red-500/50 rounded text-red-400 text-sm">
                                            <strong>Rejection Reason:</strong> {{ $leaveRequest->rejection_reason }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('staff.leaves.show', $leaveRequest->id) }}"
                                        class="bg-transparent border border-[#4a4a4a] text-white px-4 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all text-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-[#9a9a9a]">No leave requests found</div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($leaveRequests->hasPages())
                    <div class="mt-6 flex items-center justify-center">
                        <div class="flex gap-2">
                            @if ($leaveRequests->onFirstPage())
                                <span
                                    class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $leaveRequests->previousPageUrl() }}"
                                    class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Previous</a>
                            @endif

                            @foreach ($leaveRequests->getUrlRange(1, $leaveRequests->lastPage()) as $page => $url)
                                @if ($page == $leaveRequests->currentPage())
                                    <span
                                        class="px-4 py-2 bg-[#2a2a2a] border border-[#6a6a6a] rounded-lg text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($leaveRequests->hasMorePages())
                                <a href="{{ $leaveRequests->nextPageUrl() }}"
                                    class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Next</a>
                            @else
                                <span
                                    class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>

    @include('components.admin-footer')
    @php
        use Illuminate\Support\Str;
    @endphp

    <script>
        // Real-time updates for leave requests
        (function() {
            let lastUpdate = new Date().toISOString().slice(0, 19).replace('T', ' ');
            const leaveRequestsMap = new Map();
            const container = document.querySelector('.space-y-4');

            // Initialize existing leave requests
            @foreach ($leaveRequests as $leaveRequest)
                leaveRequestsMap.set({{ $leaveRequest->id }}, true);
            @endforeach

            function getStatusBadge(status) {
                if (status === 'pending') {
                    return 'bg-yellow-900/30 border-yellow-500/50 text-yellow-400';
                } else if (status === 'approved') {
                    return 'bg-green-900/30 border-green-500/50 text-green-400';
                } else {
                    return 'bg-red-900/30 border-red-500/50 text-red-400';
                }
            }

            function getStatusText(status) {
                if (status === 'pending') return 'Pending';
                if (status === 'approved') return 'Approved';
                if (status === 'rejected') return 'Rejected';
                return 'Unknown';
            }

            function createLeaveRequestCard(leaveRequest) {
                const card = document.createElement('div');
                card.className =
                    'bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-6 hover:border-[#6a6a6a] transition-colors';
                card.setAttribute('data-leave-id', leaveRequest.id);

                const rejectionHtml = leaveRequest.rejection_reason ? `
                    <div class="mt-3 p-3 bg-red-900/20 border border-red-500/50 rounded text-red-400 text-sm">
                        <strong>Rejection Reason:</strong> ${leaveRequest.rejection_reason}
                    </div>
                ` : '';

                const approverHtml = leaveRequest.approver_name ? `
                    <span>•</span>
                    <span>By: ${leaveRequest.approver_name}</span>
                ` : '';

                const approvedAtHtml = leaveRequest.approved_at ? `
                    <span>•</span>
                    <span>Processed: ${leaveRequest.approved_at}</span>
                    ${approverHtml}
                ` : '';

                card.innerHTML = `
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                <h3 class="text-white text-lg font-medium">
                                    ${leaveRequest.start_date} - ${leaveRequest.end_date}
                                </h3>
                                <span class="px-2 py-1 ${getStatusBadge(leaveRequest.status)} border rounded text-xs uppercase">
                                    ${getStatusText(leaveRequest.status)}
                                </span>
                            </div>
                            <p class="text-[#9a9a9a] text-sm mb-3">
                                <span class="text-white font-medium">Total Days:</span> ${leaveRequest.total_days} day(s)
                            </p>
                            <p class="text-[#9a9a9a] text-sm mb-3">${leaveRequest.reason.substring(0, 150)}${leaveRequest.reason.length > 150 ? '...' : ''}</p>
                            <div class="flex items-center gap-4 text-xs text-[#6a6a6a]">
                                <span>Submitted: ${leaveRequest.created_at}</span>
                                ${approvedAtHtml}
                            </div>
                            ${rejectionHtml}
                        </div>
                        <div class="flex gap-2">
                            <a href="/staff/leaves/${leaveRequest.id}" 
                               class="bg-transparent border border-[#4a4a4a] text-white px-4 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                `;

                return card;
            }

            function checkForUpdates() {
                fetch(`{{ route('staff.leaves.api.updates') }}?last_update=${encodeURIComponent(lastUpdate)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.leave_requests && data.leave_requests.length > 0) {
                            data.leave_requests.forEach(leaveRequest => {
                                if (!leaveRequestsMap.has(leaveRequest.id)) {
                                    // New leave request - add to top
                                    const card = createLeaveRequestCard(leaveRequest);
                                    if (container && container.firstChild) {
                                        container.insertBefore(card, container.firstChild);
                                    } else if (container) {
                                        container.appendChild(card);
                                    }
                                    leaveRequestsMap.set(leaveRequest.id, true);
                                } else {
                                    // Update existing card
                                    const existingCard = document.querySelector(
                                        `[data-leave-id="${leaveRequest.id}"]`);
                                    if (existingCard) {
                                        const newCard = createLeaveRequestCard(leaveRequest);
                                        existingCard.replaceWith(newCard);

                                        // Show notification if status changed
                                        if (leaveRequest.status !== 'pending') {
                                            const statusText = getStatusText(leaveRequest.status);
                                            showNotification(
                                                `Pengajuan cuti Anda telah ${statusText.toLowerCase()}!`,
                                                leaveRequest.status === 'approved' ? 'success' : 'error'
                                                );
                                        }
                                    }
                                }
                            });
                        }
                        lastUpdate = data.last_update;
                    })
                    .catch(error => {
                        console.error('Error checking for updates:', error);
                    });
            }

            function showNotification(message, type = 'info') {
                const icon = type === 'success' ? 'success' : type === 'error' ? 'error' : 'info';
                Swal.fire({
                    title: message,
                    icon: icon,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    background: '#1a1a1a',
                    color: '#ffffff',
                    customClass: {
                        popup: 'swal2-dark',
                        title: 'swal2-title-dark',
                    }
                });
            }

            // Polling realtime dinonaktifkan untuk mencegah request API berulang.
            // Muat ulang halaman untuk melihat pembaruan cuti terbaru.
        })();
    </script>
</body>

</html>
