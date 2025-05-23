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
                  <li class="breadcrumb-item active" aria-current="page">Edit Student Attend</li>
               </ol>
            </nav>
         </div>
      </div>

      <div class="card card-dark mt-2">
         <div class="card-header">
            <h3 class="card-title">Edit Attendance Student {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</h3>
            <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
            </div>
         </div>
         <div class="card-body p-0">
            <form method="POST" action="{{ route('actionEditAttendanceStudent') }}">
               @csrf
               @method('POST')
               <table class="table table-striped projects">
                  <thead>
                        <tr>
                           <th>No</th>
                           <th style="width: 20%">Student</th>
                           <th >Attendance</th>
                           <th style="width: 20%"> 
                              <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
                                 <option value="">-- Semester -- </option>
                                 <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
                                 <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
                              </select>
                           </th>
                        </tr>
                  </thead>
                  <tbody>
                     @foreach ($data as $el)
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
                           <td><a>{{ ucwords(strtolower($el->student_name)) }}</a></td>
                           <td colspan="2">
                                 <div class="input-group">
                                    <input name="attendanceId[{{ $el->id }}]" type="number" class="form-control d-none" id="attendance{{ $loop->index + 1 }}" value="{{ $el->id }}">
                                 </div>
                                 <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                       <input id="present{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="present" id="present" {{ $el->present ? 'checked' : '' }}>
                                       <label class="form-check-label" for="present">
                                             Present
                                       </label>
                                    </div>
                                    <div class="form-check me-2 mx-2">
                                       <input id="alpha{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="alpha" id="absent" {{ $el->alpha ? 'checked' : '' }}>
                                       <label class="form-check-label" for="alpha">
                                             Alpha
                                       </label>
                                    </div>
                                    <div class="form-check me-2">
                                       <input id="sick{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="sick" id="sick" {{ $el->sick ? 'checked' : '' }}>
                                       <label class="form-check-label" for="sick">
                                             Sick
                                       </label>
                                    </div>
                                    <div class="form-check me-2 mx-2">
                                       <input id="permission{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="permission" id="permission" {{ $el->permission ? 'checked' : '' }}>
                                       <label class="form-check-label" for="permission">
                                             Permission
                                       </label>
                                    </div>
                                    <div class="form-check me-2 ">
                                       <input id="late{{ $loop->index + 1 }}" name="status[{{ $el->id }}]" class="form-check-input absence-type" type="checkbox" value="late" id="late" {{ $el->late ? 'checked' : '' }}>
                                       <label class="form-check-label" for="permission">
                                             Late
                                       </label>
                                    </div>
                                    <div class="flex-grow-1 comment-container mx-2">
                                       <input id="latest{{ $loop->index + 1 }}" name="latest[{{ $el->id }}]" type="number" class="form-control comment-type" placeholder="times late (minute)" value="{{ $el->latest ? $el->latest : '' }}">
                                    </div>
                                    <div class="flex-grow-1 comment-container">
                                       <input id="comment{{ $loop->index + 1 }}" name="comment[{{ $el->id }}]" type="text" class="form-control comment-type" placeholder="information" value="{{ $el->information ? $el->information : '' }}">
                                    </div>
                                 </div>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
               <div class="card-footer">
                  <div class="d-flex align-items-center float-right">
                     <button type="button" class="btn btn-info mr-2" id="present_all_btn">Present All</button>
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

   @if(session('success_edit_attend')) 
      <script> 
         Swal.fire({
               icon: 'success',
               title: 'Successfully',
               text: 'Successfully Edit Attend Student',
         });
      </script>
   @endif

@endsection
