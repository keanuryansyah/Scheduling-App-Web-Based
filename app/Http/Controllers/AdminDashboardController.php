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

        // QUERY DASAR
        $jobsQuery = Job::with(['type', 'users'])
            ->orderByRaw("FIELD(status, 'scheduled', 'ongoing', 'done', 'canceled')")
            ->orderBy('job_date', 'asc')
            ->orderBy('start_time', 'asc');

        $statsJobQuery = Job::query();

        // LOGIKA FILTER (Sama seperti Boss)
        if ($filterJobType) {
            $jobsQuery->where('job_type', $filterJobType);
            $statsJobQuery->where('job_type', $filterJobType);
        }

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

        $todaysJobs = $jobsQuery->get();
        $allJobTypes = JobType::all();

        // STATISTIK OPERASIONAL (Tanpa Uang)
        $stats = [
            'total_jobs' => $statsJobQuery->count(), // <--- GANTI JADI total_jobs
            'scheduled'  => (clone $statsJobQuery)->where('status', 'scheduled')->count(),
            'ongoing'    => (clone $statsJobQuery)->where('status', 'ongoing')->count(),
            'done'       => (clone $statsJobQuery)->where('status', 'done')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'todaysJobs', 'allJobTypes', 'judulPeriode'));
    }
}