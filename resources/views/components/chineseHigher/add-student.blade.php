@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 ">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/chineseHigher')}}">Chinese Higher</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Student</li>
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
                        <form method="POST" action={{route('actionSuperAddStudentChineseHigher')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminAddStudentChineseHigher')}}>
                    @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Add Student Chinese Higher</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="subject_id">Student</label>
                                        <select name="subject_id" id="subject_id" class="form-control">
                                            @foreach ($subject as $su)
                                                <option value="{{ $su->id }}" selected>{{ $su->name_subject }}</option>
                                            @endforeach
                                        </select>

                                       @if($errors->any())
                                          <p style="color: red">{{$errors->first('subject_id')}}</p>
                                       @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="student_id">Select Student</label>
                                        <select name="student_id[]" id="student_id" class="js-select2 form-control" multiple="multiple>
                                            <option value="" >-- SELECTED STUDENT --</option>
                                            @foreach ($data as $dt)
                                                <option value="{{ $dt->id }}">{{ $dt->name }} ({{ $dt->grade_name }} - {{ $dt->grade_class }})</option>
                                            @endforeach
                                        </select>

                                       @if($errors->any())
                                          <p style="color: red">{{$errors->first('student_id')}}</p>
                                       @endif
                                    </div>
                                </div>
                              
                                <div class="row d-flex justify-content-center">
                                <input role="button" type="submit" class="btn btn-success center col-12 m-3">
                                </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection
