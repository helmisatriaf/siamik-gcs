<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LetterController extends Controller
{
    public function index()
    {
        try {
            session()->flash('page', (object)[
                'page' => 'letter',
                'child' => 'database letter'
            ]);

            // Ambil semua data surat
            $letters = \App\Models\Letter::query();

            // Filter berdasarkan tanggal
            if (
                request()->has('date_from') && !empty(request('date_from')) &&
                request()->has('date_to') && !empty(request('date_to'))
            ) {
                $from = request('date_from') . ' 00:00:00';
                $to = request('date_to') . ' 23:59:59';
                $letters->whereBetween('created_at', [$from, $to]);
            }

            // Filter berdasarkan kategori
            if (request()->has('filter_category') && !empty(request('filter_category'))) {
                $letters->where('category', request('filter_category'));
            }

            // Pencarian
            if (request()->has('search') && !empty(request('search'))) {
                $search = request('search');
                $letters->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('letter_number', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // Debug Query (opsional, hapus pada produksi)
            $rawQuery = $letters->toSql();
            $bindings = $letters->getBindings();
            Log::info('Query: ' . $rawQuery);
            Log::info('Bindings: ' . json_encode($bindings));

            // Hitung total sebelum pagination
            $totalCount = $letters->count();
            Log::info('Total count: ' . $totalCount);

            $letters = $letters->latest()->paginate(2);

            return view('components.letter.index', compact('letters'));
        } catch (Exception $err) {
            Log::error('Error in letter index: ' . $err->getMessage());
            dd($err);
        }
    }

    // public function store(Request $request)
    // {

    //     try {
    //         session()->flash('page', (object)[
    //             'page' => 'letter',
    //             'child' => 'database letter'
    //         ]);

    //         $request->validate([
    //             'category' => 'required|string',
    //             'content' => 'nullable|string',
    //             'title' => 'nullable|string|max:255',
    //         ]);

    //         $letter_number = Letter::generateLetterNumber($request->category);

    //         Letter::create([
    //             'letter_number' => $letter_number,
    //             'title' => $request->title,
    //             'content' => $request->content,
    //             'category' => $request->category,
    //         ]);

    //         return redirect()->route('letter.index')->with('success', 'Surat berhasil dibuat!');
    //     } catch (Exception $err) {
    //         dd($err);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'letter',
                'child' => 'database letter'
            ]);

            $request->validate([
                'category' => 'required|string',

            ], [
                'category.required' => 'Kategori surat harus dipilih',

            ]);

            $letter_number = Letter::generateLetterNumber($request->category);

            Letter::create([
                'letter_number' => $letter_number,
                'title' => $request->title,
                'content' => $request->content,
                'category' => $request->category,
            ]);

            return redirect()->route('letter.index')->with('success', 'Surat berhasil dibuat!');
        } catch (Exception $err) {
            return back()->withErrors(['system_error' => 'Terjadi kesalahan: ' . $err->getMessage()])->withInput();
        }
    }


    public function generateLetterNumber($category)
    {
        $letter_number = Letter::generateLetterNumber($category);
        return response()->json(['letter_number' => $letter_number]);
    }
}
