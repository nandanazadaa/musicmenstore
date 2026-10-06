<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Leave Request Details - Musicmen</title>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Leave Request Details</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
                    </div>
                    <a href="{{ route('staff.leaves.index') }}" 
                       class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Back
                    </a>
                </div>
            </div>

            <!-- Leave Request Details -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Status</label>
                        <span class="px-3 py-1 {{ $leaveRequest->status_badge }} border rounded text-sm uppercase">
                            {{ $leaveRequest->status_text }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Start Date</label>
                            <p class="text-white">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">End Date</label>
                            <p class="text-white">{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Total Days</label>
                        <p class="text-white text-lg">{{ $leaveRequest->total_days }} day(s)</p>
                    </div>

                    <div>
                        <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Reason</label>
                        <p class="text-white whitespace-pre-wrap">{{ $leaveRequest->reason }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Submitted At</label>
                            <p class="text-white">{{ $leaveRequest->created_at->format('d M Y H:i') }}</p>
                        </div>
                        @if($leaveRequest->approved_at)
                            <div>
                                <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Processed At</label>
                                <p class="text-white">{{ $leaveRequest->approved_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($leaveRequest->approver)
                        <div>
                            <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Processed By</label>
                            <p class="text-white">{{ $leaveRequest->approver->name }}</p>
                        </div>
                    @endif

                    @if($leaveRequest->rejection_reason)
                        <div class="p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                            <label class="block text-sm text-red-400 mb-2 uppercase tracking-wider">Rejection Reason</label>
                            <p class="text-red-300">{{ $leaveRequest->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @include('components.admin-footer')
</body>
</html>

