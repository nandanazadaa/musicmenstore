<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Request Leave - Musicmen</title>
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
                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-4">Request Leave</h1>
                <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl"></div>
            </div>

            <!-- Form -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 lg:p-8">
                <form method="POST" action="{{ route('staff.leaves.store') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
                            <ul class="text-sm text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Start Date <span class="text-red-400">*</span></label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" 
                                       class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                       min="{{ date('Y-m-d') }}" required>
                                <p class="text-xs text-[#6a6a6a] mt-1">Leave start date</p>
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">End Date <span class="text-red-400">*</span></label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" 
                                       class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                       min="{{ date('Y-m-d') }}" required>
                                <p class="text-xs text-[#6a6a6a] mt-1">Leave end date</p>
                            </div>
                        </div>

                        <div>
                            <label for="reason" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Reason <span class="text-red-400">*</span></label>
                            <textarea id="reason" name="reason" rows="5" 
                                      class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                                      placeholder="Please provide a detailed reason for your leave request..." required>{{ old('reason') }}</textarea>
                            <p class="text-xs text-[#6a6a6a] mt-1">Minimum 10 characters, maximum 1000 characters</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <button type="submit" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all uppercase tracking-wider">
                            Submit Request
                        </button>
                        <a href="{{ route('staff.leaves.index') }}" 
                           class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <script>
        // Auto-update end_date minimum based on start_date
        document.getElementById('start_date').addEventListener('change', function() {
            const endDateInput = document.getElementById('end_date');
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });
    </script>
</body>
</html>

