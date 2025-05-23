@extends('layouts.admin.master')
@section('content')

<section class="content">
    
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/masterAcademics')}}">Master Academic</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-12">
                <!-- general form elements -->
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperUpdateMasterAcademic', $data->id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateMasterAcademic', $data->id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit Master Academic</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-6" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                        <input name="typeScheduleId" type="text" class="form-control" id="typeScheduleId" value="{{ $data->id }}" >
                                    </div>

                                    <div class="col-6">
                                        <label for="academic_year">Academic Year<span style="color: red"> *</span></label>
                                        <input name="academic_year" type="text" class="form-control" id="academic_year"
                                            placeholder="Enter Academic Year" value="{{old('academic_year')? old('academic_year') : $data->academic_year}}" required>
                                        
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('academic_year')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-6">
                                        <label for="now_semester">Semester Now<span style="color: red"> *</span></label>
                                        <select name="now_semester" id="now_semester" class="form-control">
                                            <option value="1" {{ $data->now_semester === 1 ? "selected" : "" }}>Semester 1</option>
                                            <option value="2" {{ $data->now_semester === 2 ? "selected" : "" }}>Semester 2</option>
                                        </select>
                                        @if($errors->has('now_semester'))
                                            <p style="color: red">{{ $errors->first('now_semester') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-6">
                                        <label for="semester1">Semester 1<span style="color: red"> *</span></label>
                                        <input name="semester1" type="date" class="form-control" id="semester1" value="{{old('semester1')? old('semester1') : $data->semester1}}" required>
                                        @if($errors->has('semester1'))
                                            <p style="color: red">{{ $errors->first('semester1') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-6">
                                        <label for="end_semester1">End Semester 1<span style="color: red"> *</span></label>
                                        <input name="end_semester1" type="date" class="form-control" id="end_semester1" value="{{old('end_semester1')? old('end_semester1') : $data->end_semester1}}" required>
                                        @if($errors->has('end_semester1'))
                                            <p style="color: red">{{ $errors->first('end_semester1') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-6">
                                        <label for="semester2">Semester 2<span style="color: red"> *</span></label>
                                        <input name="semester2" type="date" class="form-control" id="semester2" value="{{old('semester2')? old('semester2') : $data->semester2}}" required>
                                        @if($errors->has('semester2'))
                                            <p style="color: red">{{ $errors->first('semester2') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-6">
                                        <label for="end_semester2">End Semester 2<span style="color: red"> *</span></label>
                                        <input name="end_semester2" type="date" class="form-control" id="end_semester2" value="{{old('end_semester2')? old('end_semester2') : $data->end_semester2}}" required>
                                        @if($errors->has('end_semester2'))
                                            <p style="color: red">{{ $errors->first('end_semester2') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-6">
                                        <label for="midreportcard1">Date Mid Report Card Semester 1</label>
                                        <input name="mid_report_card1" type="date" class="form-control" id="midreportcard1" value="{{old('mid_report_card1')? old('mid_report_card1') : $data->mid_report_card1}}" >
                                        @if($errors->has('mid_report_card1'))
                                            <p style="color: red">{{ $errors->first('mid_report_card1') }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="col-6">
                                        <label for="reportcard1">Date Report Card Semester 1</label>
                                        <input name="report_card1" type="date" class="form-control" id="reportcard1" value="{{old('report_card1')? old('report_card1') : $data->report_card1}}" >
                                        @if($errors->has('report_card1'))
                                            <p style="color: red">{{ $errors->first('report_card1') }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="col-6">
                                        <label for="midreportcard2">Date Mid Report Card Semester 2</label>
                                        <input name="mid_report_card2" type="date" class="form-control" id="midreportcard2" value="{{old('mid_report_card2')? old('mid_report_card2') : $data->mid_report_card2}}" >
                                        @if($errors->has('mid_report_card2'))
                                            <p style="color: red">{{ $errors->first('mid_report_card2') }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="col-6">
                                        <label for="reportcard2">Date Report Card Semester 2</label>
                                        <input name="report_card2" type="date" class="form-control" id="reportcard2" value="{{old('report_card2')? old('report_card2') : $data->report_card2}}" >
                                        @if($errors->has('report_card2'))
                                            <p style="color: red">{{ $errors->first('report_card2') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-12 mt-3">
                                        <input role="button" type="submit" class="btn btn-success col-12">
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

@endsection