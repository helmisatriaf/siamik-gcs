<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Roles;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Relationship;

date_default_timezone_set('Asia/Jakarta');

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'role_id',
        'name',
        'phone',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function role(){
        return $this->belongsTo(Roles::class);
    }

    public function student(){
        return $this->hasOne(Student::class, 'user_id');
    }

    public function relationship(){
        return $this->hasOne(Relationship::class, 'user_id');
    }

    public function teacher(){
        return $this->hasOne(Teacher::class, 'user_id');
    }

}