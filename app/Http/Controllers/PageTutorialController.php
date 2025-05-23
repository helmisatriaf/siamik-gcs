<?php

namespace App\Http\Controllers;

use App\Models\Page_Tutorials;
use App\Models\Pages;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PageTutorialController extends Controller
{
    public function index(Request $request)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'tutorial',
                'child' => 'database tutorials',
            ]);

            // panggil semua data pages
            $pages = Pages::all();

            // Buat query dasar
            $query = Page_Tutorials::with('page')->withoutTrashed();
    
            // Filter berdasarkan tanggal
            if ($request->filled(['date_from', 'date_to'])) {
                $query->whereBetween('created_at', [
                    Carbon::parse($request->date_from)->startOfDay(),
                    Carbon::parse($request->date_to)->endOfDay()
                ]);
            } elseif ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            } elseif ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
    
            // Filter berdasarkan page/kategori
            if ($request->filled('filter_category')) {
                $query->whereHas('page', function($q) use ($request) {
                    $q->where('name', $request->filter_category);
                });
            }
    
            // Filter berdasarkan pencarian
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhereHas('page', function($q2) use ($searchTerm) {
                          $q2->where('name', 'LIKE', "%{$searchTerm}%");
                      });
                });
            }
    
            // Ambil data dengan pagination
            $tutorials = $query->latest()->paginate(5)->withQueryString();
    
            return view('components.tutorials.index-tutorial', compact('tutorials', 'pages'));
        } catch (Exception $err) {
            dd($err);
        }
    }
    


    public function create()
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'tutorial',
                'child' => 'database tutorials',
            ]);

            $pages = Pages::all();

            return view('components.tutorials.create-tutorial', compact('pages'));
        } catch (Exception $err) {
            dd($err);
        }
    }


    public function store(Request $request)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'tutorial',
                'child' => 'database tutorials',
            ]);

            // Validasi input
            $validatedData = $request->validate([
                'page_name'         => 'required|string|max:255',
                'title'            => 'required|string|max:255',
                'media_type'       => 'required|string|in:video,image,text',
                'order'            => 'required|integer',
                'media_path'       => 'nullable|file|mimes:mp4,jpg,png|max:100240',
                'description'      => 'nullable|string',
                'element_selector' => 'nullable|string|max:255',
                'position'         => 'nullable|string|in:top,bottom,left,right'
            ]);

            // Dapatkan page yang sudah ada
            $page = Pages::where('name', $validatedData['page_name'])->firstOrFail();

            // Initialize media path as null
            $mediaPath = null;

            // Handle file upload hanya jika media type adalah video atau image
            if (in_array($validatedData['media_type'], ['video', 'image']) && $request->hasFile('media_path')) {
                $file = $request->file('media_path');
                $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                $mediaPath = $file->storeAs('uploads/tutorials', $fileName, 'public');
            }

            // Create tutorial
            Page_Tutorials::create([
                'page_id'           => $page->id,
                'title'             => $validatedData['title'],
                'description'       => $validatedData['description'],
                'media_type'        => $validatedData['media_type'],
                'media_path'        => $mediaPath,
                'order'             => $validatedData['order'],
                'element_selector'  => $validatedData['element_selector'] ?? null,
                'position'          => $validatedData['position'] ?? 'bottom',
                'created_by'        => auth()->user()->name ?? 'System'
            ]);

            return redirect()->route('tutorials.index')->with('success', 'Tutorial has been successfully saved!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Khusus untuk error validasi
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $err) {
            // Untuk error lainnya
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $err->getMessage())
                ->withInput();
        }
    }

    public function storePage(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:pages,name',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            // Simpan data halaman baru
            $page = Pages::create([
                'name' => $request->name,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Halaman berhasil ditambahkan!',
                'page' => $page
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan halaman!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'tutorial',
                'child' => 'database tutorials',
            ]);

            $tutorial = Page_Tutorials::findOrFail($id);
            $pages = Pages::all(); // Add this to get all pages for the select dropdown

            return view('components.tutorials.edit-tutorial', [
                'tutorial' => $tutorial,
                'pages' => $pages,
            ]);
        } catch (Exception $err) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $err->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Log request data
            Log::info('Tutorial update started', [
                'tutorial_id' => $id,
                'request_data' => $request->except(['media_path']),
                'has_file' => $request->hasFile('media_path')
            ]);

            session()->flash('page', (object)[
                'page' => 'tutorial',
                'child' => 'database tutorials',
            ]);

            // Validasi input
            $validatedData = $request->validate([
                'page_name'        => 'required|string|max:255',
                'title'            => 'required|string|max:255',
                'media_type'       => 'required|string|in:video,image,text',
                'order'            => 'required|integer',
                'media_path'       => 'nullable|file|mimes:mp4,jpg,png|max:100240',
                'description'      => 'nullable|string',
                'element_selector' => 'nullable|string|max:255',
                'position'         => 'nullable|string|in:top,bottom,left,right'
            ]);

            Log::info('Validation passed', ['validated_data' => $validatedData]);

            // Dapatkan tutorial yang akan diupdate
            $tutorial = Page_Tutorials::findOrFail($id);
            Log::info('Tutorial found', ['tutorial' => $tutorial]);

            // Dapatkan page yang sudah ada
            $page = Pages::where('name', $validatedData['page_name'])->firstOrFail();
            Log::info('Page found', ['page' => $page]);

            // Initialize media path dengan nilai yang sudah ada
            $mediaPath = $tutorial->media_path;

            // Handle file upload
            if (in_array($validatedData['media_type'], ['video', 'image']) && $request->hasFile('media_path')) {
                Log::info('Starting file upload process', [
                    'media_type' => $validatedData['media_type'],
                    'original_filename' => $request->file('media_path')->getClientOriginalName(),
                    'file_size' => $request->file('media_path')->getSize(),
                    'mime_type' => $request->file('media_path')->getMimeType()
                ]);

                // Check storage directory
                $uploadPath = storage_path('app/public/uploads/tutorials');
                if (!file_exists($uploadPath)) {
                    Log::info('Creating upload directory', ['path' => $uploadPath]);
                    mkdir($uploadPath, 0755, true);
                }

                // Hapus file lama jika ada
                if ($tutorial->media_path && Storage::disk('public')->exists($tutorial->media_path)) {
                    try {
                        Storage::disk('public')->delete($tutorial->media_path);
                        Log::info('Old file deleted', ['old_path' => $tutorial->media_path]);
                    } catch (\Exception $e) {
                        Log::error('Failed to delete old file', [
                            'error' => $e->getMessage(),
                            'old_path' => $tutorial->media_path
                        ]);
                    }
                }

                // Upload file baru
                try {
                    $file = $request->file('media_path');
                    $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                    $mediaPath = $file->storeAs('uploads/tutorials', $fileName, 'public');

                    // Verify file exists after upload
                    if (!Storage::disk('public')->exists($mediaPath)) {
                        throw new \Exception('File not found after upload');
                    }
                } catch (\Exception $e) {
                    Log::error('File upload failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // Update tutorial
            $updateData = [
                'page_id'           => $page->id,
                'title'             => $validatedData['title'],
                'description'       => $validatedData['description'],
                'media_type'        => $validatedData['media_type'],
                'media_path'        => $mediaPath,
                'order'             => $validatedData['order'],
                'element_selector'  => $validatedData['element_selector'] ?? null,
                'position'          => $validatedData['position'] ?? 'bottom',
                'updated_by'        => auth()->user()->name ?? 'System'
            ];

            Log::info('Updating tutorial with data', ['update_data' => $updateData]);

            $tutorial->update($updateData);
            Log::info('Tutorial updated successfully');

            return redirect()->route('tutorials.index')->with('success', 'Tutorial has been updated!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['media_path'])
            ]);
            throw $e;
        } catch (\Exception $err) {
            Log::error('Tutorial update failed', [
                'error' => $err->getMessage(),
                'trace' => $err->getTraceAsString(),
                'line' => $err->getLine(),
                'file' => $err->getFile()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $err->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $tutorial = Page_Tutorials::find($id);

            if (!$tutorial) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan!']);
            }

            // Hapus secara soft delete
            $tutorial->delete();

            return response()->json(['success' => true, 'message' => 'Tutorial berhasil dihapus!']);
        } catch (Exception $err) {
            Log::error("Gagal menghapus tutorial ID: $id, Error: " . $err->getMessage());

            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.']);
        }
    }

    public function getTutorial(Page_Tutorials $tutorial)
    {
        return response()->json($tutorial);
    }
}
