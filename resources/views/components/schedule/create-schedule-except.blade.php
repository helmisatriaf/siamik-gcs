@extends('layouts.admin.master')
@section('content')

<section class="content">
    @if (session('role') == 'superadmin')
        <form method="POST" action={{route('actionSuperCreateSchedule')}}>
    @elseif (session('role') == 'admin')
        <form method="POST" action={{route('actionAdminCreateSchedule')}}>
    @endif
    @csrf

    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-3">
                    <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/schedules/grades')}}">Master Schedule</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Schedule {{ $data['grade'][0]['name'] }} - {{ $data['grade'][0]['class'] }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container ml-0 mr-0" style="overflow-x: auto;">
            <div class="row" style="width: 1900px;">
                <!-- left column -->
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div>
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create schedule selain primary/second</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-2 d-none">
                                        <label for="semester">Semester<span style="color: red"> *</span></label>
                                        <select required name="semester" class="form-control">
                                            <option value="">-- Select Semester -- </option>
                                            <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
                                            <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
                                        </select>
    
                                        @if($errors->has('semester'))
                                        <p style="color: red">{{ $errors->first('semester') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-2 d-none">
                                        <label for="type_schedule">Type Schedule<span style="color: red"> *</span></label>
                                        <select required name="type_schedule" class="form-control" id="type_schedule">
                                            @foreach($data['typeSchedule'] as $el)
                                            <option value="{{ $el->id }}" selected>{{ $el->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('type_schedule'))
                                        <p style="color: red">{{ $errors->first('type_schedule') }}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-2 d-none">
                                        <label for="grade_id">Grade<span style="color: red"> *</span></label>
                                        <select required name="grade_id" class="form-control" id="grade_id">
                                            @foreach($data['grade'] as $el)
                                            <option value="{{ $el->id }}" selected>{{ $el->name }} - {{ $el->class}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('grade_id'))
                                        <p style="color: red">{{ $errors->first('grade_id') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th style="width: 14%;">Subject</th>
                                        <th style="width: 18%;">Teacher</th>
                                        <th style="width: 18%;">Assisstant</th>
                                        <th style="width: 8%;">Days</th>
                                        <th style="width: 8%;">Start Time</th>
                                        <th style="width: 8%;">End Time</th>
                                        <th style="width: 12%;">Notes</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="scheduleTableBody">
                                        <tr>
                                            <td>
                                                <select name="subject_id[]" class="form-control" id="subject_id">
                                                    <option value="" selected> -- Subject --</option>
                                                    @foreach($data['subject'] as $el)
                                                        <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('subject_id'))
                                                <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <select name="teacher_id[]" class="form-control" id="teacher_id">
                                                    <option value="" selected> -- Teacher --</option>
                                                    @foreach($data['teacher'] as $el)
                                                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('teacher_id'))
                                                <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <select name="teacher_companion[]" class="form-control" id="teacher_companion">
                                                    <option value="" selected>-- Assisstant --</option>
                                                    @foreach($data['teacher'] as $dt)
                                                    <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('teacher_companion'))
                                                <p style="color: red">{{ $errors->first('teacher_companion') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <select name="day[]" class="form-control">
                                                    <option value="" class="text-xs">Day</option>
                                                    <option value="1">Monday</option>
                                                    <option value="2">Tuesday</option>
                                                    <option value="3">Wednesday</option>
                                                    <option value="4">Thursday</option>
                                                    <option value="5">Friday</option>
                                                </select>

                                                @if($errors->has('day'))
                                                <p style="color: red">{{ $errors->first('day') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" id="start_time" name="start_time[]">
                                                @if($errors->has('start_time'))
                                                <p style="color: red">{{ $errors->first('start_time') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="time" class="form-control" id="end_time" name="end_time[]">
                                                @if($errors->has('end_time'))
                                                <p style="color: red">{{ $errors->first('end_time') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <textarea name="notes[]" class="form-control" id="notes" cols="10" rows="1"></textarea>
                                                @if($errors->has('notes'))
                                                <p style="color: red">{{ $errors->first('notes') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row d-flex justify-content-center">
                                <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <!-- general form elements -->
                    <div>
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">See Teacher Schedule</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body" style="height:340px;overflow-y:auto;">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Teacher: <span style="color: red"></span></label>
                                            <select id="teacher-select" name="teacher_select" class="form-control">
                                                <option value="" selected>-- Select Teacher --</option>
                                                @foreach ($data['teacher'] as $tc)
                                                    <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Grade: <span style="color: red"></span></label>
                                            <select id="grade-select" name="grade_select" class="form-control">
                                                <option value="" selected>-- Select Grade --</option>
                                                @foreach ($data['grades'] as $gr)
                                                    <option value="{{ $gr->id }}">{{ $gr->name }} - {{ $gr->class }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Day: <span style="color: red"></span></label>
                                            <select id="day-select" name="day_select" class="form-control">
                                                <option value="">-- Select Day --</option>
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
        
                                <div id="scheduleTeacher">
                            </div>
                        </div>
                    </div>
        
                    <div>
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">See Assistant Schedule</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body" style="height:340px;overflow-y:auto;">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Assistant: <span style="color: red"></span></label>
                                            <select id="assistant-select" name="assistant_select" class="form-control">
                                                <option value="" selected>-- Select Teacher --</option>
                                                @foreach ($data['teacher'] as $tc)
                                                    <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Grade: <span style="color: red"></span></label>
                                            <select id="assistant-grade-select" name="assistant_grade_select" class="form-control">
                                                <option value="" selected>-- Select Grade --</option>
                                                @foreach ($data['grades'] as $gr)
                                                    <option value="{{ $gr->id }}">{{ $gr->name }} - {{ $gr->class }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Day: <span style="color: red"></span></label>
                                            <select id="assistant-day-select" name="assistant-day-select" class="form-control">
                                                <option value="">-- Select Day --</option>
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="scheduleTeacherCompanion"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Function to add a new row
        function addRow() {
            var newRow = `<tr>
                <td>
                    <select name="subject_id[]" class="form-control" id="subject_id">
                        <option value="" selected> -- Subject --</option>
                        @foreach($data['subject'] as $el)
                            <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('subject_id'))
                    <p style="color: red">{{ $errors->first('subject_id') }}</p>
                    @endif
                </td>
                <td>
                    <select name="teacher_id[]" class="form-control" id="teacher_id">
                        <option value="" selected> -- Teacher --</option>
                        @foreach($data['teacher'] as $el)
                            <option value="{{ $el->id }}">{{ $el->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('teacher_id'))
                    <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                    @endif
                </td>
                <td>
                    <select name="teacher_companion[]" class="form-control">
                        <option value="" selected > Teacher Companion </option>
                        @foreach($data['teacher'] as $dt)
                        <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="day[]" class="form-control">
                        <option value="" class="text-xs">Day</option>
                        <option value="1">Monday</option>
                        <option value="2">Tuesday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                    </select>
                </td>
                <td>
                    <input type="time" class="form-control" name="start_time[]">
                </td>
                <td>
                    <input type="time" class="form-control" name="end_time[]">
                </td>
                <td>
                    <textarea name="notes[]" class="form-control" cols="10" rows="1"></textarea>
                </td>
                <td>
                    <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data"><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1" title="Hapus Baris"><i class="fa fa-times"></i></button>
                </td>
            </tr>`;
            $('#scheduleTableBody').append(newRow);

            // Call the function to populate subject and teacher options for the new row
            const newSubjectSelect = $('#scheduleTableBody tr:last .subject_id');
            const newTeacherSelect = $('#scheduleTableBody tr:last .teacher_id');

            loadSubjectOptionExam($('#grade_id').val(), newSubjectSelect);
            newSubjectSelect.change(function() {
                loadTeacherOption($('#grade_id').val(), $(this).val(), newTeacherSelect);
            });

            updateHapusButtons();
        }

        // Function to update the visibility of the "Hapus" buttons
        function updateHapusButtons() {
            $('#scheduleTableBody tr').each(function(index, row) {
                var hapusButton = $(row).find('.btn-hapus');
                if (index === $('#scheduleTableBody tr').length - 1) {
                    hapusButton.removeClass('d-none');
                } else {
                    hapusButton.addClass('d-none');
                }
            });
        }

        // Event listener for the "Tambah" button
        $('#scheduleTableBody').on('click', '.btn-tambah', function() {
            addRow();
        });

        // Event listener for the "Hapus" button
        $('#scheduleTableBody').on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();
            updateHapusButtons();
        });

        // Initial call to update the visibility of the "Hapus" buttons
        updateHapusButtons();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const teacherSelect = document.getElementById('teacher-select');
        const gradeSelect = document.getElementById('grade-select'); 
        const daySelect = document.getElementById('day-select');
        const scheduleTeacherDiv = document.getElementById('scheduleTeacher');

        teacherSelect.addEventListener('change', validateAndFetchSchedule);
        gradeSelect.addEventListener('change', validateAndFetchSchedule);
        daySelect.addEventListener('change', validateAndFetchSchedule);

        function validateAndFetchSchedule() {
            const teacher = teacherSelect.value || 'null';
            const grade = gradeSelect.value || 'null';
            const day = daySelect.value || 'null';

            fetchTeacherSchedule(teacher, grade, day);
        }

        function fetchTeacherSchedule(teacher, grade, day) {
            fetch(`/get-schedule-filter/${teacher}/${grade}/${day}`)
                .then(response => response.json())
                .then(data => {
                    renderScheduleTable(data, scheduleTeacherDiv);
                })
                .catch(error => console.error('Error fetching schedule:', error));
        }

        function renderScheduleTable(data, container) {
            let table = '<table class="table table-bordered">';
            table += `
                <thead>
                    <tr>
                        <th style="font-size:11px;">Grade</th>
                        <th style="font-size:11px;">Subject</th>
                        <th style="font-size:11px;">Teacher</th>
                        <th style="font-size:11px;">Day</th>
                        <th style="font-size:11px;">Start Time</th>
                        <th style="font-size:11px;">End Time</th>
                    </tr>
                </thead>
                <tbody>
            `;

            const getDayName = (day) => {
                switch(day) {
                    case 1:
                        return "Monday";
                    case 2:
                        return "Tuesday";
                    case 3:
                        return "Wednesday";
                    case 4:
                        return "Thursday";
                    case 5:
                        return "Friday";
                    default:
                        return "";
                }
            }

            data.forEach((item, index) => {
                table += `
                    <tr>
                        <td style="font-size:11px;">${item.grade_name || ''}</td>
                        <td style="font-size:11px;">${item.subject_name == null ? item.note : item.subject_name}</td>
                        <td style="font-size:11px;">${item.teacher_name == null ? '' : item.teacher_name}</td>
                        <td style="font-size:11px;">${getDayName(item.day)}</td>
                        <td style="font-size:11px;">${item.start_time}</td>
                        <td style="font-size:11px;">${item.end_time}</td>
                    </tr>
                `;
            });

            table += '</tbody></table>';
            container.innerHTML = table;
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const teacherAssistSelect = document.getElementById('assistant-select');
        const gradeAssistSelect = document.getElementById('assistant-grade-select');
        const dayAssistSelect = document.getElementById('assistant-day-select');
        const scheduleTeacherDiv = document.getElementById('scheduleTeacherCompanion');

        teacherAssistSelect.addEventListener('change', validateAndFetchSchedule);
        gradeAssistSelect.addEventListener('change', validateAndFetchSchedule);
        dayAssistSelect.addEventListener('change', validateAndFetchSchedule);

        function validateAndFetchSchedule() {
            const teacherAssist = teacherAssistSelect.value || 'null';
            const gradeAssist = gradeAssistSelect.value || 'null';
            const dayAssist = dayAssistSelect.value || 'null';

            fetchTeacherSchedule(teacherAssist, gradeAssist, dayAssist);
        }

        function fetchTeacherSchedule(teacherAssist, gradeAssist, dayAssist) {
            fetch(`/get-schedule-assist-filter/${teacherAssist}/${gradeAssist}/${dayAssist}`)
                .then(response => response.json())
                .then(data => {
                    renderScheduleTable(data, scheduleTeacherDiv);
                })
                .catch(error => console.error('Error fetching schedule:', error));
        }

        function renderScheduleTable(data, container) {
            let table = '<table class="table table-bordered">';
            table += `
                <thead>
                    <tr>
                        <th style="font-size:11px;">Grade</th>
                        <th style="font-size:11px;">Subject</th>
                        <th style="font-size:11px;">Assistant</th>
                        <th style="font-size:11px;">Day</th>
                        <th style="font-size:11px;">Start Time</th>
                        <th style="font-size:11px;">End Time</th>
                    </tr>
                </thead>
                <tbody>
            `;

            const getDayName = (day) => {
                switch(day) {
                    case 1:
                        return "Monday";
                    case 2:
                        return "Tuesday";
                    case 3:
                        return "Wednesday";
                    case 4:
                        return "Thursday";
                    case 5:
                        return "Friday";
                    default:
                        return "";
                }
            }

            data.forEach((item, index) => {
                table += `
                    <tr>
                        <td style="font-size:11px;">${item.grade_name || ''}</td>
                        <td style="font-size:11px;">${item.subject_name == null ? item.note : item.subject_name}</td>
                        <td style="font-size:11px;">${item.teacher_name == null ? '' : item.teacher_name}</td>
                        <td style="font-size:11px;">${getDayName(item.day)}</td>
                        <td style="font-size:11px;">${item.start_time}</td>
                        <td style="font-size:11px;">${item.end_time}</td>
                    </tr>
                `;
            });

            table += '</tbody></table>';
            container.innerHTML = table;
        }
    });
</script>

@if(session('after_create_schedule')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new schedule in the database.',
        });
    </script>
@endif

@endsection
