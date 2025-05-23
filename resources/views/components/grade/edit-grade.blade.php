@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/grades')}}">Grade</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit {{ $data['name'] }} - {{ $data['class'] }}</li>
                </ol>
            </nav>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-md-12">
                <! general form elements >
                <div>
                @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperUpdateGrade', $data->id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateGrade', $data->id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit grade</h3>
                            </div>
                            <! /.card-header >
                            <! form start >
                            <div class="card-body">
                                <div class="form-group row d-none">
                                    <div class="col-md-6" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                        <input name="gradeId" type="text" class="form-control" id="gradeId" value="{{ $data->id }}" >
                                    </div>

                                    <div class="col-md-6">
                                        @php
                                          $selected = old('name') ? old('name') : $data->name;
                                       @endphp

                                        <label for="name">Grade<span style="color: red">*</span></label>
                                        <select required name="name" class="form-control" id="name">
                                            <option {{$selected === 'Preeschool' ? 'selected' : ''}}>Preeschool</option>
                                            <option {{$selected === 'Toddler' ? 'selected' : ''}}>Toddler</option>
                                            <option {{$selected === 'Nursery' ? 'selected' : ''}}>Nursery</option>
                                            <option {{$selected === 'Kindergarten' ? 'selected' : ''}}>Kindergarten</option>
                                            <option {{$selected === 'Primary' ? 'selected' : ''}}>Primary</option>
                                            <option {{$selected === 'Secondary' ? 'selected' : ''}}>Secondary</option>
                                            <option {{$selected === 'Junior College' ? 'selected' : ''}}>Junior College</option>
                                            <option {{$selected === 'IGCSE' ? 'selected' : ''}}>IGCSE</option>
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="class">Class<span style="color: red">*</span></label>
                                        <input name="class" type="text" class="form-control" id="class"
                                            placeholder="Enter class" value="{{old('class')? old('class') : $data->class}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('class')}}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- <! SELECT TEACHER > --}}
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       @php
                                          $selectedTeacher = $teacherGrade;
                                       @endphp
                                        <label for="teacher_id">Class Teacher</label>
                                        <select name="teacher_id" class="form-control js-select2" id="teacher_id">
                                            <option value=""> SELECT TEACHER CLASS </option>
                                            @if(count($teacherGrade) == 0)
                                                @foreach($teacher as $el)
                                                    <option value="{{$el->id}}" >{{$el->name}}</option>
                                                @endforeach
                                            @else
                                                @foreach ($teacher as $el)
                                                    @if (!in_array($el->id, $teacherGrade))
                                                        <option value="{{$el->id}}">{{$el->name}}</option>
                                                    @endif
                                                @endforeach

                                                @foreach ($teacherGrade as $tg)
                                                    @foreach ($teacher as $t)
                                                        @if ($t->id == $tg)
                                                            <option value="{{$t->id}}" selected>{{$t->name}}</option>
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary btn"
                                        href="{{url('/' . session('role') .'/grades/manageSubject') . '/' . $gradeId}}">Manage Subject Teacher</a>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <input role="button" type="submit" class="btn btn-success center col-12">
                                    </div>
                                </div>
                                {{-- <! END SELECT TEACHER > --}}


                                {{-- <! SELECT SUBJECT > --}}
                                {{-- <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="subject_id">Subject Class</label>
                                        <select name="subject_id[]" class="js-select2 form-control" id="subject_id" multiple="multiple">
                                            @if(count($subjectGrade) == 0)
                                                @foreach($subject as $se)
                                                    <option value="{{$se->id}}" >{{$se->name_subject}}</option>
                                                @endforeach
                                            @else

                                                @foreach ($subject as $se)
                                                    @if (!in_array($se->id, $subjectGrade))
                                                        <option value="{{$se->id}}">{{$se->name_subject}}</option>
                                                    @endif
                                                @endforeach

                                                @foreach ($subjectGrade as $sg)
                                                    @foreach ($subject as $s)
                                                        @if ($s->id == $sg)
                                                            <option value="{{$s->id}}" selected>{{$s->name_subject}}</option>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </select>

                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('subject_id')}}</p>
                                        @endif
                                    </div>
                                </div> --}}
                                {{-- <! END SUBJECT > --}}

                                <!-- <table class="table table-striped table-bordered">
                                    <thead>
                                        <th style="width: 40%;">Subject</th>
                                        <th style="width: 40%;">Teacher</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="scheduleTableBody">
                                        <tr>
                                            <td>
                                                <select required name="subject_id[]" class="form-control" id="subject_id">
                                                    <option value="" selected>  SELECT SUBJECT </option>
                                                    @foreach($subject as $el)
                                                        <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('subject_id'))
                                                <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <select required name="teacher_subject_id[]" class="form-control" id="teacher_subject_id">
                                                    <option value="" selected>  SELECT TEACHER </option>
                                                    @foreach($teacher as $el)
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
                                </table> -->
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
$(document).ready(function() {
    // Function to add a new row
    function addRow() {
        var newRow = `<tr>
            <td>
                <select required name="subject_id[]" class="form-control" id="subject_id">
                    <option value="" selected>  SELECT SUBJECT </option>
                    @foreach($subject as $el)
                        <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                    @endforeach
                </select>
                @if($errors->has('subject_id'))
                <p style="color: red">{{ $errors->first('subject_id') }}</p>
                @endif
            </td>
            <td>
                <select required name="teacher_subject_id[]" class="form-control" id="teacher_subject_id">
                    <option value="" selected>  SELECT TEACHER </option>
                    @foreach($teacher as $el)
                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('teacher_subject_id'))
                <p style="color: red">{{ $errors->first('teacher_subject_id') }}</p>
                @endif
            </td>
            <td>
                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1" title="Hapus Baris"><i class="fa fa-times"></i></button>
            </td>
        </tr>`;
        $('#scheduleTableBody').append(newRow);

        // Call the function to populate subject and teacher options for the new row
        const newSubjectSelect = $('#scheduleTableBody tr:last .subject_id');
        const newTeacherSelect = $('#scheduleTableBody tr:last .teacher_id');

        loadSubjectOptionExam($('#grade_id').val(), newSubjectSelect);
        newSubjectSelect.change(function() {
            loadTeacherOption($('#grade_id').val(), $(this).val(), newTeacherSelect);
        });

        updateHapusButtons();
    }

    // Function to update the visibility of the "Hapus" buttons
    function updateHapusButtons() {
        $('#scheduleTableBody tr').each(function(index, row) {
            var hapusButton = $(row).find('.btn-hapus');
            if (index === $('#scheduleTableBody tr').length - 1) {
                hapusButton.removeClass('d-none');
            } else {
                hapusButton.addClass('d-none');
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

    // Initial call to update the visibility of the "Hapus" buttons
    updateHapusButtons();
});
</script>

@if(session('after_update_grade')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the grade in the database.',
        });
    </script>
@endif

@endsection