<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pages extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function tutorials()
    {
        return $this->hasMany(Page_Tutorials::class, 'page_id');
    }

    public function chatbots()
    {
        return $this->hasMany(Chat_bot::class, 'page_id');
    }
}
