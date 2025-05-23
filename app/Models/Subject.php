<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name_subject',
        'icon',
        'created_at',
        'updated_at',
    ];

    public function teacher(){
        return $this->belongsToMany(Teacher::class, 'teacher_subjects');
    }

    public function grade(){
        return $this->belongsToMany(Grade::class, 'grade_subjects');
    }

    public function exam(){
        return $this->belongsToMany(Exam::class, 'subject_exams');
    }
}
