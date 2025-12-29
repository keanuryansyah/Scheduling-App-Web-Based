<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Fonts & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .navbar-admin {
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        /* CARD CLICKABLE (UNTUK MEMBUKA POPUP) */
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 20px;
            color: white;
            transition: transform 0.2s;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card.clickable {
            cursor: pointer;
        }

        .stat-card.clickable:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .bg-gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .bg-gradient-orange {
            background: linear-gradient(135deg, #f97316 0%, #d97706 100%);
        }

        .bg-red {
            background: #ef4444;
        }

        .card-table {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .table thead th,
        .table tbody td {
            white-space: nowrap;
            vertical-align: middle;
            padding: 12px 16px;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .avatar-circle {
            width: 30px;
            height: 30px;
            background-color: #e2e8f0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            color: #475569;
        }

        /* TABS STYLE */
        .nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 10px 20px;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background-color: transparent;
            border-bottom: 3px solid #0d6efd;
        }

        /* Mobile Optimization */
        @media (max-width: 768px) {
            .stat-card h2 {
                font-size: 1.5rem;
            }

            .input-group,
            .form-select {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR ADMIN -->
    <nav class="navbar navbar-expand-lg navbar-admin mb-4 py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="#">
                <i class="bi bi-shield-lock-fill me-2"></i> Admin<span class="text-dark">Panel</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarContent">
                <div class="d-flex flex-column flex-lg-row gap-3 ms-auto align-items-lg-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none fw-bold text-dark">Dashboard</a>
                    <a href="{{ route('job-types.index') }}" class="text-decoration-none text-secondary">Job Types</a>
                    <div class="vr d-none d-lg-block mx-2"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-bold w-100 w-lg-auto">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <!-- FILTER BAR -->
        <div class="card border-0 shadow-sm mb-4 p-3 bg-white rounded-3">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div class="w-100 w-md-auto">
                    <h4 class="fw-bold text-dark mb-1">Operasional Jadwal</h4>
                    <p class="text-muted mb-0 small">Periode: <strong>{{ $judulPeriode }}</strong></p>
                </div>

                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2 w-100 w-lg-auto">
                    <select name="job_type" class="form-select form-select-sm" style="min-width: 130px;" onchange="storeTabAndSubmit(this.form)">
                        <option value="">Semua Tipe</option>
                        @foreach($allJobTypes as $type)
                        <option value="{{ $type->id }}" {{ request('job_type') == $type->id ? 'selected' : '' }}>{{ $type->job_type_name }}</option>
                        @endforeach
                    </select>

                    <div class="input-group input-group-sm" style="width: auto; min-width: 200px;">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" onchange="document.getElementsByName('month')[0].value=''; storeTabAndSubmit(this.form)">
                        <span class="input-group-text bg-white border-start-0 border-end-0">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" onchange="document.getElementsByName('month')[0].value=''; storeTabAndSubmit(this.form)">
                    </div>

                    <select name="month" class="form-select form-select-sm" style="width: 110px;" onchange="document.getElementsByName('start_date')[0].value=''; document.getElementsByName('end_date')[0].value=''; storeTabAndSubmit(this.form)">
                        <option value="">- Bulan -</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endfor
                    </select>

                    <select name="year" class="form-select form-select-sm" style="width: 80px;" onchange="storeTabAndSubmit(this.form)">
                        @for($y = date('Y') + 1; $y >= 2025; $y--)
                        <option value="{{ $y }}" {{ (request('year') ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light border" title="Reset" onclick="localStorage.removeItem('activeJobTab')"><i class="bi bi-arrow-counterclockwise"></i></a>
                </form>
            </div>
        </div>

        <!-- STATISTIK (TANPA UANG) -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card bg-gradient-purple">
                    <h6 class="text-uppercase small fw-bold opacity-75">TOTAL JOB</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_jobs'] }}</h2>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card bg-gradient-blue">
                    <h6 class="text-uppercase small fw-bold opacity-75">TERJADWAL</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['scheduled'] }}</h2>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card bg-gradient-orange">
                    <h6 class="text-uppercase small fw-bold opacity-75">SEDANG JALAN</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['ongoing'] }}</h2>
                </div>
            </div>

            <!-- KARTU MERAH: TOMBOL PEMBUKA SIDEBAR -->
            <div class="col-6 col-md-3">
                <div class="stat-card bg-red clickable" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTagihan">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-uppercase small fw-bold opacity-75">TAGIHAN & KONFIRMASI</h6>
                        <i class="bi bi-chevron-right opacity-50"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $stats['unpaid_jobs'] }}</h2>
                    <small class="opacity-75" style="font-size: 0.7rem;">Klik untuk proses</small>
                </div>
            </div>
        </div>

        <!-- TABEL FULL WIDTH (COL-12) -->
        <div class="row">
            <div class="col-12">
                <div class="card card-table overflow-hidden">
                    <div class="card-header bg-white p-0 border-bottom">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pe-3">
                            <!-- Nav Tabs -->
                            <ul class="nav nav-tabs w-100 w-md-auto border-0" id="jobTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#tab-active">
                                        🚀 Aktif
                                        <span class="badge bg-primary ms-1">
                                            {{ $todaysJobs->whereIn('status', ['scheduled','otw','arrived','ongoing'])->count() }}
                                        </span>
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" id="waiting-editor-tab" data-bs-toggle="tab" data-bs-target="#tab-waiting-editor">
                                        ⏳ Status Editor
                                        <span class="badge bg-warning text-dark ms-1">
                                            {{ $waitingEditorJobs->count() }}
                                        </span>
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#tab-completed">
                                        ✅ Selesai
                                        <span class="badge bg-secondary ms-1">
                                            {{ $completedJobs->count() }}
                                        </span>
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#tab-canceled">
                                        ❌ Dibatalkan
                                        <span class="badge bg-danger ms-1">
                                            {{ $canceledJobs->count() }}
                                        </span>
                                    </button>
                                </li>

                            </ul>

                            <!-- Tombol Buat Job -->
                            <div class="p-2 text-end">
                                <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold text-nowrap shadow-sm">
                                    <i class="bi bi-plus-lg me-1"></i> Buat Job Baru
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="tab-content" id="jobTabsContent">

                            <!-- TAB 1: AKTIF -->
                            <div class="tab-pane fade show active" id="tab-active" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Tanggal</th>
                                                <th>Jam</th>
                                                <th>Job & Klien</th>
                                                <th>Tipe</th>
                                                <th>Crew / Editor</th>
                                                <th>Status</th>
                                                <th class="text-end pe-4">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $lastDate = null; @endphp

                                            @forelse($activeJobs as $job)

                                            @if($lastDate !== $job->job_date->toDateString())
                                            <tr class="table-secondary">
                                                <td colspan="7" class="fw-bold text-dark ps-4 py-2">
                                                    @if($job->job_date->isToday())
                                                    🔴 Hari Ini — {{ $job->job_date->translatedFormat('d M Y') }}
                                                    @else
                                                    📅 {{ $job->job_date->translatedFormat('l, d M Y') }}
                                                    @endif
                                                </td>
                                            </tr>

                                            @php $lastDate = $job->job_date->toDateString(); @endphp
                                            @endif

                                            @include('admin.partials.job_row', ['job' => $job])
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    Tidak ada jadwal aktif.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB 2: STATUS EDITOR -->
                            <div class="tab-pane fade" id="tab-waiting-editor">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Tanggal</th>
                                                <th>Jam</th>
                                                <th>Job & Klien</th>
                                                <th>Tipe</th>
                                                <th>Crew / Editor</th>
                                                <th>Status</th>
                                                <th class="text-end pe-4">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $lastDate = null; @endphp

                                            @forelse($waitingEditorJobs as $job)

                                            @if($lastDate !== $job->job_date->toDateString())
                                            <tr class="table-secondary">
                                                <td colspan="7" class="fw-bold text-dark ps-4 py-2">
                                                    @if($job->job_date->isToday())
                                                    🔴 Hari Ini — {{ $job->job_date->translatedFormat('d M Y') }}
                                                    @else
                                                    📅 {{ $job->job_date->translatedFormat('l, d M Y') }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $lastDate = $job->job_date->toDateString(); @endphp
                                            @endif

                                            @include('admin.partials.job_row', ['job' => $job])

                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    Tidak ada job menunggu editor.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <!-- TAB 3: SELESAI -->
                            <div class="tab-pane fade" id="tab-completed" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Tanggal</th>
                                                <th>Jam</th>
                                                <th>Job & Klien</th>
                                                <th>Tipe</th>
                                                <th>Crew / Editor</th>
                                                <th>Status</th>
                                                <th class="text-end pe-4">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $lastDate = null; @endphp

                                            @forelse($completedJobs as $job)


                                            @if($lastDate !== $job->job_date->toDateString())
                                            <tr class="table-secondary">
                                                <td colspan="7" class="fw-bold text-dark ps-4 py-2">
                                                    @if($job->job_date->isToday())
                                                    🔴 Hari Ini — {{ $job->job_date->translatedFormat('d M Y') }}
                                                    @else
                                                    📅 {{ $job->job_date->translatedFormat('l, d M Y') }}
                                                    @endif
                                                </td>
                                            </tr>

                                            @php $lastDate = $job->job_date->toDateString(); @endphp
                                            @endif

                                            @include('admin.partials.job_row
                                            ', ['job' => $job])
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    Belum ada riwayat selesai.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB 4: DIBATALKAN -->
                            <div class="tab-pane fade" id="tab-canceled" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Tanggal</th>
                                                <th>Jam</th>
                                                <th>Job & Klien</th>
                                                <th>Tipe</th>
                                                <th>Crew / Editor</th>
                                                <th>Status</th>
                                                <th class="text-end pe-4">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $lastDate = null; @endphp

                                            @forelse($canceledJobs as $job)

                                            @if($lastDate !== $job->job_date->toDateString())
                                            <tr class="table-danger">
                                                <td colspan="7" class="fw-bold text-dark ps-4 py-2">
                                                    @if($job->job_date->isToday())
                                                    🔴 Hari Ini — {{ $job->job_date->translatedFormat('d M Y') }}
                                                    @else
                                                    📅 {{ $job->job_date->translatedFormat('l, d M Y') }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $lastDate = $job->job_date->toDateString(); @endphp
                                            @endif

                                            @include('admin.partials.job_row', ['job' => $job])

                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    Tidak ada job dibatalkan.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- OFFCANVAS (SIDEBAR POPUP) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasTagihan" aria-labelledby="offcanvasTagihanLabel" style="width: 400px;">
        <div class="offcanvas-header bg-light border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark" id="offcanvasTagihanLabel">Keuangan & Konfirmasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-3">

            <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab-sidebar" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold small" id="pills-tagih-tab" data-bs-toggle="pill" data-bs-target="#pills-tagih" type="button">
                        ⚠️ TAGIHAN <span class="badge bg-white text-danger ms-1">{{ $billingList->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold small" id="pills-konfirmasi-tab" data-bs-toggle="pill" data-bs-target="#pills-konfirmasi" type="button">
                        ✅ KONFIRMASI <span class="badge bg-white text-primary ms-1">{{ $confirmationList->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Tab Tagihan -->
                <div class="tab-pane fade show active" id="pills-tagih">
                    <ul class="list-group list-group-flush">

                        @php
                        $groupedBilling = $billingList
                        ->sortBy([
                        ['job_date', 'desc'],
                        ['start_time', 'asc'],
                        ])
                        ->groupBy(fn($job) => $job->job_date->toDateString());
                        @endphp

                        @forelse($groupedBilling as $date => $jobs)
                        {{-- HEADER TANGGAL --}}
                        <li class="list-group-item bg-danger fw-bold small text-white">
                            📅 {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        </li>

                        @foreach($jobs as $job)
                        <li class="list-group-item px-0 pb-3 mb-2">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <div class="fw-bold text-dark">
                                        {{ $job->client_name }} — {{ $job->job_title }}
                                    </div>
                                    <small class="text-muted">
                                        ⏰ {{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold {{ $job->amount > 0 ? 'text-dark' : 'text-warning' }}">
                                        {{ $job->amount > 0 ? 'Rp '.number_format($job->amount,0,',','.') : 'Harga 0' }}
                                    </div>
                                    <span class="badge bg-danger">Unpaid</span>
                                </div>
                            </div>

                            <form action="{{ route('jobs.confirmPayment', $job->id) }}" method="POST" class="bg-light p-3 rounded border">
                                @csrf
                                <div class="mb-2">
                                    <label class="small text-muted">Metode Bayar</label>
                                    <select name="payment_method" class="form-select form-select-sm" required>
                                        <option value="">Pilih...</option>
                                        <option value="tf">Transfer</option>
                                        <option value="cash">Cash</option>
                                        <option value="vendor">Vendor</option>
                                    </select>
                                </div>

                                @if($job->amount == 0)
                                <div class="mb-2">
                                    <label class="small text-muted">Update Harga</label>
                                    <input type="number" name="amount" class="form-control form-control-sm" required>
                                </div>
                                @endif

                                <button class="btn btn-success btn-sm w-100 fw-bold">
                                    Simpan & Lunas
                                </button>
                            </form>
                        </li>
                        @endforeach

                        @empty
                        <div class="text-center py-5 text-muted">
                            Semua tagihan aman 👍
                        </div>
                        @endforelse
                    </ul>
                </div>

                <!-- Tab Konfirmasi -->
                <div class="tab-pane fade" id="pills-konfirmasi">
                    <ul class="list-group list-group-flush">

                        @php
                        $groupedConfirmation = $confirmationList
                        ->sortBy([
                        ['job_date', 'desc'],
                        ['start_time', 'asc'],
                        ])
                        ->groupBy(fn($job) => $job->job_date->toDateString());
                        @endphp

                        @forelse($groupedConfirmation as $date => $jobs)
                        {{-- HEADER TANGGAL --}}
                        <li class="list-group-item bg-danger fw-bold small text-white">
                            📅 {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        </li>

                        @foreach($jobs as $job)
                        <li class="list-group-item px-0 pb-3 mb-2">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <div class="fw-bold text-dark">{{ $job->client_name }} — {{ $job->job_title }}</div>
                                    <small class="text-muted">
                                        ⏰ {{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }} |
                                        <span class="badge bg-info text-dark">{{ strtoupper($job->payment_method) }}</span>
                                    </small>
                                </div>
                                <div class="fw-bold">
                                    Rp {{ number_format($job->amount,0,',','.') }}
                                </div>
                            </div>

                            <form action="{{ route('jobs.confirmPayment', $job->id) }}" method="POST" class="bg-light p-3 rounded border">
                                @csrf
                                <input type="hidden" name="payment_method" value="{{ $job->payment_method }}">

                                @if($job->amount == 0)
                                <div class="mb-2">
                                    <label class="small text-muted">Update Harga</label>
                                    <input type="number" name="amount" class="form-control form-control-sm" required>
                                </div>
                                @endif

                                <button class="btn btn-primary btn-sm w-100 fw-bold">
                                    Verifikasi Terima
                                </button>
                            </form>
                        </li>
                        @endforeach

                        @empty
                        <div class="text-center py-5 text-muted">
                            Tidak ada konfirmasi
                        </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT WAJIB -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- LOGIKA TAB PERSISTENCE -->
    <script>
        function storeTabAndSubmit(form) {
            const activeTab = document.querySelector('#jobTabs .nav-link.active').id;
            localStorage.setItem('activeJobTab', activeTab);
            form.submit();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const savedTab = localStorage.getItem('activeJobTab');
            if (savedTab) {
                const tabTrigger = new bootstrap.Tab(document.querySelector('#' + savedTab));
                tabTrigger.show();
            }
        });
    </script>
</body>

</html>