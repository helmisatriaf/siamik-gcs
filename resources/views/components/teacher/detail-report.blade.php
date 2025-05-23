@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Score Report</li>
            </ol>
        </nav>
        </div>
    </div>

    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title">Report Score</h3>

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
                        <th>#</th>
                        <th>Name Exam</th>
                        <th>Date Exam</th>
                        <th>Grade</th>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Type Exam</th>
                        <th>Teacher</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $el)
                    <tr id={{'index_grade_' . $el->id}}>
                        <td class="text-sm">{{ $loop->index + 1 }}</td>
                        <td class="text-sm"><a>{{ $el->exam_name }}</a></td>
                        <td class="text-sm"><a>{{ $el->date_exam }}</a></td>
                        <td class="text-sm">{{ $el->grade_name }} - {{ $el->grade_class }}</td>
                        <td class="text-sm">{{ $el->student_name }}</td>
                        <td class="text-sm">{{ $el->subject_name }}</td>
                        <td class="text-sm">{{ $el->type_exam }}</td>
                        <td class="text-sm">{{ $el->teacher_name }}</td>
                        <td class="text-md"><span class="badge badge-success">{{ $el->score }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

{{-- pagination --}}

<div class="d-flex justify-content-end my-5">

    <nav aria-label="...">
        <ul class="pagination" max-size="2">
            
            @php
            $role = session('role');
            $link = "/{$role}/dashboard/score?";
            $previousLink = $link . '&page=' . ($data->currentPage() - 1);
            $nextLink = $link . '&page=' . ($data->currentPage() + 1);
            $firstLink = $link . '&page=1';
            $lastLink = $link . '&page=' . $data->lastPage();
            
            $arrPagination = [];
            $flag = false;
            
            if ($data->lastPage() - 5 > 0) {
                if ($data->currentPage() <= 4) {
                    for ($i = 1; $i <= 5; $i++) {
                        $temp = (object) [
                            'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];
                        array_push($arrPagination, $temp);
                    }
                } else if ($data->lastPage() - $data->currentPage() > 2) {
                    $flag = true;
                    $idx = [$data->currentPage() - 2, $data->currentPage() - 1, $data->currentPage(), $data->currentPage() + 1, $data->currentPage() + 2];
                    foreach ($idx as $value) {
                        $temp = (object) [
                            'page' => $value,
                            'link' => $link . '&page=' . $value,
                        ];
                        array_push($arrPagination, $temp);
                    }
                } else {
                    $arrFirst = [];
                    for ($i = $data->currentPage(); $i <= $data->lastPage(); $i++) {
                        $temp = (object) [
                            'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];
                        array_push($arrFirst, $temp);
                    }
                    
                    $arrLast = [];
                    $diff = $data->currentPage() - (5 - sizeof($arrFirst));
                    for ($i = $diff; $i < $data->currentPage(); $i++) {
                        $temp = (object) [
                            'page' => $i,
                            'link' => $link . '&page=' . $i,
                        ];
                        array_push($arrLast, $temp);
                    }
                    
                    $arrPagination = array_merge($arrLast, $arrFirst);
                }
            } else {
                for ($i = 1; $i <= $data->lastPage(); $i++) {
                    $temp = (object) [
                        'page' => $i,
                        'link' => $link . '&page=' . $i,
                    ];
                    array_push($arrPagination, $temp);
                }
            }
            @endphp

            <li class="mr-1 page-item {{$data->previousPageUrl() ? '' : 'disabled'}}">
                <a class="page-link" href="{{$firstLink}}" tabindex="+1">
                    << First
                </a>
            </li>

            <li class="page-item {{$data->previousPageUrl() ? '' : 'disabled'}}">
                <a class="page-link" href="{{$previousLink}}" tabindex="-1">
                    Previous
                </a>
            </li>

            @foreach ($arrPagination as $el)
            <li class="page-item {{$el->page === $data->currentPage() ? 'active' : ''}}">
                <a class="page-link" href="{{$el->link}}">
                    {{$el->page}}
                </a>
            </li>
            @endforeach

            <li class="page-item {{$data->nextPageUrl() ? '' : 'disabled'}}">
                <a class="page-link" href="{{$nextLink}}" tabindex="+1">
                    Next
                </a>
            </li>

            <li class="ml-1 page-item {{$data->nextPageUrl() ? '' : 'disabled'}}">
                <a class="page-link" href="{{$lastLink}}" tabindex="+1">
                    Last >>
                </a>
            </li>

        </ul>   
    </nav>


</div>

<link rel="stylesheet" href="{{ asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@endsection
