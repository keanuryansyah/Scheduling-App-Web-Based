<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Job Baru</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Flatpickr (Timepicker Keren 24 Jam) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: white;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 20px 30px;
        }

        .form-floating>label {
            color: #6c757d;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
        }

        option:disabled {
            color: #dc3545;
            background-color: #f8d7da;
            font-style: italic;
        }

        /* Flatpickr Custom */
        .flatpickr-input[readonly] {
            background-color: #fff !important;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card card-modern">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i> Buat Jadwal Baru</h4>
                        <a href="{{ auth()->user()->role->name == 'admin' ? route('admin.dashboard') : route('boss.dashboard') }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>

                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('jobs.store') }}" method="POST">
                            @csrf

                            <!-- BAGIAN 1: PILIH TIPE -->
                            <div class="mb-4">
                                <div class="form-floating">
                                    <select name="job_type" id="jobType" class="form-select border-primary" required>
                                        <option value="" selected disabled>-- Pilih Jenis Pekerjaan --</option>
                                        @foreach($types as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->job_type_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label for="jobType" class="fw-bold text-primary">Jenis Pekerjaan</label>
                                </div>
                            </div>

                            <!-- BAGIAN 2: INFO UMUM -->
                            <h6 class="text-uppercase text-secondary fw-bold mb-3 small ls-1 border-bottom pb-2">Informasi Umum</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" name="job_title" class="form-control" id="jobTitle" placeholder="Judul" required>
                                        <label for="jobTitle">Judul Pekerjaan</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="client_name" class="form-control" id="clientName" placeholder="Nama" required>
                                        <label for="clientName">Nama Klien</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="client_phone" class="form-control" id="clientPhone" placeholder="08.." required>
                                        <label for="clientPhone">No. HP / WhatsApp</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" name="job_date" id="jobDate" class="form-control" required>
                                        <label for="jobDate">Tanggal (Deadline/Acara)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <textarea name="notes" class="form-control" id="notes" style="height: 120px" placeholder="Catatan"></textarea>
                                        <label for="notes">Catatan Tambahan klien. (*WM)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- BAGIAN 3: OPERASIONAL LAPANGAN -->
                            <h6 class="text-uppercase text-secondary fw-bold mb-3 small ls-1 border-bottom pb-2">Operasional Lapangan</h6>
                            <div class="row g-3">
                                <!-- UPDATE: Ganti type="time" jadi type="text" untuk Flatpickr -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="start_time" id="startTime" class="form-control time-picker" placeholder="Pilih Jam" required>
                                        <label for="startTime">Jam Mulai (24 Jam)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="end_time" id="endTime" class="form-control time-picker" placeholder="Pilih Jam" required>
                                        <label for="endTime">Jam Selesai (24 Jam)</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="location" class="form-control" id="location" style="height: 100px" placeholder="Lokasi"></textarea>
                                        <label for="location">Lokasi Lengkap / Link Maps</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="number" name="amount" class="form-control" id="amount" placeholder="0">
                                        <label for="amount">Total Harga Job (Rp) - Boleh 0</label>
                                    </div>
                                </div>

                                <!-- ASSIGN CREW -->
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <label class="fw-bold text-dark small"><i class="bi bi-camera-video-fill me-1"></i> TUGASKAN CREW</label>
                                                <span id="loadingCheck" class="text-primary small d-none">
                                                    <div class="spinner-border spinner-border-sm" role="status"></div> Cek Jadwal...
                                                </span>
                                            </div>
                                            <div class="form-floating">
                                                <select name="crew_ids[]" id="crewSelect" class="form-select user-select" required>
                                                    <option value="" selected disabled>-- Pilih Crew (Wajib) --</option>
                                                    @foreach($crews as $crew)
                                                    <option value="{{ $crew->id }}" id="optUser{{ $crew->id }}">
                                                        {{ $crew->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label>Pilih Crew <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" class="btn btn-primary btn-lg py-3 rounded-pill fw-bold shadow-sm">
                                    <i class="bi bi-save me-2"></i> SIMPAN JADWAL
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        const jobDateInput = document.getElementById('jobDate');
        const startTimeInput = document.getElementById('startTime');
        const endTimeInput = document.getElementById('endTime');
        const crewSelect = document.getElementById('crewSelect');

        // 1. INISIALISASI FLATPICKR (24 JAM)
        const timeConfig = {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true, // INI KUNCINYA AGAR 24 JAM (00-23)
            disableMobile: "true", // Paksa tampilan desktop agar format tetap terjaga di HP
            onClose: function(selectedDates, dateStr, instance) {
                // Panggil cek bentrok setiap kali jam dipilih/ditutup
                checkAvailability();
            }
        };

        flatpickr("#startTime", timeConfig);
        flatpickr("#endTime", timeConfig);

        // 2. FUNGSI CEK BENTROK
        // async function checkAvailability() {
        //     const date = jobDateInput.value;
        //     const start = startTimeInput.value;
        //     const end = endTimeInput.value;

        //     // Reset dropdown dulu
        //     document.querySelectorAll('.user-select option').forEach(opt => {
        //         if (opt.value) {
        //             opt.disabled = false;
        //             opt.text = opt.text.replace(' (Sibuk)', '');
        //         }
        //     });

        //     // Hanya jalan kalau Tanggal & Jam diisi lengkap
        //     if (date && start && end) {
        //         document.getElementById('loadingCheck').classList.remove('d-none');

        //         try {
        //             const response = await fetch(`{{ route('api.checkAvailability') }}?date=${date}&start=${start}&end=${end}`);
        //             const busyUserIds = await response.json();

        //             // Tandai crew yang sibuk
        //             busyUserIds.forEach(id => {
        //                 const option = document.getElementById('optUser' + id);
        //                 if (option) {
        //                     option.disabled = true;
        //                     option.text = option.text + ' (Sibuk)';
        //                 }
        //             });

        //             // Reset pilihan jika crew yang dipilih ternyata sibuk
        //             if (crewSelect.value && busyUserIds.includes(parseInt(crewSelect.value))) {
        //                 crewSelect.value = "";
        //                 alert("Crew yang dipilih ternyata sibuk di jam tersebut!");
        //             }

        //         } catch (error) {
        //             console.error('Error cek jadwal:', error);
        //         } finally {
        //             document.getElementById('loadingCheck').classList.add('d-none');
        //         }
        //     }
        // }

        // // Event Listeners (Tanggal tetap pakai change biasa)
        // jobDateInput.addEventListener('change', checkAvailability);

        // Note: Event listener untuk Jam sudah dihandle oleh onClose Flatpickr di atas
    </script>

</body>

</html>