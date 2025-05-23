<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve_book extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function available()
    {
        $book = $this->book; // Ambil instance book

        if (!$book) return 0;

        $total = $book->total ?? 0;
        $reserved = $book->reserve()->where('status', 1)->count();

        return $total - $reserved;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
