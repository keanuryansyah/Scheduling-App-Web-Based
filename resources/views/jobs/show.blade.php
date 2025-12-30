<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Job - {{ $job->job_title }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Modern -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #344767;
        }

        /* Card Styles */
        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            background: white;
            transition: transform 0.2s;
        }

        .card-header-modern {
            background: transparent;
            border-bottom: 1px solid #f0f2f5;
            padding: 20px 25px;
        }

        /* Gradient Card for Money */
        .card-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            color: white;
        }

        /* Icon Boxes */
        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 12px;
            flex-shrink: 0;
            /* Agar tidak gepeng */
        }

        .icon-bg-primary {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .icon-bg-success {
            background: #dcfce7;
            color: #166534;
        }

        .icon-bg-warning {
            background: #fef3c7;
            color: #b45309;
        }

        .icon-bg-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .icon-bg-dark {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* Baru untuk Judul */

        /* Typography */
        .label-text {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8898aa;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .value-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Crew Avatar */
        .avatar-circle {
            width: 38px;
            height: 38px;
            background-color: #f1f5f9;
            color: #64748b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            border: 2px solid white;
            margin-right: -10px;
            position: relative;
            z-index: 1;
        }

        /* Input Copy */
        .form-control-copy {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 0.85rem;
            color: #64748b;
        }

        /* NEWWWW CUSTOM */
        /* ========================= */
        /* COMPACT HORIZONTAL TIMELINE */
        /* ========================= */

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

    <div class="container py-5">

        <!-- HEADER SECTION -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">

            <!-- BAGIAN KIRI: Back Icon + Judul + Badges -->
            <div class="d-flex align-items-start gap-3 w-100">
                <!-- Tombol Back Bulat -->
                <a href="{{ auth()->user()->role->name == 'admin' ? route('admin.dashboard') : route('boss.dashboard') }}" class="btn btn-white border shadow-sm rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left text-dark"></i>
                </a>

                <div class="w-100">
                    <!-- Judul Job (Text Break agar tidak nabrak jika panjang) -->
                    <h3 class="fw-bold mb-1 text-dark text-break lh-sm">{{ $job->job_title }}</h3>

                    <!-- Container Badge (Flex Wrap agar turun rapi) -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">

                        <!-- 1. Badge Tipe Job -->
                        <span class="badge rounded-pill text-white border border-white shadow-sm"
                            style="background-color: {{ $job->type->badge_color ?? '#6c757d' }}; font-weight: 500;">
                            {{ $job->type->job_type_name }}
                        </span>

                        <!-- 2. Badge Status Editor -->
                        @if($job->status == 'done' || $job->editor_status != 'idle')
                        @if($job->editor_status == 'idle')
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25"><i class="bi bi-hourglass-split"></i> Menunggu Editor</span>
                        @elseif($job->editor_status == 'editing')
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i class="bi bi-laptop"></i> Sedang Edit</span>
                        @elseif($job->editor_status == 'completed')
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"><i class="bi bi-check-all"></i> Editing Selesai</span>
                        @endif
                        @endif

                        <!-- 3. Badge Status Utama -->
                        @if($job->status == 'scheduled')
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">Terjadwal</span>

                        @elseif($job->status == 'otw')
                        <span class="badge bg-primary text-white"><i class="bi bi-scooter"></i> OTW</span>

                        @elseif($job->status == 'arrived')
                        <span class="badge bg-info text-white"><i class="bi bi-geo-alt-fill"></i> Sampai</span>

                        @elseif($job->status == 'ongoing')
                        <span class="badge bg-warning text-dark"><i class="bi bi-play-fill"></i>Sedang Kerja</span>

                        @elseif($job->status == 'done')
                        @if($job->editor_status == 'completed')
                        <span class="badge bg-success"><i class="bi bi-check-lg"></i>Selesai</span>
                        @endif
                        @elseif($job->status == 'canceled')
                        <span class="badge bg-dark">Batal</span>
                        @endif

                    </div>
                </div>
            </div>

            <!-- BAGIAN KANAN: Tombol Aksi (Full Width di HP) -->
            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-md-end">

                <!-- LOGIKA TOMBOL EDIT -->
                @if($job->status == 'scheduled')
                <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-warning shadow-sm fw-bold text-white flex-grow-1 flex-md-grow-0">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>

                <form action="{{ route('jobs.cancel', $job->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan job ini?');" class="flex-grow-1 flex-md-grow-0">
                    @csrf @method('POST')
                    <button type="submit" class="btn btn-dark shadow-sm fw-bold w-100">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                </form>

                @elseif($job->status == 'otw')
                <form action="{{ route('jobs.cancel', $job->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan job ini?');" class="flex-grow-1 flex-md-grow-0">
                    @csrf @method('POST')
                    <button type="submit" class="btn btn-dark shadow-sm fw-bold w-100">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                </form>
                
                @elseif($job->status == 'arrived')
                <form action="{{ route('jobs.cancel', $job->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan job ini?');" class="flex-grow-1 flex-md-grow-0">
                    @csrf @method('POST')
                    <button type="submit" class="btn btn-dark shadow-sm fw-bold w-100">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                </form>
                
                @elseif($job->status == 'ongoing')
                <button class="btn btn-secondary disabled flex-grow-1 flex-md-grow-0"><i class="bi bi-lock-fill"></i> Sedang Jalan</button>
                <form action="{{ route('jobs.cancel', $job->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan job ini?');" class="flex-grow-1 flex-md-grow-0">
                    @csrf @method('POST')
                    <button type="submit" class="btn btn-dark shadow-sm fw-bold w-100">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                </form>

                @elseif($job->status == 'done')
                <button class="btn btn-success disabled flex-grow-1 flex-md-grow-0"><i class="bi bi-check-circle-fill"></i> Selesai</button>

                @elseif($job->status == 'canceled')
                <button class="btn btn-secondary disabled flex-grow-1 flex-md-grow-0"><i class="bi bi-slash-circle"></i> Dibatalkan</button>
                @endif

                <!-- Tombol Hapus -->
                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus job ini?');" class="flex-grow-1 flex-md-grow-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger shadow-sm fw-bold w-100">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>

                <!-- Tombol Kembali Teks (Hanya muncul di Desktop, di HP sudah ada panah diatas) -->
                <a href="{{ auth()->user()->role->name == 'admin' ? route('admin.dashboard') : route('boss.dashboard') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>

            </div>
        </div>

        <div class="row g-4">

            <!-- KOLOM KIRI (DETAIL UTAMA) -->
            <div class="col-lg-8">

                <!-- CARD 1: INFORMASI JOB -->
                <div class="card card-modern mb-4">
                    <div class="card-header-modern d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-uppercase ls-1 text-primary">
                            <i class="bi bi-info-circle me-2"></i> Detail Pekerjaan
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">

                            <!-- JUDUL JOB (DITAMBAHKAN DISINI) -->
                            <div class="col-12 d-flex border-bottom pb-4 mb-2">
                                <div class="icon-box icon-bg-dark"><i class="bi bi-briefcase-fill"></i></div>
                                <div>
                                    <div class="label-text">Nama Job / Judul</div>
                                    <div class="value-text fs-5">{{ $job->job_title }}</div>
                                </div>
                            </div>

                            <!-- Tanggal -->
                            <div class="col-md-6 d-flex">
                                <div class="icon-box icon-bg-primary"><i class="bi bi-calendar-event"></i></div>
                                <div>
                                    <div class="label-text">Tanggal</div>
                                    <div class="value-text">{{ $job->job_date->translatedFormat('l, d F Y') }}</div>
                                </div>
                            </div>
                            <!-- Jam -->
                            <div class="col-md-6 d-flex">
                                <div class="icon-box icon-bg-warning"><i class="bi bi-clock"></i></div>
                                <div>
                                    <div class="label-text">Jam Operasional</div>
                                    <div class="value-text">
                                        {{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($job->end_time)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                            <!-- Klien -->
                            <div class="col-md-6 d-flex">
                                <div class="icon-box icon-bg-success"><i class="bi bi-person"></i></div>
                                <div>
                                    <div class="label-text">Klien</div>
                                    <div class="value-text">{{ $job->client_name }}</div>
                                </div>
                            </div>
                            <!-- Kontak -->
                            <div class="col-md-6 d-flex">
                                <div class="icon-box icon-bg-danger"><i class="bi bi-whatsapp"></i></div>
                                <div>
                                    <div class="label-text">Kontak</div>
                                    <a href="https://wa.me/{{ $job->client_phone }}" target="_blank" class="text-decoration-none fw-bold text-dark">
                                        {{ $job->client_phone }} <i class="bi bi-box-arrow-up-right small ms-1 text-muted"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- Lokasi -->
                            <div class="col-12 mt-2">
                                <div class="p-3 rounded-3 bg-light border border-light">
                                    <div class="label-text mb-1 text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Lokasi</div>
                                    <p class="mb-0 small text-muted">{{ $job->location }}</p>
                                </div>
                            </div>
                            <!-- Notes -->
                            <div class="col-12">
                                <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                    <div class="label-text mb-1 text-warning"><i class="bi bi-sticky-fill me-1"></i> Catatan Khusus</div>
                                    <p class="mb-0 small text-dark fst-italic">"{{ $job->notes ?? 'Tidak ada catatan khusus.' }}"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: TIM BERTUGAS -->
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h6 class="fw-bold mb-0 text-uppercase ls-1 text-dark">
                            <i class="bi bi-people-fill me-2"></i> Tim Bertugas
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- 1. TAMPILKAN CREW (Dari kolom user_id) --}}
                            @foreach($job->users as $crew)
                            {{-- Kita filter: Hanya tampilkan jika dia BUKAN editor yang sama dengan editor_id (biar ga double) --}}
                            @php
                            $assignment = $job->assignments->first();
                            $editorIdInAssignment = $assignment ? $assignment->editor_id : null;

                            // Skip jika user ini ternyata adalah editor yang sama (nanti ditampilkan di bawah)
                            if($crew->id == $editorIdInAssignment) continue;
                            @endphp

                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded-3 bg-white shadow-sm h-100">
                                    <div class="avatar-circle bg-primary text-white">
                                        {{ substr($crew->name, 0, 1) }}
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-bold text-dark">{{ $crew->name }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            {{-- 2. TAMPILKAN EDITOR (Dari kolom editor_id) --}}
                            @php
                            // Ambil ID Editor dari tabel assignment
                            $assignmentData = $job->assignments->whereNotNull('editor_id')->first();
                            $editorData = $assignmentData ? \App\Models\User::find($assignmentData->editor_id) : null;
                            @endphp

                            @if($editorData)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded-3 bg-white shadow-sm h-100">
                                    <!-- Avatar Kuning Khas Editor -->
                                    <div class="avatar-circle bg-warning text-dark">
                                        {{ substr($editorData->name, 0, 1) }}
                                    </div>

                                    <div class="ms-3">
                                        <div class="fw-bold text-dark d-flex align-items-center">
                                            {{ $editorData->name }}

                                            {{-- Ikon Centang jika selesai --}}
                                            @if($job->editor_status == 'completed')
                                            <i class="bi bi-patch-check-fill text-primary ms-1" title="Editing Selesai"></i>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- 3. JIKA KOSONG SEMUA --}}
                            @if($job->users->isEmpty() && !$editorData)
                            <div class="col-12 text-center text-muted py-3">
                                <i class="bi bi-exclamation-circle me-1"></i> Belum ada crew/editor yang ditugaskan.
                            </div>
                            @endif

                        </div>
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

            </div>

            <!-- KOLOM KANAN -->
            <div class="col-lg-4">

                <!-- CARD KEUANGAN -->
                <div class="card card-modern card-gradient mb-4 border-0">
                    <div class="card-body p-4 text-center">
                        <div class="text-white-50 small text-uppercase fw-bold ls-1 mb-1">Total Nilai Job</div>
                        <h1 class="fw-bold mb-3 text-white">Rp {{ number_format($job->amount, 0, ',', '.') }}</h1>

                        <div class="d-inline-block bg-white rounded-pill px-4 py-2 shadow-sm">
                            @if($job->payment_method == 'unpaid')
                            <span class="text-danger fw-bold text-uppercase small" style="letter-spacing: 0.5px;">
                                <i class="bi bi-hourglass-split me-1"></i> Belum Lunas
                            </span>
                            @elseif($job->payment_method == 'tf' && $job->amount == '0.00')
                            <span class="text-danger fw-bold text-uppercase small" style="letter-spacing: 0.5px;">
                                <i class="bi bi-hourglass-split me-1"></i> Belum Lunas ({{$job->payment_method}})
                            </span>
                            @elseif($job->payment_method == 'cash' && $job->amount == '0.00')
                            <span class="text-danger fw-bold text-uppercase small" style="letter-spacing: 0.5px;">
                                <i class="bi bi-hourglass-split me-1"></i> Belum Lunas ({{$job->payment_method}})
                            </span>
                            @elseif($job->payment_method == 'vendor' && $job->amount == '0.00')
                            <span class="text-danger fw-bold text-uppercase small" style="letter-spacing: 0.5px;">
                                <i class="bi bi-hourglass-split me-1"></i> Belum Lunas ({{$job->payment_method}})
                            </span>
                            @else
                            <span class="text-success fw-bold text-uppercase small" style="letter-spacing: 0.5px;">
                                <i class="bi bi-check-circle-fill me-1"></i> Lunas ({{ $job->payment_method }})
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- CARD BUKTI -->
                <div class="card card-modern mb-4">
                    <div class="card-header-modern">
                        <h6 class="fw-bold mb-0 text-uppercase ls-1">Bukti Pembayaran</h6>
                    </div>
                    <div class="card-body p-3 text-center">

                        @if($job->proof && $job->proof != 'no-proof.img')
                        <!-- JIKA SUDAH ADA BUKTI -->
                        <div class="ratio ratio-16x9 rounded overflow-hidden border mb-2">
                            <img src="{{ asset('storage/'.$job->proof) }}" class="object-fit-cover" alt="Bukti">
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ asset('storage/'.$job->proof) }}" target="_blank" class="btn btn-sm btn-outline-dark flex-grow-1">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploadProof">
                                <i class="bi bi-arrow-repeat"></i> Ganti
                            </button>
                        </div>

                        @else
                        <!-- JIKA BELUM ADA BUKTI -->
                        <div class="py-4 bg-light rounded border border-dashed mb-2">
                            <i class="bi bi-image text-muted fs-1 opacity-50"></i>
                            <p class="small text-muted mb-0 mt-2">Belum ada bukti upload</p>
                        </div>
                        <button class="btn btn-sm btn-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploadProof">
                            <i class="bi bi-upload me-1"></i> Upload Bukti
                        </button>
                        @endif

                        <!-- FORM UPLOAD (Collapsible) -->
                        <div class="collapse mt-2" id="collapseUploadProof">
                            <form action="{{ route('jobs.updateProof', $job->id) }}" method="POST" enctype="multipart/form-data" class="bg-light p-3 rounded border text-start">
                                @csrf
                                <label class="small fw-bold text-muted mb-1">Pilih Gambar:</label>
                                <input type="file" name="proof" class="form-control form-control-sm mb-2" required>
                                <button class="btn btn-dark btn-sm w-100 fw-bold">Simpan Bukti</button>
                            </form>
                        </div>

                    </div>
                </div>


                <!-- CARD LINK -->
                <div class="card card-modern">
                    <div class="card-header-modern bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-uppercase ls-1 text-primary">Link Hasil</h6>

                        {{-- ICON LOCK --}}
                        <i id="lockIcon" class="bi {{ $job->amount == 0.00 ? 'bi-lock-fill text-danger' : 'bi-unlock-fill text-success' }} fs-5"></i>
                    </div>

                    <div class="card-body p-4">

                        {{-- ========================= --}}
                        {{-- 1️⃣ DONE + COMPLETED --}}
                        {{-- ========================= --}}
                        @if($job->status === 'done' && $job->editor_status === 'completed')

                        @php
                        $isLocked = ($job->amount == 0.00);
                        $isPrivileged = in_array(auth()->user()->role->name, ['admin', 'boss']);
                        @endphp

                        <p id="lockText" class="small text-muted text-center mb-3">
                            {{ $isLocked
                ? 'Link dikunci untuk saat ini. Klien harus menyelesaikan administrasi terlebih dahulu.'
                : 'Link sedang terbuka dan dapat diakses.'
            }}
                        </p>

                        @if($isPrivileged)
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <button id="unlockBtn"
                                class="btn btn-sm btn-outline-primary fw-bold {{ !$isLocked ? 'd-none' : '' }}"
                                onclick="toggleLink(true)">
                                <i class="bi bi-unlock-fill me-1"></i> Buka Kunci
                            </button>

                            <button id="lockBtn"
                                class="btn btn-sm btn-outline-danger fw-bold {{ $isLocked ? 'd-none' : '' }}"
                                onclick="toggleLink(false)">
                                <i class="bi bi-lock-fill me-1"></i> Kunci
                            </button>
                        </div>
                        @endif

                        <div id="linkArea" class="{{ $isLocked ? 'd-none' : '' }}">

                            {{-- LINK --}}
                            <div class="mb-3">
                                <label class="small text-muted fw-bold mb-1">Link Hasil:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-copy"
                                        id="resultLink"
                                        value="{{ $job->result_link }}"
                                        readonly>
                                    <button class="btn btn-light border" onclick="copyLink()">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- ACTION --}}
                            <div class="d-grid gap-2 mb-3">
                                <a href="{{ $job->result_link }}" target="_blank"
                                    class="btn btn-primary fw-bold">
                                    <i class="bi bi-folder2-open me-2"></i> Buka Link
                                </a>

                                @php
                                $hp = substr($job->client_phone, 0, 1) == '0'
                                ? '62'.substr($job->client_phone, 1)
                                : $job->client_phone;

                                $msg = "Halo Kak {$job->client_name}, ini link hasil dokumentasi acara {$job->job_title}:\n\n{$job->result_link}\n\nTerima kasih!";
                                @endphp

                                <a href="https://wa.me/{{ $hp }}?text={{ urlencode($msg) }}"
                                    target="_blank"
                                    class="btn btn-success fw-bold">
                                    <i class="bi bi-whatsapp me-2"></i> Kirim WA
                                </a>
                            </div>

                            {{-- REVISI LINK --}}
                            @if($isPrivileged)
                            <form action="{{ route('jobs.updateLink', $job->id) }}"
                                method="POST"
                                class="border-top pt-3">
                                @csrf
                                @method('PUT')

                                <label class="small fw-bold text-muted mb-1">
                                    Revisi / Ganti Link:
                                </label>
                                <input type="url"
                                    name="result_link"
                                    class="form-control form-control-sm mb-2"
                                    required>

                                <button class="btn btn-dark btn-sm w-100 fw-bold">
                                    <i class="bi bi-arrow-repeat me-1"></i> Simpan Revisi
                                </button>
                            </form>
                            @endif

                        </div>

                        {{-- ========================= --}}
                        {{-- 2️⃣ CANCELED --}}
                        {{-- ========================= --}}
                        @elseif($job->status === 'canceled')

                        <div class="text-center py-4">
                            <i class="bi bi-x-circle-fill text-danger fs-1 mb-3"></i>
                            <h6 class="fw-bold text-danger mb-2">Job Dibatalkan</h6>
                            <p class="small text-muted mb-0">
                                Link hasil tidak tersedia karena job ini telah dibatalkan.
                            </p>
                        </div>

                        {{-- ========================= --}}
                        {{-- 3️⃣ STATUS LAIN --}}
                        {{-- ========================= --}}
                        @else

                        <div class="text-center py-4">
                            <i class="bi bi-hourglass-split text-muted fs-1 mb-3"></i>
                            <h6 class="fw-bold text-muted mb-2">Link Belum Tersedia</h6>
                            <p class="small text-muted mb-0">
                                Link hasil akan tersedia setelah pekerjaan selesai dan editing diselesaikan.
                            </p>
                        </div>

                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Copy -->
    <script>
        function toggleLink(open) {
            const linkArea = document.getElementById('linkArea');
            const lockIcon = document.getElementById('lockIcon');
            const lockText = document.getElementById('lockText');
            const unlockBtn = document.getElementById('unlockBtn');
            const lockBtn = document.getElementById('lockBtn');

            if (open) {
                linkArea.classList.remove('d-none');
                lockIcon.className = 'bi bi-unlock-fill text-success fs-5';
                lockText.innerText = 'Link sedang terbuka dan dapat diakses.';
                unlockBtn.classList.add('d-none');
                lockBtn.classList.remove('d-none');
            } else {
                linkArea.classList.add('d-none');
                lockIcon.className = 'bi bi-lock-fill text-danger fs-5';
                lockText.innerText = 'Link dikunci untuk saat ini. Klien harus menyelesaikan administrasi terlebih dahulu.';
                unlockBtn.classList.remove('d-none');
                lockBtn.classList.add('d-none');
            }
        }

        function copyLink() {
            const copyText = document.getElementById("resultLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            alert("Link berhasil disalin!");
        }
    </script>



</body>

</html>