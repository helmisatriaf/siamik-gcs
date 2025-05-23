@extends('layouts.admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="/library">Library</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
    <a data-toggle="modal" data-target="#modalAddOtherSchedule" class="btn btn-primary btn">   
        <i class="fa-solid fa-calendar-plus"></i>
        </i>   
        Add Data
    </a>

    <!-- Separator -->
    <hr class="my-4">

    <!-- Data Table Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Daftar Buku Rak Perpustakaan</h3>
        </div>
        <div class="card-body">
            <!-- Filter dan Search -->
            <form action="{{ route('library.add.book') }}" method="GET" class="mb-4">
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
                    {{-- <div class="col-md-3">
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
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Pencarian</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari Buku..."
                                    value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="mt-3"></label>
                            <div class="input-group mt-2">
                                <button type="submit" class="btn btn-primary mr-1">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('library.add.book') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row mt-2">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('library.add.book') }}" class="btn btn-secondary">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                    </div>
                </div> --}}
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Rack</th>
                            <th>Code</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $key => $letter)
                            <tr>
                                <td>{{ $letter->rack }}{{$letter->rack_location}}</td>
                                <td>{{ $letter->code }}</td>
                                <td>{{ $letter->title }}</td>
                                <td>{{ $letter->author }}</td>
                                <td>{{ $letter->publisher }}</td>
                                <td>{{ $letter->year_published }}</td>
                                <td>{{ $letter->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            id="deleteData"
                                            data-id="{{ $letter->id }}"> 
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-warning btn-edit" data-toggle="modal"
                                            id="editData"
                                            data-id="{{ $letter->id }}"
                                        >
                                            <i class="fa fa-pen"></i> Detail & Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Empty Book Data</td>
                            </tr>
                        @endforelse 
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $books->links() }}
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
            <div class="modal-body" style="max-height:80vh; overflow-y: auto;">
                <form method="POST" action="{{route('store.library')}}" enctype="multipart/form-data">
                    @csrf

                    {{-- <div class="card card-dark">
                        <div class="card-body" style="position: relative; max-height: 500px; overflow-y: auto;">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-dark" style="position: sticky; top: 0; z-index: 100;">
                                    <th>Rack</th>
                                    <th>No_rack</th>
                                    <th>Code</th>
                                    <th>Kategori</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Publisher</th>
                                    <th>Year</th>
                                    <th>Cover Image</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="scheduleTableBody">
                                    <tr>
                                        <td>
                                            <input name="rack[]" type="text" class="form-control" id="rack">
                                        </td>
                                        <td>
                                            <input name="no_rack[]" type="text" class="form-control" id="norack">
                                        </td>
                                        <td>
                                            <input name="code[]" type="text" class="form-control" id="code">
                                        </td>
                                        <td>
                                            <select required name="category[]" class="form-control" id="category">
                                                <option value="">-- Kategori --</option>
                                                @foreach($categories as $el)
                                                    <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input name="title[]" type="text" class="form-control" id="title" required>
                                        </td>
                                        <td>
                                            <input name="author[]" type="text" class="form-control" id="author">
                                        </td>
                                        <td>
                                            <input name="publisher[]" type="text" class="form-control" id="publisher">
                                        </td>
                                        <td>
                                            <input name="year[]" type="number" class="form-control" id="year">
                                        </td>
                                        <td>
                                            <input name="image[]" type="file" class="form-control" id="image">
                                        </td>
                                        <td>
                                            <textarea name="description[]" class="form-control" id="description" cols="10" rows="1"></textarea>
                                        </td>
                                        <td>
                                            <input name="total[]" type="number" class="form-control" id="total">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> --}}

                    
                    <div class="form-entry" id="addBook">
                        <div class="card p-2 bg-light">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="rack">Rack</label>
                                        <input type="text" name="rack[]" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="norack">No Rack</label>
                                        <input type="text" name="no_rack[]" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="norack">Code</label>
                                        <input type="text" name="code[]" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="norack">Kategori</label>
                                        <select name="category[]" class="form-control" id="category">
                                            <option value="">-- Kategori --</option>
                                            @foreach($categories as $el)
                                                <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title[]" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="author">Author</label>
                                        <input type="text" name="author[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="publisher">Publisher</label>
                                        <input type="text" name="publisher[]" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="year">Year</label>
                                        <input type="number" name="year[]" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="image">Cover Image</label>
                                        <input type="file" name="image[]" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="total">Total</label>
                                        <input type="number" name="total[]" class="form-control">
                                    </div>
                                    <div class="form-grou mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description[]" class="form-control" cols="10" rows="1"></textarea>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div class="row d-flex justify-content-center">
                    <input role="button" type="submit" class="btn btn-success center col-12">
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UPDATE DATA --}}
<div class="modal fade" id="modalUpdateData" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reserve_user">

                </div>
                <form method="POST" action="{{ route('library.update.book') }}" class="row" enctype="multipart/form-data">
                    @csrf
                    <div class="col-6">
                        <div class="form-group d-none">
                            <input name="id" type="number" class="form-control" id="id-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="rack">Rack</label>
                            <input name="rack" type="text" class="form-control" id="rack-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="no_rack">No Rack</label>
                            <input name="no_rack" type="text" class="form-control" id="norack-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input name="code" type="text" class="form-control" id="code-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input name="title" type="text" class="form-control" id="title-update" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="author">Author</label>
                            <input name="author" type="text" class="form-control" id="author-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="publisher">Publisher</label>
                            <input name="publisher" type="text" class="form-control" id="publisher-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="year">Year</label>
                            <input name="year" type="number" class="form-control" id="year-update" required>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input name="total" type="number" class="form-control" id="total-update" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" id="description-update"></textarea>
                        </div>

                        <div class="form-group">
                            <img src="" alt="" style="width: 64px; height: 96px; mb-2" id="show-image">
                            <input name="image" type="file" class="form-control" id="image-update">
                        </div>
                    </div>

                    <div class="col-12">
                        <input role="button" type="submit" class="btn btn-success w-100" id="submit-edit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
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

    $(document).ready(function () {
        // Fungsi tambah form
        function addRow() {
            const newRow = `
                <div class="card p-2 bg-light">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="rack">Rack</label>
                                <input type="text" name="rack[]" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="norack">No Rack</label>
                                <input type="text" name="no_rack[]" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="norack">Code</label>
                                <input type="text" name="code[]" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="category">Kategori</label>
                                <select  name="category[]" class="form-control">
                                    <option value="">-- Kategori --</option>
                                @foreach($categories as $el)
                                                    <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title[]" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="author">Author</label>
                                <input type="text" name="author[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label for="publisher">Publisher</label>
                                <input type="text" name="publisher[]" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="year">Year</label>
                                <input type="number" name="year[]" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="image">Cover Image</label>
                                <input type="file" name="image[]" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="total">Total</label>
                                <input type="number" name="total[]" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Description</label>
                                <textarea name="description[]" class="form-control" cols="10" rows="1"></textarea>
                            </div>
                            <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data"><i class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1" title="Hapus Baris"><i class="fa fa-times"></i></button>
                        </div>    
                    <div>
                </div>
                `;
            $('#addBook').append(newRow);
            updateButtons();
        }

        // Fungsi update tombol
        function updateButtons() {
            const forms = $('#addBook .form-entry');

            forms.each(function (index) {
                const form = $(this);
                const btnTambah = form.find('.btn-tambah');
                const btnHapus = form.find('.btn-hapus');

                if (forms.length === 1) {
                    btnTambah.removeClass('d-none');
                    btnHapus.addClass('d-none');
                } else {
                    if (index === 0) {
                        btnTambah.addClass('d-none');
                        btnHapus.removeClass('d-none');
                    } else {
                        btnTambah.removeClass('d-none');
                        btnHapus.removeClass('d-none');
                    }
                }
            });
        }

        // Event: tambah form
        $('#addBook').on('click', '.btn-tambah', function () {
            addRow();
        });

        // Event: hapus form
        $('#addBook').on('click', '.btn-hapus', function () {
            $(this).closest('.card').remove();
            updateButtons();
        });
    });

    // Panggilan awal


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
                    url: "{{ route('delete.library.book', ':id') }}".replace(':id', id),
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

    $(document).on("click", ".btn-edit", function () {
        var id = $(this).data("id"); // Ambil ID dari tombol yang diklik
        $.ajax({
            url: "/data/library/edit/" + id, // Panggil route Laravel
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.data) {
                    $("#id-update").val(response.data.id);
                    $("#rack-update").val(response.data.rack);
                    $("#norack-update").val(response.data.rack_location);
                    $("#code-update").val(response.data.code);
                    $("#category-update").val(response.data.category_id);
                    $("#title-update").val(response.data.title);
                    $("#author-update").val(response.data.author);
                    $("#publisher-update").val(response.data.publisher);
                    $("#year-update").val(response.data.year_published);
                    $("#total-update").val(response.data.total);
                    $("#description-update").val(response.data.description);
                    let imagePath = `{{asset('storage/${response.data.cover_image}')}}`;
                    $("#show-image").attr("src", imagePath);

                    // Ambil data detail user yang meminjam
                    let reserveHTML = '';
                    // Cek apakah ada data reserve
                    if (response.data.reserve && response.data.reserve.length > 0) {
                        response.data.reserve.forEach((item, index) => {
                            let studentName = item.user?.student?.name || item.user?.username || '-';
                            let statusLabel = '';
                            switch (item.status) {
                                case 0:
                                    statusLabel = 'Menunggu pengambilan';
                                    break;
                                case 1:
                                    statusLabel = 'Sedang dalam peminjaman';
                                    break;
                                case 2:
                                    statusLabel = 'Buku sudah dikembalikan';
                                    break;
                                default:
                                    statusLabel = 'Status tidak dikenal';
                                    break;
                            }

                            reserveHTML += `
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <p class="mb-0">👤 Peminjam #${index + 1}</p>
                                        <p class="mb-0"><strong>Username:</strong> ${studentName}</p>
                                        <p class="mb-0"><strong>Status:</strong> ${statusLabel}</p>
                                        <p class="mb-0"><strong>Tanggal Pinjam:</strong> ${item.reserve_date}</p>
                                        <p class="mb-0"><strong>Batas Pinjam:</strong> ${item.return_date}</p>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        reserveHTML = `
                        <h5 class="fw-bold">Detail Peminjaman</h5>
                        <p class="text-muted">Belum ada data peminjam aktif.</p>`;
                    }


                    // Tampilkan ke dalam container
                    $("#reserve_user").html(reserveHTML);


                    // Tampilkan modal edit
                    $("#modalUpdateData").modal("show");
                } else {
                    Swal.fire("Error", "Data tidak ditemukan!", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Terjadi kesalahan saat mengambil data!", "error");
            }
        });
    });
</script>

@endsection
