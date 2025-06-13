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
                        <li class="breadcrumb-item" ><a href="{{url( '/teacher/course/')}}">Course</a></li>
                        @break
                    @case('student')
                        <li class="breadcrumb-item"><a href="{{url( '/student/course/')}}">Course</a></li>
                        @break
                    @case('parent')
                        <li class="breadcrumb-item"><a href="{{url( '/parent/course/')}}">Course</a></li>
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
            <div class="course-info d-flex">
                <img loading="lazy" src="{{ asset('storage/' . $subject->icon) }}" alt="icon" style="width: 32px; height: 32px;">
                <h3 class="ml-2">
                    ({{ $subject->name_subject }}) {{ $subject->grade->first()->name }} -
                    {{ $subject->grade->first()->class }}
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @if ($ebook == null)
                    @if (in_array(session('role'), ['admin', 'superadmin', 'teacher']))
                        <div class="col-md-3 col-6">
                            <div class="inner">
                                @if (session('role') == 'teacher')
                                    <a class="small-box bg-danger d-flex flex-column align-items-center justify-content-center text-center"
                                        style="min-height: 110px;border-radius:12px;" data-bs-toggle="modal" data-bs-target="#ebook"
                                        href="">
                                        <i class="fas fa-plus fa-2x"></i>
                                        <span>Add Ebook</span>
                                    </a>
                                @else
                                    <a href="{{ route('subject.create-section.super', [
                                        'role' => session('role'),
                                        'id' => $subject->id,
                                        'grade_id' => $grade_id,
                                    ]) }}"
                                        class="small-box bg-danger bg-info justfify-content-center text-align-center"
                                        style="min-height: 110px;border-radius:12px;">
                                        <i class="fas fa-plus"></i> Add Ebook
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="col-md-3 col-6">
                        <div class="small-box d-flex justify-content-between align-items-center shadow-soft px-4"
                            style="min-height: 110px;background-color: #ffe8d6;border-radius:12px;">
                            <a href="{{ Storage::url($ebook->file_path) }}"
                                class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center"
                                target="_blank">
    
                                <!-- Ribbon -->
                                <div class="ribbon-wrapper ribbon-md">
                                    <div class="ribbon bg-secondary">
                                        E-Book
                                    </div>
                                </div>
    
                                <!-- Bagian Utama -->
                                <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                    <!-- Ikon -->
                                    <div>
                                        <img loading="lazy" src="{{ asset('images/book.png') }}" alt="Book Icon"
                                            style="width: 50px; height: 50px;">
                                    </div>
                                    <!-- Nama Subject -->
                                    <div class="inner mt-2">
                                        <p class="mb-0 text-sm fw-bold text-center">{{ $ebook->title }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="row">
                            <div class="form-group justify-content-start grid">
                                @if (in_array(session('role'), ['admin', 'superadmin', 'teacher']))
                                    <div class="col mb-2">
                                        <a href="#" class="btn-link text-secondary hover:cursor-pointer"
                                            data-toggle="modal" data-target="#changeBook">
                                            <i class="fas fa-edit ml-1"></i> Change E-Book
                                        </a>
                                    </div>
                                @endif
                                @if (in_array(session('role'), ['admin', 'superadmin', 'teacher']))
                                    <div class="col">
                                        <a href="#" class="btn-link text-danger hover:cursor-pointer"
                                            data-toggle="modal" data-target="#deleteBook">
                                            <i class="fas fa-trash ml-1"></i> Delete E-Book
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
    
                <div class="col-md-3 col-6">
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
                <div class="col-md-3 col-6">
                    <div class="small-box d-flex justify-content-between align-items-center shadow-soft px-4" style="min-height: 110px; background-color: #ffe8d6; border-radius: 12px;">
                        <div class="inner">
                            <h3>{{ $assessment }}</h3>
                            <p>Total Assessments</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/assessment.png') }}" class="img-fluid" style="max-height: 60px;" alt="Assessment Icon">
                        </div>
                    </div>
                </div>
    
                <div class="col-md-3 col-6">
                    <div class="small-box d-flex justify-content-between align-items-center shadow-soft px-4" style="min-height: 110px;background-color: #ffe8d6;border-radius:12px;">
                        <div class="inner">
                            <h3>{{ $assessmentActive }}</h3>
                            <p>Active Assessments</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/grade.png') }}" class="img-fluid" style="max-height: 60px;" alt="Assessment Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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
                                $activities = \App\Models\CourseActivities::where('section_id', $index)
                                    ->where('grade_subject_id', $gradeSubject->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
    
                                $assessments = \App\Models\Exam::join(
                                    'grade_exams',
                                    'exams.id',
                                    '=',
                                    'grade_exams.exam_id',
                                )
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
                                    ->select(
                                        'exams.id',
                                        'type_exams.name as type_exam',
                                        'exams.name_exam',
                                        'exams.hasFile',
                                        'exams.model',
                                        'exams.is_active',
                                    )
                                    ->get();
    
                                foreach ($assessments as $assessment) {
                                    $info = \App\Models\Score::where('exam_id', $assessment->id)->first();
                                }
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
                                            <div class="mt-2">
                                                <a href="{{ Storage::url($activity->file_path) }}"
                                                    class="btn btn-warning" target="_blank">
                                                    <i class="fas fa-book"></i> See Material
                                                </a>
                                            </div>
                                        @endif
    
    
                                        @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                                            <div class="d-flex justify-content-end mt-3">
                                                <a href="{{ route('subject.edit-activity.super', ['role' => session('role'), 'id' => $activity->id]) }}"
                                                    class="btn btn-sm btn-outline-secondary mr-2" title="Edit Activity">
                                                    <i class="fas fa-pencil"></i>
                                                </a>
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
                                                                            action="{{ route('delete-activity.teacher', ['id' => $activity->id]) }}"
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
                                @endforeach
                            @endif
    
    
                            @if ($assessments->count() > 0)
                                @foreach ($assessments as $assessment)
                                    {{-- {{$assessment}} --}}
                                    @if ($assessment->hasFile == 1)
                                        <div
                                            class="activity-item shadow-soft mb-2 p-4 border d-flex flex-column position-relative" style="background-color: #ffe8d6;">
                                            <a @if (session('role') == 'admin' || session('role') == 'superadmin') href="{{ url('/' . session('role') . '/dashboard/exam/detail/' . $assessment->id) }}"
                                            @elseif (session('role') == 'teacher')
                                                href="/teacher/dashboard/exam/detail/{{ $assessment->id }}"
                                            @else
                                                href="javascript:void(0);"
                                                data-id="{{ $assessment->id }}"
                                                id="set-assessment" @endif
                                                class="stretched-link text-decoration-none"></a>
    
                                            <div class="d-flex align-items-center">
                                                <i>
                                                    <img loading="lazy" src="{{ asset('images/exam.png') }}" alt="exam"
                                                        style="width:24px; height:24px;">
                                                </i>
                                                <span class="ml-2">
                                                    {{ ucwords($assessment->type_exam) }} |
                                                    {{ ucwords($assessment->name_exam) }}
                                                </span>
                                            </div>
                                            <div class="pt-2">
                                                <i class="fas fa-clock"></i>
                                                Timeline :
                                                <span class="text-danger">
                                                    {{ \Carbon\Carbon::parse($assessment->date_exam)->format('l, d F Y') }}
                                                    <br>
                                                </span>
    
                                                <i class="fas fa-info-circle"></i>
                                                Model : {{$assessment->model}} 
                                                <span>
                                                    @switch($assessment->model)
                                                        @case("mce")
                                                            CBT (Multiple Choice & Essay)
                                                            @break
                                                        @case("mc")
                                                            CBT (Multiple Choice)
                                                            @break
                                                        @case("essay")
                                                            CBT (Essay)
                                                            @break
                                                        @case("upload")
                                                            Non-CBT / Upload File
                                                            @break
                                                        @default
                                                            Non-CBT
                                                            @break
                                                    @endswitch
                                                </span><br>
    
                                                @if (session('role') == 'parent' || session('role') == 'student')
                                                    <i class="fas fa-info-circle"></i>
                                                    Status :
                                                    @if ($info->hasFile == 1)
                                                        Already submit answer <br>
                                                        <i class="fas fa-file ml-1"></i> {{ $info->file_name }}
                                                    @else
                                                        <span class="text-danger">
                                                            You have not completed this assessment
                                                        </span>
                                                    @endif
                                                @endif
                                                @if (session('role') == 'teacher' || session('role') == 'admin' || session('role') == 'superadmin')
                                                    <i class="fas fa-info-circle"></i>
                                                    Status :
                                                    @if ($assessment->is_active)
                                                        <span class="badge badge-warning">Active</span>
                                                    @else
                                                        <span class="badge badge-secondary">Completed</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="activity-item shadow-soft mb-2 p-4 d-flex flex-column position-relative" style="background-color: #ffe8d6;">
                                            <a @if (session('role') == 'admin' || session('role') == 'superadmin') href="{{ url('/' . session('role') . '/dashboard/exam/detail/' . $assessment->id) }}"
                                                @elseif (session('role') == 'teacher')
                                                    href="/teacher/dashboard/exam/detail/{{ $assessment->id }}"
                                                @else
                                                    href="javascript:void(0);"
                                                    data-id="{{ $assessment->id }}"
                                                    id="set-assessment" @endif
                                                class="stretched-link text-decoration-none"></a>
    
                                            <div class="d-flex align-items-center">
                                                <i>
                                                    <img loading="lazy" src="{{ asset('images/exam.png') }}" alt="exam"
                                                        style="width:24px; height:24px;">
                                                </i>
                                                <span class="ml-2">
                                                    Assessment: {{ ucwords($assessment->type_exam) }} |
                                                    {{ ucwords($assessment->name_exam) }}
                                                </span>
                                            </div>
                                            <div class="pt-2">
                                                @if (session('role') !== 'parent' || session('role') !== 'student')
                                                    <i class="fas fa-clock"></i>    
                                                    Timeline :
                                                    <span class="text-danger">
                                                        {{ \Carbon\Carbon::parse($assessment->date_exam)->format('l, d F Y') }}
                                                        <br>
                                                    </span>
                                                    <i class="fas fa-info-circle"></i>
                                                    Model {{$assessment->model}} : 
                                                    <span>
                                                        @switch($assessment->model)
                                                            @case("mce")
                                                                CBT (Multiple Choice & Essay)
                                                                @break
                                                            @case("mc")
                                                                CBT (Multiple Choice)
                                                                @break
                                                            @case("essay")
                                                                CBT (Essay)
                                                                @break
                                                            @case("upload")
                                                                Non-CBT
                                                                @break
                                                            @default
                                                                Non-CBT
                                                                @break
                                                        @endswitch
                                                        <br>
                                                    </span>
                                                    <i class="fas fa-info-circle"></i>
                                                    Status :
                                                    @if ($assessment->is_active == 1)
                                                        <span class="badge badge-warning">Active</span>
                                                    @else
                                                        <span class="badge badge-secondary">Completed</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
    
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="mb-3">
                                @if (in_array(session('role'), ['admin', 'superadmin', 'teacher']))
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary d-flex justify-content-center align-items-center p-2 rounded-3 shadow-sm border-2"
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
                        <div class="modal fade" id="modal{{ $index }}" tabindex="-1"
                            aria-labelledby="modalLabel-{{ $section }}-{{ $index }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel-{{ $section }}">
                                            <i class="fas fa-clipboard-list mr-2"></i>Select Activity Type
                                        </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <p class="text-muted mb-4">Please select the type of activity you would like to
                                            create :</p>
    
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="card h-100 activity-card">
                                                    <div class="card-body text-center p-4">
                                                        <div class="activity-icon mb-3">
                                                            <i class="fas fa-book-open fa-3x text-primary"></i>
                                                        </div>
                                                        <h5 class="card-title">Learning Material</h5>
                                                        <p class="card-text text-muted">Create content for students to
                                                            study and review.</p>
                                                        <ul class="text-start small text-muted mb-4">
                                                            <li>Upload documents and resources</li>
                                                            <li>Create reading materials</li>
                                                            <li>Share educational content</li>
                                                        </ul>
                                                        <a href="{{ route('subject.create-activity', [
                                                            'role' => session('role'),
                                                            'id' => $subject->id,
                                                            'grade_id' => $grade_id,
                                                            'section_id' => $index,
                                                        ]) }}"
                                                            class="btn btn-outline-primary w-100 materi-link">
                                                            <i class="fas fa-plus-circle mr-2"></i>Create Material
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="col-md-6">
                                                <div class="card h-100 activity-card">
                                                    <div class="card-body text-center p-4">
                                                        <div class="activity-icon mb-3">
                                                            <i class="fas fa-tasks fa-3x text-success"></i>
                                                        </div>
                                                        <h5 class="card-title">Assessment</h5>
                                                        <p class="card-text text-muted">Create evaluations to test
                                                            student knowledge.</p>
                                                        <ul class="text-start small text-muted mb-4">
                                                            <li>Create quizzes and tests</li>
                                                            <li>Assign homework and projects</li>
                                                            <li>Evaluate student progress</li>
                                                        </ul>
                                                        <button type="button" id="assessment"
                                                            data-id={{ $index }}
                                                            class="btn btn-outline-success w-100">
                                                            <i class="fas fa-plus-circle mr-2"></i>Create Assessment
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <small class="text-muted me-auto">Section: {{ $section }} • Subject:
                                            {{ $subject->name_subject ?? 'Current Subject' }}</small>
                                        <button type="button" class="btn btn-sm btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

