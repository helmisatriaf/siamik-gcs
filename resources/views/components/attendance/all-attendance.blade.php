@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    {{-- <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Attendances</li>
                </ol>
            </nav>
        </div> --}}
    </div>

    <div class="card card-light">
        <div class="card-header">
            <h3 class="card-title text-bold">Attendance</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($data as $el)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon bg-primary">
                                {{ $el->grade_name }} - {{ $el->grade_class }}
                                </div>
                            </div>
                            {{-- <p class="flex-grow-1">
                                {{$el->teacher_class}} |
                                Class Teacher <br>
                                {{$el->active_student_count}} Student
                            </p> --}}
                            {{-- <div class="flex-wrap p-0"> --}}
                                {{-- <div class="col-12 py-1 p-0">
                                    @if (session('role') == 'superadmin')
                                        <a class="btn btn-light btn-sm text-sm w-80 rounded"
                                            href="{{ route('super.attendance.detail', ['id' => session('id_user'), 'gradeId' => $el->id]) }}">
                                            <i class="fas fa-eye"> </i>
                                            View
                                        </a>
                                    @elseif (session('role') == 'admin')
                                        <a class="btn btn-light btn-sm text-sm w-80 rounded"
                                            href="{{ route('attendance.detail', ['id' => session('id_user'), 'gradeId' => $el->id]) }}">
                                            <i class="fas fa-eye"> </i>
                                            View
                                        </a>
                                    @endif
                                </div> --}}
                                {{-- <div class="col-8 py-1">
                                    <a class="btn btn-warning btn-sm text-sm w-100 rounded"
                                        href="{{url('/' . session('role') . '/dashboard/attendance/all') . '/' . session('id_user') . '/' . $el->id}}">
                                        <i class="fas fa-paper-plane">
                                        </i>
                                        Attend
                                    </a>
                                </div> --}}
                            {{-- </div>    --}}
                            
                            
                            <a 
                                @if (session('role') == 'superadmin')
                                href="{{ route('super.attendance.detail', ['id' => session('id_user'), 'gradeId' => $el->id]) }}"
                                @elseif (session('role') == 'admin')
                                href="{{ route('attendance.detail', ['id' => session('id_user'), 'gradeId' => $el->id]) }}"
                                @endif
                                class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                            
                                <!-- Bagian Utama -->
                                <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                    <!-- Ikon -->
                                    <div>
                                        <img loading="lazy" src="{{ asset('images/greta-face.png') }}" 
                                            alt="avatar" class="profileImage img-fluid" 
                                            style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                                    </div>

                                    <!-- Nama Subject -->
                                    <div class="inner mt-2">
                                        <p class="mb-0 text-lg fw-bold text-center">View</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('data_is_empty')) 
      <script> 
         setTimeout(() => {
            Swal.fire({
               icon: 'error',
               title: 'Oops..',
               text: 'Data Attendance is Empty !!!',
            });
         }, 500);
      </script>
   @endif

@endsection
