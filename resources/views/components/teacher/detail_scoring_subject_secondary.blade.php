@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="p-3 mb-4" style="background-color: #ffde9e;border-radius:12px;">
          <ol class="breadcrumb mb-0"  style="background-color: #fff3c0;">
            <li class="breadcrumb-item">Home</li>
            @if (session('role') == 'superadmin')
              <li class="breadcrumb-item"><a href="{{url('/superadmin/reports')}}">Reports</a></li>
            @elseif (session('role') == 'admin')
                <li class="breadcrumb-item"><a href="{{url('/admin/reports')}}">Reports</a></li>
            @elseif (session('role') == 'teacher')
                <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/report/subject/teacher')}}">Reports </a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Detail Report {{ $data['subject']->subject_name }}</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="card" style="background-color: #ffde9e;border-radius:12px;">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <p class="text-bold">Secondary Scoring</p>
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

                        @if($data['status'] == null)
                        @elseif ($data['status']->status !== null && $data['status']->status == 1)
                            <tr>
                                <td>Status</td>
                                <td> : <span class="text-bold">Already Submitted in {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}</span>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-start text-end">
                    @if ($data['status'] == null)
                        @if ($data['permission'] == true)               
                            @if (!empty($data['students']))
                                <div class="row my-2">
                                    <div class="input-group-append mx-2">
                                        <a type="button" class="btn btn-app bg-success" data-toggle="modal" data-target="#confirmModal">
                                            <i class="fas fa-save"></i>
                                            Submit</a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @elseif ($data['status']->status != null && $data['status']->status == 1)   
                        @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                        <a  class="btn btn-app bg-secondary" data-toggle="modal" data-target="#modalDecline">
                            <i class="fas fa-cancel"></i>
                            Decline</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('role') == 'superadmin')
                <form id="confirmForm" method="POST" action={{route('actionPostScoringSecondary')}}>
            @elseif (session('role') == 'admin')
                <form id="confirmForm" method="POST" action={{route('actionAdminCreateExam')}}>
            @elseif (session('role') == 'teacher')
                <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringSecondary')}}>
            @endif
            @csrf
        
            <div style="overflow-x: auto;">
        
                {{-- MINOR SUBJECT --}}
                @if (
                        strtolower($data['subject']->subject_name) !== 'science' &&
                        strtolower($data['subject']->subject_name) !== 'english' &&
                        strtolower($data['subject']->subject_name) !== 'mathematics' &&
                        strtolower($data['subject']->subject_name) !== 'chinese higher' &&
                        strtolower($data['subject']->subject_name) !== 'chinese lower'
                    )
        
                    <table class="table table-striped table-bordered border-black" style=" width: 2000px;">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                                <th rowspan="2 class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                                <th colspan="{{ $data['grade']->total_homework + 1 }}" class="text-center" style="vertical-align : middle;text-align:center;">Homework (20%)</th>
                                <th colspan="{{ $data['grade']->total_exercise + 1 }}" class="text-center" style="vertical-align : middle;text-align:center;">Exercise (35%)</th>
                                <th colspan="{{ $data['grade']->total_participation + 1 }} class="text-center" style="vertical-align : middle;text-align:center;">Attendance / Participation (10%)</th>
                                <th colspan="{{ $data['grade']->total_final_exam + 1 }}" class="text-center" style="vertical-align : middle;text-align:center;">Project/Practical/Final Assessment (35%)</th>
                                <th class="text-center">Total</th>
                                {{-- <th class="text-center">Marks</th> --}}
                                <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Remedial</th>
                                <th rowspan="2" class="text-center" style="width: 25%;vertical-align : middle;text-align:center;">Comment</th>
                            </tr>
                            <tr>
                                @for ($i=1; $i <= $data['grade']->total_homework; $i++)
                                    <td class="text-center">{{ $i }}</td>
                                @endfor
                                <td style="background-color:beige;" class="text-center">Avg</td>
                                @for ($j=1; $j <= $data['grade']->total_exercise; $j++)
                                    <td class="text-center">{{ $j }}</td>
                                @endfor
                                <td style="background-color:beige;" class="text-center">Avg</td>
                                @for ($k=1; $k <= $data['grade']->total_participation; $k++)
                                    <td class="text-center">{{ $k }}</td>
                                @endfor
                                <td style="background-color:beige;" class="text-center">Avg</td>
                                @for ($l=1; $l <= $data['grade']->total_final_exam; $l++)
                                    <td class="text-center">{{ $l }}</td>
                                @endfor
                                <td style="background-color:beige;" class="text-center">Avg</td>
                                <td style="background-color:beige;" class="text-center">100%</td>
                                {{-- <td>&nbsp;</td> --}}
                            </tr>
                        </thead>
        
                        <tbody>
                        @if (!empty($data['students']))
        
                            @foreach ($data['students'] as $student)
                                
                                <tr>
                                    <td>{{ $loop->iteration }}</td>  <!-- nomer -->
                                    <td style="position: sticky; left: 0; background: #fff3c0; z-index: 99;">{{ $student['student_name'] }}</td> <!-- name -->
                                
        
                                    <!-- COUNT HOMEWORK -->
                                    @foreach ($student['scores'] as $index => $score)
                                        @if($score['type_exam'] == $data['homework'])
                                            <td class="text-center">{{ $score['score'] }}</td>
                                        @endif
                                    @endforeach
                                    <td style="background-color:beige;" class="text-center">{{ $student['percent_homework'] }} </td>
                                    <!-- END HOMEWORK -->
        
        
                                    <!-- COUNT EXERCISE -->
                                    @foreach ($student['scores'] as $index => $score)
                                        @if($score['type_exam'] == $data['exercise'])
                                            <td class="text-center">{{ $score['score'] }}</td>
                                        @endif
                                    @endforeach
        
                                    <td style="background-color:beige;" class="text-center">{{ $student['percent_exercise'] }}</td> <!-- nilai rata-rata exercise -->
                                    <!-- END COUNT EXERCISE -->
        
        
                                    <!-- COUNT PARTICIPATION -->
                                    @foreach ($student['scores'] as $index => $score)
                                        @if($score['type_exam'] == $data['participation'])
                                            <td class="text-center">{{ $score['score'] }}</td> 
                                        @endif
                                    @endforeach
        
                                    <td style="background-color:beige;" class="text-center">{{ $student['percent_participation'] }}</td> <!-- nilai rata-rata participation -->
                                    
                                    <!-- END COUNT PARTICIPATION -->
        
                                    <!-- COUNT PROJECT / PRACTICAL / FINAL ASESSMENT -->
                                    @foreach ($student['scores'] as $index => $score)
                                        @if($score['type_exam'] == $data['project'])
                                            <td class="text-center">{{ $score['score'] }}</td> 
                                        @elseif($score['type_exam'] == $data['practical'])
                                            <td class="text-center">{{ $score['score'] }}</td> 
                                        @elseif($score['type_exam'] == $data['finalAssessment'])
                                            <td class="text-center">{{ $score['score'] }}</td> 
                                        @elseif($score['type_exam'] == $data['finalExam'])
                                            <td class="text-center">{{ $score['score'] }}</td> 
                                        @endif
                                    @endforeach
                                    <td style="background-color:beige;" class="text-center">{{ $student['percent_fe'] }}</td>
                                    <!-- END COUNT PROJECT / PRACTICAL / FINAL ASESSMENT -->
                                    
        
                                    <!-- FINAL SCORE -->
                                    <td style="background-color:beige;" class="text-center text-bold">{{ $student['total_score'] }}</td>
        
                                    <!-- MARKS -->
                                    {{-- <td class="text-center">{{ $student['grades'] }}</td> --}}

                                    {{-- REMEDIAL --}}
                                    @if ($student['total_score'] < 70)
                                        <td 
                                            class="text-bold text-center text-danger align-middle" 
                                            style="background-color: beige;" 
                                            >
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <!-- Tampilkan total score -->
                                                <span>{{ $student['acar'] }}</span>
                                        
                                                <!-- Tombol remedial -->
                                                <div class="input-group-append w-100 mt-2">
                                                    <a 
                                                        data-student-id="{{ $student['student_id'] }}" 
                                                        data-student-name="{{ ucwords(strtolower($student['student_name'])) }}" 
                                                        data-total-score="{{ $student['total_score'] }}" 
                                                        data-acar="{{ $student['acar'] }}"
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
                                    {{-- END REMEDIAL --}}

                                    <!-- COMMENT -->
                                    <td class="project-actions text-left">
                                        <div class="input-group">
                                            <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">  
                                            <input name="final_score[]" type="number" class="form-control d-none" id="final_score" value="{{ $student['total_score'] }}">  
                                            <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">  
                                            @if ($data['status'] == null) 
                                            <input 
                                                name="comment[]" 
                                                type="text" 
                                                class="form-control" 
                                                id="comment" 
                                                placeholder="{{ $student['comment'] ? '' : 'Maksimal 255 Karakter' }}" 
                                                value="{{ $student['comment'] ?: '' }}" 
                                                maxlength="255" 
                                                oninput="validateCommentLength(this)" 
                                                autocomplete="off" 
                                                required
                                            >
                                            {{-- <div class="input-group-append">
                                                <a class="btn btn-danger btn" data-toggle="modal" data-target="#editSingleComment">
                                                    <i class="fas fa-pen"></i>
                                                    Edit
                                                </a>
                                            </div> --}}
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
                                    You haven't added a assessment... <br>
                                    <a href="/teacher/dashboard/exam/teacher" class="text-red">Create Exam</a>        
                                </td>    
                            </tr>
                            @endif
                        </tbody>
                    </table>
                @else
                {{-- MAJOR SUBJECT --}}
                    <table class="table table-striped table-bordered" style=" width: 2000px;">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                                <th rowspan="2 class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                                <th colspan="{{ $data['grade']->total_tasks + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;"> Tasks (Homework/Small Project/Presentation)</th>
                                <th colspan="{{ $data['grade']->total_mid + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Quiz/Practical Exam/Project</th>
                                <th colspan="{{ $data['grade']->total_final_exam + 2 }}" class="text-center" style="vertical-align : middle;text-align:center;">Final Exam</th>
                                <th class="text-center">Total</th>
                                <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Remedial</th>
                                <th rowspan="2" class="text-center" style="vertical-align : middle;text-align:center;">Comment</th>
                            </tr>
                            <tr>
                                <!-- TASKS -->
                                @for ($i=1; $i <= $data['grade']->total_tasks; $i++)
                                    <td class="text-center">{{ $i }}</td>
                                @endfor
                                <td class="text-center">Avg</td>
                                <td style="background-color:beige;" class="text-center">25%</td>
                                <!-- END TASKS -->
        
                                <!-- QUIZ -->
                                @for ($j=1; $j <= $data['grade']->total_mid; $j++)
                                    <td class="text-center">{{ $j }}</td>
                                @endfor
                                <td class="text-center">Avg</td>
                                <td style="background-color:beige;" class="text-center">35%</td>
                                <!-- END QUIZ -->
        
                                <!-- FINAL EXAM -->
                                @for ($j=1; $j <= $data['grade']->total_final_exam; $j++)
                                    <td class="text-center">{{ $j }}</td>
                                @endfor
                                <td class="text-center">Avg</td>
                                <td style="background-color:beige;" class="text-center">40%</td>
                                <!-- END FINAL EXAM -->
        
                                <td style="background-color:beige;" class="text-center">100%</td>
                            </tr>
                        </thead>
        
                        <tbody>
                        @if (!empty($data['students']))
        
                            @foreach ($data['students'] as $student)
                                
                                <tr>
                                    <td>{{ $loop->iteration }}</td>  <!-- nomer -->
                                    <td style="position: sticky; left: 0; background: #fff3c0; z-index: 99;">{{ $student['student_name'] }}</td> <!-- name -->
                            
                                    <!-- COUNT TASKS -->
                                    @foreach ($student['scores'] as $index => $score)
                                        @if(in_array($score['type_exam'], $data['tasks']))
                                            <td class="text-center">{{ $score['score'] }}</td>
                                        @endif
                                    @endforeach
        
                                    <td>{{ $student['avg_tasks'] }} </td>
                                    <td style="background-color:beige;">{{ $student['percent_tasks'] }} </td>
                                    <!-- END TASKS -->
        
        
                                    <!-- COUNT QUIZ -->
                                    @foreach ($student['scores'] as $index => $score)
                                        @if(in_array($score['type_exam'], $data['mid']))
                                            <td class="text-center">{{ $score['score'] }}</td>
                                        @endif
                                    @endforeach
        
                                    <td class="text-center">{{ $student['avg_mid'] }}</td> <!-- nilai rata-rata exercise -->
                                    <td style="background-color:beige;" class="text-center">{{ $student['percent_mid'] }}</td> <!-- 15% dari nilai rata-rata exercise -->
                                    <!-- END COUNT QUIZ -->
        
        
                                    <!-- COUNT F.EXAM -->
                                    @php $foundFinalExam = false; @endphp
                                    @foreach ($student['scores'] as $score)
                                        @if(in_array($score['type_exam'], $data['finalExam']))
                                            <td class="text-center">{{ $score['score'] }}</td>
                                            @php $foundFinalExam = true; @endphp
                                        @endif
                                    @endforeach
                                    <td>{{ $student['avg_fe'] ?? '&nbsp;' }}</td>
                                    <td style="background-color:beige;">{{ $student['percent_fe'] ?? '&nbsp;' }}</td>
                                    <!-- END COUNT F.EXAM -->
                                    
        
                                    <!-- FINAL SCORE -->
                                    @if ($student['total_score'] < 70)
                                        <td class="text-bold text-center text-danger align-middle" style="background-color: beige;">
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <!-- Tampilkan total score -->
                                                <span>{{ $student['total_score'] }}</span>
                                            </div>
                                        </td>
                                    @else
                                        <td style="background-color: beige;" class="text-center text-bold">{{ $student['total_score'] ?? '&nbsp;' }}</td>
                                    @endif
        
        
                                    <!-- REMEDIAL -->
                                    @if ($student['total_score'] < 70)
                                        <td 
                                            class="text-bold text-center text-danger align-middle" 
                                            style="background-color: beige;" 
                                            >
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <!-- Tampilkan total score -->
                                                <span>{{ $student['acar'] }}</span>
                                        
                                                <!-- Tombol remedial -->
                                                <div class="input-group-append w-100 mt-2">
                                                    <a 
                                                        data-student-id="{{ $student['student_id'] }}" 
                                                        data-student-name="{{ ucwords(strtolower($student['student_name'])) }}" 
                                                        data-total-score="{{ $student['total_score'] }}" 
                                                        data-acar="{{ $student['acar'] }}"
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
                                    {{-- END REMEDIAL --}}
        
        
                                    <!-- COMMENT -->
                                    <td class="project-actions text-left">
                                        @if ($data['status'] == null)
                                            <div class="input-group">
                                                <input 
                                                    name="comment[]" 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="comment" 
                                                    placeholder="{{ $student['comment'] ? '' : 'Maksimal 255 Karakter' }}" 
                                                    value="{{ $student['comment'] ?: '' }}" 
                                                    maxlength="255" 
                                                    oninput="validateCommentLength(this)" 
                                                    autocomplete="off" 
                                                    required
                                                >
                                                <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $student['student_id'] }}">  
                                                <input name="final_score[]" type="number" class="form-control d-none" id="final_score" value="{{ $student['total_score'] }}">  
                                                <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">  
                                                {{-- <div class="input-group-append">
                                                    <a class="btn btn-danger btn" data-toggle="modal" data-target="#editSingleComment">
                                                        <i class="fas fa-pen"></i>
                                                        Edit
                                                    </a>
                                                </div>     --}}
                                            </div>
                                        @elseif ($data['status'] != null && $data['status']->status == 1)       
                                            {{ $student['comment'] }}
                                        @endif
                                    </td>
                                </tr>
        
                            @endforeach
        
                                <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->id }}">  
                                <input name="subject_id" type="number" class="form-control d-none" id="subject_id" value="{{ $data['subject']->subject_id }}">  
                                <input name="subject_teacher" type="number" class="form-control d-none" id="subject_teacher" value="{{ $data['subjectTeacher']->teacher_id }}">  
                        @else
                            <tr>
                                <td colspan="15" class="text-center">
                                    You haven't added a assessment... <br>
                                    <a href="/teacher/dashboard/exam/teacher" class="text-red">Create Exam</a>        
                                </td>    
                            </tr>
                        @endif
                            
                        </tbody>
                    </table>
                    </form>
                @endif
        
                <!-- Confirmation Modal -->
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

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
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

