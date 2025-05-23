<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function history()
    {
        return $this->hasMany(Chat_history::class);
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function relationship(){
        return $this->hasOne(Relationship::class, 'user_id', 'user_id');
    }
}
