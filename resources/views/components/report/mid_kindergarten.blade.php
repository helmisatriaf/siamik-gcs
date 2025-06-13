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
            <li class="breadcrumb-item active" aria-current="page">Mid Report Card</li>
          </ol>
        </nav>
      </div>
    </div>


    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    @if ($data['mid'] == 0)
                        
                        <div class="col">
                            <p class="text-bold">Mid Report Card Kindergarten Semester {{ $data['semester'] }}</p>
                            <table>
                                <tr>
                                    <td>Class Teacher</td>
                                    <td> : {{$data['grade']->teacher_name}}</td>
                                </tr>
                                <tr>
                                    <td>Class</td>
                                    <td> : {{$data['grade']->grade_name}} - {{$data['grade']->grade_class}}</td>
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
                                        Already Submitted in {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    @else
                        <div class="col">
                            <table>
                                {{-- <tr>
                                    <td><img src="{{asset('images/logo-school.png')}}" alt="logo sekolah" style="width: 100px;"></td>
                                    <td>
                                        <h3 class="text-bold">SMP Yogyakarta</h3>
                                        <p>Jl. Affandi, Gejayan, Yogyakarta</p>
                                        <p>0274-123456</p>
                                    </td>
                                </tr> --}}
                            </table>
                            @if ($data['mid'] == 0.5 || $data['mid'] == 1.5)
                                <p class="text-bold">Mid Report Card Kindergarten Semester {{ $data['semester'] }}</p>
                            @endif
                            <table>
                                <tr>
                                    <td>Class Teacher</td>
                                    <td> : {{$data['grade']->teacher_name}}</td>
                                </tr>
                                <tr>
                                    <td>Class</td>
                                    <td> : {{$data['grade']->grade_name}} - {{$data['grade']->grade_class}}</td>
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
                                        Already Submitted in {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    @endif
                </div>
                <div class="col-6 d-flex justify-content-end align-items-start text-end">
                    @if ($data['status'] == null)
                        <a class="btn btn-app bg-success" data-toggle="modal" data-target="#confirmModal">
                            <i class="fas fa-save"></i>
                            Submit</a>
                    @elseif ($data['status']->status != null && $data['status']->status == 1)
                        <a class="btn btn-app bg-danger" data-toggle="modal" data-target="#modalDecline">
                            <i class="fas fa-cancel"></i>
                            Decline</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            <div style="overflow-x: auto;">
                @if (session('role') == 'superadmin')
                    <form id="confirmForm"  method="POST">
                @elseif (session('role') == 'admin')
                    <form id="confirmForm" method="POST">
                @elseif (session('role') == 'teacher')
                    <form id="confirmForm" method="POST" action={{route('actionTeacherPostMidReportCardKindergarten')}}>
                @endif
                @csrf
        
                @if (!empty($data['students']))
                <div style="max-height: 500px; overflow-y:auto;"></div>
                <table class="table table-striped table-bordered bg-white" style=" width: 3000px;overflow-x: auto;">
                    @if ($data['status'] == null)
                        <!-- JIKA DATA BELUM DI SUBMIT OLEH TEACHER  -->
                        <thead class="bg-light" style="position: sticky; top: 0; z-index: 100;">
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">S/N</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">First Name</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="{{count($data['title'])+16}}">Subject / Attitude</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Remarks</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Brain Gym</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Cursive Writing</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Dictation</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">English Language</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Mandarin Language</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Writing Skill</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Reading SKill</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Phonic</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Science</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Art and Craft</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Character Building</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Physical Education</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Able to sit quietly</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Willingness to listen</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Willingness to work</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Willingness to sing</th>
                                @foreach ($data['monthly'] as $monthly)
                                <th class="text-center" style="vertical-align : middle;text-align:center;">{{$monthly->name}}</th>
                                @endforeach
                            </tr>
                        </thead>
        
                        <!-- JIKA TEACHER MEMINTA EDIT SETELAH SUBMIT -->
                        @if(!empty($data['result']))
                            <tbody>
                                @foreach ($data['result'] as $student)
                                <tr>
                                    <td class="bg-white">{{ $loop->iteration }}</td>
                                    <td class="bg-white" style="position: sticky;left: 0;z-index: 100;">{{ $student['student_name'] }}</td>
                                    @foreach ($student['scores'] as $index => $score)
                                        @foreach (array_merge([
                                            'brain_gym', 'cursive_writing', 'dictation',
                                            'english_language', 'mandarin_language', 'writing_skill', 
                                            'reading_skill', 'phonic', 'science', 'art_and_craft', 'character_building',
                                            'physical_education', 'able_to_sit_quietly', 'willingness_to_listen', 
                                            'willingness_to_work', 'willingness_to_sing',
                                        ], $data['title']) as $field)
                                            <td class="text-left text-xs">
                                                <div class="form-check me-2 mx-2">
                                                    <input id="{{ $field }}_excellent_{{ $student['student_id'] }}" name="{{ $field }}[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="1" {{ $score[$field] == 1 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="{{ $field }}_excellent_{{ $student['student_id'] }}">
                                                        Excellent
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="{{ $field }}_good_{{ $student['student_id'] }}" name="{{ $field }}[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="2" {{ $score[$field] == 2 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="{{ $field }}_good_{{ $student['student_id'] }}">
                                                        Good
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="{{ $field }}_satisfactory_{{ $student['student_id'] }}" name="{{ $field }}[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="3" {{ $score[$field] == 3 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="{{ $field }}_satisfactory_{{ $student['student_id'] }}">
                                                        Satisfactory
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="{{ $field }}_weak_{{ $student['student_id'] }}" name="{{ $field }}[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="4" {{ $score[$field] == 4 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="{{ $field }}_weak_{{ $student['student_id'] }}">
                                                        Needs Improvement
                                                    </label>
                                                </div>
                                            </td>
                                        @endforeach
                                    @endforeach
                                    <td class="text-center">
                                        <textarea name="remarks[{{ $student['student_id'] }}]" class="form-control" autocomplete="off" rows="4" cols="40" required>
                                            {{ $student['remarks'] }}
                                        </textarea>
                                    </td>
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">
                                </tr>
                                @endforeach
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
                                <td style="position: sticky;left: 0;z-index: 100;">{{ $student['name'] }}</td>
        
                                @foreach (array_merge([
                                    'brain_gym', 'cursive_writing', 'dictation',
                                    'english_language', 'mandarin_language', 'writing_skill', 
                                    'reading_skill', 'phonic', 'science', 'art_and_craft', 'character_building',
                                    'physical_education', 'able_to_sit_quietly', 'willingness_to_listen', 
                                    'willingness_to_work', 'willingness_to_sing',
                                ], $data['title']) as $field)
                                    <td class="text-left text-xs">
                                        <div class="form-check me-2 mx-2">
                                            <input id="{{ $field }}_excellent_{{ $student['id'] }}" name="{{ $field }}[{{ $student['id'] }}]" class="form-check-input status-type" type="radio" value="1">
                                            <label class="form-check-label" for="{{ $field }}_excellent_{{ $student['id'] }}">
                                                Excellent
                                            </label>
                                        </div>
                                        <div class="form-check me-2 mx-2">
                                            <input id="{{ $field }}_good_{{ $student['id'] }}" name="{{ $field }}[{{ $student['id'] }}]" class="form-check-input status-type" type="radio" value="2">
                                            <label class="form-check-label" for="{{ $field }}_good_{{ $student['id'] }}">
                                                Good
                                            </label>
                                        </div>
                                        <div class="form-check me-2 mx-2">
                                            <input id="{{ $field }}_satisfactory_{{ $student['id'] }}" name="{{ $field }}[{{ $student['id'] }}]" class="form-check-input status-type" type="radio" value="3">
                                            <label class="form-check-label" for="{{ $field }}_satisfactory_{{ $student['id'] }}">
                                                Satisfactory
                                            </label>
                                        </div>
                                        <div class="form-check me-2 mx-2">
                                            <input id="{{ $field }}_weak_{{ $student['id'] }}" name="{{ $field }}[{{ $student['id'] }}]" class="form-check-input status-type" type="radio" value="4">
                                            <label class="form-check-label" for="{{ $field }}_weak_{{ $student['id'] }}">
                                                Need Improvement
                                            </label>
                                        </div>
                                    </td>
                                @endforeach
        
                                <td>
                                    <textarea name="remarks[{{ $student['id'] }}]" autocomplete="off" class="required-input"  rows="4" cols="40" required></textarea>
                                </td>
                                <input name="student_id[]" type="number" class="form-control d-none" value="{{ $student['id'] }}">
                            </tr>
                            @endforeach
                        </tbody>
                        <input name="grade_id" type="number" class="form-control d-none" value="{{ $data['grade']->grade_id }}">
                        <input name="teacher_id" type="number" class="form-control d-none" value="{{ $data['classTeacher']->teacher_id }}">
                        @if ($data['mid'] == 0)
                                <input name="semester" type="number" class="form-control d-none" value="{{ $data['semester'] }}">
                            @else
                                @if ($data['mid'] == 0.5)
                                    <input name="semester" type="number" class="form-control d-none" value="0.5">
                                @elseif ($data['mid'] == 1.5)
                                    <input name="semester" type="number" class="form-control d-none" value="1.5">
                                @endif
                            @endif
                        @endif
        
        
                    <!-- JIKA DATA SUDAH DI SUBMiT OLEH TEACHER -->
                    @elseif ($data['status']->status != null && $data['status']->status == 1)
                        <thead class="bg-light" style="position: sticky;top: 0;z-index: 100;">
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">S/N</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">First Name</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="{{count($data['monthly'])+16}}">Subject / Attitude</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Remarks</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Brain Gym</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Cursive Writing</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Dictation</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">English Language</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Mandarin Language</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Writing Skill</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Reading SKill</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Phonic</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Science</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Art and Craft</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Character Building</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Physical Education</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Able to sit quietly</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Willingness to listen</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Willingness to work</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Willingness to sing</th>
                                @foreach ($data['monthly'] as $monthly)
                                <th class="text-center" style="vertical-align : middle;text-align:center;">{{$monthly->name}}</th>
                                @endforeach
                            </tr>
                        </thead>
        
                        <tbody>
                        @if(!empty($data['result']))
                            @foreach ($data['result'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student['student_name'] }}</td>
                                    @foreach ($student['scores'] as $index => $score)
                                        @foreach (array_merge([
                                            'brain_gym', 'cursive_writing', 'dictation',
                                            'english_language', 'mandarin_language', 'writing_skill', 
                                            'reading_skill', 'phonic', 'science', 'art_and_craft', 'character_building',
                                            'physical_education', 'able_to_sit_quietly', 'willingness_to_listen', 
                                            'willingness_to_work', 'willingness_to_sing',
                                        ], $data['title']) as $field)
                                            <td class="text-center">
                                                @switch($score[$field])
                                                    @case(1)
                                                        Excellent
                                                        @break
                                                    @case(2)
                                                        Good
                                                        @break
                                                    @case(3)    
                                                        Satisfactory
                                                        @break
                                                    @case(4)
                                                        Needs Improvement
                                                        @break
                                                    @default
                                                @endswitch
                                            </td>
                                        @endforeach
        
                                        <td class="text-justify">
                                            {{ $student['remarks'] }}
                                        </td>
        
                                        @if ($data['status'] !== null)
                                            @if ($data['mid'] == 0)
                                                <td>
                                                    <a class="btn btn-primary btn"
                                                        href="{{url('teacher/dashboard/report/kindergarten/print') . '/' . $student['student_id']}}">
                                                        Print
                                                    </a>
                                                </td>
                                            @else
                                                <td>
                                                    <a class="btn btn-primary btn"
                                                        href="{{url('teacher/dashboard/report/mid/kindergarten/print') . '/' . $student['student_id']}}">
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
                </div>
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
                <h5 class="modal-title" id="confirmModalLabel">Confirm Submit Report Card</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit report card kindergarten?
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
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Report Card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} Semester {{session('semester')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline report card {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} semester {{session('semester')}} ?</div>
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
    document.getElementById('confirmAccScoring').addEventListener('click', function() {
        // Mengambil semua input komentar
        var comments = document.querySelectorAll('input[name="remarks[]"]');
        var allFilled = true;
        
        // Memeriksa setiap komentar apakah kosong atau tidak
        comments.forEach(function(comment) {
            if (comment.value.trim() === '') {
                allFilled = false;
                // Menambahkan kelas untuk memberikan highlight pada input yang kosong
                comment.classList.add('is-invalid');
            } else {
                // Menghapus kelas jika input tidak kosong
                comment.classList.remove('is-invalid');
            }
        });
        
        // Mengambil semua baris siswa
        var studentRows = document.querySelectorAll('tbody tr');
        
        // Memeriksa setiap baris siswa apakah semua radio button tercentang
        studentRows.forEach(function(row) {
            var radiosInRow = row.querySelectorAll('.status-type');
            var groups = {};
            var rowFilled = true;

            radiosInRow.forEach(function(radio) {
                var name = radio.name;
                if (!groups[name]) {
                    groups[name] = false;
                }
                if (radio.checked) {
                    groups[name] = true;
                }
            });

            for (var key in groups) {
                if (!groups[key]) {
                    rowFilled = false;
                    break;
                }
            }

            if (!rowFilled) {
                allFilled = false;
                // Menambahkan kelas untuk memberikan highlight pada input yang belum tercentang
                row.classList.add('is-invalid');
            } else {
                // Menghapus kelas jika semua input tercentang
                row.classList.remove('is-invalid');
            }
        });
        
        // Jika semua komentar dan radio button terisi, submit form
        if (allFilled) {
            document.getElementById('confirmForm').submit();
        } else {
            // Menampilkan pesan peringatan
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'All comments and scores must be filled before submitting the form!',
            });
        }
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

            // console.log("id=", id, "teacher=", teacherId, "semester=", semester);
            var confirmDecline = document.getElementById('confirmDecline');
            if(role == 'admin' || role == 'superadmin'){
                confirmDecline.href = "{{ url('/' . session('role') . '/reports/midreportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
            else if(role == 'teacher'){
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/midreportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;
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
</script>

@if(session('after_post_mid_report'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully post mid report card kindergarten in the database.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

@if(session('after_decline_mid_report'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully decline report card kindergarten.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif


@endsection
