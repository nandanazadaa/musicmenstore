<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Checkup - Musicmen Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0a0a0a;
        }

        .serif-font {
            font-family: 'serif';
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }

        /* Modal Transitions */
        #checkupModal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            padding: 1rem;
        }

        #checkupModal.active {
            display: flex;
            opacity: 1;
        }

        #modalBox {
            transform: scale(0.95) translateY(20px);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            width: 100%;
            max-width: 800px;
        }

        #checkupModal.active #modalBox {
            transform: scale(1) translateY(0);
        }

        .label-technical {
            font-size: 9px;
            font-weight: 700;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .value-technical {
            font-size: 13px;
            color: #fff;
            font-weight: 500;
        }

        .bg-glass {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Mobile specific adjustments */
        @media (max-width: 640px) {
            .serif-font {
                font-size: 1.75rem !important;
            }

            #modalBox {
                border-radius: 1.5rem !important;
            }

            .px-responsive {
                padding-left: 1.25rem !important;
                padding-right: 1.25rem !important;
            }

            .value-technical {
                font-size: 12px;
            }
        }
    </style>
</head>

<body class="text-white flex flex-col min-h-screen overflow-x-hidden">
    @include('components.admin-sidebar')
    @include('components.admin-navbar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        <div class="w-full px-4 sm:px-6 py-8 sm:py-10 max-w-[1400px] mx-auto">

            @if (session('success'))
                <div
                    class="mb-6 px-4 py-3 bg-green-900/20 border border-green-600/30 rounded-xl text-green-400 text-xs sm:text-sm flex items-center gap-2">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
                <div>
                    <h1 class="serif-font text-3xl md:text-5xl text-white tracking-tight mb-2">Inventory Checkup</h1>
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-0.5 bg-yellow-600"></span>
                        <p class="text-[#666] text-[10px] sm:text-xs font-medium uppercase tracking-widest">Quality
                            Control</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#141414] border border-[#222] rounded-2xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="bg-[#0a0a0a] text-[#555] uppercase text-[9px] sm:text-[10px] font-bold tracking-[0.2em] border-b border-[#222]">
                                <th class="px-5 sm:px-8 py-4 sm:py-5">Instrument</th>
                                <th class="hidden sm:table-cell px-8 py-5">Serial Number</th>
                                <th class="hidden md:table-cell px-8 py-5">Origin / Year</th>
                                <th class="px-5 sm:px-8 py-4 sm:py-5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1d1d1d]">
                            @forelse($products as $p)
                                <tr class="hover:bg-[#1a1a1a]/50 transition-all">
                                    <td class="px-5 sm:px-8 py-5">
                                        <div class="text-white font-bold text-sm sm:text-base uppercase tracking-tight">
                                            {{ $p->nama_barang }}</div>
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 mt-1">
                                            <span
                                                class="text-[9px] text-blue-400 uppercase font-bold">{{ $p->type }}</span>
                                            <span
                                                class="sm:hidden text-[10px] text-[#555] font-mono">{{ $p->nomor_seri ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td
                                        class="hidden sm:table-cell px-8 py-5 text-white font-mono text-sm tracking-wider">
                                        {{ $p->nomor_seri ?? '—' }}</td>
                                    <td class="hidden md:table-cell px-8 py-5 text-sm text-[#888]">
                                        {{ $p->tempat_pembuatan ?? '—' }} ({{ $p->tahun_pembuatan ?? '?' }})</td>
                                    <td class="px-5 sm:px-8 py-5 text-right">
                                        @if ($p->input_system == 'Sudah')
                                            <div class="flex flex-col items-end gap-1">
                                                <span
                                                    class="bg-green-900/20 text-green-500 border border-green-500/30 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">
                                                    ● Completed
                                                </span>
                                                <span
                                                    class="text-[8px] text-[#444] italic uppercase tracking-tighter">Inventory
                                                    Locked</span>
                                            </div>
                                        @else
                                            <button onclick="openCheckup({{ $p->id }})"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 sm:px-5 py-2 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all animate-pulse">
                                                Check Now
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="py-20 text-center text-[#444] uppercase tracking-widest text-xs font-bold">
                                        No instruments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $products->links() }}</div>
        </div>
    </main>

    <div id="checkupModal">
        <div id="modalOverlay" class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="closeCheckup()"></div>
        <div id="modalBox"
            class="relative z-10 max-h-[90vh] overflow-hidden flex flex-col bg-[#0f0f0f] border border-white/10 rounded-[1.5rem] sm:rounded-[2rem] shadow-2xl">

            <div
                class="px-6 sm:px-8 py-4 sm:py-6 flex items-center justify-between border-b border-white/5 bg-white/[0.01]">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-yellow-600/20 flex items-center justify-center text-yellow-500">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[8px] sm:text-[10px] font-bold uppercase tracking-[0.2em] text-[#555]">Process
                            Verification</p>
                        <h2 class="text-base sm:text-xl font-bold text-white uppercase tracking-tight">Instrument Audit
                        </h2>
                    </div>
                </div>
                <button onclick="closeCheckup()" class="text-[#444] hover:text-white transition-all text-xl">✕</button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar px-6 sm:px-8 py-6 sm:py-8">
                <div id="modalLoading" class="py-12 text-center">
                    <div
                        class="inline-block w-6 h-6 border-2 border-yellow-600 border-t-transparent rounded-full animate-spin">
                    </div>
                </div>

                <div id="modalContent" class="hidden space-y-6 sm:space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 space-y-5">
                            <div>
                                <span class="label-technical">Nomenclature</span>
                                <div id="mc-nama"
                                    class="text-xl sm:text-2xl font-bold text-white uppercase mt-1 leading-tight"></div>
                                <div id="mc-type-badge"
                                    class="mt-2 inline-block px-2 py-0.5 rounded bg-blue-900/30 text-blue-400 text-[8px] font-bold uppercase border border-blue-500/20">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div><span class="label-technical">Serial Number</span>
                                    <div id="mc-seri" class="value-technical mt-0.5 font-mono text-yellow-600"></div>
                                </div>
                                <div><span class="label-technical">Origin / Year</span>
                                    <div id="mc-origin-year" class="value-technical mt-0.5"></div>
                                </div>
                                <div><span class="label-technical">Finish Color</span>
                                    <div id="mc-warna" class="value-technical mt-0.5 italic"></div>
                                </div>
                                <div><span class="label-technical">Received Date</span>
                                    <div id="mc-tanggal" class="value-technical mt-0.5 opacity-60"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-glass rounded-2xl p-4 sm:p-5 border border-white/5 h-fit">
                            <span class="label-technical mb-3 block">Commission Log</span>
                            <div id="mc-fee-container" class="space-y-2"></div>
                        </div>
                    </div>

                    <div class="bg-[#151515] rounded-2xl sm:rounded-3xl p-5 sm:p-8 border border-white/5">
                        <form id="checkupForm" method="POST" action="">
                            @csrf @method('PATCH')

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                <div class="space-y-2">
                                    <label class="label-technical">Unit Condition</label>
                                    <select name="kondisi" id="inp-kondisi" required
                                        class="w-full bg-[#0a0a0a] border border-[#222] rounded-xl px-4 py-3 text-white focus:border-yellow-600 outline-none text-xs">
                                        <option value="new">NEW (MINT)</option>
                                        <option value="great">GREAT (EXCELLENT)</option>
                                        <option value="good">GOOD (USED)</option>
                                        <option value="need service">NEED SERVICE</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="label-technical">Setup Status</label>
                                    <select name="setting_setup" id="inp-setting" required
                                        class="w-full bg-[#0a0a0a] border border-[#222] rounded-xl px-4 py-3 text-white focus:border-yellow-600 outline-none text-xs font-bold">
                                        <option value="Pending">NOT YET</option>
                                        <option value="Done">DONE & READY</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                                <div class="space-y-2">
                                    <label class="label-technical text-green-500">Price Tag & Hologram</label>
                                    <select name="price_hologram" id="inp-hologram" required
                                        class="w-full bg-[#0a0a0a] border border-green-900/30 rounded-xl px-4 py-3 text-white focus:border-green-500 outline-none text-xs font-bold">
                                        <option value="Pending">NOT YET</option>
                                        <option value="Done">DONE (PHYSICAL TAG)</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="label-technical text-purple-500">Cashier Application</label>
                                    <select name="input_cashier" id="inp-cashier" required
                                        class="w-full bg-[#0a0a0a] border border-purple-900/30 rounded-xl px-4 py-3 text-white focus:border-purple-500 outline-none text-xs font-bold">
                                        <option value="Pending">NOT YET</option>
                                        <option value="Done">DONE (INPUTTED)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-8">
                                <label class="label-technical text-orange-400">Inventory Status (Finalize)</label>
                                <select name="input_system" id="inp-system" required
                                    class="w-full bg-[#111] border border-orange-900/30 rounded-xl px-4 py-3 text-orange-500 focus:border-orange-500 outline-none text-xs font-black">
                                    <option value="Belum">PENDING (STILL DRAFT)</option>
                                    <option value="Sudah">COMPLETED (LOCK INSTRUMENT)</option>
                                </select>
                                <p class="text-[8px] text-[#444] mt-2 italic px-1">*Jika diset ke "COMPLETED", data
                                    unit tidak bisa diubah kembali oleh staff.</p>
                            </div>

                            <button type="submit"
                                class="w-full bg-white hover:bg-yellow-500 text-black font-black py-4 sm:py-5 rounded-xl sm:rounded-2xl uppercase tracking-[0.2em] text-[10px] sm:text-xs transition-all">
                                Finalize & Lock Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.admin-footer')

    <script>
        const productsData = {
            @foreach ($products as $p)
                @php
                    $feeRaw = [];
                    foreach ($p->additionalPics as $ap) {
                        $feeRaw[] = ['name' => $ap->pic_name, 'amount' => number_format($ap->fee_amount, 0, ',', '.')];
                    }
                @endphp
                    "{{ $p->id }}": {
                        nama: @json($p->nama_barang),
                        seri: @json($p->nomor_seri ?? '—'),
                        origin: @json($p->tempat_pembuatan ?? '—'),
                        tahun: @json($p->tahun_pembuatan ?? '—'),
                        warna: @json($p->warna ?? '—'),
                        type: @json($p->type ?? '—'),
                        tanggal: @json($p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('d M Y') : '—'),
                        kondisi: @json(strtolower($p->kondisi)),
                        setup: @json($p->setting_setup ?? 'Pending'),
                        hologram: @json($p->price_hologram ?? 'Pending'),
                        cashier: @json($p->input_cashier ?? 'Pending'),
                        system: @json($p->input_system ?? 'Belum'),
                        fees: @json($feeRaw)
                    },
            @endforeach
        };

        function openCheckup(id) {
            const modal = document.getElementById('checkupModal');
            const d = productsData[id];
            if (!d) return;

            modal.classList.add('active');
            document.getElementById('modalLoading').style.display = 'block';
            document.getElementById('modalContent').classList.add('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                document.getElementById('mc-nama').innerText = d.nama;
                document.getElementById('mc-type-badge').innerText = d.type;
                document.getElementById('mc-seri').innerText = d.seri;
                document.getElementById('mc-origin-year').innerText = `${d.origin} / ${d.tahun}`;
                document.getElementById('mc-warna').innerText = d.warna;
                document.getElementById('mc-tanggal').innerText = d.tanggal;

                const feeContainer = document.getElementById('mc-fee-container');
                feeContainer.innerHTML = d.fees.length ? d.fees.map(f => `
        <div class="flex justify-between items-center bg-black/20 rounded-lg p-2.5">
            <span class="text-[8px] text-white/50 uppercase font-bold truncate mr-2">${f.name}</span>
            <span class="text-[10px] text-yellow-500 font-mono">Rp${f.amount}</span>
        </div>`).join('') : '<p class="text-[#444] text-[9px] text-center italic">No comm.</p>';

                document.getElementById('inp-kondisi').value = d.kondisi || 'good';
                document.getElementById('inp-setting').value = d.setup;
                document.getElementById('inp-hologram').value = d.hologram; // ← PERBAIKAN
                document.getElementById('inp-cashier').value = d.cashier; // ← PERBAIKAN
                document.getElementById('inp-system').value = d.system;

                document.getElementById('checkupForm').action = `{{ url('staff/inventory') }}/${id}/checkup`;
                document.getElementById('modalLoading').style.display = 'none';
                document.getElementById('modalContent').classList.remove('hidden');
            }, 300);
        }

        function closeCheckup() {
            document.getElementById('checkupModal').classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>
</body>

</html>
