<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job - {{ $job->job_title }}</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body { 
            background-color: #f0f2f5; 
            font-family: 'Poppins', sans-serif;
        }
        .card-modern { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            background: white;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); /* Warna Oranye untuk Edit */
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 20px 30px;
        }
        .form-floating > label { color: #6c757d; }
        .form-control:focus, .form-select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.15);
        }
        option:disabled {
            color: #dc3545;
            background-color: #f8d7da;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card card-modern">
                <!-- Header Keren -->
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Jadwal</h4>
                        <small class="opacity-75">Perubahan data untuk: {{ $job->job_title }}</small>
                    </div>
                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-light btn-sm text-warning fw-bold rounded-pill px-3">
                        <i class="bi bi-x-lg"></i> Batal
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('jobs.update', $job->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- BAGIAN 1: INFO KLIEN -->
                        <h6 class="text-uppercase text-warning fw-bold mb-3 small ls-1">Informasi Dasar</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="job_title" class="form-control" id="jobTitle" value="{{ $job->job_title }}" required>
                                    <label for="jobTitle">Judul Pekerjaan</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="client_name" class="form-control" id="clientName" value="{{ $job->client_name }}" required>
                                    <label for="clientName">Nama Klien</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="client_phone" class="form-control" id="clientPhone" value="{{ $job->client_phone }}" required>
                                    <label for="clientPhone">No. HP / WhatsApp</label>
                                </div>
                            </div>
                        </div>

                        <!-- BAGIAN 2: WAKTU & TEMPAT -->
                        <h6 class="text-uppercase text-warning fw-bold mb-3 small ls-1 border-top pt-3">Waktu & Lokasi</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="date" name="job_date" id="jobDate" class="form-control" value="{{ $job->job_date->format('Y-m-d') }}" required>
                                    <label for="jobDate">Tanggal</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="time" name="start_time" id="startTime" class="form-control" value="{{ \Carbon\Carbon::parse($job->start_time)->format('H:i') }}" required>
                                    <label for="startTime">Jam Mulai</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="time" name="end_time" id="endTime" class="form-control" value="{{ \Carbon\Carbon::parse($job->end_time)->format('H:i') }}" required>
                                    <label for="endTime">Jam Selesai</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="location" class="form-control" id="location" style="height: 80px">{{ $job->location }}</textarea>
                                    <label for="location">Lokasi Lengkap / Link Maps</label>
                                </div>
                            </div>
                        </div>

                        <!-- BAGIAN 3: DETAIL PEKERJAAN -->
                        <h6 class="text-uppercase text-warning fw-bold mb-3 small ls-1 border-top pt-3">Detail & Tim</h6>
                        <div class="row g-3">
                            <!-- Catatan -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="notes" class="form-control" id="notes" style="height: 80px">{{ $job->notes }}</textarea>
                                    <label for="notes">Catatan Tambahan</label>
                                </div>
                            </div>

                            <!-- Tipe Job & Harga -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="job_type" id="jobType" class="form-select" onchange="toggleCrewSection()">
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" 
                                                data-name="{{ strtolower($type->job_type_name) }}"
                                                {{ $job->job_type == $type->id ? 'selected' : '' }}>
                                                {{ $type->job_type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="jobType">Jenis Pekerjaan</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" name="amount" class="form-control" id="amount" value="{{ $job->amount }}" required>
                                    <label for="amount">Total Harga Job (Rp)</label>
                                </div>
                            </div>

                            <!-- ASSIGN CREW (DROPDOWN UPDATE) -->
                            <div class="col-12" id="crewAssignmentSection">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <label class="fw-bold text-dark small">UPDATE CREW BERTUGAS</label>
                                            <span id="loadingCheck" class="text-primary small d-none">
                                                <div class="spinner-border spinner-border-sm" role="status"></div> Cek Jadwal...
                                            </span>
                                        </div>
                                        
                                        <!-- Kita simpan ID Crew yang sedang bertugas sekarang di variable JS -->
                                        @php 
                                            $currentCrewId = $job->users->first()->id ?? ''; 
                                        @endphp

                                        <div class="form-floating">
                                            <select name="crew_ids[]" id="crewSelect" class="form-select">
                                                <option value="" {{ empty($currentCrewId) ? 'selected' : '' }}>-- Tidak Ada Crew --</option>
                                                @foreach($crews as $crew)
                                                    <option value="{{ $crew->id }}" id="optCrew{{ $crew->id }}"
                                                        {{ $currentCrewId == $crew->id ? 'selected' : '' }}>
                                                        {{ $crew->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="crewSelect">Pilih Nama Crew</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-warning text-white btn-lg py-3 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-check-circle-fill me-2"></i> UPDATE JADWAL
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT LOGIKA UI & AJAX -->
<script>
    const jobDateInput = document.getElementById('jobDate');
    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');
    const loadingText = document.getElementById('loadingCheck');
    const jobTypeSelect = document.getElementById('jobType');
    const crewSection = document.getElementById('crewAssignmentSection');
    const crewSelect = document.getElementById('crewSelect');
    
    // ID Crew yang sedang bertugas saat ini (dari PHP)
    const currentAssignedId = "{{ $currentCrewId }}";

    // 1. FUNGSI SHOW/HIDE CREW
    function toggleCrewSection() {
        const selectedOption = jobTypeSelect.options[jobTypeSelect.selectedIndex];
        const typeName = selectedOption.getAttribute('data-name'); 

        if (typeName && typeName.includes('edit')) {
            crewSection.classList.add('d-none');
            crewSelect.value = ""; // Kosongkan crew jika jadi editor
        } else {
            crewSection.classList.remove('d-none');
        }
    }

    // 2. FUNGSI CEK BENTROK
    async function checkAvailability() {
        const date = jobDateInput.value;
        const start = startTimeInput.value;
        const end = endTimeInput.value;

        // Reset Dropdown
        const options = crewSelect.querySelectorAll('option');
        options.forEach(opt => {
            if (opt.value) {
                opt.disabled = false;
                opt.text = opt.text.replace(' (Sibuk)', '');
            }
        });

        if (date && start && end) {
            loadingText.classList.remove('d-none');
            
            try {
                const response = await fetch(`{{ route('api.checkAvailability') }}?date=${date}&start=${start}&end=${end}`);
                const busyCrewIds = await response.json();

                busyCrewIds.forEach(id => {
                    // PENTING: Jangan disable jika itu adalah crew yang sedang mengerjakan job INI sendiri
                    // (Supaya tidak bentrok dengan diri sendiri saat edit)
                    if (id != currentAssignedId) {
                        const option = document.getElementById('optCrew' + id);
                        if (option) {
                            option.disabled = true;
                            option.text = option.text + ' (Sibuk)';
                        }
                    }
                });

                // Cek jika pilihan saat ini (bukan current assigned) jadi bentrok
                const selectedVal = crewSelect.value;
                if (selectedVal && selectedVal != currentAssignedId && busyCrewIds.includes(parseInt(selectedVal))) {
                    crewSelect.value = "";
                    alert("Crew yang dipilih ternyata sibuk di jam baru ini.");
                }

            } catch (error) {
                console.error('Error cek jadwal:', error);
            } finally {
                loadingText.classList.add('d-none');
            }
        }
    }

    // Event Listeners
    jobDateInput.addEventListener('change', checkAvailability);
    startTimeInput.addEventListener('change', checkAvailability);
    endTimeInput.addEventListener('change', checkAvailability);

    // Jalankan saat load (untuk sembunyikan kolom edit jika defaultnya edit)
    document.addEventListener("DOMContentLoaded", function() {
        toggleCrewSection();
        // Opsional: Cek availability saat load juga (tapi hati2 logic self-check)
        checkAvailability(); 
    });
</script>

</body>
</html>