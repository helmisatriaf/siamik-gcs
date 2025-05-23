<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kindergarten extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'class_teacher_id',
        'english',
        'mathematics',
        'chinese',
        'science',
        'character_building',
        'art_and_craft',
        'it',
        'phonic',
        'conduct',
        'remarks',
        'semester',
        'promote',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
