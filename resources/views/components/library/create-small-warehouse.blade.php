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
        <i class="fa-solid fa-plus"></i>
        </i>   
        Add Data
    </a>

    <!-- Separator -->
    <hr class="my-4">

    <!-- Data Table Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Data Gudang Kecil</h3>
        </div>
        <div class="card-body">
            <!-- Filter dan Search -->
            <form action="{{ route('library.small.warehouse') }}" method="GET" class="mb-4" enctype="multipart/form-data">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="mt-3"></label>
                            <div class="input-group mt-2">
                                <button type="submit" class="btn btn-primary mr-1">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('library.small.warehouse') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="">
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Penyimpanan</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Rack</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Nama Buku</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Jumlah</th>
                            <th colspan="6" class="text-center" style="vertical-align : middle;text-align:center;">Keterangan</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Action</th>
                        </tr>
                        <tr>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Kota</th>
                            <th>Informasi</th>
                            <th>ISBN</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $key => $letter)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $letter->place }}</td>
                                <td>{{ $letter->rack }}</td>
                                <td>{{ $letter->name }}</td>
                                <td>{{ $letter->total }}</td>
                                <td>{{ $letter->publisher }}</td>
                                <td>{{ $letter->year }}</td>
                                <td>{{ $letter->city }}</td>
                                <td>{{ $letter->information }}</td>
                                <td>{{ $letter->isbn }}</td>
                                <td>
                                    <img src="{{ asset('storage/'.$letter->cover_image) }}" alt="" style="width: 64px; height: 96px;">
                                </td>
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
                                            <i class="fa fa-pen"></i> Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Empty Data</td>
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
            <div class="modal-body">
                <div>
                    <form method="POST" action="{{route('store.small.warehouse')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-dark">
                            <div class="card-body" style="position: relative; max-height: 500px; overflow-y: auto;">
                                <table class="table table-striped table-bordered">
                                    <thead class="bg-dark" style="position: sticky; top: 0; z-index: 100;">
                                        <th>Place</th>
                                        <th>Rack</th>
                                        <th>Nama Buku</th>
                                        <th>Jumlah</th>
                                        <th>Penerbit</th>
                                        <th>Tahun</th>
                                        <th>Kota</th>
                                        <th>Informasi</th>
                                        <th>ISBN</th>
                                        <th>Foto Cover</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="scheduleTableBody">
                                        <tr>
                                            <td>
                                                <select name="place[]" class="form-control">
                                                    @foreach ($places as $place)
                                                    <option value="{{$place}}">{{$place}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input name="rack[]" type="number" class="form-control" id="rack">
                                            </td>
                                            <td>
                                                <input name="name[]" type="text" class="form-control" id="name">
                                            </td>
                                            <td>
                                                <input name="total[]" type="text" class="form-control" id="total" required>
                                            </td>
                                            <td>
                                                <input name="publisher[]" type="text" class="form-control" id="publisher">
                                            </td>
                                            <td>
                                                <input name="year[]" type="number" class="form-control" id="year">
                                            </td>
                                            <td>
                                                <input name="city[]" type="text" class="form-control" id="city">
                                            </td>
                                            <td>
                                                <textarea name="information[]" class="form-control" id="information" cols="10" rows="1"></textarea>
                                            </td>
                                            <td>
                                                <input name="isbn[]" type="text" class="form-control" id="isbn">
                                            </td>
                                            <td>
                                                <input name="cover_image[]" type="file" class="form-control" id="cover_image" accept=".png, .jpg, .jpeg">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                <form method="POST" action="{{ route('update.small.warehouse') }}" enctype="multipart/form-data" class="row">
                    @csrf
                    <div class="col-6">
                        <div class="form-group d-none">
                            <input name="id" type="number" class="form-control" id="id-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="place">Penyimpanan</label>
                            <select name="place" id="place-update" class="form-control" required>
                                @foreach ($places as $place)
                                <option value="{{ $place }}">{{ $place }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="rack">Rack</label>
                            <input name="rack" type="number" class="form-control" id="rack-update">
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Nama Buku/Barang</label>
                            <input name="name" type="text" class="form-control" id="name-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="total">Jumlah</label>
                            <input name="total" type="text" class="form-control" id="total-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="publisher">Publisher</label>
                            <input name="publisher" type="text" class="form-control" id="publisher-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="year">Year</label>
                            <input name="year" type="number" class="form-control" id="year-update" required>
                        </div>
    
                        <div class="form-group">
                            <label for="city">City</label>
                            <input name="city" type="text" class="form-control" id="city-update" required>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="isbn">ISBN</label>
                            <input name="isbn" type="text" class="form-control" id="isbn-update" required>
                        </div>

                        <div class="form-group">
                            <label for="information">Information</label>
                            <textarea name="information" class="form-control" id="information-update" required></textarea>
                        </div>

                        <div class="form-group">
                            <img src="" alt="" style="width: 64px; height: 96px; mb-2" id="show-image">
                            <input name="image" type="file" class="form-control" id="image-update">
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center col-12">
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

    $(document).ready(function() {
        // Function to add a new row
        function addRow() {
            var newRow = `<tr>
                <td>
                    <select name="place[]" class="form-control">
                        @foreach ($places as $place)
                        <option value="{{$place}}">{{$place}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input name="rack[]" type="text" class="form-control">
                </td>
                <td>
                    <input name="name[]" type="text" class="form-control">
                </td>
                <td>
                    <input name="total[]" type="text" class="form-control" required>
                </td>
                <td>
                    <input name="publisher[]" type="text" class="form-control">
                </td>
                <td>
                    <input name="year[]" type="number" class="form-control">
                </td>
                <td>
                    <input name="city[]" type="text" class="form-control">
                </td>
                <td>
                    <textarea name="information[]" class="form-control" cols="10" rows="1"></textarea>
                </td>
                <td>
                    <input name="isbn[]" type="text" class="form-control">
                </td>
                <td>
                    <input name="cover_image[]" type="file" class="form-control" accept=".png, .jpg, .jpeg">
                </td>
                <td>
                    <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                </td>
            </tr>`;
            $('#scheduleTableBody').append(newRow);

            updateHapusButtons();
        }

        // Function to update the visibility of the "Hapus" buttons
        function updateHapusButtons() {
            const rows = $('#scheduleTableBody tr');

            rows.each(function(index, row) {
                var tambahButton = $(row).find('.btn-tambah');
                var hapusButton = $(row).find('.btn-hapus');

                if (rows.length === 1) {
                    // Jika hanya ada satu baris, hanya tampilkan tombol "Tambah"
                    tambahButton.removeClass('d-none');
                    hapusButton.addClass('d-none');
                } else {
                    // Baris terakhir tampilkan tombol "Tambah" dan "Hapus"
                    if (index === rows.length - 1) {
                        tambahButton.removeClass('d-none');
                        hapusButton.removeClass('d-none');
                    } else {
                        // Baris lainnya hanya tampilkan tombol "Hapus"
                        tambahButton.addClass('d-none');
                        hapusButton.removeClass('d-none');
                    }
                }
            });
        }

        // Event listener for the "Tambah" button
        $('#scheduleTableBody').on('click', '.btn-tambah', function() {
            addRow();
        });

        // Event listener for the "Hapus" button
        $('#scheduleTableBody').on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();
            updateHapusButtons();
        });

        // Initial call to update the visibility of the "Hapus" and "Tambah" buttons
        updateHapusButtons();
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
                    url: "{{ route('delete.small.warehouse', ':id') }}".replace(':id', id),
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
            url: "/data/small-warehouse/edit/" + id, // Panggil route Laravel
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.data) {
                    $("#id-update").val(response.data.id);
                    $("#place-update").val(response.data.place);
                    $("#rack-update").val(response.data.rack);
                    $("#norack-update").val(response.data.no);
                    $("#name-update").val(response.data.name);
                    $("#total-update").val(response.data.total);
                    $("#publisher-update").val(response.data.publisher);
                    $("#year-update").val(response.data.year);
                    $("#city-update").val(response.data.city);
                    $("#isbn-update").val(response.data.isbn);
                    $("#information-update").val(response.data.information);
                    let imagePath = `{{asset('storage/${response.data.cover_image}')}}`;
                    $("#show-image").attr("src", imagePath);

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
