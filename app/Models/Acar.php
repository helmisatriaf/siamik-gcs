<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acar extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'subject_id',
        'grade_id',
        'subject_teacher_id',
        'final_score',
        'grades',
        'comment',
        'semester',
        'teacher_id',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
