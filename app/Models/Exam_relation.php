<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_relation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'type_exam_id',
        'subject_id',
        'grade_id',
        'exam_id',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
