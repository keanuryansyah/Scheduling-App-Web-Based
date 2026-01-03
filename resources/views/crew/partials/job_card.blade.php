@php
$dateCarbon = \Carbon\Carbon::parse($date);

if ($dateCarbon->isYesterday() && $job->status == 'scheduled') {
    $detectDay = 'is-yesterday';
} elseif (!$dateCarbon->isToday() && $job->status == 'scheduled') {
    $detectDay = 'not-today';
} else {
    $detectDay = '';
}
@endphp

<div class="col-md-6 col-lg-4">
    <div class="card job-card p-3 h-100 {{$detectDay}}">

        <!-- Status Badge -->
        <div class="status-badge">
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

        <!-- Tipe Job -->
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

            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-secondary w-100 mb-2 fw-bold btn-action" style="border-style: dashed;">
                <i class="bi bi-info-circle me-1"></i> Detail
            </a>

            @if(!\Carbon\Carbon::parse($date)->isToday() && $job->status == 'scheduled')
            <button class="btn w-100 fw-bold btn-action shadow-sm disabled btn-secondary"><i class="bi bi-lock-fill"></i> Job Terkunci</button>
            @else

            @if($job->status == 'scheduled')
            <form action="{{ route('crew.progress', ['job' => $job->id, 'status' => 'otw']) }}" method="POST">
                @csrf
                <button class="btn btn-primary w-100 fw-bold btn-action shadow-sm"><i class="bi bi-scooter me-1"></i> OTW Lokasi</button>
            </form>
            @elseif($job->status == 'otw')
            <form action="{{ route('crew.progress', ['job' => $job->id, 'status' => 'arrived']) }}" method="POST">
                @csrf
                <button class="btn btn-info text-white w-100 fw-bold btn-action shadow-sm"><i class="bi bi-geo-alt-fill me-1"></i> Sampai</button>
            </form>
            @elseif($job->status == 'arrived')
            <form action="{{ route('crew.progress', ['job' => $job->id, 'status' => 'ongoing']) }}" method="POST">
                @csrf
                <button class="btn btn-warning text-dark w-100 fw-bold btn-action shadow-sm"><i class="bi bi-play-circle-fill me-1"></i> Mulai</button>
            </form>
            @elseif($job->status == 'ongoing')
            <button class="btn btn-success w-100 fw-bold btn-action shadow-sm" data-bs-toggle="modal" data-bs-target="#finishModal{{ $job->id }}">
                <i class="bi bi-check-lg me-1"></i> Selesai
            </button>
            @else
            <button class="btn btn-secondary w-100 btn-action bg-opacity-25 border-0 text-muted" disabled>Selesai</button>
            @endif

            @endif

        </div>
    </div>
</div>

<!-- Modal Finish -->
@if($job->status == 'ongoing')
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
                    <p class="text-muted small">Job: <strong class="text-dark">{{ $job->job_title }}</strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">METODE PEMBAYARAN KLIEN</label>
                        <select name="payment_method" class="form-select" id="payMethod{{ $job->id }}" onchange="toggleProof({{ $job->id }})">
                            <option value="unpaid">Belum Bayar</option>
                            <option value="tf">Transfer</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="proofDiv{{ $job->id }}">
                        <label class="form-label fw-bold small text-danger">UPLOAD BUKTI UANG / KUITANSI</label>
                        <input type="file" name="proof" class="form-control">
                    </div>

                    <!-- NEW: INPUT NOMINAL JIKA CASH (Jika Boss belum set harga) -->
                    @if($job->amount == 0)
                    <div class="mb-3 d-none" id="amountDiv{{ $job->id }}">
                        <label class="form-label fw-bold small text-success">TOTAL UANG DITERIMA (RP)</label>
                        <input type="number" name="amount" class="form-control border-success" placeholder="Contoh: 500000">
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif