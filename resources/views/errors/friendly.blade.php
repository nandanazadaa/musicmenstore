<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman belum bisa dimuat</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #0a0a0a;
            color: #ffffff;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .box {
            width: min(92vw, 520px);
            border: 1px solid #2a2a2a;
            background: #111111;
            padding: 28px;
            text-align: center;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 24px;
        }

        p {
            margin: 0;
            color: #b4b4b4;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <main class="box">
        <h1>Halaman belum bisa dimuat</h1>
        <p>{{ $message ?? 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.' }}</p>
    </main>
</body>

</html>
