@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   {{-- <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Grades</li>
                </ol>
            </nav>
        </div>
    </div>

   <div class="row">
      <a type="button" href="{{ url('/' . session('role') . '/grades/create') }}" class="btn btn-success btn mx-2">   
         <i class="fa-solid fa-user-plus"></i>
         Add Grade
      </a>
   </div> --}}

   <div class="card card-dark mt-2">
      <div class="card-header">
         <h3 class="card-title">Grades</h3>

         <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
         </div>
      </div>
      <div class="card-body p-0" style="overflow-x:auto">
         <table class="table table-striped projects">
               <thead>
                  <tr>
                     <th>
                        #
                     </th>
                     <th style="width: 20%">
                        Grades
                     </th>
                     <th style="width: 10%">
                        Student
                     </th>
                     <th style="width: 15%">
                        Class Teacher
                     </th>
                     <th style="width: 10%">
                        Subject
                     </th>
                     <th style="width: 10%">
                        Assessment
                     </th>
                     <th style="width: 35%">
                        Action
                     </th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($data as $el)
                  <tr id={{'index_grade_' . $el->id}}>
                     <td>{{ $loop->index + 1 }}</td>
                     <td>{{$el->name . ' - ' . $el->class}}</td>
                     <td>{{$el->active_student_count}}</td>
                     <td>
                        @foreach ($el->teacher as $ct)
                           {{$ct->name}}
                        @endforeach
                     </td>
                     <td>{{$el->active_subject_count}}</td>
                     <td>{{$el->active_exam_count}}</td>
                     
                     <td class="project-actions text-left toastsDefaultSuccess">
                        <a class="btn btn-primary btn"
                           href="{{url('/' . session('role') .'/grades') . '/' . $el->id}}">
                           <i class="fas fa-eye">
                           </i>
                           View
                        </a>
                        <a class="btn btn-warning btn"
                           href="{{url('/' . session('role') .'/grades') . '/edit/' . $el->id}}">
                           {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                           <i class="fas fa-pencil-alt">
                           </i>
                           Edit
                        </a>
                        @if (session('role') == 'superadmin')
                        {{-- <a class="btn btn-danger btn"
                           href="{{url('/' . session('role') .'/grades') . '/delete/' . $el->id}}">
                           <i class="fas fa-trash">
                           </i>
                           Hapus
                        </a> --}}
                        @endif
                     </td>
                  </tr>

                  @endforeach
               </tbody>
         </table>
      </div>
      <!-- /.card-body -->
   </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_create_grade')) 
   <script>
      Swal.fire({
         icon: 'success',
         title: 'Successfully',
         title: 'Successfully created new grade in the database.',
      });
   </script>
@endif

@if(session('after_delete_grade')) 
   <script>
      Swal.fire({
         icon: 'success',
         title: 'Successfully',
         text: 'Successfully delete grade in the database.',
      });
   </script>
@endif

@endsection
