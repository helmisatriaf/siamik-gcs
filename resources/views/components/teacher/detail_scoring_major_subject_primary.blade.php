@extends('layouts.admin.master')
@section('content')

<style>
    .tooltip-inner {
        max-width: 250px; /* Adjust width for tooltip content */
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid" id="anjayani">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="p-3 mb-4" style="background-color: #ffde9e;border-radius:12px;">
                <ol class="breadcrumb mb-0"  style="background-color: #fff3c0;">
                    <li class="breadcrumb-item">Home</li>
                    @if (session('role') == 'superadmin')
                        <li class="breadcrumb-item"><a href="{{url('/superadmin/reports')}}">Report</a></li>
                    @elseif (session('role') == 'admin')
                        <li class="breadcrumb-item"><a href="{{url('/admin/reports')}}">Report</a></li>
                    @elseif (session('role') == 'teacher')
                        <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/report/subject/teacher')}}">Report</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">Detail Report {{ $data['subject']->subject_name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card" style="height:70vh;overflow-y: auto;border-radius:12px;">
        <div class="card-header position-relative">
            <div class="row">
                <div class="col-6">
                    <p class="text-bold">Major Subject Assessment</p>
                    <table>
                        <tr>
                            <td>Subject</td>
                            <td> : {{ $data['subject']->subject_name }}</td>
                        </tr>
                        <tr>
                            <td>Subject Teacher</td>
                            <td> : {{ $data['subjectTeacher']->teacher_name }}</td>
                        </tr>
                        <tr>
                            <td>Class</td>
                            <td> : {{ $data['grade']->name }} - {{ $data['grade']->class }}</td>
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
                            <td> : <span class="text-bold">
                                Already Submitted on {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}</td>
                            </span> 
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-start text-end"> <!-- Menggeser ke kanan -->
                    @if ($data['status'] == null)
                        @if (!empty($data['students']))
                            <a type="button" class="btn btn-app bg-success position-absolute top-0 end-0 m-3" 
                                data-toggle="modal" data-target="#confirmModal">
                                <i class="fas fa-save"></i> Submit
                            </a>
                        @endif
                    @elseif ($data['status']->status != null && $data['status']->status == 1)  
                        @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                            <a type="submit" class="btn btn-app bg-danger position-absolute top-0 end-0 m-3" data-toggle="modal" data-target="#modalDecline">
                                <i class="fas fa-cancel"></i> Decline
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            <div id="scroll-top" style="overflow-x: auto;position: sticky; top: 0; z-index: 100;">
                <div style="width: 3000px; height: 1px;"></div> <!-- dummy scroll -->
            </div>
            <div id="scroll-bottom" style="overflow-x: auto;">
                @if (session('role') == 'superadmin')
                    <form id="confirmForm" method="POST" action={{route('actionPostScoringMajorPrimary')}}>
                @elseif (session('role') == 'admin')
                    <form id="confirmForm" method="POST" action={{route('actionAdminCreateExam')}}>
                @elseif (session('role') == 'teacher')
                    <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringMajorPrimary')}}>
                @endif
                @csrf
            
                <table class="table table-striped table-bordered" style="width:3000px;">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                            <th colspan="{{ $data['grade']->total_homework + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Homework</th>
                            <th colspan="{{ $data['grade']->total_exercise + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Exercise</th>
                            <th colspan="{{ $data['grade']->total_participation + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Participation</th>
                            <th class="text-center" style="vertical-align : middle;text-align:center;">R (30%)</th>
                            <th colspan="{{ $data['grade']->total_quiz + 1 }}" class="text-center" style="vertical-align : middle;text-align:center;">Quiz</th>
                            <th class="text-center" style="vertical-align : middle;text-align:center;">R (30%)</th>
                            <th colspan="1" class="text-center" style="vertical-align : middle;text-align:center;">Final</th>
                            <th class="text-center" style="vertical-align : middle;text-align:center;">R (40%)</th>
                            <th class="text-center" style="vertical-align : middle;text-align:center;">Total</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Remedial</th>
                            <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;width: 20%;">Comment</th>
                        </tr>
                        <tr>
                            @for ($i=1; $i <= $data['grade']->total_homework; $i++)
                                <td class="text-center">{{ $i }}</td>
                            @endfor
                            <td class="text-center">Avg</td>
                            <td style="background-color:beige;" class="text-center">10%</td>
                            @for ($j=1; $j <= $data['grade']->total_exercise; $j++)
                                <td class="text-center">{{ $j }}</td>
                            @endfor
                            <td class="text-center">Avg</td>
                            <td style="background-color:beige;" class="text-center">15%</td>
                            @for ($k=1; $k <= $data['grade']->total_participation; $k++)
                                <td class="text-center">{{ $k }}</td>
                            @endfor
                            <td class="text-center">Avg</td>
                            <td style="background-color:beige;" class="text-center">5%</td>
                            <td style="background-color:beige;" class="text-center">H+E+P</td>
                            @for ($l=1; $l <= $data['grade']->total_quiz; $l++)
                                <td class="text-center">{{ $l }}</td>
                            @endfor
                            <td class="text-center">Avg</td>
                            <td style="background-color:beige;" class="text-center">Quiz</td>
                            <td class="text-center">Exam</td>
                            <td style="background-color:beige;" class="text-center">F.E</td>
                            <td style="background-color:beige;" class="text-center">100%</td>
                        </tr>
                    </thead>
        
                    <tbody>
                    @if (!empty($data['students']))
                        @foreach ($data['students'] as $student)                    
                            <tr>
                                <td class="text-center" style="vertical-align : middle;text-align:center;">{{ $loop->iteration }}</td>  <!-- nomer -->
                                <td style="position: sticky; left: 0; background: #ffffff; z-index: 99;">{{ $student['student_name'] }}</td> <!-- name -->
                            
        
                                <!-- COUNT HOMEWORK -->
                                @foreach ($student['scores'] as $index => $score)
                                    @if($score['type_exam'] == 1)
                                        <td class="text-center">{{ $score['score'] }}</td>
                                    @endif
                                @endforeach
                                <td>{{ $student['avg_homework'] }} </td>
                                <td style="background-color:beige;">{{ $student['percent_homework'] }} </td>
                                <!-- END HOMEWORK -->
        
        
                                <!-- COUNT EXERCISE -->
                                @foreach ($student['scores'] as $index => $score)
                                    @if($score['type_exam'] == 2)
                                        <td class="text-center">{{ $score['score'] }}</td>
                                    @endif
                                @endforeach
        
                                <td class="text-center">{{ $student['avg_exercise'] }}</td> <!-- nilai rata-rata exercise -->
                                <td style="background-color:beige;" class="text-center">{{ $student['percent_exercise'] }}</td> <!-- 15% dari nilai rata-rata exercise -->
                                <!-- END COUNT EXERCISE -->
        
        
                                <!-- COUNT PARTICIPATION -->
                                @foreach ($student['scores'] as $index => $score)
                                    @if($score['type_exam'] == 5)
                                        <td class="text-center">{{ $score['score'] }}</td> 
                                    @endif
                                @endforeach
        
                                <td class="text-center">{{ $student['avg_participation'] }}</td> <!-- nilai rata-rata exercise -->
                                <td style="background-color:beige;" class="text-center">{{ $student['percent_participation'] }}</td> <!-- 15% dari nilai rata-rata exercise -->
                                
                                <!-- END COUNT PARTICIPATION -->
        
                                <!-- H+E+P -->
                                <td style="background-color:beige;">{{$student['h+e+p'] }}</td>
                                <!-- END H+E+P -->
        
        
                                <!-- COUNT QUIZ -->
                                @foreach ($student['scores'] as $index => $score)
                                    @if($score['type_exam'] == 3)
                                        <td class="text-center">{{ $score['score'] }}</td> <!-- total jumlah homework -->
                                    @endif
                                @endforeach
                                <td>{{ $student['avg_quiz'] }}</td>
                                <td style="background-color:beige;">{{ $student['percent_quiz'] }}</td>
                                <!-- END COUNT QUIZ -->
        
                                <!-- COUNT F.EXAM -->
                                @php $foundFinalExam = false; @endphp
                                @foreach ($student['scores'] as $score)
                                    @if($score['type_exam'] == 4)
                                        <td class="text-center">{{ $score['score'] }}</td>
                                        @php $foundFinalExam = true; @endphp
                                    @endif
                                @endforeach
                                @if(!$foundFinalExam)
                                    <td>&nbsp;</td>
                                @endif
                                <td style="background-color:beige;">{{ $student['percent_fe'] ?? '&nbsp;' }}</td>
                                <!-- END COUNT F.EXAM -->
        
                                <!-- FINAL SCORE -->
                                @if ($student['total_score'] < 70)
                                    <td 
                                        class="text-bold text-center text-danger align-middle" 
                                        style="background-color: beige;">
                                            {{ $student['total_score'] }}
                                    </td>
                                @else
                                    <td class="text-bold text-center" style="background-color:beige;">{{ $student['total_score'] }}</td>
                                @endif
                                {{-- END FINAL SCORE --}}
        
                                
                                <!-- REMEDIAL -->
                                @if ($student['total_score'] < 70)
                                    <td 
                                        class="text-bold text-center text-danger align-middle" 
                                        style="background-color: beige;" 
                                        data-student-id="{{ $student['student_id'] }}" 
                                        data-student-name="{{ ucwords(strtolower($student['student_name'])) }}" 
                                        data-total-score="{{ $student['total_score'] }}" 
                                        data-acar="{{ $student['acar'] }}">
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <!-- Tampilkan total score -->
                                            <span>{{ $student['acar'] }}</span>
                                    
                                            <!-- Tombol remedial -->
                                            <div class="input-group-append w-100 mt-2">
                                                <a 
                                                    class="btn btn-danger w-100 btn-open-remedial-modal" 
                                                    data-student-id="{{ $student['student_id'] }}">
                                                    Remedial
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td class="text-bold text-center">-</td>
                                @endif
                                {{-- END REMEDIA --}}
                                
        
                                <!-- COMMENT -->
                                <td class="project-actions text-left">
                                    <div class="input-group">
                                        <input name="student_id[]" type="number" class="form-control d-none" id="student_id-{{$student['student_id']}}" value="{{ $student['student_id'] }}">  
                                        <input name="final_score[]" type="number" class="form-control d-none" id="final_score-{{$student['student_id']}}" value="{{ $student['total_score'] }}">  
                                        <input name="semester" type="number" class="form-control d-none" id="semester-{{$student['student_id']}}" value="{{ $data['semester'] }}"> 
                                        @if ($data['status'] == null) 
                                        <input 
                                            name="comment[]" 
                                            type="text" 
                                            class="form-control" 
                                            id="comment" 
                                            placeholder="{{ $student['comment'] ? '' : 'Maksimal 255 Character' }}" 
                                            value="{{ $student['comment'] ?: '' }}" 
                                            maxlength="255" 
                                            oninput="validateCommentLength(this)" 
                                            autocomplete="off" 
                                            required
                                        >
                                        @else
                                        {{ $student['comment'] }}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                        @endforeach
                        <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->id }}">  
                        <input name="subject_id" type="number" class="form-control d-none" id="subject_id" value="{{ $data['subject']->subject_id }}">  
                        <input name="subject_teacher" type="number" class="form-control d-none" id="subject_teacher" value="{{ $data['subjectTeacher']->teacher_id }}">  
                    </form>
                    @else
                    <tr>
                        <td colspan="15" class="text-center">
                            Data Empty
                        </td>    
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Submit Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit comment?
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
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Comment {{ $data['grade']->name }} - {{ $data['grade']->class }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline comment {{ $data['grade']->name }} - {{ $data['grade']->class }} ?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-danger btn" id="confirmDecline">Yes decline</a>
            </div>
        </div>
    </div>
</div>

{{-- MODAL REMEDIAL --}}
<div class="modal" id="remedialModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remedial <span id="studentName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Before remedial <span class="text-bold text-danger" id="beforeRemedialScore"></span> 
                After remedial <span class="text-bold" id="afterRemedialScore"></span>
                <form id="remedialForm" method="POST" action="{{ route('remedial') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input name="remedial" min="70" max="100" type="number" class="form-control input-remedial" id="remedialScoreInput" value="" oninput="validateRemedialInput(this)">
                        <button class="btn btn-success" type="submit">Submit</button>
                    </div>
                    <input name="student_id" type="hidden" id="modalStudentId">
                    <input name="grade_id" type="hidden" value="{{ $data['grade']->id }}">
                    <input name="subject_id" type="hidden" value="{{ $data['subject']->subject_id }}">
                    <input name="subject_teacher_id" type="hidden" value="{{ $data['subjectTeacher']->teacher_id }}">
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalDecline').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var gradeId = @json($data['grade']->id);
            var teacherId = @json($data['subjectTeacher']->teacher_id);
            var subjectId = @json($data['subject']->subject_id);
            var semester = @json($data['semester']);

            // console.log("id=", gradeId, "teacher=", teacherId, "semester=", semester, "subject=", subjectId, academicYear);
            var confirmDecline = document.getElementById('confirmDecline');
            confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/scoring/decline') }}/" + gradeId + "/" + teacherId + "/" + subjectId + "/" + semester;
        });
    });

    // Action Open Modal Remedial
    document.addEventListener('DOMContentLoaded', function () {
        // Pilih semua tombol yang membuka modal
        const remedialButtons = document.querySelectorAll('.btn-open-remedial-modal');

        remedialButtons.forEach(button => {
            button.addEventListener('click', function () {
                const studentId = this.getAttribute('data-student-id');
                const row = document.querySelector(`[data-student-id="${studentId}"]`);

                const studentName = row.getAttribute('data-student-name');
                const totalScore = row.getAttribute('data-total-score');
                const acarScore = row.getAttribute('data-acar');

                // Isi data modal
                document.getElementById('studentName').innerText = studentName;
                document.getElementById('beforeRemedialScore').innerText = totalScore;
                document.getElementById('afterRemedialScore').innerText = acarScore;
                document.getElementById('modalStudentId').value = studentId;

                // Tampilkan modal
                $('#remedialModal').modal('show');
            });
        });
        });


    function validateCommentLength(input) {
        const maxLength = 255;
        if (input.value.length == maxLength) {
            console.log(input.value.length);
            alert(`Jangan Melebihi Batas Karakter.`);
            input.value = input.value.slice(0, maxLength); // Truncate excess characters
        }
    }

    function validateRemedialInput(input) {
        const min = parseInt(input.min, 10);
        const max = parseInt(input.max, 10);
        const value = parseInt(input.value, 10);

        if (value < min) {
            input.value = min;
        } else if (value > max) {
            input.value = max;
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Cari semua tombol submit remedial
        const remedialButtons = document.querySelectorAll('[id^="remedialButton-"]');

        remedialButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Dapatkan student_id dari ID tombol
                const studentId = button.id.split('-')[1];
                
                // Cari form terkait berdasarkan student_id
                const form = document.getElementById(`remedialForm-${studentId}`);

                // Validasi input sebelum submit
                const input = document.querySelector(`#input-remed-${studentId}`);
                if (!input.value || input.value < 70 || input.value > 100) {
                    alert('Please enter a valid value between 70 and 100.');
                    return;
                }

                // Submit form
                if (form) {
                    form.submit();
                }
            });
        });
    });
</script>

<script>    
    document.addEventListener('DOMContentLoaded', function() {
        const confirmButton = document.getElementById('confirmAccScoring');
        const confirmForm = document.getElementById('confirmForm');
        
        if (confirmButton && confirmForm) {
            confirmButton.addEventListener('click', function() {
                confirmForm.submit();
            });
        }
    });
</script>


<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_post_final_score')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully Post Comment Major Subject .',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false, // Sembunyikan tombol "OK"
        });   
    </script>
@endif

@if(session('after_decline_scoring'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully Decline Comment.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

@if(session('remedial_posted')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully submit remedial score.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

<script>
    const topScroll = document.getElementById('scroll-top');
    const bottomScroll = document.getElementById('scroll-bottom');

    topScroll.addEventListener('scroll', () => {
        bottomScroll.scrollLeft = topScroll.scrollLeft;
    });

    bottomScroll.addEventListener('scroll', () => {
        topScroll.scrollLeft = bottomScroll.scrollLeft;
    });
</script>

@endsection
