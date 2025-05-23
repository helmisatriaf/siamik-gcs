<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade_exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'grade_id',
        'exam_id',
        'created_at',
        'updated_at',
        'academic_year',
    ];
}
