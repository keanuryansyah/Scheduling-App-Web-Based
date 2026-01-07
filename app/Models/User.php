<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ResetPasswordIzzati;
use Illuminate\Auth\Notifications\ResetPassword;

class User extends Authenticatable
{

    use Notifiable, CanResetPassword;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'role_id',
        'payday',
        'income',
        'profile_picture',
    ];

    protected $hidden = ['password'];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordIzzati($token));
    }


    /* ================= RELATIONS ================= */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jobAssignments()
    {
        return $this->hasMany(JobAssignment::class);
    }

    // RELASI LAMA (crew default) — BIARKAN
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_assignments');
    }

    // 🔥 JOB SEBAGAI CREW (EXPLICIT)
    public function crewJobs()
    {
        return $this->belongsToMany(
            Job::class,
            'job_assignments',
            'user_id',
            'job_id'
        );
    }

    // 🔥 JOB SEBAGAI EDITOR (INI KUNCI)
    public function editorJobs()
    {
        return $this->belongsToMany(
            Job::class,
            'job_assignments',
            'editor_id',
            'job_id'
        );
    }

    public function jobsCreated()
    {
        return $this->hasMany(Job::class, 'created_by');
    }
}
