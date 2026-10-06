<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members - Musicmen Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
                        <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl text-white mb-2">Members</h1>
                        <div class="h-px bg-gradient-to-r from-[#4a4a4a] via-[#6a6a6a] to-[#4a4a4a] w-full max-w-2xl">
                        </div>
                    </div>
                    <a href="{{ route('staff.members.create') }}"
                        class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add Member
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
                    <p class="text-sm text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Quick Visit Record -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 mb-6">
                <h2 class="text-lg text-white mb-4 uppercase tracking-wider">Quick Visit Record</h2>
                <form id="quickVisitForm" class="flex flex-col sm:flex-row gap-4">
                    <input type="text" id="quickMemberId"
                        class="flex-1 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                        placeholder="Scan atau ketik Member ID">
                    <input type="date" id="quickVisitDate" value="{{ date('Y-m-d') }}"
                        class="bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all">
                    <button type="button" onclick="recordQuickVisit()"
                        class="bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Record Visit
                    </button>
                </form>
                <div id="quickVisitResult" class="mt-4"></div>
            </div>

            <!-- Members Table -->
            <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-4 sm:p-6 lg:p-8 w-full">
                <div class="mb-4">
                    <form method="GET" action="{{ route('staff.members.index') }}" class="flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="flex-1 bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                            placeholder="Search by ID, Name, Phone, Email...">
                        <button type="submit"
                            class="bg-transparent border border-[#4a4a4a] text-white px-6 py-2 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('staff.members.index') }}"
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
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">ID
                                    Member</th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Nama
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Phone
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Email
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Visits
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Status
                                </th>
                                <th class="text-left py-3 px-4 text-sm text-[#9a9a9a] uppercase tracking-wider">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                                <tr class="border-b border-[#4a4a4a] hover:bg-[#2a2a2a] transition-colors">
                                    <td class="py-3 px-4 text-white text-sm">{{ $member->member_id }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $member->name }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $member->phone ?? '-' }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $member->email ?? '-' }}</td>
                                    <td class="py-3 px-4 text-white text-sm">{{ $member->visits_count }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        @php
                                            $rank = $member->rank ?? 'bronze';
                                            $status = $member->status ?? 'Bronze';

                                            $rankColors = [
                                                'bronze' => 'text-amber-600',
                                                'silver' => 'text-gray-300',
                                                'gold' => 'text-yellow-400',
                                                'diamond' => 'text-cyan-300',
                                                'ruby' => 'text-red-400',
                                            ];

                                            $rankBgColors = [
                                                'bronze' => 'bg-amber-900/20 border-amber-700',
                                                'silver' => 'bg-gray-800/20 border-gray-600',
                                                'gold' => 'bg-yellow-900/20 border-yellow-700',
                                                'diamond' => 'bg-cyan-900/20 border-cyan-700',
                                                'ruby' => 'bg-red-900/20 border-red-700',
                                            ];

                                            $color = $rankColors[$rank] ?? 'text-gray-400';
                                            $bgColor = $rankBgColors[$rank] ?? 'bg-gray-800/20 border-gray-600';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $bgColor }} {{ $color }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <button
                                                onclick="openVisitModal({{ $member->id }}, '{{ $member->member_id }}', '{{ $member->name }}')"
                                                class="text-blue-400 hover:text-blue-300 transition-colors"
                                                title="Record Visit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                            </button>
                                            @if ($member->phone)
                                                @php
                                                    // Format nomor untuk WhatsApp (hapus karakter non-numeric, jika dimulai dengan 0 ganti dengan 62)
                                                    $phone = preg_replace('/[^0-9]/', '', $member->phone);
                                                    if (substr($phone, 0, 1) == '0') {
                                                        $phone = '62' . substr($phone, 1);
                                                    } elseif (substr($phone, 0, 2) != '62') {
                                                        $phone = '62' . $phone;
                                                    }
                                                @endphp
                                                <button onclick="sendWelcomeWA({{ $member->id }}, this)"
                                                    class="text-green-400 hover:text-green-300 transition-colors"
                                                    title="Send Welcome WA">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 24 24" fill="currentColor">
                                                        <path
                                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                                    </svg>
                                                </button>
                                            @endif
                                            <a href="{{ route('staff.members.show', $member->id) }}"
                                                class="text-green-400 hover:text-green-300 transition-colors"
                                                title="View Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-[#9a9a9a]">No members found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($members->hasPages())
                    <div class="mt-6 flex items-center justify-center">
                        {{ $members->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    @include('components.admin-footer')

    <!-- Visit Modal -->
    <div id="visitModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center"
        style="display: none;">
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl text-white">Record Visit</h3>
                <button onclick="closeVisitModal()" class="text-white hover:text-[#9a9a9a]">&times;</button>
            </div>
            <form id="visitForm" method="POST">
                @csrf
                <input type="hidden" id="visitMemberId" name="member_id">
                <div class="mb-4">
                    <label class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Member</label>
                    <input type="text" id="visitMemberName"
                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white opacity-75 cursor-not-allowed"
                        disabled>
                </div>
                <div class="mb-4">
                    <label for="visit_date" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Visit
                        Date</label>
                    <input type="date" id="visit_date" name="visit_date" value="{{ date('Y-m-d') }}"
                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"
                        required>
                </div>
                <div class="mb-4">
                    <label for="notes" class="block text-sm text-[#9a9a9a] mb-2 uppercase tracking-wider">Notes
                        (Optional)</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#6a6a6a] transition-all"></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Record
                    </button>
                    <button type="button" onclick="closeVisitModal()"
                        class="flex-1 bg-transparent border border-[#4a4a4a] text-white px-6 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Quick Visit Record
        function recordQuickVisit() {
            const memberId = $('#quickMemberId').val().trim();
            const visitDate = $('#quickVisitDate').val();

            if (!memberId) {
                $('#quickVisitResult').html('<p class="text-red-400 text-sm">Please enter Member ID</p>');
                return;
            }

            $.ajax({
                url: '{{ route('staff.members.search', ':id') }}'.replace(':id', memberId),
                method: 'GET',
                success: function(member) {
                    if (member) {
                        $.ajax({
                            url: '{{ route('staff.members.visit', ':id') }}'.replace(':id', member.id),
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                visit_date: visitDate,
                                notes: 'Quick record'
                            },
                            success: function(response) {
                                $('#quickVisitResult').html(
                                    '<p class="text-green-400 text-sm">Visit recorded! Total: ' +
                                    (member.visit_count + 1) + '</p>');
                                $('#quickMemberId').val('');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            },
                            error: function() {
                                $('#quickVisitResult').html(
                                    '<p class="text-red-400 text-sm">Error recording visit</p>');
                            }
                        });
                    }
                },
                error: function() {
                    $('#quickVisitResult').html('<p class="text-red-400 text-sm">Member not found</p>');
                }
            });
        }

        // Visit Modal
        function openVisitModal(memberId, memberIdCode, memberName) {
            $('#visitMemberId').val(memberId);
            $('#visitMemberName').val(memberIdCode + ' - ' + memberName);
            $('#visitForm').attr('action', '{{ route('staff.members.visit', ':id') }}'.replace(':id', memberId));
            $('#visitModal').removeClass('hidden').css('display', 'flex');
        }

        function closeVisitModal() {
            $('#visitModal').addClass('hidden').css('display', 'none');
        }

        // Close modal on outside click
        $('#visitModal').on('click', function(e) {
            if (e.target === this) {
                closeVisitModal();
            }
        });

        function sendWelcomeWA(id, btn) {
            // 1. Langsung kunci tombol agar tidak diklik dua kali
            const originalContent = btn.innerHTML;
            btn.disabled = true;

            // Tampilkan loading kecil di tombol
            btn.innerHTML =
                '<svg class="animate-spin h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            // 2. Jalankan AJAX
            $.ajax({
                // Deteksi route otomatis (Admin/Staff)
                url: '{{ auth()->user()->role == 'admin' ? '/admin/members/' : '/staff/members/' }}' + id +
                    '/send-wa',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Tampilkan SweetAlert Sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Terkirim!',
                            text: 'Pesan Welcome WA berhasil dikirim ke member.',
                            background: '#1a1a1a',
                            color: '#fff',
                            confirmButtonColor: '#22c55e',
                            timer: 2000, // Hilang otomatis dalam 2 detik
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            background: '#1a1a1a',
                            color: '#fff'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Sistem',
                        text: 'Terjadi kesalahan koneksi.',
                        background: '#1a1a1a',
                        color: '#fff'
                    });
                },
                complete: function() {
                    // Kembalikan tombol ke semula
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            });
        }
    </script>
</body>

</html>
