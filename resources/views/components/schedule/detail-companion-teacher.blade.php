@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    @if (!empty($data))
        <div class="card card-dark mt-2">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Assisstant Schedule</h5>

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
<div class="modal fade " id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Schedule Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="eventTitle"></p>
                <p id="eventDescription"></p></div>
            <div class="modal-footer">
                <div id="attendanceTeacherBtnContainer"></div>
            </div>
        </div>
    </div>
</div>

@else
    <p>Data Kosong</p>
@endif

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<?php
$startOfWeek = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d');
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var gradeSchedule = @json($gradeSchedule);
    var subtituteTeacher = @json($subtituteTeacher);
    var startOfWeek = @json($startOfWeek);

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
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
            ...gradeSchedule.map(schedule => ({
                title: schedule.subject_name,
                startRecur: startOfWeek,
                daysOfWeek: [schedule.day],
                startTime: schedule.start_time,
                endTime: schedule.end_time,
                description: `<br>Teacher : ${schedule.teacher_name}<br>TeaAssisstant : ${schedule.teacher_companion} <br>Grade : ${schedule.grade_name}`,
                color: colorGrades(schedule.grade_name),
                grade_id: schedule.grade_id,
                subject_id: schedule.subject_id,
            })),
            ...subtituteTeacher.map(subs => ({
                title: subs.subject_name,
                startRecur: startOfWeek,
                daysOfWeek: [subs.day],
                startTime: subs.start_time,
                endTime: subs.end_time,
                description: `<br>Teacher: ${subs.teacher_name}<span class='badge badge-danger'>substitute</span><br>TeaAssisstant : ${subs.teacher_companion}<br>Grade: ${subs.grade_name} - ${subs.grade_class}`,
                color: 'light',
                grade_id: subs.grade_id,
                subject_id: subs.subject_id,
            }))
        ],
        eventClick: function(info) {
            document.getElementById('eventTitle').innerText = info.event.title;
            document.getElementById('eventDescription').innerHTML = 'Description : ' + info.event.extendedProps.description;
            
            showAttendanceButton({
                gradeId: info.event.extendedProps.grade_id,
                subjectId:info.event.extendedProps.subject_id,
            });
            
            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                keyboard: false
            });
            eventModal.show();
        }
    });
    calendar.render();

    function colorGrades(key) {
        switch (key ? key.toLowerCase() : '') {
            case 'primary - 1':
                return 'brown';
            case 'primary - 2':
                return 'red';
            case 'primary - 3':
                return 'orange';
            case 'primary - 4':
                return 'pink';
            case 'primary - 5':
                return 'green';
            case 'primary - 6':
                return 'blue';
            case 'secondary - 1':
                return 'purple';
            case 'secondary - 2':
                return 'teal';
            case 'secondary - 3':
                return 'yellow';
            default:
                return 'gray'; // Default color if no match is found
        }
    }

    function showAttendanceButton(data) {
        var attendanceBtnContainer = document.getElementById('attendanceTeacherBtnContainer');
        attendanceBtnContainer.innerHTML = ''; // clear previous buttons

        if (data.gradeId && data.subjectId) {
            var attendanceBtn = document.createElement('button');
            attendanceBtn.setAttribute('type', 'button');
            attendanceBtn.setAttribute('class', 'btn btn-primary');
            attendanceBtn.innerText = 'Attendance';

            attendanceBtn.onclick = function() {
                window.location.href = "{{ url('/teacher/dashboard/attendanceSubject') }}/" + '{{ session("id_user") }}' + "/" + data.gradeId + "/" + data.subjectId;
            };

            attendanceBtnContainer.appendChild(attendanceBtn);
        } else {
            // Hide or disable the container if no grade and subject are provided, or if the user is not a teacher
            attendanceBtnContainer.style.display = 'none';
        }
    }
});

</script>

@if(session('after_create_grade_schedule')) 
   <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new grade schedule in the database.',
        });
   </script>
@endif

@if(session('after_subtitute_teacher_schedule')) 
   <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully subtitute teacher schedule in the database.',
        });
   </script>
@endif

@endsection
