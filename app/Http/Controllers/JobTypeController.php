<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobType;

class JobTypeController extends Controller
{
    public function index()
    {
        $types = JobType::all();
        return view('job_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_type_name' => 'required|unique:job_types',
            'badge_color' => 'required' // Validasi warna wajib diisi
        ]);

        JobType::create([
            'job_type_name' => $request->job_type_name,
            'badge_color' => $request->badge_color
        ]);

        return back()->with('success', 'Job Type berhasil ditambahkan');
    }

    public function destroy(JobType $jobType)
    {
        // Hapus semua job terkait dulu (HATI-HATI DATA HILANG)
        $jobType->jobs()->delete(); 
        
        // Baru hapus tipenya
        $jobType->delete();
        
        return back()->with('success', 'Job Type dan semua Job terkait berhasil dihapus');
    }
}
