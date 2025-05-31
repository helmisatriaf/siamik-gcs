@extends('layouts.admin.master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container-fluid">
    <a type="button" href="users/register-user" id="#" class="btn btn-success btn mb-2">
        <i class="fa-solid fa-user-plus me-1"></i>
        </i>
        Add user
    </a>

    @php
        $groupedUser = $data->groupBy('role.name');
    @endphp
    
    <div class="card shadow-sm border-0 rounded">
        <div class="card-body p-0">
            @forelse ($groupedUser as $roleName => $users)
                <div class="p-3 bg-light rounded-top">
                    <h5 class="fw-bold text-primary mb-0">
                        <i class="fas fa-user-tag "></i>{{ $roleName }}
                    </h5>
                    <small class="text-muted">{{ $users->count() }} Users</small>
                </div>
    
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th style="width: 15%;">User</th>
                                <th style="width: 35%;">Name</th>
                                <th style="width: 50%;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users->sortBy('role_id') as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-secondary me-2 fa-lg"></i>
                                            <span class="fw-medium">{{ $user->username }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- @if ($user->role_id == 3)
                                                <span class="fw-medium">{{ strtolower(ucwords($user->teacher->name ?? $user->teacher->name)) }}</span>
                                            @elseif ($user->role_id == 4)
                                                <span class="fw-medium">{{ strtolower(ucwords($user->student->name ?? $user->student->name)) }} ({{ $user->student->full_grade ?? '-' }})</span>
                                            @elseif($user->role_id == 5)
                                                <span class="fw-medium">{{ strtolower(ucwords($user->relationship->name ?? $user->relationship->name)) }}</span>
                                            @else --}}
                                            <span class="fw-medium">{{ $user->username }}</span>
                                            {{-- @endif --}}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="" class="btn btn-outline-primary btn-sm change-password-btn" 
                                            data-id="{{ $user->id }}" 
                                            data-name="{{ $user->username }}" 
                                            data-toggle="modal" 
                                            data-target="#change-password-user">
                                            <i class="fas fa-key"></i>
                                        </a>
    
                                        <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm delete-user" 
                                            data-id="{{ $user->id }}" 
                                            data-name="{{ $user->username }}"
                                            id="delete-user">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="text-center p-4">
                    <i class="fas fa-user-slash text-danger fa-2x"></i>
                    <p class="mt-2 mb-0 text-muted">No users found.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    
</div>

<div class="d-flex justify-content-center mt-3">
    {{$data->links()}}
</div>

<!-- Modal Change Password -->
<div class="modal fade" id="change-password-user"
    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="change-password-user-form" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="cspassword">Password</label>
                        <input name="password" type="password" class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function () {
        $(".change-password-btn").click(function () {
            var userId = $(this).data("id");
            var username = $(this).data("name");

            var actionUrl = "{{ route('user.editPassword', ':id') }}".replace(':id', userId);
            
            // Update form action URL dengan ID user yang dipilih
            $("#change-password-user-form").attr("action", actionUrl);

            // Update modal title dengan username yang dipilih
            $("#modal-title").text("Change Password for " + username);
        });
    });
</script>

@include('components.super.delete-user')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('password.success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfuly',
            text: 'Success update password',
        });
    </script>
@endif


@if(session('register.success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Success add users',
        });
    </script>
@endif


@if(session('error.type.password'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Make sure your input password is the same !!!',
        });
    </script>
@endif

@if(session('error.password'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'The password must be at least 5 !!!',
        });
    </script>
@endif

@endsection
