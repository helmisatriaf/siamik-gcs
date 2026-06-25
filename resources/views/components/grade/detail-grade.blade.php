@extends('layouts.admin.master')


@section('content')

<style>
    .teacher-card{
        border-radius:12px;
        transition:all .25s ease;
    }

    .teacher-card:hover{
        transform:translateY(-3px);
        box-shadow:0 .5rem 1rem rgba(0,0,0,.12)!important;
    }

    .teacher-card img{
        border:3px solid #f8f9fa;
    }

    .teacher-card h6{
        font-size:15px;
        margin-bottom:4px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="p-3 mb-4 shadow-soft" style="background-color: #ffde9e;border-radius: 12px;">
                <ol class="breadcrumb mb-0" style="background-color: #fff3c0;">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/grades')}}">Grade</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail {{ $data->grade['grade_name'] }} - {{ $data->grade['grade_class'] }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info" style="border-radius: 12px;">
                <div class="inner">
                    <h3>{{ count($data->gradeStudent)}}</h3>
                    <p>Student</p>
                </div>
                <div class="icon">
                {{-- <i class="ion ion-pie-graph"></i> --}}
                <i class="fa-solid fa-user-graduate"></i>
                </div>
            
                <a href="/teacher/dashboard/exam/teacher" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
            
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning" style="border-radius: 12px;">
            <div class="inner">
                <h3>{{ count($data->gradeSubject)}} </h3>
                {{-- <sup style="font-size: 20px">%</sup> --}}
                </h3>

                <p>Total Courses Active</p>
            </div>
            <div class="icon">
                {{-- <i class="ion ion-person-add"></i> --}}
                <i class="fa-solid fa-book"></i>
            </div>
            <a href="/teacher/course" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger" style="border-radius: 12px;">
            <div class="inner">
                <h3>{{ count($data->gradeExam)}}</h3>

                <p>Total Assessments</p>
            </div>
            <div class="icon">
                {{-- <i class="ion ion-pie-graph"></i> --}}
                <i class="fa-solid fa-book-open-reader"></i>
            </div>
            
            <a href="/teacher/dashboard/exam/teacher" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
            
            </div>
        </div>
    </div>

    <div class="row">
        <!-- TEACHER -->
        <div class="col-12">
            <div class="card" style="background-color: #ffde9e;border-radius: 12px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Teacher Class
                    </h3>
    
                    <div class="card-tools">
                        <span class="badge badge-success">
                            {{ count($data->gradeTeacher) }} Teacher(s)
                        </span>
                    </div>
                </div>
    
                <div class="card-body">
    
                    @if(count($data->gradeTeacher) > 0)
    
                    <div class="row">
                        @foreach ($data->gradeTeacher as $el)
    
                        @php
                            $birthDate = explode("-", $el->date_birth);
    
                            $age = (
                                date("md", date("U", mktime(
                                    0,0,0,
                                    $birthDate[2],
                                    $birthDate[1],
                                    $birthDate[0]
                                ))) > date("md")
                            )
                            ? ((date("Y")-$birthDate[0])-1)
                            : (date("Y")-$birthDate[0]);
                        @endphp
    
                        <div class="col-lg-12 mb-3">
    
                            <div class="card teacher-card border-0 shadow-sm" style="background-color: #fff3c0;">
    
                                <div class="card-body">
    
                                    <div class="row align-items-center">
    
                                        {{-- LEFT --}}
                                        <div class="col-5">
    
                                            <div class="d-flex align-items-center">
    
                                                <img
                                                    src="{{ $el->profil
                                                        ? asset('storage/file/profile/'.$el->profil)
                                                        : asset('img/default-user.png') }}"
                                                    alt="avatar" class="rounded-circle img-fluid" style="width: 150px;height: 150px; cursor: pointer;"
                                                    id="profileImage">
    
                                                <div class="ml-3">
    
                                                    <h6 class="mb-1 font-weight-bold">
                                                        {{ $el->name }}
                                                    </h6>
    
                                                    @if($el->is_active)
                                                        <span class="badge badge-success">
                                                            Active
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger">
                                                            Inactive
                                                        </span>
                                                    @endif
    
                                                </div>
    
                                            </div>
    
                                        </div>
    
                                        {{-- RIGHT --}}
                                        <div class="col-7">
    
                                            <div class="row">
    
                                                <div class="col-12 mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-birthday-cake text-warning mr-1"></i>
                                                        Age
                                                    </small>
                                                    <div>{{ $age }} Years Old</div>
                                                </div>
    
                                                <div class="col-12 mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                        Place of Birth
                                                    </small>
                                                    <div>{{ $el->place_birth }}</div>
                                                </div>
    
                                                <div class="col-12">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user text-primary mr-1"></i>
                                                        Gender
                                                    </small>
                                                    <div>{{ $el->gender }}</div>
                                                </div>
    
                                            </div>
    
                                        </div>
    
                                    </div>
    
                                </div>
    
                            </div>
    
                        </div>
    
                        @endforeach
    
                        </div>
    
                    @else
    
                    <div class="text-center py-5">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
    
                        <h5>No Teacher Found</h5>
    
                        <p class="text-muted">
                            You haven't added teacher data yet.
                        </p>
                    </div>
    
                    @endif
    
                </div>
            </div>
        </div>
        <!-- END TEACHER -->

        <!-- STUDENT -->
        <div class="col-12">
            <div class="row">
                @foreach($data->gradeStudent as $student)

                @php
                    $birthDate = explode("-", $student->date_birth);

                    $ageStudent = (
                        date("md", date("U", mktime(
                            0,0,0,
                            $birthDate[2],
                            $birthDate[1],
                            $birthDate[0]
                        ))) > date("md")
                    )
                    ? ((date("Y")-$birthDate[0])-1)
                    : (date("Y")-$birthDate[0]);
                @endphp
    
                <div class="col-lg-3 col-md-4 mb-3" >
    
                    <div class="card student-card border-0 shadow-sm" style="background-color: #ffde9e;border-radius: 12px;">
    
                        <div class="card-body">
    
                            <div class="d-flex">
    
                                {{-- Photo --}}
                                <img
                                    src="{{ $student->profil
                                        ? asset('storage/file/profile/'.$student->profil)
                                        : asset('images/admin.png') }}"
                                    alt="avatar" class="rounded-circle img-fluid" style="width: 90px;height: 90px; cursor: pointer;"
                                    id="profileImage">
    
                                {{-- Information --}}
                                <div class="ml-3 flex-grow-1">
    
                                    <div class="d-flex justify-content-between">
    
                                        <h6 class="font-weight-bold mb-1">
                                            {{ $student->name }}
                                        </h6>
    
                                        @if($student->is_active)
                                            <span class="text-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                Inactive
                                            </span>
                                        @endif
    
                                    </div>
    
                                    <small class="text-muted d-block">
                                        {{ $student->gender }}
                                    </small>
    
                                    <small class="text-muted d-block">
                                        {{ $ageStudent }} Years Old
                                    </small>
    
                                    <small class="text-primary">
                                        NISN : {{ $student->nisn }}
                                    </small>
    
                                </div>
    
                            </div>
    
                        </div>
    
                    </div>
    
                </div>
    
                @endforeach
    
            </div>
        </div>
        <!-- END STUDENT -->

        

        <!-- SUBJECT -->
        {{-- <div class="card card-dark col-12">
            <div class="card-header">
                <h3 class="card-title">Subject</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 1%">#</th>
                            <th>Subject Name</th>
                            <th>Subject Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(sizeof($data->gradeSubject) > 0)
                            @foreach ($data->gradeSubject as $index => $gradeSubject)
                                @php
                                    // Cari guru yang mengajar subjek ini
                                    $teacherName = 'teacher belum ada';
                                    foreach ($data->subjectTeacher as $subjectTeacher) {
                                        if ($gradeSubject->subject_id == $subjectTeacher->subject_id) {
                                            $teacherName = $subjectTeacher->teacher_name;
                                            break;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $gradeSubject->subject_name }}
                                            @if($gradeSubject->is_lead) 
                                                <span class="badge badge-primary">Main Teacher</span> 
                                            @elseif($gradeSubject->is_group) 
                                                <span class="badge badge-warning">Member</span>
                                            @else 
                                            @endif
                                    </td>
                                    <td>{{ $gradeSubject->teacher_name }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">
                                    <p>You haven't added Subject data yet</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div> --}}

        <div class="col-12">
            <div class="card col-12" style="background-color: #ffde9e;border-radius: 12px;">
                <div class="card-header">
                    <h3 class="card-title">Subjects</h3>
    
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($data->gradeSubject as $subject)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card border-0 shadow-sm subject-card h-100" style="background-color: #fff3c0;border-radius: 12px;">
        
                                    <div class="card-body">
        
                                        <div class="d-flex align-items-center">
        
                                            {{-- Subject Icon --}}
                                            <div class="subject-icon">
                                                <i class="fas fa-book-open"></i>
                                            </div>
        
                                            <div class="ml-3 flex-grow-1">
        
                                                <div class="d-flex justify-content-between align-items-start">
        
                                                    <div>
                                                        <h5 class="mb-1 font-weight-bold">
                                                            {{ $subject->subject_name }}
                                                        </h5>
        
                                                        <small class="text-muted">
                                                            {{ $subject->grade_name }}
                                                        </small>
                                                    </div>
        
                                                </div>
        
                                            </div>
        
                                        </div>
        
                                        <hr>
        
                                        <div class="row text-sm">
        
                                            <div class="col-12 mb-2">
                                                <i class="fas fa-user-tie text-primary mr-2"></i>
                                                <strong>Teacher :</strong>
                                                {{ $subject->teacher_name ?? '-' }}
                                            </div>
        
                                        </div>
        
                                    </div>
        
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- END SUBJECT -->


        <!-- EXAM -->
        <div class="card card-dark col-12">
            <div class="card-header">
                <h3 class="card-title">Exam</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>
                            <th>
                                Exam Name
                            </th>
                            <th>
                                Materi
                            </th>
                            <th>
                                Date
                            </th>
                            <th class="text-center">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(sizeof($data->gradeExam)>0)

                        @foreach ($data->gradeExam as $el)
                        <tr>
                            <td>
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                                {{$el->name_exam}}
                            </td>
                            <td>
                                {{$el->materi}}
                            </td>
                            <td>
                                {{$el->date_exam}}
                            </td>
                            <td class="project-state">
                                @if($el->is_active)
                                <p class="badge badge-success">Active</p>
                                @else
                                <p class="badge badge-danger">Inactive</p>
                                @endif
                            </td>
                        </tr>

                        @endforeach
                        @else
                        <tr>
                           <td colspan="5" class="text-center">
                              <p>You haven't added exam data yet</p>
                           </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- END EXAM -->




    </div>

</div>


@endsection
