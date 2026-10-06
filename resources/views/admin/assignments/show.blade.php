<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>View Assignment - Musicmen Admin</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Assignment Details</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.assignments.edit', $assignment->id) }}" 
                           class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Edit
                        </a>
                        <a href="{{ route('admin.assignments.index') }}" 
                           class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Assignment Details -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Title</label>
                        <p class="text-white text-lg">{{ $assignment->title }}</p>
                    </div>

                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Staff</label>
                        <p class="text-white">{{ $assignment->staff->nama }} ({{ $assignment->staff->id_employee }})</p>
                    </div>

                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Status</label>
                        @if($assignment->status === 'pending')
                            <span class="px-3 py-1 bg-yellow-900/30 border border-yellow-500/50 rounded text-yellow-400 text-sm uppercase">Pending</span>
                        @elseif($assignment->status === 'in_progress')
                            <span class="px-3 py-1 bg-blue-900/30 border border-blue-500/50 rounded text-blue-400 text-sm uppercase">In Progress</span>
                        @else
                            <span class="px-3 py-1 bg-green-900/30 border border-green-500/50 rounded text-green-400 text-sm uppercase">Completed</span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Description</label>
                        <p class="text-white whitespace-pre-wrap">{{ $assignment->description ?? 'No description' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Created By</label>
                            <p class="text-white">{{ $assignment->creator->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Created At</label>
                            <p class="text-white">{{ $assignment->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>
