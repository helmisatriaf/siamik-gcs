@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-3">
                <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/schedules/midexams')}}">Mid Exam Schedule</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Schedule Mid Exam {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (session('role') == 'superadmin' || session('role') == 'admin')
    <a class="btn btn-success btn"
        href="{{url('/' . session('role') .'/schedules/midexam/create') . '/' . $data['grade_id']}}">
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
<div class="modal fade " id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Schedule Mid Exam Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="eventTitle"></p>
                <p id="eventDescription"></p>
                <div id="substituteTeacherBtnContainer" class="mb-2"></div>
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

    @php
        // Calculate the overall min and max dates for validRange
        $minDate = null;
        $maxDate = null;
        foreach($gradeSchedule as $schedule) {
            $endDate = date('Y-m-d', strtotime($schedule->end_date . ' +1 day'));
            if ($minDate === null || $schedule->date < $minDate) {
                $minDate = $schedule->date;
            }
            if ($maxDate === null || $endDate > $maxDate) {
                $maxDate = $endDate;
            }
        }
    @endphp

    var calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: '',
            center: 'title',
            right: ''
        },
        dayHeaderFormat: { weekday: 'short' },
        slotLabelFormat: { hour: 'numeric', minute: '2-digit', omitZeroMinute: false, meridiem: 'short' },
        slotMinTime: '07:00:00',
        slotMaxTime: '18:00:00',
        hiddenDays: [0, 6],
        validRange: {
            start: '{{ $minDate }}',
            end: '{{ $maxDate }}'
        },
        events: [
            @foreach($gradeSchedule as $schedule)
            @php
                $endDate = date('Y-m-d', strtotime($schedule->end_date . ' +1 day'));

                $event = [
                    'title' => "{$schedule->subject_name} ({$schedule->teacher_name})" . ($schedule->note ? " {$schedule->note}" : ''),
                    'startRecur' => $schedule->date,
                    'endRecur' => $endDate,
                    'daysOfWeek' => [$schedule->day],
                    'startTime' => $schedule->start_time,
                    'endTime' => $schedule->end_time,
                    'color' => $schedule->color,
                    'teacherId' => $schedule->teacher_id,
                    'day' => $schedule->day,
                    'description' => "<br>Invigilater : {$schedule->teacher_name}<br>Grade : {$schedule->grade_name} - {$schedule->grade_class}<br>Note :" . ($schedule->note ? " {$schedule->note}" : '')
                ];

                echo json_encode($event) . ',';
            @endphp
            @endforeach
        ],
        eventClick: function(info) {
            document.getElementById('eventTitle').innerText = info.event.title;
            document.getElementById('eventDescription').innerHTML = 'Description Mid Exam :' + info.event.extendedProps.description;

            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                keyboard: false
            });
            eventModal.show();
        }
    });
    calendar.render();
});

</script>


@if(session('after_create_midexam_schedule')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully added mid exam schedule in the database.',
        });
    </script>
@endif

@endsection
