<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $fillable = ['job_type_name', 'badge_color'];

    public function jobs()
    {
        return $this->hasMany(Job::class, 'job_type');
    }
}
