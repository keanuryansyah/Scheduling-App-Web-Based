<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Editor</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .navbar-custom {
            background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%);
            padding: 1rem 0;
        }
        .card-job {
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .card-job:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .status-line { height: 4px; width: 100%; position: absolute; top: 0; left: 0; }
        
        .status-idle { background-color: #3b82f6; }
        .status-editing { background-color: #f59e0b; }
        .status-locked { background-color: #64748b; }
        .status-completed { background-color: #10b981; }

        .avatar-locked { width: 24px; height: 24px; background: #e2e8f0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; margin-right: 6px; font-weight: bold; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-custom navbar-dark mb-5 shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold"><i class="bi bi-layers-half me-2"></i> Editor Workspace</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white small opacity-75">Halo, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-secondary text-white border-secondary rounded-pill px-3">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container pb-5">
    
    <!-- INFO SALDO -->
    <div class="card bg-white border-0 shadow-sm mb-5 rounded-4 overflow-hidden">
        <div class="card-body d-flex justify-content-between align-items-center p-4">
            <div>
                <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.7rem;">Dompet Editor</small>
                <h2 class="fw-bold text-dark mt-1 mb-0">
                    @if(Auth::user()->income > 0)
                        Rp {{ number_format(Auth::user()->income, 0, ',', '.') }}
                    @else
                        Belum di Entry.
                    @endif
                </h2>
            </div>
        </div>
    </div>

    <!-- SECTION 1: ANTRIAN EDITING (ACTIVE) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-play-circle-fill text-primary me-2"></i> Antrian Editing</h5>
        <span class="badge bg-primary rounded-pill">{{ $activeJobs->count() }} Job</span>
    </div>

    <div class="row g-4 mb-5">
        @forelse($activeJobs as $job)
            @php
                $assignment = $job->assignments->first(); 
                $editorId = $assignment ? $assignment->editor_id : null;
                $currentUserId = Auth::id();
                $isLocked = ($job->editor_status == 'editing' && $editorId && $editorId != $currentUserId);
                
                $editorName = 'Editor Lain';
                if ($isLocked && $assignment) {
                    $editorData = \App\Models\User::find($editorId);
                    $editorName = $editorData ? $editorData->name : 'Editor Lain';
                }
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="card card-job {{ $isLocked ? 'bg-light' : '' }}">
                    @if($isLocked) <div class="status-line status-locked"></div>
                    @elseif($job->editor_status == 'editing') <div class="status-line status-editing"></div>
                    @else <div class="status-line status-idle"></div>
                    @endif

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge {{ $isLocked ? 'bg-secondary' : 'bg-primary' }} bg-opacity-10 text-{{ $isLocked ? 'secondary' : 'primary' }} mb-2">
                                    {{ $job->type->job_type_name }}
                                </span>
                                <h5 class="fw-bold mb-0 {{ $isLocked ? 'text-muted' : 'text-dark' }}">{{ $job->job_title }}</h5>
                            </div>
                            @if($isLocked) <i class="bi bi-lock-fill text-muted fs-4"></i>
                            @elseif($job->editor_status == 'editing') <div class="spinner-grow text-warning spinner-grow-sm"></div>
                            @endif
                        </div>

                        <p class="text-muted small mb-4">
                            <i class="bi bi-calendar-event me-1"></i> {{ $job->job_date->translatedFormat('d M Y') }}
                        </p>

                        <div class="mt-auto">
                            @if($isLocked)
                                <div class="alert alert-secondary py-2 px-3 small mb-0 rounded-3 d-flex align-items-center">
                                    <div class="avatar-locked">{{ substr($editorName, 0, 1) }}</div>
                                    <div><span class="d-block text-xs text-muted">Sedang diedit oleh:</span><strong class="text-dark">{{ $editorName }}</strong></div>
                                </div>
                            @elseif($job->editor_status == 'editing')
                                <form action="{{ route('editor.finish', $job->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="url" name="result_link" class="form-control form-control-sm bg-light" placeholder="Link GDrive..." required>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('editor.show', $job->id) }}" class="btn btn-outline-secondary btn-sm flex-grow-1 fw-bold">Detail</a>
                                        <button class="btn btn-success btn-sm flex-grow-1 fw-bold"><i class="bi bi-check-lg"></i> Selesai</button>
                                    </div>
                                </form>
                            @else
                                <form action="{{ route('editor.start', $job->id) }}" method="POST">
                                    @csrf
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('editor.show', $job->id) }}" class="btn btn-outline-primary w-100 btn-sm fw-bold">Detail</a>
                                        <button class="btn btn-primary w-100 btn-sm fw-bold shadow-sm"><i class="bi bi-play-fill"></i> Mulai</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4 border rounded-3 bg-white">
                <p class="text-muted mb-0 small">Belum ada job baru yang masuk.</p>
            </div>
        @endforelse
    </div>

    <!-- SECTION 2: RIWAYAT SELESAI (COMPLETED) -->
    @if($completedJobs->isNotEmpty())
        <div class="d-flex justify-content-between align-items-center mb-4 pt-4 border-top">
            <h5 class="fw-bold text-success mb-0"><i class="bi bi-check-circle-fill me-2"></i> Riwayat Selesai</h5>
            <span class="badge bg-success rounded-pill">10 Terakhir</span>
        </div>

        <div class="row g-4">
            @foreach($completedJobs as $job)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-job border border-success border-opacity-25">
                        <div class="status-line status-completed"></div>
                        <div class="card-body p-4 opacity-75">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold text-dark mb-0">{{ $job->job_title }}</h6>
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Selesai</span>
                            </div>
                            <p class="text-muted small mb-3">
                                {{ $job->job_date->translatedFormat('d M Y') }}
                            </p>
                            <div class="d-grid">
                                <a href="{{ route('editor.show', $job->id) }}" class="btn btn-outline-success btn-sm fw-bold">
                                    Lihat Hasil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
</body>
</html>