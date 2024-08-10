<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_country'
    ];

    protected $table = 'language_lines';

}
