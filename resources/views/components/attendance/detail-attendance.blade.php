@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
      <div class="row">
         <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-3">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/attendance/class/teacher')}}">Attendance</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Data</li>
               </ol>
            </nav>
         </div>
      </div>
      <div class="card card-dark mt-2">
         <div class="card-header">
            <h3 class="card-title">{{ $data['nameGrade'] }} / {{ \Carbon\Carbon::parse($data['date'])->format('l, d F Y') }}</h3>
            <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
            </div>
         </div>
         <div class="card-body p-0">
            <form method="POST" action="{{ route('actionUpdateAttendanceStudent') }}">
               @csrf
               @method('POST')
               <table class="table table-striped projects">
                  <thead>
                        <tr>
                           <th>#</th>
                           <th style="width: 20%">Student</th>
                           <th >Attendance</th>
                        </tr>
                  </thead>
                  <tbody>
                        @if (sizeof($data['student']) != 0)

                        @foreach ($data['student'] as $el)
                           @php
                              $student[$el->id] = [
                                    'name' => $el->name,
                                    'present' => false,
                                    'alpha' => false,
                                    'sick' => false,
                                    'permission' => false,
                                    'late' => false,
                                    'latest' => 0,
                                    'comment' => '',
                              ];
                           @endphp
                           <tr id="{{ 'index_grade_' . $el->id }}">
                              <td>{{ $loop->index + 1 }}</td>
                              <td><a>{{ $el->name }}</a></td>
                              <td colspan="2">
                                    <div class="input-group">
                                       <input name="date" type="date" class="form-control d-none" id="date" value="{{$data['date']}}">
                                       <input name="grade_id" type="text" class="form-control d-none" id="grade_id" value="{{ $data['gradeId'] }}">
                                       <input name="teacher_id" type="text" class="form-control d-none" id="teacher_id" value="{{ $data['teacherId'] }}">
                                    </div>
                                    <div class="d-flex align-items-center">
                                       <div class="form-check me-2">
                                          <input id="present{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="present" id="present">
                                          <label class="form-check-label" for="present">
                                                Present
                                          </label>
                                       </div>
                                       <div class="form-check me-2 mx-2">
                                          <input id="alpha{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="alpha" id="absent">
                                          <label class="form-check-label" for="absent">
                                                Alpha
                                          </label>
                                       </div>
                                       <div class="form-check me-2">
                                          <input id="sick{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="sick" id="sick">
                                          <label class="form-check-label" for="sick">
                                                Sick
                                          </label>
                                       </div>
                                       <div class="form-check me-2 mx-2">
                                          <input id="permission{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="permission" id="permission">
                                          <label class="form-check-label" for="permission">
                                                Permission
                                          </label>
                                       </div>
                                       <div class="form-check me-2 ">
                                          <input id="late{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="late" id="late">
                                          <label class="form-check-label" for="permission">
                                                Late
                                          </label>
                                       </div>
                                       <div class="flex-grow-1 comment-container mx-2">
                                          <input id="latest{{ $loop->index + 1 }}" name="latest[{{ $el->id }}]" type="number" class="form-control comment-type" placeholder="Minutes late">
                                       </div>
                                       <div class="flex-grow-1 comment-container">
                                          <input id="comment{{ $loop->index + 1 }}" name="comment[{{ $el->id }}]" type="text" class="form-control comment-type" placeholder="Information">
                                       </div>
                                    </div>
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
               <div class="card-footer">
                  <div class="d-flex align-items-center float-right">
                     <button type="button" class="btn btn-info mr-2" id="present_all_btn">All Present</button>
                     <button type="submit" class="btn btn-success" name="present_attend">Submit</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   <!-- END TABLE -->
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      let checkboxes = document.querySelectorAll('.absence-type');
      let presentAllBtn = document.getElementById('present_all_btn');

      presentAllBtn.addEventListener('click', function() {
         checkboxes.forEach(function(checkbox) {
            if (checkbox.id.startsWith('present')) {
               checkbox.checked = true;
            } else {
               checkbox.checked = false;
               let currentRow = checkbox.closest('tr');
               let commentInput = currentRow.querySelector('.comment-container input');
               commentInput.value = ''; // Reset comment value
            }
         });
      });

      checkboxes.forEach(function(checkbox) {
         checkbox.addEventListener('change', function() {
            let currentRow = this.closest('tr');
            let checkboxesInRow = currentRow.querySelectorAll('.absence-type');

            checkboxesInRow.forEach(function(cb) {
               if (cb !== checkbox) {
                  cb.checked = false;
               }
            });
         });
      });
   });

   
</script>


@if(session('failed_attend')) 
   <script>
         Swal.fire({
            icon: 'error',
            title: 'Oops..',
            text: 'Attendance already recorded for this day.',
      });
   </script>
@endif

@if(session('success_attend')) 
   <script> 
      Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully Attend Student',
      });
   </script>
@endif

@endsection
