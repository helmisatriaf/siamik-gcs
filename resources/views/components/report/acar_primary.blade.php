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

    <div class="card" style="height:70vh;overflow-y: auto;">
        <div class="card-header">
            <div class="row">   
                <div class="col-11 col-md-10">
                    <p class="text-bold">Academic Assessment Report</p>
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
                <form id="confirmForm" method="POST" action={{route('actionPostScoringAcarPrimary')}}>
            @elseif (session('role') == 'admin')
                <form id="confirmForm" method="POST" action={{route('actionAdminPostScoringAcarPrimary')}}>
            @elseif (session('role') == 'teacher')
                <form id="confirmForm" method="POST" action={{route('actionTeacherPostScoringAcarPrimary')}}>
            @endif
            @csrf
        
            <div id="scroll-top" style="overflow-x: auto;">
                <div style="width: 3000px; height: 1px;"></div> <!-- dummy scroll -->
            </div>
            <div id="scroll-bottom" style="overflow-x: auto;">
                <table class="table table-striped table-bordered bg-white border-black" style=" width: 2800px;">
                    <thead>
                        <tr>
                            <th rowspan="3" class="text-center" style="vertical-align : middle;text-align:center;">S/N</th>
                            <th rowspan="3" class="text-center" style="vertical-align : middle;text-align:center;">First Name</th>
                            <th colspan="9" class="text-center" style="vertical-align : middle;text-align:center;">Major Subjects</th>
                            <th colspan="7" class="text-center" style="vertical-align : middle;text-align:center;">Minor Subjects</th>
                            @if ($data['healthEducation'] !== null)
                            <th colspan="13" class="text-center" style="vertical-align : middle;text-align:center;">Supplementary Subjects</th>
                            @else
                            <th colspan="11" class="text-center" style="vertical-align : middle;text-align:center;">Supplementary Subjects</th>
                            @endif
                            <th class="text-center">Academic</th>
                            <th rowspan="3" class="text-center" style="width: 25%;vertical-align : middle;text-align:center;">Remarks</th>
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
                            <td class="text-center" colspan="2">PPKN</td>
                            <td class="text-center" colspan="2">Religion</td>
                            <td class="text-center" colspan="2">BI</td>
                            <td style="background-color:beige;" class="text-center">Avg</td>
                            <!-- END MINOR SUBJECTS -->
                            
                            <!-- SUPPLEMENTARY SUBJECTS -->
                            <td class="text-center" colspan="2">PE</td>
                            <td class="text-center" colspan="2">IT</td>
                            <td class="text-center" colspan="2">FL</td>
                            <td class="text-center" colspan="2">A/C</td>
                            <td class="text-center" colspan="2">CB</td>
                            @if ($data['healthEducation'] !== null)
                            <td class="text-center" colspan="2">HE</td>
                            @else
                            @endif
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
                            @if ($data['healthEducation'] !== null)
                            <td class="text-center">Mks</td>
                            <td class="text-center">Grs</td>
                            @else
                            @endif
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
                                    <td style="position: sticky; left: 0; background: #ffffff; z-index: 99;">{{ ucwords(strtolower($dt['student_name'])) }}</td> <!-- name -->
        
                                    @php
                                        $subjects = [
                                            3 => ['mks' => null, 'grs' => null], // English
                                            1 => ['mks' => null, 'grs' => null], // Chinese
                                            2 => ['mks' => null, 'grs' => null], // Math
                                            5 => ['mks' => null, 'grs' => null], // Science
                                            7 => ['mks' => null, 'grs' => null], // PPKN
                                            20 => ['mks' => null, 'grs' => null], // Religion
                                            4 => ['mks' => null, 'grs' => null], // Bhs.Indonesia
                                            18 => ['mks' => null, 'grs' => null], // PE
                                            6 => ['mks' => null, 'grs' => null], // IT
                                            62 => ['mks' => null, 'grs' => null], // GK
                                            15 => ['mks' => null, 'grs' => null], // A/C
                                            16 => ['mks' => null, 'grs' => null], // CB
                                            19 => ['mks' => null, 'grs' => null], // HE
                                        ];
                                    @endphp
        
                                    @foreach ($dt['scores'] as $score)
                                        @if (array_key_exists($score['subject_id'], $subjects))
                                            @php
                                                $subjects[$score['subject_id']]['mks'] = $score['final_score'];
                                                $subjects[$score['subject_id']]['grs'] = $score['grades'];
                                            @endphp
                                        @endif
                                    @endforeach
        
                                    @foreach ([3, 1, 2, 5] as $subject_id)
                                        <td class="text-center' {{ $subjects[$subject_id]['mks'] < 70 ? 'text-danger text-bold' : '' }}"'>{{ $subjects[$subject_id]['mks'] }}</td>
                                        <td class="text-center">{{ $subjects[$subject_id]['grs'] }}</td>
                                    @endforeach
        
                                    <td style="background-color:beige;" class="text-center">{{ $dt['percent_majorSubjects'] }}</td>
        
                                    @foreach ([7, 20, 4] as $subject_id)
                                        <td class="text-center {{ $subjects[$subject_id]['mks'] < 70 ? 'text-danger text-bold' : '' }}">{{ $subjects[$subject_id]['mks'] }}</td>
                                        <td class="text-center">{{ $subjects[$subject_id]['grs'] }}</td>
                                    @endforeach
        
                                    <td style="background-color:beige;" class="text-center">{{ $dt['percent_minorSubjects'] }}</td>
        
                                    @if ($data['healthEducation'] !== null)
                                    @foreach ([18, 6, 62, 15, 16, 19] as $subject_id)
                                        <td class="text-center {{ $subjects[$subject_id]['mks'] < 70 ? 'text-danger text-bold' : '' }}">{{ $subjects[$subject_id]['mks'] }}</td>
                                        <td class="text-center">{{ $subjects[$subject_id]['grs'] }}</td>
                                    @endforeach
                                    @else    
                                    @foreach ([18, 6, 62, 15, 16] as $subject_id)
                                        <td class="text-center {{ $subjects[$subject_id]['mks'] < 70 ? 'text-danger text-bold' : '' }}">{{ $subjects[$subject_id]['mks'] }}</td>
                                        <td class="text-center">{{ $subjects[$subject_id]['grs'] }}</td>
                                    @endforeach
                                    @endif
        
                                    <td style="background-color:beige;" class="text-center">{{ $dt['percent_supplementarySubjects'] }}</td>
                                    <td style="background-color:beige;" class="text-center text-bold {{ $dt['total_score'] < 70 ? 'text-danger' : '' }}">{{ $dt['total_score'] }}</td>
                                    <td class="project-actions text-left">
                                        <div class="input-group">
                                            @if ($data['status'] == null)
                                            <input 
                                                name="comment[]" 
                                                type="text" 
                                                class="form-control" 
                                                id="comment" 
                                                placeholder="{{ $dt['comment'] ? '' : 'Maksimal 255 Character' }}" 
                                                value="{{ $dt['comment'] ?: '' }}" 
                                                maxlength="255" 
                                                oninput="validateCommentLength(this)" 
                                                autocomplete="off" 
                                                required
                                            >
                                            @else 
                                                {{ $dt['comment'] }}
                                            @endif
                                            <input name="student_id[]" type="number" class="form-control d-none" id="student_id_{{ $dt['student_id'] }}" value="{{ $dt['student_id'] }}">
                                            <input name="final_score[]" type="number" class="form-control d-none" id="final_score_{{ $dt['student_id'] }}" value="{{ $dt['total_score'] }}">
                                        </div>
                                    </td>
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
    </div>
</div>



<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Submit ACAR Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit ACAR ?
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
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Decline Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure want to decline ACAR ?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn" id="confirmDecline">Yes</a>
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

            var confirmDecline = document.getElementById('confirmDecline');

            if(role == 'admin' || role == 'superadmin'){
                confirmDecline.href = "{{ url('/' . session('role') . '/reports/acar/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
            else if(role == 'teacher'){
                confirmDecline.href = "{{ url('/' . session('role') . '/dashboard/acar/decline') }}/" + id + "/" + teacherId + "/" + semester;
            }
        });
    });

    function validateCommentLength(input) {
        const maxLength = 255;
        if (input.value.length == maxLength) {
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
            text: 'Successfully submit ACAR.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

@if(session('after_decline_acar'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully decline ACAR.',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    </script>
@endif

<script>
    @if(session('swal'))
        Swal.fire({
            icon: '{{ session('swal.type') }}', // 'success', 'error', 'warning', 'info', 'question'
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}'
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
        });
    @endif
</script>


@endsection
