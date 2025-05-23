@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 ">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/eca')}}">ECA</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
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
                        <form method="POST" action={{route('actionSuperCreateEca')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminCreateEca')}}>
                    @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create ECA</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       <label for="class">ECA<span style="color: red"> *</span></label>
                                       <input name="name" type="text" class="form-control" id="name"
                                          placeholder="Enter Name ECA" value="{{old('name')}}" autocomplete="off" required>
                                       @if($errors->any())
                                          <p style="color: red">{{$errors->first('name')}}</p>
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
