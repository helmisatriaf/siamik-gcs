@extends('layouts.admin.master')
@section('content')

<div class="container-fluid">
    <div class="row d-flex justify-content-center">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div>
                @if (session('role') == 'superadmin')
                    <form method="POST" action={{route('actionSuperCreateTypeSchedule')}}>
                @elseif (session('role') == 'admin')
                    <form method="POST" action={{route('actionAdminCreateTypeSchedule')}}>
                @endif
                    @csrf
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Create type schedule</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="class">Type Schedule<span style="color: red"> *</span></label>
                                    <input name="name" type="text" class="form-control" id="name"
                                        placeholder="Enter Name Schedule" value="{{old('name')}}" autocomplete="off" required>
                                    @if($errors->any())
                                        <p style="color: red">{{$errors->first('name')}}</p>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="class">Color<span style="color: red"> *</span></label>
                                    <select name="color" id="color" class="form-control">
                                        <option value="">-- Choose Color --</option>
                                        <option value="red">Red</option>
                                        <option value="blue">Blue</option>
                                        <option value="green">Green</option>
                                        <option value="yellow">Yellow</option>
                                        <option value="orange">Orange</option>
                                        <option value="purple">Purple</option>
                                        <option value="pink">Pink</option>
                                        <option value="brown">Brown</option>
                                        <option value="gray">Gray</option>
                                        <option value="black">Black</option>
                                        <option value="white">White</option>
                                        <option value="cyan">Cyan</option>
                                        <option value="magenta">Magenta</option>
                                        <option value="lime">Lime</option>
                                        <option value="maroon">Maroon</option>
                                        <option value="navy">Navy</option>
                                        <option value="olive">Olive</option>
                                        <option value="teal">Teal</option>
                                        <option value="indigo">Indigo</option>
                                        <option value="violet">Violet</option>
                                    </select>
                                    @if($errors->any())
                                        <p style="color: red">{{$errors->first('color')}}</p>
                                    @endif
                                </div>
        
                            </div>
                            <div class="row d-flex justify-content-center">
                                <input role="button" type="submit" class="btn btn-success center col-12">
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