@if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
    <!-- Modal Add Ebook-->
    <div class="modal fade" id="ebook" tabindex="-1" aria-labelledby="modalLabel-ebook" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background-color: #ffe8d6;">
                <div class="modal-header" style="background-color: #ffe8d6;">
                    <div>
                        {{-- <h5 class="modal-title fw-bold">Add Ebook Resource</h5> --}}
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            <i class="fas fa-book mr-2"></i>Add Ebook Resource
                        </h5>
                        <p class="text-muted mb-0 small">{{ $subject->name_subject }} |
                            {{ $subject->grade->first()->name }} - {{ $subject->grade->first()->class }}</p>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-4">
                    <div class="container">
                        {{-- <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <div class="upload-icon mb-3">
                                    <i class="fas fa-book fa-2x text-danger"></i>
                                </div>
                                <h6 class="fw-bold">Add Educational Resource</h6>
                                <p class="text-muted small">Make learning materials easily accessible to your students
                                </p>
                            </div>
                        </div> --}}

                        <form
                            @if (session('role') == 'admin' || session('role') == 'superadmin') action="{{ route('subject.store-section', ['role' => session('role'), 'id' => $subject->id, 'grade_id' => $grade_id]) }}"    
                            @elseif (session('role') == 'teacher')
                                action="{{ route('subject.store-section.teacher', ['role' => session('role'), 'id' => $subject->id, 'grade_id' => $grade_id]) }}" @endif
                            method="POST" enctype="multipart/form-data" class="pt-2">
                            @csrf

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label for="title" class="form-label fw-semibold">Book Title <span
                                            class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            placeholder="Enter a descriptive title for this resource" required>
                                        <div class="form-text small text-muted">Choose a clear, descriptive title that
                                            helps students identify the content</div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label for="file" class="form-label fw-semibold">Upload PDF <span
                                    class="text-danger">*</span></label>
                                    <div class="file-upload-wrapper">
                                        <div class="file-upload-area text-center p-4" id="upload-area">
                                            <input type="file" class="file-input" id="file" name="file"
                                                accept=".pdf" required>
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-3 text-primary"></i>
                                            <h6 class="mb-2">Drag and drop your PDF file here</h6>
                                            <p class="text-muted small mb-2">or</p>
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="document.getElementById('file').click()">Browse Files</button>
                                            <p class="text-muted small mt-2">Maximum file size: 10MB</p>
                                        </div>
                                         {{-- <div class="selected-file mt-3 d-none"> --}}
                                            <div class="selected-file-info p-2 d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <span class="file-name text-truncate"></span>
                                                <span class="file-size ms-2 text-muted small"></span>
                                                <button type="button" class="btn-remove-file ms-auto btn btn-sm">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </div>
                                        {{-- </div> --}}
                                    </div>
                                    <div class="form-text small text-muted mt-2">Only PDF format is supported for
                                        consistency and compatibility</div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="alert alert-info small py-2" role="alert">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Resource Guidelines:</strong> Ensure you have proper rights to share
                                        this material. Large files may take longer to upload.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-outline-secondary mr-4"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Add Resource
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if ($ebook != null)
        <!-- Modal change ebook -->
        <div class="modal fade" id="changeBook" tabindex="-1" role="dialog" aria-labelledby="changeFileLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            <i class="fas fa-book mr-2"></i>Update E-Book File
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fas fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <div class="upload-icon mb-3">
                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                            </div>
                            <h6 class="fw-bold">Replace Current E-Book</h6>
                            <p class="text-muted small">The new file will replace the existing e-book while maintaining
                                all associated metadata and settings.</p>
                        </div>

                        <form action="{{ route('change.file.ebook') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="number" id="ebookid" name="ebook_id" class="form-control"
                                value="{{ $ebook->id }}" hidden>

                            <div class="file-upload-container mb-4">
                                <label for="upload_file" class="form-label fw-medium">Select PDF File</label>
                                <div class="file-upload-wrapper">
                                    <div class="file-upload-area p-4 text-center">
                                        <i class="fas fa-cloud-upload-alt fa-2x mb-3 text-primary"></i>
                                        <h6 class="mb-2">Drag and drop your file here</h6>
                                        <p class="text-muted small mb-3">or</p>
                                        <label for="upload_file" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-search me-1"></i> Browse Files
                                        </label>
                                        <input type="file" id="upload_file" name="upload_file" accept=".pdf"
                                            class="file-input" required>
                                        <p class="text-muted small mt-3 file-requirements">
                                            <i class="fas fa-info-circle me-1"></i> Accepted format: PDF only
                                        </p>
                                        <div class="selected-file mt-3 d-none">
                                            <div class="selected-file-info p-2 d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <span class="file-name text-truncate"></span>
                                                <span class="file-size ms-2 text-muted small"></span>
                                                <button type="button" class="btn-remove-file ms-auto btn btn-sm">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info small" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> Please ensure your PDF is properly formatted and optimized for
                                online viewing. Larger files may take longer to load for students.
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary" id="submitBtnUpdate">
                                    <i class="fas fa-save mr-2"></i>Update E-Book
                                </button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal delete ebook -->
        <div class="modal fade" id="deleteBook" tabindex="-1" role="dialog" aria-labelledby="changeFileLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="background-color: #ffe8d6;border-radius: 36px;">
                    <div class="modal-body p-4">
                        <div class="row d-flex px-4">
                            <div class="col-3 align-items-end justify-content-end">
                                <img src="{{ asset('images/greta-face.png')}}" style="width:100%; height:100%;">
                            </div>
                            <div class="col-9 d-flex justify-content-center align-items-center">
                                <form action="{{ route('delete.ebook') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('DELETE')
                                    <input type="number" id="delebookid" name="ebook_id" class="form-control"
                                        value="{{ $ebook->id }}" hidden>
        
                                        <strong>Are you sure want to delete this e-book ?</strong>
        
                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary"  id="deleteBtn">
                                            <i class="fas fa-check mr-2"></i>Yes
                                        </button>
                                        <button type="button" class="btn btn-light" data-dismiss="modal">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

