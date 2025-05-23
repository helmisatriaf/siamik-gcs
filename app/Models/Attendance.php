<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'student_id',
        'grade_id',
        'teacher_id',
        'date',
        'present',
        'alpha',
        'sick',
        'late',
        'latest',
        'permission',
        'information',
        'semester',
        'created_at',
        'updated_at',
        'academic_year',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grades::class, 'grade_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
