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
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .section-header {
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
    }round-color: var(--hover-bg);
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
        font-size: 14px;
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
        font-size: 12px;
    }

    .announcement-icon {
        color: #6666ff;
        margin-right: 15px;
        font-size: 12px;
    }

    .actions-link {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 12px;
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
        font-size: 12px;
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

    .btn-outline-primary,
    .btn-outline-success {
        font-weight: 1000;
        padding: 0.5rem 1rem;
        border-width: 2px;
    }

    .btn-outline-primary:hover,
    .btn-outline-success:hover {
        transform: translateY(-2px);
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        color: #2c3e50;
    }

    /* Custom Close Button Styles */
    .modal-header .close {
        padding: 0;
        margin: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #f1f3f5;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .modal-header .close:hover {
        background-color: #e9ecef;
        transform: rotate(90deg);
    }

    .modal-header .close i {
        font-size: 16px;
        color: #6c757d;
    }

    .activity-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 10px;
    }

    .activity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .activity-icon {
        background-color: rgba(0, 102, 204, 0.1);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .activity-card:nth-child(2) .activity-icon {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }

    /* File Upload Specific Styles */
    .upload-icon {
        background-color: rgba(220, 53, 69, 0.1);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .file-upload-wrapper {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .file-upload-wrapper:hover {
        border-color: #0066cc;
        background-color: rgba(0, 102, 204, 0.03);
    }

    .file-upload-area {
        cursor: pointer;
    }

    .file-input {
        position: absolute;
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        z-index: -1;
    }

    .selected-file-info {
        background-color: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .file-name {
        max-width: 200px;
    }

    .btn-remove-file {
        background: none;
        border: none;
        color: #dc3545;
        padding: 0;
    }

    .activity-item:hover {
        transform: scale(1.01);
        border-color: #d70000;
        cursor: pointer;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="p-3 mb-3 shadow-soft" style="background-color: #ffde9e;">
            <ol class="breadcrumb mb-0" style="background-color: #fff3c0;">
                <li class="breadcrumb-item">Home</li>
                @switch(session('role'))
                    @case('teacher')
                        <li class="breadcrumb-item" ><a href="{{url( '/teacher/eca/section')}}">Eca</a></li>
                        @break
                    @case('student')
                        <li class="breadcrumb-item"><a href="{{url( '/student/course/')}}">Eca</a></li>
                        @break
                    @case('parent')
                        <li class="breadcrumb-item"><a href="{{url( '/parent/course/')}}">Eca</a></li>
                        @break
                    @default
                        
                @endswitch
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
            </nav>
        </div>
    </div>
    
    <div class="card" style="background-color: #ffde9e;border-radius: 12px;">
        <div class="card-header">
            <div class="course-info d-flex justify-content-start align-items-center">
                <img loading="lazy" src="{{ asset('storage/' . $subject->icon) }}" alt="icon" style="width: 32px; height: 32px;">
                <h3 class="ml-2">
                    {{ $subject->name }}
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-6">
                    <div class="small-box d-flex justify-content-between align-items-center shadow-soft px-4" style="min-height: 110px;background-color: #ffe8d6; border-radius: 12px;">
                        <div class="inner">
                            <h3>{{ $material }}</h3>
                            <p>Total Materials</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/underline.png') }}" class="img-fluid" style="max-height: 60px;" alt="Assessment Icon">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="small-box d-flex justify-content-between align-items-center shadow-soft px-4" style="min-height: 110px; background-color: #ffe8d6; border-radius: 12px;">
                        <div class="inner">
                            <h3></h3>
                            <p>Total Assessments</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/assessment.png') }}" class="img-fluid" style="max-height: 60px;" alt="Assessment Icon">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="small-box d-flex justify-content-between align-items-center shadow-soft px-4" style="min-height: 110px;background-color: #ffe8d6;border-radius:12px;">
                        <div class="inner">
                            <h3></h3>
                            <p>Active Assessments</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/grade.png') }}" class="img-fluid" style="max-height: 60px;" alt="Assessment Icon">
                        </div>
                    </div>
                </div>
            </div>
            <div class="post">
                <p class="text-bold text-dark text-lg">Students :</p>
                <div class="row">
                    @foreach ($student as $st)
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $st['student']->profil)}}" alt="" loading="lazy">
                            <span class="username">
                                <a class="text-dark text-lg" href="#">{{ucwords(strtolower($st['student']->name))}}</a>
                            </span>
                            <span class="description text-dark">{{ucwords(strtolower($st['student']['grade']->name))}} - {{ucwords(strtolower($st['student']['grade']->class))}}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="card" style="background-color: #ffde9e;border-radius: 12px;">
        <div class="card-body">
            <div class="row col-12">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sort By Month :</label>
                        <select name="month" class="form-control" id="month-select">
                            <option value="all" selected>All Month</option>
                            @foreach ($course as $month => $value)
                                <option value="{{ $month }}">
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    
    <div id="sections-container">
        @foreach ($course as $section => $month)
            <div class="section shadow-soft" style="background-color: #ffde9e;">
                <div class="section-header d-flex justify-content-start align-items-center"
                    onclick="toggleSection(this)">
                    <div class="section-left d-flex align-items-center">
                        <i class="fas fa-chevron-down chevron-icon"></i>
                        <i>
                            <img loading="lazy" src="{{ asset('images/month.png') }}" alt="{{ $section }}"
                                style="width: 21px; height: 21px;">
                        </i>
                        <h2 class="section-title pl-2 mb-0">{{ $section }}</h2>
                    </div>
                </div>
                <div class="section-content">
                    @foreach ($month as $index => $week)
                        <div class="week">
                            <div class="section-header d-flex justify-content-between align-items-center text-end"
                                onclick="toggleWeek(this)">
                                <div class="section-left d-flex align-items-center">
                                    <i>
                                        <img loading="lazy" src="{{ asset('images/timetable.png') }}" alt="{{ $week }}"
                                            style="width: 21px; height: 21px;">
                                    </i>
                                    <h2 class="section-title pl-2 mb-0">{{ $week }}</h2>
                                </div>
                            </div>
                        </div>
    
    
                        <div class="section-activities p-4">
                            @php
                                $activities = \App\Models\Eca_activity::where('section_id', $index)
                                    ->where('eca_id', $subject->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

                                
                                

                                if (session('role') == 'student') {
                                    $studentId = \App\Models\Student::where('user_id', session('id_user'))->value('id');
                                    $studentActivity =  \App\Models\Student_eca_activity::where('section_id', $index)
                                        ->where('eca_id', $subject->id)
                                        ->where('student_id', $studentId)
                                        ->get();
                                }
                                elseif (session('role') == 'parent') {
                                    $studentId = \App\Models\Student::where('id', session('studentId'))->value('id');
                                    $studentActivity =  \App\Models\Student_eca_activity::where('section_id', $index)
                                        ->where('eca_id', $subject->id)
                                        ->where('student_id', $studentId)
                                        ->get();
                                }

                                if (session('role') == 'teacher') {
                                    $attendanceEca = \App\Models\AttendanceEca::with(['student'])->where('section_id', $index)
                                        ->where('eca_id', $subject->id)
                                        ->get();

                                    $studentActivities = \App\Models\Student_eca_activity::with(['student'])
                                        ->where('section_id', $index)
                                        ->where('eca_id', $subject->id)
                                        ->get();
                                }

                                // dd($studentActivities->count());
                                // dd(count($studentActivity));
                            @endphp

                            @if ($activities->count() > 0)
                                @foreach ($activities as $activity)
                                    <div class="activity-item shadow-soft mb-2 p-4 border" style="background-color: #ffe8d6;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1">{{ $activity->title }}</h5>
    
                                                @if ($activity->description)
                                                    <p class="text-muted mb-1">{{ $activity->description }}</p>
                                                @endif
    
                                            </div>
                                        </div>
                                        @if ($activity->file_path)
                                            @php
                                                $ext = strtolower(pathinfo($activity->file_path, PATHINFO_EXTENSION));
                                                $imageExt = ['png','jpg','jpeg','heic'];
                                                $videoExt = ['mp4','webm','ogg','mkv'];
                                            @endphp

                                            <div class="mt-2">
                                                @if (in_array($ext, $videoExt))
                                                    {{-- Jika video tampilkan player --}}
                                                    <video width="60%" height="auto" controls>
                                                        <source src="{{ Storage::url($activity->file_path) }}" type="video/{{ $ext }}">
                                                        Your browser does not support the video tag.
                                                    </video>

                                                    {{-- Tombol download sebagai fallback --}}
                                                    <div class="mt-2">
                                                        <a href="{{ Storage::url($activity->file_path) }}" class="btn btn-secondary" download>
                                                            <i class="fas fa-download"></i> Download Video
                                                        </a>
                                                    </div>
                                                @elseif(in_array($ext, $imageExt))
                                                    <img src="{{ Storage::url($activity->file_path) }}" alt="Submitted Image" class="img-fluid rounded" style="width: 30%; height: auto;">
                                                @else
                                                    {{-- Jika bukan video, cek apakah embed atau link biasa --}}
                                                    <a
                                                        @if ($activity->embed)
                                                            href="/view/material/{{ $activity->id }}"
                                                        @else
                                                            href="{{ Storage::url($activity->file_path) }}"
                                                        @endif
                                                        class="btn btn-warning" target="_blank">
                                                        <i class="fas fa-book"></i> See Material
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
    
                                        @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                                            <div class="d-flex justify-content-end mt-3">
                                                {{-- <a href="{{ route('subject.edit-activity.super', ['role' => session('role'), 'id' => $activity->id]) }}"
                                                    class="btn btn-sm btn-outline-secondary mr-2" title="Edit Activity">
                                                    <i class="fas fa-pencil"></i>
                                                </a> --}}
                                                <a class="btn btn-sm btn-outline-danger" title="Delete Activity"
                                                    data-id="{{ $activity->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#delete-activity-{{ $activity->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
    
                                    <!-- Modal Delete Activity -->
                                    <div class="modal fade" id="delete-activity-{{ $activity->id }}"
                                        tabindex="-1"aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content" style="background-color: #ffe8d6;border-radius:36px;">
                                                <div class="modal-body" class="text-center">
                                                    <div class="row d-flex px-4">
                                                        <div class="col-3 align-items-end justify-content-end">
                                                            <img src="{{ asset('images/greta-face.png')}}" style="width:100%; height:100%;">
                                                        </div>
                                                        <div class="col-9 d-flex justify-content-center align-items-center">
                                                            <div>
                                                                <p class="text-lg text-center">
                                                                    Are you sure want to delete this activity ?
                                                                </p>
                                                                @if (session('role') == 'superadmin' || session('role') == 'admin')
                                                                    <form
                                                                        action="{{ route('delete-activity.super', ['role' => session('role'), 'id' => $activity->id]) }}"
                                                                        method="POST">
                                                                    @elseif (session('role') == 'teacher')
                                                                        <form
                                                                            action="{{ route('delete-activity-eca.teacher', ['id' => $activity->id]) }}"
                                                                            method="POST">
                                                                @endif
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="d-flex justify-content-center">
                                                                    <button type="button" class="btn btn-secondary mr-2" data-bs-dismiss="modal">Close</button>    
                                                                    <button type="submit" class="btn btn-danger">Yes</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (session('role') == 'student')
                                        @if($studentActivity->count() > 0)
                                            <div class="modal fade" id="delete-your-activity-{{ $studentActivity[0]->id }}"
                                                tabindex="-1"aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" style="background-color: #ffe8d6;border-radius:36px;">
                                                        <div class="modal-body" class="text-center">
                                                            <div class="row d-flex px-4">
                                                                <div class="col-3 align-items-end justify-content-end">
                                                                    <img src="{{ asset('images/greta-face.png')}}" style="width:100%; height:100%;">
                                                                </div>
                                                                <div class="col-9 d-flex justify-content-center align-items-center">
                                                                    <div>
                                                                        <p class="text-lg text-center">
                                                                            Are you sure want to delete your activity ?
                                                                        </p>
                                                                        <form
                                                                            action="{{ route('delete-activity-eca.student', ['id' => $studentActivity[0]->id]) }}"
                                                                            method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="d-flex justify-content-center">
                                                                            <button type="button" class="btn btn-secondary mr-2" data-bs-dismiss="modal">Close</button>    
                                                                            <button type="submit" class="btn btn-danger">Yes</button>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                
                                
                                @if (session('role') == 'teacher')
                                    <br>
                                    <strong>Student Attendance :</strong>
                                    <br>
                                    @if ($attendanceEca->count() > 0)
                                        <div class="row">
                                            @foreach ($attendanceEca as $attendance)
                                                <div class="user-block shadow-soft p-3 mx-2" style="background-color: #fff3c0;">
                                                    <div class="col-12">
                                                        <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $attendance->student->profil)}}" alt="user image">
                                                        <span class="username">
                                                            <a class="text-dark text-sm" href="">{{ucwords(strtolower($attendance->student->name))}}</a>
                                                            <br>
                                                            @if ($attendance->present == true)
                                                                <span class="badge badge-success text-xs">Present</span>
                                                            @elseif ($attendance->alpha == true)
                                                                <span class="badge badge-warning text-xs">Alpha</span>
                                                            @elseif ($attendance->permission == true)
                                                                <span class="badge badge-info text-xs">Permission ({{$attendance->information}})</span>
                                                            @endif
                                                        </span>
                                                        <span class="description text-dark text-sm">Checked at - {{ \Carbon\Carbon::parse($attendance->created_at)->format('d M Y H:i') }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('attendanceEcaStudent') }}">
                                            @csrf
                                            @method('POST')
                                            
                                            <table class="table table-striped projects">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%">#</th>
                                                        <th style="width: 20%">Student</th>
                                                        <th >Attendance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($student as $st)
                                                        @php
                                                            $student[$st['student']->id] = [
                                                                'name' => $st->name,
                                                                'present' => false,
                                                                'alpha' => false,
                                                                'permission' => false,
                                                                'comment' => '',
                                                            ];
                                                        @endphp

                                                        <tr id="{{ 'index_grade_' . $st['student']->id }}">
                                                            <td>
                                                                <img class="img-circle img-bordered-sm" 
                                                                    src="{{asset('storage/file/profile/'. $st['student']->profil)}}" 
                                                                    alt="" loading="lazy" style="width:100%;"></td>
                                                            <td><a>{{ucwords(strtolower($st['student']->name))}} <br> {{ucwords(strtolower($st['student']['grade']->name))}} - 
                                                                    {{ucwords(strtolower($st['student']['grade']->class))}}</a></td>
                                                            <td colspan="2">
                                                                <div class="input-group">
                                                                <input name="section_id" type="number" class="form-control d-none" value="{{$index}}">
                                                                <input name="eca_id" type="number" class="form-control d-none" value="{{ $subject->id }}">
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                <div class="form-check me-2">
                                                                    <input id="present{{ $loop->index + 1 }}" name="status[{{ $st['student']->id }}]" class="form-check-input absence-type" type="checkbox" value="present" id="present">
                                                                    <label class="form-check-label" for="present">
                                                                            Present
                                                                    </label>
                                                                </div>
                                                                <div class="form-check me-2 mx-2">
                                                                    <input id="alpha{{ $loop->index + 1 }}" name="status[{{ $st['student']->id }}]" class="form-check-input absence-type" type="checkbox" value="alpha" id="absent">
                                                                    <label class="form-check-label" for="absent">
                                                                            Alpha
                                                                    </label>
                                                                </div>
                                                                <div class="form-check me-2 mx-2">
                                                                    <input id="permission{{ $loop->index + 1 }}" name="status[{{ $st['student']->id }}]" class="form-check-input absence-type" type="checkbox" value="permission" id="permission">
                                                                    <label class="form-check-label" for="permission">
                                                                            Permission
                                                                    </label>
                                                                </div>
                                                                <div class="flex-grow-1 comment-container">
                                                                    <input id="comment{{ $loop->index + 1 }}" name="comment[{{ $st['student']->id }}]" type="text" class="form-control comment-type" placeholder="Information">
                                                                </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="card-footer">
                                                <div class="d-flex align-items-center float-right">
                                                    <button type="button" class="btn btn-info mr-2" id="present_all_btn"> <i class="fas fa-check"></i> All Present</button>
                                                    <button type="submit" class="btn btn-success" name="present_attend">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                    
                                    @if ($studentActivities->count() > 0)
                                        <br>
                                        <strong>Student Activities : </strong>
                                        <br>
                                        <div class="row">
                                            @foreach ($studentActivities as $activities)
                                                <div class="col-md-4 mb-3">
                                                    <div class="user-block shadow-soft p-3" style="background-color: #fff3c0;">
                                                        <div class="row">
                                                            <div class="col-9">
                                                                <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $activities->student->profil)}}" alt="user image">
                                                                <span class="username">
                                                                    <a class="text-dark text-sm" href="#">{{ucwords(strtolower($activities->student->name))}}</a>
                                                                </span>
                                                                <span class="description text-dark text-sm">Upload at - {{ \Carbon\Carbon::parse($activities->created_at)->format('d M Y H:i') }}</span>
                                                            </div>
                                                            <div class="col-3">
                                                                <a class="btn btn-danger" href="{{ Storage::url($activities->file_path) }}">
                                                                    <i class="fas fa-eye"></i>
                                                                    See
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-center text-muted mt-5">Students doesnt send their activity.</p>
                                    @endif
                                @endif

                                @if (session('role') == 'student' || session('role') == 'parent')
                                    @if (count($studentActivity) > 0)
                                        @php
                                            $ext = strtolower(pathinfo($studentActivity[0]->file_path, PATHINFO_EXTENSION));
                                            $videoExt = ['mp4','webm','ogg','mkv'];
                                            $imageExt = ['png','jpg','jpeg','heic'];
                                        @endphp

                                        <div class="mt-2">
                                            Activity Submission :
                                            <br>
                                            @if (in_array($ext, $videoExt))
                                                {{-- Jika video tampilkan player --}}
                                                <video width="100%" height="auto" controls>
                                                    <source src="{{ Storage::url($studentActivity[0]->file_path) }}" type="video/{{ $ext }}">
                                                    Your browser does not support the video tag.
                                                </video>

                                                {{-- Tombol download sebagai fallback --}}
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($studentActivity[0]->file_path) }}" class="btn btn-secondary" download>
                                                        <i class="fas fa-download"></i> Download Video
                                                    </a>
                                                </div>
                                            @elseif (in_array($ext, $imageExt))
                                                {{-- Jika gambar tampilkan image --}}
                                                <img src="{{ Storage::url($studentActivity[0]->file_path) }}" alt="Submitted Image" class="img-fluid rounded" style="width: 30%; height: auto;">

                                                {{-- Tombol download sebagai fallback --}}
                                            @else
                                                {{-- Jika bukan video, cek apakah embed atau link biasa --}}
                                                <a
                                                    href="{{ Storage::url($studentActivity[0]->file_path) }}"
                                                    class="btn btn-warning" target="_blank">
                                                    <i class="fas fa-book"></i> See Material
                                                </a>
                                            @endif

                                            @if(session('role') == 'student')
                                                <a class="btn btn-sm btn-outline-danger" title="Delete Activity"
                                                    data-id="{{ $studentActivity[0]->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#delete-your-activity-{{ $studentActivity[0]->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
                                            <br>
                                            Upload at - {{ \Carbon\Carbon::parse($student[0]->created_at)->format('d M Y H:i') }}
                                        </div>
                                    @else
                                        <div class="alert alert-warning mt-3" role="alert">
                                            You have not submitted this activity yet. Please submit it as soon as possible.
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="mb-3">
                                                <button type="button"
                                                    class="add-activity-button btn btn-sm btn-outline-primary d-flex justify-content-center align-items-center p-2 rounded-3 shadow-sm border-2"
                                                    style="color: #007bff; border-color: #007bff; background-color: transparent; transition: all 0.3s ease;border-radius: 18px;"
                                                    data-bs-toggle="modal" data-bs-target="#modalstudent{{ $index }}"
                                                    data-section-id="{{ $index }}">
                                                    <div class="px-2 d-flex align-items-center">
                                                        <i class="fas fa-plus me-2"></i>
                                                        <span>Add Activity</span>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @else
                                <p class="text-center text-muted">No activities available for this section.</p>
                            @endif
                        </div>
    
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="mb-3">
                                @if (in_array(session('role'), ['admin', 'superadmin', 'teacher']))
                                    <button type="button"
                                        class="add-activity-button btn btn-sm btn-outline-primary d-flex justify-content-center align-items-center p-2 rounded-3 shadow-sm border-2"
                                        style="color: #007bff; border-color: #007bff; background-color: transparent; transition: all 0.3s ease;border-radius: 18px;"
                                        data-bs-toggle="modal" data-bs-target="#modal{{ $index }}"
                                        data-section-id="{{ $index }}">
                                        <div class="px-2 d-flex align-items-center">
                                            <i class="fas fa-plus me-2"></i>
                                            <span>Add Activity</span>
                                        </div>
                                    </button>
                                @endif
                            </div>
                        </div>
    
                        <!-- Modal Add Activity-->
                        @if (session('role') == 'teacher')
                            <div class="modal fade" id="modal{{ $index }}" tabindex="-1"
                                aria-labelledby="modalLabel-{{ $section }}-{{ $index }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel-{{ $section }}">
                                                <i class="fas fa-clipboard-list mr-2"></i>Form Activity
                                            </h5>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                            </button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form
                                                @if (session('role') == 'admin' || session('role') == 'superadmin') action="{{ route('subject.store-section', ['role' => session('role'), 'id' => $subject->id, 'grade_id' => $grade_id]) }}"    
                                                    action="{{ route('subject.create-section.super', [
                                                            'role' => session('role'),
                                                            'id' => $subject->id,
                                                            'grade_id' => $grade_id,
                                                            ]) }}" 
                                                @elseif (session('role') == 'teacher')
                                                    action="{{ route('store-eca-activity.teacher', ['role' => session('role'), 'id' => $subject->id]) }}" 
                                                @endif
                                                method="POST" enctype="multipart/form-data" class="pt-2">
                                                @csrf

                                                <div class="row">
                                                    <input type="number" class="form-control d-none" name="section_id"
                                                        value="{{ $index }}" readonly>
                                                    <input type="number" class="form-control d-none" name="eca_id"
                                                        value="{{ $subject->id }}" readonly>
                                                        
                                                    <div class="col-md-12 mb-4">
                                                        <div class="form-group">
                                                            <label for="title" class="form-label fw-semibold">Activity Name<span
                                                                class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="title"
                                                                placeholder="Enter a descriptive title for this resource" required>
                                                            <div class="form-text small text-muted">Choose a clear, descriptive title that
                                                                helps students identify the content</div>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-12 mb-4">
                                                        <div class="form-group">
                                                            <label for="title" class="form-label fw-semibold">Description<span
                                                                class="text-danger">*</span></label>
                                                            <textarea class="form-control" name="description" required></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="row grid md:flex col-12">
                                                        <div class="col-12 mb-4">
                                                            <label for="file" class="form-label fw-semibold">Upload Content/Materi/Acivity <span
                                                            class="text-danger">*</span></label>
                                                            <div class="file-upload-wrapper">
                                                                <div class="file-upload-area text-center p-4">
                                                                    <input type="file" class="file-input" name="file" required>
                                                                    <i class="fas fa-cloud-upload-alt fa-2x mb-3 text-primary"></i>
                                                                    <h6 class="mb-2">Drag and drop your file here</h6>
                                                                    <p class="text-muted small mb-2">or</p>
                                                                    <button type="button" class="btn btn-outline-primary btn-sm browse-file">Browse Files</button>
                                                                    {{-- <p class="text-muted small mt-2">Maximum file size: 10MB</p> --}}
                                                                </div>
                                                                <div class="selected-file mt-3 d-none">
                                                                    {{-- <div class="selected-file-info p-2 d-flex align-items-center">
                                                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                                                        <span class="file-name text-truncate"></span>
                                                                        <span class="file-size ms-2 text-muted small"></span>
                                                                        <button type="button" class="btn-remove-file ms-auto btn btn-sm">
                                                                            <i class="fas fa-times-circle"></i>
                                                                        </button>
                                                                    </div> --}}
                                                                </div>
                                                            </div>
                                                            {{-- <div class="form-text small text-muted mt-2">Only PDF format is supported for
                                                                consistency and compatibility
                                                            </div> --}}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mb-2">
                                                        <div class="alert alert-warning small py-2" role="alert">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <strong>Resource Guidelines : </strong> Ensure you have proper rights to share
                                                            this material. Large files may take longer to upload.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end mt-4">
                                                    <button type="button" class="btn btn-outline-secondary mr-4"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save mr-2"></i>Save Activity
                                                    </button>
                                                </div>
                                            </form>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Modal Activity Student --}}
                        @if (session('role') == 'student')
                            <div class="modal fade" id="modalstudent{{ $index }}" tabindex="-1"
                                aria-labelledby="modalLabel-{{ $section }}-{{ $index }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel-{{ $section }}">
                                                <i class="fas fa-clipboard-list mr-2"></i>Form Upload Student Activity
                                            </h5>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                            </button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form
                                                action="{{ route('store-eca-activity.student') }}" 
                                                method="POST" enctype="multipart/form-data" class="pt-2">
                                                @csrf

                                                <div class="row">
                                                    <input type="number" class="form-control d-none" name="section_id"
                                                        value="{{ $index }}" readonly>
                                                    <input type="number" class="form-control d-none" name="eca_id"
                                                        value="{{ $subject->id }}" readonly>
                                                
                                                    <div class="col-md-12 mb-4">
                                                        <div class="form-group">
                                                            <label for="title" class="form-label fw-semibold">Description<span
                                                                class="text-danger">*</span></label>
                                                            <textarea class="form-control" name="description" required></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="row grid md:flex col-12">
                                                        <div class="col-12 mb-4">
                                                            <label for="file" class="form-label fw-semibold">Upload your activity <span
                                                            class="text-danger">*</span></label>
                                                            <div class="file-upload-wrapper">
                                                                <div class="file-upload-area text-center p-4">
                                                                    <input type="file" class="file-input" name="file">
                                                                    <i class="fas fa-cloud-upload-alt fa-2x mb-3 text-primary"></i>
                                                                    <h6 class="mb-2">Drag and drop your file here</h6>
                                                                    <p class="text-muted small mb-2">or</p>
                                                                    <button type="button" class="btn btn-outline-primary btn-sm browse-file">Browse Files</button>
                                                                    {{-- <p class="text-muted small mt-2">Maximum file size: 10MB</p> --}}
                                                                </div>
                                                                <div class="selected-file mt-3 d-none">
                                                                    {{-- <div class="selected-file-info p-2 d-flex align-items-center">
                                                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                                                        <span class="file-name text-truncate"></span>
                                                                        <span class="file-size ms-2 text-muted small"></span>
                                                                        <button type="button" class="btn-remove-file ms-auto btn btn-sm">
                                                                            <i class="fas fa-times-circle"></i>
                                                                        </button>
                                                                    </div> --}}
                                                                </div>
                                                            </div>
                                                            {{-- <div class="form-text small text-muted mt-2">Only PDF format is supported for
                                                                consistency and compatibility
                                                            </div> --}}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mb-2">
                                                        <div class="alert alert-warning small py-2" role="alert">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <strong>Resource Guidelines : </strong> Ensure you have proper rights to share
                                                            this material. Large files may take longer to upload.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end mt-4">
                                                    <button type="button" class="btn btn-outline-secondary mr-4"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save mr-2"></i>Save Activity
                                                    </button>
                                                </div>
                                            </form>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[id^="modal"]').forEach(modal => {
        modal.addEventListener('shown.bs.modal', () => {
            
            const fileInput   = modal.querySelector('.file-input');
            const fileArea    = modal.querySelector('.file-upload-area');
            const browseBtn   = modal.querySelector('.browse-file');
            const selectedFile= modal.querySelector('.selected-file');
            const fileName    = modal.querySelector('.file-name');
            const fileSize    = modal.querySelector('.file-size');
            const removeBtn   = modal.querySelector('.btn-remove-file');
    
            // console.log(fileInput, fileArea, browseBtn, selectedFile, fileName, fileSize, removeBtn);
    
            // Klik browse button → trigger input file
            browseBtn.addEventListener('click', () => fileInput.click());
    
            // Klik area upload → buka input file (selain tombol browse)
            fileArea.addEventListener('click', e => {
                if (!e.target.closest('.browse-file')) {
                    fileInput.click();
                }
            });
    
            // Ketika ada file dipilih
            fileInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const ext  = file.name.split('.').pop().toLowerCase();
    
                    
                    // fileName.textContent = file.name;

                    let formattedSize;
                    if (file.size < 1024) {
                        formattedSize = file.size + ' bytes';
                    } else if (file.size < 1024 * 1024) {
                        formattedSize = (file.size / 1024).toFixed(1) + ' KB';
                    } else {
                        formattedSize = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                    }

                    // fileSize.textContent = formattedSize;
                    selectedFile.classList.remove('d-none');
                    
                    renderFiles(this.files);
                
                }
            });

            function renderFiles(files) {
                selectedFile.innerHTML = ''; // reset
                if (files.length > 0) {
                    selectedFile.classList.remove('d-none');
                    Array.from(files).forEach((file, index) => {
                        const ext = file.name.split('.').pop().toLowerCase();

                        let formattedSize;
                        if (file.size < 1024) {
                            formattedSize = file.size + ' bytes';
                        } else if (file.size < 1024 * 1024) {
                            formattedSize = (file.size / 1024).toFixed(1) + ' KB';
                        } else {
                            formattedSize = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                        }

                        const fileItem = document.createElement('div');
                        fileItem.classList.add('selected-file-info','p-2','d-flex','align-items-center','border','rounded','mb-2');

                        fileItem.innerHTML = `
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <span class="file-name text-truncate">${file.name}</span>
                            <span class="file-size ms-2 text-muted small">(${formattedSize})</span>
                            <button type="button" class="btn-remove-file ms-auto btn btn-sm text-danger" data-index="${index}">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        `;

                        selectedFile.appendChild(fileItem);
                    });

                    // Attach remove events
                    selectedFile.querySelectorAll('.btn-remove-file').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const index = btn.getAttribute('data-index');
                            removeFile(index);
                        });
                    });
                } else {
                    selectedFile.classList.add('d-none');
                }
            }

        // Function hapus file tertentu
        function removeFile(index) {
            const dt = new DataTransfer();
            Array.from(fileInput.files).forEach((file, i) => {
                if (i != index) dt.items.add(file);
            });
            fileInput.files = dt.files;
            renderFiles(fileInput.files);
        }
    
            // Remove file
            removeBtn.addEventListener('click', e => {
                e.stopPropagation();
                fileInput.value = '';
                selectedFile.classList.add('d-none');
            });
    
            // Drag & drop handler
            ['dragenter','dragover','dragleave','drop'].forEach(evt => {
                fileArea.addEventListener(evt, e => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
    
            ['dragenter','dragover'].forEach(evt => {
                fileArea.addEventListener(evt, () => {
                    fileArea.classList.add('border-primary');
                    fileArea.style.backgroundColor = 'rgba(0, 102, 204, 0.05)';
                });
            });
    
            ['dragleave','drop'].forEach(evt => {
                fileArea.addEventListener(evt, () => {
                    fileArea.classList.remove('border-primary');
                    fileArea.style.backgroundColor = '';
                });
            });
    
            // Handle drop
            fileArea.addEventListener('drop', e => {
                const files = e.dataTransfer.files;
                fileInput.files = files;
    
                if (files.length > 0) {
                    const file = files[0];
                    fileName.textContent = file.name;
    
                    let formattedSize;
                    if (file.size < 1024) {
                        formattedSize = file.size + ' bytes';
                    } else if (file.size < 1024 * 1024) {
                        formattedSize = (file.size / 1024).toFixed(1) + ' KB';
                    } else {
                        formattedSize = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                    }
    
                    fileSize.textContent = formattedSize;
                    selectedFile.classList.remove('d-none');
                }
            });
        });
    });
});


    document.addEventListener('DOMContentLoaded', function() {
        let checkboxes = document.querySelectorAll('.absence-type');
        let presentAllBtn = document.getElementById('present_all_btn');

        presentAllBtn.addEventListener('click', function() {
            checkboxes.forEach(function(checkbox) {
            if (checkbox.id.startsWith('present')) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
                let currentRow = checkbox.closest('tr');
                let commentInput = currentRow.querySelector('.comment-container input');
                commentInput.value = ''; // Reset comment value
            }
            });
        });

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
            let currentRow = this.closest('tr');
            let checkboxesInRow = currentRow.querySelectorAll('.absence-type');

            checkboxesInRow.forEach(function(cb) {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
            });
        });
    });
