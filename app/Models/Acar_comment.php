<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acar_comment extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'class_teacher_id',
        'type',
        'comment',
        'semester',
        'academic_year',
        'created_at',
        'updated_at',
    ];
}
