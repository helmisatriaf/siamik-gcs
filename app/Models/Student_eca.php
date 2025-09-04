<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_eca extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'student_id',
        'eca_id',
        'created_at',
        'updated_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function eca()
    {
        return $this->belongsTo(Eca::class, 'eca_id', 'id');
    }
}