<link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

@if (session('success_add_activity'))
    <script>
        Swal.fire({
            title: 'Successfully Add Material',
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            timer: 1000,
            showConfirmButton: false,
        });
    </script>
@endif

@if (session('success_edit_section'))
    <script>
        Swal.fire({
            title: 'Successfully Edit Section',
            imageUrl: '/images/happy.png', 
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            timer: 1000, 
            showConfirmButton: false,
        });
    </script>
@endif

@if (session('success_edit_activity'))
    <script>
        Swal.fire({
            title: 'Successfully Edit Activity',
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            showConfirmButton: false, // Sembunyikan tombol "OK",
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
        });
        
    </script>
@endif

@if (session('success_delete_activity'))
    <script>
        Swal.fire({
            title: 'Successfully Delete Activity',
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            timer: 1000,
            showConfirmButton: false,
        });
    </script>
@endif

@if (session('success_delete_ebook'))
    <script>
        Swal.fire({
            title: 'Successfully Delete E-Book',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
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

@if (session('success_edit_file_activity'))
    <script>
        Swal.fire({
            
            title: 'Successfully Edit Activity',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            }
        });
    </script>
@endif

@if (session('success_add_ebook'))
    <script>
        Swal.fire({
            
            title: 'Successfully Add en Ebook',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
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
            
            title: 'Successfully Change Ebook',
            timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false // Sembunyikan tombol "OK",
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            }
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


@if (session('role') == 'teacher' || session('role') == 'admin' || session('role') == 'superadmin')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#assessment').forEach(function(button) {
                button.addEventListener('click', function() {
                    var sectionId = this.getAttribute('data-id');
                    var sessionRole = @json(session('role'));
                    var gradeSubject = {{ $gradeSubject->id }};
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
                                if (sessionRole == 'teacher') {
                                    window.location.href = '/' + sessionRole +
                                        '/dashboard/exam/create';
                                } else if (sessionRole == 'admin' || sessionRole ==
                                    'superadmin') {
                                    window.location.href = '/' + sessionRole +
                                        '/exams/create';
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    html: `Failed to set exam ID || Contact IT Dept to resolved`,
                                    confirmButtonText: 'Oke',
                                });
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
    
    <script>
        // Add this JavaScript to handle the file upload interaction
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('upload_file');
            const fileArea = document.querySelector('.file-upload-area');
            const browseButton = document.querySelector('label[for="upload_file"]');
            const selectedFile = document.querySelector('.selected-file');
            const fileName = document.querySelector('.file-name');
            const fileSize = document.querySelector('.file-size');
            const removeBtn = document.querySelector('.btn-remove-file');

            // Hentikan propagasi klik pada tombol browse
            browseButton.addEventListener('click', function(e) {
                e.stopPropagation();
                // Tidak perlu panggil fileInput.click() karena atribut for="upload_file" sudah melakukannya
            });

            // Ubah event listener untuk area file, kecualikan saat mengklik tombol browse
            fileArea.addEventListener('click', function(e) {
                // Jika yang diklik bukan tombol browse, baru buka dialog file
                if (!e.target.closest('label[for="upload_file"]')) {
                    fileInput.click();
                }
            });

            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                const fileExtension = file.name.split('.').pop();
                if (this.files.length > 0) {
                    if (fileExtension == "pdf"){
                        const file = this.files[0];
                        fileName.textContent = file.name;
                        // Format file size
                        const size = file.size;
                        let formattedSize;
                        if (size < 1024) {
                            formattedSize = size + ' bytes';
                        } else if (size < 1024 * 1024) {
                            formattedSize = (size / 1024).toFixed(1) + ' KB';
                        } else {
                            formattedSize = (size / (1024 * 1024)).toFixed(1) + ' MB';
                        }

                        fileSize.textContent = formattedSize;
                        selectedFile.classList.remove('d-none');
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: `You can upload a PDF file only`,
                            confirmButtonText: 'Oke',
                        });
                    }
                }
            });

            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.value = '';
                selectedFile.classList.add('d-none');
            });

            // Prevent default browser behavior for drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight drop area when file is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                fileArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                fileArea.closest('.file-upload-wrapper').classList.add('border-primary');
                fileArea.closest('.file-upload-wrapper').style.backgroundColor = 'rgba(0, 102, 204, 0.05)';
            }

            function unhighlight() {
                fileArea.closest('.file-upload-wrapper').classList.remove('border-primary');
                fileArea.closest('.file-upload-wrapper').style.backgroundColor = '';
            }

            // Handle files dropped into the area
            fileArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {

                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;

                if (files.length > 0) {
                    const file = files[0];
                    fileName.textContent = file.name;
                    // Format file size
                    const size = file.size;
                    let formattedSize;

                    if (size < 1024) {
                        formattedSize = size + ' bytes';
                    } else if (size < 1024 * 1024) {
                        formattedSize = (size / 1024).toFixed(1) + ' KB';
                    } else {
                        formattedSize = (size / (1024 * 1024)).toFixed(1) + ' MB';
                    }

                    fileSize.textContent = formattedSize;
                    selectedFile.classList.remove('d-none');
                }
            }
        });
    </script>

    <script>
        // Initialize file upload interactions
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file');
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const removeFileBtn = document.getElementById('remove-file');
            const uploadArea = document.getElementById('upload-area');
    
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                const fileExtension = file.name.split('.').pop();

                if(fileExtension !== 'pdf') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: `You can upload a PDF file only`,
                        confirmButtonText: 'Oke',
                    });
                }else{
                    if (this.files.length > 0) {
                        fileName.textContent = this.files[0].name;
                        fileInfo.classList.remove('d-none');
                        uploadArea.classList.add('d-none');
                    } else {
                        resetFileUpload();
                    }
                }
            });
    
            removeFileBtn.addEventListener('click', function() {
                fileInput.value = '';
                resetFileUpload();
            });
    
            function resetFileUpload() {
                fileInfo.classList.add('d-none');
                uploadArea.classList.remove('d-none');
                fileName.textContent = 'No file selected';
            }
    
            // Allow drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('d-none');
            });
    
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('d-none');
            });
    
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('d-none');
                
                if (e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    if (file.type === 'application/pdf') {
                        fileInput.files = e.dataTransfer.files;
                        fileName.textContent = file.name;
                        fileInfo.classList.remove('d-none');
                        uploadArea.classList.add('d-none');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: `You can upload a PDF file only`,
                            confirmButtonText: 'Oke',
                        });
                    }
                }
            });
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#set-assessment').forEach(function(button) {
            button.addEventListener('click', function() {
                var sectionId = this.getAttribute('data-id');
                var sessionRole = @json(session('role'));
                if (sessionRole == 'student') {
                    var url = "{{ route('set.assessment.id.student') }}";
                } else if (sessionRole == 'parent') {
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
                            window.location.href = '/' + sessionRole +
                                '/dashboard/exam/detail';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: `Failed to set exam ID || Contact IT Dept to resolved`,
                                confirmButtonText: 'Oke',
                            }); 
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    })
</script>
@endsection
