<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>My Assignments - Musicmen</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">My Assignments</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                    <p class="text-sm text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Assignments List -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <!-- Filter -->
                <form method="GET" action="{{ route('staff.assignments.index') }}" class="mb-6">
                    <select name="status" onchange="this.form.submit()" 
                            class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </form>

                <div class="space-y-4">
                    @forelse($assignments as $assignment)
                        <div class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg p-6 hover:border-[#6a6a6a] transition-colors {{ $assignment->isUnread() ? 'border-yellow-500/50' : '' }}" data-assignment-id="{{ $assignment->id }}">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-white text-lg font-medium">{{ $assignment->title }}</h3>
                                        @if($assignment->isUnread())
                                            <span class="px-2 py-1 bg-yellow-500/20 border border-yellow-500/50 rounded text-yellow-400 text-xs">NEW</span>
                                        @endif
                                        @if($assignment->status === 'pending')
                                            <span class="px-2 py-1 bg-yellow-900/30 border border-yellow-500/50 rounded text-yellow-400 text-xs uppercase">Pending</span>
                                        @elseif($assignment->status === 'in_progress')
                                            <span class="px-2 py-1 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-xs uppercase">In Progress</span>
                                        @else
                                            <span class="px-2 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs uppercase">Completed</span>
                                        @endif
                                    </div>
                                    <p class="text-[#9a9a9a] text-sm mb-3">{{ Str::limit($assignment->description ?? 'No description', 150) }}</p>
                                    <div class="flex items-center gap-4 text-xs text-[#6a6a6a]">
                                        <span>Created by: {{ $assignment->creator->name }}</span>
                                        <span>•</span>
                                        <span>{{ $assignment->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('staff.assignments.show', $assignment->id) }}" 
                                       class="bg-transparent border border-[#4a4a4a] text-white px-4 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all text-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-[#9a9a9a]">No assignments found</div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($assignments->hasPages())
                <div class="mt-6 flex items-center justify-center">
                    <div class="flex gap-2">
                        @if($assignments->onFirstPage())
                            <span class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $assignments->previousPageUrl() }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Previous</a>
                        @endif

                        @foreach($assignments->getUrlRange(1, $assignments->lastPage()) as $page => $url)
                            @if($page == $assignments->currentPage())
                                <span class="px-4 py-2 bg-[#2a2a2a] border border-[#6a6a6a] rounded-lg text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($assignments->hasMorePages())
                            <a href="{{ $assignments->nextPageUrl() }}" class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-white hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all">Next</a>
                        @else
                            <span class="px-4 py-2 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg text-[#6a6a6a] cursor-not-allowed">Next</span>
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
        // Real-time assignments update
        (function() {
            // Set lastUpdate to 1 minute ago to catch any recent assignments
            let lastUpdate = new Date(Date.now() - 60000).toISOString().slice(0, 19).replace('T', ' ');
            const assignmentsContainer = document.querySelector('.space-y-4');
            const assignmentsMap = new Map(); // Track existing assignments by ID
            
            // Initialize existing assignments
            @foreach($assignments as $assignment)
                assignmentsMap.set({{ $assignment->id }}, true);
            @endforeach

            function getStatusBadge(status) {
                if (status === 'pending') {
                    return '<span class="px-2 py-1 bg-yellow-900/30 border border-yellow-500/50 rounded text-yellow-400 text-xs uppercase">Pending</span>';
                } else if (status === 'in_progress') {
                    return '<span class="px-2 py-1 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-xs uppercase">In Progress</span>';
                } else {
                    return '<span class="px-2 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs uppercase">Completed</span>';
                }
            }

            function createAssignmentCard(assignment) {
                const unreadBadge = assignment.is_unread ? '<span class="px-2 py-1 bg-yellow-500/20 border border-yellow-500/50 rounded text-yellow-400 text-xs">NEW</span>' : '';
                const borderClass = assignment.is_unread ? 'border-yellow-500/50' : 'border-[#4a4a4a]';
                
                return `
                    <div class="bg-[#0f0f0f] border ${borderClass} rounded-lg p-6 hover:border-[#6a6a6a] transition-colors new-assignment-card" data-assignment-id="${assignment.id}">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-white text-lg font-medium">${assignment.title}</h3>
                                    ${unreadBadge}
                                    ${getStatusBadge(assignment.status)}
                                </div>
                                <p class="text-[#9a9a9a] text-sm mb-3">${assignment.description ? (assignment.description.length > 150 ? assignment.description.substring(0, 150) + '...' : assignment.description) : 'No description'}</p>
                                <div class="flex items-center gap-4 text-xs text-[#6a6a6a]">
                                    <span>Created by: ${assignment.creator_name}</span>
                                    <span>•</span>
                                    <span>${assignment.created_at}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="/staff/assignments/${assignment.id}" 
                                   class="bg-transparent border border-[#4a4a4a] text-white px-4 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }

            function updateAssignments() {
                const status = new URLSearchParams(window.location.search).get('status') || '';
                
                fetch(`{{ route('staff.assignments.api.new') }}?last_update=${encodeURIComponent(lastUpdate)}&status=${encodeURIComponent(status)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.assignments && data.assignments.length > 0) {
                        data.assignments.forEach(assignment => {
                            // Only add if it's a new assignment (not in our map)
                            if (!assignmentsMap.has(assignment.id)) {
                                assignmentsMap.set(assignment.id, true);
                                
                                // Remove "No assignments found" message if exists
                                const emptyDiv = assignmentsContainer.querySelector('.text-center');
                                if (emptyDiv && emptyDiv.textContent.includes('No assignments found')) {
                                    emptyDiv.remove();
                                }
                                
                                // Insert new card at the top
                                const newCard = document.createElement('div');
                                newCard.innerHTML = createAssignmentCard(assignment);
                                newCard.classList.add('new-assignment-highlight');
                                assignmentsContainer.insertBefore(newCard, assignmentsContainer.firstChild);
                                
                                // Add animation
                                setTimeout(() => {
                                    newCard.classList.remove('new-assignment-highlight');
                                }, 2000);
                                
                                // Show notification
                                console.log('New assignment detected:', assignment.title);
                                
                                // Update badge immediately
                                updateAssignmentBadge();
                            } else {
                                // Update existing card if status changed
                                const existingCard = assignmentsContainer.querySelector(`[data-assignment-id="${assignment.id}"]`);
                                if (existingCard) {
                                    const statusBadge = existingCard.querySelector('.flex.items-center.gap-3');
                                    if (statusBadge) {
                                        // Update status badge
                                        const badges = statusBadge.querySelectorAll('span');
                                        if (badges.length > 1) {
                                            const currentStatus = badges[badges.length - 1].textContent.trim();
                                            if (currentStatus !== assignment.status.toUpperCase()) {
                                                badges[badges.length - 1].outerHTML = getStatusBadge(assignment.status);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                    
                    // Always update lastUpdate to current time
                    if (data.last_update) {
                        lastUpdate = data.last_update;
                    } else {
                        lastUpdate = new Date().toISOString().slice(0, 19).replace('T', ' ');
                    }
                    
                    // Update badge notification
                    updateAssignmentBadge();
                })
                .catch(error => {
                    console.error('Error fetching new assignments:', error);
                });
            }

            function updateAssignmentBadge() {
                const assignmentBadge = document.getElementById('assignment-badge');
                if (assignmentBadge) {
                    fetch('{{ route("staff.assignments.unreadCount") }}', {
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
                    .catch(error => {
                        console.error('Error fetching assignment count:', error);
                    });
                }
            }

            // Polling realtime dan pembaruan badge otomatis dinonaktifkan
            // untuk mencegah request API berulang. Muat ulang halaman bila perlu.
        })();
    </script>

    <style>
        .new-assignment-highlight {
            animation: highlightNew 2s ease-out;
        }

        @keyframes highlightNew {
            0% {
                background-color: rgba(234, 179, 8, 0.3);
                transform: translateX(-10px);
                opacity: 0;
            }
            50% {
                background-color: rgba(234, 179, 8, 0.2);
            }
            100% {
                background-color: transparent;
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</body>
</html>
