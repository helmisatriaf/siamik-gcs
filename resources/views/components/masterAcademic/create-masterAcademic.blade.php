@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row mt-4">
          <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/masterAcademics')}}">Master Academic</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
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
                        <form method="POST" action={{route('actionSuperCreateMasterAcademic')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminCreateMasterAcademic')}}>
                    @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create master academic</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-6">
                                       <label for="class">Academic Year<span style="color: red"> *</span></label>
                                       <input name="academic_year" type="text" class="form-control" id="academic_year"
                                          placeholder="Enter Academic Year" value="{{old('academic_year')}}" autocomplete="off" required>
                                       @if($errors->any())
                                          <p style="color: red">{{$errors->first('academic_year')}}</p>
                                       @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="now_semester">Now Semester<span style="color: red"></span></label>
                                        <select name="now_semester" id="now_semester" class="form-control">
                                            <option value="">-- SELECT SEMESTER --</option>
                                            <option value="1">Semester 1</option>
                                            <option value="2">Semester 2</option>
                                        </select>
                                        @if($errors->has('now_semester'))
                                            <p style="color: red">{{ $errors->first('now_semester') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="date">Semester 1<span style="color: red"> *</span></label>
                                        <input name="semester1" type="date" class="form-control" id="semester1" required>
                                        @if($errors->has('semester1'))
                                            <p style="color: red">{{ $errors->first('semester1') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="date">End Semester 1<span style="color: red"> *</span></label>
                                        <input name="end_semester1" type="date" class="form-control" id="end_semester1" required>
                                        @if($errors->has('end_semester1'))
                                            <p style="color: red">{{ $errors->first('end_semester1') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="date">Semester 2<span style="color: red"> *</span></label>
                                        <input name="semester2" type="date" class="form-control" id="semester2" required>
                                        @if($errors->has('semester2'))
                                            <p style="color: red">{{ $errors->first('semester2') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="date">End Semester 2<span style="color: red"> *</span></label>
                                        <input name="end_semester2" type="date" class="form-control" id="end_semester2" required>
                                        @if($errors->has('end_semester2'))
                                            <p style="color: red">{{ $errors->first('end_semester2') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="date">Date Mid Report Card Semester 1<span style="color: red" class="text-xs pl-3"> *Bisa dikosongi dulu</span></label>
                                        <input name="mid_report_card1" type="date" class="form-control" id="mid_report_card1">
                                        @if($errors->has('mid_report_card1'))
                                            <p style="color: red">{{ $errors->first('mid_report_card1') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="date">Date Report Card Semester 1<span style="color: red" class="text-xs pl-3"> *Bisa dikosongi dulu</span></label>
                                        <input name="report_card1" type="date" class="form-control w-fit" id="report_card1">
                                        @if($errors->has('report_card1'))
                                            <p style="color: red">{{ $errors->first('report_card1') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="date">Date Mid Report Card Semester 2<span style="color: red" class="text-xs pl-3"> *Bisa dikosongi dulu</span></label>
                                        <input name="mid_report_card2" type="date" class="form-control" id="mid_report_card2">
                                        @if($errors->has('mid_report_card2'))
                                            <p style="color: red">{{ $errors->first('mid_report_card2') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <label for="date">Date Report Card Semester 2<span style="color: red" class="text-xs pl-3"> *Bisa dikosongi dulu</span></label>
                                        <input name="report_card2" type="date" class="form-control w-fit" id="report_card2">
                                        @if($errors->has('report_card2'))
                                            <p style="color: red">{{ $errors->first('report_card2') }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="col-12 mt-2">
                                        <input role="button" type="submit" class="btn btn-success center col-12">
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
