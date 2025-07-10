<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
