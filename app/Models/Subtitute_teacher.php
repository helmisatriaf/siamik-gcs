<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtitute_teacher extends Model
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
        'day',
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
    ];

}
