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

        // 1. FILTER
        $startDate     = $request->start_date;
        $endDate       = $request->end_date;
        $filterMonth   = $request->month;
        $filterYear    = $request->year ?? date('Y');
        $filterJobType = $request->job_type;

        // 2. QUERY JOB LIST (FIXED ORDER)
        $jobsQuery = Job::with(['type', 'users', 'creator'])
            ->orderBy('job_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->orderByRaw("                    
        CASE status
            WHEN 'ongoing' THEN 1
            WHEN 'arrived' THEN 2
            WHEN 'otw' THEN 3
            WHEN 'scheduled' THEN 4
            WHEN 'done' THEN 5
            WHEN 'canceled' THEN 6
            ELSE 7
        END
    ");

        $statsJobQuery = Job::query();

        // 3. APPLY FILTER
        if ($startDate && $endDate) {
            $jobsQuery->whereBetween('job_date', [$startDate, $endDate]);
            $statsJobQuery->whereBetween('job_date', [$startDate, $endDate]);
            $judulPeriode = Carbon::parse($startDate)->translatedFormat('d M') . " - " .
                Carbon::parse($endDate)->translatedFormat('d M Y');
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

        // 4. QUERY INCOME
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

        // 5. SIDEBAR NOTIFICATION
        $billingList = Job::where('status', 'done')
            ->where('payment_method', 'unpaid')
            ->whereDoesntHave('transactions', function ($q) use ($bossId) {
                $q->where('user_id', $bossId)->where('amount', '>', 0);
            })
            ->orderBy('job_date', 'desc')
            ->get();

        $confirmationList = Job::where('status', 'done')
            ->whereIn('payment_method', ['tf', 'cash', 'vendor'])
            ->whereDoesntHave('transactions', function ($q) use ($bossId) {
                $q->where('user_id', $bossId)->where('amount', '>', 0);
            })
            ->orderBy('job_date', 'desc')
            ->get();

        // 6. FINAL RESULT
        $todaysJobs = $jobsQuery->get();

        // KHUSUS JADWAL AKTIF → ASC
        $activeJobs = $todaysJobs
            ->whereIn('status', ['scheduled', 'otw', 'arrived', 'ongoing'])
            ->sortBy([
                ['job_date', 'asc'],
                ['start_time', 'asc'],
            ]);


        $waitingEditorJobs = $todaysJobs
            ->where('status', 'done')
            ->whereIn('editor_status', ['idle', 'editing'])
            ->sortBy([
                ['job_date', 'desc'],
                ['start_time', 'asc'],
            ]);

        $completedJobs = $todaysJobs
            ->where('status', 'done')
            ->where('editor_status', 'completed')
            ->sortBy([
                ['job_date', 'desc'],
                ['start_time', 'asc'],
            ]);

        $canceledJobs = $todaysJobs
            ->where('status', 'canceled')
            ->sortBy([
                ['job_date', 'desc'],
                ['start_time', 'asc'],
            ]);

        $stats = [
            'jobs_count'     => $statsJobQuery->count(),
            'ongoing_jobs'   => Job::where('status', 'ongoing')->count(),
            'unpaid_jobs'    => $billingList->count() + $confirmationList->count(),
            'monthly_income' => $incomeQuery->sum('amount'),
        ];

        $allJobTypes = \App\Models\JobType::all();

        return view('boss.dashboard', compact(
            'stats',
            'todaysJobs',
            'activeJobs',
            'waitingEditorJobs',
            'completedJobs',
            'canceledJobs',
            'billingList',
            'confirmationList',
            'allJobTypes',
            'judulPeriode'
        ));
    }
}
