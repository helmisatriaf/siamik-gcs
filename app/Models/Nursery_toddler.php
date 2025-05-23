<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nursery_toddler extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'class_teacher_id',
        'songs',
        'prayer',
        'colour',
        'number',
        'object',
        'body_movement',
        'colouring',
        'painting',
        'chinese_songs',
        'ability_to_recognize_the_objects',
        'able_to_own_up_to_mistakes',
        'takes_care_of_personal_belongings_and_property',
        'demonstrates_importance_of_self_control',
        'management_emotional_problem_solving',
        'remarks',
        'semester',
        'promote',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
