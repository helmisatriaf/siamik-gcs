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
                        <form method="POST" action={{route('actionSuperUpdateTypeSchedule', $data->id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateTypeSchedule', $data->id)}}>
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
                                        <label for="name">Type Schedule<span style="color: red"> *</span></label>
                                        <input name="name" type="text" class="form-control" id="name"
                                            placeholder="Enter Type Schedule" value="{{old('name')? old('name') : $data->name}}" required>
                                        
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                       <label for="class">Color<span style="color: red"> *</span></label>
                                       <select name="color" id="color" class="form-control">
                                            <option value="{{ $data['color'] }}">{{ $data['color'] }}</option>
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

@endsection