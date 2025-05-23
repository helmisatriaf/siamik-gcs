@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="form-group row">
      <div class="col-md-2">
         <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
            <option value="">-- Semester -- </option>
            <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
            <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
         </select>
      </div>
   </div>
   
   <!-- START TABEL -->
         <div class="card card-dark mt-2">
               <div class="card-header"> 
                  <h3 class="card-title">Your Subject</h3>
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
                              <th style="width: 20%">
                                 Grade
                              </th>
                              <th style="width: 20%">
                                 Subject
                              </th>
                              <th>
                                 Action
                              </th>
                           </tr>
                     </thead>
                     <tbody>
                           @if (sizeof($data) != 0)
                              @foreach ($data as $el)
                                    <tr id="{{ 'index_grade_' . $el->id }}">
                                          <td>
                                             {{ $loop->index + 1 }}
                                          </td>
                                          <td>
                                             {{ $el->name }} - {{ $el->class }}
                                          </td>
                                          <td>
                                             <a>
                                                {{ $el->name_subject }}
                                             </a>
                                          </td>
                                          <td>
                                             <a class="btn btn-primary btn-sm"
                                                href="{{url('/' . session('role') . '/dashboard/attendanceSubject') . '/' . session('id_user') . '/' . $el->grade_id . '/' . $el->subject_id}}">
                                                <i class="fas fa-folder">
                                                </i>
                                                Attend
                                             </a>
                                             <a class="btn btn-primary btn-sm"
                                             href="{{ route('attendance.detail.teacher', ['id' => session('id_user'), 'gradeId' => $el->grade_id, 'subjectId' => $el->subject_id]) }}">
                                                <i class="fas fa-folder">
                                                </i>
                                                View
                                             </a>
                                          </td>
                                    </tr>
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
      <!-- END TABLE -->
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
