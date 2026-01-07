<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="card shadow-sm p-4" style="width:100%; max-width:400px;">
    <h4 class="fw-bold mb-2 text-center">Lupa Password</h4>
    <p class="text-muted small text-center mb-4">
        Masukkan email yang terdaftar untuk reset password
    </p>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success small">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger small">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label small fw-bold">Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <button class="btn btn-primary w-100 fw-bold">
            Kirim Link Reset
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="small text-decoration-none">
            ← Kembali ke Login
        </a>
    </div>
</div>

</body>
</html>