@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades')}}">Grade</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/edit/' . $data['grade_id'])}}">Edit {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/manageSubject/' . $data['grade_id'])}}">Manage Subject & Teacher {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit {{ $data['subject_name'] }} {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</li>
                </ol>
            </nav>
            </div>
        </div>

        <div class="row d-flex justify-content-center mt-3">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperUpdateGradeSubjectTeacher', $data->teacher_subject_id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateGradeSubjectTeacher', $data->teacher_subject_id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit Subject Teacher {{$data->teacher_subject_id}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <input type="text" value="{{ $data->grade_id }}" class="d-none" name="grade_id" id="grade_id">
                                        <input type="text" value="{{ $data->subject_id }}" class="d-none" name="before_subject_id" id="before_subject_id">
                                        <label for="name">Subject<span style="color: red"> *</span></label>
                                        <select name="subject" id="subject" class="form-control">
                                            <option value="{{ $data->subject_id }}" selected>{{ $data->subject_name }}</option>
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('subject')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label for="name">Teacher<span style="color: red"> *</span></label>
                                        <select name="teacher" id="teacher" class="form-control">
                                            <option value="{{ $data->teacher_id }}" selected>{{ $data->teacher_name }}</option>
                                            @foreach ($teacher as $teacher)
                                                <option value="{{$teacher->id}}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('teacher')}}</p>
                                        @endif
                                    </div>

                                 </div>
                                </div>
                                
                                <div class="row d-flex justify-content-center">
                                    <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>


@if(session('after_update_subject_teacher')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the subject teacher in the database.',
        });
    </script>
@endif

@endsection