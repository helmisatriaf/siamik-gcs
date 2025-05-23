@extends('layouts.admin.master')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Tambah Tutorial Baru</h1>
        <a href="{{ route('tutorials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('tutorials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Page <span class="text-danger">*</span></label>
                            <select name="page_name"
                                class="form-control select2-tags {{ $errors->has('page_name') ? 'is-invalid' : '' }}">
                                <option value="">Select or add a new page</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page->name }}"
                                        {{ old('page_name') == $page->name ? 'selected' : '' }}>
                                        {{ $page->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('page_name'))
                                <span class="error invalid-feedback d-block">
                                    {{ $errors->first('page_name') }}
                                </span>
                            @endif
                            <small class="text-muted">Choose an existing page or add a new one</small>
                            <a href="#" class="text-primary" data-toggle="modal" data-target="#addPageModal">+ Add
                                Page</a>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Media Type <span class="text-danger">*</span></label>
                            <select name="media_type"
                                class="form-control {{ $errors->has('media_type') ? 'is-invalid' : '' }}" id="mediaType">
                                <option value="">Select media type</option>
                                <option value="video" {{ old('media_type') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="image" {{ old('media_type') == 'image' ? 'selected' : '' }}>Image</option>
                                <option value="text" {{ old('media_type') == 'text' ? 'selected' : '' }}>Text</option>
                            </select>
                            @if ($errors->has('media_type'))
                                <span class="error invalid-feedback d-block">
                                    {{ $errors->first('media_type') }}
                                </span>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tutorial Title <span class="text-danger">*</span></label>
                            <input type="text" name="title"
                                class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                value="{{ old('title') }}">

                            @if ($errors->has('title'))
                                <span class="error invalid-feedback d-block">
                                    {{ $errors->first('title') }}
                                </span>
                            @endif
                            <small class="text-muted">Enter a title that matches the selected page</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Order <span class="text-danger">*</span></label>
                            <input type="number" name="order"
                                class="form-control {{ $errors->has('order') ? 'is-invalid' : '' }}"
                                value="{{ old('order', 0) }}" min="0">
                            @if ($errors->has('order'))
                                <span class="error invalid-feedback d-block">
                                    {{ $errors->first('order') }}
                                </span>
                            @endif
                            <small class="text-muted">Order determines the position in the tutorial list</small>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3" id="mediaUpload">
                            <label class="form-label">Media File</label>
                            <input type="file" name="media_path"
                                class="form-control {{ $errors->has('media_path') ? 'is-invalid' : '' }}"
                                accept=".mp4,.jpg,.jpeg,.png">
                            <small class="text-muted d-block mt-1">
                                Supported formats: MP4 for videos, JPG/PNG for images.
                                Maximum size: 10MB.
                            </small>
                            @if ($errors->has('media_path'))
                                <span class="error invalid-feedback d-block">
                                    {{ $errors->first('media_path') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="4"
                                placeholder="Enter tutorial description">{{ old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="error invalid-feedback d-block">
                                    {{ $errors->first('description') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('tutorials.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Tutorial
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Halaman -->
    <div class="modal fade" id="addPageModal" tabindex="-1" aria-labelledby="addPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- Modal di tengah -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPageModalLabel">Create New Page</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="addPageForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="pageName" class="form-label">Page Name</label>
                            <input type="text" class="form-control" id="pageName" name="name">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mediaTypeSelect = document.getElementById('mediaType');
            const mediaUpload = document.getElementById('mediaUpload');

            function toggleMediaUpload() {
                if (mediaTypeSelect.value === 'text') {
                    mediaUpload.style.display = 'none';
                } else {
                    mediaUpload.style.display = 'block';
                }
            }

            // Initial check
            toggleMediaUpload();

            // Listen for changes
            mediaTypeSelect.addEventListener('change', toggleMediaUpload);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#addPageForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('tutorials.pages.store') }}",
                    type: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500
                            });

                            // Reset form dan tutup modal
                            form[0].reset();
                            $('#addPageModal').modal('hide');

                            // Update dropdown
                            let select = $('.select2-tags');
                            if (!select.find(`option[value="${response.page.name}"]`).length) {
                                select.append(new Option(response.page.name, response.page.name,
                                    true, true));
                            }
                            select.trigger('change');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });

                            if (xhr.status === 422) {
                                const errors = response.errors;
                                for (const field in errors) {
                                    $(`#${field}`).addClass('is-invalid')
                                        .siblings('.invalid-feedback')
                                        .text(errors[field][0]);
                                }
                            }
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            // Reset form ketika modal ditutup
            $('#addPageModal').on('hidden.bs.modal', function() {
                const form = $('#addPageForm');
                form[0].reset();
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').empty();
            });
        });
    </script>

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
