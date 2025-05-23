@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    @if (!empty($data))
        <div class="card card-dark mt-2">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Your Invigilater Schedule</h5>

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
    var midExam         = @json($midExam);
    var finalExam       = @json($finalExam);
    var startOfWeek     = @json($startOfWeek);
    var endSemester     = @json($endSemester);
    var startSemester   = @json($startSemester);

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
            ...midExam.map(midExam => ({
                title: `Mid Exam: ${midExam.grade_name} (${midExam.subject_name}) Room: ${midExam.note !== null ? midExam.note : ""}`,
                startRecur: midExam.date,
                endRecur: midExam.end_date,
                daysOfWeek: [midExam.day],
                startTime: midExam.start_time,
                endTime: midExam.end_time,
                description: `<br>Teacher : <span class="badge badge-primary"> ${midExam.teacher_name} </span> <br>Grade : ${midExam.grade_name} <br>Room : ${midExam.note !== null ? midExam.note : ""}`,
                color: 'brown',
                grade_id: midExam.grade_id,
                subject_id: midExam.subject_id,
            })),
            ...finalExam.map(finalExam => ({
                title: `Final Exam: ${finalExam.grade_name} (${finalExam.subject_name}) Room: ${finalExam.note !== null ? finalExam.note : ""}`,
                startRecur: finalExam.date,
                endRecur: finalExam.end_date,
                daysOfWeek: [finalExam.day],
                startTime: finalExam.start_time,
                endTime: finalExam.end_time,
                description: `<br>Teacher : <span class="badge badge-primary"> ${finalExam.teacher_name} </span> <br>Grade : ${finalExam.grade_name} <br>Room : ${finalExam.note !== null ? finalExam.note : ""}`,
                color: 'red',
                grade_id: finalExam.grade_id,
                subject_id: finalExam.subject_id,
            })),
        ],
        eventClick: function(info) {
            document.getElementById('eventTitle').innerText = info.event.title;
            document.getElementById('eventDescription').innerHTML = 'Description : ' + info.event.extendedProps.description;
            
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
});
</script>

@endsection
