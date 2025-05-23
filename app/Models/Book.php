<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reserve_book;

class Book extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function reserve()
    {
        return $this->hasMany(Reserve_book::class, 'book_id', 'id')->where('status', 1);
    }

    public function getAvailableAttribute()
    {
        $total = $this->total ?? 0;
        $reserved = $this->reserve()->where('status', 1)->count();
        return $total - $reserved;
    }
    

    public function fullBooked()
    {
        $totalPeminjam = $this->reserve()->where('status', 1)->count();
        $totalBuku = $this->total ?? 0; // Pastikan kolom quantity tersedia

        return $totalPeminjam >= $totalBuku ? 1 : 0;
    }

}
