<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Income: {{ $user->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #344767;
        }

        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            background: white;
        }

        .profile-card {
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            color: white;
        }

        .avatar-circle {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .table-custom th {
            background-color: #f1f5f9;
            text-transform: uppercase;
            font-size: 0.7rem;
            color: #64748b;
            font-weight: 700;
            padding: 16px;
            white-space: nowrap;
        }

        .table-custom td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .input-gaji {
            font-family: 'Consolas', monospace;
            font-weight: 700;
            color: #198754;
            border-color: #198754;
            text-align: right;
            min-width: 150px;
        }

        .status-pill {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .pill-pending {
            background: #fff3cd;
            color: #856404;
        }

        .pill-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .kn-fz-smll {
            font-size: 8px;
            color: black;
        }

        @media (max-width: 768px) {
            .profile-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-info {
                margin-top: 15px;
                margin-left: 0 !important;
            }

            .profile-money {
                margin-top: 20px;
                width: 100%;
                text-align: center !important;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding-top: 15px;
            }

            .header-flex {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 header-flex">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('boss.income.index') }}" class="btn btn-white border shadow-sm rounded-circle p-2" style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-arrow-left text-dark"></i>
                </a>
                @if(auth()->user()->role_id == 1)
                <h4 class="fw-bold mb-0 text-dark">Input Gaji Manual</h4>
                @else
                <h4 class="fw-bold mb-0 text-dark">Lihat Daftar Job User</h4>
                @endif
            </div>
        </div>

        <!-- INFO USER & TOTAL TERFILTER -->
        <div class="row g-4 mb-4">
            <!-- Kartu Kiri: Info User Global -->
            <div class="col-md-8">
                <div class="card card-modern profile-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 profile-content">
                        <div class="avatar-circle">{{ substr($user->name, 0, 1) }}</div>
                        <div class="profile-info">
                            <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                            <div class="opacity-75 small mb-2">
                                <i class="bi bi-envelope me-1"></i> {{ $user->email }}
                                <span class="d-none d-md-inline mx-1">•</span>
                                <i class="bi bi-whatsapp me-1"></i> {{ $user->phone_number }}
                            </div>
                            <div>
                                <span class="badge bg-white text-dark text-uppercase px-2 py-1">{{ ucfirst($user->role->name) }}</span>
                                <span class="badge bg-warning text-dark text-uppercase px-2 py-1 ms-1">{{ ucfirst($user->payday) }}</span>
                            </div>
                        </div>
                        <div class="ms-auto text-end profile-money">
                            <!-- INI TOTAL DOMPET ASLI (GLOBAL) -->
                            @if($user->payday == 'weekly')
                            <small class="opacity-75 text-uppercase fw-bold ls-1" style="font-size: 0.7rem;">Total Dompet Minggu Ini</small>
                            @else
                            <small class="opacity-75 text-uppercase fw-bold ls-1" style="font-size: 0.7rem;">Total Dompet Bulan Ini</small>
                            @endif

                            @if(auth()->user()->role_id == 1)
                            <h2 class="mb-0 fw-bold">Rp {{ number_format($user->income, 0, ',', '.') }}</h2>
                            @if($user->income != '0.00')
                            <form action="{{ route('boss.income.resetIncome') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button class="btn badge bg-danger">Reset</button>
                            </form>
                            @endif
                            @else
                            <h2 class="mb-0 fw-bold">****</h2>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Kanan: Total Sesuai Filter -->
            <div class="col-md-4">
                <div class="card card-modern p-4 h-100 border-start border-4 border-success">
                    <div class="text-muted small text-uppercase fw-bold ls-1">Saldo Terfilter</div>
                    <!-- INI TOTAL SESUAI FILTER -->
                    @if(auth()->user()->role_id == 1)
                    <h2 class="fw-bold text-success mb-0">Rp {{ number_format($totalRealIncome, 0, ',', '.') }}</h2>
                    @else
                    <h2 class="fw-bold text-success mb-0">****</h2>
                    @endif
                    <div class="small text-muted mt-2">Menampilkan hasil sesuai filter di bawah.</div>
                </div>
            </div>
        </div>

        <!-- FILTER TOOLBAR -->
        <div class="card border-0 shadow-sm mb-4 p-3 bg-white">
            <form action="{{ route('boss.income.detail', $user->id) }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">

                <!-- 1. FILTER TIPE JOB -->
                <select name="job_type" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    @foreach($allJobTypes as $type)
                    <option value="{{ $type->id }}" {{ request('job_type') == $type->id ? 'selected' : '' }}>
                        {{ $type->job_type_name }}
                    </option>
                    @endforeach
                </select>

                <div class="vr mx-1"></div>

                <!-- 2. FILTER TANGGAL -->
                <div class="input-group input-group-sm" style="width: auto;">
                    <span class="input-group-text bg-white">Dari</span>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"
                        onchange="document.getElementById('monthSelect').value = ''; this.form.submit()">

                    <span class="input-group-text bg-white">s/d</span>

                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"
                        onchange="document.getElementById('monthSelect').value = ''; this.form.submit()">
                </div>

                <!-- 3. FILTER BULAN -->
                <select name="month" id="monthSelect" class="form-select form-select-sm" style="width: 120px;"
                    onchange="document.getElementsByName('start_date')[0].value = ''; document.getElementsByName('end_date')[0].value = ''; this.form.submit()">
                    <option value="">- Semua Bulan -</option>
                    @for($m=1; $m<=12; $m++)
                        @php
                        // Default ke bulan saat ini jika tidak ada request 'month'
                        $selectedMonth=request('month') ?? date('n');
                        @endphp
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endfor
                </select>

                <!-- 4. FILTER TAHUN -->
                <select name="year" class="form-select form-select-sm" style="width: 90px;" onchange="this.form.submit()">
                    @php
                    // Default ke tahun saat ini jika tidak ada request 'year'
                    $selectedYear = request('year') ?? date('Y');
                    @endphp
                    @for($y=date('Y')+1; $y>=2025; $y--)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <!-- 5. RESET -->
                <a href="{{ route('boss.income.detail', $user->id) }}" class="btn btn-sm btn-light border shadow-sm" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise text-dark"></i> Reset
                </a>
            </form>
        </div>

        <!-- TABEL LIST JOB -->
        <div class="card card-modern overflow-hidden">
            <div class="table-responsive">
                <table class="table table-custom mb-0 align-middle">
                    <thead>
                        <tr>
                            <th width="15%">Waktu</th>
                            <th width="20%">Detail Job</th>
                            <th width="10%">Tipe Job</th>
                            <th width="15%">Status</th>

                            <th width="15%">
                                {{ $user->role->name == 'editor' ? 'CREW' : 'EDITOR' }}
                            </th>

                            <th width="10%">Hasil</th>
                            <th width="10%">Harga</th>
                            @if(auth()->user()->role_id == 1)
                            <th width="15%" class="text-end">Input Fee (Rp)</th>
                            <th width="12%" class="text-end text-primary">Extra Fee (Rp)</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        // Kelompokkan jobs berdasarkan minggu dalam bulan tersebut (hari 1-7 = Minggu 1, dst)
                        $groupedJobs = $jobs->groupBy(function($item) {
                        return ceil($item->job_date->day / 7);
                        });
                        @endphp

                        @forelse($groupedJobs as $weekNum => $weekJobs)
                        <!-- HEADER MINGGU -->
                        <tr class="bg-light">
                            <td colspan="9" class="py-2 px-4 fw-bold text-primary" style="background-color: #f8fafc; border-left: 4px solid #0d6efd;">
                                <i class="bi bi-calendar3 me-2"></i> MINGGU {{ $weekNum }}
                            </td>
                        </tr>

                        @foreach($weekJobs as $job)
                        <tr>
                            <!-- 1. WAKTU -->
                            <td>
                                <div class="fw-bold text-dark">{{ $job->job_date->translatedFormat('d M Y') }}</div>
                                <div class="small text-muted">{{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($job->end_time)->format('H:i') }}</div>
                            </td>

                            <!-- 2. DETAIL -->
                            <td>
                                <div class="fw-bold text-dark">{{ $job->job_title }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2 rounded-pill"
                                        style="font-size: 10px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#jobModal{{ $job->id }}">
                                        <i class="bi bi-info-circle me-1"></i> Info
                                    </button>
                                </div>
                            </td>

                            <!-- 3. TIPE JOB -->
                            <td>
                                <span class="badge text-white" style="background-color: {{ $job->type->badge_color ?? '#6c757d' }}">
                                    {{ $job->type->job_type_name }}
                                </span>
                            </td>

                            <!-- 4. STATUS -->
                            <td>
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
                            </td>

                            <!-- 5. PARTNER -->
                            <td>
                                @php
                                $partnerName = '-';
                                if ($user->role->name == 'editor') {
                                $crewAssign = $job->assignments->where('user_id', '!=', $user->id)->first();
                                $partnerName = $crewAssign ? \App\Models\User::find($crewAssign->user_id)->name : '-';
                                } else {
                                $editorAssign = $job->assignments->whereNotNull('editor_id')->first();
                                $partnerName = $editorAssign ? \App\Models\User::find($editorAssign->editor_id)->name : '-';
                                }
                                @endphp
                                <span class="small text-muted">{{ $partnerName }}</span>
                            </td>

                            <!-- 6. HASIL -->
                            <td>
                                @if($job->result_link)
                                <a href="{{ $job->result_link }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3"><i class="bi bi-link-45deg"></i> Link</a>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <!-- 7. HARGA -->
                            <td>
                                <div class="fw-bold text-dark">Rp {{ number_format($job->amount, 0, ',', '.') }}</div>
                                <div class="small text-uppercase text-muted" style="font-size: 0.65rem;">{{ $job->payment_method }}</div>
                            </td>

                            @if(auth()->user()->role_id == 1)
                            <!-- 8. INPUT GAJI -->
                            <td class="text-end">
                                @php
                                $trx = $job->transactions->where('user_id', $user->id)->first();
                                $amountTrx = $trx ? $trx->amount : 0;
                                $statusTrx = $trx ? $trx->type : 'new';
                                @endphp

                                <div class="d-flex flex-column align-items-end gap-2">
                                    <form action="{{ route('boss.income.storeSingle') }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <input type="number" name="amount" class="form-control form-control-sm input-gaji"
                                            value="{{ $amountTrx > 0 ? $amountTrx : '' }}" placeholder="0">
                                        <button class="btn btn-sm btn-primary"><i class="bi bi-save"></i></button>
                                    </form>

                                    @if($statusTrx == 'salary_pending')
                                    <form action="{{ route('boss.income.cairkan') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <button class="btn btn-success btn-sm py-0 px-2 fw-bold" style="font-size: 11px;">CAIRKAN SEKARANG</button>
                                    </form>
                                    <span class="status-pill pill-pending">MENUNGGU CAIR</span>
                                    @elseif($statusTrx == 'income')
                                    <span class="status-pill pill-success"><i class="bi bi-check-all"></i> SUDAH CAIR</span>
                                    @endif
                                </div>
                            </td>

                            <!-- 9. EXTRA FEE -->
                            <td class="text-end">
                                @php
                                $extraFee = $trx ? $trx->extra_fee : 0;
                                @endphp
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <form action="{{ route('boss.income.storeExtraFee') }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <input type="hidden" name="amount" value="{{ $amountTrx > 0 ? $amountTrx : '' }}">

                                        @if($amountTrx != 0 && $statusTrx == 'income')
                                        <input type="number" name="extra_fee" class="form-control form-control-sm input-gaji"
                                            value="{{ $extraFee > 0 ? $extraFee : '' }}" placeholder="0">
                                        <button class="btn btn-sm btn-primary"><i class="bi bi-save"></i></button>
                                        @else
                                        <span class="small text-muted" style="font-size: 10px;">Belum Cair</span>
                                        @endif
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>

                        <!-- MODAL (Dipindah ke dalam loop agar ID sesuai) -->
                        <div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold">Detail Pekerjaan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="text-muted small fw-bold">JUDUL PEKERJAAN</label>
                                            <div class="fw-bold text-dark fs-5">{{ $job->job_title }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label class="text-muted small fw-bold">KLIEN</label>
                                                <div>{{ $job->client_name }}</div>
                                            </div>
                                            <div class="col-6">
                                                <label class="text-muted small fw-bold">METODE BAYAR</label>
                                                <div><span class="badge bg-secondary text-white">{{ strtoupper($job->payment_method) }}</span></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted small fw-bold">LOKASI</label>
                                            <div class="p-2 bg-light rounded border border-light">{{ $job->location }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">Belum ada pekerjaan yang sesuai filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>