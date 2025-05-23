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
                        <form method="POST" action={{route('actionSuperCreateMinorSubject')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminCreateMinorSubject')}}>
                    @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create subject</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="minor_subject">Minor Subject<span style="color: red">*</span></label>
                                        <select required name="minor_subject[]" class="js-select2 form-control" id="minor_subject" multiple="multiple">
                                                <option value="" >--- SELECT MINOR SUBJECT ---</option>
                                                @foreach($data as $el)
                                                    <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                @endforeach
                                        </select>
                                        @if($errors->has('minor_subject'))
                                                <p style="color: red">{{ $errors->first('minor_subject') }}</p>
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
