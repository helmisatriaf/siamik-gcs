<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_history extends Model
{
    use HasFactory;

    Protected $guarded = ['id'];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
