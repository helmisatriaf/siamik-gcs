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
                        <form method="POST" action={{route('actionSuperUpdateEca', $data->id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateEca', $data->id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit ECA</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                        <input name="subjectId" type="text" class="form-control" id="subjectId" value="{{ $data->id }}" >
                                    </div>

                                    <div class="col-md-12">
                                        <label for="name">ECA<span style="color: red"> *</span></label>
                                        <input name="name" type="text" class="form-control" id="name"
                                            placeholder="Enter Subject" value="{{old('name')? old('name') : $data->name}}" required>
                                        
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('name')}}</p>
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