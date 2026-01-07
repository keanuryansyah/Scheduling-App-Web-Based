<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menghubungi Crew...</title>

    <script>
        setTimeout(() => {
            window.location.href = "{{ $waLink }}";
        }, 800);
    </script>
</head>

<body style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif">
    <div style="text-align:center">
        <h3>Menghubungi Crew via WhatsApp…</h3>
        <p>Jika tidak terbuka otomatis, <a href="{{ $waLink }}">klik di sini</a></p>
    </div>
</body>
</html>
