@extends('layouts.admin.master')
@section('content')

<style>
   .full-height {
      height: 60vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
   }
   .icon-wrapper i {
      font-size: 200px;
      color: #ccc;
   }
   .icon-wrapper p {
      position: absolute;
      left: 50%;
      transform: translate(-50%, 0%);
      margin: 0;
      font-size: 1.5rem;
      color: black;
      text-align: center;
   }
</style>

<!-- Content Wrapper. Contains page content -->
@if (!empty($data))
    <div class="container-fluid text-center">
        @if ($totalClass > 1)
            @foreach ($data as $dt)
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12">
                    <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;background-color: #ffde9e;border-radius: 12px;">
                        <a class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center"
                            href="{{url('teacher/dashboard/schedules/gradeOther') . '/' . $dt->grade_id}}">
                            <!-- Ribbon -->
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon bg-dark text-sm">
                                   Schedule
                                </div>
                            </div>
                        
                            <!-- Bagian Utama -->
                            <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                <!-- Ikon -->
                                <div>
                                    <img loading="lazy" src="{{ asset('images/schedules.png')}}"  
                                     alt="avatar" class="profileImage img-fluid" 
                                     style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                                </div>
                                <!-- Nama Subject -->
                                <div class="inner mt-2">
                                    <p class="mb-0 text-lg fw-bold text-center text-dark"> {{ $dt->grade_name }} - {{ $dt->grade_class }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <h5 class="text-bold text-xl">{{ $data['grade_name'] }} - {{ $data['grade_class'] }}</h5>                
            <div id="calendar"></div>
        @endif
    </div>
@else
    <div class="container-fluid full-height">
        <div class="icon-wrapper">
            <i class="fa-regular fa-face-laugh-wink"></i>
            <p>Oops.. <br> This page can only be accessed by class teachers</p>
        </div>
    </div>
@endif

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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>    
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if ($totalClass === 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                            'color' => 'transparant'
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
                                $event['description'] = "Teacher: {$schedule->teacher_name}<br>Assisstant: {$schedule->teacher_companion}<br>Grade: {$schedule->grade_name} - {$schedule->grade_class}";
                        }

                        echo json_encode($event) . ',';

                        foreach ($subtituteTeacher as $st) {
                            if ($st->grade_id == $schedule->grade_id && $st->subject_id == $schedule->subject_id && $st->day == $schedule->day && $st->start_time == $schedule->start_time && $st->end_time == $schedule->end_time) {
                                $substituteEvent = [
                                    'title' => $schedule->subject_name,
                                    'start' => "{$st->date}T{$schedule->start_time}",
                                    'end' => "{$st->date}T{$schedule->end_time}",
                                    'description' => "<br>Teacher: {$st->teacher_name} <span class='badge badge-danger'>substitute</span> <br>Grade: {$schedule->grade_name} - {$schedule->grade_class}",
                                    'color' => 'green'
                                ];
                                echo json_encode($substituteEvent) . ',';
                            }
                        }
                    @endphp
                    @endforeach
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
        });

    </script>
@endif

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
