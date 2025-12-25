<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use App\Models\JobAssignment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    // 1. Tampilkan Form Buat Job
    public function create()
    {
        $types = JobType::all();

        // Crew lapangan (untuk job biasa)
        $crews = User::where('role_id', 3)->get();

        // Sumber Footage (Bisa semua user, atau crew juga. Di sini saya ambil semua user kecuali Boss)
        // Agar bisa pilih "Ini footage dari Crew A"
        $usersSource = User::where('role_id', '!=', 1)->get();

        return view('jobs.create', compact('types', 'crews', 'usersSource'));
    }

    // 2. Simpan Data Job
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'job_title' => 'required',
            'client_name' => 'required',
            'job_type' => 'required',
            'job_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'amount' => 'nullable|numeric',
            'crew_ids' => 'required|array'
        ]);

        DB::transaction(function () use ($request) {
            // --- LOGIKA STATUS OTOMATIS ---
            // Cari data tipe job berdasarkan ID yang dipilih
            $type = JobType::find($request->job_type);

            // Default status
            $initialStatus = 'scheduled';

            // Jika nama tipe job mengandung kata "edit" (misal: "Video Editing"),
            // Maka status langsung 'done' agar masuk ke dashboard Editor.
            if ($type && str_contains(strtolower($type->job_type_name), 'edit')) {
                $initialStatus = 'done';
            }
            // ------------------------------

            // A. Simpan Job
            $job = Job::create([
                'job_title' => $request->job_title,
                'client_name' => $request->client_name,
                'client_phone' => $request->client_phone ?? '-',
                'job_type' => $request->job_type,
                'job_date' => $request->job_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location ?? '-',
                'amount' => $request->amount ?? 0,
                'notes' => $request->notes,
                'payment_method' => $request->location == 'Basecamp' ? "cash" : "unpaid",

                'status' => $initialStatus, // <--- PAKAI VARIABEL STATUS BARU

                'created_by' => auth()->id(),
            ]);

            // B. Simpan Assignment (Jika ada crew/editor dipilih)
            if ($request->has('crew_ids') && is_array($request->crew_ids)) {
                $validUserIds = array_filter($request->crew_ids);

                foreach ($validUserIds as $userId) {
                    JobAssignment::create([
                        'job_id' => $job->id,
                        'user_id' => $userId
                    ]);
                }
            }
        });

        if (auth()->user()->role->name == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Job berhasil dibuat!');
        }
        return redirect()->route('boss.dashboard')->with('success', 'Job berhasil dibuat!');
    }

    public function show($id)
    {
        $job = Job::with(['type', 'users', 'creator'])->findOrFail($id);
        return view('jobs.show', compact('job'));
    }

    // 4. Form Edit
    public function edit(Job $job)
    {
        // PROTEKSI KUAT: Hanya status 'scheduled' yang boleh diedit.
        // Ongoing & Done otomatis tertolak.
        if ($job->status != 'scheduled') {
            return back()->with('error', 'Job sudah berjalan atau selesai, tidak bisa diedit!');
        }

        $types = JobType::all();
        $crews = User::where('role_id', 3)->get();
        $assignedCrewIds = $job->users->pluck('id')->toArray();

        return view('jobs.edit', compact('job', 'types', 'crews', 'assignedCrewIds'));
    }

    // 5. Proses Update Job
    public function update(Request $request, Job $job)
    {
        // PROTEKSI KUAT SAAT UPDATE
        if ($job->status != 'scheduled') {
            return back()->with('error', 'Gagal update! Job sudah berjalan atau selesai.');
        }

        $request->validate([
            'job_title' => 'required',
            'client_name' => 'required',
            'amount' => 'nullable|numeric',
            'crew_ids' => 'nullable|array'
        ]);

        DB::transaction(function () use ($request, $job) {
            $job->update([
                'job_title' => $request->job_title,
                'client_name' => $request->client_name,
                'client_phone' => $request->client_phone,
                'job_type' => $request->job_type,
                'job_date' => $request->job_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'amount' => $request->amount,
                'notes' => $request->notes,
            ]);

            // Update Crew jika ada input
            if ($request->has('crew_ids')) {
                $job->users()->sync($request->crew_ids);
            }
        });

        return redirect()->route('jobs.show', $job->id)->with('success', 'Job berhasil diupdate!');
    }

    // 6. Proses Hapus Job
    public function destroy(Job $job)
    {
        $job->assignments()->delete();
        $job->delete();
        if (auth()->user()->role->name == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Job berhasil dibuat!');
        }
        return redirect()->route('boss.dashboard')->with('success', 'Job berhasil dibuat!');
    }

    // API Cek Jadwal
    public function checkAvailability(Request $request)
    {
        $date = $request->date;
        $start = $request->start;
        $end = $request->end;

        // Validasi input
        if (!$date || !$start || !$end) {
            return response()->json([]);
        }

        // Tentukan Buffer (Jeda Waktu) dalam menit
        // Contoh: 60 menit (1 jam) perjalanan/istirahat
        $bufferMinutes = 60;

        // Cari ID Crew yang SIBUK (Busy) pada jam tersebut + Jeda
        $busyCrewIds = JobAssignment::whereHas('job', function ($query) use ($date, $start, $end, $bufferMinutes) {
            $query->where('job_date', $date)
                ->where('status', '!=', 'canceled')
                ->where(function ($q) use ($start, $end, $bufferMinutes) {

                    // Logika Tabrakan dengan BUFFER
                    // Job Baru Mulai < (Job Lama Selesai + Buffer)
                    // DAN
                    // Job Baru Selesai > (Job Lama Mulai - Buffer)

                    $q->whereRaw("TIME_TO_SEC(start_time) < (TIME_TO_SEC(?) + (? * 60))", [$end, $bufferMinutes])
                        ->whereRaw("TIME_TO_SEC(end_time) > (TIME_TO_SEC(?) - (? * 60))", [$start, $bufferMinutes]);
                });
        })->pluck('user_id')->toArray();

        return response()->json($busyCrewIds);
    }


    // 7. Konfirmasi Pembayaran (LOGIKA TIDAK SAYA UBAH SESUAI REQUEST)
    public function confirmPayment(Request $request, Job $job)
    {
        // Cek apakah sudah diproses
        if ($job->transactions()->exists()) {
            return back()->with('error', 'Job ini sudah lunas/diproses sebelumnya!');
        }

        // Validasi
        $rules = [
            'payment_method' => 'required|in:tf,vendor,cash',
            'proof' => 'nullable|image|max:2048'
        ];

        // Jika harga masih 0, Boss WAJIB input harga real
        if ($job->amount == 0) {
            $rules['amount'] = 'required|numeric|min:1';
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $job) {

            // 1. UPDATE DATA JOB
            $updateData = [
                'payment_method' => $request->payment_method
            ];

            // Update harga jika ada input baru
            if ($request->amount) {
                $updateData['amount'] = $request->amount;
                $job->amount = $request->amount; // Update object memory
            }

            // Update bukti jika ada upload
            if ($request->hasFile('proof')) {
                $path = $request->file('proof')->store('proofs', 'public');
                $updateData['proof'] = $path;
            }

            $job->update($updateData);


            // 2. CATAT PEMASUKAN FULL KE BOSS (TANPA BAGI HASIL)
            $boss = User::where('role_id', 1)->first(); // Cari akun Boss

            if ($boss) {
                // Uang masuk 100% ke saldo Boss
                $boss->increment('income', $job->amount);

                // Catat Transaksi Pemasukan
                \App\Models\Transaction::create([
                    'user_id' => $boss->id,
                    'job_id' => $job->id,
                    'amount' => $job->amount, // FULL AMOUNT
                    'type' => 'income',
                    'description' => 'Pemasukan Job: ' . $job->job_title . ' (' . strtoupper($request->payment_method) . ')',
                    'transaction_date' => now()
                ]);
            }

            // --- BAGIAN CREW DIHAPUS TOTAL ---
            // Tidak ada transaksi yang dibuat untuk crew di sini.
            // Gaji crew nanti diinput manual oleh Boss di menu Income.

        });

        return back()->with('success', 'Pembayaran diterima! Uang masuk kas Boss (Full).');
    }

    public function cancel(Job $job)
    {
        // Proteksi: Hanya job 'scheduled' yang bisa dicancel
        // Jika sudah ongoing/done, biasanya tidak boleh cancel (kecuali kebijakan boss beda)
        if ($job->status == 'done') {
            return back()->with('error', 'Job sudah selesai, tidak bisa dibatalkan.');
        }

        // Ubah status jadi 'canceled'
        $job->update(['status' => 'canceled']);

        // Opsional: Hapus assignments agar crew jadi free lagi jadwalnya
        // $job->assignments()->delete(); 

        if (auth()->user()->role->name == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Job berhasil dibuat!');
        }
        return redirect()->route('boss.dashboard')->with('success', 'Job berhasil dibuat!');
    }

    public function updateLink(Request $request, Job $job)
    {
        $request->validate([
            'result_link' => 'required|url'
        ]);

        $job->update([
            'result_link' => $request->result_link
        ]);

        return back()->with('success', 'Link hasil berhasil diperbarui!');
    }

    public function updateProof(Request $request, Job $job)
    {
        $request->validate([
            'proof' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('proofs', 'public');
            $job->update(['proof' => $path]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload!');
    }
}
