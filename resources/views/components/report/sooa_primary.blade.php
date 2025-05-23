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
                    <p class="text-bold">Summary of Academic Assessment</p>
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
                <form id="confirmForm"  method="POST" action={{route('actionPostScoringSooaPrimary')}}>
            @elseif (session('role') == 'admin')
                <form id="confirmForm" method="POST" action={{route('actionAdminPostScoringSooaPrimary')}}>
            @elseif (session('role') == 'teacher')
                <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringSooaPrimary')}}>
            @endif
            @csrf
            
            <div style="overflow-x: auto;">
        
                <table class="table table-striped table-bordered bg-white" style=" width: 2000px;">
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
        
                    <tbody>
                    @if (!empty($data['students']))
                        @foreach ($data['students'] as $student)
        
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student['student_name'] }}</td>
        
                            @if ($data['status'] == null)
                                @foreach ($student['scores'] as $index => $score)
        
                                    <!-- ACADEMIC -->
                                    <td class="text-center">{{ $score['academic'] }}</td>
                                    <td class="text-center">{{ $score['grades_academic'] }}</td>
        
                                    <!-- Choice -->
                                    <td class="text-center">   
                                        <input name="choice[]" min="0" max="100" type="number" class="form-control required-input " id="choice" value="{{ $score['choice'] ? : '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_choice'] }}</td>
        
                                    <!-- Language & Art -->
                                    <td class="text-center">
                                            <input name="language_and_art[]" min="0" max="100" type="number" class="form-control required-input " id="language_and_art" value="{{ $score['language_and_art'] ? : '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_language_and_art'] ?? '' }}</td>
        
                                    <!-- Self-Development -->
                                    <td class="text-center">
                                        <input name="self_development[]" min="0" max="100" type="number" class="form-control required-input " id="self_development" value="{{ $score['self_development'] ?: '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>
        
                                    <!-- ECA Aver -->
                                    <td class="text-center">
                                        {{$score['eca_aver']}}
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>
        
                                    <!-- Behavior -->
                                    <td class="text-center">
                                        <input name="behavior[]" min="0" max="100" type="number" class="form-control required-input " id="behavior" value="{{ $score['behavior'] ?: '' }}" autocomplete="off" required>
                                    </td>
                                    <td class="text-center">{{ $score['grades_behavior'] ?? '' }}</td>
        
                                    <!-- Attendance -->
                                    <td class="text-center">{{ $score['attendance'] }}</td>
                                    <td class="text-center">{{ $score['grades_attendance'] }}</td>
        
                                    <!-- Participation -->
                                    <td class="text-center">
                                        <input name="participation[]"  min="0" max="100" type="number" class="form-control required-input " id="participation"value="{{ $score['participation'] ?: '' }}" autocomplete="off" required></td>
                                    <td class="text-center">{{ $score['grades_participation'] }}</td>
        
                                    <input name="student_id[]" type="number" class="form-control required-input  d-none" id="student_id" value="{{ $student['student_id'] }}">
                                                    
                                    <td class="text-center">{{ $score['final_score'] }}</td>
                                    <td class="text-center">{{ $score['grades_final_score'] }}</td>
                                @endforeach
                            @elseif ($data['status']->status != null && $data['status']->status == 1) 
                            @foreach ($student['scores'] as $index => $score)
        
                                    <!-- ACADEMIC -->
                                    <td class="text-center">{{ $score['academic'] }}</td>
                                    <td class="text-center">{{ $score['grades_academic'] }}</td>
        
                                    <!-- Choice -->
                                    <td class="text-center">
                                        @if(isset($score['choice']))
                                            {{ $score['choice'] }}
                                        @else
                                            <input name="choice[]" min="0" max="100" type="number" class="form-control required-input " id="choice" value="{{ $score['choice'] ? : '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_choice'] }}</td>
        
                                    <!-- Language & Art -->
                                    <td class="text-center">
                                        @if(isset($score['language_and_art']))
                                            {{ $score['language_and_art'] }}
                                        @else
                                            <input name="language_and_art[]" min="0" max="100" type="number" class="form-control required-input " id="language_and_art" value="{{ $score['language_and_art'] ? : '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_language_and_art'] ?? '' }}</td>
        
                                    <!-- Self-Development -->
                                    <td class="text-center">
                                        @if(isset($score['self_development']))
                                            {{ $score['self_development'] }}
                                        @else
                                            <input name="self_development[]" min="0" max="100" type="number" class="form-control required-input " id="self_development" value="{{ $score['self_development'] ?: '' }}" autocomplete="off" required>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_self_development'] ?? '' }}</td>
        
                                    <!-- ECA Aver -->
                                    <td class="text-center">
                                        @if(isset($score['eca_aver']))
                                            {{ $score['eca_aver'] }}
                                        @else
                                            {{ $score['eca_aver'] }}    
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $score['grades_eca_aver'] ?? '' }}</td>
        
                                    <!-- Behavior -->
                                    <td class="text-center">
                                        @if(isset($score['behavior']))
                                            {{ $score['behavior'] }}
                                        @else
                                            <input name="behavior[]" min="0" max="100" type="number" class="form-control required-input " id="behavior" value="{{ $score['behavior'] ?: '' }}" autocomplete="off" required>
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
                                            <input name="participation[]"  min="0" max="100" type="number" class="form-control required-input " id="participation"value="{{ $score['participation'] ?: '' }}" autocomplete="off" required></td>
                                        @endif
                                    <td class="text-center">{{ $score['grades_participation'] }}</td>
        
                                    <input name="student_id[]" type="number" class="form-control required-input  d-none" id="student_id" value="{{ $student['student_id'] }}">
                                                    
                                    <td class="text-center">{{ $score['final_score'] }}</td>
                                    <td class="text-center">{{ $score['grades_final_score'] }}</td>
                                @endforeach
                                
                            @endif
                            
                            <td class="text-center">{{ $student['ranking'] }}</td>
                        @endforeach
                        </tr>
                        <input name="grade_id" type="number" class="form-control required-input  d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
                        <input name="class_teacher" type="number" class="form-control required-input  d-none" id="class_teacher" value="{{ $data['classTeacher']->teacher_id }}">    
                        <input name="semester" type="number" class="form-control required-input  d-none" id="semester" value="{{ $data['semester'] }}">    
                    @else
                        <tr>
                            <td colspan="33" class="text-center text-danger">
                                Data Empty
                            </td>    
                        </tr>
                    @endif
                    </tbody>
                </table>
        
                </form>
            </div>
        </div>
    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Submit SOOA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                Are you sure want to submit sooa ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSooaScoring">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Decline SOOA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline sooa ?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn" id="confirmDecline">Yes</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('input').forEach(function(input) {
        input.addEventListener('input', function(event) {
            let value = parseInt(input.value, 10);
            if (value < 0 || value > 100) {
                input.value = '';
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `Please enter number 0 between 100.`,
                    confirmButtonText: 'Oke',
                });
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

            console.log("id=", id, "teacher=", teacherId, "semester=", semester);
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

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_post_sooa'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully submit SOOA.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif


@if(session('after_decline_sooa'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully decline SOOA.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

@endsection
