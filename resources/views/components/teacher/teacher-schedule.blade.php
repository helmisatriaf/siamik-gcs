@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

   <div class="card card-dark mt-5">
      <div class="card-header">
         <h3 class="card-title">Schedule Grade</h3>

         <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
         </div>
      </div>
      <div class="card-body">
         <table class="table table-striped projects">
               <thead>
                  <tr>
                     <th style="width: 10%">
                        #
                     </th>
                     <th style="width: 25%">
                        Grades
                     </th>
                     <th>
                        Total student
                     </th>
                     <th>
                        Total subject
                     </th>
                     <th>
                        Total schedule
                     </th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($data['gradeTeacher'] as $el)
                  <tr id={{'index_grade_' . $el->id}}>
                     <td>
                           {{ $loop->index + 1 }}
                     </td>
                     <td>
                        <a>
                              {{$el->name . ' - ' . $el->class}}
                        </a>
                     </td>
                     <td>
                        <a>
                              {{$el->active_student_count}}
                        </a>
                     </td>
                     <td>
                        <a>
                              {{$el->active_subject_count}}
                        </a>
                     </td>
                     <td>
                        <a>
                              {{$el->active_schedule_count}}
                        </a>
                     </td>
                     
                     <td class="project-actions text-right toastsDefaultSuccess">
                        <a class="btn btn-primary btn"
                           href="{{url('/' . session('role') .'/dashboard/schedules/detail') . '/' . session('id_user') . '/' . $el->id}}">
                           <i class="fas fa-folder">
                           </i>
                           View
                        </a>
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

@if(session('after_update_schedule')) 
   <script>
      Swal.fire({
         icon: 'success',
         title: 'Successfully',
         text: 'Successfully updated the schedule in the database.',
      });
   </script>
@endif

@endsection