<div class="modal fade" id="remedialModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remedial <span id="modalStudentName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Before remedial: <span class="text-bold text-danger" id="modalTotalScore"></span><br>
                After remedial: <span class="text-bold" id="modalAcarScore"></span><br>
                <form id="remedialForm" method="POST" action="{{ route('remedial') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input 
                            name="remedial" 
                            min="70" 
                            max="100" 
                            type="number" 
                            class="form-control" 
                            id="remedialScoreInput" 
                            oninput="validateRemedialInput(this)">
                        <button class="btn btn-success" type="submit">Submit</button>
                    </div>
                    <input type="hidden" name="student_id" id="modalStudentId">
                    <input type="hidden" name="grade_id" value="{{ $data['grade']->id }}">
                    <input type="hidden" name="subject_id" value="{{ $data['subject']->subject_id }}">
                    <input type="hidden" name="subject_teacher_id" value="{{ $data['subjectTeacher']->teacher_id }}">
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

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

    // REMEDIAL
    document.addEventListener('DOMContentLoaded', function () {
        // Pilih semua tombol dengan kelas .btn-open-remedial-modal
        const remedialButtons = document.querySelectorAll('.btn-open-remedial-modal');

        remedialButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                // Ambil data dari tombol
                const studentId = this.getAttribute('data-student-id');
                const studentName = this.getAttribute('data-student-name');
                const totalScore = this.getAttribute('data-total-score');
                const acarScore = this.getAttribute('data-acar');

                // Isi data ke modal
                document.getElementById('modalStudentName').innerText = studentName;
                document.getElementById('modalTotalScore').innerText = totalScore;
                document.getElementById('modalAcarScore').innerText = acarScore;
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

    const remedialButtons = document.querySelectorAll('[id^="remedialButton-"]');

    remedialButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Dapatkan student_id dari ID tombol
            const studentId = button.id.split('-')[1];
            
            console.log(studentId);
            // Cari form terkait berdasarkan student_id
            const form = document.getElementById(`remedialForm-${studentId}`);

            // Validasi input sebelum submit
            const input = document.querySelector(`#input-remed-${studentId}`);
            if (!input.value || input.value < 70 || input.value > 100) {
                alert('Please enter a valid value between 70 and 100.');
                return;
            }
            if (form) {
                form.submit();
            }
        });
    });

    // document.addEventListener('DOMContentLoaded', function() {
    //     // Cari semua tombol submit remedial
        
    // });
</script>

<script>
    document.getElementById('confirmAccScoring').addEventListener('click', function() {
        var comments = document.querySelectorAll('input[name="comment[]"]');
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
        
        // Jika semua komentar terisi, submit form
        if (allFilled) {
            document.getElementById('confirmForm').submit();
        } else {
            // Menampilkan pesan peringatan
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'All comments must be filled before submitting the form!',
            });
        }
    });
</script>

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

@endsection
