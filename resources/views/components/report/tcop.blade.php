@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-4">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            @if (session('role') == 'superadmin')
              <li class="breadcrumb-item"><a href="{{url('/superadmin/reports')}}">Report</a></li>
            @elseif (session('role') == 'admin')
            <li class="breadcrumb-item"><a href="{{url('/admin/reports')}}">Report</a></li>
            @elseif (session('role') == 'teacher')
            <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/report/class/teacher')}}">Report</a></li>    
            @endif
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
          </ol>
        </nav>
      </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-11 col-md-10">
                    <p class="text-bold">The Certificate of Promotion</p>
                    <table>
                        <tr>
                            <td>Class</td>
                            <td> : {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</td>
                        </tr>
                        <tr>
                            <td>Class Teacher</td>
                            <td> : {{ $data['classTeacher']->teacher_name }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td> : {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
                        </tr>
                        @if($data['status'] !== null)  
                        <tr>
                            <td>Status</td>
                            <td> : <span class="text-bold">
                                Already Submitted on {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}
                                </span> 
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>

                <div class="col-1 col-md-2 d-flex justify-content-end align-items-start text-end">
                    @if ($data['status'] == null)
                        @if (!empty($data['students']))
                            <a class="btn btn-app bg-success" data-toggle="modal" data-target="#confirmModal">
                                <i class="fas fa-save"></i>
                                Submit
                            </a>
                        @endif
                    @elseif ($data['status'] !== null)    
                        @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                            <a  class="btn btn-app bg-danger" data-toggle="modal" data-target="#modalDecline">
                                <i class="fas fa-cancel"></i>
                                Decline
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('role') == 'superadmin')
                <form id="confirmForm"  method="POST">
            @elseif (session('role') == 'admin')
                <form id="confirmForm" method="POST">
            @elseif (session('role') == 'teacher')
                <form id="confirmForm" method="POST" action={{route('actionTeacherPostTcop')}}>
            @endif
            @csrf
            
            <div style="overflow-x: auto;">    
                <table class="table table-striped table-bordered bg-white">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Sem 1</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Sem 2</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Average</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Marks</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Grade</th>
                            <th colspan="1" class="text-center" style="vertical-align : middle;text-align:center;">Promotion</th>
                        </tr>
        
                        <tr>
                            <!-- Major Subjects -->
                            <td class="text-center">SM</td>
                            <td class="text-center">SG</td>
                            <td class="text-center">SM</td>
                            <td class="text-center">SG</td>
                            <td class="text-center">(T/F)</td>
                            <!-- END MAJOR SUBJECTS -->
                        </tr>
                    </thead>
        
                    <tbody>
                        @if (!empty($data['students']))
                            @foreach ($data['students'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student['student_name'] }}
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">    
                                    </td>
        
                                    @php
                                        // Initialize variables to store scores for both semesters
                                        $semester1Scores = [];
                                        $semester2Scores = [];
        
                                        // Separate scores by semester
                                        foreach ($student['scores'] as $semester => $scores) {
                                            if ($semester == 1) {
                                                $semester1Scores = $scores;
                                            } elseif ($semester == 2) {
                                                $semester2Scores = $scores;
                                            }
                                        }
                                    @endphp
        
                                    @foreach (['semester1Scores', 'semester2Scores'] as $semesterScores)
                                        @if (!empty($$semesterScores))
                                            @foreach ($$semesterScores as $score)
                                                <td class="text-center">{{ $score['final_score'] }} </td>
                                                <td class="text-center">{{ $score['grades_final_score'] }} </td>
                                            @endforeach
                                        @else
                                            <!-- Display empty cells if there are no scores for the semester -->
                                            <td class="text-center">N/A</td>
                                            <td class="text-center">N/A</td>
                                        @endif
                                    @endforeach
        
                                    <td class="text-center">{{ $student['average_final_score'] }} <input type="number" name="final_score[]" value="{{ $student['average_final_score'] }}" class="d-none"></td>
                                    <td class="text-center">{{ $student['marks'] }} <input type="text" name="grades_final_score[]" value="{{ $student['marks'] }}" class="d-none"></td>
                                    <td class="text-center">{{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</td>
                                    <td class="text-left">
                                        @if ( $student['average_final_score'] > 64)
                                            <span class="badge badge-success">
                                                Promote to {{ $data['promote']->name }}-{{ $data['promote']->class }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                Stay in {{ $data['grade']->grade_name }}-{{ $data['grade']->grade_class }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                            <input name="class_teacher" type="number" class="form-control d-none" id="class_teacher" value="{{ $data['classTeacher']->teacher_id }}">    
                        @else
                            <tr>
                                <td colspan="10" class="text-center text-red">
                                    Data empty
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Submit TCOP</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                Are you sure want to submit TCOP ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmTcop">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Decline TCOP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline TCOP ?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn" id="confirmDecline">Yes</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('confirmTcop').addEventListener('click', function() {
            document.getElementById('confirmForm').submit();
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDecline').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = @json($data['grade']->grade_id);
            var teacherId = @json($data['classTeacher']->teacher_id);
            var semester = @json($data['semester']);
            var role = @json(session('role'));

            var confirmDecline = document.getElementById('confirmDecline');

            if(role == 'admin' || role == 'superadmin'){
                confirmDecline.href = "{{ url('/' . session('role') . '/reports/tcop/decline') }}/" + id + "/" + teacherId;
            }
            else if(role == 'teacher'){
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/tcop/decline') }}/" + id + "/" + teacherId;
            }            
        });
    });
</script>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>


@if(session('after_post_tcop'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully post tcop in the database.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

@if(session('after_decline_tcop'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            title: 'Successfully decline tcop.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

@endsection
