<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users</title>
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
        }

        .card-header {
            background-color: var(--brand-blue);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem 1.75rem;
        }

        .table {
            margin-bottom: 0;
            border-radius: 0 0 16px 16px;
            overflow: hidden;
        }

        .table thead {
            background-color: #f8f9fa;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.78rem;
            letter-spacing: 0.5px;
            color: #4b5563;
            border-bottom: 2px solid #e5e7eb;
        }

        tr {
            transition: all 0.2s ease;
        }

        tr:hover {
            background-color: rgba(0, 119, 182, 0.05); /* biru sangat tipis */
            transform: translateY(-1px);
        }

        .col-name {
            min-width: 140px;
            max-width: 45vw;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .col-email {
            min-width: 120px;
            max-width: 40vw;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .badge-role {
            font-weight: 500;
            padding: 0.45em 0.9em;
            border-radius: 1rem;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 119, 182, 0.2);
        }

        .btn-primary {
            background-color: var(--brand-blue);
            border: none;
            color: white;
            transition: all 0.25s;
        }

        .btn-primary:hover {
            background-color: var(--brand-blue-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 119, 182, 0.25);
        }

        .btn-outline-light {
            border-color: white;
            color: white;
        }

        .btn-outline-light:hover {
            background-color: rgba(255,255,255,0.2);
        }

        .icon-circle {
            background: white;
            color: var(--brand-blue);
        }

        @media (max-width: 768px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .table-responsive {
                font-size: 0.92rem;
            }

            .col-name {
                min-width: 120px;
            }

            .col-email {
                min-width: 100px;
            }

            .action-btn {
                width: 42px;
                height: 42px;
            }

            .col-name:hover,
            .col-email:hover {
                white-space: normal;
                overflow: visible;
                background: #f8f9fa;
                position: relative;
                z-index: 1;
            }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-circle rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <h4 class="mb-0 fw-semibold">Manajemen Users (Crew & Editor)</h4>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('boss.dashboard') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah User
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="col-name">Nama</th>
                        <th class="col-email">Email</th>
                        <th>Role</th>
                        <th>Payday</th>
                        <th class="text-center" width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="fw-medium col-name" title="{{ $user->name }}">{{ $user->name }}</td>
                        <td class="col-email" title="{{ $user->email }}"><small class="text-muted">{{ $user->email }}</small></td>
                        <td>
                            @php
                            $badgeColor = match($user->role->name) {
                                'boss'    => 'bg-danger',
                                'admin'   => 'bg-primary',
                                'crew'    => 'bg-success',
                                'editor'  => 'bg-warning text-dark',
                                default   => 'bg-secondary'
                            };
                            @endphp
                            <span class="badge badge-role {{ $badgeColor }} fs-6">
                                {{ ucfirst($user->role->name) }}
                            </span>
                        </td>
                        <td>
                            @if($user->payday == 'weekly')
                                <span class="badge bg-info text-dark fs-6">Mingguan</span>
                            @else
                                <span class="badge bg-secondary fs-6">Bulanan</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" 
                                   class="btn btn-sm btn-warning action-btn"
                                   title="Edit">
                                    <i class="bi bi-pencil fs-5"></i>
                                </a>

                                @if(auth()->id() != $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger action-btn"
                                            title="Hapus">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-people display-1 opacity-25"></i>
            <p class="mt-3">Belum ada data user saat ini</p>
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>