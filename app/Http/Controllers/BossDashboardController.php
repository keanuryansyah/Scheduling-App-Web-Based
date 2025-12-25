<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Transaction;
use Carbon\Carbon;

class BossDashboardController extends Controller
{
    public function index(Request $request)
    {
        $bossId = auth()->id();

        // 1. FILTER WAKTU
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $filterMonth = $request->month;
        $filterYear = $request->year ?? date('Y');
        $filterJobType = $request->job_type;

        // 2. QUERY JOB LIST (Tabel Utama)
        $jobsQuery = Job::with(['type', 'users', 'creator'])
                        ->orderByRaw("FIELD(status, 'scheduled', 'ongoing', 'done', 'canceled')")
                        ->orderBy('job_date', 'asc')
                        ->orderBy('start_time', 'asc');

        $statsJobQuery = Job::query();

        // Terapkan Filter
        if ($startDate && $endDate) {
            $jobsQuery->whereBetween('job_date', [$startDate, $endDate]);
            $statsJobQuery->whereBetween('job_date', [$startDate, $endDate]);
            $judulPeriode = Carbon::parse($startDate)->translatedFormat('d M') . " - " . Carbon::parse($endDate)->translatedFormat('d M Y');
        } elseif ($filterMonth) {
            $jobsQuery->whereMonth('job_date', $filterMonth)->whereYear('job_date', $filterYear);
            $statsJobQuery->whereMonth('job_date', $filterMonth)->whereYear('job_date', $filterYear);
            $judulPeriode = Carbon::createFromDate($filterYear, $filterMonth, 1)->translatedFormat('F Y');
        } else {
            $jobsQuery->whereYear('job_date', $filterYear);
            $statsJobQuery->whereYear('job_date', $filterYear);
            $judulPeriode = "Tahun $filterYear";
        }
        if ($filterJobType) {
            $jobsQuery->where('job_type', $filterJobType);
            $statsJobQuery->where('job_type', $filterJobType);
        }

        // 3. QUERY INCOME
        $incomeQuery = Transaction::where('user_id', $bossId)
            ->where('amount', '>', 0)
            ->whereHas('job', function ($q) use ($filterJobType, $startDate, $endDate, $filterMonth, $filterYear) {
                if ($filterJobType) $q->where('job_type', $filterJobType);
                if ($startDate && $endDate) {
                    $q->whereBetween('job_date', [$startDate, $endDate]);
                } elseif ($filterMonth) {
                    $q->whereMonth('job_date', $filterMonth)->whereYear('job_date', $filterYear);
                } else {
                    $q->whereYear('job_date', $filterYear);
                }
            });

        // 4. SIDEBAR LOGIC (NEW)
        
        // A. PERLU DITAGIH (Crew pilih 'unpaid')
        // Job status DONE, metode UNPAID, dan Boss belum terima uang
        $billingList = Job::where('status', 'done')
                        ->where('payment_method', 'unpaid')
                        ->whereDoesntHave('transactions', function($q) use ($bossId) {
                             $q->where('user_id', $bossId)->where('amount', '>', 0);
                        })
                        ->orderBy('job_date', 'desc')
                        ->get();

        // B. PERLU KONFIRMASI (Crew pilih 'tf', 'cash', 'vendor')
        // Job status DONE, metode BUKAN unpaid, tapi Boss belum terima uang (verifikasi)
        $confirmationList = Job::where('status', 'done')
                        ->whereIn('payment_method', ['tf', 'cash', 'vendor'])
                        ->whereDoesntHave('transactions', function($q) use ($bossId) {
                             $q->where('user_id', $bossId)->where('amount', '>', 0);
                        })
                        ->orderBy('job_date', 'desc')
                        ->get();

        // 5. HASIL AKHIR
        $todaysJobs = $jobsQuery->get();
        
        $stats = [
            'jobs_count' => $statsJobQuery->count(),
            'ongoing_jobs' => Job::where('status', 'ongoing')->count(),
            // Total notif di dashboard
            'unpaid_jobs' => $billingList->count() + $confirmationList->count(), 
            'monthly_income' => $incomeQuery->sum('amount')
        ];

        $allJobTypes = \App\Models\JobType::all();

        // Variable yang dikirim ke View: billingList & confirmationList
        return view('boss.dashboard', compact('stats', 'todaysJobs', 'billingList', 'confirmationList', 'allJobTypes', 'judulPeriode'));
    }
}