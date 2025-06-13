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
            <li class="breadcrumb-item active" aria-current="page">Detail Report Card</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="card" style="height:70vh;overflow-y: auto;">
        <div class="card-header">
            <div class="row">
                <div class="col-11 col-md-10">
                    <p class="text-bold">Report Card Semester 2</p>
                    {{-- <p class="text-xs">Class Teacher : {{ $data['grade']->teacher_name }}</p>
                    <p class="text-xs">Class: {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} </p>
                    <p class="text-xs">Date  : {{date('d-m-Y')}}</p> --}}
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
                        @if($data['status'] == null)
                        @elseif ($data['status']->status != null && $data['status']->status == 1)  
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
                    @elseif ($data['status']->status != null && $data['status']->status == 1)    
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
                <form id="confirmForm" method="POST" action={{route('actionAdminPostReportCard2')}}>
            @elseif (session('role') == 'teacher')
                <form id="confirmForm" method="POST" action={{route('actionTeacherPostReportCard2')}}>
            @endif
            @csrf
            
            <div id="scroll-top" style="overflow-x: auto;position: sticky; top: 0; z-index: 100;">
                <div style="width: 2200px; height: 1px;"></div> <!-- dummy scroll -->
            </div>
            <div id="scroll-bottom" style="overflow-x: auto;">
        
                @if (!empty($data['students']))
                
                <table class="table table-striped table-bordered bg-white" style=" width: 2200px;">
                    @if ($data['status'] == null)
                        <!-- JIKA DATA BELUM DI SUBMIT OLEH TEACHER -->
                        <thead>
                            <tr>
                                <th colspan="2" style="vertical-align : middle;text-align:center;">Legend</th>
                                <th colspan="11" style="vertical-align : middle;text-align:left;">E – Excellent   G – Good   S – Satisfactory   N – Needs Improvement</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Independent work</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Initiative</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Homework Completion</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Use of Information</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Cooperation with Others</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Conflict Resolution</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Class Participation</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Problem Solving</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Goal setting to improve work</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Strengths/Weeakness/Next Steps</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Promotion Status</th>
                            </tr>
                        </thead>
        
                        <!-- JIKA TEACHER MEMINTA EDIT SETELAH SUBMIT -->
                        @if(!empty($data['result']))
                            <tbody>
                                @foreach ($data['result'] as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td style="position: sticky; left: 0; background: #ffffff; z-index: 99;">{{ $student['student_name'] }}</td>
                                        @foreach ($student['scores'] as $index => $score)
                                                
                                            <!-- Independent_work -->
                                            <td class="text-center">
                                                <input name="independent_work[]" type="text" class="form-control required-input" id="iw" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['independent_work'] }}" onkeyup="validateInput(this)"></td>
        
                                            <!-- Initiative -->
                                            <td class="text-center">
                                                <input name="initiative[]" type="text" class="form-control required-input" id="in" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['use_of_information'] }}" onkeyup="validateInput(this)"></td>
        
                                            <!-- Homework_completion -->
                                            <td class="text-center">
                                            <input name="homework_completion[]" type="text" class="form-control required-input" id="hc" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['homework_completion'] }}" onkeyup="validateInput(this)"></td>
        
        
                                            <!-- Use_of_information -->
                                            <td class="text-center">
                                            <input name="use_of_information[]" type="text" class="form-control required-input" id="uoi" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['use_of_information'] }}" onkeyup="validateInput(this)"></td>
        
        
                                            <!-- Cooperation_with_other -->
                                            <td class="text-center">
                                            <input name="cooperation_with_other[]" type="text" class="form-control required-input" id="cwo" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['cooperation_with_other'] }}" onkeyup="validateInput(this)"></td>
        
        
                                            <!-- Conflict_resolution -->
                                            <td class="text-center">
                                            <input name="conflict_resolution[]" type="text" class="form-control required-input" id="cr" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['conflict_resolution'] }}" onkeyup="validateInput(this)"></td>
        
        
                                            <!-- Class_participation -->
                                            <td class="text-center">
                                            <input name="class_participation[]" type="text" class="form-control required-input" id="cp" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['class_participation'] }}" onkeyup="validateInput(this)"></td>
        
        
                                            <!-- Problem_solving -->
                                            <td class="text-center">
                                            <input name="problem_solving[]" type="text" class="form-control required-input" id="ps" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['problem_solving'] }}" onkeyup="validateInput(this)"></td>
        
        
                                            <!-- Goal_setting_to_improve_work -->
                                            <td class="text-center">
                                            <input name="goal_setting_to_improve_work[]" type="text" class="form-control required-input" id="gstiw" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" value="{{ $score['goal_setting_to_improve_work'] }}" onkeyup="validateInput(this)"></td>
        
        
                                            <!-- Strengths/weakness/nextstep -->
                                            <td class="text-center">
                                                <textarea name="strength_weakness_nextstep[]" class="required-input"  autocomplete="off" maxlength="255" oninput="validateCommentLength(this)"
                                                    rows="3" cols="30">
                                                    {{ $score['strength_weakness_nextstep'] }}
                                                </textarea>
                                            </td>
        
                                            <td class="text-left text-xs">
                                                <div class="form-check me-2 mx-2">
                                                    <input id="promotionstatus1" name="status[]" class="form-check-input status-type" type="checkbox" value="1" id="promotion1" {{ $score['promotion_status'] == 1 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="present">
                                                        Progressing well towards promotion
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="promotionstatus2" name="status[]" class="form-check-input status-type" type="checkbox" value="2" id="promotion2" {{ $score['promotion_status'] == 2 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="present">
                                                        Progressing with some difficulty towards promotion
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="promotionstatus3" name="status[]" class="form-check-input status-type" type="checkbox" value="3" id="promotion3" {{ $score['promotion_status'] == 3 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="present">
                                                        No Promotion
                                                    </label>
                                                </div>
                                            </td>
                                        @endforeach
                                        <input name="student_id[]" type="number" class="form-control required-input d-none" id="student_id" value="{{ $student['student_id'] }}">
                                    </tr>
                                @endforeach
                            </tbody>
                            <input name="grade_id" type="number" class="form-control required-input d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                            <input name="teacher_id" type="number" class="form-control required-input d-none" id="class_teacher_id" value="{{ $data['classTeacher']->teacher_id }}">    
                            <input name="semester" type="number" class="form-control required-input d-none" id="semester" value="{{ $data['semester'] }}"> 
                        
                        <!-- JIKA TEACHER BELUM INPUT NILAI -->
                        @else
                            <tbody>
                                @foreach ($data['students'] as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td style="position: sticky; left: 0; background: #ffffff; z-index: 99;">{{ $student['name'] }}</td>
                
                                            <!-- Independent_work -->
                                            <td class="text-center">
                                                <input name="independent_work[]" type="text" class="form-control required-input" id="iw" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                                            <!-- Initiative -->
                                            <td class="text-center">
                                                <input name="initiative[]" type="text" class="form-control required-input" id="in" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                                            <!-- Homework_completion -->
                                            <td class="text-center">
                                            <input name="homework_completion[]" type="text" class="form-control required-input" id="hc" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                
                                            <!-- Use_of_information -->
                                            <td class="text-center">
                                            <input name="use_of_information[]" type="text" class="form-control required-input" id="uoi" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                
                                            <!-- Cooperation_with_other -->
                                            <td class="text-center">
                                            <input name="cooperation_with_other[]" type="text" class="form-control required-input" id="cwo" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                
                                            <!-- Conflict_resolution -->
                                            <td class="text-center">
                                            <input name="conflict_resolution[]" type="text" class="form-control required-input" id="cr" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                
                                            <!-- Class_participation -->
                                            <td class="text-center">
                                            <input name="class_participation[]" type="text" class="form-control required-input" id="cp" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                                            <!-- Problem_solving -->
                                            <td class="text-center">
                                            <input name="problem_solving[]" type="text" class="form-control required-input" id="ps" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                
                                            <!-- Goal_setting_to_improve_work -->
                                            <td class="text-center">
                                            <input name="goal_setting_to_improve_work[]" type="text" class="form-control required-input" id="gstiw" autocomplete="off" required placeholder="E, G, S, or N." maxlength="1" onkeyup="validateInput(this)"></td>
                
                
                                            <!-- Strengths/weakness/nextstep -->
                                            <td class="text-center">
                                                <textarea name="strength_weakness_nextstep[]" class="required-input"  autocomplete="off" maxlength="255" oninput="validateCommentLength(this)">    
                                                </textarea>
                                            </td>
                
                                            <td class="text-left text-xs">
                                                <div class="form-check me-2 mx-2">
                                                    <input id="promotionstatus1" name="status[]" class="form-check-input status-type" type="checkbox" value="1" id="promotion1">
                                                    <label class="form-check-label" for="present">
                                                        Progressing well towards promotion
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="promotionstatus2" name="status[]" class="form-check-input status-type" type="checkbox" value="2" id="promotion2">
                                                    <label class="form-check-label" for="present">
                                                        Progressing with some difficulty towards promotion
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="promotionstatus3" name="status[]" class="form-check-input status-type" type="checkbox" value="3" id="promotion3">
                                                    <label class="form-check-label" for="present">
                                                        No Promotion
                                                    </label>
                                                </div>
                                            </td>
                                    
                                            <input name="student_id[]" type="number" class="form-control required-input d-none" id="student_id" value="{{ $student['id'] }}">
                                    </tr>
                                @endforeach
                            </tbody>
                            <input name="grade_id" type="number" class="form-control required-input d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                            <input name="teacher_id" type="number" class="form-control required-input d-none" id="class_teacher_id" value="{{ $data['classTeacher']->teacher_id }}">    
                            <input name="semester" type="number" class="form-control required-input d-none" id="semester" value="{{ $data['semester'] }}"> 
                        @endif  
                    
                    <!-- JIKA DATA SUDAH DI SUBMIT OLEH TEACHER -->
                    @elseif ($data['status']->status != null && $data['status']->status == 1)
                        <thead>
                            <tr>
                                <th colspan="2" style="vertical-align : middle;text-align:center;">Legend</th>
                                <th colspan="12" style="vertical-align : middle;text-align:left;">E – Excellent   G – Good   S – Satisfactory   N – Needs Improvement</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Independent work</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Initiative</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Homework Completion</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Use of Information</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Cooperation with Others</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Conflict Resolution</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Class Participation</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Problem Solving</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Goal setting to improve work</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Strengths/Weeakness/Next Steps</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Promotion Status</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Print Report Card</th>
                            </tr>
                        </thead>
        
                        <tbody>
                        @if(!empty($data['result']))
                            @foreach ($data['result'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="position: sticky; left: 0; background: #ffffff; z-index: 99;">{{ $student['student_name'] }}</td>
                                    @foreach ($student['scores'] as $index => $score)
                                        <!-- Independent Work -->
                                        <td class="text-center">{{ strtoupper($score['independent_work']) }}</td>
        
                                        <!-- Initiative -->
                                        <td class="text-center">{{ strtoupper($score['initiative']) }}</td>
        
                                        <!-- Homework_completion -->
                                        <td class="text-center">{{ strtoupper($score['homework_completion']) }}</td>
                    
        
                                        <!-- Use_of_information -->
                                        <td class="text-center">{{ strtoupper($score['use_of_information']) }}</td>
                    
        
                                        <!-- Cooperation_with_other -->
                                        <td class="text-center">{{ strtoupper($score['cooperation_with_other']) }}</td>
                    
        
                                        <!-- Conflict_resolution -->
                                        <td class="text-center">{{ strtoupper($score['conflict_resolution']) }}</td>
                    
        
                                        <!-- Class_participation -->
                                        <td class="text-center">{{ strtoupper($score['class_participation']) }}</td>
        
                                        <!-- Problem_solving -->
                                        <td class="text-center">{{ strtoupper($score['problem_solving']) }}</td>
                    
        
                                        <!-- Goal_setting_to_improve_work -->
                                        <td class="text-center">{{ strtoupper($score['goal_setting_to_improve_work']) }}</td>
                    
        
                                        <!-- Strengths/weakness/nextstep -->
                                        <td class="text-left">{{ $score['strength_weakness_nextstep'] }}</td>
        
                                        <td class="text-left">
                                            @if($score['promotion_status'] === 1)
                                                <span class="badge badge-success">Progressing well towards promotion</span>
                                            @elseif($score['promotion_status'] === 2)
                                                <span class="badge badge-warning">Progressing with some difficulty towards promotion</span>
                                            @elseif($score['promotion_status'] === 3)
                                            <span class="badge badge-danger">No Promotion</span>
                                            @endif
                                        </td>
        
                                        @if ($data['status'] !== null)
                                            @if (session('role') == "superadmin" || session('role') == "admin")
                                                <td>
                                                    <a class="btn btn-primary btn"
                                                        href="{{url(session('role') . '/reports/semester2/print') . '/' . $student['student_id']}}">
                                                        Print
                                                    </a>
                                                </td>
                                            @elseif (session('role') == "teacher")
                                                <td>
                                                    <a class="btn btn-primary btn"
                                                        href="{{url('teacher/dashboard/report/semester2/print') . '/' . $student['student_id']}}">
                                                        Print
                                                    </a>
                                                </td>
                                            @endif
                                        @endif
                                    @endforeach
                            @endforeach
                        @endif  
                        </tbody>
                    @endif
        
                </table>
                @else
                    <p>Empty Data Student !!!</p>
                @endif
            </div>
        </div>
    </div>


</div>

<!-- <script>
    document.getElementById('confirmAccScoring').addEventListener('click', function() {
        document.getElementById('confirmForm').submit();
    });
</script> -->

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Submit Report Card Semester 2</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit report card semester 2?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAccScoring">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Report Card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} Semester 2</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline report card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} semester 2?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn" id="confirmDecline">Yes</a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    function validateInput(input) {
        var validChars = ['E', 'G', 'S', 'N'];
        var value = input.value.toUpperCase();
        if (!validChars.includes(value) && value !== '') {
            input.value = '';
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter only "E", "G", "S", or "N".'
            });
        }
    }

    function validateCommentLength(input) {
        const maxLength = 255;
        if (input.value.length == maxLength) {
            console.log(input.value.length);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `Maksimum 255 Characters`,
                confirmButtonText: 'Oke',
            });
            input.value = input.value.slice(0, maxLength); // Truncate excess characters
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('confirmAccScoring').addEventListener('click', function() {
            // Mengambil semua input yang wajib diisi
            var requiredInputs = document.querySelectorAll('.required-input');
            var allFilled = true;

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
    
    document.addEventListener('DOMContentLoaded', function() {
    let checkboxes = document.querySelectorAll('.status-type');

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let currentRow = this.closest('tr');
            let checkboxesInRow = currentRow.querySelectorAll('.status-type');

            checkboxesInRow.forEach(function(cb) {
                if (cb !== checkbox) {
                cb.checked = false;
                }
            });
        });
    });
    });

    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDecline').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = @json($data['grade']->grade_id);
            var teacherId = @json($data['classTeacher']->teacher_id);
            var semester = @json($data['semester']);
            var role = @json(session('role'));
            
            var confirmDecline = document.getElementById('confirmDecline');
            if(role == 'admin' || role == 'superadmin'){
                confirmDecline.href = "{{ url('/' . session('role') . '/reports/reportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
            else if(role == 'teacher'){
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/reportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
        });
    });
</script>

@if(session('after_post_report_card2'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully submit report card semester 2.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

@if(session('after_decline_report_card'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully decline report card semester 2.'
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",,
        });
    </script>
@endif


@endsection
