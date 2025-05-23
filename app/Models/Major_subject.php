<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major_subject extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'subject_id',
        'created_at',
        'subject_id',
        'created_at',
        'updated_at',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
