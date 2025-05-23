<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade_subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'grade_id',
        'subject_id',
        'academic_year',
        'created_at',
        'subject_id',
    ];
}