</script>

@if (session('success_add_activity'))
    <script>
        Swal.fire({
            title: 'Successfull Add Material',
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            timer: 1800,
            showConfirmButton: false,
        });
    </script>
@endif

@if (session('success_edit_section'))
    <script>
        Swal.fire({
            title: 'Successfull Edit Section',
            imageUrl: '/images/happy.png', 
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            timer: 1800, 
            showConfirmButton: false,
        });
    </script>
@endif

@if (session('success_edit_activity'))
    <script>
        Swal.fire({
            title: 'Successfull Edit Activity',
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            timer: 1800,
            showConfirmButton: false,
        });
    </script>
@endif

 @if (session('success_delete_activity'))
    <script>
        Swal.fire({
            title: 'Successfull Delete Activity',
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            timer: 1800,
            showConfirmButton: false,
        });
    </script>
@endif

@if (session('success_post_attendance'))
    <script>
        Swal.fire({
            title: 'Successfull Submit Attendance',
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            timer: 1800,
            showConfirmButton: false,
        });
    </script>
@endif

@if (session('success_delete_ebook'))
    <script>
        Swal.fire({
            title: 'Successfull Delete E-Book',
            timer: 1800, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false, // Sembunyikan tombol "OK",
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
        });
    </script>
@endif

@if (session('success_add_ebook'))
    <script>
        Swal.fire({
            
            title: 'Successfull Add Ebook',
            timer: 1800, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false, // Sembunyikan tombol "OK",
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
        });
    </script>
@endif

@if (session('success_change_ebook'))
    <script>
        Swal.fire({
            title: 'Successfull Change Ebook',
            timer: 1800, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false, // Sembunyikan tombol "OK",
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
        });
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

@endsection
