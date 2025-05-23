@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    {{-- @php
        dd($data);
    @endphp --}}
    @if(count($data) !== 0)
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-3">
                    <ol class="breadcrumb mb-0">
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

        <div class="card card-orange">
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
                                        <textarea id="froala-editor"> {{ $el->text }} {{$el->answer[0]['answer_text']}}</textarea>
    
                                        <div class="post">
                                            <p>Recent Activity</p>
                                            @foreach ($el->students as $index => $student)
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $student->profil)}}" alt="user image">
                                                    <span class="username">
                                                    <a class="text-muted">{{$student->name}}</a>
                                                    </span>
                                                    <div class="row">
                                                        <div class="col-12 col-md-10">
                                                                Answer : 
                                                                @php
                                                                    if ($el->type == "mc") {
                                                                        $point = \App\Models\StudentAnswer::with(['answer'])
                                                                            ->where('exam_id', $exam->id)
                                                                            ->where('question_id', $el->id)
                                                                            ->where('student_id', $student->id)
                                                                            ->first();
                                                                        $answer = $point?->answer?->answer_text ?? '';
                                                                    }
                                                                    elseif ($el->type == "essay") {
                                                                        $point = \App\Models\StudentAnswer::with(['answer'])
                                                                            ->where('exam_id', $exam->id)
                                                                            ->where('question_id', $el->id)
                                                                            ->where('student_id', $student->id)
                                                                            ->first();
                                                                        $answer = $point?->essay_answer ?? '';
                                                                    }
                                                                @endphp 
                                                                {{ $answer }}
                                                        </div>
                                                        <div class="col-12 col-md-2">
                                                            @if ($el->type == "mc")
                                                                @php
                                                                    $point = \App\Models\StudentAnswer::with(['answer'])
                                                                        ->where('exam_id', $exam->id)
                                                                        ->where('question_id', $el->id)
                                                                        ->where('student_id', $student->id)
                                                                        ->value('point');
                                                                @endphp
                                                                Point : {{$point}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
