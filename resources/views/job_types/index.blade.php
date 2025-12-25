<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Job Types</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
        }

        .job-type-badge {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 20px;
        }

        @media (max-width: 576px) {
            h4 {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <div class="container py-4 py-md-5">
        <div class="card p-3 p-md-4 mx-auto" style="max-width: 720px;">

            <!-- Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <h4 class="fw-bold mb-0">🏷️ Kelola Job Types</h4>
                <!-- Tombol Kembali Pintar -->
                <a href="{{ auth()->user()->role->name == 'admin' ? route('admin.dashboard') : route('boss.dashboard') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
            </div>

            <!-- Form Tambah -->
            <form action="{{ route('job-types.store') }}" method="POST" class="row g-2 g-md-3 mb-4">
                @csrf

                <div class="col-12 col-md-7">
                    <input type="text" name="job_type_name" class="form-control"
                        placeholder="Nama Job Type (Misal: Wedding)" required>
                </div>

                <div class="col-6 col-md-3">
                    <input type="color" name="badge_color"
                        class="form-control form-control-color w-100"
                        value="#563d7c"
                        title="Pilih Warna Badge">
                </div>

                <div class="col-6 col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>

            <!-- List Job Types -->
            <ul class="list-group list-group-flush">
                @foreach($types as $type)
                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <span class="badge job-type-badge text-white"
                        style="background-color: {{ $type->badge_color }};">
                        {{ $type->job_type_name }}
                    </span>

                    <form action="{{ route('job-types.destroy', $type->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Hapus job type ini?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </li>
                @endforeach
            </ul>

        </div>
    </div>

</body>

</html>