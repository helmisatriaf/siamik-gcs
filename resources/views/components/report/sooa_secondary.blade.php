@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-white p-3 rounded-3 mb-4">
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

    <div class="row">
        <div class="col">
            <p class="font-bold">Summary of Academic Assessment</p>
            {{-- <p class="text-xs">Class Teacher: {{ $data['grade']->teacher_name }}</p>
            <p class="text-xs">Class : {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} </p>
            <p class="text-xs">Date : {{date('d-m-Y')}}</p> --}}
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
            </table>
        </div>
    </div>

    @if (session('role') == 'superadmin')
        <form id="confirmForm" method="POST" action={{route('actionPostScoringSooaSecondary')}}>
    @elseif (session('role') == 'admin')
        <form id="confirmForm" method="POST" action={{route('actionAdminPostScoringSooaSecondary')}}>
    @elseif (session('role') == 'teacher')
        <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringSooaSecondary')}}>
    @endif
    
    @csrf

    @if ($data['status'] == null)
        <div class="row my-2">
            <div class="input-group-append mx-2">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Submit</button>
            </div>
        </div>
    @elseif ($data['status']->status != null && $data['status']->status == 1)       
        <div class="row my-2">
            <div class="input-group-append mx-2">
                <a  class="btn btn-success">Already Submitted on {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}</a>
                @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                <a  class="btn btn-warning mx-2" data-toggle="modal" data-target="#modalDecline">Decline</a>
                @endif
            </div>
        </div>  
    @endif
    <div style="overflow-x: auto;">

        <table class="table table-striped table-bordered bg-white" style="width: 2000px">
            @if ($data['status'] == null)
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Academic</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA 1</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA 2</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Self-Development</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA Average</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Behavior</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Attendance</th>
                            <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Participation</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Total</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Grades</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Rank</th>
                        </tr>

                        <tr>
                            <!-- Major Subjects -->
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <!-- END MAJOR SUBJECTS -->
                            
                            <!-- MINOR SUBJECTS -->
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <!-- END MINOR SUBJECTS -->
                            
                            <!-- SUPPLEMENTARY SUBJECTS -->
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            <!-- END SUPPLEMENTARY SUBJECTS -->
                        </tr>
                    </thead>

                    @if (!empty($data['students']))
                    <tbody>
                    @foreach ($data['students'] as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student['student_name'] }}</td>

                            @if (!empty($student['scores']))
                                @foreach ($student['scores'] as $index => $score)

                                    <!-- ACADEMIC -->
                                    <td class="text-center">{{ $score['academic'] }}</td>
                                    <td class="text-center">{{ $score['grades_academic'] }}</td>

                                    <!-- ECA 1 -->
                                    <td class="text-center">
                                        {{-- @if ($student['haveEca'] ==  1)
                                            {{ $student['nameEca']['eca_1'] }}
                                            <input name="eca_1[]" min="0" max="100" type="number" value="{{$score['eca_1']}}" class="form-control required-input" id="eca_1" autocomplete="off" value required>
                                        @elseif ($student['haveEca'] == 0) --}}
                                            {{-- {{ $student['nameEca'] }} --}}
                                            <input name="eca_1[]" min="0" max="100" type="number" class="form-control" id="eca_1" value="{{ $score['eca_1'] ?: '' }}" autocomplete="off" required>
                                            {{-- <input name="eca_1[]" min="0" max="100" type="number" class="form-control d-none" id="eca_1" value="0" autocomplete="off" required> --}}
                                        {{-- @endif --}}
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_1'] }}</td>
                                    
                                    <!-- ECA 2 -->  
                                    <td class="text-center">
                                        {{-- @if ($student['haveEca'] == 1)
                                            @if ($student['nameEca']['eca_2'] !=  "Not Choice")
                                                {{ $student['nameEca']['eca_2'] }}
                                                <input name="eca_2[]" min="0" max="100" type="number" value="{{$score['eca_2']}}" class="form-control required-input" id="eca_2" autocomplete="off" required>
                                            @elseif ($student['nameEca']['eca_2'] ==  "Not Choice")
                                                {{ $student['nameEca']['eca_2'] }}
                                                <input name="eca_2[]" min="0" max="100" type="number" value="{{$score['eca_2']}}" class="form-control d-none" id="eca_2" value="0" autocomplete="off" required>    
                                            @endif
                                        @elseif ($student['haveEca'] == 0)
                                            {{ $student['nameEca'] }} --}}
                                            <input name="eca_2[]" min="0" max="100" type="number" class="form-control" id="eca_2" value="{{ $score['eca_2'] ?: '' }}" autocomplete="off" required>
                                            {{-- <input name="eca_2[]" min="0" max="100" type="number" class="form-control d-none" id="eca_2" value="0" autocomplete="off" required>     --}}
                                        {{-- @endif --}}
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_2'] }}</td>

                                    <!-- Self-Development -->
                                    <td class="text-center">
                                        <input name="self_development[]" min="0" max="100" type="number" value="{{$score['self_development']}}" class="form-control required-input" id="self_development" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>
                                    
                                    <!-- ECA Aver -->
                                    <td class="text-center">
                                        {{$score['eca_aver']}}
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>

                                    <td class="text-center">
                                        <input name="behavior[]" min="0" max="100" type="number" value="{{$score['behavior']}}" class="form-control required-input" id="behavior" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_behavior'] ?? '' }}</td>

                                    <!-- Attendance -->
                                    <td class="text-center">{{ $score['attendance'] }}</td>
                                    <td class="text-center">{{ $score['grades_attendance'] }}</td>

                                    <!-- Participation -->
                                    <td class="text-center">
                                        <input name="participation[]"  min="0" max="100" type="number" value="{{$score['participation']}}" class="form-control required-input" id="participation" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_participation'] }}</td>
                                    
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">

                                    <td class="text-center">{{ $score['final_score'] }}</td>
                                    <td class="text-center">{{ $score['grades_final_score'] }}</td>
                                @endforeach
                            @else
                                @foreach ($student['scores'] as $index => $score)

                                    <!-- ACADEMIC -->
                                    <td class="text-center">{{ $score['academic'] }}</td>
                                    <td class="text-center">{{ $score['grades_academic'] }}</td>

                                    <!-- ECA 1 -->
                                    <td class="text-center">
                                        @if($score['eca_1'])
                                            {{ $score['eca_1'] }}
                                        @else
                                            <input name="eca_1[]" min="0" max="100" type="number" class="form-control" id="eca_1" value="{{ $score['eca_1'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_1'] }}</td>

                                    <!-- ECA 2 -->  
                                    <td class="text-center">
                                        @if($score['eca_2'])
                                            {{ $score['eca_2'] }}
                                        @else
                                            <input name="eca_2[]"  min="0" max="100" type="number" class="form-control" id="eca_2" value="{{ $score['eca_2'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_2'] }}</td>

                                    <!-- Self-Development -->
                                    <td class="text-center">
                                        @if(isset($score['self_development']))
                                            {{ $score['self_development'] }}
                                        @else
                                            <input name="self_development[]" min="0" max="100" type="number" class="form-control" id="self_development" value="{{ $score['self_development'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>

                                    <!-- ECA Aver -->
                                    <td class="text-center">
                                        @if(isset($score['eca_aver']))
                                            {{ $score['eca_aver'] }}
                                        @else
                                            <input name="eca_aver[]" min="0" max="100" type="number" class="form-control" id="eca_aver" value="{{ $score['eca_aver'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>

                                    <td class="text-center">
                                        @if(isset($score['behavior']))
                                            {{ $score['behavior'] }}
                                        @else
                                            <input name="behavior[]" min="0" max="100" type="number" class="form-control" id="behavior" value="{{ $score['behavior'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_behavior'] ?? '' }}</td>

                                    <!-- Attendance -->
                                    <td class="text-center">{{ $score['attendance'] }}</td>
                                    <td class="text-center">{{ $score['grades_attendance'] }}</td>

                                    <!-- Participation -->
                                    <td class="text-center">
                                        @if(isset($score['participation']))
                                            {{ $score['participation'] }}
                                        @else
                                            <input name="participation[]"  min="0" max="100" type="number" class="form-control" id="participation"value="{{ $score['participation'] ?: '' }}" autocomplete="off" required></td>
                                        @endif
                                    <td class="text-center">{{ $score['grades_participation'] }}</td>

                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">

                                    <td class="text-center">{{ $score['final_score'] }}</td>
                                    <td class="text-center">{{ $score['grades_final_score'] }}</td>
                                @endforeach
                            @endif

                            <td class="text-center">{{ $student['ranking'] }}</td>
                        @endforeach
                        </tr>
                        <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                        <input name="class_teacher" type="number" class="form-control d-none" id="class_teacher" value="{{ $data['classTeacher']->teacher_id }}">    
                        <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">    
                    @else
                        <p>Data Kosong</p>
                    @endif
                    </tbody>

            @elseif ($data['status']->status != null && $data['status']->status == 1)       
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Academic</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA 1</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA 2</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Self-Development</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">ECA Aver</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Behavior</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Attendance</th>
                        <th colspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Participation</th>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Total</th>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Grades</th>
                        <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Rank</th>
                    </tr>

                    <tr>
                        <!-- Major Subjects -->
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <!-- END MAJOR SUBJECTS -->
                        
                        <!-- MINOR SUBJECTS -->
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <!-- END MINOR SUBJECTS -->
                        
                        <!-- SUPPLEMENTARY SUBJECTS -->
                        <td class="text-center">Mks</td>
                        <td class="text-center">Grs</td>
                        <!-- END SUPPLEMENTARY SUBJECTS -->
                    </tr>
                </thead>

                <tbody>
                @if (!empty($data['students']))
                    @foreach ($data['students'] as $student)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student['student_name'] }}</td>

                        @foreach ($student['scores'] as $index => $score)
                            <!-- ACADEMIC -->
                            <td class="text-center">{{ $score['academic'] }}</td>
                            <td class="text-center">{{ $score['grades_academic'] }}</td>

                            <!-- ECA 1 -->
                            <td class="text-center">
                                {{-- @if ($student['haveEca'] ==  1)
                                    {{ $student['nameEca']['eca_1'] }} --}}
                                    {{ $score['eca_1'] ?? '' }}
                                {{-- @elseif ($student['haveEca'] == 0)
                                    {{ $student['nameEca'] }}
                                    <input name="eca_1[]" min="0" max="100" type="number" class="form-control d-none" id="eca_1" value="0" autocomplete="off" required> --}}
                                {{-- @endif --}}
                            </td>
                            <td class="text-center">{{ $score['grades_eca_1'] }}</td>
                            
                            <!-- ECA 2 -->  
                            <td class="text-center">
                                {{-- @if ($student['haveEca'] == 1)
                                    @if ($student['nameEca']['eca_2'] !=  "Not Choice")
                                        {{ $student['nameEca']['eca_2'] }} --}}
                                        {{ $score['eca_2'] ?? '' }}
                                    {{-- @elseif ($student['nameEca']['eca_2'] ==  "Not Choice")
                                        {{ $student['nameEca']['eca_2'] }}  
                                    @endif
                                @elseif ($student['haveEca'] == 0)
                                    {{ $student['nameEca'] }} 
                                @endif --}}
                            </td>
                            <td class="text-center">{{ $score['grades_eca_2'] }}</td>

                            <!-- Self-Development -->
                            <td class="text-center">
                                {{ $score['self_development'] ?? '' }}
                            </td>
                            <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>
                            
                            <!-- ECA Aver -->
                            <td class="text-center">
                                {{ $score['eca_aver'] ?? '' }}
                            </td>
                            <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>

                            <td class="text-center">
                                {{ $score['behavior'] ?? '' }}
                            </td>
                            <td class="text-center">{{ $score['grades_behavior'] ?? '' }}</td>

                            <!-- Attendance -->
                            <td class="text-center">{{ $score['attendance'] }}</td>
                            <td class="text-center">{{ $score['grades_attendance'] }}</td>

                            <!-- Participation -->
                            <td class="text-center">
                                {{ $score['participation'] ?? '' }}
                            </td>
                            <td class="text-center">{{ $score['grades_participation'] }}</td>
                            
                            <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">

                            <td class="text-center">{{ $score['final_score'] }}</td>
                            <td class="text-center">{{ $score['grades_final_score'] }}</td>
                        @endforeach

                        <td class="text-center">{{ $student['ranking'] }}</td>
                    @endforeach
                    </tr>
                    <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                    <input name="class_teacher" type="number" class="form-control d-none" id="class_teacher" value="{{ $data['classTeacher']->teacher_id }}">    
                    <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">    
                @else
                    <p>Empty Data</p>
                @endif
                </tbody>
            @endif
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Submit Score</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure want to submit score sooa?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmSooaScoring">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Report Card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline report card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-danger btn" id="confirmDecline">Yes decline</a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    document.querySelectorAll('input').forEach(function(input) {
        input.addEventListener('input', function(event) {
            let value = parseInt(input.value, 10);
            if (value < 0 || value > 100) {
                input.value = '';
                alert('Please enter a number between 0 and 100.');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('confirmSooaScoring').addEventListener('click', function() {
            // Mengambil semua input yang wajib diisi
            var requiredInputs = document.querySelectorAll('.required-input');
            var allFilled = true;

            // console.log(requiredInputs);

            requiredInputs.forEach(function(input) {
                var value = input.value.trim();

                // Memeriksa apakah input tidak kosong dan apakah bernilai angka yang valid
                if (value === '') {
                    allFilled = false;
                    // Menambahkan kelas untuk memberikan highlight pada input yang kosong atau tidak valid
                    input.classList.add('is-invalid');
                } else {
                    // Menghapus kelas jika input tidak kosong atau tidak valid
                    input.classList.remove('is-invalid');
                }
            });

            // alert(allFilled);
            // Jika semua input terisi dan valid, submit form
            if (allFilled) {
                document.getElementById('confirmForm').submit();
            } else {
                // Menampilkan pesan peringatan
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'All fields must be filled with valid values before submitting the form!',
                });
            }
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
                confirmDecline.href = "{{ url('/' . session('role') . '/reports/sooa/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
            else if(role == 'teacher'){
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/sooa/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
        });
    });
</script>

@if(session('after_post_sooa'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully post sooa in the database.',
        });
    </script>
@endif

@if(session('after_decline_sooa'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully decline SOOA.',
        });
    </script>
@endif

@endsection
