@extends('layouts.admin.master')
@section('content')
    <style>
        :root {
            --primary-color: #0066cc;
            --text-color: #242424;
            --border-color: #e5e7eb;
            --hover-bg: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            margin: 0;
        }

        .course-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section {
            background: white;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            user-select: none;
        }

        .section-header:hover {
            background-color: var(--hover-bg);
        }

        .section-left {
            display: flex;
            align-items: center;
        }

        .chevron-icon {
            transition: transform 0.3s ease;
            color: #666;
            margin-right: 15px;
        }

        .collapsed .chevron-icon {
            transform: rotate(-90deg);
        }

        .section-title {
            font-size: 12px;
            color: var(--text-color);
            margin: 0;
        }

        .section-content {
            border-top: 1px solid var(--border-color);
            padding: 6px;
            display: block;
            margin-bottom: 0;
        }

        .collapsed .section-content {
            display: none;
        }

        .task-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0;
        }

        .task-icon {
            color: #ff1493;
            margin-right: 15px;
            font-size: 20px;
        }

        .announcement-icon {
            color: #6666ff;
            margin-right: 15px;
            font-size: 20px;
        }

        .actions-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }

        .controls {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        .task-dates {
            margin-top: 5px;
            font-size: 14px;
            color: #666;
        }

        .task-dates span {
            display: block;
            margin-bottom: 3px;
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

    </style>

    <div class="card card-light">
        <div class="card-header">
            <div class="course-info d-flex mb-4">
                <img loading="lazy" src="{{ asset('storage/'.$subject->icon) }}" alt="icon"  style="width: 32px; height: 32px;" >
                <h3 class="ml-2">
                    {{ $subject->name_subject }} {{ $subject->grade->first()->name }} - {{ $subject->grade->first()->class }}
                </h3>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                @if ($ebook == null)
                    @if (in_array(session('role'), ['admin', 'superadmin', 'teacher']))
                        <div class="col-lg-3 col-6">
                            <div class="inner">
                                <a class="small-box bg-warning d-flex flex-column align-items-center justify-content-center text-center" 
                                style="min-height: 110px;" 
                                >
                                    <i class="fas fa-book fa-2x"></i> 
                                    <span>E-Book not available</span>
                                </a>
                            </div>
                        </div>
                    @endif            
                @else
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-orange px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                        <a href="{{ Storage::url($ebook->file_path) }}" class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center" target="_blank">
                        
                            <!-- Ribbon -->
                            <div class="ribbon-wrapper ribbon-md">
                                <div class="ribbon bg-light">
                                    E-Book
                                </div>
                            </div>
                        
                            <!-- Bagian Utama -->
                            <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                <!-- Ikon -->
                                <div>
                                    <img loading="lazy" src="{{ asset('images/book.png') }}" alt="Book Icon" style="width: 50px; height: 50px;">
                                </div>
                                <!-- Nama Subject -->
                                <div class="inner mt-2">
                                    <p class="mb-0 text-sm fw-bold text-center">{{ $ebook->title }}</p>
                                </div>
                            </div>
                        </a>
                    </div> 
                </div>
                @endif
        
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info" style="min-height: 110px;">
                        <div class="inner">
                            <h3>{{$material}}</h3>
                            <p>Total Material</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-book"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary" style="min-height: 110px;">
                        <div class="inner">
                            <h3>{{$assessment}}</h3>
                            <p>Total Assessment</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger" style="min-height: 110px;">
                        <div class="inner">
                            <h3>{{$assessmentActive}}</h3>
                            <p>Assessment Active</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div id="sections-container">
        @foreach ($course as $section => $month)
            <div class="section">
                <div class="section-header d-flex justify-content-between align-items-center" onclick="toggleSection(this)">
                    <div class="section-left d-flex align-items-center">
                        <i class="fas fa-chevron-down chevron-icon"></i>
                        <i>
                            <img loading="lazy" src="{{ asset('images/month.png') }}" alt="{{$section}}" style="width: 21px; height: 21px;">
                        </i>
                        <h2 class="section-title pl-2 mb-0">{{ $section }}</h2>
                    </div>
                </div>
                <div class="section-content">
                    @foreach ($month as $index => $week)
                        <div class="week">
                            <div class="section-header d-flex justify-content-between align-items-center" onclick="toggleWeek(this)">
                                <div class="section-left d-flex align-items-center">
                                    <i>
                                        <img loading="lazy" src="{{ asset('images/timetable.png') }}" alt="{{$week}}" style="width: 21px; height: 21px;">
                                    </i>
                                    <h2 class="section-title pl-2 mb-0">{{ $week }}</h2>
                                </div>
                            </div>
                        </div>

                        
                        <div class="section-activities p-4">
                            @php
                                $activities = \App\Models\CourseActivities::where('section_id', $index)
                                    ->where('grade_subject_id', $gradeSubject->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

                                $assessments = \App\Models\Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                                    ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                                    ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                                    ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                                    ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                                    ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                                    ->where('exams.semester', session('semester'))
                                    ->where('exams.academic_year', session('academic_year'))
                                    ->where('subjects.id', $subject->id)
                                    ->where('grades.id', $grade_id)
                                    ->where('section_id', $index)
                                    ->orderByRaw('exams.is_active = 0 ASC')
                                    ->select('exams.id', 'type_exams.name as type_exam', 'exams.name_exam', 'exams.hasFile')
                                    ->get();

                                // dd($assessments);
                            @endphp
    
                            @if ($activities->count() > 0)
                                @foreach ($activities as $activity)
                                    <div class="activity-item mb-2 p-4 border rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1">{{ $activity->title }}</h5>
                                                
                                                @if ($activity->description)
                                                    <p class="text-muted mb-1">{{ $activity->description }}</p>
                                                @endif

                                            </div>
                                        </div>
                                        @if ($activity->file_path)
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($activity->file_path) }}"
                                                    class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-book"></i> See Material
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    
                                @endforeach
                            @endif

                            
                            @if ($assessments->count() > 0)
                                @foreach ($assessments as $assessment)
                                    {{-- {{$assessment}} --}}
                                    @if ($assessment->hasFile == 1)
                                    <div class="activity-item p-4 border rounded bg-light d-flex flex-column position-relative">
                                        <a 
                                            @if (session('role') == 'teacher')
                                                href="/teacher/dashboard/exam/detail/{{$assessment->id}}"
                                            @else
                                                href="javascript:void(0);"
                                                data-id="{{$assessment->id}}"
                                                id="set-assessment"
                                            @endif
                                            class="stretched-link text-decoration-none"
                                        ></a>
                                        
                                        <div class="d-flex align-items-center">
                                            <i>
                                                <img loading="lazy" src="{{ asset('images/exam.png') }}" alt="exam" style="width:24px; height:24px;">
                                            </i>
                                            <span class="ml-2">
                                                {{ ucwords($assessment->type_exam) }} | {{ ucwords($assessment->name_exam) }}
                                            </span>
                                        </div>
                                    </div>
                                    @else
                                        <div class="activity-item p-4 border rounded bg-light d-flex flex-column position-relative mb-2">
                                            <a 
                                                @if (session('role') == 'teacher')
                                                    href="/teacher/dashboard/exam/detail/{{$assessment->id}}"
                                                @else
                                                    href="javascript:void(0);"
                                                    data-id="{{$assessment->id}}"
                                                    id="set-assessment"
                                                @endif
                                                class="stretched-link text-decoration-none"
                                            ></a>
                                            
                                            <div class="d-flex align-items-center">
                                                <i>
                                                    <img loading="lazy" src="{{ asset('images/exam.png') }}" alt="exam" style="width:24px; height:24px;">
                                                </i>
                                                <span class="ml-2">
                                                    Assessment : {{ ucwords($assessment->type_exam) }} | {{ ucwords($assessment->name_exam) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
        <!-- Modal Add Ebook-->
        <div class="modal fade" id="ebook" tabindex="-1" aria-labelledby="modalLabel-ebook" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Ebook ({{ $subject->name_subject }}) {{ $subject->grade->first()->name }} - {{ $subject->grade->first()->class }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <form
                                @if (session('role') == 'admin' || 'superadmin')
                                action="{{ route('subject.store-section', ['role' => session('role'), 'id' => $subject->id, 'grade_id' => $grade_id]) }}"    
                                @elseif (session('role') == 'teacher')
                                action="{{ route('subject.store-section.teacher', ['role' => session('role'), 'id' => $subject->id, 'grade_id' => $grade_id]) }}"    
                                @endif
                                method="POST" enctype="multipart/form-data" class="pt-2">
                                @csrf
                                <div class="form-group">
                                    <label for="title">Book Title <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
        
                                <label for="upload_file">Upload File <span style="color: red">(only format pdf) *</span></label>
                                <div class="form-group text-muted" id="file-form">
                                    <input type="file" id="file" name="file" accept=".pdf" required>
                                </div>   
        
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($ebook != null)
            <!-- Modal change ebook -->
            <div class="modal fade" id="changeBook" tabindex="-1" role="dialog" aria-labelledby="changeFileLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Change E-Book</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('change.file.ebook')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                                <label for="upload_file">Upload File <span style="color: red">(only pdf)*</span></label>
                                <div class="form-group row text-muted" id="file-form">
                                    <input type="file" id="upload_file" name="upload_file" accept=".pdf" required>
                                    <input type="number" id="ebookid" name="ebook_id" class="form-control" value="{{$ebook->id}}" hidden>
                                </div>      
                            </div>
                        <div class="modal-footer">
                            <div class="form-group row">
                                <button type="submit" class="btn btn-sm btn-primary w-100" id="submitBtn">Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @if (session('data_is_empty'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops..',
                    text: 'Data Attendance is Empty !!!',
                });
            }, 500);
        </script>
    @endif
    
    @if (session('success_add_activity'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Add Material',
                });
            }, 500);
        </script>
    @endif

    @if (session('succes_edit_section'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Edit Section',
                });
            }, 500);
        </script>
    @endif
    
    @if (session('succes_edit_activity'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Edit Activity',
                });
            }, 500);
        </script>
    @endif
    
    @if (session('succes_delete_activity'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Delete Activity',
                });
            }, 500);
        </script>
    @endif

    @if (session('succes_edit_file_activity'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Edit Activity',
                });
            }, 500);
        </script>
    @endif
    
    @if (session('success_add_ebook'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Add en Ebook',
                });
            }, 500);
        </script>
    @endif
    
    @if (session('success_change_ebook'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Change Ebook',
                });
            }, 500);
        </script>
    @endif

    <script>
        function toggleSection(header) {
            const section = header.closest('.section');
            section.classList.toggle('collapsed');
        }
        function toggleWeek(header) {
            const week = header.closest('.week');
            week.classList.toggle('collapsed');
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal');

            modal.addEventListener('show.bs.modal', function(event) {
                // Get the button that triggered the modal
                const button = event.relatedTarget;
                const sectionId = button.getAttribute('data-section-id');

                // Update the materi link
                const materiLink = modal.querySelector('.materi-link');
                const href = materiLink.getAttribute('href');
                materiLink.setAttribute('href', href.replace('__section_id__', sectionId));
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#assessment').forEach(function(button) {
                button.addEventListener('click', function() {
                    var sectionId = this.getAttribute('data-id');
                    var sessionRole = @json(session('role'));
                    var gradeSubject = {{$gradeSubject->id}};
                    var url = "{{ route('set.section.id') }}";
                    
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            id: sectionId,
                            gradeSubject: gradeSubject,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                if(sessionRole == 'teacher'){
                                    window.location.href = '/' + sessionRole + '/dashboard/exam/create';
                                }
                                else if(sessionRole == 'admin' || sessionRole == 'superadmin'){
                                    window.location.href = '/' + sessionRole + '/exams/create';
                                }
                            } else {
                                alert('Failed to set exam ID in session.');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error: ' + error);
                        }
                    });
                });
            });


            document.querySelectorAll('#set-assessment').forEach(function(button) {
                button.addEventListener('click', function() {
                    var sectionId = this.getAttribute('data-id');
                    var sessionRole = @json(session('role'));
                    if(sessionRole == 'student'){
                        var url = "{{ route('set.assessment.id.student') }}";
                    }
                    else if(sessionRole == 'parent'){
                        var url = "{{ route('set.assessment.id') }}";
                    }
                    
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            id: sectionId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.href = '/' + sessionRole + '/dashboard/exam/detail';
                            } else {
                                alert('Failed to set exam ID in session.');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error: ' + error);
                        }
                    });
                });
            });
        });
    </script>
@endsection
