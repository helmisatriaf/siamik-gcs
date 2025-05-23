<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'section_id',
        'semester',
        'name_exam',
        'type_exam',
        'date_exam',
        'materi',
        'model',
        'teacher_id',
        'is_active',
        'hasFile',
        'file_name',
        'file_path',
        'academic_year',
        'created_at',
        'updated_at',
    ];

    public function grade(){
        return $this->belongsToMany(Grade::class, 'grade_exams');
    }

    public function subject(){
        return $this->belongsToMany(Subject::class, 'subject_exams');
    }

    public function student(){
        return $this->belongsToMany(Student::class, 'student_exams');
    }

    public function score(){
        return $this->hasMany(Score::class);
    }
    
    public function question(){
        return $this->hasMany(Question::class);
    }
    
    public function studentAnswer(){
        return $this->hasMany(StudentAnswer::class);
    }




}
