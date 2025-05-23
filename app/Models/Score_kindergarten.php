<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score_kindergarten extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'subject_id',
        'subject_teacher_id',
        'participation',
        'total',
        'mark',
        'semester',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
