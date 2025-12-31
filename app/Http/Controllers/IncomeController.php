<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Transaction;

class IncomeController extends Controller
{
    public function index()
    {
        $users = User::with(['crewJobs', 'editorJobs'])->get();

        foreach ($users as $user) {
            $user->jobCount = $user->crewJobs->count() + $user->editorJobs->count();
            $user->estimatedIncome =
                $user->crewJobs->sum(fn($job) => $job->amount / 2) +
                $user->editorJobs->sum(fn($job) => $job->amount / 2);
        }

        return view('boss.income.index', compact('users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'income' => 'required|numeric|min:0'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->income = $request->income;
        $user->save();

        return back()->with('success', "Income {$user->name} berhasil diupdate.");
    }

    public function detail(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // --- FILTER VARIABLES ---
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $filterMonth = $request->month;
        $filterYear = $request->year ?? date('Y');
        $filterJobType = $request->job_type;

        // --- 1. QUERY LIST JOB ---
        $query = Job::where(function ($q) use ($userId) {
            $q->whereHas('assignments', function ($subQ) use ($userId) {
                $subQ->where('user_id', $userId);
            })
                ->orWhereHas('assignments', function ($subQ) use ($userId) {
                    $subQ->where('editor_id', $userId);
                });
        });

        // Filter Logic untuk Job
        if ($filterJobType) {
            $query->where('job_type', $filterJobType);
        }
        if ($startDate && $endDate) {
            $query->whereBetween('job_date', [$startDate, $endDate]);
        } elseif ($filterMonth) {
            $query->whereMonth('job_date', $filterMonth)->whereYear('job_date', $filterYear);
        } else {
            $query->whereYear('job_date', $filterYear);
        }

        $jobs = $query->with(['type', 'assignments.editor', 'transactions' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        }])
            ->orderBy('job_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();


        // --- 2. QUERY TOTAL SALDO (SESUAI FILTER) ---
        // Kita hitung jumlah transaksi 'income' tapi hanya untuk Job yang lolos filter di atas

        $totalRealIncome = \App\Models\Transaction::where('user_id', $userId)
            ->whereIn('type', ['income', 'expense']) // Ambil income (plus) dan expense (minus)
            ->whereHas('job', function ($q) use ($filterJobType, $startDate, $endDate, $filterMonth, $filterYear) {
                // Terapkan Filter yang SAMA PERSIS dengan Query Job di atas
                if ($filterJobType) {
                    $q->where('job_type', $filterJobType);
                }
                if ($startDate && $endDate) {
                    $q->whereBetween('job_date', [$startDate, $endDate]);
                } elseif ($filterMonth) {
                    $q->whereMonth('job_date', $filterMonth)->whereYear('job_date', $filterYear);
                } else {
                    $q->whereYear('job_date', $filterYear);
                }
            })
            ->sum('amount');

        // Ambil data untuk dropdown
        $allJobTypes = \App\Models\JobType::all();

        return view('boss.income.detail', compact('user', 'jobs', 'totalRealIncome', 'allJobTypes'));
    }

    public function storeSingleIncome(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'job_id' => 'required',
            'amount' => 'required|numeric|min:0'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $trx = \App\Models\Transaction::where('user_id', $request->user_id)
                ->where('job_id', $request->job_id)
                ->first();

            if ($trx) {
                $trx->amount = $request->amount;
                $trx->save();

                if ($trx->type == 'income') {
                    $this->recalculateUserBalance($request->user_id);
                }
            } else {
                \App\Models\Transaction::create([
                    'user_id' => $request->user_id,
                    'job_id'  => $request->job_id,
                    'amount'  => $request->amount,
                    'type'    => 'salary_pending',
                    'description' => 'Gaji Job (Menunggu Cair)',
                    'transaction_date' => now()
                ]);
            }
        });

        return back()->with('success', 'Gaji berhasil diupdate!');
    }

    public function resetIncome(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        User::where('id', $request->user_id)
            ->update(['income' => 0]);

        return back()->with('success', 'Income berhasil di-reset.');
    }


    private function recalculateUserBalance($userId)
    {
        $user = \App\Models\User::find($userId);

        $totalSaldo = \App\Models\Transaction::where('user_id', $userId)
            ->whereIn('type', ['income', 'expense'])
            ->sum('amount');

        $user->income = $totalSaldo;
        $user->save();
    }

    public function cairkanGaji(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'job_id' => 'required',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $trx = \App\Models\Transaction::where('user_id', $request->user_id)
                ->where('job_id', $request->job_id)
                ->firstOrFail();

            $trx->update([
                'type' => 'income',
                'description' => 'Gaji Job (Cair)',
                'transaction_date' => now()
            ]);

            $user = \App\Models\User::find($request->user_id);

            $totalSaldo = \App\Models\Transaction::where('user_id', $user->id)
                ->whereIn('type', ['income', 'expense'])
                ->sum('amount');

            $user->income = $totalSaldo;
            $user->save();
        });

        return back()->with('success', 'Gaji berhasil dicairkan ke saldo user!');
    }
}
