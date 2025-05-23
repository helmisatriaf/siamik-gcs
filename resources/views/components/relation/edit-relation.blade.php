@extends('layouts.admin.master')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                <form method="POST" action="{{ route('actionUpdateRelation', $data['dataRelationship']->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Relationship</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="name">Fullname<span style="color: red">*</span></label>
                                    <input name="name" type="text" class="form-control" id="name"
                                        placeholder="Enter name" value="{{ old('name') ? old('name') : $data['dataRelationship']->name }}"
                                        >
                                    @if($errors->has('name'))
                                    <p style="color: red">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="relation">Relation<span style="color: red">*</span></label>
                                    <input name="relation" type="text" class="form-control" id="relation"
                                        placeholder="Enter relation"
                                        value="{{ old('relation') ? old('relation') : $data['dataRelationship']->relation }}"
                                        >
                                    @if($errors->has('relation'))
                                    <p style="color: red">{{ $errors->first('relation') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="id_or_passport">Id or Passport<span style="color: red">*</span></label>
                                    <input name="id_or_passport" type="text" class="form-control" id="id_or_passport"
                                        placeholder="Enter NIK/Passport"
                                        value="{{ old('id_or_passport') ? old('id_or_passport') : $data['dataRelationship']->id_or_passport }}"
                                        >
                                    @if($errors->has('id_or_passport'))
                                    <p style="color: red">{{ $errors->first('id_or_passport') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="religion">Religion<span style="color: red">*</span></label>
                                    <select name="religion" class="form-control" >
                                        @php
                                        $arrReligion = array('Islam', 'Protestant Christianity', 'Catholic Christianity', 'Hinduism', 'Buddhism', 'Confucianism', 'Others');
                                        $religion = old('religion') ? old('religion') : $data['dataRelationship']->religion;
                                        @endphp
                                        @foreach($arrReligion as $value)
                                        <option value="{{ $value }}" @if($religion == $value) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('religion'))
                                    <p style="color: red">{{ $errors->first('religion') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="place_birth">Place of Birth<span style="color: red">*</span></label>
                                    <input name="place_birth" type="text" class="form-control" id="place_birth"
                                        placeholder="Enter city"
                                        value="{{ old('place_birth') ? old('place_birth') : $data['dataRelationship']->place_birth }}"
                                        >
                                    @if($errors->has('place_birth'))
                                    <p style="color: red">{{ $errors->first('place_birth') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <label>Date of Birth<span style="color: red">*</span></label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input name="date_birth" type="text" class="form-control datetimepicker-input"
                                            placeholder="Enter date of birth"
                                            value="{{ old('date_birth') ? old('date_birth') : date('d/m/Y', strtotime($data['dataRelationship']->date_birth)) }}"
                                             />
                                        <div class="input-group-append" data-target="#reservationdate"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        @if($errors->has('date_birth'))
                                        <p style="color: red">{{ $errors->first('date_birth') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="nationality">Nationality<span style="color: red">*</span></label>
                                    <input name="nationality" type="text" class="form-control" id="nationality"
                                        placeholder="Enter nationality"
                                        value="{{ old('nationality') ? old('nationality') : $data['dataRelationship']->nationality }}"
                                        >
                                    @if($errors->has('nationality'))
                                    <p style="color: red">{{ $errors->first('nationality') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="mobilephone">Mobilephone<span style="color: red">*</span></label>
                                    <input name="mobilephone" type="text" class="form-control"
                                        id="mobilephone" placeholder="Enter mobilephone" 
                                        value="{{ old('mobilephone') ? old('mobilephone') : $data['dataRelationship']->mobilephone }}">
                                    @if($errors->has('mobilephone'))
                                    <p style="color: red">{{ $errors->first('mobilephone') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="telephone">Telephone<span style="color: red">*</span></label>
                                    <input name="telephone" type="text" class="form-control"
                                        id="telephone" 
                                        value="{{ old('telephone') ? old('telephone') : $data['dataRelationship']->telephone }}">
                                    @if($errors->has('telephone'))
                                    <p style="color: red">{{ $errors->first('telephone') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="phone">Phone<span style="color: red">*</span></label>
                                    <input name="phone" type="text" class="form-control"
                                        id="phone" 
                                        value="{{ old('phone') ? old('phone') : $data['dataRelationship']->phone }}">
                                    @if($errors->has('phone'))
                                    <p style="color: red">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Email<span style="color: red">*</span></label>
                                    <input name="email" type="email" class="form-control"
                                        id="email" placeholder="Enter father's email" 
                                        value="{{ old('email') ? old('email') : $data['dataRelationship']->email }}">
                                    @if($errors->has('email'))
                                    <p style="color: red">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="home_address">Home Address<span style="color: red">*</span></label>
                                    <textarea  name="home_address" class="form-control" id="home_address"
                                        cols="10" rows="3">{{ old('home_address') ? old('home_address') : $data['dataRelationship']->home_address }}</textarea>
                                    @if($errors->has('home_address'))
                                    <p style="color: red">{{ $errors->first('home_address') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="occupation">Occupation<span style="color: red">*</span></label>
                                    <input name="occupation" type="text" class="form-control"
                                        id="occupation" 
                                        value="{{ old('occupation') ? old('occupation') : $data['dataRelationship']->occupation }}">
                                    @if($errors->has('occupation'))
                                    <p style="color: red">{{ $errors->first('occupation') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="company_name">Company Name<span style="color: red">*</span></label>
                                    <input name="company_name" type="text" class="form-control"
                                        id="company_name" 
                                        value="{{ old('company_name') ? old('company_name') : $data['dataRelationship']->company_name }}">
                                    @if($errors->has('company_name'))
                                    <p style="color: red">{{ $errors->first('company_name') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="company_address">Company Address<span style="color: red">*</span></label>
                                    <textarea  name="company_address" class="form-control" id="company_address"
                                        cols="10" rows="3">{{ old('company_address') ? old('company_address') : $data['dataRelationship']->company_address }}</textarea>
                                    @if($errors->has('company_address'))
                                    <p style="color: red">{{ $errors->first('company_address') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body Teacher -->

                    <div class="d-flex justify-content-center my-5">
                        <button type="submit" class="col-11 btn btn-success">Submit</button>
                    </div>
                </form>

                </div>
                <!-- /.card -->

                <!-- general form elements -->
            </div>
            <!--/.col (right) -->

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
