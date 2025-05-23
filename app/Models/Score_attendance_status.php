<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score_attendance_status extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'grade_id',
        'class_teacher_id',
        'semester',
        'status',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
