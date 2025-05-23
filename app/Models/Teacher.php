<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
date_default_timezone_set('Asia/Jakarta');
class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'is_active',
      'user_id',
      'unique_id',
      'name',
      'place_birth',
      'religion',
      'date_birth',
      'home_address',
      'nationality',
      'nik',
      'gender',
      'email',
      'handphone',
      'temporary_address',
      'last_education',
      'major',
      'profil',
      'created_at',
      'updated_at',
   ];

   public function user()
   {
       return $this->belongsTo(User::class, 'user_id');
   }

   public function grade()
   {
      return $this->belongsToMany(Grade::class, 'teacher_grades');
   }

   public function subject(){
      return $this->belongsToMany(Subject::class, 'teacher_subjects');
   }

   public function exam(){
      return $this->hasMany(Exam::class, 'teacher_id');
   }

}