<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <style>
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 55px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            user-select: none;
        }

        .toggle-password:hover {
            color: #000;
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">
            <img src="{{ asset('images/logo-izz.png') }}" class="kn-logo-login-form" alt="Logo Izzati" width="175">

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

                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" required>
                        <span class="toggle-password" id="toggleText" onclick="togglePassword()">See</span>
                    </div>
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                    Lupa password?
                </a>
            </div>


            <div style="margin-top: 20px; font-size: 12px; color: #aaa;">
                &copy; {{ date('Y') }} Izzati Computindo
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const text = document.getElementById('toggleText');

            if (pwd.type === 'password') {
                pwd.type = 'text';
                text.textContent = 'Hide';
            } else {
                pwd.type = 'password';
                text.textContent = 'See';
            }
        }
    </script>

</body>

</html>