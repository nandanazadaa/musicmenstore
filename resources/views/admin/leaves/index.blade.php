<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Manage Leave Requests - Musicmen Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Manage Leave Requests</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ketentuan & Kebijakan Cuti
                    </h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6a6a6a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            <h3 class="text-yellow-400 font-semibold uppercase tracking-wider">3. Izin Pribadi & Unpaid</h3>
                            <ul class="text-[#9a9a9a] list-disc ml-4 space-y-1">
                                <li>Izin Pribadi: Maks <span class="text-white">2 hari/tahun</span>.</li>
                                <li>Unpaid Leave: Maks <span class="text-white">3 hari/tahun</span> (Izin Pimpinan).</li>
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

            <!-- Leave Requests Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.leaves.index') }}" class="mb-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select name="staff_id" onchange="this.form.submit()" 
                                class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                            <option value="">All Staff</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->nama }}
                                </option>
                            @endforeach
                        </select>
                        <select name="status" onchange="this.form.submit()" 
                                class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Staff</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Date Range</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Total Days</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Status</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Submitted</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveRequests as $leaveRequest)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4 text-white text-sm">{{ $leaveRequest->staff->nama }}</td>
                                    <td class="py-3 px-4 text-white text-sm">
                                        {{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $leaveRequest->total_days }} day(s)</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 {{ $leaveRequest->status_badge }} border rounded text-xs uppercase">
                                            {{ $leaveRequest->status_text }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $leaveRequest->created_at->format('d M Y') }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.leaves.show', $leaveRequest->id) }}" 
                                               class="text-blue-400 hover:text-blue-300 transition-colors" title="View Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            @if($leaveRequest->status === 'pending')
                                                <form method="POST" action="{{ route('admin.leaves.approve', $leaveRequest->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-green-400 hover:text-green-300 transition-colors" 
                                                            title="Approve"
                                                            onclick="return confirm('Are you sure you want to approve this leave request?');">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <button onclick="openRejectModal({{ $leaveRequest->id }})" 
                                                        class="text-red-400 hover:text-red-300 transition-colors" 
                                                        title="Reject">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 px-4 text-center text-[#9a9a9a]">No leave requests found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($leaveRequests->hasPages())
                <div class="mt-6 flex items-center justify-center">
                    <div class="flex gap-2">
                        @if($leaveRequests->onFirstPage())
                            <span class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $leaveRequests->previousPageUrl() }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Previous</a>
                        @endif

                        @foreach($leaveRequests->getUrlRange(1, $leaveRequests->lastPage()) as $page => $url)
                            @if($page == $leaveRequests->currentPage())
                                <span class="px-4 py-2 bg-[#2a2a2a] border border-[#6a6a6a] rounded-lg text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($leaveRequests->hasMorePages())
                            <a href="{{ $leaveRequests->nextPageUrl() }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Next</a>
                        @else
                            <span class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-semibold mb-4">Reject Leave Request</h2>
            <form method="POST" id="rejectForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Rejection Reason <span class="text-red-400">*</span></label>
                    <textarea name="rejection_reason" rows="4" 
                              class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                              placeholder="Please provide a reason for rejection..." required></textarea>
                    <p class="text-xs text-[#6a6a6a] mt-1">Minimum 10 characters, maximum 500 characters</p>
                </div>
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                        Reject
                    </button>
                    <button type="button" onclick="closeRejectModal()" 
                            class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('components.admin-footer')

    <script>
        function openRejectModal(leaveRequestId) {
            document.getElementById('rejectForm').action = `/admin/leaves/${leaveRequestId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectForm').reset();
        }

        // Close modal on outside click
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        // Real-time updates for leave requests
        (function() {
            let lastUpdate = new Date().toISOString().slice(0, 19).replace('T', ' ');
            const leaveRequestsMap = new Map();
            const tbody = document.querySelector('tbody');
            
            // Initialize existing leave requests
            @foreach($leaveRequests as $leaveRequest)
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

            function createLeaveRequestRow(leaveRequest) {
                const row = document.createElement('tr');
                row.className = 'border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors';
                row.setAttribute('data-leave-id', leaveRequest.id);
                
                const actionsHtml = leaveRequest.status === 'pending' ? `
                    <div class="flex items-center gap-2">
                        <a href="/admin/leaves/${leaveRequest.id}" 
                           class="text-blue-400 hover:text-blue-300 transition-colors" title="View Details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </a>
                        <form method="POST" action="/admin/leaves/${leaveRequest.id}/approve" class="inline approve-form">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="button" 
                                    class="approve-btn text-green-400 hover:text-green-300 transition-colors" 
                                    title="Approve"
                                    data-id="${leaveRequest.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </button>
                        </form>
                        <button onclick="openRejectModal(${leaveRequest.id})" 
                                class="text-red-400 hover:text-red-300 transition-colors" 
                                title="Reject">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                ` : `
                    <div class="flex items-center gap-2">
                        <a href="/admin/leaves/${leaveRequest.id}" 
                           class="text-blue-400 hover:text-blue-300 transition-colors" title="View Details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </a>
                    </div>
                `;

                row.innerHTML = `
                    <td class="py-3 px-4 text-white text-sm">${leaveRequest.staff_name}</td>
                    <td class="py-3 px-4 text-white text-sm">${leaveRequest.start_date} - ${leaveRequest.end_date}</td>
                    <td class="py-3 px-4 text-white text-sm">${leaveRequest.total_days} day(s)</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 ${getStatusBadge(leaveRequest.status)} border rounded text-xs uppercase">
                            ${getStatusText(leaveRequest.status)}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-white text-sm">${leaveRequest.created_at}</td>
                    <td class="py-3 px-4">
                        ${actionsHtml}
                    </td>
                `;
                
                return row;
            }

            function checkForNewLeaveRequests() {
                fetch(`{{ route('admin.leaves.api.new') }}?last_update=${encodeURIComponent(lastUpdate)}`, {
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
                                // New leave request - add to top of table
                                const row = createLeaveRequestRow(leaveRequest);
                                if (tbody && tbody.firstChild) {
                                    tbody.insertBefore(row, tbody.firstChild);
                                } else if (tbody) {
                                    tbody.appendChild(row);
                                }
                                leaveRequestsMap.set(leaveRequest.id, true);
                                
                                // Show notification
                                showNotification(`Pengajuan cuti baru dari ${leaveRequest.staff_name}`, 'info');
                            } else {
                                // Update existing row
                                const existingRow = document.querySelector(`tr[data-leave-id="${leaveRequest.id}"]`);
                                if (existingRow) {
                                    const newRow = createLeaveRequestRow(leaveRequest);
                                    existingRow.replaceWith(newRow);
                                }
                            }
                        });
                    }
                    lastUpdate = data.last_update;
                })
                .catch(error => {
                    console.error('Error checking for new leave requests:', error);
                });
            }

            function showNotification(message, type = 'info') {
                const icon = type === 'info' ? 'info' : type === 'success' ? 'success' : 'error';
                Swal.fire({
                    title: message,
                    icon: icon,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
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
            // Muat ulang halaman untuk melihat pengajuan cuti terbaru.
            
            // Handle approve button clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('.approve-btn')) {
                    e.preventDefault();
                    const btn = e.target.closest('.approve-btn');
                    const form = btn.closest('.approve-form');
                    const leaveId = btn.getAttribute('data-id');
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Pengajuan cuti ini akan disetujui!',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6a6a6a',
                        confirmButtonText: 'Ya, Setujui!',
                        cancelButtonText: 'Batal',
                        background: '#1a1a1a',
                        color: '#ffffff',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        })();
    </script>
</body>
</html>
