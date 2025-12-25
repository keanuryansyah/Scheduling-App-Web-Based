<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Boss</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f3f4f6;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 20px;
            color: white;
        }

        .bg-indigo {
            background: #4f46e5;
        }

        .bg-green {
            background: #10b981;
        }

        .bg-orange {
            background: #f59e0b;
        }

        .bg-red {
            background: #ef4444;
        }

        .table-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
            display: inline-block;
            text-align: center;
            line-height: 30px;
            font-size: 12px;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .kn-form-select:focus {
            box-shadow: none !important;
        }

        /* TABLET & MOBILE */
        @media (max-width: 992px) {

            /* Navbar rapih turun */
            .navbar .container {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .navbar .kn-header-1 {
                flex-wrap: wrap;
                gap: 10px;
            }

            /* Filter turun ke bawah */
            form[action*="boss.dashboard"] {
                flex-wrap: wrap !important;
                gap: 8px;
            }

            /* Header card */
            .kn-header-2 {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 10px;
            }

            /* Statistik card */
            .stat-card h2,
            .stat-card h3 {
                font-size: 1.4rem;
            }
        }

        /* MOBILE */
        @media (max-width: 576px) {

            /* Statistik jadi full */
            .stat-card {
                padding: 16px;
            }

            /* Table scroll */
            .table-responsive {
                overflow-x: auto;
            }

            table {
                min-width: 700px;
            }

            /* Avatar kecil */
            .avatar {
                width: 26px;
                height: 26px;
                font-size: 11px;
            }

            /* Button kecil */
            .btn-sm {
                padding: 4px 8px;
                font-size: 12px;
            }

            /* Header text */
            h4 {
                font-size: 1.2rem;
            }

            h5 {
                font-size: 1rem;
            }
        }

        @media screen and (max-width : 992px) {
            .kn-bill {
                margin-top: 1.5rem !important;
            }
        }

        @media screen and (max-width : 425px) {
            .kn-header-2 {
                flex-direction: column !important;
                align-items: start !important;
            }
        }
    </style>
</head>

<body>

    <!-- 1. NAVBAR BARU (Menu & Logout) -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-4 py-3">
        <div class="container">
            <img src="{{ asset('images/logo-izz.png') }}" class="kn-logo-login-form" alt="Logo Izzati" width="150">


            <div class="d-flex gap-3 align-items-center kn-header-1">
                <!-- Menu Navigasi -->
                <a href="{{ route('boss.dashboard') }}" class="text-decoration-none fw-bold text-dark">Dasbor</a>
                <a href="{{ route('users.index') }}" class="text-decoration-none text-secondary">Kelola User</a>
                <a href="{{ route('job-types.index') }}" class="text-decoration-none text-secondary">Kelola Tipe Pekerjaan</a>
                <a href="{{ route('boss.income.index') }}" class="text-decoration-none text-secondary">Pendapatan Tim</a>


                <div class="vr mx-2"></div>

                <!-- Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <!-- HEADER & FILTER -->
        <div class="card border-0 shadow-sm mb-4 p-3 bg-white">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Dashboard Boss</h4>
                    <p class="text-muted mb-0 small">Halo, {{ Auth::user()->name }}! Semangat kerjanya.</p>
                </div>

                <form action="{{ route('boss.dashboard') }}" method="GET" class="d-flex flex-wrap align-items-center justify-content-end gap-2">

                    <!-- Filter Tipe Job -->
                    <select name="job_type" class="form-select form-select-sm" style="width: 120px;" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        @foreach($allJobTypes as $type)
                        <option value="{{ $type->id }}" {{ request('job_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->job_type_name }}
                        </option>
                        @endforeach
                    </select>

                    <div class="vr mx-1"></div>

                    <!-- FILTER RENTANG TANGGAL -->
                    <div class="input-group input-group-sm" style="width: auto;">
                        <span class="input-group-text bg-white text-muted border-end-0">Dari</span>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control border-start-0"
                            onchange="if(this.form.end_date.value){ this.form.month.value=''; this.form.submit() }">

                        <span class="input-group-text bg-white text-muted border-start-0 border-end-0">s/d</span>

                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control border-start-0"
                            onchange="if(this.form.start_date.value){ this.form.month.value=''; this.form.submit() }">
                    </div>

                    <div class="vr mx-1"></div>

                    <!-- Filter Bulan (Opsional jika malas isi tanggal) -->
                    <select name="month" class="form-select form-select-sm" style="width: 110px;" onchange="this.form.start_date.value=''; this.form.end_date.value=''; this.form.submit()">
                        <option value="">- Bulan -</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                            @endfor
                    </select>

                    <!-- Filter Tahun -->
                    <select name="year" class="form-select form-select-sm" style="width: 80px;" onchange="this.form.submit()">
                        <!-- Loop dari Tahun Depan sampai 2025 -->
                        @for($y = date('Y') + 1; $y >= 2025; $y--)
                        <option value="{{ $y }}" {{ (request('year') ?? date('Y')) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                        @endfor
                    </select>


                    <!-- Tombol Reset -->
                    <a href="{{ route('boss.dashboard') }}" class="btn btn-sm btn-light border" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </form>
            </div>
        </div>


        <!-- WIDGETS STATISTIK -->
        <div class="row g-4 mb-4">

            <!-- KARTU 1: PENDAPATAN (DINAMIS) -->
            <div class="col-md-3">
                <div class="stat-card bg-green">
                    <h6 class="text-uppercase" style="font-size: 0.7rem; font-weight: bold; opacity: 0.9;">
                        PENDAPATAN ({{ $judulPeriode }})
                    </h6>
                    <h3 class="fw-bold">Rp {{ number_format($stats['monthly_income'], 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- KARTU 2: JOB COUNT (DINAMIS) -->
            <div class="col-md-3">
                <div class="stat-card bg-indigo">
                    <h6 class="text-uppercase" style="font-size: 0.7rem; font-weight: bold; opacity: 0.9;">
                        JOB SELESAI/JADWAL ({{ $judulPeriode }})
                    </h6>
                    <h2 class="fw-bold">{{ $stats['jobs_count'] }}</h2>
                </div>
            </div>

            <!-- KARTU 3 & 4 TETAP SAMA (GLOBAL) -->
            <div class="col-md-3">
                <div class="stat-card bg-orange">
                    <h6 class="text-uppercase" style="font-size: 0.7rem; font-weight: bold;">
                        SEDANG BERJALAN (SAAT INI)
                    </h6>
                    <h2 class="fw-bold">{{ $stats['ongoing_jobs'] }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bg-red">
                    <h6 class="text-uppercase" style="font-size: 0.7rem; font-weight: bold;">
                        BELUM LUNAS (GLOBAL)
                    </h6>
                    <h2 class="fw-bold">{{ $stats['unpaid_jobs'] }}</h2>
                </div>
            </div>
        </div>
        <!-- KONTEN TABEL (Sama seperti sebelumnya, tombol Create Job & Eye sudah diperbaiki) -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card table-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold">
                            Data Job: {{ $judulPeriode }}
                            @if(request('job_type'))
                            <span class="badge bg-primary fs-6 ms-1">
                                {{ $allJobTypes->find(request('job_type'))->job_type_name }}
                            </span>
                            @endif
                        </h5>
                        <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm">+ Buat Job Baru</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <!-- KOLOM BARU -->
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Nama Job</th>
                                    <th>Tipe</th>
                                    <th>Crew</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todaysJobs as $job)
                                <tr>
                                    <!-- ISI KOLOM TANGGAL -->
                                    <td class="text-nowrap">
                                        {{ $job->job_date->translatedFormat('d M Y') }}
                                        <div class="small text-muted">{{ $job->job_date->translatedFormat('l') }}</div>
                                    </td>

                                    <td><strong>{{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }}</strong></td>
                                    <td>
                                        {{ $job->job_title }}<br>
                                    </td>
                                    <td>
                                        <span class="badge text-white" style="background-color: {{ $job->type->badge_color ?? '#6c757d' }}">
                                            {{ $job->type->job_type_name }}
                                        </span>
                                    </td>
                                    <td>
                                        @foreach($job->users as $crew)
                                        <div class="avatar" title="{{ $crew->name }}">{{ substr($crew->name, 0, 1) }}</div>
                                        @endforeach
                                        @if($job->users->isEmpty()) <span class="text-danger small">Belum assign</span> @endif
                                    </td>
                                    <td>
                                        @if($job->status == 'scheduled')
                                        <span class="badge bg-secondary">Terjadwal</span>

                                        @elseif($job->status == 'ongoing')
                                        <span class="badge bg-warning text-dark">Proses Lapangan</span>

                                        @elseif($job->status == 'canceled')
                                        <span class="badge bg-dark">Dibatalkan</span>

                                        @elseif($job->status == 'done')
                                        {{-- Cek Status Editor --}}
                                        @if($job->status === 'done')

                                        @switch($job->editor_status)

                                        @case('idle')
                                        <span class="badge bg-info text-dark">
                                            <i class="bi bi-hourglass-split"></i> Menunggu Editor
                                        </span>
                                        @break

                                        @case('editing')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-laptop"></i> Proses Edit
                                        </span>
                                        @break

                                        @case('completed')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill"></i> Selesai
                                        </span>
                                        @break

                                        @default
                                        <span class="badge bg-secondary">Selesai</span>

                                        @endswitch

                                        @endif

                                        @endif
                                    </td>
                                    <td>
                                        <!-- LOGIC TOMBOL WA REMINDER -->
                                        @if($job->users->isNotEmpty())
                                        @php
                                        $crewName = $job->users->first()->name;
                                        $crewPhone = $job->users->first()->phone_number;

                                        // Format Nomor HP (08xx -> 628xx)
                                        if(substr($crewPhone, 0, 1) == '0') {
                                        $crewPhone = '62' . substr($crewPhone, 1);
                                        }

                                        // Pesan WA
                                        $text = "Halo {$crewName}, ada job baru dari Boss untuk tanggal " . $job->job_date->format('d-m-Y') . ". Jangan lupa cek dashboard kamu ya!";
                                        $waLink = "https://wa.me/{$crewPhone}?text=" . urlencode($text);
                                        @endphp

                                        <a href="{{ $waLink }}" target="_blank" class="btn btn-success btn-sm" title="Kirim WA">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                        @endif

                                        <!-- Tombol Detail -->
                                        <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada jadwal.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 kn-bill">
                <div class="card table-card p-3">

                    <!-- NAV TABS -->
                    <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold small" id="pills-tagih-tab" data-bs-toggle="pill" data-bs-target="#pills-tagih" type="button" role="tab">
                                ⚠️ PERLU DITAGIH <span class="badge bg-white text-danger ms-1">{{ $billingList->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold small" id="pills-konfirmasi-tab" data-bs-toggle="pill" data-bs-target="#pills-konfirmasi" type="button" role="tab">
                                ✅ KONFIRMASI <span class="badge bg-white text-primary ms-1">{{ $confirmationList->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">

                        <!-- TAB 1: PERLU DITAGIH (Crew pilih Unpaid) -->
                        <div class="tab-pane fade show active" id="pills-tagih" role="tabpanel">
                            <ul class="list-group list-group-flush">
                                @forelse($billingList as $job)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $job->client_name }} - {{ $job->job_title }}</div>
                                            <small class="text-muted">{{ $job->job_date->format('d M Y') }}</small>
                                        </div>
                                        <div class="text-end">
                                            @if($job->amount > 0)
                                            <span class="d-block fw-bold text-dark">Rp {{ number_format($job->amount, 0, ',', '.') }}</span>
                                            @else
                                            <span class="badge bg-warning text-dark">Harga 0</span>
                                            @endif
                                            <span class="badge bg-danger">Unpaid</span>
                                        </div>
                                    </div>

                                    <!-- FORM PELUNASAN (FULL) -->
                                    <form action="{{ route('jobs.confirmPayment', $job->id) }}" method="POST" enctype="multipart/form-data" class="bg-light p-2 rounded">
                                        @csrf

                                        <!-- 1. Pilih Metode -->
                                        <div class="mb-2">
                                            <select name="payment_method" class="form-select form-select-sm" required>
                                                <option value="" disabled selected>Pilih Metode Bayar...</option>
                                                <option value="tf">Transfer</option>
                                                <option value="vendor">Vendor</option>
                                                <option value="cash">Cash</option>
                                            </select>
                                        </div>

                                        <!-- 2. Input Harga (Muncul jika harga masih 0) -->
                                        @if($job->amount == 0)
                                        <div class="mb-2">
                                            <input type="number" name="amount" class="form-control form-control-sm" placeholder="Masukkan Nominal Job (Rp)" required>
                                        </div>
                                        @endif

                                        <!-- 3. Upload Bukti (Opsional) -->
                                        <div class="mb-2">
                                            <label class="small text-muted">Upload Bukti (Opsional)</label>
                                            <input type="file" name="proof" class="form-control form-control-sm">
                                        </div>

                                        <button class="btn btn-success btn-sm w-100 fw-bold">Simpan & Lunas</button>
                                    </form>
                                </li>
                                @empty
                                <li class="list-group-item text-center text-muted py-4">Semua tagihan aman.</li>
                                @endforelse
                            </ul>
                        </div>

                        <!-- TAB 2: PERLU KONFIRMASI (Crew pilih TF/Cash) -->
                        <div class="tab-pane fade" id="pills-konfirmasi" role="tabpanel">
                            <ul class="list-group list-group-flush">
                                @forelse($confirmationList as $job)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $job->client_name }} - {{ $job->job_title }}</div>
                                            <small class="text-muted">Laporan Crew:
                                                <span class="badge bg-info text-dark">{{ strtoupper($job->payment_method) }}</span>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            @if($job->amount > 0)
                                            <span class="d-block fw-bold text-dark">Rp {{ number_format($job->amount, 0, ',', '.') }}</span>
                                            @else
                                            <span class="badge bg-warning text-dark">Harga 0</span>
                                            @endif

                                            <!-- Tombol Lihat Bukti Crew -->
                                            @if($job->proof && $job->proof != 'no-proof.img')
                                            <a href="{{ asset('storage/'.$job->proof) }}" target="_blank" class="small text-primary text-decoration-none">
                                                <i class="bi bi-paperclip"></i> Bukti Crew
                                            </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- FORM KONFIRMASI (SIMPLE) -->
                                    <form action="{{ route('jobs.confirmPayment', $job->id) }}" method="POST" enctype="multipart/form-data" class="bg-light p-2 rounded">
                                        @csrf
                                        <!-- Hidden payment method (tetap pakai pilihan crew/boss) -->
                                        <!-- Boss bisa ubah metode jika mau -->
                                        <div class="mb-2">
                                            <label class="small text-muted">Metode Pembayaran</label>
                                            <select name="payment_method" class="form-select form-select-sm">
                                                <option value="tf" {{ $job->payment_method == 'tf' ? 'selected' : '' }}>Transfer</option>
                                                <option value="cash" {{ $job->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="vendor" {{ $job->payment_method == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                            </select>
                                        </div>

                                        <!-- Input Harga (Wajib jika 0) -->
                                        @if($job->amount == 0)
                                        <div class="mb-2">
                                            <input type="number" name="amount" class="form-control form-control-sm" placeholder="Nominal Real (Rp)" required>
                                        </div>
                                        @endif

                                        <!-- Upload Bukti Boss (Opsional, menimpa bukti crew jika diisi) -->
                                        <div class="mb-2">
                                            <label class="small text-muted">Bukti Validasi (Opsional)</label>
                                            <input type="file" name="proof" class="form-control form-control-sm">
                                        </div>

                                        <button class="btn btn-primary btn-sm w-100 fw-bold">Validasi & Terima Uang</button>
                                    </form>
                                </li>
                                @empty
                                <li class="list-group-item text-center text-muted py-4">Tidak ada yang perlu dikonfirmasi.</li>
                                @endforelse
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
