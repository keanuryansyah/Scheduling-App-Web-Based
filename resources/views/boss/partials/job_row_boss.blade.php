<tr>
    <!-- TANGGAL -->
    <!-- <td class="ps-4">
        <div class="fw-bold text-dark">{{ $job->job_date->translatedFormat('d M Y') }}</div>
        <div class="small text-muted">{{ $job->job_date->translatedFormat('l') }}</div>
    </td> -->
    <td class="ps-4">
        <span class="text-muted small">—</span>
    </td>


    <!-- JAM -->
    <td>
        <span class="badge bg-light text-dark border">
            {{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }}
        </span>
    </td>

    <!-- JOB & KLIEN -->
    <td>
        <div class="fw-bold text-dark">{{ $job->job_title }}</div>
        <div class="small text-muted mb-1">{{ $job->client_name }}</div>

        <!-- INFO CREATOR KECIL -->
        <div style="font-size: 10px;" class="fst-italic text-secondary bg-light px-2 py-1 rounded d-inline-block border">
            Added By:
            @if($job->creator->role->name == 'boss')
            <strong class="text-danger">BOSS</strong>
            @else
            <strong class="text-primary">{{ $job->creator->name }}</strong>
            @endif
        </div>
    </td>

    <!-- TIPE -->
    <td>
        <span class="badge text-white" style="background-color: {{ $job->type->badge_color ?? '#6c757d' }}">
            {{ $job->type->job_type_name }}
        </span>
    </td>

    <!-- CREW / EDITOR -->
    <td>
        <div class="d-flex align-items-center">
            @foreach($job->users as $crew)
            <div class="avatar-circle me-1" title="{{ $crew->name }}">{{ substr($crew->name, 0, 1) }}</div>
            @endforeach

            @php
            $assign = $job->assignments->whereNotNull('editor_id')->first();
            $editor = $assign ? \App\Models\User::find($assign->editor_id) : null;
            @endphp
            @if($editor)
            <div class="avatar-circle bg-warning text-dark me-1" title="Editor: {{ $editor->name }}">{{ substr($editor->name, 0, 1) }}</div>
            @endif
        </div>
    </td>

    <!-- STATUS -->
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

    <!-- AKSI (TOMBOL WA CANGGIH & DETAIL) -->
    <td class="text-end pe-4">
        <div class="btn-group">

            @if($job->users->isNotEmpty() || session('crew_changes'))
            @php
            $isSent = $job->wa_sent_at != null;

            // 1. Cek Session (Apakah baru saja di-update detik ini?)
            $changes = session('crew_changes');
            $isJustUpdated = $changes && $changes['job_id'] == $job->id;

            // 2. Cek Database (Apakah Crew di DB lebih baru dari WA terakhir?)
            // Kita pakai 'created_at' karena sync() membuat row baru
            $dbChanged = false;
            if ($isSent) {
            $lastAssign = $job->assignments->sortByDesc('created_at')->first();
            $lastAssignTime = $lastAssign ? $lastAssign->created_at : null;

            // Jika Assignment lebih baru dari WA Sent -> Berarti ada Crew Baru
            $dbChanged = $lastAssignTime && $lastAssignTime->gt($job->wa_sent_at);
            }
            @endphp

            {{-- KONDISI 1: ADA PERUBAHAN CREW (SESSION ATAU DB) --}}
            @if($isJustUpdated || $dbChanged)
            @php
            // Jika baru update -> Merah (Info Perubahan)
            // Jika refresh page (session hilang) tapi DB berubah -> Biru (Update Info)
            $btnClass = $isJustUpdated ? 'btn-danger' : 'btn-primary';
            $btnIcon = $isJustUpdated ? 'bi-exclamation-circle' : 'bi-arrow-repeat';
            $btnText = $isJustUpdated ? 'Info Perubahan' : 'Update Info';
            @endphp

            <div class="btn-group">
                <button type="button"
                    class="btn btn-sm {{ $btnClass }} dropdown-toggle"
                    data-bs-toggle="modal"
                    data-bs-target="#waModalBoss{{ $job->id }}">
                    <i class="bi {{ $btnIcon }}"></i> {{ $btnText }}
                </button>

                <!-- MODAL POPUP -->
                <div class="modal fade text-start" id="waModalBoss{{ $job->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-light py-2 px-3">
                                <h6 class="modal-title fw-bold text-dark" style="font-size: 0.9rem;">Pilih Penerima WA</h6>
                                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-2">
                                <div class="d-grid gap-2">

                                    <!-- A. KE CREW LAMA (Info Batal) -->
                                    @if($isJustUpdated && isset($changes['removed']))
                                    @foreach($changes['removed'] as $oldCrew)
                                    <a href="{{ route('jobs.sendWa', ['job' => $job->id, 'type' => 'cancel_person', 'no_update' => 1, 'target_phone' => $oldCrew->phone_number, 'target_name' => $oldCrew->name]) }}"
                                        target="_blank" class="btn btn-outline-danger text-start btn-sm p-2">
                                        <i class="bi bi-person-dash-fill me-2"></i>
                                        <span><strong class="d-block" style="font-size: 11px;">Batal ke:</strong> {{ $oldCrew->name }}</span>
                                    </a>
                                    @endforeach
                                    @elseif($hasCache)
                                    @php $oldData = Illuminate\Support\Facades\Cache::get('old_crew_' . $job->id); @endphp
                                    <a href="{{ route('jobs.sendWa', ['job' => $job->id, 'type' => 'cancel_person', 'no_update' => 1, 'target_phone' => $oldData['phone'], 'target_name' => $oldData['name']]) }}"
                                        target="_blank" class="btn btn-outline-danger text-start btn-sm p-2">
                                        <i class="bi bi-person-dash-fill me-2"></i>
                                        <span><strong class="d-block" style="font-size: 11px;">Batal ke:</strong> {{ $oldData['name'] }}</span>
                                    </a>
                                    @endif

                                    <!-- B. KE CREW BARU (Info Job Baru) -->
                                    <a href="{{ route('jobs.sendWa', ['job' => $job->id, 'type' => 'new', 'target' => 'current', 'no_update' => 1]) }}"
                                        target="_blank" class="btn btn-outline-primary text-start btn-sm p-2">
                                        <i class="bi bi-person-plus-fill me-2"></i>
                                        <span><strong class="d-block" style="font-size: 11px;">Job Baru ke:</strong> {{ $job->users->first()->name ?? 'Crew' }}</span>
                                    </a>

                                    <hr class="my-1">

                                    <!-- C. TANDAI SELESAI -->
                                    <a href="{{ route('jobs.markWaSent', $job->id) }}" class="btn btn-success fw-bold btn-sm">
                                        <i class="bi bi-check-lg"></i> Tandai Selesai Dikirim
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONDISI 2: SUDAH SENT & AMAN --}}
            @elseif($isSent)
            <button class="btn btn-sm btn-secondary disabled" title="Sudah dikirim pada {{ $job->wa_sent_at->format('d M H:i') }}">
                <i class="bi bi-check2-all"></i> Sent
            </button>

            {{-- KONDISI 3: BELUM PERNAH KIRIM --}}
            @else
            <a href="{{ route('jobs.sendWa', ['job' => $job->id, 'type' => 'new']) }}" target="_blank"
                class="btn btn-sm btn-outline-success"
                onclick="setTimeout(function(){ location.reload(); }, 1000);">
                <i class="bi bi-whatsapp"></i> Kirim WA
            </a>
            @endif

            @endif

            <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm ms-1"><i class="bi bi-eye"></i></a>
        </div>
    </td>
</tr>