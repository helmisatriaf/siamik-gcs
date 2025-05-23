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

    .clicked {
        font-color: yellow;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="inner">
                <a class="small-box bg-light d-flex align-items-center justify-content-center text-center"
                    style="min-height: 110px;"
                    href="/create-chat-bot">
                    <i class="fas fa-plus fa-2x mr-2"></i>
                    <span>Create Chat Bot</span>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill">
                <div class="card-header text-muted border-bottom-0">
                    Admin SIAMIK
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-9">
                        <h2 class="lead"><b>{{$admin->name}}</b></h2>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="text-md"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: Jl. Raya Darmo Permai III No.8, Pradahkalikendal, Kec. Dukuhpakis, Surabaya, Jawa Timur 60226</li>
                            <li class="text-md mt-2"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone: {{$admin->phone}}</li>
                        </ul>
                        </div>
                        <div class="col-3 text-center">
                        <img loading="lazy" src="{{asset('images/admin.png')}}" alt="user-avatar" class="img-circle img-fluid" style="width:50px;height:50px;" loading="lazy">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <a href="#" class="btn btn-sm bg-teal"
                        data-toggle="modal" data-target="#changeNumber">
                        <i class="fas fa-lg fa-phone"></i> Change Number
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        // Mengelompokkan berdasarkan nama kategori halaman
        $groupedTutorials = $tutorials->groupBy('page.name');
    @endphp

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="card-title">
                <h3>Data Chatbot</h3>
            </div>
            <div class="card-tools">
                <button type="button" class="btn btn-light btn-sm" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse ($groupedTutorials as $pageName => $pageTutorials)
                    <div class="list-group-item bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-computer mr-2"></i>{{ $pageName ?: 'Uncategorized' }}
                                </h5>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary">
                                    {{ $pageTutorials->count() }} Topics
                                </span>
                            </div>
                        </div>
                    </div>

                    @foreach ($pageTutorials->sortBy('order') as $index => $tutorial)
                        <div class="list-group-item border-0 border-bottom">
                            <div class="row align-items-center g-3">
                                <div class="col-1">
                                    <span class="badge bg-secondary"># {{ $index+1 }}</span>
                                </div>

                                <div class="col-2">
                                    <h6 class="tutorial-title mb-1">{!! $tutorial->title !!}</h6>
                                </div>
                                
                                <div class="col-8">
                                    @if ($tutorial->description)
                                        <p class="description-text mb-0">
                                            {!! $tutorial->description !!}
                                        </p>
                                    @endif
                                </div>

                                <div class="col-md-auto">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="action-buttons">
                                            <a href="{{ route('edit.chat.bot', $tutorial->id) }}"
                                                class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmDelete({{ $tutorial->id }})" title="Delete">
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


    <div class="row">
        @foreach ($chat as $history)
            <div class="col-4">
                <a href="{{route('cc.detail', ['id' => $history->id])}}">
                    <div class="small-box bg-light p-2 gap-2 zoom-hover position-relative" style="min-height: 50px;">
                        <!-- User Avatar -->
                        <div class="row">
                            <div class="col-2">
                                @if ($history->profil !== null)
                                    <img class="img-circle" src="{{ asset('storage/file/profile/'.$history->profil) }}" alt="User Avatar" style="width: 45px; height: 45px;">
                                @else
                                    <img class="img-circle" src="{{ asset('images/admin.png') }}" alt="User Avatar" style="width: 45px; height: 45px;">
                                @endif
                            </div>
                            <div class="col-10 flex-column">
                                <strong>{{$history->name}}</strong>                          
                                <div>
                                    <i class="fas fa-comments"></i>
                                    <span class="badge badge-warning" id="notif-message">{{$history->history_count}}</span>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </a> 
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="changeNumber" tabindex="-1" role="dialog" aria-labelledby="changeNumberLabel"
aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    <i class="fas fa-lg fa-phone mr-2"></i>Change Phone Admin
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body p-4">

                <form action="{{route('change.number.phone')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">+62</span>
                        <input type="phone" name="phone" class="form-control" placeholder="contoh : 89********" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtnUpdate">
                            <i class="fas fa-save mr-2"></i>Update Number
                        </button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                timer: 2000, // 3 seconds
                showConfirmButton: true
            });
        @endif
    });
</script>

@endsection