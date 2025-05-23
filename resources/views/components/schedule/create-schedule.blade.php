@extends('layouts.admin.master')
@section('content')

<div class="container-fluid">
    @if (session('role') == 'superadmin')
        <form method="POST" action={{route('actionSuperCreateSchedule')}}>
    @elseif (session('role') == 'admin')
        <form method="POST" action={{route('actionAdminCreateSchedule')}}>
    @endif
    @csrf
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
    
    <div class="container-fluid" style="overflow-x: auto;">
        <div class="row" style="width: 3000px;">
            <!-- Left column -->
            <div class="col-md-5">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Create Schedule</h3>
                    </div>
                    <div class="card-body" style="max-height: 700px; overflow-y: auto;">
                        <div class="row mb-2">
                            <div class="col-md-2 d-none">
                                <label for="semester">Semester<span style="color: red"> *</span></label>
                                <select required name="semester" class="form-control">
                                    <option value="">-- Select Semester --</option>
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
                                        <option value="{{ $el->id }}" selected>{{ $el->name }} - {{ $el->class }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('grade_id'))
                                    <p style="color: red">{{ $errors->first('grade_id') }}</p>
                                @endif
                            </div>
                        </div>

                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:15%">Subject</th>
                                    <th style="width:15%">Teacher</th>
                                    <th style="width:15%">Assistant</th>
                                    <th>Days</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="scheduleTableBody">
                                <tr>
                                    <td>
                                        <select name="subject_id[]" class="form-control js-select2" id="subject_id"></select>
                                        @if($errors->has('subject_id'))
                                            <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <select name="teacher_id[]" class="form-control js-select2" id="teacher_id">
                                            <option value="">-- Teacher --</option>
                                        </select>
                                        @if($errors->has('teacher_id'))
                                            <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <select name="teacher_companion[]" class="form-control js-select2"" id="teacher_companion">
                                            <option value="" selected>-- Assistant --</option>
                                            @foreach($data['teacher'] as $dt)
                                                <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('teacher_companion'))
                                            <p style="color: red">{{ $errors->first('teacher_companion') }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <select required name="day[]" class="form-control">
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
                    <input role="button" type="submit" class="btn btn-success mx-3 mb-2">
                </div>
                </form>
            </div>

            <!-- Right column -->
            <div class="col-md-7">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">All Schedule</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Teacher: <span style="color: red"></span></label>
                                    <select id="teacher-select-all" name="teacher_select_all" class="form-control">
                                        <option value="" selected>-- Select Teacher --</option>
                                        @foreach ($data['teacher'] as $tc)
                                            <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Grade: <span style="color: red"></span></label>
                                    <select id="grade-select-all" name="grade_select_all" class="form-control">
                                        <option value="" selected>-- Select Grade --</option>
                                        @foreach ($data['grades'] as $gr)
                                            <option value="{{ $gr->id }}">{{ $gr->name }} - {{ $gr->class }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Day: <span style="color: red"></span></label>
                                    <select id="day-select-all" name="day_select_all" class="form-control">
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
                        <div id="scheduleAll"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Function to add a new row
        function addRow() {
            var newRow = `<tr>
                <td>
                    <select name="subject_id[]" class="form-control subject_id js-select2">
                    </select>
                </td>
                <td>
                    <select name="teacher_id[]" class="form-control teacher_id js-select2">
                        <option value="" selected >-- Teacher --</option>
                    </select>
                </td>
                <td>
                    <select name="teacher_companion[]" class="form-control js-select2">
                        <option value="" selected >-- Assistant --</option>
                        @foreach($data['teacher'] as $dt)
                        <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select required name="day[]" class="form-control">
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

            $('.js-select2').select2({
                closeOnSelect : false,
                placeholder : "Click to select an option",
                theme: 'bootstrap4',
                allowHtml: true,
                allowClear: true,
                tags: true,
                searchInputPlaceholder: 'Search options'
            });
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
            const rows = $('#scheduleTableBody tr');

            rows.each(function(index, row) {
                var tambahButton = $(row).find('.btn-tambah');
                var hapusButton = $(row).find('.btn-hapus');

                if (rows.length === 1) {
                    // Jika hanya ada satu baris, hanya tampilkan tombol "Tambah"
                    tambahButton.removeClass('d-none');
                    hapusButton.addClass('d-none');
                } else {
                    // Baris terakhir tampilkan tombol "Tambah" dan "Hapus"
                    if (index === rows.length - 1) {
                        tambahButton.removeClass('d-none');
                        hapusButton.removeClass('d-none');
                    } else {
                        // Baris lainnya hanya tampilkan tombol "Hapus"
                        tambahButton.addClass('d-none');
                        hapusButton.removeClass('d-none');
                    }
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

        // Initial call to update the visibility of the "Hapus" and "Tambah" buttons
        updateHapusButtons();
    });

    function loadSubjectOptionExam(gradeId, subjectSelect) {
        // Clear existing options and add the default option
        subjectSelect.html('<option value="" selected >-- Subject --</option>');

        fetch(`/get-subjects/${gradeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    // If no subjects, add "Subject Empty" option
                    const option = document.createElement('option');
                    option.value = '';
                    option.text = 'Subject Empty';
                    subjectSelect.append(option);
                } else {
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.text = subject.name_subject;
                        subjectSelect.append(option);
                    });
                }
            })
            .catch(error => console.error(error));
    }

    function loadTeacherOption(gradeId, subjectId, teacherSelect) {
        // Clear existing options and add the default option
        teacherSelect.html('<option value="" selected >-- Teacher --</option>');

        fetch(`/get-teachers/${gradeId}/${subjectId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    // If no teachers, add "Teacher Empty" option
                    const option = document.createElement('option');
                    option.value = '';
                    option.text = 'Teacher Empty';
                    teacherSelect.append(option);
                } else {
                    data.forEach(teacher => {
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.text = teacher.name;
                        teacherSelect.append(option);
                    });
                }
            })
            .catch(error => console.error(error));
    }

    // Call loadSubjectOptionExam if grade_id is already selected
    window.onload = function() {
        const gradeSelect = document.getElementById('grade_id');
        const subjectSelect = document.getElementById('subject_id');

        if (gradeSelect.value) {
            loadSubjectOptionExam(gradeSelect.value, $(subjectSelect));
        }

        $(subjectSelect).change(function() {
            const teacherSelect = document.getElementById('teacher_id');
            loadTeacherOption(gradeSelect.value, $(this).val(), $(teacherSelect));
        });
    };
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const teacherSelectAll = document.getElementById('teacher-select-all');
        const gradeSelectAll = document.getElementById('grade-select-all'); 
        const daySelectAll = document.getElementById('day-select-all');
        const scheduleTeacherAllDiv = document.getElementById('scheduleAll');

        fetchTeacherAllSchedule('null', 'null', 'null');
        
        teacherSelectAll.addEventListener('change', validateAndFetchScheduleAll);
        gradeSelectAll.addEventListener('change', validateAndFetchScheduleAll);
        daySelectAll.addEventListener('change', validateAndFetchScheduleAll);

        function validateAndFetchScheduleAll() {
            const teacher = teacherSelectAll.value || 'null';
            const grade = gradeSelectAll.value || 'null';
            const day = daySelectAll.value || 'null';

            fetchTeacherAllSchedule(teacher, grade, day);
        }

        function fetchTeacherAllSchedule(teacher, grade, day) {
            fetch(`/get-all-schedule-filter/${teacher}/${grade}/${day}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Data fetched:', data); // Log data yang diambil
                    renderScheduleTableAll(data, scheduleTeacherAllDiv);
                })
                .catch(error => console.error('Error fetching schedule:', error));
        }

        function renderScheduleTableAll(data, container) {
            const dayColors = {
                "Monday": "#FFDDC1",
                "Tuesday": "#D4F1F4",
                "Wednesday": "#F7D6E0",
                "Thursday": "#D1D4E0",
                "Friday": "#FFF5E1"
            };

            const classColors = {
                "Toddler": "#FFCCCC",      // Light Pink
                "Nursery": "#CCFFCC",      // Light Green
                "Kindergarten - A": "#CCCCFF",           // Light Blue
                "Kindergarten - B": "#FFCC99",           // Light Orange
                "Primary - 1": "#FF99CC",  // Pink
                "Primary - 2": "#99CCFF",  // Sky Blue
                "Primary - 3": "#FFCC99",  // Peach
                "Primary - 4": "#99FF99",  // Light Lime
                "Primary - 5": "#FFCCFF",  // Light Magenta
                "Primary - 6": "#CCCC99",   // Light Olive
                "Secondary - 1": "#FF99FF", // Violet
                "Secondary - 2": "#FFFF99", // Light Yellow
                "Secondary - 3": "#99CCFF"  // Light Cyan
            };

            let table = '<table class="table table-bordered">';
            table += `
                <thead>
                    <tr>
                        <th style="font-size:12px;">Waktu</th>
                        <th style="font-size:12px;">Toddler</th>
                        <th style="font-size:12px;">Nursery</th>
                        <th style="font-size:12px;">KA</th>
                        <th style="font-size:12px;">KB</th>
                        <th style="font-size:12px;">Primary-1</th>
                        <th style="font-size:12px;">Primary-2</th>
                        <th style="font-size:12px;">Primary-3</th>
                        <th style="font-size:12px;">Primary-4</th>
                        <th style="font-size:12px;">Primary-5</th>
                        <th style="font-size:12px;">Primary-6</th>
                        <th style="font-size:12px;">Secondary-1</th>
                        <th style="font-size:12px;">Secondary-2</th>
                        <th style="font-size:12px;">Secondary-3</th>
                    </tr>
                </thead>
                <tbody>
            `;

            Object.keys(data).forEach(day => {
                table += `<tr style="background-color: ${dayColors[day]}"><td class="bg-black text-white text-center text-bold" style="font-size:12px;" colspan="14">${day.toUpperCase()}</td></tr>`;
                const gradeSchedules = data[day];

                // Kumpulkan semua slot waktu untuk hari ini
                const timeSlots = [];
                Object.keys(gradeSchedules).forEach(grade => {
                    gradeSchedules[grade].forEach(schedule => {
                        const timeSlot = `${schedule.start_time}-${schedule.end_time}`;
                        if (!timeSlots.includes(timeSlot)) {
                            timeSlots.push(timeSlot);
                        }
                    });
                });

                timeSlots.forEach(timeSlot => {
                    table += `<tr><td class="font-bold text-xs">${timeSlot}</td>`;
                    const timeSlotSchedules = {};

                    Object.keys(gradeSchedules).forEach(grade => {
                        gradeSchedules[grade].forEach(schedule => {
                            if (`${schedule.start_time}-${schedule.end_time}` === timeSlot) {
                                const gradeName = schedule.grade_name || '';
                                const classColor = classColors[gradeName.split(' ')[0]] || '#FFFFFF';
                        
                                if (!timeSlotSchedules[grade]) {
                                    timeSlotSchedules[grade] = '';
                                }
                                
                                timeSlotSchedules[grade] += `
                                    <div class="col p-0" style="background-color: ${classColor[grade]};">
                                        <p class="text-bold" style="font-size:12px;">${schedule.subject_name ? schedule.subject_name.toUpperCase() : ""}
                                        <br>${schedule.teacher_name}
                                        <br>${schedule.assisstant ? `${schedule.assisstant} (assisstant)` : ""}</p>
                                    </div>
                                `;

                                if (!schedule.subject_name) {
                                    timeSlotSchedules[grade] = `<p>${schedule.notes}</p>`;
                                }
                            }
                        });
                    });

                    const grades = ["Toddler", "Nursery", "Kindergarten - A", "Kindergarten - B", "Primary - 1", "Primary - 2", "Primary - 3", "Primary - 4", "Primary - 5", "Primary - 6", "Secondary - 1", "Secondary - 2", "Secondary - 3"];

                    grades.forEach(grade => {
                        table += `<td style="font-size:12px;background-color: ${classColors[grade]};">${timeSlotSchedules[grade] || ''}</td>`;
                    });

                    table += `</tr>`;
                });
            });

            table += '</tbody></table>';
            container.innerHTML = table;
        }
    });
</script>


@if(session('after_create_grade_schedule')) 
   <script>
    Swal.fire({
        icon: 'success',
        text: 'Successfully',
        title: 'Successfully created schedule in the database.',
    });
   </script>
@endif

@if(session('same_schedule')) 
   <script>
    Swal.fire({
        icon: 'error',
        text: 'Oops...',
        title: 'Schedule have same schedule with another schedule.',
    });
   </script>
@endif

@endsection