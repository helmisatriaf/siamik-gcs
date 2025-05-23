@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-12">
            <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                <a class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center" href="{{ route('library.add.book') }}">
                
                    <!-- Ribbon -->
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-dark text-sm">
                            Library
                        </div>
                    </div>
                
                    <!-- Bagian Utama -->
                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <!-- Ikon -->
                        <div>
                            <img loading="lazy" src="{{ asset('images/book.png') }}" 
                             alt="avatar" class="profileImage img-fluid" 
                             style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                        </div>
                        <!-- Nama Subject -->
                        <div class="inner mt-2">
                            <p class="mb-0 text-lg fw-bold text-center">Data Buku</p>
                        </div>
                    </div>
                </a>
            </div>         
        </div>
        <div class="col-lg-3 col-md-4 col-12">
            <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                <a class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center" href="{{ route('library.add.cd.and.book') }}">
                
                    <!-- Ribbon -->
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-dark text-sm">
                            Library
                        </div>
                    </div>
                
                    <!-- Bagian Utama -->
                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <!-- Ikon -->
                        <div>
                            <img loading="lazy" src="{{ asset('images/book.png') }}" 
                             alt="avatar" class="profileImage img-fluid" 
                             style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                        </div>
                        <!-- Nama Subject -->
                        <div class="inner mt-2">
                            <p class="mb-0 text-lg fw-bold text-center">Lemari CD & Buku</p>
                        </div>
                    </div>
                </a>
            </div>         
        </div>
        <div class="col-lg-3 col-md-4 col-12">
            <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                <a class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center" href="{{ route('library.three.level') }}">
                
                    <!-- Ribbon -->
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-dark text-sm">
                            Library
                        </div>
                    </div>
                
                    <!-- Bagian Utama -->
                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <!-- Ikon -->
                        <div>
                            <img loading="lazy" src="{{ asset('images/book.png') }}" 
                             alt="avatar" class="profileImage img-fluid" 
                             style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                        </div>
                        <!-- Nama Subject -->
                        <div class="inner mt-2">
                            <p class="mb-0 text-lg fw-bold text-center">Lemari Buku 3 Tingkat</p>
                        </div>
                    </div>
                </a>
            </div>         
        </div>
        <div class="col-lg-3 col-md-4 col-12">
            <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                <a class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center" href="{{ route('library.small.warehouse') }}">
                
                    <!-- Ribbon -->
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-dark text-sm">
                            Library
                        </div>
                    </div>
                
                    <!-- Bagian Utama -->
                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <!-- Ikon -->
                        <div>
                            <img loading="lazy" src="{{ asset('images/book.png') }}" 
                             alt="avatar" class="profileImage img-fluid" 
                             style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                        </div>
                        <!-- Nama Subject -->
                        <div class="inner mt-2">
                            <p class="mb-0 text-lg fw-bold text-center">Gudang Kecil</p>
                        </div>
                    </div>
                </a>
            </div>         
        </div>
        <div class="col-lg-3 col-md-4 col-12">
            <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                <a class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center" href="{{ route('library.reference.book') }}">
                
                    <!-- Ribbon -->
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-dark text-sm">
                            Library
                        </div>
                    </div>
                
                    <!-- Bagian Utama -->
                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <!-- Ikon -->
                        <div>
                            <img loading="lazy" src="{{ asset('images/book.png') }}" 
                             alt="avatar" class="profileImage img-fluid" 
                             style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                        </div>
                        <!-- Nama Subject -->
                        <div class="inner mt-2">
                            <p class="mb-0 text-lg fw-bold text-center">Reference Books</p>
                        </div>
                    </div>
                </a>
            </div>         
        </div>
        <div class="col-lg-3 col-md-4 col-12">
            <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                <a class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center" href="{{ route('library.lemari.cd') }}">
                
                    <!-- Ribbon -->
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-dark text-sm">
                            Library
                        </div>
                    </div>
                
                    <!-- Bagian Utama -->
                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <!-- Ikon -->
                        <div>
                            <img loading="lazy" src="{{ asset('images/book.png') }}" 
                             alt="avatar" class="profileImage img-fluid" 
                             style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                        </div>
                        <!-- Nama Subject -->
                        <div class="inner mt-2">
                            <p class="mb-0 text-lg fw-bold text-center">Lemari CD</p>
                        </div>
                    </div>
                </a>
            </div>         
        </div>
        <div class="col-lg-3 col-md-4 col-12">
            <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                <a class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center" href="{{ route('library.curriculum.old') }}">
                
                    <!-- Ribbon -->
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-dark text-sm">
                            Library
                        </div>
                    </div>
                
                    <!-- Bagian Utama -->
                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <!-- Ikon -->
                        <div>
                            <img loading="lazy" src="{{ asset('images/book.png') }}" 
                             alt="avatar" class="profileImage img-fluid" 
                             style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                        </div>
                        <!-- Nama Subject -->
                        <div class="inner mt-2">
                            <p class="mb-0 text-lg fw-bold text-center">Kurikulum Lama</p>
                        </div>
                    </div>
                </a>
            </div>         
        </div>
    </div>

    {{-- <div class="card">
        <div class="card-header">
            <h3>Add Book</h3>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Category</label>
                    <select id="category" name="category" class="form-control @error('category') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{ucwords($category->name_subject)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Publisher</label>
                    <input type="text" name="publisher" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Year</label>
                    <input type="text" name="year" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Cover Image</label>
                    <input type="file" name="cover" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Tambah</button>
            </form>
        </div>
    </div> --}}

@endsection
