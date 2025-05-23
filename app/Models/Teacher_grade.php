<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher_grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'grade_id',
        'academic_year',
        'created_at',
        'updated_at',
    ];
}
