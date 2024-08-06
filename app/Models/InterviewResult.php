<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewResult extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'user_apply_job_id',
        'interview_id',
        'is_success',
        'review'
    ];

    // public function userApplyJob()
    // {
    //     return $this->belongsTo(UserApplyJob::class);
    // }

    public function interview()
    {
        return $this->belongsTo(Interview::class, 'interview_id', 'id');
    }

}
