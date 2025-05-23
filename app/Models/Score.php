<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'exam_id',
      'subject_id',
      'grade_id',
      'teacher_id',
      'type_exam_id',
      'student_id',
      'hasFile',
      'file_name',
      'file_path',
      'time_upload',
      'score',
      'created_at',
      'updated_at',
      'academic_year',
      'semester',
    ];
}
