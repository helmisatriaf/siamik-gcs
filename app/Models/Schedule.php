<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'grade_id',
        'subject_id',
        'teacher_id',
        'teacher_companion',
        'type_schedule_id',
        'note',
        'date',
        'end_date',
        'day',
        'start_time',
        'end_time',
        'semester',
        'created_at',
        'updated_at',
        'academic_year',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function type_schedule(){
        return $this->belongsTo(Type_schedule::class);
    }
}
