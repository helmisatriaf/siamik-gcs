<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chinese_higher extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'subject_id',
        'student_id',
        'grade_id',
        'created_at',
        'updated_at',
    ];
}
