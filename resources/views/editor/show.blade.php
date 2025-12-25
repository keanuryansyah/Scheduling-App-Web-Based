<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Editing - {{ $job->job_title }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; font-family: 'Plus Jakarta Sans', sans-serif; }
        .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); background: white; }
        .label-text { font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 5px; }
        .value-text { font-size: 0.95rem; font-weight: 600; color: #1e293b; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Tombol Kembali -->
            <a href="{{ route('editor.dashboard') }}" class="btn btn-outline-secondary mb-3 rounded-pill px-4 fw-bold" style="font-size: 0.85rem;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>

            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0 text-dark">{{ $job->job_title }}</h4>
                        
                        <!-- BADGE STATUS -->
                        @if($job->editor_status == 'idle')
                            <span class="badge bg-secondary">Menunggu Editor</span>
                        @elseif($job->editor_status == 'editing')
                            <span class="badge bg-warning text-dark"><div class="spinner-grow spinner-grow-sm me-1"></div> Proses Edit</span>
                        @elseif($job->editor_status == 'completed')
                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Selesai</span>
                        @endif
                    </div>
                    
                    <div class="mt-2">
                        <span class="badge text-white" style="background-color: {{ $job->type->badge_color ?? '#6c757d' }}">
                            {{ $job->type->job_type_name }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <!-- INFO UTAMA -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="label-text">TANGGAL ACARA</div>
                            <div class="value-text">{{ $job->job_date->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="label-text">KLIEN</div>
                            <div class="value-text">{{ $job->client_name }}</div>
                        </div>
                        <div class="col-12">
                            <div class="label-text">LOKASI</div>
                            <div class="value-text">{{ $job->location }}</div>
                        </div>
                    </div>

                    <!-- CATATAN & SUMBER -->
                    <div class="alert alert-warning border-0 text-dark mb-4">
                        <h6 class="fw-bold mb-1"><i class="bi bi-sticky-fill text-warning me-2"></i> Catatan Khusus:</h6>
                        <p class="mb-0 fst-italic">"{{ $job->notes ?? 'Tidak ada catatan.' }}"</p>
                    </div>

                    <div class="mb-4">
                        <div class="label-text">SUMBER FOOTAGE / TIM LAPANGAN</div>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            @forelse($job->users as $crew)
                                <span class="badge bg-white border text-dark py-2 px-3">
                                    <i class="bi bi-person-fill me-1"></i> {{ $crew->name }}
                                </span>
                            @empty
                                <span class="text-muted small">- Tidak ada info crew -</span>
                            @endforelse
                        </div>
                    </div>

                    <hr>

                    <!-- AREA AKSI (Action Area) -->
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3">Status & Hasil Pekerjaan</h5>

                        @if($job->editor_status == 'idle')
                            <!-- KONDISI 1: BELUM DIAMBIL -->
                            <div class="text-center p-4 bg-light rounded-3 border border-dashed">
                                <p class="text-muted mb-3">Job ini belum mulai dikerjakan.</p>
                                <form action="{{ route('editor.start', $job->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                                        <i class="bi bi-play-fill me-2"></i> AMBIL & MULAI EDIT
                                    </button>
                                </form>
                            </div>

                        @elseif($job->editor_status == 'editing')
                            <!-- KONDISI 2: SEDANG PROSES -->
                            <div class="p-4 bg-warning bg-opacity-10 rounded-3 border border-warning">
                                <label class="label-text text-dark mb-2">MASUKKAN LINK HASIL (GDRIVE/YOUTUBE)</label>
                                <form action="{{ route('editor.finish', $job->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-white"><i class="bi bi-link-45deg"></i></span>
                                        <input type="url" name="result_link" class="form-control" placeholder="https://..." required>
                                    </div>
                                    <button class="btn btn-success w-100 fw-bold">
                                        <i class="bi bi-check-lg me-2"></i> SELESAI & SIMPAN
                                    </button>
                                </form>
                            </div>

                        @elseif($job->editor_status == 'completed')
                            <!-- KONDISI 3: SUDAH SELESAI (TAPI BISA EDIT) -->
                            <div class="p-4 bg-success bg-opacity-10 rounded-3 border border-success">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-success mb-0"><i class="bi bi-check-circle-fill me-2"></i> Pekerjaan Selesai</h6>
                                    <a href="{{ $job->result_link }}" target="_blank" class="btn btn-sm btn-outline-success bg-white">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                    </a>
                                </div>
                                
                                <label class="label-text text-success mb-1">LINK SAAT INI:</label>
                                <input type="text" class="form-control mb-3 bg-white" value="{{ $job->result_link }}" readonly>

                                <hr class="border-success opacity-25">

                                <!-- Form Update Link -->
                                <label class="label-text text-dark mb-2">SALAH LINK? UPDATE DI SINI:</label>
                                <form action="{{ route('editor.finish', $job->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="url" name="result_link" class="form-control" placeholder="Paste link baru..." value="{{ $job->result_link }}" required>
                                        <button class="btn btn-dark fw-bold">
                                            <i class="bi bi-pencil-square me-1"></i> UPDATE
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>