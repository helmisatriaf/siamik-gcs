<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_Monthly_Activity extends Model
{
    use HasFactory;

    protected $table = 'student_monthly_activities';

    protected $fillable = [
        'id',
        'student_id',
        'grade_id',
        'monthly_activity_id',
        'semester',
        'academic_year',
        'score',
        'grades',
        'created_at',
        'updated_at',
    ];

    
}
