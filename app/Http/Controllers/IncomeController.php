<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        // ======================
        // FILTER
        // ======================
        $year  = $request->year ?? now()->year;
        $month = $request->month;
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // ======================
        // DATA USER (TABEL)
        // ======================
        $users = User::with(['crewJobs', 'editorJobs'])->get();

        foreach ($users as $user) {
            $user->jobCount = $user->crewJobs->count() + $user->editorJobs->count();

            // REAL income (saldo)
            $user->realIncome = Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->sum('amount');
        }

        // ======================
        // DATA GRAFIK BERDASARKAN JOB DATE
        // ======================
        $trxQuery = Transaction::where('type', 'income')
            ->whereHas('job', function ($q) use ($startDate, $endDate, $month, $year) {
                // Terapkan filter sama seperti di detail()
                if ($startDate && $endDate) {
                    $q->whereBetween('job_date', [$startDate, $endDate]);
                } elseif ($month) {
                    $q->whereMonth('job_date', $month)
                        ->whereYear('job_date', $year);
                } else {
                    $q->whereYear('job_date', $year);
                }
            });

        $incomePerUser = $trxQuery
            ->select('user_id', DB::raw('SUM(amount) as total'))
            ->groupBy('user_id')
            ->with('user')
            ->get();

        $grandTotal = $incomePerUser->sum('total');

        // $chartData = $incomePerUser->map(function ($row) use ($grandTotal) {
        //     return [
        //         'name'       => $row->user->name,
        //         'total'      => (float) $row->total,
        //         'percentage' => $grandTotal > 0
        //             ? round(($row->total / $grandTotal) * 100, 1)
        //             : 0
        //     ];
        // });
        $chartData = $incomePerUser->map(function ($row) use ($grandTotal) {
            return [
                'name'       => $row->user->name,
                'percentage' => $grandTotal > 0
                    ? round(($row->total / $grandTotal) * 100, 1)
                    : 0
            ];
        });


        return view('boss.income.index', compact(
            'users',
            'chartData',
            'year',
            'month',
            'grandTotal',
            'startDate',
            'endDate'
        ));
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

        // MODIFIKASI DISINI: Berikan default ke bulan dan tahun saat ini
        $filterMonth = $request->get('month', date('n'));
        $filterYear = $request->get('year', date('Y'));
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
        } else {
            // Paksa filter bulan dan tahun jika tidak sedang menggunakan range tanggal
            $query->whereMonth('job_date', $filterMonth)
                ->whereYear('job_date', $filterYear);
        }

        $jobs = $query->with(['type', 'assignments.editor', 'transactions' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        }])
            ->orderBy('job_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        // --- 2. QUERY TOTAL SALDO (SESUAI FILTER) ---
        $totalRealIncome = \App\Models\Transaction::where('user_id', $userId)
            ->whereIn('type', ['income', 'expense'])
            ->whereHas('job', function ($q) use ($filterJobType, $startDate, $endDate, $filterMonth, $filterYear) {
                if ($filterJobType) {
                    $q->where('job_type', $filterJobType);
                }
                if ($startDate && $endDate) {
                    $q->whereBetween('job_date', [$startDate, $endDate]);
                } else {
                    $q->whereMonth('job_date', $filterMonth)
                        ->whereYear('job_date', $filterYear);
                }
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('
            CASE 
                WHEN type = "income" THEN (amount + COALESCE(extra_fee, 0))
                WHEN type = "expense" THEN -(amount + COALESCE(extra_fee, 0))
                ELSE 0 
            END
        '));

        $allJobTypes = \App\Models\JobType::all();

        return view('boss.income.detail', compact('user', 'jobs', 'totalRealIncome', 'allJobTypes', 'filterMonth', 'filterYear'));
    }

    public function storeSingleIncome(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'amount' => 'required|numeric|min:0'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $trx = \App\Models\Transaction::where('user_id', $request->user_id)
                ->where('job_id', $request->job_id)
                ->first();

            if ($trx) {
                $trx->amount = $request->amount;
                $trx->save();
            } else {
                $job = \App\Models\Job::find($request->job_id);
                $description = "Gaji Job: " . ($job->job_title ?? 'Tanpa Nama') . " (Menunggu Cair)";

                \App\Models\Transaction::create([
                    'user_id' => $request->user_id,
                    'job_id'  => $request->job_id,
                    'amount'  => $request->amount,
                    'type'    => 'salary_pending',
                    'description' => $description,
                    'transaction_date' => now(),
                    'extra_fee' => 0
                ]);
            }

            // SYNC: Hitung ulang saldo setelah ada perubahan apapun
            $this->recalculateUserBalance($request->user_id);
        });

        return back()->with('success', 'Gaji berhasil diupdate!');
    }

    public function storeExtraFee(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'amount' => 'required|numeric|min:0',
            'extra_fee' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $trx = \App\Models\Transaction::where('user_id', $request->user_id)
                ->where('job_id', $request->job_id)
                ->first();

            if ($trx) {
                $trx->amount = $request->amount;
                $trx->extra_fee = $request->extra_fee;
                $trx->save();

                // SYNC: Panggil fungsi central agar hitungannya sama
                $this->recalculateUserBalance($request->user_id);
            }
        });

        return back()->with('success', 'Extra Fee dan Income User berhasil diperbarui!');
    }

    private function recalculateUserBalance($userId)
    {
        $user = \App\Models\User::find($userId);
        if (!$user) return;

        // HANYA hitung 'income' dan 'expense'. 
        // 'salary_pending' TIDAK dimasukkan agar saldo user tidak bertambah sebelum cair.
        $totalSaldo = \App\Models\Transaction::where('user_id', $userId)
            ->whereIn('type', ['income', 'expense'])
            ->sum(\Illuminate\Support\Facades\DB::raw('
            CASE 
                WHEN type = "expense" THEN -(amount + COALESCE(extra_fee, 0))
                ELSE (amount + COALESCE(extra_fee, 0))
            END
        '));

        $user->income = $totalSaldo;
        $user->save();
    }

    public function cairkanGaji(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $trx = \App\Models\Transaction::where('user_id', $request->user_id)
                ->where('job_id', $request->job_id)
                ->firstOrFail();

            // Ubah status jadi income (Cair)
            $trx->update([
                'type' => 'income',
                'description' => 'Gaji Job: ' . ($trx->job->job_title ?? 'Tanpa Nama') . ' (Cair)',
                'transaction_date' => now()
            ]);

            // SYNC: Baru di sini income user akan bertambah
            $this->recalculateUserBalance($request->user_id);
        });

        return back()->with('success', 'Gaji berhasil dicairkan ke saldo user!');
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
}
