@extends('layouts.admin.master')
@section('content')

<style>
   .full-height {
      height: 60vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
   }
   .icon-wrapper i {
      font-size: 200px;
      color: #ccc;
   }
   .icon-wrapper p {
      position: absolute;
      left: 50%;
      transform: translate(-50%, 0%);
      margin: 0;
      font-size: 1.5rem;
      color: black;
      text-align: center;
   }
</style>

<!-- Content Wrapper. Contains page content -->
@if (sizeof($data['gradeTeacher']) != 0)
   <div class="container-fluid">
      {{-- <div class="card card-orange mt-2">
            <div class="card-header"> 
               <h3 class="card-title">Your Class</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                     <i class="fas fa-minus"></i>
                  </button>
               </div>
            </div> --}}

            {{-- <div class="card-body p-0">
               <table class="table table-striped projects">
                  <thead>
                        <tr>
                           <th style="width: 5%">No </th>
                           <th style="width: 10%">Class</th>
                           <th style="width: 85%">Action</th>
                        </tr>
                  </thead>
                  <tbody>
                     @foreach ($data['gradeTeacher'] as $el)
                           <tr id="{{ 'index_grade_' . $el->id }}">
                                 <td>
                                    {{ $loop->index + 1 }}
                                 </td>
                                 <td>
                                    <a>
                                       {{ $el->name }} - {{ $el->class }}
                                    </a>
                                 </td>
                                 <td>
                                    <a class="btn btn-primary btn-sm"
                                       href="{{url('/' . session('role') . '/dashboard/attendance/all') . '/' . session('id_user') . '/' . $el->id}}">
                                       <i class="fas fa-paper-plane">
                                       </i>
                                       Attend
                                    </a>
                                    <a class="btn btn-secondary btn-sm"
                                       href="{{ route('attendance.detail.teacher', ['id' => session('id_user'), 'gradeId' => $el->id]) }}">
                                       <i class="fas fa-eye">
                                       </i>
                                       View
                                    </a>
                                    <!-- <a class="btn btn-warning btn-sm"
                                       href="{{url('/' . session('role') . '/dashboard/attendance/edit') . '/' . session('id_user') . '/' . $el->id}}">
                                       <i class="fas fa-pencil">
                                       </i>
                                       Edit
                                    </a> -->
                                 </td>
                           </tr>
                     @endforeach
                  </tbody>
               </table>
            </div> --}}

               @foreach ($data['gradeTeacher'] as $el)
               <div class="col-md-4 col-sm-6 mb-3">
                  <div class="position-relative p-3 bg-light d-flex flex-column rounded-lg shadow-lg border">
                     <div class="ribbon-wrapper ribbon-lg">
                       <div class="ribbon bg-dark text-md">
                        {{ $el->name }} - {{ $el->class }}
                       </div>
                     </div>
                     <p class="flex-grow-1 text-bold text-blackD">
                        {{session('name_user')}} |
                        Class Teacher
                     </p>
                     <div class="row col-10 p-0">
                        <div class="col-6 p-0 pr-1">
                           <a class="btn btn-app btn-sm text-sm w-100 rounded"
                              href="{{ route('attendance.detail.teacher', ['id' => session('id_user'), 'gradeId' => $el->id]) }}">
                              <i class="fas fa-eye">
                              </i>
                              Data
                           </a>
                        </div>
                        <div class="col-6 p-0">
                           <a class="btn btn-app btn-sm text-sm w-100 rounded"
                              href="{{url('/' . session('role') . '/dashboard/attendance/all') . '/' . session('id_user') . '/' . $el->id}}">
                              <i class="fas fa-paper-plane">
                              </i>
                              Attendances
                           </a>
                        </div>
                     </div>
                   </div>
               </div>
               @endforeach
            {{-- </div>
      </div> --}}
   </div>
@else
   <div class="container-fluid full-height">
      <div class="icon-wrapper">
         <i class="fa-regular fa-face-laugh-wink"></i>  
         <p>Oops.. <br> This page can only be accessed by class teachers</p>
      </div>
   </div>
@endif

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
   function saveSemesterToSession() {
      var semesterSelect = document.getElementById('semester');
      var selectedSemester = semesterSelect.value;
      
      // Simpan nilai semester ke dalam session
      $.ajax({
         url: '{{ route('save.semester.session') }}',
         type: 'POST',
         data: {
            semester: selectedSemester,
            _token: '{{ csrf_token() }}'
         },
         success: function(response) {
            console.log('Semester saved to session:', response.semester);
         },
         error: function(xhr, status, error) {
            console.error('Error saving semester to session:', error);
         }
      });
   }
</script>

   @if(session('after_create_attendance')) 

      <script>
           Swal.fire({
               icon: 'success',
               title: 'Successfully',
               text: 'Successfully upload attendance in the database.',
            });
      </script>

   @endif

   @if(session('data_is_empty')) 
      <script>
         Swal.fire({
               icon: 'error',
               title: 'Oops...',
               text: 'Data Attendance is empty !!!',
         });
      </script>
   @endif

@endsection
