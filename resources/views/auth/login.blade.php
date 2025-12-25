<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <!-- Memanggil CSS yang kita buat tadi di public/css -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Logo Sederhana -->
            <img src="{{ asset('images/logo-izz.png') }}" class="kn-logo-login-form" alt="Logo Izzati" width="175">
            <!-- <div class="brand-logo">Daily<span>Activity</span></div> -->

            <!-- <h3 class="kn-welcome-text">Selamat Datang</h3>
            <p class="subtitle">Silakan login untuk memulai pekerjaan.</p> -->

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                @error('email')
                <div class="error-msg">{{ $message }}</div>
                @enderror

                <!-- Email -->
                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email"
                        class="@error('email') is-invalid @enderror"
                        placeholder="user@example.com"
                        value="{{ old('email') }}" required autofocus>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" required>
                </div>

                <!-- Tombol Login -->
                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <div style="margin-top: 20px; font-size: 12px; color: #aaa;">
                &copy; {{ date('Y') }} Izzati Computindo
            </div>
        </div>
    </div>

</body>

</html>