<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Musicmen Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-[#0f0f0f] text-white min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg p-8 text-center">
            <img src="{{ asset('images/logo4.png') }}" alt="MUSICMEN" class="h-12 mx-auto mb-6">
            <h1 class="text-2xl font-bold mb-2">Verifikasi WhatsApp</h1>
            <p class="text-sm text-[#9a9a9a] mb-8">Masukkan 4 digit kode yang dikirim ke <br>
                <span class="text-white font-mono">{{ Session::get('pending_phone') }}</span>
            </p>

            <form method="POST" action="{{ route('member.verify.otp') }}">
                @csrf
                @if ($errors->any())
                    <p class="text-red-500 text-xs mb-4">{{ $errors->first() }}</p>
                @endif

                <input type="text" name="otp" maxlength="4" inputmode="numeric" required
                    class="w-full bg-[#0f0f0f] border border-[#4a4a4a] rounded-lg py-4 text-center text-3xl tracking-[0.5em] font-bold focus:border-white outline-none transition-all"
                    placeholder="----">

                <button type="submit"
                    class="w-full mt-6 bg-white text-black font-bold py-3 rounded-lg hover:bg-gray-200 transition-all uppercase">
                    Verifikasi Akun
                </button>
            </form>

            <p class="mt-8 text-xs text-[#6a6a6a]">Pastikan nomor WhatsApp Anda aktif untuk menerima pesan dari kami.
            </p>
        </div>
        <div class="mt-8 pt-6 border-t border-[#4a4a4a]">
            <p class="text-sm text-[#9a9a9a]">
                Tidak menerima kode?
            <form id="resendForm" action="{{ route('member.resend.otp') }}" method="POST" class="inline">
                @csrf
                <button type="submit" id="resendBtn"
                    class="text-white hover:underline font-bold disabled:opacity-50 transition-all">
                    Kirim Ulang (<span id="timer">60</span>s)
                </button>
            </form>
            </p>
        </div>
    </div>

    <script>
        let timeLeft = 60;
        const timerEl = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');

        resendBtn.disabled = true;

        const countdown = setInterval(() => {
            timeLeft--;
            if (timeLeft > 0) {
                timerEl.textContent = timeLeft;
            } else {
                clearInterval(countdown);
                resendBtn.disabled = false;
                // Ubah teks tombol saat waktu habis
                resendBtn.innerHTML = "Kirim Sekarang";
            }
        }, 1000);
    </script>
</body>

</html>
