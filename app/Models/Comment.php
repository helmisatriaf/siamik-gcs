<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'subject_id',
        'grade_id',
        'subject_teacher_id',
        'type',
        'comment',
        'semester',
        'teacher_id',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
