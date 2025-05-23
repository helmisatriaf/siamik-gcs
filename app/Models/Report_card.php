<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report_card extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'class_teacher_id',
        'independent_work',
        'initiative',
        'homework_completion',
        'use_of_information',
        'cooperation_with_other',
        'conflict_resolution',
        'class_participation',
        'problem_solving',
        'goal_setting_to_improve_work',
        'strength_weakness_nextstep',
        'remarks',
        'promotion_status',
        'semester',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}

