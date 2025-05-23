<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mid_report extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'class_teacher_id',
        'critical_thinking',
        'cognitive_skills',
        'life_skills',
        'learning_skills',
        'social_and_emotional_development',
        'semester',
        'academic_year',
        'created_at',
        'updated_at',
    ];
}
