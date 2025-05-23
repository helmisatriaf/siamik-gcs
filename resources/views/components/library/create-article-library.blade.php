@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="/article-library">Article</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <a data-toggle="modal" data-target="#modalAddOtherSchedule" class="btn btn-primary btn">   
        <i class="fa-solid fa-plus"></i>
        </i>   
        Tambah Artikel
    </a>

    <!-- Separator -->
    <hr class="my-4">

    <!-- Data Table Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>List Artikel </h3>
        </div>
        <div class="card-body">
            <!-- Filter dan Search -->
            <form action="{{ route('letter.index') }}" method="GET" class="mb-4">
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
                            <label>Kategori</label>
                            <select name="filter_category" class="form-control">
                                <option value="">Semua Kategori</option>
                                <option value="PRT" {{ request('filter_category') == 'PRT' ? 'selected' : '' }}>
                                    Pemberitahuan Orang Tua</option>
                                <option value="IZN" {{ request('filter_category') == 'IZN' ? 'selected' : '' }}>
                                    Surat
                                    Izin</option>
                                <option value="UND" {{ request('filter_category') == 'UND' ? 'selected' : '' }}>
                                    Undangan</option>
                                <option value="SRT" {{ request('filter_category') == 'SRT' ? 'selected' : '' }}>
                                    Surat
                                    Resmi</option>
                                <option value="PMB" {{ request('filter_category') == 'PMB' ? 'selected' : '' }}>
                                    Pemberitahuan Pembayaran</option>
                                <option value="SPP" {{ request('filter_category') == 'SPP' ? 'selected' : '' }}>
                                    Surat
                                    Peringatan</option>
                                <option value="SKL" {{ request('filter_category') == 'SKL' ? 'selected' : '' }}>
                                    Surat Keterangan</option>
                                <option value="BEA" {{ request('filter_category') == 'BEA' ? 'selected' : '' }}>
                                    Surat Beasiswa</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Pencarian</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari surat..."
                                    value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('letter.index') }}" class="btn btn-secondary">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Creator</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $key => $letter)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $letter->title }}</td>
                                <td>{!! $letter->description !!}</td>
                                <td>Admin</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/article/edit/{{$letter->id}}" class="btn btn-sm btn-warning btn-edit">
                                            <i class="fa fa-pen"></i> Edit
                                        </a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            id="deleteData"
                                            data-id="{{ $letter->id }}"> 
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Artikel Belum dibuat</td>
                            </tr>
                        @endforelse 
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ADD DATA --}}
<div class="modal fade" id="modalAddOtherSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <form method="POST" action="{{route('store.article.library')}}">
                        @csrf
                        <div class="card card-dark">
                            <div class="card-body" style="position: relative; max-height: 500px; overflow-y: auto;">
                                <label for="title">Judul Artikel</label>
                                <input type="text" name="title" class="form-control mb-2" id="title" required>
                                <textarea name="description" class="w-100"></textarea>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <input role="button" type="submit" class="btn btn-success center col-12">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert untuk pesan sukses
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 1000,
                showConfirmButton: false
            });
        @endif

        // SweetAlert untuk pesan error
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: `
            <ul class="text-left">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        `,
            });
        @endif
    });

    $(document).on('click', '#deleteData', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to delete this data!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('delete.article.library', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: {
                        exam_id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully',
                            text: 'Successfully Delete Data',
                            timer: 1000,
                            showConfirmButton: false,
                        }).then(function() {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert("Error occurred!");
                    }
                });
            }
        });
    });
</script>

@endsection
