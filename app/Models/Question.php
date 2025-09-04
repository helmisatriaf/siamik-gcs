<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['correct_answer_id'];

    public function getCorrectAnswerIdAttribute()
    {
        return $this->correctAnswer()
            ->where('is_correct', true)
            ->value('id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function correctAnswer()
    {
        return $this->hasMany(Answer::class);
    }

    public function answer()
    {
        return $this->hasMany(Answer::class);
    }
    
    public function studentAnswer()
    {
        return $this->hasMany(StudentAnswer::class);
    }
    
}
