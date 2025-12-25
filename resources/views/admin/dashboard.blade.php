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
        body { background-color: #f3f4f6; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Navbar Spesial Admin */
        .navbar-admin { background: white; border-bottom: 1px solid #e2e8f0; }
        
        /* Card Styles */
        .stat-card { border: none; border-radius: 12px; padding: 24px; color: white; position: relative; overflow: hidden; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { position: absolute; right: 20px; top: 20px; font-size: 3rem; opacity: 0.2; }
        
        /* Gradients */
        .bg-gradient-purple { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
        .bg-gradient-blue { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .bg-gradient-orange { background: linear-gradient(135deg, #f97316 0%, #d97706 100%); }
        .bg-gradient-green { background: linear-gradient(135deg, #10b981 0%, #047857 100%); }

        /* Table Styles (AGAR TIDAK WRAP) */
        .card-table { border: none; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        
        .table thead th { 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            background: #f1f5f9; 
            padding: 16px; 
            color: #64748b; 
            white-space: nowrap; /* JANGAN TURUN KE BAWAH */
        }
        
        .table tbody td { 
            padding: 16px; 
            vertical-align: middle; 
            white-space: nowrap; /* JANGAN TURUN KE BAWAH */
        }
        
        .avatar-circle { width: 30px; height: 30px; background-color: #e2e8f0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; color: #475569; }
    </style>
</head>
<body>

    <!-- NAVBAR ADMIN -->
    <nav class="navbar navbar-expand-lg navbar-admin mb-4 py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="#">
                <i class="bi bi-shield-lock-fill me-2"></i> Admin<span class="text-dark">Panel</span>
            </a>
            <div class="d-flex gap-3 align-items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none fw-bold text-dark">Dashboard</a>
                <a href="{{ route('job-types.index') }}" class="text-decoration-none text-secondary">Job Types</a>
                <div class="vr"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-bold">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <!-- FILTER BAR -->
        <div class="card border-0 shadow-sm mb-4 p-4 bg-white rounded-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Operasional Jadwal</h4>
                    <p class="text-muted mb-0 small">Periode: <strong>{{ $judulPeriode }}</strong></p>
                </div>

                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex flex-wrap align-items-center justify-content-end gap-2">
                    <select name="job_type" class="form-select form-select-sm border-secondary-subtle" style="width: 140px;" onchange="this.form.submit()">
                        <option value="">Semua Tipe</option>
                        @foreach($allJobTypes as $type)
                        <option value="{{ $type->id }}" {{ request('job_type') == $type->id ? 'selected' : '' }}>{{ $type->job_type_name }}</option>
                        @endforeach
                    </select>

                    <div class="vr mx-1"></div>

                    <div class="input-group input-group-sm" style="width: auto;">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" onchange="document.getElementsByName('month')[0].value=''; this.form.submit()">
                        <span class="input-group-text bg-white border-start-0 border-end-0">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" onchange="document.getElementsByName('month')[0].value=''; this.form.submit()">
                    </div>

                    <select name="month" class="form-select form-select-sm" style="width: 110px;" onchange="document.getElementsByName('start_date')[0].value=''; document.getElementsByName('end_date')[0].value=''; this.form.submit()">
                        <option value="">- Bulan -</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endfor
                    </select>

                    <select name="year" class="form-select form-select-sm" style="width: 80px;" onchange="this.form.submit()">
                        @for($y = date('Y') + 1; $y >= 2025; $y--)
                        <option value="{{ $y }}" {{ (request('year') ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light border" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                </form>
            </div>
        </div>

        <!-- STATISTIK (TANPA UANG) -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card bg-gradient-purple">
                    <i class="bi bi-calendar-range stat-icon"></i>
                    <h6 class="text-uppercase small fw-bold opacity-75">TOTAL JOB</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_jobs'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-gradient-blue">
                    <i class="bi bi-clock-history stat-icon"></i>
                    <h6 class="text-uppercase small fw-bold opacity-75">TERJADWAL</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['scheduled'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-gradient-orange">
                    <i class="bi bi-play-circle stat-icon"></i>
                    <h6 class="text-uppercase small fw-bold opacity-75">SEDANG JALAN</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['ongoing'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-gradient-green">
                    <i class="bi bi-check-circle stat-icon"></i>
                    <h6 class="text-uppercase small fw-bold opacity-75">SELESAI</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['done'] }}</h2>
                </div>
            </div>
        </div>

        <!-- TABEL FULL WIDTH (SCROLLABLE) -->
        <div class="card card-table p-0 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">Daftar Jadwal Kerja</h6>
                <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> Buat Job Baru
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
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
                        @forelse($todaysJobs as $job)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $job->job_date->translatedFormat('d M Y') }}</div>
                                <div class="small text-muted">{{ $job->job_date->translatedFormat('l') }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $job->job_title }}</div>
                                <div class="small text-muted">{{ $job->client_name }}</div>
                            </td>
                            <td>
                                <span class="badge text-white" style="background-color: {{ $job->type->badge_color ?? '#6c757d' }}">
                                    {{ $job->type->job_type_name }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @foreach($job->users as $crew)
                                        <div class="avatar-circle me-1" title="{{ $crew->name }}">{{ substr($crew->name, 0, 1) }}</div>
                                    @endforeach
                                    
                                    {{-- Cek Editor --}}
                                    @php
                                        $assign = $job->assignments->whereNotNull('editor_id')->first();
                                        $editor = $assign ? \App\Models\User::find($assign->editor_id) : null;
                                    @endphp
                                    @if($editor)
                                        <div class="avatar-circle bg-warning text-dark me-1" title="Editor: {{ $editor->name }}">{{ substr($editor->name, 0, 1) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($job->status == 'scheduled') <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">Terjadwal</span>
                                @elseif($job->status == 'ongoing') <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Proses</span>
                                @elseif($job->status == 'done') <span class="badge bg-success bg-opacity-10 text-success border border-success">Selesai</span>
                                @elseif($job->status == 'canceled') <span class="badge bg-dark">Batal</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <!-- Tombol WA -->
                                    @if($job->users->isNotEmpty())
                                        @php
                                        $crewPhone = $job->users->first()->phone_number;
                                        if(substr($crewPhone, 0, 1) == '0') $crewPhone = '62' . substr($crewPhone, 1);
                                        $msg = "Halo Team, reminder job: " . $job->job_title;
                                        @endphp
                                        <a href="https://wa.me/{{ $crewPhone }}?text={{ urlencode($msg) }}" target="_blank" class="btn btn-outline-success btn-sm"><i class="bi bi-whatsapp"></i></a>
                                    @endif
                                    
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">Tidak ada jadwal.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>