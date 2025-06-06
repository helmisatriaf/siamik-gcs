<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
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
