@extends('layouts.admin.master')
@section('content')

<div class="container">

    <form method="POST" action="{{route('actionCreateChatBot')}}">
    @csrf
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-3">
                <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/cc')}}">Chat</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-dorange">
                <div class="card-header">
                    <h3 class="card-title">Create Chat Bot</h3>
                </div>
                <div class="card-body" style="max-height: 700px; overflow-y: auto;">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width:15%">Topic</th>
                                <th style="width:40%">Title</th>
                                <th style="width:40%">Answer</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            <tr>
                                <td>
                                    <select name="page_id[]" class="form-control" id="page_id">
                                        @foreach ($topics as $topic)
                                            <option value="{{$topic->id}}">{{$topic->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <textarea name="title[]" id="froala-editor" cols="45" rows="5"></textarea>
                                </td>
                                <td>
                                    <textarea name="answer[]" id="froala-editor" cols="45" rows="5"></textarea>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input role="button" type="submit" class="btn btn-success mx-3 mb-2">
            </div>
            </form>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        // Function to add a new row
        function addRow() {
            var newRow = `<tr>
                 <td>
                    <select name="page_id[]" class="form-control" id="page_id">
                        @foreach ($topics as $topic)
                            <option value="{{$topic->id}}">{{$topic->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <textarea name="title[]" id="froala-editor" cols="45" rows="5"></textarea>
                </td>
                <td>
                    <textarea name="answer[]" id="froala-editor" cols="45" rows="5"></textarea>
                </td>
                <td>
                    <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                </td>
            </tr>`;
            $('#scheduleTableBody').append(newRow);

            new FroalaEditor('textarea#froala-editor', {
                toolbarButtons: [],
                quickInsertEnabled: false, // Menonaktifkan quick insert
                toolbarInline: false, // Pastikan toolbar tetap ada
                pastePlain: true, // Mencegah pemformatan saat paste
                pluginsEnabled: [] // Menonaktifkan semua plugin tambahan
            });
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

    new FroalaEditor('textarea#froala-editor', {
        toolbarButtons: [],
        quickInsertEnabled: false, // Menonaktifkan quick insert
        toolbarInline: false, // Pastikan toolbar tetap ada
        pastePlain: true, // Mencegah pemformatan saat paste
        pluginsEnabled: [] // Menonaktifkan semua plugin tambahan
    });
</script>

@endsection