<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CrewController extends Controller
{
    // 1. Dashboard Crew (List Job Saya)
    public function index()
    {
        $userId = auth()->id();
        $today = Carbon::today();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. QUERY DASAR
        $query = Job::whereHas('assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        // 2. DATA UNTUK TAMPILAN TABS
        $activeJobs = (clone $query)
            ->whereIn('status', ['scheduled', 'otw', 'arrived', 'ongoing'])
            ->whereBetween('job_date', [$startOfMonth, $endOfMonth])
            ->orderBy('job_date', 'asc')->orderBy('start_time', 'asc')
            ->get();

        $completedJobs = (clone $query)
            ->where('status', 'done')
            ->whereBetween('job_date', [$startOfMonth, $endOfMonth])
            ->orderBy('job_date', 'desc')->orderBy('start_time', 'desc')
            ->get();

        // 3. LOGIKA POPUP PINTAR (SESSION BASED - SETIAP LOGIN)
        
        // A. Hitung Total Job Hari Ini (Spesifik tanggal hari ini)
        $todayJobCount = Job::whereHas('assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->whereDate('job_date', $today)
            ->where('status', '!=', 'canceled')
            ->count();

        // B. Hitung yang sudah Selesai Hari Ini
        $doneToday = Job::whereHas('assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->whereDate('job_date', $today)
            ->where('status', 'done')
            ->count();

        // C. Hitung Sisa
        $remainingToday = $todayJobCount - $doneToday;

        // D. Cek Session (Session hilang saat Logout)
        $sessionKey = 'popup_seen_' . $userId . '_' . $today->format('Y-m-d');
        $showPopup = false;
        $popupData = [];

        // Muncul jika ada job DAN belum dilihat di sesi login ini
        if ($todayJobCount > 0 && !session()->has($sessionKey)) {
            $showPopup = true;

            // Simpan Session (Akan reset kalau logout)
            session()->put($sessionKey, true);

            // TEXT MOTIVASI DINAMIS
            if ($doneToday == 0) {
                // Pagi / Belum mulai
                $popupData = [
                    'title' => "Halo, " . auth()->user()->name . "! 👋",
                    'body'  => "Hari ini ada <strong class='text-primary fs-4'>$todayJobCount Job</strong> yang harus dihajar!",
                    'quote' => "Siapkan energimu, mari kita mulai!",
                    'btn'   => "Siap, Gaspol! 🚀"
                ];
            } elseif ($remainingToday > 0) {
                // Progress Siang
                $popupData = [
                    'title' => "Keep Going, " . auth()->user()->name . "! 🔥",
                    'body'  => "Mantap! Sudah <strong class='text-success'>$doneToday dari $todayJobCount</strong> selesai.<br>Tinggal <strong class='text-warning fs-4'>$remainingToday Job</strong> lagi.",
                    'quote' => "Sedikit lagi, tetap fokus dan semangat!",
                    'btn'   => "Lanjut Kerja! 💪"
                ];
            } else {
                // Selesai Semua
                $popupData = [
                    'title' => "Misi Selesai! 🎉",
                    'body'  => "Luar biasa! Semua <strong class='text-success'>$todayJobCount Job</strong> hari ini sudah beres.",
                    'quote' => "Terima kasih atas kerja kerasmu hari ini. Istirahatlah!",
                    'btn'   => "Tutup & Santai ☕"
                ];
            }
        }

        return view('crew.index', compact('activeJobs', 'completedJobs', 'todayJobCount', 'showPopup', 'popupData'));
    }

    // 2. Mulai Kerja (Update status ke Ongoing)
    public function startJob(Job $job)
    {
        if ($job->status == 'scheduled') {
            $job->update(['status' => 'ongoing']);
            return back()->with('success', 'Selamat bekerja! Status berubah jadi Ongoing.');
        }
        return back()->with('error', 'Job tidak bisa dimulai.');
    }

    // 3. Selesai Kerja (Update status ke Done & Bagi Hasil)
    public function finishJob(Request $request, Job $job)
    {
        // if ($request->payment_method == 'cash') {
        //     $request->validate(['proof' => 'required|image|max:2048']);
        // }

        DB::transaction(function () use ($request, $job) {
            if ($request->hasFile('proof')) {
                $path = $request->file('proof')->store('proofs', 'public');
                $job->proof = $path;
            }

            $job->update([
                'status' => 'done',
                'payment_method' => $request->payment_method,
                'finished_at' => now() // Catat waktu selesai real
            ]);
            
            // Hapus bagian bagi hasil (sesuai request sebelumnya)
        });

        return back()->with('success', 'Laporan selesai! Status: DONE.');
    }

    // 4. Detail Job Khusus Crew
    public function show($id)
    {
        // Cari job, dan pastikan crew yang login terdaftar di job tersebut (Security)
        $job = Job::with('users')->findOrFail($id);

        // Cek apakah user yang login ada di daftar crew job ini
        if (!$job->users->contains(auth()->id())) {
            abort(403, 'Akses ditolak. Ini bukan job Anda.');
        }

        return view('crew.show', compact('job'));
    }

    public function updateProgress(Request $request, Job $job, $status)
    {
        $userId = auth()->id();

        // VALIDASI URUTAN STATUS
        $flow = ['scheduled', 'otw', 'arrived', 'ongoing', 'done'];
        // (Logic validasi urutan bisa ditambah jika perlu ketat)

        DB::transaction(function () use ($job, $status, $userId) {

            // --- LOGIKA AUTO-CLOSE JOB SEBELUMNYA (JIKA STATUS = OTW) ---
            if ($status == 'otw') {
                // Cari job lain milik user ini yang statusnya masih gantung (otw/arrived/ongoing)
                // Dan bukan job yang sedang diproses saat ini
                $stuckJobs = Job::whereHas('assignments', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                    ->whereIn('status', ['otw', 'arrived', 'ongoing'])
                    ->where('id', '!=', $job->id) // Jangan close diri sendiri
                    ->get();

                foreach ($stuckJobs as $stuckJob) {
                    // Paksa Selesai
                    $stuckJob->update([
                        'status' => 'done',
                        'finished_at' => now(),
                        'payment_method' => 'unpaid', // Paksa unpaid karena ga ada bukti upload
                        'notes' => $stuckJob->notes . " (Auto-closed by System karena Crew mulai job lain: {$job->job_title})",
                    ]);
                }
            }
            // ------------------------------------------------------------

            // Update status job saat ini
            $dataToUpdate = ['status' => $status];

            // Catat Waktu (Timeline)
            if ($status == 'otw') $dataToUpdate['otw_at'] = now();
            if ($status == 'arrived') $dataToUpdate['arrived_at'] = now();
            if ($status == 'ongoing') $dataToUpdate['started_at'] = now(); // Start kerja

            $job->update($dataToUpdate);
        });

        return back()->with('success', 'Status diperbarui: ' . strtoupper($status));
    }
}
