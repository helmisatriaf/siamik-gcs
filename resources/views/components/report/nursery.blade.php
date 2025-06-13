@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="p-3 rounded-3 mb-4" style="background-color: #ffde9e;border-radius: 12px;">
          <ol class="breadcrumb mb-0" style="background-color: #fff3c0;">
            <li class="breadcrumb-item">Home</li>
            @if (session('role') == 'superadmin')
              <li class="breadcrumb-item"><a href="{{url('/superadmin/reports')}}">Report Card</a></li>
            @elseif (session('role') == 'admin')
            <li class="breadcrumb-item"><a href="{{url('/admin/reports')}}">Report Card</a></li>
            @elseif (session('role') == 'teacher')
            <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/report/class/teacher')}}">Report Card</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Detail Report Card</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="card" style="background-color:#ffde9e;border-radius:12px;">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    @if ($data['mid'] == 0)
                        <div class="col">
                            <p class="text-bold">Report Card Nursery Semester {{ $data['semester'] }}</p>
                            <table>
                                <tr>
                                    <td>Class</td>
                                    <td> : {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</td>
                                </tr>
                                <tr>
                                    <td>Class Teacher</td>
                                    <td> : {{ $data['grade']->teacher_name }}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td> :  {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
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
                    @else
                        <div class="col">
                            @if ($data['mid'] == 0.5 || $data['mid'] == 1.5)
                                <p class="text-bold">Mid Report Card Nursery Semester {{ $data['semester'] }}</p>
                            @endif
                            <table>
                                <tr>
                                    <td>Class</td>
                                    <td> : {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</td>
                                </tr>
                                <tr>
                                    <td>Class Teacher</td>
                                    <td> : {{ $data['grade']->teacher_name }}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td> :  {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
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
                    @endif
                </div>
                <div class="col-6 d-flex justify-content-end align-item-start text-end">
                    @if ($data['status'] == null)
                        <div class="row my-2">
                            <a class="btn btn-app bg-success" data-toggle="modal" data-target="#confirmModal">
                                <i class="fas fa-save"></i>
                                Submit
                            </a>
                        </div>
                    @elseif ($data['status']->status != null && $data['status']->status == 1)       
                        <div class="row my-2">
                            @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                            <a  class="btn btn-app bg-danger" data-toggle="modal" data-target="#modalDecline">
                                <i class="fas fa-cancel"></i>
                                Decline</a>
                            @endif
                        </div>  
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
                    <form id="confirmForm" method="POST" action={{route('actionTeacherPostReportCardNursery')}}>
                @endif
                @csrf
        
                @if (!empty($data['students']))
                
                <table class="table table-striped table-borderer" style=" width: 3000px;background-color: #fff3c0;">
                    @if ($data['status'] == null)   
                        <!-- JIKA DATA BELUM DI SUBMIT OLEH TEACHER  -->
                        <thead>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">S/N</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">First Name</th>
                                @foreach ($data['monthly'] as $monthly)
                                    <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">{{$monthly['name']}}</th>
                                @endforeach
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="2">Able to Understand</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="3">Able to Recognize</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Physical Skill / Motor Skill</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="2">Able Art and Craft</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="2">Chinese</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="4">Self and Social Awareness</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Remarks</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Songs</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Prayer</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Colour</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Number</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Object</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Body Movement</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Colouring</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Painting</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Songs</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Ability to recognize the objects</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Able to own up to mistakes</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Takes care of personal belongings and property</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Demonstrates importance of self-control</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Understands that having a temper is not acceptable behavior for problem-solving</th>
                            </tr>
                        </thead>
        
                        <!-- JIKA TEACHER MEMINTA EDIT SETELAH SUBMIT -->
                        @if(!empty($data['result']))
                            <tbody>
                                @foreach ($data['result'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="position: sticky; left: 0; background: #fff3c0; z-index: 2;">{{ $student['student_name'] }}</td>
                                    @foreach ($student['scores'] as $index => $score)
                                        @foreach (array_merge(['songs', 'prayer', 'colour', 'number', 'object', 'body_movement', 'colouring', 'painting', 'chinese_songs', 'ability_to_recognize_the_objects', 'able_to_own_up_to_mistakes', 'takes_care_of_personal_belongings_and_property', 'demonstrates_importance_of_self_control', 'management_emotional_problem_solving'], $data['monthlyTitle']) as $field)
                                            <td class="text-left ">
                                                <div class="form-check me-2 mx-2">
                                                    <input id="{{ $field }}_excellent_{{ $student['student_id'] }}" name="{{ $field }}[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="1" {{ $score[$field] == 1 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="{{ $field }}_excellent_{{ $student['student_id'] }}">
                                                        Excellent
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="{{ $field }}_satisfactory_{{ $student['student_id'] }}" name="{{ $field }}[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="2" {{ $score[$field] == 2 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="{{ $field }}_satisfactory_{{ $student['student_id'] }}">
                                                        Satisfactory
                                                    </label>
                                                </div>
                                                <div class="form-check me-2 mx-2">
                                                    <input id="{{ $field }}_weak_{{ $student['student_id'] }}" name="{{ $field }}[{{ $student['student_id'] }}]" class="form-check-input status-type" type="radio" value="3" {{ $score[$field] == 3 ? "checked" : "" }}>
                                                    <label class="form-check-label" for="{{ $field }}_weak_{{ $student['student_id'] }}">
                                                        Weak
                                                    </label>
                                                </div>
                                            </td>
        
                                        @endforeach
                                    @endforeach
                                    <td class="text-center">
                                        <input 
                                        name="remarks[{{ $student['student_id'] }}]" 
                                        type="text" 
                                        class="form-control" 
                                        autocomplete="off" 
                                        value="{{ $student['remarks'] }}">
                                    </td>
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">
                                </tr>
                                @endforeach
                            </tbody>
                            <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                            <input name="teacher_id" type="number" class="form-control d-none" id="class_teacher_id" value="{{ $data['classTeacher']->teacher_id }}">    
                            @if ($data['mid'] == 0)
                                <input name="semester" type="number" class="form-control d-none" value="{{ $data['semester'] }}">
                            @else
                                @if ($data['mid'] == 0.5)
                                    <input name="semester" type="number" class="form-control d-none" value="0.5">
                                @elseif ($data['mid'] == 1.5)
                                    <input name="semester" type="number" class="form-control d-none" value="1.5">
                                @endif
                            @endif
                        
                        <!-- JIKA TEACHER BELUM INPUT NILAI -->
                        @else 
                        <tbody>
                            @foreach ($data['students'] as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="position: sticky; left: 0; background: #fff3c0; z-index: 2;">{{ $student['name'] }}</td>
        
                                @foreach (array_merge(['songs', 'prayer', 'colour', 'number', 'object', 'body_movement', 'colouring', 'painting', 'chinese_songs', 'ability_to_recognize_the_objects', 'able_to_own_up_to_mistakes', 'takes_care_of_personal_belongings_and_property', 'demonstrates_importance_of_self_control', 'management_emotional_problem_solving']
                                , $data['monthlyTitle']) as $field)
                                
                                    <td class="text-left ">
                                        <div class="form-check me-2 mx-2">
                                            <input id="{{ $field }}_excellent_{{ $student['id'] }}" name="{{ $field }}[{{ $student['id'] }}]" class="form-check-input status-type" type="radio" value="1">
                                            <label class="form-check-label" for="{{ $field }}_excellent_{{ $student['id'] }}">
                                                Excellent
                                            </label>
                                        </div>
                                        <div class="form-check me-2 mx-2">
                                            <input id="{{ $field }}_satisfactory_{{ $student['id'] }}" name="{{ $field }}[{{ $student['id'] }}]" class="form-check-input status-type" type="radio" value="2">
                                            <label class="form-check-label" for="{{ $field }}_satisfactory_{{ $student['id'] }}">
                                                Satisfactory
                                            </label>
                                        </div>
                                        <div class="form-check me-2 mx-2">
                                            <input id="{{ $field }}_weak_{{ $student['id'] }}" name="{{ $field }}[{{ $student['id'] }}]" class="form-check-input status-type" type="radio" value="3">
                                            <label class="form-check-label" for="{{ $field }}_weak_{{ $student['id'] }}">
                                                Weak
                                            </label>
                                        </div>
                                    </td>
                                @endforeach
        
                                <td class="text-center">
                                    <input name="remarks[{{ $student['id'] }}]" type="text" class="form-control" autocomplete="off">
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
                        <thead>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">S/N</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">First Name</th>
                                @foreach ($data['monthly'] as $monthly)
                                    <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">{{$monthly['name']}}</th>
                                @endforeach
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="2">Able to Understand</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="3">Able to Recognize</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Physical Skill / Motor Skill</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="2">Able Art and Craft</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="2">Chinese</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" colspan="4">Self and Social Awareness</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;" rowspan="2">Remarks</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Songs</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Prayer</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Colour</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Number</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Object</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Body Movement</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Colouring</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Painting</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Songs</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Ability to recognize the objects</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Able to own up to mistakes</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Takes care of personal belongings and property</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Demonstrates importance of self-control</th>
                                <th class="text-center" style="vertical-align : middle;text-align:center;">Understands that having a temper is not acceptable behavior for problem-solving</th>
                            </tr>
                        </thead>
        
                        <tbody>
                        @if(!empty($data['result']))
                            @foreach ($data['result'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="position: sticky; left: 0; background: #fff3c0; z-index: 2;">{{ $student['student_name'] }}</td>
                                    @foreach ($student['scores'] as $index => $score)
                                        @foreach (array_merge(['songs', 'prayer', 'colour', 'number', 'object', 'body_movement', 'colouring', 'painting', 'chinese_songs', 'ability_to_recognize_the_objects', 'able_to_own_up_to_mistakes', 'takes_care_of_personal_belongings_and_property', 'demonstrates_importance_of_self_control', 'management_emotional_problem_solving']
                                        , $data['monthlyTitle']) as $field)
                                            <td class="text-center">
                                                @if ($score[$field] == 1)
                                                    Excellent
                                                @elseif ($score[$field] == 2)
                                                    Satisfactory
                                                @elseif ($score[$field] == 3)
                                                    Weak
                                                @endif
                                            </td>
                                        @endforeach
        
                                        <td class="text-justify">
                                            {{ $student['remarks'] }}
                                        </td>
        
                                        @if ($data['status'] !== null)
                                            @if ($data['mid'] == 0)
                                                <td>
                                                    <a class="btn btn-primary btn"
                                                        href="{{url('teacher/dashboard/report/nursery/print') . '/' . $student['student_id']}}">
                                                        Print
                                                    </a>
                                                </td>
                                            @else
                                                <td>
                                                    <a class="btn btn-primary btn"
                                                        href="{{url('teacher/dashboard/report/mid/nursery/print') . '/' . $student['student_id']}}">
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
                Are you sure you want to submit report card nursery?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAccScoring">Yes, Acc</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Report Card {{ $data['grade']->grade_name }} Semester {{session('semester')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline report card {{ $data['grade']->grade_name }} semester {{session('semester')}} ?</div>
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
                timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
                showConfirmButton: false // Sembunyikan tombol "OK",
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
            var  mid = @json($data['mid']);

            // console.log("id=", id, "teacher=", teacherId, "semester=", semester);
            var confirmDecline = document.getElementById('confirmDecline');
            if(mid == 0){
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/reportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;     
            }
            else{
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/midreportCard/decline') }}/" + id + "/" + teacherId + "/" + semester;     
            }
        });
    });
</script>

@if(session('after_post_report_card_nursery'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully post report card nursery in the database.',
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
            text: 'Successfully decline report card toddler.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif


@endsection
