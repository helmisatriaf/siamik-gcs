<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sooa_secondary extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'class_teacher_id',
        'academic',
        'grades_academic',
        'eca_1',
        'grades_eca_1',
        'eca_2',
        'grades_eca_2',
        'self_development',
        'grades_self_development',
        'eca_aver',
        'grades_eca_aver',
        'behavior',
        'grades_behavior',
        'attendance',
        'grades_attendance',
        'participation',
        'grades_participation',
        'final_score',
        'grades_final_score',
        'ranking',
        'semester',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
