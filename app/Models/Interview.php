<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = ['user_apply_job_id', 'is_invited', 'interview_date', 'google_meet_link', 'notes'];

    public function userApplyJob()
    {
        return $this->belongsTo(UserApplyJob::class);
    }

    public function interviewResult()
    {
        return $this->hasOne(InterviewResult::class);
    }

}
