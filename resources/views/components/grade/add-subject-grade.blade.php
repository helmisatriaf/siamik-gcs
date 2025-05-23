@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 ">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades')}}">Grade</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/edit/' . $data['grade']['id'])}}">Edit {{ $data['grade']['name'] }} - {{ $data['grade']['class'] }}</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/manageSubject/' . $data['grade']['id'])}}">Manage Subject & Teacher {{ $data['grade']['name'] }} - {{ $data['grade']['class'] }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Subject & Teacher</li>
                </ol>
            </nav>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <nav class="col-12 mt-1">
                <div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
                  <a id="btnSingleTeacher" class="nav-item nav-link active text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]">Single teacher</a>
                  <a id="btnMultipleTeacher" class="nav-item nav-link text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" href="{{url('/' .session('role'). '/grades/manageSubject/addSubject/multiple/' . $data['grade']['id'])}}">Multiple teacher</a>
                </div>
            </nav>

            <div class="col-md-12" id="singleTeacher" style="display: block;">
                <!-- general form elements -->
                <div>
                @if (session('role') == 'superadmin')
                    <form method="POST" action={{route('actionSuperAddSubjectGrade')}}>
                @elseif (session('role') == 'admin')
                    <form method="POST" action={{route('actionAdminAddSubjectGrade')}}>
                @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create Subject & Teacher </h3>
                            </div>

                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12 d-none">
                                        <label for="grade_id">Grade<span style="color: red">*</span></label>
                                        <select required name="grade_id" class="form-control" id="grade_id"> 
                                            <option value="{{ $data['grade']['id'] }}" selected>{{ $data['grade']['name']  }} - {{ $data['grade']['class'] }}</option>
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('grade_id')}}</p>
                                        @endif
                                    </div>
                                    <div class="col mt-2">
                                        <table class="table table-striped table-bordered">
                                        <thead>
                                            <th style="width: 40%;">Subject</th>
                                            <th style="width: 40%;">Teacher</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody id="scheduleTableBody">
                                            <tr>
                                                <td>
                                                    <select required name="subject_id[]" class="form-control js-select2" id="subject_id">
                                                        <option value="" selected>  SELECT SUBJECT </option>
                                                        @foreach($data['subject'] as $el)
                                                            <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('subject_id'))
                                                    <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select name="teacher_subject_id[]" class="form-control js-select2" id="teacher_subject_id">
                                                        <option value="" selected>  SELECT TEACHER </option>
                                                        @foreach($data['teacher'] as $el)
                                                            <option value="{{ $el->id }}">{{ $el->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('teacher_subject_id'))
                                                    <p style="color: red">{{ $errors->first('teacher_subject_id') }}</p>
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12">
                                        <input role="button" type="submit" class="btn btn-success center col-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>            
        </div>
    </div>

</section>

<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let row = 1; // Inisialisasi variabel row di luar fungsi agar bertambah tiap kali

    // Function to add a new row
    function addRow() {
        var newRow = `<tr>
            <td>
                <select required name="subject_id[]" class="form-control js-select2" id="subject_id_${row}">
                    <option value="" selected>  SELECT SUBJECT </option>
                    @foreach($data['subject'] as $el)
                        <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                    @endforeach
                </select>
                @if($errors->has('subject_id'))
                <p style="color: red">{{ $errors->first('subject_id') }}</p>
                @endif
            </td>
            <td>
                <select required name="teacher_subject_id[]" class="form-control js-select2" id="teacher_subject_id_${row}">
                    <option value="" selected>  SELECT TEACHER </option>
                    @foreach($data['teacher'] as $el)
                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('teacher_subject_id'))
                <p style="color: red">{{ $errors->first('teacher_subject_id') }}</p>
                @endif
            </td>
            <td>
                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris"><i class="fa fa-times"></i></button>
            </td>
        </tr>`;
        $('#scheduleTableBody').append(newRow);
        row++;
        
        $('.js-select2').select2({
            closeOnSelect : false,
            placeholder : "Click to select an option",
            theme: 'bootstrap4',
            allowHtml: true,
            allowClear: true,
            tags: true,
            searchInputPlaceholder: 'Search options'
        });

        updateHapusButtons();
    }

    // Function to update the visibility of the "Hapus" and "Tambah" buttons
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

</script>

@if(session('sweetalert'))
    <script>
        Swal.fire({
            title: '{{ session('sweetalert.title') }}',
            text: '{{ session('sweetalert.text') }}',
            icon: '{{ session('sweetalert.icon') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif



@endsection
