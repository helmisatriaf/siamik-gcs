<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'title',
        'grade_subject_id',
        'file_path',
        'semester',
        'academic_year',
    ];
}
