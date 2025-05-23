<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');
class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'name',
      'class',
      'teacher_id',
      'created_at',
      'updated_at',
    ];

    public function subjectTeacher()
    {
      return $this->belongsToMany(Teacher::class, 'teacher_subjects');
    }

    public function teacher()
    {
      return $this->belongsToMany(Teacher::class, 'teacher_grades');
    }

    public function gradeTeacher()
    {
      return $this->belongsToMany(Teacher::class, 'teacher_grades');
    }

    public function student()
    {
      return $this->hasMany(Student::class);
    }

    public function subject()
    {
      return $this->belongsToMany(Subject::class, 'grade_subjects');
    }

    public function exam()
    {
      return $this->belongsToMany(Exam::class, 'grade_exams');
    }

    public function score()
    {
      return $this->hasMany(Score::class);
    }

    public function schedule()
    {
      return $this->hasMany(Schedule::class);
    }
}