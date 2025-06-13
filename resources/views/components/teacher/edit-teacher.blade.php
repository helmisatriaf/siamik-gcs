@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="p-3 mb-4" style="background-color: #ffde9e;border-radius:12px;">
                    <ol class="breadcrumb mb-0"  style="background-color: #fff3c0;">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Teacher</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                    @if(session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperUpdateTeacher', $data->id)}}>
                    @elseif(session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminUpdateTeacher', $data->id)}}>
                    @elseif (session('role') == 'teacher')
                        <form method="POST" action={{route('actionUpdateSelfTeacher', $data->id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card" style="background-color: #ffde9e;border-radius:12px;">
                            <div class="card-header">
                                <h3 class="card-title text-xl">Teacher</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="name">Full name<span style="color: red">*</span></label>
                                        <input name="name" type="text" class="form-control" id="name"
                                            placeholder="Enter name" value="{{old('name')? old('name') : $data->name}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nik">NIK/Passport<span style="color: red">*</span></label>
                                        <input name="nik" type="text" class="form-control" id="nik"
                                            placeholder="Enter NIK/Passport" value="{{old('nik')? old('nik') : $data->nik}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nik')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Gender<span style="color: red">*</span></label>
                                            <select name="gender" class="form-control" required>
                                                @php
                                                $arrGender = array('Female', 'Male');
                                                $gender = old('gender')? old('gender') : $data->gender
                                                @endphp

                                                <option selected>
                                                {{ $gender }}
                                                </option>

                                                @if ($gender)

                                                @foreach($arrGender as $value)

                                                @if ($gender !== $value)
                                                <option>{{$value}}</option>
                                                @endif

                                                @endforeach
                                                @else
                                                <option>Male</option>
                                                <option>Female</option>
                                                @endif
                                            </select>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('gender')}}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="place_birth">Place of Birth<span style="color: red">*</span></label>
                                        <input name="place_birth" type="text" class="form-control" id="place_birth"
                                            placeholder="Enter city" value="{{old('place_birth') ? old('place_birth') : $data->place_birth}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('place_birth')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">

                                        <label>Date of Birth<span style="color: red">*</span></label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input name="date_birth" type="text"
                                                class="form-control datetimepicker-input" placeholder={{date("d/m/Y")}}
                                                data-target="#reservationdate" data-inputmask-alias="datetime"
                                                data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                value="{{old('date_birth') ? old('date_birth') : date('d/m/Y', strtotime($data->date_birth)) }}"
                                                required />

                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('date_birth')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="nationality">Nationality<span style="color: red">*</span></label>
                                        <input name="nationality" type="text" class="form-control" id="nationality"
                                            placeholder="Enter nationality" value="{{old('nationality') ? old('nationality') : $data->nationality}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nationality')}}</p>
                                        @endif
                                    </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Religion<span style="color: red">*</span></label>
                                        <select name="religion" class="form-control" required>

                                            @php
                                            $arrReligion = array('Islam', 'Protestant Christianity', 'Catholic
                                            Christianity', 'Hinduism', 'Buddhism', 'Confucianism', 'Others');

                                            $religion = old('religion')? old('religion') : $data->religion
                                            @endphp

                                            <option selected>
                                                    {{ $religion }}
                                            </option>

                                            @if ($religion)

                                            @foreach($arrReligion as $value)

                                            @if ($religion !== $value)
                                            <option>{{$value}}</option>
                                            @endif

                                            @endforeach
                                            @else
                                            @foreach($arrReligion as $religion)
                                            <option>{{$religion}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('religion')}}</p>
                                        @endif
                                    </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    
                                    <div class="col-md-6">
                                    <label>Last Education<span style="color: red">*</span></label>
                                    <select name="last_education" class="form-control" required>

                                            @php
                                                $last_education = old('last_education')? old('last_education') : $data->last_education;
                                                $arrLast_education = array('Senior High School', 'Vocational School', 'Bachelor`s Degree', 'Master Degree', 'Doctoral Degree ', 'Others');
                                            @endphp

                                            <option selected>
                                                {{$last_education}}
                                            </option>

                                            @foreach($arrLast_education as $value)

                                            @if ($last_education !== $value)
                                            <option>{{$value}}</option>
                                            @endif

                                            @endforeach
                                        </select>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('last_education')}}</p>
                                            @endif
                                    </div>
                                
                                    <div class="col-md-6">
                                        <label for="major">Major<span style="color: red">*</span></label>
                                        <input name="major" type="text" class="form-control" id="major"
                                            placeholder="Enter teacher major" value="{{old('major')? old('major') : $data->major}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('major')}}</p>
                                        @endif
                                    </div>
                                </div>

                                

                                <div class="form-group row">
                                    
                                    <div class="col-md-6">
                                    <label for="handphone">Mobile phone<span style="color: red">*</span></label>
                                    <input name="handphone" type="text" class="form-control"
                                    id="handphone" placeholder="Enter mobilephone" required value="{{old('handphone')? old('handphone') : $data->handphone}}">

                                    @if($errors->any())
                                                <p style="color: red">{{$errors->first('handphone')}}</p>
                                    @endif
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email">Email<span style="color: red">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input name="email" type="email" class="form-control" placeholder="Enter father's email" value="{{old('email')? old('email') : $data->email}}" required>

                                        @if($errors->any())
                                                <p style="color: red">{{$errors->first('email')}}</p>
                                        @endif
                                    </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="home_address">Home Address<span style="color: red">*</span></label>

                                        <textarea required name="home_address" class="form-control" id="" cols="10" rows="3">{{old('home_address') ? old('home_address') : $data->home_address}}</textarea>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('home_address')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="temporary_address">Temporary Address<span style="color: red">*</span></label>

                                            <textarea required name="temporary_address" class="form-control" id="" cols="10" rows="3">{{old('temporary_address') ? old('temporary_address') : $data->temporary_address}}</textarea>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('temporary_address')}}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row justify-content-center px-2">
                                    <button type="submit" class="col-12  btn btn-success">Submit</button>
                                </div>
                            </div>
                            
                        </div>
                        <!-- /.card-body Teacher -->

                    </form>

                </div>
                <!-- /.card -->

                <!-- general form elements -->
            </div>
            <!--/.col (right) -->

        </div>
        <!-- /.row -->
    </div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {
        let index = 1;

        function updateAddButtonVisibility() {
            // Sembunyikan semua tombol tambah kecuali tombol tambah pada baris terakhir
            $('.btn-tambah').hide();
            $('.btn-tambah').last().show();
        }

        function initializeSelect2() {
            $('.js-select-2-2').select2({
                closeOnSelect: false,
                placeholder: "Click to select an option",
                theme: 'bootstrap4',
                allowHtml: true,
                allowClear: true
            });
        }

        $(document).on('click', '.btn-tambah', function() {
            let row = `<div class="form-group row" id="row_${index}">
                <div class="col-md-5">
                    <label for="grade_id_${index}">Teacher Grade</label>
                    <select name="grade_id[${index}][]" class="js-select-2-2 form-control" id="grade_id_${index}" multiple="multiple">
                        <option value=""> -- SELECTED TEACHER GRADE --</option>
                        @foreach($grade as $el)
                            <option value="{{ $el->id }}">{{ $el->name }} - {{ $el->class }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="subject_id_${index}">Subject Class</label>
                    <select name="subject_id[${index}][]" class="js-select-2-2 form-control" id="subject_id_${index}" multiple="multiple">
                        <option value=""> -- SELECTED SUBJECT --</option>
                        @foreach ($subject as $se)
                            <option value="{{ $se->id }}">{{ $se->name_subject }}</option>
                        @endforeach
                    </select>
                    @if($errors->any())
                        <p style="color: red">{{ $errors->first('subject_id') }}</p>
                    @endif
                </div>
                <div class="col-md-2">
                    <label style="color:white;">Action</label>
                    <div class="form-group">
                        <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" id="add_${index}"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1" id="delete_${index}" data-teacher-id="{{ $data->id }}" data-grade-id="{{ $el->id }}" data-subject-id=""><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>`;
            $('.edit_teacher_grade_subject').append(row);
            index++;
            updateAddButtonVisibility();
            initializeSelect2(); // Reinitialize Select2 for the new elements
        });

        $(document).on('click', '.btn-hapus', function() {
            const teacherId = $(this).data('teacher-id');
            const gradeId = $(this).data('grade-id');
            const subjectId = $(this).data('subject-id');

            console.log("teacher_ID : ", teacherId);

            Swal.fire({
                title: 'Are you sure?',
                text: "Delete data teacher grade & subject!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `teachers/${teacherId}/${gradeId}/${subjectId}`,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Successfully deleted grade and subject teacher in the database !!!',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        initializeSelect2();
        updateAddButtonVisibility();
    });
</script>

<script>
    $(document).ready(function(){

        $("#teacher_grade_subject").on("change", "#teacher_grade_id_0", function() {
        loadSubjectOptions2(this.value);
        });

        $("#teacher_grade_subject").on("change", "#teacher_subject_id_0", function() {
        loadSubjectOptions2(this.value);
        });

        function loadSubjectOptions2(gradeId) {
        // Hapus semua opsi yang ada di select subject
    
        // Buat request AJAX untuk mengambil data subject berdasarkan grade
        fetch(`/get-subjects/${gradeId}`)
            .then(response => response.json())
            .then(data => {
                // Tambahkan opsi baru ke select subject
                data.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.text = subject.name_subject;
                    subjectSelect.add(option);
                });
            })
            .catch(error => console.error(error));
        }
    });
</script>

@if (session('after_delete_update_teacher'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully deleted grade & subject teacher in the database !!!',
        });
    </script>
@endif

@endsection
