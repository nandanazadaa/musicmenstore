<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Manage Assignments - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Manage Assignments</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('admin.assignments.create') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5l0 14" /><path d="M5 12l14 0" />
                        </svg>
                        Add Assignment
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Assignments Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <!-- Search and Filter -->
                <form method="GET" action="{{ route('admin.assignments.index') }}" class="mb-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="flex-1 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                               placeholder="Search by title, description, staff name...">
                        <select name="status" onchange="this.form.submit()" 
                                class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        <button type="submit" 
                                class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Search
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#4a4a4a]">
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Title</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Staff</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Status</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Created By</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Created At</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors" data-assignment-id="{{ $assignment->id }}">
                                    <td class="py-3 px-4 text-white text-sm font-medium">{{ $assignment->title }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $assignment->staff->nama }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        @if($assignment->status === 'pending')
                                            <span class="px-2 py-1 bg-yellow-900/30 border border-yellow-500/50 rounded text-yellow-400 text-xs uppercase">Pending</span>
                                        @elseif($assignment->status === 'in_progress')
                                            <span class="px-2 py-1 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-xs uppercase">In Progress</span>
                                        @else
                                            <span class="px-2 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-xs uppercase">Completed</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $assignment->creator->name }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $assignment->created_at->format('d M Y H:i') }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.assignments.show', $assignment->id) }}" 
                                               class="text-blue-400 hover:text-blue-300 transition-colors" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.assignments.edit', $assignment->id) }}" 
                                               class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.assignments.destroy', $assignment->id) }}" 
                                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 px-4 text-center text-[#9a9a9a]">No assignments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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

    <script>
        // Real-time assignments update
        (function() {
            // Set lastUpdate to 1 minute ago to catch any recent assignments
            let lastUpdate = new Date(Date.now() - 60000).toISOString().slice(0, 19).replace('T', ' ');
            const tbody = document.querySelector('tbody');
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

            function createAssignmentRow(assignment) {
                return `
                    <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors new-assignment-row" data-assignment-id="${assignment.id}">
                        <td class="py-3 px-4 text-white text-sm font-medium">${assignment.title}</td>
                        <td class="py-3 px-4 text-white text-sm">${assignment.staff_name}</td>
                        <td class="py-3 px-4 text-sm">${getStatusBadge(assignment.status)}</td>
                        <td class="py-3 px-4 text-white text-sm">${assignment.creator_name}</td>
                        <td class="py-3 px-4 text-white text-sm">${assignment.created_at}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <a href="/admin/assignments/${assignment.id}" 
                                   class="text-blue-400 hover:text-blue-300 transition-colors" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                <a href="/admin/assignments/${assignment.id}/edit" 
                                   class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="/admin/assignments/${assignment.id}" 
                                      class="inline" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `;
            }

            function updateAssignments() {
                const search = new URLSearchParams(window.location.search).get('search') || '';
                const status = new URLSearchParams(window.location.search).get('status') || '';
                
                fetch(`{{ route('admin.assignments.api.new') }}?last_update=${encodeURIComponent(lastUpdate)}&search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`, {
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
                                const emptyRow = tbody.querySelector('tr td[colspan]');
                                if (emptyRow) {
                                    emptyRow.closest('tr').remove();
                                }
                                
                                // Insert new row at the top
                                const newRow = document.createElement('tr');
                                newRow.innerHTML = createAssignmentRow(assignment);
                                newRow.classList.add('new-assignment-highlight');
                                tbody.insertBefore(newRow, tbody.firstChild);
                                
                                // Add animation
                                setTimeout(() => {
                                    newRow.classList.remove('new-assignment-highlight');
                                }, 2000);
                                
                                // Show notification sound or visual feedback
                                console.log('New assignment detected:', assignment.title);
                            } else {
                                // Update existing row if status changed
                                const existingRow = tbody.querySelector(`tr[data-assignment-id="${assignment.id}"]`);
                                if (existingRow) {
                                    const statusCell = existingRow.querySelector('td:nth-child(3)');
                                    if (statusCell) {
                                        const currentStatus = statusCell.textContent.trim();
                                        if (currentStatus !== assignment.status.toUpperCase()) {
                                            statusCell.innerHTML = getStatusBadge(assignment.status);
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
                })
                .catch(error => {
                    console.error('Error fetching new assignments:', error);
                });
            }

            // Polling realtime dinonaktifkan untuk mencegah request API berulang.
            // Muat ulang halaman untuk melihat tugas terbaru.
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
