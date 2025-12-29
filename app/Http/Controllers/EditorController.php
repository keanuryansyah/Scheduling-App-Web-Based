<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\User;
use App\Models\JobAssignment;
use Illuminate\Support\Facades\DB;

class EditorController extends Controller
{
    // 1. Dashboard Editor
    public function index()
    {
        $editorId = auth()->id();

        // 1. JOB AKTIF (Idle & Editing)
        $activeJobs = Job::with(['type', 'assignments'])
            ->whereIn('editor_status', ['idle', 'editing'])
            ->where(function($query) use ($editorId) {
                $query->where('status', 'done')
                      ->orWhereHas('assignments', function($q) use ($editorId) {
                          $q->where('user_id', $editorId);
                      });
            })
            ->orderBy('job_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // 2. JOB SELESAI (Completed - Hanya milik saya)
        $completedJobs = Job::with(['type', 'assignments'])
            ->where('editor_status', 'completed')
            ->whereHas('assignments', function($q) use ($editorId) {
                // Pastikan yang muncul cuma job yang diedit oleh user ini
                $q->where('editor_id', $editorId);
            })
            ->orderBy('job_date', 'desc')
            ->limit(10) // Batasi biar ga kepanjangan
            ->get();

        return view('editor.index', compact('activeJobs', 'completedJobs'));
    }

    // 2. Lihat Detail Job
    public function show($id)
    {
        $job = Job::with(['type', 'users'])->findOrFail($id);
        return view('editor.show', compact('job'));
    }

    // 3. Mulai Edit (UPDATE: Tanpa Transaksi 0)
    public function start(Job $job)
    {
        // Proteksi Race Condition (Cek jika sudah diambil orang lain)
        if ($job->editor_status != 'idle') {
            $assignment = $job->assignments->first();
            
            if ($assignment && $assignment->editor_id) {
                // Jika user sendiri yang ambil, biarkan
                if ($assignment->editor_id == auth()->id()) {
                    return back();
                }
                $editorName = User::find($assignment->editor_id)->name ?? 'Editor lain';
            } else {
                $editorName = 'Editor lain';
            }

            return back()->with('error', "Gagal! Job ini baru saja diambil oleh {$editorName}.");
        }

        DB::transaction(function () use ($job) {
            // Lock record
            $job = Job::lockForUpdate()->find($job->id);

            // Double check
            if ($job->editor_status != 'idle') return;

            // 1. Update Status
            $job->update(['editor_status' => 'editing']);

            // 2. Tandai Editor di Assignment
            \App\Models\JobAssignment::where('job_id', $job->id)
                ->update(['editor_id' => auth()->id()]);

            // CATATAN: KITA HAPUS Transaction::create DISINI
            // Agar job ini tetap dianggap "Belum ada transaksi" oleh sistem Boss
        });

        return back()->with('success', 'Job berhasil diambil! Silakan mulai editing.');
    }

    // 4. Selesai Edit (UPDATE: MURNI UPDATE STATUS & LINK SAJA)
    public function finishJob(Request $request, Job $job)
    {
        $request->validate(['result_link' => 'required|url']);

        DB::transaction(function () use ($request, $job) {
            // 1. Update Link & Status Selesai
            $job->update([
                'result_link' => $request->result_link,
                'editor_status' => 'completed'
            ]);

            // 2. Pastikan Editor ID tercatat (jaga-jaga)
            \App\Models\JobAssignment::where('job_id', $job->id)
                ->update(['editor_id' => auth()->id()]);

            // --- STOP ---
            // TIDAK ADA LAGI LOGIKA KEUANGAN DISINI.
            // Biarkan Boss yang menginput gaji editor secara manual di menu "Income".
        });

        return redirect()->route('editor.dashboard')->with('success', 'Link tersimpan! Pekerjaan selesai.');
    }
}