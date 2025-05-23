<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Subject;
use App\Models\Book;
use App\Models\Cupboard_cd_book;
use App\Models\Cupboard_three_level;
use App\Models\Curriculum_old;
use App\Models\Lemari_cd;
use App\Models\Small_warehouse_library;
use App\Models\Reference_book;
use App\Models\Reserve_book;
use App\Models\Student;
use App\Models\Visit_student;
use App\Models\User;
use App\Models\Article_library;
use App\Models\Plan_visit;
use Carbon\Carbon;

use League\CommonMark\Reference\Reference;

class LibraryController extends Controller
{
    public function index(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = Book::paginate(5);

        return view('components.library.index', compact('categories', 'books'));
    }

    // CRUD LIBRARY
    public function library(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $books = \App\Models\Book::query();

        // Filter berdasarkan tanggal
        if (
            request()->has('date_from') && !empty(request('date_from')) &&
            request()->has('date_to') && !empty(request('date_to'))
        ) {
            $from = request('date_from') . ' 00:00:00';
            $to = request('date_to') . ' 23:59:59';
            $books->whereBetween('created_at', [$from, $to]);
        }
        
        // Pencarian
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $books->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = $books->orderBy('rack', 'ASC')
        ->orderBy('rack_location', 'ASC')->paginate(20);

        return view('components.library.create-library', compact('categories', 'books'));
    }

    public function editLibrary($id){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $data = Book::with(['reserve.user.student'])->where('id', $id)->first();

        return response()->json(['data' => $data, 'categories' => $categories]);
    }

    public function storeLibrary(Request $request){
        for($i=0; $i<count($request->rack); $i++){
            $filePath = null;
            if ($request['image'][$i] != null) {
                $filePath = $request->file('image')[$i]->store('library', 'public');
            }
            
            Book::create([
                'rack' => $request['rack'][$i],
                'rack_location' => $request['no_rack'][$i],
                'code' => $request['code'][$i],
                'title' => $request['title'][$i],
                'author' => $request['author'][$i],
                'category' => $request['category'][$i],
                'publisher' => $request['publisher'][$i],
                'year_published' => $request['year'][$i],
                'cover_image' => $filePath,
                'description' => $request['description'][$i],
                'total' => $request['total'][$i],
            ]);
        }

        session()->flash('success', 'Data berhasil ditambahkan');
        return redirect()->back();
    }

    public function updateLibrary(Request $request){
        if(count($request->files) == 0){
            Book::where('id', $request->id)->update([
                'rack' => $request->rack,
                'rack_location' => $request->no_rack,
                'code' => $request->code,
                'title' => $request->title,
                'author' => $request->author,
                'category' => $request->category,
                'publisher' => $request->publisher,
                'year_published' => $request->year,
                'description' => $request->description,
                'total' => $request->total,
            ]);
        }
        else{
            $book = Book::findOrFail($request->id);
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $filePath = $request->file('image')->store('library', 'public');
            Book::where('id', $request->id)->update([
                'rack' => $request->rack,
                'rack_location' => $request->no_rack,
                'code' => $request->code,
                'title' => $request->title,
                'author' => $request->author,
                'category' => $request->category,
                'publisher' => $request->publisher,
                'year_published' => $request->year,
                'cover_image' => $filePath,
                'description' => $request->description,
                'total' => $request->total,
            ]);
        }

        session()->flash('success', 'Data berhasil diubah');
        return redirect()->back();
    }

    public function deleteBook($id){
        $book = Book::find($id);
        $book->delete();

        return response()->json(['success' => true]);
    }
    // END CRUD LIBRARY


