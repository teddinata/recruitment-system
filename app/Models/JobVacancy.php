<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'image',
        'title',
        'slug',
        'description',
        'work_hours',
        'location',
        'qualifications',
        'valid_until',
        'experience',
        'remote',
        'enable',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function userApplyJobs()
    {
        return $this->hasMany(UserApplyJob::class);
    }
}
