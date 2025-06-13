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
            <li class="breadcrumb-item active" aria-current="page">Detail Mid Report Card</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="card" style="height:70vh;overflow-y: auto;">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <p class="text-bold">Mid Report Card Semester </p>
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
                        @if ($data['status'] == null)
                        @elseif ($data['status']->status != null && $data['status']->status == 1)  
                        <tr>
                            <td>Status</td>
                            <td> : Already Submitted in {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}</td>
                        </tr>  
                        @endif   
                    </table>
                </div>
                <div class="col-6 d-flex justify-content-end align-item-start text-end">
                    @if ($data['status'] == null)
                        <a class="btn btn-app bg-success" data-toggle="modal" data-target="#confirmModal">
                            <i class="fas fa-save"></i>
                            Submit
                        </a>
                    @elseif ($data['status']->status != null && $data['status']->status == 1) 
                        @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                        <a  class="btn btn-app bg-danger" data-toggle="modal" data-target="#modalDecline">
                            <i class="fas fa-cancel"></i>
                            Decline</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('role') == 'superadmin')
                <form id="confirmForm"  method="POST" action={{route('actionPostMidReportCard')}}>
            @elseif (session('role') == 'admin')
                <form id="confirmForm" method="POST" action={{route('actionPostMidReportCard')}}>
            @elseif (session('role') == 'teacher')
                <form id="confirmForm" method="POST" action={{route('actionTeacherPostMidReportCard')}}>
            @endif
            @csrf
            
           
            <div id="scroll-top" style="overflow-x: auto;position: sticky; top: 0; z-index: 99;">
                <div style="width: 2000px; height: 1px;"></div> <!-- dummy scroll -->
            </div>
            <div id="scroll-bottom" style="overflow-x: auto;">
        
                @if (!empty($data['students']))
                
                <table class="table table-striped table-bordered bg-white" style="width: 2000px;">
                    @if ($data['status'] == null)
                        <!-- JIKA DATA BELUM DI SUBMIT OLEH TEACHER  -->
                        <thead>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;width:2%;">S/N</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;width:15%;">First Name</th>
                                
                                @foreach ($data['monthlyActivities'] as $ma)
                                <th class="text-center" style="vertical-align : middle;text-align:center;">{{$ma->name}}</th>
                                @endforeach
        
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Critical Thinking</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Cognitive Skills</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Life Skills</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Learning Skills</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Social and Emotional Development</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;width:3%;">View</th>
                            </tr>
                        </thead>
        
                        <!-- JIKA TEACHER MEMINTA EDIT SETELAH SUBMIT -->
                        @if(!empty($data['result']))
                            <tbody>
                                @foreach ($data['result'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="position: sticky; left: 0; background: #ffffff; z-index: 99;">{{ $student['name'] }}</td>
                                    
                                    @foreach ($student['monthly_activities'] as $ma)
                                    <td class="text-center">
                                        <input name="{{strtolower($ma['activity_name'])}}[]" min="0" max="100" type="number" class="form-control required-input" value="{{$ma['score']}}" autocomplete="off" required>
                                    </td>
                                    @endforeach
        
                                    {{-- <td class="text-center"><input name="remarks[]" type="text" class="form-control" value="{{ $student['remarks'] }}" autocomplete="off"></td> --}}
                                    <td><input name="critical_thinking[]" type="text" class="form-control" value="{{ $student['critical_thinking'] }}" autocomplete="off" maxlength="255" oninput="validateCommentLength(this)"></td>
                                    <td><input name="cognitive_skills[]" type="text" class="form-control" value="{{ $student['cognitive_skills'] }}" autocomplete="off" maxlength="255" oninput="validateCommentLength(this)"></td>
                                    <td><input name="life_skills[]" type="text" class="form-control" value="{{ $student['life_skills'] }}" autocomplete="off" maxlength="255" oninput="validateCommentLength(this)"></td>
                                    <td><input name="learning_skills[]" type="text" class="form-control" value="{{ $student['learning_skills'] }}" autocomplete="off" maxlength="255" oninput="validateCommentLength(this)"></td>
                                    <td><input name="social_and_emotional_development[]" type="text" class="form-control" value="{{ $student['social_and_emotional_development'] }}" autocomplete="off" maxlength="255" oninput="validateCommentLength(this)"></td>
                                    
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">
                                    <td>
                                        <a class="btn btn-primary btn"
                                            href="{{url('teacher/dashboard/midreport/print') . '/' . $student['id']}}">
                                            View
                                        </a>
                                    </td>
                                @endforeach
                                </tr>
                            </tbody>
                            <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                            <input name="teacher_id" type="number" class="form-control d-none" id="class_teacher_id" value="{{ $data['classTeacher']->teacher_id }}">    
                            <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">
                        
                        <!-- JIKA TEACHER BELUM INPUT NILAI -->
                        @else 
                            <tbody>
                                @foreach ($data['students'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="position: sticky; left: 0; background: #ffffff; z-index: 99;">{{ $student['name'] }}</td>
                                    
                                    @foreach ($data['monthlyActivities'] as $ma)
                                    <td class="text-center">
                                        <input name="{{strtolower($ma->name)}}[]" min="0" max="100" type="number" class="form-control required-input" value="" autocomplete="off" required>
                                    </td>
                                    @endforeach
        
                                    <td><textarea name="critical_thinking[]" autocomplete="off" class="required-input"  maxlength="255" oninput="checkCharacterLimit(this)" required></textarea>
                                        {{-- <input 
                                            name="critical_thinking[]" 
                                            type="text" 
                                            class="form-control" 
                                            id="critical_thinking-{{ $student['id'] }}" 
                                            placeholder="Maksimal 255 Karakter"  
                                            maxlength="255" 
                                            oninput="validateCommentLength(this)" 
                                            autocomplete="off" 
                                            required
                                        > --}}
                                    </td>
                                    <td><textarea name="cognitive_skills[]" autocomplete="off" class="required-input"  maxlength="255" oninput="checkCharacterLimit(this)" required></textarea></td>
                                    <td><textarea name="life_skills[]" autocomplete="off" class="required-input"  maxlength="255" oninput="checkCharacterLimit(this)" required></textarea></td>
                                    <td><textarea name="learning_skills[]" autocomplete="off" class="required-input"  maxlength="255" oninput="checkCharacterLimit(this)" required></textarea></td>
                                    <td><textarea name="social_and_emotional_development[]" autocomplete="off" class="required-input"  maxlength="255" oninput="checkCharacterLimit(this)" required></textarea></td>
                                    <td>
                                        <a class="btn btn-primary btn"
                                            href="{{url('teacher/dashboard/midreport/print') . '/' . $student['id']}}">
                                            View
                                        </a>
                                    </td>
                    
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['id'] }}">
                                </tr>
                                @endforeach
                            </tbody>
                            <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                            <input name="teacher_id" type="number" class="form-control d-none" id="class_teacher_id" value="{{ $data['classTeacher']->teacher_id }}">    
                            <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">
                        @endif
        
        
                    <!-- JIKA DATA SUDAH DI SUBMiT OLEH TEACHER -->
                    @elseif ($data['status']->status != null && $data['status']->status == 1)
                        <thead>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;width:2%;">S/N</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;width:15%;">First Name</th>
                                
                                @foreach ($data['monthlyActivities'] as $ma)
                                    <th class="text-center" style="vertical-align : middle;text-align:center;">{{$ma->name}}</th>
                                @endforeach
        
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Critical Thinking</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Cognitive Skills</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Life Skills</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Learning Skills</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Social and Emotional Development</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;width:3%;">View</th>
                            </tr>
                        </thead>
        
                        <tbody>
                        @if(!empty($data['result']))
                            @foreach ($data['result'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="position: sticky; left: 0; background: #ffffff; z-index: 99;">{{ $student['name'] }}</td>
                                    @foreach ($student['monthly_activities'] as $ma)
                                        <td>{{$ma['score']}}</td>
                                    @endforeach
                                    <td>{{ $student['critical_thinking'] }}</td>
                                    <td>{{ $student['cognitive_skills'] }}</td>
                                    <td>{{ $student['life_skills'] }}</td>
                                    <td>{{ $student['learning_skills'] }}</td>
                                    <td>{{ $student['social_and_emotional_development'] }}</td>
        
        
        
                                    @if ($data['status'] !== null)
                                        @if (session('role') == "superadmin" || session('role') == "admin")
                                            <td>
                                                <a class="btn btn-primary btn"
                                                    href="{{url(session('role') . '/reports/midreport/print') . '/' . $student['student_id']}}">
                                                    View
                                                </a>
                                            </td>
                                        @elseif (session('role') == "teacher")
                                            <td>
                                                <a class="btn btn-primary btn"
                                                    href="{{url('teacher/dashboard/midreport/print') . '/' . $student['student_id']}}">
                                                    View
                                                </a>
                                            </td>
                                        @endif
                                    @endif
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Submit Mid Report Card</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit mid report card?
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
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Report Card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline mid report card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}?</div>
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
    document.querySelectorAll('input').forEach(function(input) {
        input.addEventListener('input', function(event) {
            let value = parseInt(input.value, 10);
            if (value < 0 || value > 100) {
                input.value = '';
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `Enter a number between 0 and 100.`,
                    confirmButtonText: 'Oke',
                });
            }
        });
    });

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
        $('#modalDecline').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = @json($data['grade']->grade_id);
            var teacherId = @json($data['classTeacher']->teacher_id);
            var semester = @json($data['semester']);
            var role = @json(session('role'));

            console.log("id=", id, "teacher=", teacherId, "semester=", semester);
            var confirmDecline = document.getElementById('confirmDecline');
            if(role == 'admin' || role == 'superadmin'){
                confirmDecline.href = "{{ url('/' . session('role') . '/reports/midreportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
            else if(role == 'teacher'){
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/midreportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
        });
    });
</script>

@if(session('after_post_mid_report_card'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully submit mid report card in the database.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

@if(session('after_decline_mid_report_card'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully decline mid report card semester',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif


@endsection
