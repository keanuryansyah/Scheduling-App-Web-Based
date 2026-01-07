<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Income User</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
        }

        /* === TABLE FIX === */
        .table-nowrap th,
        .table-nowrap td {
            white-space: nowrap;
        }

        .table-min-width {
            min-width: 950px;
        }

        .table thead th {
            background: #f1f5f9;
            font-size: 12px;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #64748b;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .badge-soft {
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
        }

        .badge-boss {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-admin {
            background: #e0f2fe;
            color: #075985;
        }

        .badge-editor {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-crew {
            background: #dcfce7;
            color: #166534;
        }

        .input-group-custom {
            width: 240px;
        }
    </style>
</head>

<body>

    <div class="container py-5">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                @if(auth()->user()->role_id == 1)
                <h3 class="fw-bold mb-1">Kelola Income User</h3>
                <p class="text-muted small mb-0">Atur saldo dan lihat performa tim.</p>
                @else
                <h3 class="fw-bold mb-1">Lihat Detail Job User</h3>
                <p class="text-muted small mb-0">lihat performa tim.</p>
                @endif
            </div>
            <a href="{{ auth()->user()->role->name == 'admin' ? route('admin.dashboard') : route('boss.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali Dashboard
            </a>
        </div>
        <!-- FILTER -->
        <form method="GET" class="row g-2 mb-4">
            <!-- FROM DATE -->
            <div class="col-md-2">
                <input type="date" name="start_date" class="form-control form-control-sm"
                    value="{{ request('start_date') }}" onchange="this.form.submit()">
            </div>

            <!-- TO DATE -->
            <div class="col-md-2">
                <input type="date" name="end_date" class="form-control form-control-sm"
                    value="{{ request('end_date') }}" onchange="this.form.submit()">
            </div>

            <!-- SELECT MONTH -->
            <div class="col-md-2">
                <select name="month" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endfor
                </select>
            </div>

            <!-- SELECT YEAR -->
            <div class="col-md-2">
                <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                    @for($y = now()->year; $y >= 2022; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                    @endfor
                </select>
            </div>
            <a href="{{ route('boss.income.index') }}" class="btn btn-outline-secondary btn-sm col-md-2" title="Reset filter">
                <i class="bi bi-arrow-clockwise"></i>
            </a>

        </form>

        <canvas id="incomeChart" height="120"></canvas>



        <!-- TABLE -->
        <div class="card card-custom mt-4">
            <div class="table-responsive">
                <table class="table table-nowrap table-min-width align-middle mb-0">
                    <thead>
                        <tr>
                            <th>User & Role</th>
                            @if(auth()->user()->role_id == 1)
                            <th>Update Saldo (Income)</th>
                            @endif
                            <th>Statistik & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <!-- USER -->
                            <td>
                                <div class="user-info">
                                    <div class="avatar-circle">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        @php
                                        $roleClass = match($user->role->name) {
                                        'boss' => 'badge-boss',
                                        'admin' => 'badge-admin',
                                        'editor' => 'badge-editor',
                                        'crew' => 'badge-crew',
                                        default => 'bg-secondary text-white'
                                        };
                                        @endphp
                                        <span class="badge badge-soft {{ $roleClass }}">
                                            {{ ucfirst($user->role->name) }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- INCOME -->
                            @if(auth()->user()->role_id == 1)

                            <td>
                                <form action="{{ route('boss.income.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="input-group input-group-sm input-group-custom">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="income" class="form-control fw-semibold"
                                            value="{{ $user->income ?? 0 }}" min="0">
                                        <button class="btn btn-primary">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </div>
                                    <div class="form-text small">Klik centang untuk update manual</div>
                                </form>
                            </td>

                            @endif

                            <!-- ACTION -->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ route('boss.income.detail', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail Job
                                    </a>

                                    @if($user->jobCount > 0)
                                    <span class="text-muted small fw-semibold">
                                        {{ $user->jobCount }} Job
                                    </span>
                                    @else
                                    <span class="text-muted small fst-italic">Belum ada job</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
const ctx = document.getElementById('incomeChart');
const labels = @json($chartData->pluck('name'));
const percentages = @json($chartData->pluck('percentage'));

// Buat array warna untuk tiap bar
const barColors = [
    '#3b82f6', // biru
    '#ef4444', // merah
    '#f59e0b', // kuning/orange
    '#10b981', // hijau
    '#8b5cf6', // ungu
    '#ec4899', // pink
    '#0ea5e9', // cyan
    '#f43f5e', // rose
    '#f97316', // amber
    '#14b8a6', // teal
]; 

// Jika data lebih banyak dari warna, ulangi warnanya
const colors = percentages.map((_, i) => barColors[i % barColors.length]);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Income (%)',
            data: percentages,
            backgroundColor: colors, // pakai array warna
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return percentages[context.dataIndex] + '%';
                    }
                }
            },
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: value => value + '%'
                }
            }
        }
    }
});
</script>




</body>

</html>