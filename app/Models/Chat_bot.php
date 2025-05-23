<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_bot extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function page()
    {
        return $this->belongsTo(Pages::class);
    }
}
