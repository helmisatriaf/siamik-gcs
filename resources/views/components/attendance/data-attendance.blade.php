@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <!-- START TABEL -->
   @if (sizeof($data['gradeTeacher']) != 0)
      @foreach ($data['gradeTeacher'] as $dgt)
         <div class="card card-dark mt-5">
               <div class="card-header"> 
                  <h3 class="card-title">{{ $dgt->name . ' - ' . $dgt->class }}     (Total {{ $dgt->countStudent }} student) </h3>
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
                                 Subject
                              </th>
                              <th>
                                 Action
                              </th>
                           </tr>
                     </thead>
                     <tbody>
                           @if (sizeof($data['subjectTeacher']) != 0)
                              @foreach ($data['subjectTeacher'] as $el)
                                 @if($dgt->id == $el->grade_id)
                                    <tr id="{{ 'index_grade_' . $el->id }}">
                                          <td>
                                             {{ $loop->index + 1 }}
                                          </td>
                                          <td>
                                             <a>
                                                {{ $el->name_subject }}
                                             </a>
                                          </td>
                                          <td>
                                             <a class="btn btn-primary btn-sm"
                                                href="{{url('/' . session('role') . '/dashboard/attendance') . '/' . session('id_user') . '/' . $dgt->id . '/' . $el->id}}">
                                                <i class="fas fa-folder">
                                                </i>
                                                Attend
                                             </a>
                                             <a class="btn btn-primary btn-sm"
                                                href="{{url('/' . session('role') . '/dashboard/attendance/view/') . '/' . session('id_user') . '/' . $dgt->id . '/' . $el->id}}">
                                                <i class="fas fa-folder">
                                                </i>
                                                View
                                             </a>
                                          </td>
                                    </tr>
                                 @endif
                              @endforeach
                           @else
                              <tr>
                                 <td colspan="7" class="text-center">No student in this grade!!!</td>
                              </tr>
                           @endif
                     </tbody>
                  </table>
               </div>
         </div>
      @endforeach
      <!-- END TABLE -->
   @else
      <p class="text-center">You don't have data grade</p>
   @endif
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

   @if(session('data_is_empty')) 
      <script> 
         Swal.fire({
            icon: 'failed',
            title: 'Oops..',
            text: 'Data Attendance is Empty !!!',
         });
      </script>
   @endif


@endsection
