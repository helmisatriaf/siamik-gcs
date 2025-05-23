@extends('layouts.admin.master')


@section('content')


<div class="container-fluid">
    <div class="row">
      <div class="col">
      <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
         <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/grades')}}">Grade</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail {{ $data->grade['grade_name'] }} - {{ $data->grade['grade_class'] }}</li>
         </ol>
      </nav>
      </div>
   </div>

    <div class="row">

        <!-- STUDENT -->
        <div class="card card-dark col-12">
            <div class="card-header">
                <h3 class="card-title">Student</h3>

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
                                Student Name
                            </th>
                            <th>
                                Place of birth
                            </th>
                            <th>
                                Gender
                            </th>
                            <th class="text-center">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(sizeof($data->gradeStudent)>0)

                        @foreach ($data->gradeStudent as $el)
                        <tr>
                            <td>
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                                <a>
                                    {{$el->name}}
                                </a>
                                <br />
                                <small>
                                    @php

                                    $birthDate = explode("-", $el->date_birth);

                                    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1],
                                    $birthDate[0]))) > date("md")
                                    ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
                                    @endphp
                                    {{$age}} years old
                                </small>
                            </td>
                            <td>
                                {{$el->place_birth}}
                            </td>
                            <td>
                                {{$el->gender}}
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
                              <p>You haven't added student data yet</p>
                           </td>

                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- END STUDENT -->

        <!-- TEACHER -->
        <div class="card card-dark col-12">
            <div class="card-header">
                <h3 class="card-title">Teacher Class</h3>

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
                                Teacher Name
                            </th>
                            <th>
                                Place of birth
                            </th>
                            <th>
                                Gender
                            </th>
                            <th class="text-center">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(sizeof($data->gradeTeacher)>0)

                        @foreach ($data->gradeTeacher as $el)
                        <tr>
                            <td>
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                                <a>
                                    {{$el->name}}
                                </a>
                                <br />
                                <small>
                                    @php

                                    $birthDate = explode("-", $el->date_birth);

                                    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1],
                                    $birthDate[0]))) > date("md")
                                    ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
                                    @endphp
                                    {{$age}} years old
                                </small>
                            </td>
                            <td>
                                {{$el->place_birth}}
                            </td>
                            <td>
                                {{$el->gender}}
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

                              <p>You haven't added Teacher data yet</p>
                           </td>

                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- END STUDENT -->

        <!-- SUBJECT -->
        <div class="card card-dark col-12">
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
