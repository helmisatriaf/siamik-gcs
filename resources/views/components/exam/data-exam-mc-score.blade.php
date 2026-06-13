@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <<div class="floating-nav">
        <button onclick="window.scrollTo({top:0,behavior:'smooth'})">
            <i class="fas fa-chevron-up"></i>
        </button>

        <button onclick="scrollToBottom()">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>

    @if(count($data) !== 0)
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-3 shadow-soft"  style="background-color: #ffde9e;">
                    <ol class="breadcrumb mb-0" style="background-color: #fff3c0;">
                        <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/teacher')}}">Home</a></li>
                        @if(session('role') == 'admin' || session('role') == 'superadmin')
                        @else
                        <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/detail/'.$data[0]->exam_id )}}">Assessment</a></li>
                        <li class="breadcrumb-item">Scoring</li>
                        @endif
                        {{-- <li class="breadcrumb-item active" aria-current="page">Scoring {{ $data[0]['exam_name'] }} {{ $data[0]['subject_name'] }} ({{ $data[0]['grade_name'] }} - {{ $data[0]['grade_class'] }})</li> --}}
                    </ol>
                </nav>
            </div>
        </div>

        <nav class="col-12 mt-1">
            <div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
                <a id="btnSingleTeacher" class="nav-item nav-link active text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]">Cek Score</a>
                <a id="btnMultipleTeacher" class="nav-item nav-link text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" href="{{url('/' .session('role'). '/dashboard/exam/score/fe/' . $examId)}}">Scoring</a>
            </div>
        </nav>

        <div class="card" style="background-color: #ffde9e;">
            <div class="card-header">
                <h3 class="card-title">Scorings</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                    <form method="POST" action="{{ route('scoreMCE') }}">
                    @csrf

                    <table class="table table-striped projects">
                        <tbody>
                            @foreach ($data as $index => $el)
                            <tr id="{{'index_grade_' . $el->id}}">
                                <td>
                                    <div>
                                        <h1>Question {{ $loop->index + 1 }} :</h1>
                                        {{-- <textarea id="froala-editor"> {{ $el->text }} {{$el->answer[0]['answer_text']}}</textarea> --}}
                                        {!! $el->text !!} {!!$el->answer[0]['answer_text'] !!}
    
                                        <div class="post">
                                            <h5 class="mb-3">Recent Activity</h5>

                                            <div class="row">
                                                @foreach ($el->students as $student)

                                                    @php
                                                        $studentAnswer = \App\Models\StudentAnswer::with(['answer'])
                                                            ->where('exam_id', $exam->id)
                                                            ->where('question_id', $el->id)
                                                            ->where('student_id', $student->id)
                                                            ->first();

                                                        $answer = '';

                                                        if ($el->type == 'mc') {
                                                            $answer = $studentAnswer?->answer?->answer_text ?? '-';
                                                        } else {
                                                            $answer = $studentAnswer?->essay_answer ?? '-';
                                                        }

                                                        $point = $studentAnswer?->point ?? 0;
                                                    @endphp

                                                    <div class="col-md-3 mb-3" >
                                                        <div class="card h-100" style="background-color: #fff3c0;">

                                                            <div class="card-header p-2">
                                                                <div class="d-flex align-items-center">

                                                                    <img
                                                                        src="{{ asset('storage/file/profile/'.$student->profil) }}"
                                                                        class="img-circle mr-2"
                                                                        width="40"
                                                                        height="40"
                                                                    >

                                                                    <div>
                                                                        <strong>
                                                                            {{ collect(explode(' ', trim($student->name)))->first() }}
                                                                        </strong>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="card-body p-3">

                                                                <div class="mb-2">
                                                                    <small class="text-muted">
                                                                        Student Answer
                                                                    </small>

                                                                    <div class="border rounded p-2 bg-light mt-1 answer-box">
                                                                        {!! $answer !!}
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <small class="text-muted">
                                                                        Score
                                                                    </small>

                                                                    @if ($el->type == 'mc')
                                                                        <div class="font-weight-bold text-success">
                                                                            {{ $point }} Point
                                                                        </div>
                                                                    @else
                                                                        <input
                                                                            class="form-control form-control-sm score-input mt-1"
                                                                            value="{{ $point }}"
                                                                            name="student[{{$student->id}}][{{$el->id}}][point]"
                                                                            type="number"
                                                                            min="0"
                                                                            max="{{ $pointEssay }}"
                                                                        >
                                                                    @endif
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <input type="number" name="exam_id" value="{{$exam->id}}" hidden>

                    <div class="card-footer">
                        {{-- <button type="submit" class="btn btn-success float-right">Update Scores</button> --}}
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    @else
        <p>Kosong</p>
    @endif
    <div id="bottom-anchor"></div>
    <a href="{{ url('/teacher/enroll/' . $examId) }}" class="btn btn-danger">Enroll Score</a>
</div>

<link rel="stylesheet" href="{{ asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
    new FroalaEditor("textarea#froala-editor", {
        toolbarInline: false,  // Hilangkan toolbar
        toolbarVisibleWithoutSelection: false,
        charCounterCount: false, // Hilangkan penghitung karakter
        events: {
            initialized: function () {
                this.edit.off(); // Nonaktifkan mode edit
            }
        }
    });
</script>


@if(session('after_create_score'))
    <script>
        Swal.fire({
            icon: 'success',

            title: 'Successfully',
            text: 'Successfully created new score in the database.'
        });
    </script>
@endif

@if(session('success_update_score_essay'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated point essay.'
        });
    </script>
@endif

@endsection
