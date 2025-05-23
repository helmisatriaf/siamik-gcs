<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'grades',
        'created_at',
        'updated_at',
    ];

    
}
