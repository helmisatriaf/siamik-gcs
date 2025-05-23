@extends('layouts.admin.master')
@section('content')


    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">

        <div class="card card-light">
            <div class="card-header">
                @if (session('role') == 'superadmin')
                    <form class="row col-12" action="/superadmin/exams">
                    @elseif (session('role') == 'admin')
                        <form class="row col-12" action="/admin/exams">
                @endif
                {{-- GRADES --}}
                <div class="col-md-3">
                    <div class="form-group">
                        @php
                            $selectGrades = $form->grades;
                        @endphp

                        <label>Grade:</label>
                        <select name="grade" class="form-control" id="grade-select" onchange="this.form.submit()">
                            <option value="all" {{ $selectGrades === 'all' ? 'selected' : '' }}>All Grades</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade['id'] }}" {{ $selectGrades == $grade['id'] ? 'selected' : '' }}>
                                    {{ ucwords($grade['name']) }} - {{ $grade['class'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- SUBJECTS --}}
                <div class="col-md-3">
                    <div class="form-group">
                        @php
                            $selectSubjects = $form->subjects;
                        @endphp

                        <label>Subject:</label>
                        <select name="subject" class="form-control" id="subject-select" onchange="this.form.submit()">
                            <option value="all" {{ $selectSubjects === 'all' ? 'selected' : '' }}>All Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject['id'] }}"
                                    {{ $selectSubjects == $subject['id'] ? 'selected' : '' }}>
                                    {{ ucwords($subject['name_subject']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- TEACHERS --}}
                <div class="col-md-3">
                    <div class="form-group">
                        @php
                            $selectTeachers = $form->teachers;
                        @endphp

                        <label>Teacher:</label>
                        <select name="teacher" class="form-control" id="teacher-select" onchange="this.form.submit()">
                            <option value="all" {{ $selectSubjects === 'all' ? 'selected' : '' }}>All Teacher</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher['id'] }}"
                                    {{ $selectTeachers == $teacher['id'] ? 'selected' : '' }}>
                                    {{ ucwords($teacher['name']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- TYPE --}}
                <div class="col-md-3">
                    <div class="form-group">
                        @php
                            $selectType = $form->type;
                        @endphp

                        <label>Type:</label>
                        <select name="type" class="form-control" id="type-select" onchange="this.form.submit()">
                            <option value="all" {{ $selectType === 'all' ? 'selected' : '' }}>All Type</option>
                            @foreach ($type as $type)
                                <option value="{{ $type['id'] }}" {{ $selectType == $type['id'] ? 'selected' : '' }}>
                                    {{ ucwords($type['name']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- SEARCH --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <input name="search" value="{{ $form->search }}" type="search"
                                class="form-control form-control-lg" placeholder="Type your keywords here">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                </form>
            </div>
            <div class="card-body">
                @if (sizeof($data) != 0)
                    {{-- <a type="button" href="{{ url('/' . session('role') . '/exams/create') }}"
                        class="btn btn-danger btn-sm mb-2">
                        <i class="fa-solid fa-plus"></i>
                        </i>
                        New Scoring
                    </a> --}}

                    <div class="row">
                        @foreach ($data as $el)
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="position-relative p-3 bg-light d-flex flex-column rounded-lg shadow-md border"
                                    style="min-height: 180px;">
                                    <div class="ribbon-wrapper ribbon-lgd">
                                        @if ($el->is_active)
                                            <div class="ribbon bg-warning">Active</div>
                                        @else
                                            <div class="ribbon bg-light">Completed</div>
                                        @endif
                                    </div>
                                    <p class="flex-grow-1">
                                        {{ $el->type_exam }} | {{ $el->subject_name }} <br>
                                        {{ $el->grade_name }} - {{ $el->grade_class }} <br>
                                        {{ $el->name_exam }} <br>
                                        <i class="fas fa-clock"></i>
                                        <span class="text-danger text-bold text-sm">
                                            {{ \Carbon\Carbon::parse($el->date_exam)->format('l, d F Y') }}
                                        </span>
                                    </p>
                                    <div class="d-flex flex-wrap">
                                        <div class="col-6 p-1">
                                            <a class="btn btn-success btn-sm text-sm w-100 rounded"
                                                href="{{ url('/exams') . '/score/' . $el->id }}">
                                                <i class="fas fa-book"></i> Score
                                            </a>
                                        </div>
                                        <div class="col-6 p-1">
                                            <a class="btn btn-primary btn-sm text-sm w-100 rounded"
                                                href="{{ url('/' . session('role') . '/exams') . '/' . $el->id }}">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </div>
                                        <div class="col-6 p-1">
                                            <a class="btn btn-warning btn-sm text-sm w-100 rounded"
                                                href="{{ url('/' . session('role') . '/exams') . '/edit/' . $el->id }}">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                        </div>
                                        <div class="col-6 p-1">
                                            <a class="btn btn-danger btn-sm text-sm w-100 rounded" id="deleteExam"
                                                data-id="{{ $el->id }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                        <div class="col-12 p-1">
                                            <a class="btn btn-secondary btn-sm text-sm w-100 d-flex align-items-center justify-content-center rounded position-relative"
                                                href="{{ url(session('role') . '/course') . '/' . $el->subject_id . '/sections/' . $el->grade_id }}" style="z-index: 1;">
                                                <i class="fas fa-book mr-1"></i> Course
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- pagination --}}

                    <div class="d-flex justify-content-center mt-3">

                        <nav aria-label="...">
                            <ul class="pagination" max-size="2">

                                @php
                                    $role = session('role');
                                    $link =
                                        '/{$role}/exams?grade=' .
                                        $selectGrades .
                                        '&subject=' .
                                        $selectSubjects .
                                        '&type=' .
                                        $selectType .
                                        '&teacher=' .
                                        $selectTeachers .
                                        '&search=' .
                                        $form->search;
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
                                        } elseif ($data->lastPage() - $data->currentPage() > 2) {
                                            $flag = true;
                                            $idx = [
                                                $data->currentPage() - 2,
                                                $data->currentPage() - 1,
                                                $data->currentPage(),
                                                $data->currentPage() + 1,
                                                $data->currentPage() + 2,
                                            ];
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

                                <li class="mr-1 page-item {{ $data->previousPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $firstLink }}" tabindex="+1">
                                        << First </a>
                                </li>

                                <li class="page-item {{ $data->previousPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $previousLink }}" tabindex="-1">
                                        Previous
                                    </a>
                                </li>

                                @foreach ($arrPagination as $el)
                                    <li class="page-item {{ $el->page === $data->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $el->link }}">
                                            {{ $el->page }}
                                        </a>
                                    </li>
                                @endforeach

                                <li class="page-item {{ $data->nextPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $nextLink }}" tabindex="+1">
                                        Next
                                    </a>
                                </li>

                                <li class="ml-1 page-item {{ $data->nextPageUrl() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $lastLink }}" tabindex="+1">
                                        Last >>
                                    </a>
                                </li>

                            </ul>
                        </nav>


                    </div>
                @else
                    <div class="container-fluid full-height">
                        <div class="m-0">
                            <p class="text-red my-b-2">Oops.. You dont create any scorings</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fa-regular fa-face-laugh-beam"></i>
                            <p class="my-2">Students are happy to get scoring from you</p>
                        </div>
                        <div class="btn-container">
                            <a type="button" href="{{ url('/teacher/dashboard/exam/create') }}"
                                class="btn btn-secondary btn">
                                <i class="fa-solid fa-plus"></i> Scoring
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $(document).on('click', '#deleteExam', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this scoring!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete.exams') }}",
                        type: 'POST',
                        data: {
                            exam_id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Delete Successfull",
                                text: "Scoring already delete",
                                icon: "success"
                            }).then(function() {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            alert("Error occurred!");
                        }
                    });
                }
            });
        })
    </script>


    @if (session('after_create_exam'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Successfully',
                text: 'Successfully created new exam in the database.',
            });
        </script>
    @endif

    @if (session('after_update_exam'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Successfully',
                text: 'Successfully updated the exam in the database.',
            });
        </script>
    @endif

    @if (session('after_done_exam'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Successfully',
                text: 'Successfully done exam in the database.',
            });
        </script>
    @endif

@endsection
