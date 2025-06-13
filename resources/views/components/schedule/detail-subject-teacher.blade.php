@extends('layouts.admin.master')
@section('content')

<style>
    .custom-modal-dialog {
        padding: 10px;
        background-color: #ffde9e;
        color: #000;
        border: 3px solid #ffcc00;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
        border-radius: 90% 70% 80% 85% / 80% 80% 80% 95% ;
    }
    .fc {
        background-color: transparent !important;
    }

    .fc-daygrid-body {
        background-color: #fffde9;
    }

    .fc-col-header {
        background-color: #ffde9e;
    }

    .fc-daygrid-day {
        background-color: #fff9cc;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    @if (!empty($data))
    <h5 class="text-bold text-xl">Your Subject Schedule</h5>
    <div id="calendar"></div>
</div>

<!-- Modal Detail Schedule -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content custom-modal-dialog">
            <div class="modal-body">
                <p id="eventTitle" class="text-lg text-center"></p>
                <p id="eventDescription" class="text-lg"></p></div>
            <!-- <div class="modal-footer">
                <div id="attendanceTeacherBtnContainer"></div>
            </div> -->
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
    var gradeSchedule           = @json($gradeSchedule);
    var subtituteTeacher        = @json($subtituteTeacher);
    var startOfWeek             = @json($startOfWeek);
    var endSemester             = @json($endSemester);
    var startSemester           = @json($startSemester);
    var assistSchedule          = @json($assistSchedule);
    var assistSubtituteTeacher  = @json($assistSubtituteTeacher);

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek',
            height: 'auto', // Menyesuaikan tinggi kalender dengan event yang ada
            contentHeight: 'auto',
            expandRows: true, // Memastikan baris otomatis berkembang sesuai jumlah event
            headerToolbar: {
                left: 'title',
                center: 'prev,next',
                right: ''
            },
            bootstrapFontAwesome: true,
            dayHeaderFormat: { weekday: 'long' },
            slotLabelFormat: { hour: 'numeric', minute: '2-digit', omitZeroMinute: false, meridiem: 'short' },
            slotMinTime: '07:00:00',
            slotMaxTime: '15:00:00',
            hiddenDays: [0, 6],
        events: [
            ...gradeSchedule.map(schedule => ({
                title: `${schedule.grade_name} (${schedule.subject_name})`,
                startRecur: startSemester,
                endRecur: endSemester,
                daysOfWeek: [schedule.day],
                startTime: schedule.start_time,
                endTime: schedule.end_time,
                description: `<br>Teacher :  ${schedule.teacher_name} <br>Assisstant : ${schedule.teacher_companion !== null ? schedule.teacher_companion : ""}`,
                color: 'blue',
                grade_id: schedule.grade_id,
                subject_id: schedule.subject_id,
            })),
            ...subtituteTeacher.map(subs => ({
                title: `${subs.grade_name} (${subs.subject_name})`,
                start: `${subs.date}T${subs.start_time}`,
                end: `${subs.date}T${subs.end_time}`,  
                description: `<br>Teacher: ${subs.teacher_name} <span class='badge badge-danger'>substitute</span><br>Assisstant : ${subs.teacher_companion !== null ? subs.teacher_companion : ""}`,
                color: 'red',
                grade_id: subs.grade_id,
                subject_id: subs.subject_id,
            })),
            ...assistSchedule.map(assist => ({
                title: `${assist.grade_name} (${assist.subject_name})`,
                startRecur: startSemester,
                endRecur: endSemester,
                daysOfWeek: [assist.day],
                startTime: assist.start_time,
                endTime: assist.end_time,
                description: `<br>Teacher : ${assist.teacher_name} <br>Assisstant :  <span class="badge badge-primary"> ${assist.teacher_companion} </span> <br>Grade : ${assist.grade_name}`,
                color: 'gray',
                grade_id: assist.grade_id,
                subject_id: assist.subject_id,
            })),
            ...assistSubtituteTeacher.map(subs => ({
                title: `${subs.grade_name} (${subs.subject_name})`,
                start: `${subs.date}T${subs.start_time}`,
                end: `${subs.date}T${subs.end_time}`,  
                description: `<br>Teacher: ${subs.teacher_name}<br>Assisstant : ${subs.teacher_companion} <span class='badge badge-danger'>substitute</span><br>Grade: ${subs.grade_name}`,
                color: 'lime',
                grade_id: subs.grade_id,
                subject_id: subs.subject_id,
            })),

        ],
        eventClick: function(info) {
            document.getElementById('eventTitle').innerText = info.event.title;
            document.getElementById('eventDescription').innerHTML = info.event.extendedProps.description;
            
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
            title: 'Successfully subtitute teacher schedule in the database.',
        });
   </script>
@endif

@endsection
