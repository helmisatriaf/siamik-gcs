@extends('layouts.admin.master')
@section('content')
    <style>
        .list-group-item:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        .list-group-item.bg-light:hover {
            background-color: #d9d7d7 !important;
        }

        .badge {
            font-weight: 500;
        }

        .media-container {
            width: 100%;
            max-width: 200px;
            /* Dikurangi dari 300px */
            margin: 0 auto;
        }


        .media-preview {
            width: 100%;
            height: 150px;
            /* Dikurangi dari 200px */
            object-fit: cover;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .media-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .video-preview {
            position: relative;
            width: 100%;
            max-height: 150px;
            /* Dikurangi dari 200px */
            background-color: #000;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .video-preview video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .video-preview .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 1.5rem;
            /* Dikurangi dari 2rem */
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 2;
            transition: transform 0.3s ease;
        }

        .video-preview:hover .play-icon {
            transform: translate(-50%, -50%) scale(1.2);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .media-container {
                max-width: 180px;
                /* Ukuran lebih kecil untuk mobile */
            }

            .media-preview,
            .video-preview {
                height: 120px;
                /* Lebih kecil untuk mobile */
            }
        }

        .tutorial-title {
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .description-text {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            margin: 0 0.125rem;
        }

        .status-badge {
            min-width: 80px;
            text-align: center;
        }
    </style>

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="h5 mb-0">Manajemen Tutorial</h4>
                <small class="text-muted">Total: {{ $tutorials->flatten()->count() }} tutorial</small>
            </div>
            <div>
                <a href="{{ route('tutorials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Tutorial
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('tutorials.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dari Tanggal</label>
                                <input type="date" name="date_from" class="form-control"
                                    value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Halaman</label>
                                <select name="filter_category" class="form-control">
                                    <option value="">Semua Halaman</option>
                                    @foreach ($pages as $page)
                                        <option value="{{ $page->name }}"
                                            {{ old('page_name') == $page->name ? 'selected' : '' }}>
                                            {{ $page->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Pencarian</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari tutorial..."
                                        value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('tutorials.index') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @php
            // Mengelompokkan berdasarkan nama kategori halaman
            $groupedTutorials = $tutorials->groupBy('page.name');
        @endphp

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse ($groupedTutorials as $pageName => $pageTutorials)
                        <div class="list-group-item bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-file-alt mr-2"></i>{{ $pageName ?: 'Uncategorized' }}
                                    </h5>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary">
                                        {{ $pageTutorials->count() }} Tutorials
                                    </span>
                                </div>
                            </div>
                        </div>

                        @foreach ($pageTutorials->sortBy('order') as $tutorial)
                            <div class="list-group-item border-0 border-bottom">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <span class="badge bg-secondary">#{{ $tutorial->order }}</span>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <div class="media-container">
                                            @if ($tutorial->media_type == 'image' && $tutorial->media_path)
                                                <a href="{{ asset('storage/' . $tutorial->media_path) }}" target="_blank"
                                                    rel="noopener noreferrer" title="Click to view full image">
                                                    <img loading="lazy" src="{{ asset('storage/' . $tutorial->media_path) }}"
                                                        class="media-preview" alt="{{ $tutorial->title }}" loading="lazy">
                                                </a>
                                            @elseif($tutorial->media_type == 'video' && $tutorial->media_path)
                                                <div class="video-preview">
                                                    <video class="tutorial-video"
                                                        poster="{{ asset('storage/thumbnails/' . pathinfo($tutorial->media_path, PATHINFO_FILENAME) . '.jpg') }}"
                                                        controls preload="none">
                                                        <source src="{{ asset('storage/' . $tutorial->media_path) }}"
                                                            type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    <i class="fas fa-play-circle play-icon"></i>
                                                </div>
                                            @else
                                                <div class="media-preview d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-file-alt text-muted fs-4"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col">
                                        <h6 class="tutorial-title mb-1">{{ $tutorial->title }}</h6>
                                        @if ($tutorial->description)
                                            <p class="description-text mb-0">
                                                {{ Str::limit($tutorial->description, 100) }}
                                            </p>
                                        @endif
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="fas fa-eye me-1"></i> {{ $tutorial->view_count }} views
                                                @if ($tutorial->last_viewed_at)
                                                    · Last viewed {{ $tutorial->last_viewed_at->diffForHumans() }}
                                                @endif
                                            </small>
                                        </div>
                                        <span
                                            class="badge status-badge bg-{{ $tutorial->media_type == 'video' ? 'primary' : ($tutorial->media_type == 'image' ? 'success' : 'secondary') }}">
                                            <i
                                                class="fas fa-{{ $tutorial->media_type == 'video' ? 'video' : ($tutorial->media_type == 'image' ? 'image' : 'file-alt') }} me-1"></i>
                                            {{ ucfirst($tutorial->media_type) }}
                                        </span>

                                    </div>

                                    <div class="col-md-auto">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="action-buttons">
                                                <a href="{{ route('tutorials.edit', $tutorial->id) }}"
                                                    class="btn btn-warning" title="Edit Tutorial">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button" class="btn btn-danger"
                                                    onclick="confirmDelete({{ $tutorial->id }})" title="Delete Tutorial">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @empty
                        <div class="list-group-item text-center py-5">
                            <i class="fas fa-inbox fs-2 text-muted mb-2 d-block"></i>
                            <p class="mb-0">No tutorials available.</p>
                            <small class="text-muted">Click the "Add Tutorial" button to create a new tutorial.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Menampilkan Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $tutorials->links() }}
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there is a success session
            @if (session('success'))
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    timer: 3000, // 3 seconds
                    showConfirmButton: true
                });
            @endif
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize video preview handlers
            document.querySelectorAll('.video-preview').forEach(preview => {
                const video = preview.querySelector('video');
                const playIcon = preview.querySelector('.play-icon');

                // Handle click on video container
                preview.addEventListener('click', function() {
                    if (video.paused) {
                        video.play();
                        playIcon.style.display = 'none';
                    } else {
                        video.pause();
                        playIcon.style.display = 'block';
                    }
                });

                // Show play icon when video ends
                video.addEventListener('ended', function() {
                    playIcon.style.display = 'block';
                });

                // Show play icon when video is paused
                video.addEventListener('pause', function() {
                    playIcon.style.display = 'block';
                });

                // Hide play icon when video starts playing
                video.addEventListener('play', function() {
                    playIcon.style.display = 'none';
                });
            });

            // Handle status toggle
            document.querySelectorAll('.toggle-status').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const tutorialId = this.dataset.id;
                    const isActive = this.checked;

                    fetch(`/tutorials/${tutorialId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            is_active: isActive
                        })
                    }).then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    }).catch(error => {
                        console.error('Error:', error);
                        this.checked = !isActive;
                        alert('Failed to update status. Please try again.');
                    });
                });
            });
        });
    </script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak akan ditampilkan lagi!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('tutorials.destroy', '') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire("Dihapus!", response.message, "success").then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Error!", response.message, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error!", "Terjadi kesalahan sistem.", "error");
                        }
                    });
                }
            });
        }
    </script>
@endsection
