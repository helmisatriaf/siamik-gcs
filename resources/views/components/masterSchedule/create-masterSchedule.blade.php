@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperCreateMasterSchedule')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminCreateMasterSchedule')}}>
                    @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create master schedule</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       <label for="class">Name<span style="color: red"> *</span></label>
                                       <input name="name" type="text" class="form-control" id="name"
                                          placeholder="Enter Name Schedule" value="{{old('name')}}" autocomplete="off" required>
                                       @if($errors->any())
                                          <p style="color: red">{{$errors->first('name')}}</p>
                                       @endif
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label for="date">Date<span style="color: red"> *</span></label>
                                        <input name="date" type="date" class="form-control" id="date" required>
                                        @if($errors->has('date'))
                                            <p style="color: red">{{ $errors->first('date') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-12 mt-2">
                                       <label for="end_date">Until<span style="color: red"></span></label>
                                       <input name="end_date" type="date" class="form-control" id="_end_date">
                                       @if($errors->has('end_date'))
                                          <p style="color: red">{{ $errors->first('end_date') }}</p>
                                       @endif
                                    </div>
                                </div>
                              
                                <div class="row d-flex justify-content-center">
                                    <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                                </div>
                           </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection
