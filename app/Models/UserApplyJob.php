<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserApplyJob extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tracking_code = (string) Str::uuid();
        });
    }

    protected $table = 'user_apply_jobs';

    protected $fillable = [
        'job_vacancy_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'place_of_birth',
        'date_of_birth',
        'education',
        'major',
        'join_date',
        'linkedin_url',
        'job_source',
        'old_company',
        'self_description',
        'gender',
        'cv_path',
        'is_valid',
        'acceptance_status',
    ];

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }

    public function interview()
    {
        // return $this->hasOne(Interview::class);
        return $this->hasMany(Interview::class);
    }

    public function interviewResult()
    {
        return $this->hasManyThrough(InterviewResult::class, Interview::class);
    }
}
