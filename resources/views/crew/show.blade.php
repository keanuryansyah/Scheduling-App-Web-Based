<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Job - Crew</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Modern (Plus Jakarta Sans) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            background-color: #f4f6f8; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #344054;
        }

        /* Navbar Sticky yang bersih */
        .navbar-sticky {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #f0f2f5;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Card Modern */
        .card-modern {
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 16px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .card-modern:active { transform: scale(0.99); }

        /* Icon Boxes (Kotak Ikon Warna-warni) */
        .icon-box {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 14px;
            flex-shrink: 0;
        }
        .bg-icon-blue { background: #eff6ff; color: #3b82f6; }
        .bg-icon-orange { background: #fff7ed; color: #f97316; }
        .bg-icon-green { background: #f0fdf4; color: #22c55e; }
        .bg-icon-red { background: #fef2f2; color: #ef4444; }
        .bg-icon-purple { background: #f5f3ff; color: #8b5cf6; }

        /* Typography */
        .label-text {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #98a2b3;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .value-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1d2939;
        }
        
        /* Avatar Tim */
        .avatar-circle {
            width: 40px;
            height: 40px;
            background-color: #f2f4f7;
            color: #475467;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Tombol Aksi */
        .btn-action {
            border-radius: 12px;
            padding: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <!-- 1. HEADER / NAVBAR -->
    <div class="navbar-sticky p-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('crew.jobs') }}" class="btn btn-light rounded-circle shadow-sm border p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="bi bi-arrow-left text-dark"></i>
            </a>
            <div>
                <h6 class="mb-0 fw-bold text-dark" style="line-height: 1.2;">{{ Str::limit($job->job_title, 20) }}</h6>
                <small class="text-muted" style="font-size: 11px;">Detail Pekerjaan</small>
            </div>
        </div>
        
        <!-- Badge Status -->
        <div>
            @if($job->status == 'scheduled') 
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">Jadwal</span>
            @elseif($job->status == 'ongoing') 
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">Proses</span>
            @else 
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Selesai</span>
            @endif
        </div>
    </div>

    <div class="container py-4">

        <!-- 2. INFO UTAMA (WAKTU & TIPE) -->
        <div class="card card-modern p-4">
            <!-- Badge Tipe Job -->
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-dashed">
                <span class="text-muted small fw-bold">JENIS PEKERJAAN</span>
                <span class="badge text-white px-3 py-2 rounded-pill shadow-sm" 
                      style="background-color: {{ $job->type->badge_color ?? '#6c757d' }}; font-weight: 600; letter-spacing: 0.5px;">
                    {{ strtoupper($job->type->job_type_name) }}
                </span>
            </div>

            <div class="row g-4">
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-icon-blue"><i class="bi bi-calendar4-week"></i></div>
                        <div>
                            <div class="label-text">TANGGAL</div>
                            <div class="value-text">{{ $job->job_date->translatedFormat('d M Y') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-icon-orange"><i class="bi bi-clock"></i></div>
                        <div>
                            <div class="label-text">JAM KERJA</div>
                            <div class="value-text">
                                {{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($job->end_time)->format('H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. LOKASI & MAPS -->
        <div class="card card-modern p-4">
            <div class="d-flex mb-3">
                <div class="icon-box bg-icon-red"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="label-text">LOKASI / TITIK KUMPUL</div>
                    <div class="value-text" style="line-height: 1.4;">{{ $job->location }}</div>
                </div>
            </div>
            
            <!-- Tombol Maps -->
            @php 
                $mapLink = str_contains($job->location, 'http') ? $job->location : "https://www.google.com/maps/search/?api=1&query=" . urlencode($job->location);
            @endphp
            <a href="{{ $mapLink }}" target="_blank" class="btn btn-outline-danger btn-action w-100">
                <i class="bi bi-map-fill"></i> Buka Google Maps
            </a>
        </div>

        <!-- 4. INFO KLIEN & CHAT -->
        <div class="card card-modern p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-icon-green"><i class="bi bi-person-fill"></i></div>
                    <div>
                        <div class="label-text">NAMA KLIEN</div>
                        <div class="value-text">{{ $job->client_name }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Tombol WA -->
            <a href="https://wa.me/{{ $job->client_phone }}" target="_blank" class="btn btn-success btn-action w-100 text-white">
                <i class="bi bi-whatsapp"></i> Hubungi Klien (WhatsApp)
            </a>
        </div>

        <!-- 5. CATATAN KHUSUS (STICKY NOTE STYLE) -->
        <div class="card card-modern border-0" style="background-color: #fffbeb;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-2 text-warning">
                    <i class="bi bi-pin-angle-fill me-2 fs-5"></i>
                    <h6 class="fw-bold mb-0">Catatan Khusus</h6>
                </div>
                <p class="mb-0 text-dark opacity-75 fst-italic" style="font-size: 0.95rem; line-height: 1.6;">
                    "{{ $job->notes ?? 'Tidak ada catatan khusus, bekerjalah dengan baik & profesional!' }}"
                </p>
            </div>
        </div>

        <!-- 6. TIM BERTUGAS -->
        <h6 class="ms-1 mb-3 fw-bold text-muted small text-uppercase ls-1">Rekan Satu Tim</h6>
        <div class="card card-modern p-3">
            <div class="d-flex flex-column gap-3">
                @foreach($job->users as $crew)
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 hover-bg-light">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-white shadow-sm border text-primary">
                                {{ substr($crew->name, 0, 1) }}
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold text-dark">{{ $crew->name }}</div>
                                <div class="small text-muted" style="font-size: 11px;">
                                    {{ $crew->role->name == 'editor' ? 'Video Editor' : 'Crew Lapangan' }}
                                </div>
                            </div>
                        </div>
                        @if($crew->id == auth()->id())
                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-2">Saya</span>
                        @endif
                    </div>
                    @if(!$loop->last) <hr class="my-0 border-light"> @endif
                @endforeach
            </div>
        </div>

        <!-- Spacer bawah agar tidak ketutup navbar HP -->
        <div style="height: 20px;"></div>

    </div>

</body>
</html>