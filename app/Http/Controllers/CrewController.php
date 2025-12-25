<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CrewController extends Controller
{
    // 1. Dashboard Crew (List Job Saya)
    public function index()
    {
        $userId = auth()->id();

        // GANTI INI: Rentang Bulan Ini (Tanggal 1 s/d Akhir Bulan)
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth();

        $myJobs = Job::whereHas('assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                // Ambil job dalam rentang BULAN INI
                $query->whereBetween('job_date', [$startOfMonth, $endOfMonth])
                    // Tetap tampilkan job ONGOING (meskipun bulan lalu, biar ga ilang)
                    ->orWhere('status', 'ongoing');
            })
            ->where('status', '!=', 'canceled')
            ->orderByRaw("FIELD(status, 'ongoing', 'scheduled', 'done')")
            ->orderBy('job_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('crew.index', compact('myJobs'));
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

        DB::transaction(function () use ($request, $job) {
            
            // Update Status & Metode Bayar
            $job->status = 'done';
            $job->payment_method = $request->payment_method;
            $job->save();
            
        });

        return back()->with('success', 'Laporan selesai! Status diperbarui.');
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
}
