@extends('layouts.admin.master')

@section('content')
<div class="container-fluid p-4">
    <div class="card mt-4">
            <div class="card-header">
                <h3>Daftar Surat</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('letter.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Kategori Surat</label>
                        <select id="category" name="category" class="form-control @error('category') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="PRT" {{ old('category') == 'PRT' ? 'selected' : '' }}>Pemberitahuan Orang Tua
                            </option>
                            <option value="IZN" {{ old('category') == 'IZN' ? 'selected' : '' }}>Surat Izin</option>
                            <option value="UND" {{ old('category') == 'UND' ? 'selected' : '' }}>Undangan</option>
                            <option value="SRT" {{ old('category') == 'SRT' ? 'selected' : '' }}>Surat Resmi</option>
                            <option value="PMB" {{ old('category') == 'PMB' ? 'selected' : '' }}>Pemberitahuan Pembayaran
                            </option>
                            <option value="SPP" {{ old('category') == 'SPP' ? 'selected' : '' }}>Surat Peringatan
                            </option>
                            <option value="SKL" {{ old('category') == 'SKL' ? 'selected' : '' }}>Surat Keterangan
                            </option>
                            <option value="BEA" {{ old('category') == 'BEA' ? 'selected' : '' }}>Surat Beasiswa</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Nomor Surat</label>
                        <div class="input-group">
                            <input type="text" id="letter_number" name="letter_number"
                                class="form-control @error('letter_number') is-invalid @enderror"
                                value="{{ old('letter_number') }}" readonly>
                            <button type="button" id="generate" class="btn btn-primary">Generate</button>
                            @error('letter_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Judul Surat</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Isi Surat</label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>

        <!-- Separator -->
        <hr class="my-4">

        <!-- Data Table Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h3>Daftar Surat</h3>
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
                                <th>Nomor Surat</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($letters as $key => $letter)
                                <tr>
                                    <td>{{ $letters->firstItem() + $key }}</td>
                                    <td>{{ $letter->letter_number }}</td>
                                    <td>
                                        @switch($letter->category)
                                            @case('PRT')
                                                <span class="badge badge-info">Pemberitahuan Orang Tua</span>
                                            @break

                                            @case('IZN')
                                                <span class="badge badge-secondary">Surat Izin</span>
                                            @break

                                            @case('UND')
                                                <span class="badge badge-primary">Undangan</span>
                                            @break

                                            @case('SRT')
                                                <span class="badge badge-dark">Surat Resmi</span>
                                            @break

                                            @case('PMB')
                                                <span class="badge badge-warning">Pemberitahuan Pembayaran</span>
                                            @break

                                            @case('SPP')
                                                <span class="badge badge-danger">Surat Peringatan</span>
                                            @break

                                            @case('SKL')
                                                <span class="badge badge-success">Surat Keterangan</span>
                                            @break

                                            @case('BEA')
                                                <span class="badge badge-light">Surat Beasiswa</span>
                                            @break

                                            @default
                                                <span class="badge badge-secondary">{{ $letter->category }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $letter->title }}</td>
                                    <td>{{ $letter->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#deleteModal{{ $letter->id }}">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </div>

                                        <!-- Modal Hapus -->
                                        <div class="modal fade" id="deleteModal{{ $letter->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus surat:
                                                        <strong>{{ $letter->title }}</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <form action="{{ route('letter.destroy', $letter->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data surat</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $letters->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Di bagian bawah halaman, sebelum tag </body> -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // SweetAlert untuk pesan sukses
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        timer: 3000,
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

                // Script untuk generate nomor surat
                $('#generate').click(function() {
                    var category = $('#category').val();
                    if (category === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Pilih kategori terlebih dahulu!'
                        });
                        return;
                    }

                    // Gunakan AJAX jika endpoint backend sudah siap
                    $.ajax({
                        url: '/admin/letter/generate-letter-number/' + category,
                        type: 'GET',
                        success: function(response) {
                            $('#letter_number').val(response.letter_number);
                        },
                        error: function() {
                            // Fallback jika AJAX gagal
                            const date = new Date();
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            const sequence = Math.floor(Date.now() / 1000) % 10000;
                            const seqFormatted = String(sequence).padStart(4, '0');
                            const letterNumber = `${category}/${seqFormatted}/${month}/${year}`;
                            $('#letter_number').val(letterNumber);
                        }
                    });
                });
            });
        </script>
    @endsection
