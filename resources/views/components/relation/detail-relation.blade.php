@extends('layouts.admin.master')
@section('content')
@php
if(isset($data) && $data != null) {
  $gender = ($data['dataRelationship']->relation == 'father') ? 'male' : 
            (($data['dataRelationship']->relation == 'mother') ? 'female' : '');
@endphp
<section style="background-color: #eee;">
  <div class="container py-5">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="{{url('/admin/relations')}}">Relations</a></li>
            <li class="breadcrumb-item active" aria-current="page">Relation Profile</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="{{asset('images')}}/user_{{$gender}}.png" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3">{{$data['dataRelationship']->name}}</h5>
            <p class="text-muted mb-1">{{(date("md", date("U", mktime(0, 0, 0, explode("-", $data['dataRelationship']->date_birth)[2], explode("-", $data['dataRelationship']->date_birth)[1], explode("-", $data['dataRelationship']->date_birth)[0]))) > date("md") ? ((date("Y")-explode("-", $data['dataRelationship']->date_birth)[0])-1) :(date("Y")-explode("-", $data['dataRelationship']->date_birth)[0]))}} years old</p>
            <p class="text-muted mb-4">{{$data['dataRelationship']->home_address}}</p>
            @if ($data['dataRelationship']->user_id == null)
              <div class="col-lg">
                <h1 class="badge badge-danger">Don't Have an Account</h1>
                <a href="{{url('/admin/users/register-user')}}" class="badge badge-primary">Create Account</a>
              </div>
            @else
              <div class="col-lg">
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Username</p>
                  </div>
                  <div class="col-sm-4">
                    <p class="text-muted">{{$data['dataRelationship']->username}}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0">Role</p>
                  </div>
                  <div class="col-sm-4">
                    <p class="text-muted">{{$data['roles']->name}}</p>
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Fullname</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->name}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Relation</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->relation}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Id or Passport</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->id_or_passport}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Student Relation Name</p>
              </div>
              <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['dataRelationship']->student_name}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Religion</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->religion}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Place Birth</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->place_birth}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Date Birth</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{date("d/m/Y", strtotime($data['dataRelationship']->date_birth))}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Nationality</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->nationality}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->email}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Mobilephone</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->mobilephone}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Telephone</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->telephone}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Occupation</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->occupation}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Company Name</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->company_name}}</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-4">
                <p class="mb-0">Company Address</p>
              </div>
              <div class="col-sm-8">
                <p class="text-muted mb-0">{{$data['dataRelationship']->company_address}}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@php
} else {
@endphp
<section style="background-color: #eee;">
  <div class="container py-5">
    <div class="row">
      <h1>Data Relation Empty</h1>
    </div>
  </div>
</section>
@php
}
@endphp

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_create_teacher'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Successfull',
      text: 'Successfully registered the teacher in the database !!!',
    });
  </script>
@endif

@if (session('after_update_teacher'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Successfully',
      title: 'Successfully updated the teacher in the database !!!',
    });
  </script>
@endif

@endsection
