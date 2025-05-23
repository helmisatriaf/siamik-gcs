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
                        <form method="POST" action={{route('actionSuperCreateSupplementarySubject')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminCreateSupplementarySubject')}}>
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
                                        <label for="supplementary_subject">Supplementary Subject<span style="color: red">*</span></label>
                                        <select required name="supplementary_subject[]" class="js-select2 form-control" id="supplementary_subject" multiple="multiple">
                                                <option value="" >--- SELECT SUPPLEMENTARY SUBJECT ---</option>
                                                @foreach($data as $el)
                                                    <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                @endforeach
                                        </select>
                                        @if($errors->has('supplementary_subject'))
                                                <p style="color: red">{{ $errors->first('supplementary_subject') }}</p>
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
