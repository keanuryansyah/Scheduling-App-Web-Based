<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Saya - Crew</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #f1f5f9; font-family: 'Plus Jakarta Sans', sans-serif; }
        .navbar-crew { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; padding: 1rem 0; margin-bottom: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .job-card { border: none; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); transition: all 0.3s ease; background: white; height: 100%; position: relative; overflow: hidden; }
        .status-badge { position: absolute; top: 16px; right: 16px; }
        .card-saldo { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3); margin-bottom: 24px; }
        .btn-action { border-radius: 10px; font-weight: 600; padding: 10px; }
        
        /* Date Header Style */
        .date-header { font-weight: 700; color: #475569; margin-top: 20px; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; font-size: 1rem; }
        .date-header::after { content: ''; flex-grow: 1; height: 1px; background: #e2e8f0; }
        
        /* Tabs Style */
        .nav-pills .nav-link { color: #64748b; font-weight: 600; border-radius: 50rem; padding: 8px 20px; }
        .nav-pills .nav-link.active { background-color: #3b82f6; color: white; }
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
                <button class="btn btn-sm btn-outline-light rounded-pill px-3"><i class="bi bi-box-arrow-right"></i></button>
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

        <!-- TABS NAV -->
        <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-aktif-tab" data-bs-toggle="pill" data-bs-target="#pills-aktif" type="button">
                    🚀 Jadwal Aktif <span class="badge bg-white text-primary ms-1">{{ $activeJobs->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-selesai-tab" data-bs-toggle="pill" data-bs-target="#pills-selesai" type="button">
                    ✅ Riwayat <span class="badge bg-secondary text-white ms-1">{{ $completedJobs->count() }}</span>
                </button>
            </li>
        </ul>

        <!-- KONTEN TABS -->
        <div class="tab-content" id="pills-tabContent">
            
            <!-- TAB 1: JOB AKTIF -->
            <div class="tab-pane fade show active" id="pills-aktif" role="tabpanel">
                @php
                    $groupedActive = $activeJobs->groupBy(function($item) { return $item->job_date->format('Y-m-d'); });
                @endphp

                @forelse($groupedActive as $date => $jobs)
                    <!-- HEADER TANGGAL -->
                    <div class="date-header">
                        <i class="bi bi-calendar-day text-primary"></i>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        @if(\Carbon\Carbon::parse($date)->isToday()) <span class="badge bg-danger ms-2">HARI INI</span> @endif
                    </div>

                    <div class="row g-3">
                        @foreach($jobs as $job)
                            @include('crew.partials.job_card', ['job' => $job])
                        @endforeach
                    </div>
                @empty
                    <div class="text-center py-5 opacity-50">
                        <i class="bi bi-calendar2-check fs-1"></i>
                        <p class="mt-2">Tidak ada jadwal aktif bulan ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- TAB 2: JOB SELESAI -->
            <div class="tab-pane fade" id="pills-selesai" role="tabpanel">
                @php
                    $groupedCompleted = $completedJobs->groupBy(function($item) { return $item->job_date->format('Y-m-d'); });
                @endphp

                @forelse($groupedCompleted as $date => $jobs)
                    <div class="date-header text-muted">
                        <i class="bi bi-check-circle text-success"></i>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        @if(\Carbon\Carbon::parse($date)->isToday()) <span class="badge bg-danger ms-2">HARI INI</span> @endif
                    </div>

                    <div class="row g-3">
                        @foreach($jobs as $job)
                            @include('crew.partials.job_card', ['job' => $job])
                        @endforeach
                    </div>
                @empty
                    <div class="text-center py-5 opacity-50">
                        <p>Belum ada riwayat pekerjaan.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- MODAL POPUP SEMANGAT (Hanya Muncul Jika Ada Job Hari Ini & Belum Dilihat) -->
    @if(isset($showPopup) && $showPopup)
    <div class="modal fade" id="welcomeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">
                <div class="mb-3">
                    <!-- Icon Dinamis Sesuai Progress -->
                    @if(str_contains($popupData['title'] ?? '', 'Selesai'))
                        <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" width="80">
                    @elseif(str_contains($popupData['title'] ?? '', 'Keep'))
                        <img src="https://cdn-icons-png.flaticon.com/512/4760/4760249.png" width="80">
                    @else
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80">
                    @endif
                </div>
                
                <h4 class="fw-bold text-dark mb-2">{{ $popupData['title'] ?? 'Halo!' }}</h4>
                <p class="text-muted mb-3">{!! $popupData['body'] ?? 'Semangat kerjanya!' !!}</p>
                <div class="alert alert-light border border-secondary border-opacity-10 rounded-3 py-2 px-3 fst-italic text-secondary" style="font-size: 0.9rem;">
                    "{{ $popupData['quote'] ?? 'Do your best!' }}"
                </div>
                <button type="button" class="btn btn-primary w-100 rounded-pill mt-3 fw-bold" data-bs-dismiss="modal">
                    {{ $popupData['btn'] ?? 'Oke, Siap!' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Popup Welcome -->
    @if(isset($showPopup) && $showPopup)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
            myModal.show();
        });
    </script>
    @endif

    <!-- Script Toggle Proof (Untuk Modal Finish) -->
    <!-- <script>
        function toggleProof(id) {
            let method = document.getElementById('payMethod' + id).value;
            let divProof = document.getElementById('proofDiv' + id);
            
            // Note: amountDiv sudah dihapus dari view partial karena Crew tidak input harga lagi
            if (method === 'cash') {
                if(divProof) divProof.classList.remove('d-none');
            } else {
                if(divProof) divProof.classList.add('d-none');
            }
        }
    </script> -->

</body>
</html>