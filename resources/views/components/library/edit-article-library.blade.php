@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="/create-article-library">Article</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    <form action="{{route('update.article.library')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="number" name="id" value="{{ $data->id }}" hidden>
        <label for="title">Title</label>
        <input type="text" class="form-control mb-3" id="title" name="title" value="{{ $data->title }}" required>
        <label for="description">Description</label>
        <textarea id="froala-editor" name="description" class="form-control" required>{{ $data->description }}</textarea>
        <input role="button" type="submit" class="btn btn-success center w-100 mt-3">
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    new FroalaEditor("textarea#froala-editor", 
    {
        imageUploadURL: "/upload-image-article", // Endpoint Laravel
        imageUploadMethod: "POST",
        imageAllowedTypes: ["jpeg", "jpg", "png", "gif"],
        imageUploadParams: {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute("content"), // Kirim CSRF Token
        },
    });
</script>

@endsection
