<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Ambil status pengerjaan exam model cbt
    public function getStudentStatusAttribute()
    {
        return $this->score > 0;
    }



    // Ambil status pengerjaan exam model upload file
    public function getStudentUploadAttribute()
    {
      return $this->hasFile == 1;
    }
}
