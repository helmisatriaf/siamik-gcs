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
            <p class="font-bold">Academic Assessment Report</p>
            <table>
                <tr>
                    <td>Class</td>
                    <td> : {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</td>
                </tr>
                <tr>
                    <td>Teacher</td>
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
        <form id="confirmForm" method="POST" action={{route('actionPostScoringAcarSecondary')}}>
    @elseif (session('role') == 'admin')
        <form id="confirmForm" method="POST" action={{route('actionAdminPostScoringAcarSecondary')}}>
    @elseif (session('role') == 'teacher')
        <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringAcarSecondary')}}>
    @endif
    @csrf

    @if ($data['status'] == null)
        @if (!empty($data['students']))
            <div class="row my-2">
                <div class="input-group-append mx-2">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmModal">Submit ACAR</button>
                </div>
            </div>
        @endif
    @elseif ($data['status']->status != null && $data['status']->status == 1)       
        <div class="row my-2">
            <div class="input-group-append mx-2">
                <a  class="btn btn-success">Already Submitted on {{ \Carbon\Carbon::parse($data['status']->created_at)->format('l, d F Y') }}</a>
                @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                <a  class="btn btn-warning mx-2" data-toggle="modal" data-target="#modalDecline">Decline ACAR</a>
                @endif
            </div>
        </div>  
    @endif

    <div style="overflow-x: auto;">

        <table class="table table-striped table-bordered bg-white" style=" width: 2200px;">
            <thead>
                <tr>
                    <th rowspan="3" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                    <th rowspan="3" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                    <th colspan="9" class="text-center" style="vertical-align : middle;text-align:center;">Major Subjects</th>
                    <th colspan="9" class="text-center" style="vertical-align : middle;text-align:center;">Minor Subjects</th>
                    <th colspan="11" class="text-center" style="vertical-align : middle;text-align:center;">Supplementary Subjects</th>
                    <th class="text-center">Academic</th>
                    <th rowspan="3" class="text-center" style="width:15%;vertical-align : middle;text-align:center;">Comment</th>
                </tr>
                <tr>
                    <!-- Major Subjects -->
                    <td class="text-center" colspan="2">English</td>
                    <td class="text-center" colspan="2">Chinese</td>
                    <td class="text-center" colspan="2">Math</td>
                    <td class="text-center" colspan="2">Science</td>
                    <td style="background-color:beige;" class="text-center">Avg</td>
                    <!-- END MAJOR SUBJECTS -->
                    
                    <!-- MINOR SUBJECTS -->
                    <td class="text-center" colspan="2">IPS</td>
                    <td class="text-center" colspan="2">PPKN</td>
                    <td class="text-center" colspan="2">Religion</td>
                    <td class="text-center" colspan="2">BI</td>
                    <td style="background-color:beige;" class="text-center">Avg</td>
                    <!-- END MINOR SUBJECTS -->
                    
                    <!-- SUPPLEMENTARY SUBJECTS -->
                    <td class="text-center" colspan="2">PE</td>
                    <td class="text-center" colspan="2">IT</td>
                    <td class="text-center" colspan="2">A/D</td>
                    <td class="text-center" colspan="2">CB</td>
                    <td class="text-center" colspan="2">FL</td>
                    <td style="background-color:beige;" class="text-center">Avg</td>
                    <!-- END SUPPLEMENTARY SUBJECTS -->

                    <td style="background-color:beige;" class="text-center">Total</td>
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
                    <td style="background-color:beige;" class="text-center">70%</td>
                    <!-- END MAJOR SUBJECTS -->
                    
                    <!-- MINOR SUBJECTS -->
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td style="background-color:beige;" class="text-center">20%</td>
                    <!-- END MINOR SUBJECTS -->
                    
                    <!-- SUPPLEMENTARY SUBJECTS -->
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td class="text-center">Mks</td>
                    <td class="text-center">Grs</td>
                    <td style="background-color:beige;" class="text-center">10%</td>
                    <!-- END SUPPLEMENTARY SUBJECTS -->

                    <td style="background-color:beige;" class="text-center">100%</td>
                </tr>
            </thead>

            <tbody>
                @if (!empty($data['students']))
                    @foreach ($data['students'] as $dt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>  <!-- nomer -->
                            <td>{{ ucwords(strtolower($dt['student_name'])) }}</td> <!-- name -->

                            @php
                                $subjects = [3 => 'English', 1 => 'Chinese', 2 => 'Math', 5 => 'Science', 32 => 'IPS', 7 => 'PPKN', 20 => 'Religion', 4 => 'BI', 18 => 'PE', 6 => 'IT', 33 => 'A/D', 16 => 'CB', 62 => 'FL'];
                                $subjectScores = array_fill_keys(array_keys($subjects), ['final_score' => '', 'grades' => '']);
                                
                                foreach ($dt['scores'] as $score) {
                                    $subjectScores[$score['subject_id']] = $score;
                                }
                            @endphp

                            @foreach ([3, 1, 2, 5] as $subjectId)
                                <td class="text-center {{ $subjectScores[$subjectId]['final_score'] < 70 ? 'text-danger text-bold' : '' }}">{{ $subjectScores[$subjectId]['final_score'] }}</td>
                                <td class="text-center">{{ $subjectScores[$subjectId]['grades'] }}</td>
                            @endforeach
                            <td style="background-color:beige;" class="text-center">{{ $dt['percent_majorSubjects'] }}</td>

                            @foreach ([32, 7, 20, 4] as $subjectId)
                                <td class="text-center {{ $subjectScores[$subjectId]['final_score'] < 70 ? 'text-danger text-bold' : '' }}">{{ $subjectScores[$subjectId]['final_score'] }}</td>
                                <td class="text-center">{{ $subjectScores[$subjectId]['grades'] }}</td>
                            @endforeach
                            <td style="background-color:beige;" class="text-center">{{ $dt['percent_minorSubjects'] }}</td>

                            @foreach ([18, 6, 33, 16, 62] as $subjectId)
                                <td class="text-center {{ $subjectScores[$subjectId]['final_score'] < 70 ? 'text-danger text-bold' : '' }}">{{ $subjectScores[$subjectId]['final_score'] }}</td>
                                <td class="text-center">{{ $subjectScores[$subjectId]['grades'] }}</td>
                            @endforeach
                            <td style="background-color:beige;" class="text-center">{{ $dt['percent_supplementarySubjects'] }}</td>
                            <td style="background-color:beige;" class="text-center text-bold">{{ $dt['total_score'] }}</td>

                            <!-- COMMENT -->
                            <td class="project-actions text-left">
                                <div class="input-group">
                                    @if ($data['status'] == null)
                                        <input name="comment[]" type="text" class="form-control" id="comment" placeholder="{{ $dt['comment'] ? '' : 'Maksimal 255 Character' }}" value="{{ $dt['comment'] ?: '' }}" autocomplete="off" required>
                                    @else 
                                        {{ $dt['comment'] }}
                                    @endif
                                    <input name="student_id[]" type="number" class="form-control d-none" id="student_id" value="{{ $dt['student_id'] }}">  
                                    <input name="final_score[]" type="number" class="form-control d-none" id="final_score" value="{{ $dt['total_score'] }}"> 
                                </div>
                            </td>
                            <!-- END COMMENT -->
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="33" class="text-center text-danger">
                            Data Empty     
                        </td>    
                    </tr>
                @endif
            </tbody>
        </table>
        <input name="semester" type="number" class="form-control d-none" id="semester" value="{{ $data['semester'] }}">  
        <input name="grade_id" type="number" class="form-control d-none" id="grade_id" value="{{ $data['grade']->grade_id }}">    
        <input name="class_teacher" type="number" class="form-control d-none" id="class_teacher" value="{{ $data['classTeacher']->teacher_id }}">  
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Acc ACAR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit comment ACAR?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAcarScoring">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Decline -->
<div class="modal fade" id="modalDecline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Acar {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline comment ACAR {{ $data['grade']->grade_name }} - {{ $data['grade']->grade_class }} ?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-danger btn" id="confirmDecline">Yes decline</a>
            </div>
        </div>
    </div>
</div>


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
            if(role == 'superadmin' || role == 'admin'){
                confirmDecline.href = "{{ url('/' . session('role') . '/reports/acar/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
            else if(role == 'teacher'){
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/acar/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
        });
    });
</script>


<script>
    document.getElementById('confirmAcarScoring').addEventListener('click', function() {
        // Mengambil semua input komentar
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

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_post_final_score')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully post comment academic assessment secondary in the database.',
        });
    </script>
@endif

@endsection
