<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-blue:       #0077B6;
            --brand-yellow:     #D98604;
            --brand-blue-dark:  #005f8f;
        }

        body {
            background: linear-gradient(135deg, #f8faff 0%, #f0f6ff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
            max-width: 580px;
            margin: 0 auto;
        }

        .card-header {
            background-color: var(--brand-blue);
            color: white;
            padding: 1.75rem;
            text-align: center;
            border-radius: 16px 16px 0 0;
        }

        .form-label {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.65rem 1rem;
            border: 1px solid #d1d5db;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 119, 182, 0.15);
        }

        .input-group-text {
            background-color: #f3f4f6;
            border-radius: 10px 0 0 10px;
            border: 1px solid #d1d5db;
            border-right: none;
            color: var(--brand-blue);
        }

        .btn-save {
            background-color: var(--brand-blue);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            color: white;
            transition: all 0.25s;
        }

        .btn-save:hover {
            background-color: var(--brand-blue-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 119, 182, 0.25);
        }

        .btn-outline-secondary {
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 500;
        }

        .btn-outline-secondary:hover {
            background-color: var(--brand-yellow);
            border-color: var(--brand-yellow);
            color: white;
        }

        .text-brand {
            color: var(--brand-blue);
        }

        @media (max-width: 576px) {
            .card {
                margin: 1rem;
                border-radius: 12px;
            }
            
            .card-header {
                padding: 1.5rem;
            }

            .d-flex.gap-2 {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container py-5 py-md-5">
    <div class="card shadow-lg">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-center gap-3">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; color: var(--brand-blue);">
                    <i class="bi bi-pencil-square fs-4"></i>
                </div>
                <h4 class="mb-0 fw-semibold">Edit User: {{ $user->name }}</h4>
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">No HP / WhatsApp</label>
                    <div class="input-group">
                        <span class="input-group-text">+62</span>
                        <input type="text" name="phone_number" class="form-control" value="{{ $user->phone_number }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password Baru <span class="text-muted small">(kosongkan jika tidak ingin mengganti)</span></label>
                    <input type="text" name="password" class="form-control" placeholder="Isi hanya jika ingin ganti password">
                    <small class="text-muted d-block mt-1">Minimal 8 karakter, disarankan campur huruf & angka</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Sistem Gaji (Payday)</label>
                    <select name="payday" class="form-select">
                        <option value="monthly" {{ $user->payday == 'monthly' ? 'selected' : '' }}>Monthly (Bulanan)</option>
                        <option value="weekly" {{ $user->payday == 'weekly' ? 'selected' : '' }}>Weekly (Mingguan)</option>
                    </select>
                </div>

                <div class="d-flex gap-3 mt-5">
                    <button type="submit" class="btn btn-save flex-grow-1">
                        <i class="bi bi-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary flex-grow-1 text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>