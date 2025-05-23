@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Data Reservasi Buku</li>
                </ol>
            </nav>
        </div>
    </div>
    </a>

    <!-- Separator -->
    <hr class="my-4">

    <!-- Data Table Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Data Reservasi Buku</h3>
        </div>
        <div class="card-body">
            <!-- Filter dan Search -->
            <form action="" method="GET" class="mb-4" enctype="multipart/form-data">
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
                            <label>Students</label>
                            <select name="filter_student" class="form-control">
                                @foreach ($students as $student)
                                <option value="{{$student->id}}">{{ucwords(strtolower($student->name))}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Pencarian</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari surat..."
                                    value="{{ request('search.') }}">
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
                        <a href="{{ route('reserve.book') }}" class="btn btn-secondary">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tanggal Ambil</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $res)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>
                                    {{ ucwords(strtolower($res->user->student['name'])) }}
                                </td>                                
                                <td>
                                    <span class="hover:cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Tersedia: {{ $res->book->available }}">
                                        <a href="data/library?search={{$res->book['title']}}">
                                            {{ $res->book['title'] }}
                                        </a>
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($res->reserve_date)->format('l, d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($res->return_date)->format('l, d F Y') }}</td>
                                <td>
                                    @switch($res->status)
                                        @case(0)
                                            <span class="badge badge-warning">
                                                Buku belum diambil
                                            </span>
                                            @break
                                        @case(1)
                                            <span class="badge badge-danger">
                                                Buku dalam masa peminjaman
                                            </span>
                                            @break
                                        @case(2)
                                            <span class="badge badge-success">
                                                Buku sudah dikembalikan
                                            </span>
                                            @break
                                        @break
                                        @default
                                    @endswitch
                                </td>
                                <td>
                                    @switch($res->status)
                                        @case(0)
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-primary btn-done" data-toggle="modal"
                                                    id="donePick"
                                                    data-id="{{ $res->id }}"
                                                    >
                                                    <i class="fas fa-hand"></i> Pick
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-danger btn-done" data-toggle="modal"
                                                    id="cancel"
                                                    data-id="{{ $res->id }}"
                                                    >
                                                    <i class="fas fa-cancel"></i> Cancel
                                                </button>
                                            </div>
                                        @break
                                        @case(1)
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
                                                    id="returnBook"
                                                    data-id="{{ $res->id }}"> 
                                                    <i class="fa fa-recycle"></i> Return
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                    id="remind"
                                                    data-id="{{ $res->id }}"> 
                                                    <i class="fa fa-info"></i> Remind
                                                </button>
                                            </div>
                                        @break
                                        @case(2)
                                            
                                        @break
                                        @default
                                            
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Empty Book Data</td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('click', '#donePick', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: "Buku sudah diambil oleh murid ?",
            text: "Pilih tombol iya untuk melanjutkan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Iya"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('done.pick.book', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status peminjaman buku sudah diperbarui',
                            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
                            showConfirmButton: false // Sembunyikan tombol "OK",
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

    $(document).on('click', '#returnBook', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: "Buku sudah dikembalikan ?",
            text: "Klik iya untuk melanjutkan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Iya"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('done.return.book', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        exam_id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Buku sudah dikembalikan oleh peminjam',
                            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
                            showConfirmButton: false // Sembunyikan tombol "OK",
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

    $(document).on('click', '#remind', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: "Ingatkan peminjam untuk mengembalikan buku ?",
            text: "Klik iya untuk melanjutkan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Iya"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('remind.book', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        exam_id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pengingat sudah terkirim ke peminjam',
                            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
                            showConfirmButton: false // Sembunyikan tombol "OK",
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

    $(document).on('click', '#cancel', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: "Batalkan peminjaman ?",
            text: "Klik iya untuk melanjutkan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Iya"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('cancel.book', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: {
                        exam_id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Peminjaman buku sudah dibatalkan',
                            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
                            showConfirmButton: false // Sembunyikan tombol "OK",
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

    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });

</script>

@endsection
