@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                        <form method="POST" action={{route('actionCreateMonthly')}}>
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create Monthly Activity</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       <label for="class">Monthly Activity<span style="color: red"> *</span></label>
                                       <input name="name" type="text" class="form-control" id="name"
                                          placeholder="Enter Monthly Activity" value="{{old('name')}}" autocomplete="off" required>
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
