<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use App\Models\JobAssignment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;


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
    // 5. Proses Update Job (DENGAN DETEKSI PERUBAHAN CREW)
    public function update(Request $request, Job $job)
    {
        if ($job->status == 'ongoing') {
            return back()->with('error', 'Gagal update! Job sedang berjalan.');
        }

        $request->validate([
            'job_title' => 'required',
            'client_name' => 'required',
            'amount' => 'nullable|numeric',
            'crew_ids' => 'nullable|array'
        ]);

        // 1. SIMPAN DATA CREW LAMA (Sebelum di-update)
        $oldCrews = $job->users()->get();

        DB::transaction(function () use ($request, $job) {
            $job->update([
                'job_title' => $request->job_title,
                'client_name' => $request->client_name,
                'client_phone' => $request->client_phone,
                'job_type' => $request->job_type,
                'job_date' => $request->job_date,
                'start_time' => $request->start_time ?? $job->start_time,
                'end_time' => $request->end_time ?? $job->end_time,
                'location' => $request->location ?? $job->location,
                'amount' => $request->amount ?? 0,
                'notes' => $request->notes,
                'result_link' => $request->result_link ?? $job->result_link,
            ]);

            // 2. UPDATE CREW DI DATABASE
            if ($request->has('crew_ids')) {
                $job->users()->sync($request->crew_ids);
            }
        });

        // 3. BANDINGKAN CREW LAMA VS BARU
        $newCrews = $job->users()->get(); // Ambil data baru

        $removedCrews = $oldCrews->diff($newCrews); // Siapa yg keluar?
        $addedCrews = $newCrews->diff($oldCrews);   // Siapa yg masuk?

        // 4. MASUKKAN KE SESSION (Agar tombol di View bisa baca)
        $changes = [];
        if ($removedCrews->isNotEmpty() || $addedCrews->isNotEmpty()) {
            $changes = [
                'job_id' => $job->id,
                'removed' => $removedCrews,
                'added' => $addedCrews
            ];
        }

        // 5. REDIRECT SESUAI ROLE
        $route = auth()->user()->role->name == 'admin' ? 'admin.dashboard' : 'boss.dashboard';
        $param = auth()->user()->role->name == 'admin' ? [] : ['job' => $job->id];

        // Kirim 'crew_changes' ke session
        return redirect()->route($route, $param)
            ->with('success', 'Job berhasil diupdate!')
            ->with('crew_changes', $changes);
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

    // 12. Kirim WA (Logic Baru: Bisa Skip Update Waktu)
    public function sendWhatsapp(Request $request, Job $job)
    {
        // Update waktu kirim (kecuali dilarang)
        if (!$request->has('no_update')) {
            $job->update(['wa_sent_at' => now()]);
        }

        // --- PERBAIKAN LOGIC NOMOR HP ---

        // 1. Cek apakah ada request khusus 'target_phone' (Untuk Crew Lama)?
        if ($request->has('target_phone') && $request->input('target_phone')) {
            $crewPhone = $request->input('target_phone');
            $crewName  = $request->input('target_name') ?? 'Crew';
        }
        // 2. Jika tidak ada, baru ambil Crew dari Database (Untuk Crew Saat Ini)
        else {
            $crew = $job->users->first();
            $crewName  = $crew ? $crew->name : 'Crew';
            $crewPhone = $crew ? $crew->phone_number : '';
        }

        // Format 08 -> 628
        // Hapus karakter selain angka
        $crewPhone = preg_replace('/[^0-9]/', '', $crewPhone);
        if (substr($crewPhone, 0, 1) == '0') {
            $crewPhone = '62' . substr($crewPhone, 1);
        }

        // --- ISI PESAN ---
        $type = $request->query('type', 'new');
        $tgl = $job->job_date->translatedFormat('d F Y');
        $jam = \Carbon\Carbon::parse($job->start_time)->format('H:i');

        if ($type == 'cancel_person') {
            // KHUSUS CREW LAMA
            $text = "⛔ *INFO PERGANTIAN CREW* ⛔\n\nHalo {$crewName}, mohon maaf untuk job:\nJudul: *{$job->job_title}*\nTanggal: {$tgl}\n\nTugasmu telah digantikan oleh crew lain. Untuk info lebih detail sisa job, Mohon cek dashboard kamu ya!";
        } elseif ($type == 'revisi_update') {
            $text = "⚠️ *INFO REVISI JADWAL* ⚠️\n\nHalo {$crewName}, ada perubahan data:\nJudul: {$job->job_title}\nTanggal: {$tgl}\nJam: {$jam}\nLokasi: {$job->location}.\n\nMohon cek dashboard kamu ya!";
        } else {
            $text = "✅ *JOB BARU* ✅\n\nHalo {$crewName}, kamu ditugaskan untuk:\nJudul: {$job->job_title}\nTanggal: {$tgl}\nJam: {$jam}\nLokasi: {$job->location}.\n\nMohon cek dashboard kamu ya!";
        }

        $waLink = "https://wa.me/{$crewPhone}?text=" . urlencode($text);

        return redirect()->away($waLink);
    }

    // 13. Method Baru: Tandai WA Selesai (Manual)
    public function markWaSent(Job $job)
    {
        $job->update(['wa_sent_at' => now()]);

        // Hapus cache crew lama karena sudah dianggap selesai
        Cache::forget('old_crew_' . $job->id);

        return back()->with('success', 'Status WA diperbarui.');
    }

    public function invoice(Job $job)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('jobs.invoice', compact('job'));

        return $pdf->stream('invoice-job-' . $job->id . '.pdf');
    }
}
