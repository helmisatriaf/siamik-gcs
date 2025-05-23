@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Edit Tutorial</h1>
            <a href="{{ route('tutorials.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <form action="{{ route('tutorials.update', $tutorial->id) }}" method="POST" enctype="multipart/form-data"
                    id="tutorialForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Page <span class="text-danger">*</span></label>
                                <select name="page_name"
                                    class="form-control select2-tags {{ $errors->has('page_name') ? 'is-invalid' : '' }}">
                                    <option value="">Select or add a new page</option>
                                    @foreach ($pages as $page)
                                        <option value="{{ $page->name }}"
                                            {{ old('page_name', $tutorial->page->name ?? '') == $page->name ? 'selected' : '' }}>
                                            {{ $page->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('page_name')
                                    <span class="error invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Choose an existing page or add a new one</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Media Type <span class="text-danger">*</span></label>
                                <select name="media_type"
                                    class="form-control {{ $errors->has('media_type') ? 'is-invalid' : '' }}"
                                    id="mediaType">
                                    <option value="">Select media type</option>
                                    <option value="video"
                                        {{ old('media_type', $tutorial->media_type) == 'video' ? 'selected' : '' }}>Video
                                    </option>
                                    <option value="image"
                                        {{ old('media_type', $tutorial->media_type) == 'image' ? 'selected' : '' }}>Image
                                    </option>
                                    <option value="text"
                                        {{ old('media_type', $tutorial->media_type) == 'text' ? 'selected' : '' }}>Text
                                    </option>
                                </select>
                                @error('media_type')
                                    <span class="error invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tutorial Title <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                    value="{{ old('title', $tutorial->title) }}">
                                @error('title')
                                    <span class="error invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Enter a title that matches the selected page</small>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">Order <span class="text-danger">*</span></label>
                                <input type="number" name="order"
                                    class="form-control {{ $errors->has('order') ? 'is-invalid' : '' }}"
                                    value="{{ old('order', $tutorial->order) }}" min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Order determines the position in the tutorial list</small>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3" id="mediaUpload">
                                <label class="form-label">Media File</label>
                                <input type="file" name="media_path"
                                    class="form-control {{ $errors->has('media_path') ? 'is-invalid' : '' }}"
                                    accept=".mp4,.jpg,.jpeg,.png">
                                <small class="text-muted d-block mt-1">Supported formats: MP4 for video, JPG/PNG for images.
                                    Max 10MB.</small>
                                @if ($tutorial->media_path)
                                    <small class="d-block mt-1">Current file: <a
                                            href="{{ Storage::url($tutorial->media_path) }}" target="_blank">View
                                            File</a></small>
                                @endif
                                @error('media_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="4">{{ old('description', $tutorial->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('tutorials.index') }}" class="btn btn-secondary me-2"><i
                                class="fas fa-times"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Tutorial</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Tampilkan/sembunyikan upload media berdasarkan tipe yang dipilih
            $('#mediaType').change(function() {
                const mediaType = $(this).val();
                if (mediaType === 'text') {
                    $('#mediaUpload').hide();
                } else {
                    $('#mediaUpload').show();
                }
            });
        </script>
    @endpush
@endsection
