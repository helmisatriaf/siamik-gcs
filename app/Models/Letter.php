<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['letter_number', 'title', 'content', 'category'];

    public static function generateLetterNumber($category)
    {
        $school_code = 'GCS'; // Sesuaikan dengan kode sekolah Anda
        $year = date('Y');
        $month = date('m');

        $latest = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('category', $category)
            ->count() + 1;

        return str_pad($latest, 3, '0', STR_PAD_LEFT) . "/{$category}/{$school_code}/{$month}/{$year}";
    }

    // public static function generateLetterNumber($category)
    // {
    //     $year = date('Y');
    //     $month = date('m');

    //     // Cek kode sekolah terbesar dalam format GCSXXXX
    //     $latest_school_code = self::where('letter_number', 'LIKE', 'GCS%')
    //         ->orderByRaw("CAST(SUBSTRING_INDEX(letter_number, 'GCS', -1) AS UNSIGNED) DESC") // Ambil angka terbesar
    //         ->pluck('letter_number')
    //         ->first();

    //     // Jika belum ada, mulai dari GCS0001
    //     if (!$latest_school_code) {
    //         $school_code = 'GCS0001';
    //     } else {
    //         // Ambil angka terakhir dari GCSXXXX
    //         preg_match('/GCS(\d+)/', $latest_school_code, $matches);
    //         $last_number = isset($matches[1]) ? (int) $matches[1] : 0;

    //         // Increment ke nomor berikutnya
    //         $next_number = str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);
    //         $school_code = "GCS{$next_number}";
    //     }

    //     // Ambil nomor surat terakhir dalam kategori ini
    //     $latest = self::whereYear('created_at', $year)
    //         ->whereMonth('created_at', $month)
    //         ->where('category', $category)
    //         ->count() + 1;

    //     // Format nomor surat
    //     return str_pad($latest, 3, '0', STR_PAD_LEFT) . "/{$category}/{$school_code}/{$month}/{$year}";
    // }
}
