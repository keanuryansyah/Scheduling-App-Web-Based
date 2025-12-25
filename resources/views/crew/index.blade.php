<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Saya - Crew</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Font Modern -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            background-color: #f1f5f9; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }

        /* Navbar */
        .navbar-crew { 
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); 
            color: white; 
            padding: 1rem 0;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        /* Card Job */
        .job-card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); 
            transition: all 0.3s ease; 
            background: white;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .job-card:active { transform: scale(0.98); }

        /* Status Badge di Pojok Kanan Atas */
        .status-badge { 
            position: absolute; 
            top: 16px; 
            right: 16px; 
        }

        /* Saldo Card */
        .card-saldo {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
            margin-bottom: 24px;
        }

        .btn-action {
            border-radius: 10px;
            font-weight: 600;
            padding: 10px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-crew">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <small class="text-white-50 d-block" style="font-size: 11px;">Selamat Datang,</small>
                <span class="navbar-brand mb-0 h1 fw-bold text-white">{{ Auth::user()->name }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </nav>

    <div class="container pb-5">

        <!-- Info Saldo -->
        <div class="card card-saldo">
            <div class="card-body p-4">
                <small class="text-white-50 text-uppercase fw-bold ls-1" style="font-size: 10px;">Dompet Saya</small>
                <h2 class="fw-bold mt-1 mb-0">
                    @if(Auth::user()->income > 0)
                        Rp {{ number_format(Auth::user()->income, 0, ',', '.') }}
                    @else
                        Belum di Entry.
                    @endif
                </h2>
            </div>
        </div>

        <!-- Judul Rentang Waktu -->
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h6 class="fw-bold text-dark mb-0">📅 Jadwal Bulan Ini</h6>
                <small class="text-muted" style="font-size: 11px;">
                    {{ now()->translatedFormat('F Y') }} 
                    ({{ now()->startOfMonth()->translatedFormat('d') }} - {{ now()->endOfMonth()->translatedFormat('d') }})
                </small>
            </div>
        </div>

        <!-- List Job -->
        <div class="row g-3">
            @forelse($myJobs as $job)
            <div class="col-md-6 col-lg-4">
                <div class="card job-card p-3 h-100">
                    
                    <!-- Status Badge -->
                    <div class="status-badge">
                        @if($job->status == 'scheduled') 
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">Terjadwal</span>
                        @elseif($job->status == 'ongoing') 
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">Proses</span>
                        @elseif($job->status == 'done') 
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Selesai</span>
                        @endif
                    </div>

                    <!-- Tipe Job Badge -->
                    <div class="mb-2">
                        <span class="badge text-white shadow-sm" style="background-color: {{ $job->type->badge_color ?? '#6c757d' }}">
                            {{ $job->type->job_type_name }}
                        </span>
                    </div>

                    <!-- Judul & Lokasi -->
                    <h5 class="fw-bold mt-1 mb-1 text-dark">{{ $job->job_title }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ Str::limit($job->location, 25) }}
                    </p>

                    <!-- Info Waktu -->
                    <div class="bg-light p-2 rounded-3 mb-3 border border-light">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-calendar-event text-primary"></i>
                            <span class="small fw-bold">{{ $job->job_date->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock text-primary"></i>
                            <span class="small text-muted">
                                {{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($job->end_time)->format('H:i') }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-auto">
                        <!-- TOMBOL DETAIL -->
                        <a href="{{ route('crew.show', $job->id) }}" class="btn btn-outline-secondary w-100 mb-2 fw-bold btn-action" style="border-style: dashed;">
                            <i class="bi bi-info-circle me-1"></i> Detail & Catatan
                        </a>

                        <!-- LOGIKA TOMBOL MULAI / SELESAI -->
                        @if($job->status == 'scheduled')
                            <form action="{{ route('crew.start', $job->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary w-100 fw-bold btn-action shadow-sm">
                                    <i class="bi bi-play-fill me-1"></i> Mulai Pekerjaan
                                </button>
                            </form>

                        @elseif($job->status == 'ongoing')
                            <button class="btn btn-success w-100 fw-bold btn-action shadow-sm" data-bs-toggle="modal" data-bs-target="#finishModal{{ $job->id }}">
                                <i class="bi bi-check-lg me-1"></i> Selesai & Lapor
                            </button>

                        @else
                            <button class="btn btn-secondary w-100 btn-action bg-opacity-25 border-0 text-muted" disabled>
                                <i class="bi bi-lock-fill me-1"></i> Job Selesai
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MODAL SELESAI JOB (Sama seperti sebelumnya) -->
            <div class="modal fade" id="finishModal{{ $job->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Laporan Selesai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('crew.finish', $job->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <p class="text-muted small">Konfirmasi penyelesaian job: <br><strong class="text-dark">{{ $job->job_title }}</strong></p>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">METODE PEMBAYARAN KLIEN</label>
                                    <select name="payment_method" class="form-select" id="payMethod{{ $job->id }}" onchange="toggleProof({{ $job->id }})">
                                        <option value="unpaid">Belum Bayar</option>
                                        <option value="tf">Transfer</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>

                                <!-- Input Nominal (Muncul jika Cash) -->
                                <!-- <div class="mb-3 d-none" id="amountDiv{{ $job->id }}">
                                    <label class="form-label fw-bold small text-success">TOTAL UANG DITERIMA (RP)</label>
                                    <input type="number" name="amount" class="form-control border-success" placeholder="Contoh: 500000">
                                </div> -->

                                <!-- Form Upload (Muncul jika Cash) -->
                                <!-- <div class="mb-3 d-none" id="proofDiv{{ $job->id }}">
                                    <label class="form-label fw-bold small text-danger">UPLOAD BUKTI UANG / KUITANSI</label>
                                    <input type="file" name="proof" class="form-control">
                                </div> -->
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Konfirmasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3 opacity-25">
                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                </div>
                <h6 class="text-muted fw-bold">Tidak ada jadwal bulan ini.</h6>
                <p class="small text-muted">Hubungi Boss jika jadwal belum muncul.</p>
            </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        function toggleProof(id) {
            let method = document.getElementById('payMethod' + id).value;
            let divProof = document.getElementById('proofDiv' + id);
            let divAmount = document.getElementById('amountDiv' + id);
            
            if (method === 'cash') {
                divProof.classList.remove('d-none');
                
                // Cek apakah perlu input harga (jika di DB masih 0)
                // Kita asumsikan default perlu, nanti backend yang validasi
                divAmount.classList.remove('d-none'); 
            } else {
                divProof.classList.add('d-none');
                divAmount.classList.add('d-none');
            }
        }
    </script> -->
</body>
</html>