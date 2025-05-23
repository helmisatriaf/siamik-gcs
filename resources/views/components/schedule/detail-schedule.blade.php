@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-3">
                <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/schedules/grades')}}">Master Schedule</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Schedule {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (session('role') == 'superadmin' || session('role') == 'admin')
    <a class="btn btn-success btn"
        href="{{url('/' . session('role') .'/schedules/grade/create') . '/' . $data['grade_id']}}">
        <i class="fas fa-calendar-plus">
        </i>
        Add
    </a>
    @endif

    @if (!empty($data))
        <div class="card card-dark mt-2">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{ $data['grade_name'] }} - {{ $data['grade_class'] }}</h5>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
</div>

<!-- Modal Detail Schedule -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Schedule Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="eventTitle"></p>
                <p id="eventDescription"></p>
                <div id="substituteTeacherBtnContainer" class="mb-2"></div>
                <div id="substituteTeacherCompanionBtnContainer"></div> <!-- Container for Substitute Teacher button -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>    
            </div>
        </div>
    </div>
</div>

<!-- Modal for showing teacher list -->
<div class="modal" id="substituteTeacherModal" tabindex="-1" aria-labelledby="substituteTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="substituteTeacherModalLabel">Select Substitute Teacher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <form id="confirmForm" method="POST" action={{route('subtitute.teacher')}}>
                                    @csrf
                                    <label>Select Substitute Teacher: <span style="color: red"></span></label>
                                    <select id="select_teacher" name="select_teacher" class="form-control">
                                        <option value="">-- Select Teacher --</option>
                                        @foreach ($teacher as $tc)
                                            <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-success w-100 mt-2" data-toggle="modal" id="submitSubstitute">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Filter Teacher: <span style="color: red"></span></label>
                                <select id="teacher-select" name="teacher" class="form-control">
                                    <option value="">-- Select Teacher --</option>
                                    @foreach ($teacher as $tc)
                                        <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Filer Grade: <span style="color: red"></span></label>
                                <select id="grade-select" name="grade" class="form-control">
                                    <option value="">-- Select Grade --</option>
                                    @foreach ($grade as $gr)
                                        <option value="{{ $gr->id }}">{{ $gr->name }} - {{ $gr->class }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-dark overflow-x-auto" style="min-width:2000px;">
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
                                        @foreach ($teacher as $tc)
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
                                        @foreach ($grade as $gr)
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
                <div class="text-center d-none">
                    <label>All Schedule Teacher</label>
                </div>
                <div class="table-responsive d-none">
                    <table class="table table-bordered" id="substituteTeacherTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Teacher Name</th>
                                <th>Grade Name</th>
                                <th>Subject Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for showing teacher companion list -->
<div class="modal" id="substituteTeacherCompanionModal" tabindex="-1" aria-labelledby="substituteTeacherCompanionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="substituteTeacherCompanionModalLabel">Select Substitute Assisstant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <form id="confirmForm" method="POST" action={{route('subtitute.teacher')}}>
                                    @csrf
                                    <label>Select Substitute Assisstant: <span style="color: red"></span></label>
                                    <select id="select_teacher_companion" name="select_teacher_companion" class="form-control">
                                        <option value="">-- Select Teacher --</option>
                                        @foreach ($teacher as $tc)
                                            <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-success w-100 mt-2" data-toggle="modal" id="submitSubstituteCompanion">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Teacher: <span style="color: red"></span></label>
                                <select id="teacher-select-companion" name="teacher_companion" class="form-control">
                                    <option value="" selected>-- Select Teacher --</option>
                                    @foreach ($teacher as $tc)
                                        <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Grade: <span style="color: red"></span></label>
                                <select id="grade-select-companion" name="grade_companion" class="form-control">
                                    <option value="" selected>-- Select Grade --</option>
                                    @foreach ($grade as $gr)
                                        <option value="{{ $gr->id }}">{{ $gr->name }} - {{ $gr->class }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="substituteTeacherCompanionTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Assisstant</th>
                                <th>Grade Name</th>
                                <th>Subject Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@else
    <p>Data Kosong</p>
@endif

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        dayHeaderFormat: { weekday: 'short' },
        slotLabelFormat: { hour: 'numeric', minute: '2-digit', omitZeroMinute: false, meridiem: 'short' },
        slotMinTime: '07:00:00',
        slotMaxTime: '18:00:00',
        hiddenDays: [0, 6],
        events: [
            @foreach($gradeSchedule as $schedule)
            @php
                $event = [
                    'title' => $schedule->note == "" ? $schedule->subject_name : $schedule->note,
                    'startRecur' => $startSemester,
                    'endRecur' => $endSemester,
                    'daysOfWeek' => [$schedule->day],
                    'startTime' => $schedule->start_time,
                    'endTime' => $schedule->end_time,
                    'description' => '',
                    'color' => 'blue',
                    'teacherId' => $schedule->teacher_id,
                    'teacherCompanion' => $schedule->teacher_companion_id,
                    'day' => $schedule->day,
                ];

                switch (strtolower($schedule->note)) {
                    case 'break':
                        $event['description'] = 'BREAK';
                        $event['color'] = 'red';
                        break;
                    case 'eca':
                        $event['description'] = 'ECA';
                        $event['color'] = 'bluesky';
                        break;
                    case 'advisory session by ct':
                        $event['description'] = 'Advisory Session by CT';
                        $event['color'] = 'orange';
                        break;
                    case 'general assembly':
                        $event['description'] = 'General Assembly';
                        $event['color'] = 'pink';
                        break;
                    case 'morning reading':
                        $event['description'] = 'Morning Reading';
                        $event['color'] = 'orange';
                        break;
                    default:
                        $event['description'] = "<br>Teacher: {$schedule->teacher_name}<br>Assisstant: {$schedule->teacher_companion}<br>Grade: {$schedule->grade_name} - {$schedule->grade_class}";
                }

                echo json_encode($event) . ',';

                foreach ($subtituteTeacher as $st) {
                    if ($st->grade_id == $schedule->grade_id && $st->subject_id == $schedule->subject_id && $st->day == $schedule->day && $st->start_time == $schedule->start_time && $st->end_time == $schedule->end_time) {
                        if ($st->teacher_companion_id == $schedule->teacher_companion_id) {
                            $substituteEvent = [
                                'title' => $schedule->subject_name,
                                'start' => "{$st->date}T{$schedule->start_time}",
                                'end' => "{$st->date}T{$schedule->end_time}",
                                'description' => "<br>Teacher: {$st->teacher_name} <span class='badge badge-danger'>substitute</span><br>Assisstant: {$schedule->teacher_companion} <br>Grade: {$schedule->grade_name} - {$schedule->grade_class}",
                                'color' => 'darkblue',
                                'teacherId' => $schedule->teacher_id,
                                'teacherCompanion' => $schedule->teacher_companion_id,
                                'day' => $schedule->day,
                            ];
                        }
                        elseif ($st->teacher_id == $schedule->teacher_id) {
                            $substituteEvent = [
                                'title' => $schedule->subject_name,
                                'start' => "{$st->date}T{$schedule->start_time}",
                                'end' => "{$st->date}T{$schedule->end_time}",
                                'description' => "<br>Teacher: {$schedule->teacher_name}<br>Assisstant: {$st->teacher_companion} <span class='badge badge-danger'>substitute</span> <br>Grade: {$schedule->grade_name} - {$schedule->grade_class}",
                                'color' => 'green',
                                'teacherId' => $schedule->teacher_id,
                                'teacherCompanion' => $schedule->teacher_companion_id,
                                'day' => $schedule->day,
                            ];
                        }
                        elseif ($st->teacher_id !== $schedule->teacher_id && $st->teacher_companion_id !== $schedule->teacher_companion_id) {
                            $substituteEvent = [
                                'title' => $schedule->subject_name,
                                'start' => "{$st->date}T{$schedule->start_time}",
                                'end' => "{$st->date}T{$schedule->end_time}",
                                'description' => "<br>Teacher: {$st->teacher_name}<span class='badge badge-danger'>substitute</span><br>Assisstant: {$st->teacher_companion} <span class='badge badge-danger'>substitute</span> <br>Grade: {$schedule->grade_name} - {$schedule->grade_class}",
                                'color' => 'pink',
                                'teacherId' => $schedule->teacher_id,
                                'teacherCompanion' => $schedule->teacher_companion_id,
                                'day' => $schedule->day,
                            ];
                        }
                        echo json_encode($substituteEvent) . ',';
                    }
                }
            @endphp
            @endforeach
        ],
        eventClick: function(info) {
            document.getElementById('eventTitle').innerText = info.event.title;
            document.getElementById('eventDescription').innerHTML = 'Description : ' + info.event.extendedProps.description;
            
            showSubstituteButton({
                subject: info.event.title,
                day: info.event.start,
                start_time: info.event.startStr,
                end_time: info.event.endStr,
                teacher_companion:info.event.extendedProps.teacherCompanion,
            });

            showSubstituteCompanionButton({
                subject: info.event.title,
                day: info.event.start,
                start_time: info.event.startStr,
                end_time: info.event.endStr,
                teacher_id: info.event.extendedProps.teacherId,
                teacher_companion:info.event.extendedProps.teacherCompanion,
            });

            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                keyboard: false
            });
            eventModal.show();
        }
    });
    calendar.render();

    function showSubstituteButton(scheduleData) {
        var substituteBtnContainer = document.getElementById('substituteTeacherBtnContainer');
        substituteBtnContainer.innerHTML = '';
    
        var day = new Date(scheduleData.day).getDay();
        var date = moment(scheduleData.day).format('YYYY-MM-DD');
    
        var startTime = moment(scheduleData.start_time).format('HH:mm');
        var endTime = moment(scheduleData.end_time).format('HH:mm');
        var subject = scheduleData.subject;
        var teacherCompanion = scheduleData.teacher_companion;
    
        document.getElementById('submitSubstitute').addEventListener('click', function() {
            var selectTeacherElement = document.getElementById('select_teacher');
            var selectedTeacherId = selectTeacherElement.value;
            var teacherName = selectTeacherElement.options[selectTeacherElement.selectedIndex].text;
    
            @foreach ($gradeSchedule as $gs)
                var selectedGradeId = {{ $gs->grade_id }};
            @endforeach
    
            if (selectedTeacherId === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a teacher!',
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to assign ${teacherName} as the substitute teacher?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, assign!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('subtitute.teacher') }}',
                            type: 'POST',
                            data: {
                                grade_id: selectedGradeId,
                                subject_id: subject,
                                teacher_id: selectedTeacherId,
                                teacher_companion: teacherCompanion,
                                date: date,
                                day: day,
                                start_time: startTime,
                                end_time: endTime,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Assigned!', 'The substitute teacher has been assigned.', 'success').then(() => {
                                    location.reload();
                                });
                                console.log('success:', response);
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error!', 'There was an error assigning the substitute teacher.', 'error');
                                console.error('Error saving:', error);
                            }
                        });
                    }
                });
            }
        });
    
        if (!scheduleData.teacher_id) {
            var substituteBtn = document.createElement('button');
            substituteBtn.setAttribute('type', 'button');
            substituteBtn.setAttribute('class', 'btn btn-primary');
            substituteBtn.innerText = 'Substitute Teacher';
    
            substituteBtn.onclick = function() {
                var selectedTeacher = document.querySelector('select[name="teacher"]').value;
                var selectedGrade = document.querySelector('select[name="grade"]').value;
    
                fetch(`/get-schedule-teacher/${day}/${startTime}/${endTime}?teacher=${selectedTeacher}&grade=${selectedGrade}`)
                    .then(response => response.json())
                    .then(data => {
                        var tableBody = document.querySelector('#substituteTeacherTable tbody');
                        tableBody.innerHTML = '';
    
                        data.forEach(teacher => {
                            var row = document.createElement('tr');
    
                            ['teacher_name', 'grade_name', 'subject_name', 'start_time', 'end_time'].forEach(key => {
                                var td = document.createElement('td');
                                if (key === 'grade_name' && teacher['grade_name'] && teacher['grade_class']) {
                                    td.innerText = `${teacher['grade_name']} - ${teacher['grade_class']}`;
                                } else {
                                    td.innerText = teacher[key];
                                }
                                row.appendChild(td);
                            });
    
                            tableBody.appendChild(row);
                        });
    
                        var modal = new bootstrap.Modal(document.getElementById('substituteTeacherModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            };
    
            substituteBtnContainer.appendChild(substituteBtn);
        }
    }
    
    function showSubstituteCompanionButton(scheduleData) {
    
        var substituteCompanionBtnContainer = document.getElementById('substituteTeacherCompanionBtnContainer');
        substituteCompanionBtnContainer.innerHTML = '';
    
        var day = new Date(scheduleData.day).getDay();
        var date = moment(scheduleData.day).format('YYYY-MM-DD');
    
        var startTime = moment(scheduleData.start_time).format('HH:mm');
        var endTime = moment(scheduleData.end_time).format('HH:mm');
        var subject = scheduleData.subject;
        var teacherCompanion = scheduleData.teacher_companion;
        var teacherId = scheduleData.teacher_id;
        
        document.getElementById('submitSubstituteCompanion').addEventListener('click', function() {
            var selectTeacherCompanionElement = document.getElementById('select_teacher_companion');
            var selectedTeacherCompanionId = selectTeacherCompanionElement.value;
            var teacherCompanionName = selectTeacherCompanionElement.options[selectTeacherCompanionElement.selectedIndex].text;
    
            @foreach ($gradeSchedule as $gs)
                var selectedGradeId = {{ $gs->grade_id }};
            @endforeach
    
            if (selectedTeacherCompanionId === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a teacher!',
                });
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to assign ${teacherCompanionName} as the substitute Assisstant?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, assign!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('subtitute.teacher') }}',
                            type: 'POST',
                            data: {
                                grade_id: selectedGradeId,
                                subject_id: subject,
                                teacher_id: teacherId,
                                teacher_companion: selectedTeacherCompanionId,
                                date: date,
                                day: day,
                                start_time: startTime,
                                end_time: endTime,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Assigned!', 'The substitute Assisstant has been assigned.', 'success').then(() => {
                                    location.reload();
                                });
                                console.log('success:', response);
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error!', 'There was an error assigning the substitute Assisstant.', 'error');
                                console.error('Error saving:', error);
                            }
                        });
                    }
                });
            }
        });
    
        if (!scheduleData.teacher_companion_id) {
            var substituteCompanionBtn = document.createElement('button');
            substituteCompanionBtn.setAttribute('type', 'button');
            substituteCompanionBtn.setAttribute('class', 'btn btn-danger');
            substituteCompanionBtn.innerText = 'Substitute Assisstant';
    
            substituteCompanionBtn.onclick = function() {
                var selectedTeacher = document.querySelector('select[name="teacher"]').value;
                var selectedGrade = document.querySelector('select[name="grade"]').value;
    
                fetch(`/get-schedule-companion/${day}/${startTime}/${endTime}?teacher=${selectedTeacher}&grade=${selectedGrade}`)
                    .then(response => response.json())
                    .then(data => {
                        var tableBody = document.querySelector('#substituteTeacherCompanionTable tbody');
                        tableBody.innerHTML = '';
    
                        console.log("hasil:",data);
                        data.forEach(teacher => {
                            var row = document.createElement('tr');
    
                            ['teacher_companion', 'grade_name', 'subject_name', 'start_time', 'end_time'].forEach(key => {
                                var td = document.createElement('td');
                                if (key === 'grade_name' && teacher['grade_name'] && teacher['grade_class']) {
                                    td.innerText = `${teacher['grade_name']} - ${teacher['grade_class']}`;
                                } else {
                                    td.innerText = teacher[key];
                                }
                                row.appendChild(td);
                            });
    
                            tableBody.appendChild(row);
                        });
    
                        var modal = new bootstrap.Modal(document.getElementById('substituteTeacherCompanionModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            };
    
            substituteCompanionBtnContainer.appendChild(substituteCompanionBtn);
        }
    }
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var teacherSelect = document.getElementById('teacher-select');
        var gradeSelect = document.getElementById('grade-select');
        var teacherCompanionSelect = document.getElementById('teacher-select-companion');
        var gradeCompanionSelect = document.getElementById('grade-select-companion');

        teacherSelect.addEventListener('change', validateAndFetchSchedule);
        gradeSelect.addEventListener('change', validateAndFetchSchedule);

        teacherCompanionSelect.addEventListener('change', filterSchedulesCompanion);
        gradeCompanionSelect.addEventListener('change', filterSchedulesCompanion);

        function validateAndFetchSchedule() {
            const teacher = teacherSelect.value || 'null';
            const grade = gradeSelect.value || 'null';

            filterSchedulesTeacher(teacher, grade);
        }

        function filterSchedulesTeacher(teacher, grade) {
            fetch(`/get-schedule-subtitute-filter/${teacher}/${grade}`)
                .then(response => response.json())
                .then(data => {
                    var tableBody = document.querySelector('#substituteTeacherTable tbody');
                    tableBody.innerHTML = '';

                    data.forEach(teacher => {
                        var row = document.createElement('tr');

                        ['teacher_name', 'grade_name', 'subject_name', 'start_time', 'end_time'].forEach(key => {
                            var td = document.createElement('td');

                            if (key === 'grade_name' && teacher['grade_name'] && teacher['grade_class']) {
                                td.innerText = `${teacher['grade_name']} - ${teacher['grade_class']}`;
                            } else {
                                td.innerText = teacher[key];
                            }
                            row.appendChild(td);
                        });
                        tableBody.appendChild(row);
                    });

                    var modal = new bootstrap.Modal(document.getElementById('substituteTeacherModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function filterSchedulesCompanion(teacherAssist, gradeAssist) {
            fetch(`/get-schedule-companion-filter/${teacherAssist}/${gradeAssist}`)
                .then(response => response.json())
                .then(data => {
                    var tableBody = document.querySelector('#substituteTeacherCompanionTable tbody');
                    tableBody.innerHTML = '';

                    // console.log(data);
                    data.forEach(teacher => {
                        var row = document.createElement('tr');

                        ['teacher_companion', 'grade_name', 'subject_name', 'start_time', 'end_time'].forEach(key => {
                            var td = document.createElement('td');
                            if (key === 'grade_name' && teacher['grade_name'] && teacher['grade_class']) {
                                td.innerText = `${teacher['grade_name']} - ${teacher['grade_class']}`;
                            } else {
                                td.innerText = teacher[key];
                            }
                            row.appendChild(td);
                        });

                        tableBody.appendChild(row);
                    });

                    var modal = new bootstrap.Modal(document.getElementById('substituteTeacherCompanionModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }
    })
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
        title: 'Successfully created new grade schedule in the database.',
    });
   </script>
@endif

@if(session('after_subtitute_teacher_schedule')) 
   <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully subtitute teacher schedule in the database.',
        });
   </script>
@endif

@endsection