    // CRUD CD BOOK
    public function cdBook(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $books = \App\Models\Cupboard_cd_book::query();

        // Filter berdasarkan tanggal
        if (
            request()->has('date_from') && !empty(request('date_from')) &&
            request()->has('date_to') && !empty(request('date_to'))
        ) {
            $from = request('date_from') . ' 00:00:00';
            $to = request('date_to') . ' 23:59:59';
            $books->whereBetween('created_at', [$from, $to]);
        }
        
        // Pencarian
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $books->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('cupboard', 'like', "%{$search}%")
                ->orWhere('rack', 'like', "%{$search}%");
            });
        }

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = Cupboard_cd_book::paginate(5);

        return view('components.library.create-cd-book', compact('categories', 'books'));
    }

    public function storeCdBook(Request $request){
        for($i=0; $i<count($request->rack); $i++){
            $filePath = null;
            if ($request['cover_image'][$i] != null) {
                $filePath = $request->file('cover_image')[$i]->store('library', 'public');
            }
            Cupboard_cd_book::create([
                'cupboard' => $request['cupboard'][$i],
                'rack' => $request['rack'][$i],
                'no' => $request['no'][$i],
                'name' => $request['name'][$i],
                'total' => $request['total'][$i],
                'cover_image' => $filePath,
                'information' => $request['information'][$i],
                'progress' => $request['progress'][$i],
            ]);
        }

        session()->flash('success', 'Data berhasil ditambahkan');
        return redirect()->back();
    }

    public function deleteCdBook($id){
        $book = Cupboard_cd_book::find($id);
        $book->delete();

        return response()->json(['success' => true]);
    }

    public function editCdBook($id){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $data = Cupboard_cd_book::find($id);

        return response()->json(['data' => $data, 'categories' => $categories]);
    }

    public function updateCdBook(Request $request){
        if(count($request->files) == 0){
            Cupboard_cd_book::where('id', $request->id)->update([
                'cupboard' => $request->cupboard,
                'rack' => $request->rack,
                'no' => $request->no,
                'name' => $request->name,
                'total' => $request->total,
                'information' => $request->information,
                'progress' => $request->progress,
            ]);
        }
        else{
            $cupboardCdBook = Cupboard_cd_book::findOrFail($request->id);
            if ($cupboardCdBook->cover_image && Storage::disk('public')->exists($cupboardCdBook->cover_image)) {
                Storage::disk('public')->delete($cupboardCdBook->cover_image);
            }
            $filePath = $request->file('cover_image')->store('library', 'public');
            Cupboard_cd_book::where('id', $request->id)->update([
                'cupboard' => $request['cupboard'],
                'rack' => $request['rack'],
                'no' => $request['no'],
                'name' => $request['name'],
                'total' => $request['total'],
                'cover_image' => $filePath,
                'information' => $request['information'],
                'progress' => $request['progress'],
            ]);
        }
        
        session()->flash('success', 'Data berhasil diubah');
        return redirect()->back();
    }
    // END CRUD CD BOOK

    // CRUD THREE LEVEL
    public function threeLevel(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $books = \App\Models\Cupboard_three_level::query();

        if (
            request()->has('date_from') && !empty(request('date_from')) &&
            request()->has('date_to') && !empty(request('date_to'))
        ) {
            $from = request('date_from') . ' 00:00:00';
            $to = request('date_to') . ' 23:59:59';
            $books->whereBetween('created_at', [$from, $to]);
        }
        
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $books->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%")
                    ->orWhere('information', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('rack', 'like', "%{$search}%");
            });
        }

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = $books->orderBy('rack', 'ASC')
        ->orderBy('no', 'ASC')->paginate(20);

        return view('components.library.create-three-level', compact('categories', 'books'));
    }

    public function storeThreeLevel(Request $request){
        for($i=0; $i<count($request->rack); $i++){
            $filePath = null;
            if ($request['cover_image'][$i] != null) {
                $filePath = $request->file('cover_image')[$i]->store('library', 'public');
            }
            Cupboard_three_level::create([
                'rack' => $request['rack'][$i],
                'no' => $request['no_rack'][$i],
                'name' => $request['name'][$i],
                'total' => $request['total'][$i],
                'publisher' => $request['publisher'][$i],
                'year' => $request['year'][$i],
                'city' => $request['city'][$i],
                'isbn' => $request['isbn'][$i],
                'cover_image' => $filePath,
                'information' => $request['information'][$i],
            ]);
        }

        session()->flash('success', 'Data berhasil ditambahkan');
        return redirect()->back();
    }

    public function deleteLibraryThreeLevel($id){
        $book = Cupboard_three_level::find($id);
        $book->delete();

        return response()->json(['success' => true]);
    }

    public function editThreeLevel($id){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $data = Cupboard_three_level::find($id);

        return response()->json(['data' => $data, 'categories' => $categories]);
    }

    public function updateThreeLevel(Request $request){
        if(count($request->files) == 0){
            Cupboard_three_level::where('id', $request->id)->update([
                'rack' => $request->rack,
                'no' => $request->no_rack,
                'name' => $request->name,
                'total' => $request->total,
                'publisher' => $request->publisher,
                'year' => $request->year,
                'city' => $request->city,
                'isbn' => $request->isbn,
                'information' => $request->information,
            ]);
        }
        else{
            $filePath = null;
            $cupboardThreeLevel = Cupboard_cd_book::findOrFail($request->id);
            if ($cupboardThreeLevel->image && Storage::disk('public')->exists($cupboardThreeLevel->image)) {
                Storage::disk('public')->delete($cupboardThreeLevel->image);
            }
            $filePath = $request->file('image')->store('library', 'public');
            Cupboard_three_level::where('id', $request->id)->update([
                'rack' => $request->rack,
                'no' => $request->no_rack,
                'name' => $request->name,
                'total' => $request->total,
                'publisher' => $request->publisher,
                'year' => $request->year,
                'city' => $request->city,
                'isbn' => $request->isbn,
                'cover_image' => $filePath,
                'information' => $request->information,
            ]);
        }
        
        session()->flash('success', 'Data berhasil diubah');
        return redirect()->back();
    }

    public function deleteThreeLevel($id){
        $book = Cupboard_three_level::find($id);
        $book->delete();

        return response()->json(['success' => true]);
    }
    // END THREE LEVEL

    // CRUD SMALL WAREHOUSE
    public function smallWarehouse(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $books = \App\Models\Small_warehouse_library::query();

        if (
            request()->has('date_from') && !empty(request('date_from')) &&
            request()->has('date_to') && !empty(request('date_to'))
        ) {
            $from = request('date_from') . ' 00:00:00';
            $to = request('date_to') . ' 23:59:59';
            $books->whereBetween('created_at', [$from, $to]);
        }
        
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $books->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%")
                    ->orWhere('information', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('place', 'like', "%{$search}%");
            });
        }

        $places = [
            "lantai 1",
            "lantai 2",
            "Meja 1",
            "Meja 2",
            "Meja 3",
            "Kursi 1",
            "Kursi 2",
            "Kardus 1",
            "Kardus 2",
            "Kardus 3",
            "Kardus 4",
            "Dikasih ke Siswa",
            "Lemari 1",
            "Lemari 2",
            "Lemari 3",
            "Lemari 4",
        ];

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = $books->paginate(20);

        return view('components.library.create-small-warehouse', compact('categories', 'books', 'places'));
    }

    public function storeSmallWarehouse(Request $request){
        for($i=0; $i<count($request->place); $i++){
            $filePath = null;
            if ($request['cover_image'][$i] != null) {
                $filePath = $request->file('cover_image')[$i]->store('library', 'public');
            }

            Small_warehouse_library::create([
                'place' => $request['place'][$i],
                'rack' => $request['rack'][$i],
                'name' => $request['name'][$i],
                'total' => $request['total'][$i],
                'publisher' => $request['publisher'][$i],
                'year' => $request['year'][$i],
                'city' => $request['city'][$i],
                'isbn' => $request['isbn'][$i],
                'cover_image' => $filePath,
                'information' => $request['information'][$i],
            ]);
        }

        session()->flash('success', 'Data berhasil ditambahkan');
        return redirect()->back();
    }

    public function editSmallWarehouse($id){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $data = Small_warehouse_library::find($id);

        return response()->json(['data' => $data, 'categories' => $categories]);
    }

    public function updateSmallWarehouse(Request $request){
        if(count($request->files) == 0){
            Small_warehouse_library::where('id', $request->id)->update([
                'place' => $request->place,
                'rack' => $request->rack,
                'no' => $request->no_rack,
                'name' => $request->name,
                'total' => $request->total,
                'publisher' => $request->publisher,
                'year' => $request->year,
                'city' => $request->city,
                'isbn' => $request->isbn,
                'information' => $request->information,
            ]);
        }else{
            $filePath = null;
            $smallWarehouse = Small_warehouse_library::findOrFail($request->id);
            if ($smallWarehouse->cover_image && Storage::disk('public')->exists($smallWarehouse->cover_image)) {
                Storage::disk('public')->delete($smallWarehouse->cover_image);
            }
            $filePath = $request->file('image')->store('library', 'public');
            Small_warehouse_library::where('id', $request->id)->update([
                'place' => $request->place,
                'rack' => $request->rack,
                'no' => $request->no_rack,
                'name' => $request->name,
                'total' => $request->total,
                'publisher' => $request->publisher,
                'year' => $request->year,
                'city' => $request->city,
                'isbn' => $request->isbn,
                'cover_image' => $filePath,
                'information' => $request->information,
            ]);
        }
        
        session()->flash('success', 'Data berhasil diubah');
        return redirect()->back();
    }

    public function deleteSmallWarehouse($id){
        $book = Small_warehouse_library::find($id);
        $book->delete();

        return response()->json(['success' => true]);
    }
    // END CRUD SMALL WAREHOUSE

    // CRUD REFERENCE BOOK
    public function referenceBook(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $books = \App\Models\Reference_book::query();

        if (
            request()->has('date_from') && !empty(request('date_from')) &&
            request()->has('date_to') && !empty(request('date_to'))
        ) {
            $from = request('date_from') . ' 00:00:00';
            $to = request('date_to') . ' 23:59:59';
            $books->whereBetween('created_at', [$from, $to]);
        }
        
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $books->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('information', 'like', "%{$search}%")
                    ->orWhere('no', 'like', "%{$search}%")
                    ->orWhere('rack', 'like', "%{$search}%");
            });
        }

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = $books->orderBy('rack', 'ASC')->paginate(5);

        return view('components.library.create-reference-book', compact('categories', 'books'));
    }

    public function storeReferenceBook(Request $request){
        for($i=0; $i<count($request->rack); $i++){
            Reference_book::create([
                'rack' => $request['rack'][$i],
                'no' => $request['no'][$i],
                'name' => $request['name'][$i],
                'author' => $request['author'][$i],
                'publisher' => $request['publisher'][$i],
                'total' => $request['total'][$i],
                'information' => $request['information'][$i],
            ]);
        }

        session()->flash('success', 'Data berhasil ditambahkan');
        return redirect()->back();
    }

    public function editReferenceBook($id){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $data = Reference_book::find($id);

        return response()->json(['data' => $data, 'categories' => $categories]);
    }

    public function updateReferenceBook(Request $request){
        Reference_book::where('id', $request->id)->update([
            'rack' => $request->rack,
            'no' => $request->no,
            'name' => $request->name,
            'total' => $request->total,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'information' => $request->information,
        ]);
        
        session()->flash('success', 'Data berhasil diubah');
        return redirect()->back();
    }

    public function deleteReferenceBook($id){
        $book = Reference_book::find($id);
        $book->delete();

        return response()->json(['success' => true]);
    }
    // END CRUD REFERENCE BOOK

    // CRUD LEMARI CD
    public function lemariCD(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $books = \App\Models\Lemari_cd::query();

        if (
            request()->has('date_from') && !empty(request('date_from')) &&
            request()->has('date_to') && !empty(request('date_to'))
        ) {
            $from = request('date_from') . ' 00:00:00';
            $to = request('date_to') . ' 23:59:59';
            $books->whereBetween('created_at', [$from, $to]);
        }
        
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $books->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('information', 'like', "%{$search}%")
                ->orWhere('rack', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = $books->orderBy('rack', 'ASC')->paginate(20);

        return view('components.library.create-lemari-cd', compact('categories', 'books'));
    }

    public function storeLemariCD(Request $request){
        for($i=0; $i<count($request->rack); $i++){
            Lemari_cd::create([
                'rack' => $request['rack'][$i],
                'code' => $request['code'][$i],
                'name' => $request['name'][$i],
                'total' => $request['total'][$i],
                'information' => $request['information'][$i],
            ]);
        }

        session()->flash('success', 'Data berhasil ditambahkan');
        return redirect()->back();
    }

    public function editLemariCD($id){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $data = Lemari_cd::find($id);

        return response()->json(['data' => $data, 'categories' => $categories]);
    }

    public function updateLemariCD(Request $request){
        Lemari_cd::where('id', $request->id)->update([
            'rack' => $request->rack,
            'code' => $request->code,
            'name' => $request->name,
            'total' => $request->total,
            'information' => $request->information,
        ]);
        
        session()->flash('success', 'Data berhasil diubah');
        return redirect()->back();
    }

    public function deleteLemariCD($id){
        $book = Lemari_cd::find($id);
        $book->delete();

        return response()->json(['success' => true]);
    }
    // END CRUD LEMARI CD
    
    // CRUD CURRICUCLUM OLD
    public function curriculumOld(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $books = \App\Models\Curriculum_old::query();

        if (
            request()->has('date_from') && !empty(request('date_from')) &&
            request()->has('date_to') && !empty(request('date_to'))
        ) {
            $from = request('date_from') . ' 00:00:00';
            $to = request('date_to') . ' 23:59:59';
            $books->whereBetween('created_at', [$from, $to]);
        }
        
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $books->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('publisher', 'like', "%{$search}%")
                ->orWhere('information', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = $books->paginate(20);

        return view('components.library.create-curriculum-old', compact('categories', 'books'));
    }

    public function storeCurriculumOld(Request $request){
        for($i=0; $i<count($request->subject); $i++){
            Curriculum_old::create([
                'subject' => $request['subject'][$i],
                'code' => $request['code'][$i],
                'name' => $request['name'][$i],
                'author' => $request['author'][$i],
                'publisher' => $request['publisher'][$i],
                'total' => $request['total'][$i],
                'information' => $request['information'][$i],
            ]);
        }

        session()->flash('success', 'Data berhasil ditambahkan');
        return redirect()->back();
    }

    public function editCurriculumOld($id){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $data = Curriculum_old::find($id);

        return response()->json(['data' => $data, 'categories' => $categories]);
    }

    public function updateCurriculumOld(Request $request){
        Curriculum_old::where('id', $request->id)->update([
            'subject' => $request->subject,
            'code' => $request->code,
            'name' => $request->name,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'total' => $request->total,
            'information' => $request->information,
        ]);
        
        session()->flash('success', 'Data berhasil diubah');
        return redirect()->back();
    }

    public function deleteCurriculumOld($id){
        $book = Curriculum_old::find($id);
        $book->delete();

        return response()->json(['success' => true]);
    }
    // END CRUD CURRICULUM OLD


    public function libraryPublic(){
        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        $books = Book::paginate(5);

        if(session('role') == 'student'){
            $reminder = Reserve_book::with(['book'])->where('user_id', session('id_user'))
            ->where('status', 1)
            ->whereDate('return_date', Carbon::tomorrow()) // H-1 dari besok = hari ini
            ->get();

            $funfacts = Article_library::get();
            $randoms = $funfacts->random(3);
            
            return view('components.library.library-public', compact('categories', 'books', 'reminder', 'funfacts', 'randoms'));
        }
        else{
            $funfacts = Article_library::get();
            $randoms = $funfacts->random(3);
            $reminder = null;
            // dd($randoms);
            return view('components.library.library-public', compact('categories', 'books', 'reminder', 'funfacts', 'randoms'));
        }
    }

    public function others(){
        $funfacts = Article_library::get();
        return view('components.library.others', compact('funfacts'));
    }

    public function booking(){
        return view('components.library.booking');
    }

    public function explore(Request $request){
        $categories = Subject::orderBy('name_subject', 'ASC')->get();
        
        $form = (object)[
            'search' => $request->search ?? null,
        ];

        if(session('role') == 'library'){
            $books = Book::with(['reserve.user']);
            if(!is_null($form->search)){
                $books->where('title', 'like', '%' . $form->search . '%');
            }

            $books = $books->orderBy('title', 'asc')->paginate(16);
        }
        elseif(session('role') == 'student'){
            if(!is_null($form->search)){
                $books= Book::where('title', 'like', '%' . $form->search . '%');
            }
            else{
                $books = Book::with(['reserve' => function($query){
                    $query->where('user_id', session('id_user'));
                }])->whereHas('reserve', function($query){
                    $query->where('user_id', session('id_user'));
                })->orWhereDoesntHave('reserve', function($query){
                    $query->where('user_id', session('id_user'));
                })->with(['reserve' => function($query){
                    $query->where('user_id', session('id_user'));   
                }]);
            }
            $books = $books->orderBy('title', 'asc')->paginate(16);
        }
        else{
            $books = Book::with(['reserve' => function($query){
            $query->where('user_id', session('id_user'));
            }])->whereHas('reserve', function($query){
                $query->where('user_id', session('id_user'));
            })->orWhereDoesntHave('reserve', function($query){
                $query->where('user_id', session('id_user'));
            })->with(['reserve' => function($query){
                $query->where('user_id', session('id_user'));   
            }]);
            // dd($form->search);
            if(!is_null($form->search)){
                $books->where('title', 'like', '%' . $form->search . '%');
            }
            $books = $books->orderBy('title', 'asc')->paginate(16);
        }

        return view('components.library.explore', compact('categories', 'books'));
    }

    public function reserveBook(Request $request){
        dd($request);
        Reserve_book::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'reserve_date' => $request->pick,
            'return_date' => $request->return,
            'status' => 0,
        ]);

        session()->flash('success');
        return redirect()->back();
    }

    public function getBook($id){
        $book = Book::find($id);
        return response()->json(['data' => $book]);
    }

    public function reserve(){
        session()->flash('page',  $page = (object)[
            'page' => 'reserve',
            'child' => 'reserve',
        ]);

        $students = Student::where('is_active', 1)->orderBy('name', "ASC")->get();
        $data = Reserve_book::with(['book', 'user.student'])->orderBy('created_at', 'DESC')->paginate(20);
        return view('components.library.data-reserve', compact('data', 'students'));
    }

    public function donePick($id){
        $return_date = \Carbon\Carbon::now()->addDays(14);
        Reserve_book::where('id', $id)->update([
            'reserve_date' => now(),
            'return_date' => $return_date,
            'status' => 1,
            'updated_at' => now(),
        ]);
        return response()->json(['success' => true]);
    }

    public function doneReturn($id){
        Reserve_book::where('id', $id)->update([
            'return_receive' => now(),
            'status' => 2,
        ]);

        return response()->json(['success' => true]);
    }

    public function remind($id){
        $userId = Reserve_book::where('id', $id)->value('user_id');
        return response()->json(['success' => true]);
    }

    public function cancel($id){
        Reserve_book::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function visit(){
        return view('components.library.visit');
    }

    public function visitStudent(Request $request){
        $userId = User::where('username', $request->username)->value('id');
        $roleStudent = User::where('id', $userId)->value('role_id');

        if($roleStudent == 4){
            if($userId == null){
                session()->flash('error', 'User tidak ditemukan');
                return redirect()->back();
            }
            else{
                Visit_student::create([
                    'user_id' => $userId,
                    'visit_date' => now(),
                ]);
                
                session()->flash('success');
                return redirect()->back();
            }
        }
        else{
            session()->flash('error', 'User tidak ditemukan');
            return redirect()->back();
        }
    }

    public function visitor(Request $request){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'visitor',
        ]);

        $students = Student::where('is_active', 1)->orderBy('name', "ASC")->get();
        $data = Visit_student::with(['student.grade'])->orderBy('created_at', 'DESC')->paginate(5);
        // dd($data);
        return view('components.library.visitor', compact('data', 'students'));
    }


    public function articleAdmin(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'article',
        ]);

        $data = Article_library::paginate(15);

        return view('components.library.create-article-library', compact('data'));
    }

    public function article(){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'article',
        ]);

        $data = Article_library::paginate(10);

        return view('components.library.article-library', compact('data'));
    }
    
    public function storeArticle(Request $request){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'article',
        ]);

        if(session('role') == 'library'){
            Article_library::create([
                'title' => $request->title,
                'description' => $request->description,
                'author' => "Admin",
            ]);
        }
        else{
            Article_library::create([
                'title' => $request->title,
                'description' => $request->description,
                'author' => session('role'),
            ]);
        }

        session()->flash('success', 'Data berhasil ditambahkan');
        return redirect()->back();
    }

    public function editArticle($id){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        $data = Article_library::find($id);
        
        return view('components.library.edit-article-library', compact('data'));
    }

    public function updateArticle(Request $request){
        session()->flash('page',  $page = (object)[
            'page' => 'library',
            'child' => 'library',
        ]);

        Article_library::where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'author' => session('role'),
        ]);

        session()->flash('success');
        return redirect()->back();
    }

    public function deleteArticle($id){
        $article = Article_library::find($id);
        $article->delete();

        return response()->json(['success' => true]);
    }

    public function facility(){
        $funfacts = Article_library::get();
        return view('components.library.facility', compact('funfacts'));
    }

    public function search(Request $request){
        $search = Book::where('title', 'LIKE', '%' . $request->search . '%')->first();
        if (!$search) {
            return response()->json(['success' => false]);
        }
        return response()->json(['success' => true, 'data' => $search]);
    }

    public function dashboardPlanVisit(){
        session()->flash('page',  $page = (object)[
            'page' => 'plan visit',
            'child' => 'plan visit',
        ]);

        $data = Plan_visit::orderBy('created_at', 'desc')->paginate(5);

        return view('components.library.data-plan-visit')->with('data', $data);
    }

    public function planVisit(Request $request){
        Plan_visit::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'plan_visit' => $request->plan_visit,
            'email_send' => false,
        ]);

        session()->flash('plan_visit');
        return redirect()->back();
    }
    
    public function confirmPlanVisit($id){
        Plan_visit::where('id', $id)->update([
            'status' => 'acc',
        ]);

        return response()->json(['success' => true]);
    }

    public function cancelPlanVisit($id){
        Plan_visit::where('id', $id)->update([
            'status' => 'cancel',
        ]);

        return response()->json(['success' => true]);
    }
}
