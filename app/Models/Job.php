<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'job_title',
        'client_name',
        'client_phone',
        'job_type',
        'job_date',
        'start_time',
        'end_time',
        'location',
        'payment_method',
        'amount',
        'proof',
        'notes',
        'status',
        'editor_status',
        'result_link',
        'wa_sent_at',
        'created_by',
        'otw_at',
        'arrived_at',
        'started_at',
        'finished_at'
    ];


    protected $casts = [
        'job_date' => 'date',
        'wa_sent_at' => 'datetime',
        'otw_at' => 'datetime',
        'arrived_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /* ================= RELATIONS ================= */

    public function type()
    {
        return $this->belongsTo(JobType::class, 'job_type');
    }

    public function assignments()
    {
        return $this->hasMany(JobAssignment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'job_assignments');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->hasOneThrough(
            User::class,
            JobAssignment::class,
            'job_id',      // FK di job_assignments ke jobs
            'id',          // PK users
            'id',          // PK jobs
            'editor_id'    // FK job_assignments ke users
        );
    }

     public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
