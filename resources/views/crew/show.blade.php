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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            margin-bottom: 16px;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .card-modern:active {
            transform: scale(0.99);
        }

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

        .bg-icon-blue {
            background: #eff6ff;
            color: #3b82f6;
        }

        .bg-icon-orange {
            background: #fff7ed;
            color: #f97316;
        }

        .bg-icon-green {
            background: #f0fdf4;
            color: #22c55e;
        }

        .bg-icon-red {
            background: #fef2f2;
            color: #ef4444;
        }

        .bg-icon-purple {
            background: #f5f3ff;
            color: #8b5cf6;
        }

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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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

        /* NEWWWW CUSTOM */
        /* ========================= */
        /* COMPACT HORIZONTAL TIMELINE */
        /* ========================= */

        .card-header-modern {
            background: transparent;
            border-bottom: 1px solid #f0f2f5;
            padding: 20px 25px;
        }

        .timeline-horizontal {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding: 16px;
        }

        .timeline-step {
            min-width: 180px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            text-align: center;
            position: relative;
            flex-shrink: 0;
        }

        .timeline-step.active {
            background: white;
            border-color: #4f46e5;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.15);
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 auto 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .timeline-label {
            font-weight: 700;
            font-size: 0.85rem;
        }

        .timeline-time {
            font-size: 0.8rem;
            color: #64748b;
        }

        .timeline-date {
            font-size: 0.7rem;
            color: #94a3b8;
        }

        /* ========================= */
        /* TIMELINE ARROW CONNECTOR */
        /* ========================= */

        .timeline-step {
            position: relative;
        }

        /* Garis penghubung */
        .timeline-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -18px;
            width: 30px;
            height: 2px;
            background: #cbd5e1;
            transform: translateY(-50%);
        }

        /* Panah */
        .timeline-step:not(:last-child)::before {
            content: '';
            position: absolute;
            top: 50%;
            right: -26px;
            border-width: 6px 0 6px 8px;
            border-style: solid;
            border-color: transparent transparent transparent #cbd5e1;
            transform: translateY(-50%);
        }

        /* Aktif → panah ikut nyala */
        .timeline-step.active:not(:last-child)::after {
            background: #4f46e5;
        }

        .timeline-step.active:not(:last-child)::before {
            border-left-color: #4f46e5;
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
            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">Terjadwal</span>

            @elseif($job->status == 'otw')
            <span class="badge bg-primary text-white"><i class="bi bi-scooter"></i> OTW</span>

            @elseif($job->status == 'arrived')
            <span class="badge bg-info text-white"><i class="bi bi-geo-alt-fill"></i> Sampai</span>

            @elseif($job->status == 'ongoing')
            <span class="badge bg-warning text-dark"><i class="bi bi-play-fill"></i>Sedang Kerja</span>

            @elseif($job->status == 'done')
            @if($job->editor_status == 'idle')
            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25"><i class="bi bi-hourglass-split"></i>Menunggu Editor</span>
            @elseif($job->editor_status == 'editing')
            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i class="bi bi-laptop"></i> Sedang Edit</span>
            @elseif($job->editor_status == 'completed')
            <span class="badge bg-success"><i class="bi bi-check-lg"></i>Selesai</span>
            @endif

            @elseif($job->status == 'canceled')
            <span class="badge bg-dark">Batal</span>
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

                {{-- CREW LAPANGAN --}}
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

                @if(!$loop->last)
                <hr class="my-0 border-light">
                @endif
                @endforeach

                {{-- EDITOR --}}
                @foreach($job->assignments as $assignment)
                @if($assignment->editor)
                <hr class="my-0 border-light">

                <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-white shadow-sm border text-success">
                            {{ substr($assignment->editor->name, 0, 1) }}
                        </div>
                        <div class="ms-3">
                            <div class="fw-bold text-dark">{{ $assignment->editor->name }}</div>
                            <div class="small text-muted" style="font-size: 11px;">
                                Video Editor
                            </div>
                        </div>
                    </div>

                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2">
                        Editor
                    </span>
                </div>
                @endif
                @endforeach

            </div>
        </div>


        <!-- CARD 3: TIMELINE CREW -->
        <div class="card card-modern mt-4">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-uppercase ls-1 text-dark">
                    <i class="bi bi-clock-history me-2"></i> Timeline Crew
                </h6>

                @if($job->status == 'ongoing')
                <span class="badge bg-warning text-dark">LIVE</span>
                @endif
            </div>

            <div class="timeline-horizontal">

                {{-- OTW --}}
                <div class="timeline-step {{ $job->otw_at ? 'active' : '' }}">
                    <div class="timeline-icon {{ $job->otw_at ? 'bg-primary text-white' : 'bg-secondary text-white opacity-25' }}">
                        <i class="bi bi-scooter"></i>
                    </div>
                    <div class="timeline-label">OTW</div>
                    <div class="timeline-time">{{ $job->otw_at ? $job->otw_at->format('H:i') : '--:--' }}</div>
                    <div class="timeline-date">{{ $job->otw_at ? $job->otw_at->translatedFormat('d M Y') : '' }}</div>
                </div>

                {{-- ARRIVED --}}
                <div class="timeline-step {{ $job->arrived_at ? 'active' : '' }}">
                    <div class="timeline-icon {{ $job->arrived_at ? 'bg-info text-white' : 'bg-secondary text-white opacity-25' }}">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="timeline-label">Sampe</div>
                    <div class="timeline-time">{{ $job->arrived_at ? $job->arrived_at->format('H:i') : '--:--' }}</div>
                    <div class="timeline-date">{{ $job->arrived_at ? $job->arrived_at->translatedFormat('d M Y') : '' }}</div>
                </div>

                {{-- START --}}
                <div class="timeline-step {{ $job->started_at ? 'active' : '' }}">
                    <div class="timeline-icon {{ $job->started_at ? 'bg-warning text-dark' : 'bg-secondary text-white opacity-25' }}">
                        <i class="bi bi-play-fill"></i>
                    </div>
                    <div class="timeline-label">Mulai Job</div>
                    <div class="timeline-time">{{ $job->started_at ? $job->started_at->format('H:i') : '--:--' }}</div>
                    <div class="timeline-date">{{ $job->started_at ? $job->started_at->translatedFormat('d M Y') : '' }}</div>
                </div>

                {{-- FINISH --}}
                <div class="timeline-step {{ $job->finished_at ? 'active' : '' }}">
                    <div class="timeline-icon {{ $job->finished_at ? 'bg-success text-white' : 'bg-secondary text-white opacity-25' }}">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="timeline-label">Selesai Job</div>
                    <div class="timeline-time">{{ $job->finished_at ? $job->finished_at->format('H:i') : '--:--' }}</div>
                    <div class="timeline-date">{{ $job->finished_at ? $job->finished_at->translatedFormat('d M Y') : '' }}</div>
                </div>

            </div>
        </div>


        <!-- TIMELINE EDITOR -->

        <div class="card card-modern mt-4">
            <div class="card-header-modern">
                <h6 class="fw-bold mb-0 text-uppercase ls-1 text-dark">
                    <i class="bi bi-laptop me-2"></i> Timeline Editor
                </h6>
            </div>

            <div class="timeline-horizontal">

                {{-- START EDIT --}}
                <div class="timeline-step {{ $job->editor_started_at ? 'active' : '' }}">
                    <div class="timeline-icon {{ $job->editor_started_at ? 'bg-warning text-dark' : 'bg-secondary text-white opacity-25' }}">
                        <i class="bi bi-play-fill"></i>
                    </div>
                    <div class="timeline-label">Mulai Edit</div>
                    <div class="timeline-time">{{ $job->editor_started_at ? $job->editor_started_at->format('H:i') : '--:--' }}</div>
                    <div class="timeline-date">{{ $job->editor_started_at ? $job->editor_started_at->translatedFormat('d M Y') : '' }}</div>
                </div>

                {{-- FINISH EDIT --}}
                <div class="timeline-step {{ $job->editor_finished_at ? 'active' : '' }}">
                    <div class="timeline-icon {{ $job->editor_finished_at ? 'bg-success text-white' : 'bg-secondary text-white opacity-25' }}">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="timeline-label">Selesai Edit</div>
                    <div class="timeline-time">{{ $job->editor_finished_at ? $job->editor_finished_at->format('H:i') : '--:--' }}</div>
                    <div class="timeline-date">{{ $job->editor_finished_at ? $job->editor_finished_at->translatedFormat('d M Y') : '' }}</div>
                </div>

                {{-- PC --}}
                @if($job->editor_pc)
                <div class="timeline-step active">
                    <div class="timeline-icon bg-dark text-white">
                        <i class="bi bi-pc-display"></i>
                    </div>
                    <div class="timeline-label">PC digunakan</div>
                    <div class="timeline-time">PC {{ $job->editor_pc }}</div>
                </div>
                @endif

            </div>
        </div>

        @if($job->result_link)
        <div class="card card-modern mt-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-icon-purple">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                    <div>
                        <div class="label-text">HASIL PEKERJAAN</div>
                        <div class="value-text">Link Final / Preview</div>
                    </div>
                </div>

                <a href="{{ $job->result_link }}"
                    target="_blank"
                    class="btn btn-outline-primary btn-action w-100">
                    <i class="bi bi-box-arrow-up-right"></i> Buka Hasil Pekerjaan
                </a>
            </div>
        </div>
        @endif

        <div class="col-12 mt-2 pt-3 border-top border-light">
            <div class="d-flex align-items-center justify-content-between">
                <span class="label-text mb-0">
                    <i class="bi bi-pencil-square me-1"></i> Added By:
                </span>

                @if($job->creator && $job->creator->role && $job->creator->role->name === 'boss')
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">
                    <i class="bi bi-suit-tie-fill me-1"></i> BOSS
                </span>
                @elseif($job->creator)
                <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2 rounded-pill">
                    <i class="bi bi-person-badge-fill me-1"></i>
                    ADMIN: {{ $job->creator->name }}
                </span>
                @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill">
                    <i class="bi bi-question-circle me-1"></i>
                    Unknown
                </span>
                @endif
            </div>
        </div>

        <!-- Spacer bawah agar tidak ketutup navbar HP -->
        <div style="height: 20px;"></div>

    </div>

</body>

</html>