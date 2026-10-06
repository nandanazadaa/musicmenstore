@php
    $sweetAlert = null;

    if (session('success')) {
        $sweetAlert = [
            'icon' => 'success',
            'title' => 'Berhasil',
            'html' => e(session('success')),
        ];
    } elseif (session('error')) {
        $sweetAlert = [
            'icon' => 'error',
            'title' => 'Data belum bisa disimpan',
            'html' => e(session('error')),
        ];
    } elseif ($errors->any()) {
        $sweetAlert = [
            'icon' => 'error',
            'title' => 'Data belum bisa disimpan',
            'html' => '<ul style="text-align:left;margin:0;padding-left:1.25rem;">'
                .collect($errors->all('<li>:message</li>'))->implode('')
                .'</ul>',
        ];
    }
@endphp

@if (session('success'))
    <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 rounded-lg text-green-400 text-sm">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg text-red-400 text-sm">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 rounded-lg text-red-300 text-sm">
        <p class="font-semibold text-red-200 mb-2">Data belum bisa disimpan:</p>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($sweetAlert)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showAlert = () => {
                Swal.fire({
                    icon: @json($sweetAlert['icon']),
                    title: @json($sweetAlert['title']),
                    html: @json($sweetAlert['html']),
                    confirmButtonText: 'OK',
                    confirmButtonColor: @json($sweetAlert['icon'] === 'success' ? '#16a34a' : '#dc2626'),
                    background: '#1a1a1a',
                    color: '#ffffff',
                });
            };

            if (window.Swal) {
                showAlert();
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
            script.onload = showAlert;
            document.head.appendChild(script);
        });
    </script>
@endif
