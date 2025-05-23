<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
date_default_timezone_set('Asia/Jakarta');

class Student_relationship extends Model
{
   use HasFactory;

   protected $table = 'student_relations';

   protected $fillable = [
      'student_id',
      'relation_id'
   ];
}