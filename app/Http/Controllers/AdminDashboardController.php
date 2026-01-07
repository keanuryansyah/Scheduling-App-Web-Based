<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobType;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // FILTER VARIABLES
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $filterMonth = $request->month;
        $filterYear = $request->year ?? date('Y');
        $filterJobType = $request->job_type;

        // QUERY DASAR JOBS
        $jobsQuery = Job::with(['type', 'users'])
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

        // LOGIKA FILTER (Sama seperti Boss)
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

        // --- LOGIKA SIDEBAR TAGIHAN (DITAMBAHKAN UNTUK ADMIN) ---
        // Catatan: Admin bisa melihat semua tagihan (tidak difilter per user id boss, tapi global)

        // 1. PERLU DITAGIH (Unpaid & Belum ada transaksi masuk)
        $billingList = Job::where('status', 'done')
            ->where('payment_method', 'unpaid')
            ->whereDoesntHave('transactions', function ($q) {
                $q->where('amount', '>', 0);
            })
            ->orderBy('job_date', 'desc')
            ->get();

        // 2. PERLU KONFIRMASI (TF/Cash/Vendor & Belum ada transaksi masuk)
        $confirmationList = Job::where('status', 'done')
            ->whereIn('payment_method', ['tf', 'cash', 'vendor'])
            ->whereDoesntHave('transactions', function ($q) {
                $q->where('amount', '>', 0);
            })
            ->orderBy('job_date', 'desc')
            ->get();

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
            ->sortByDesc('job_date');

        $completedJobs = $todaysJobs
            ->where('status', 'done')
            ->where('editor_status', 'completed')
            ->sortByDesc('job_date');

        $canceledJobs = $todaysJobs
            ->where('status', 'canceled')
            ->sortByDesc('job_date');

        $allJobTypes = JobType::all();

        // STATISTIK
        $stats = [
            'total_jobs' => $statsJobQuery->count(),
            'scheduled'  => (clone $statsJobQuery)->where('status', 'scheduled')->count(),
            'ongoing'    => (clone $statsJobQuery)->whereIn('status', ['otw', 'arrived', 'ongoing'])->count(),
            'done'       => (clone $statsJobQuery)->where('status', 'done')->count(),
            // Tambahan info tagihan
            'unpaid_jobs' => $billingList->count() + $confirmationList->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'todaysJobs',
            'activeJobs',
            'waitingEditorJobs',
            'completedJobs',
            'canceledJobs',
            'allJobTypes',
            'judulPeriode',
            'billingList',
            'confirmationList'
        ));
    }
}
