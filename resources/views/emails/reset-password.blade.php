<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Izzati Scheduling</title>
</head>
<body style="margin:0;padding:0;background:#f5f6f8;font-family:Arial,Helvetica,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.05);">

                    <!-- HEADER -->
                    <tr>
                        <td align="center" style="padding:30px;background:#111827;">
                            <img src="{{ asset('images/logo-izz.png') }}" class="kn-logo-login-form" alt="Logo Izzati" width="125">
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding:30px;color:#333;">
                            <h2 style="margin-top:0;">Halo {{ $user->name }},</h2>

                            <p>Kami menerima permintaan untuk mereset password akun Anda di <strong>Izzati Scheduling</strong>.</p>

                            <p style="text-align:center;margin:40px 0;">
                                <a href="{{ $url }}"
                                   style="background:#2563eb;color:#fff;text-decoration:none;padding:14px 28px;border-radius:6px;font-weight:bold;display:inline-block;">
                                    Reset Password
                                </a>
                            </p>

                            <p>Link ini hanya berlaku selama <strong>60 menit</strong>.</p>

                            <p>Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.</p>

                            <p style="margin-top:30px;">
                                Salam,<br>
                                <strong>Tim Izzati Scheduling</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding:20px;background:#f1f5f9;text-align:center;font-size:12px;color:#6b7280;">
                            © {{ date('Y') }} Izzati Scheduling. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>