<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function activities()
    {
        return $this->hasMany(CourseActivities::class);
    }

    public function gradeSubject()
    {
        return $this->belongsTo(Grade_subject::class);
    }
}
