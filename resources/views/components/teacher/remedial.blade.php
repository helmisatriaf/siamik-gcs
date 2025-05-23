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

@if (sizeof($data) != 0)
   <div class="container-fluid">
      <div class="card card-orange">
         <div class="card-header"> 
               <h3 class="card-title text-bold">List Student Remedial</h3>
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
                     <th style="width:15%;">Grade</th>
                     <th style="width:15%;">Subject</th>
                     <th style="width:35%;">Name</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($data as $data)
                     @foreach ($data as $dt)
                     <tr id="{{ 'index_grade_' . $dt->id }}">
                        <td>{{ $dt->grade_name}} - {{$dt->grade_class}}</td>
                        <td>{{ $dt->subject_name !== null ? $dt->subject_name : "tidak ada siswa yang remedial" }}</td>
                        <td>
                           @foreach ($dt->students as $student)
                           {{ ucwords(strtolower($student->student_name))}} - <span class="text-bold text-danger">{{$student->final_score}}</span><br>
                           @endforeach
                        </td>
                        <td>
                           @if ($dt->grade_id == 11 || $dt->grade_id == 12 || $dt->grade_id == 13)
                              @if(session('role') == "superadmin" || session('role') == "admin")
                              <a class="btn btn-danger btn" href="{{ url('/'. session('role') .'/reports/detailSubjectSec/student') . '/' . $dt->grade_id . '/' . $dt->subject_id }}">
                                 Remedial
                              </a>
                              @else
                              <a class="btn btn-danger btn" href="{{ url('/teacher/dashboard/report/detailSubjectSecondary') . '/' . $dt->grade_id . '/' . $dt->subject_id }}">
                                 Remedial
                              </a>
                              @endif
                           @else
                              @if(session('role') == "superadmin" || session('role') == "admin")
                              <a class="btn btn-danger btn" href="{{ url('/'. session('role') .'/reports/detailSubject/student') . '/' . $dt->grade_id . '/' . $dt->subject_id }}">
                                 Remedial
                              </a>
                              @else
                              <a class="btn btn-danger btn" href="{{ url('/teacher/dashboard/report/detailSubjectPrimary') . '/' . $dt->grade_id . '/' . $dt->subject_id }}">
                                 Remedial
                              </a>
                              @endif
                           @endif
                        </td>
                     </tr>
                     @endforeach
                  @endforeach
               </tbody>
               </table>
         </div>              
      </div>
   </div>
@else
   <div class="container-fluid full-height">
      <div class="icon-wrapper">
            <i class="fa-regular fa-face-laugh-wink"></i>
            <p>No students are remedial</p>
      </div>
   </div>
@endif
@endsection
