@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="card mt-2">
            <div class="card-header">
                <h3 class="card-title">Grades</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0" style="overflow-x:auto;">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th style="width: 20%">
                                Grades
                            </th>
                            <th style="width: 10%">
                                Student
                            </th>
                            <th style="width: 35%">
                                Class Teacher
                            </th>
                            <th style="width: 10%">
                                Course
                            </th>
                            <th style="width: 10%">
                                Assessment
                            </th>
                            <th style="width: 15%">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $el)
                            <tr id={{ 'index_grade_' . $el->id }}>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $el->name . ' - ' . $el->class }}</td>
                                <td>{{ $el->active_student_count }}</td>
                                <td>
                                    @foreach ($el->teacher as $ct)
                                        {{ $ct->name }}
                                    @endforeach
                                </td>
                                <td>{{ $el->active_subject_count }}</td>
                                <td>{{ $el->active_exam_count }}</td>

                                <td class="project-actions text-left toastsDefaultSuccess">
                                    <a class="btn btn-primary btn"
                                        href="{{ route('grades.subjects', ['role' => session('role'), 'id' => $el->id]) }}">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const coursesList = document.querySelector('.courses-list');

            function filterCourses() {
                const searchText = searchInput.value.toLowerCase();
                const courses = coursesList.querySelectorAll('.card.course-card');

                courses.forEach(course => {
                    const title = course.querySelector('.course-title').textContent.toLowerCase();
                    const isVisible = title.includes(searchText);
                    course.style.display = isVisible ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterCourses);

            @if (session('data_is_empty'))
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops..',
                        text: 'Data Attendance is Empty !!!',
                        confirmButtonColor: '#0f6cbf'
                    });
                }, 500);
            @endif
        });
    </script>
@endsection
