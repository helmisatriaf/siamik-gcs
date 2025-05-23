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
                        <form method="POST" action={{route('actionSuperUpdateMasterSchedule', $data->id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateMasterSchedule', $data->id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit type schedule</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                        <input name="typeScheduleId" type="text" class="form-control" id="typeScheduleId" value="{{ $data->id }}" >
                                    </div>

                                    <div class="col-md-12">
                                        <label for="name">Name<span style="color: red"> *</span></label>
                                        <input name="name" type="text" class="form-control" id="name"
                                            placeholder="Enter Name" value="{{old('name')? old('name') : $data->name}}" required>
                                        
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <label for="date">Date<span style="color: red"> *</span></label>
                                        <input name="date" type="date" class="form-control" id="date" value="{{old('date')? old('date') : $data->date}}" required>
                                        @if($errors->has('date'))
                                            <p style="color: red">{{ $errors->first('date') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <label for="end_date">Until<span style="color: red"> *</span></label>
                                        <input name="end_date" type="date" class="form-control" id="_end_date" value="{{old('end_date')? old('end_date') : $data->end_date}}" required>
                                        @if($errors->has('end_date'))
                                            <p style="color: red">{{ $errors->first('end_date') }}</p>
                                        @endif
                                    </div>
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