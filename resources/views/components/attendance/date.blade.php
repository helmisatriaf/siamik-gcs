@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <!-- START TABEL -->
   <div class="card card-dark mt-5">
      <div class="card-header"> 
         <h3 class="card-title">{{ $data['grade']->name . ' - ' . $data['grade']->class }} </h3>
         <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
               <i class="fas fa-minus"></i>
            </button>
         </div>
      </div>

      <div class="card-body p-0">
         <table class="table table-striped projects">
            <thead>
               <tr>
                  <th style="width: 10%">
                     #
                  </th>
                  <th style="width: 25%">
                     Date
                  </th>
                  <th>
                     Action
                  </th>
               </tr>
            </thead>
            <tbody>
               @foreach ($data['date'] as $el)
                  <tr id="{{ 'index_grade_' . $el->id }}">
                     <td>
                        {{ $loop->index + 1 }}
                     </td>
                     <td>
                        <a>
                        {{ \Carbon\Carbon::parse($el->date)->translatedFormat('l, j F Y') }}
                        </a>
                     </td>
                     <td>
                        <a class="btn btn-primary btn-sm"
                           href="{{url('/' . session('role') . '/dashboard/attendance/edit/detail') . '/' . $el->date . '/' . $el->grade_id . '/' . $data['teacher'] . '/' . $data['semester']}}">
                           <i class="fas fa-eye"></i>
                           View
                        </a>
                     </td>
                  </tr>
               @endforeach   
            </tbody>
         </table>
      </div>
   </div>
   <!-- END TABEL -->
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_attendance')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully upload attendance in the database.',
         });
      </script>
   @endif

   @if(session('after_update_attendance')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated attendance in the database.',
      });
      </script>
   @endif
@endsection
