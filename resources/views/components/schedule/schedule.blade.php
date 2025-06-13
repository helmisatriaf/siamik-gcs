@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    {{-- <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Schedules</li>
                    <li class="breadcrumb-item active" aria-current="page">School</li>
                </ol>
            </nav>
        </div>
    </div> --}}

    @if (session('role') == 'superadmin' || session('role') == 'admin')
    <a data-toggle="modal" data-target="#modalAddOtherSchedule" class="btn btn-primary btn">   
        <i class="fa-solid fa-calendar-plus"></i>
        </i>   
        Add Schedule
    </a>
    <a href="{{url('/' . session('role') .'/schedules/schools/manage/otherSchedule') }}" class="btn btn-warning btn">   
        <i class="fa-solid fa-pencil"></i>
        </i>   
        Manage
    </a>
    @endif

    <div id="calendar" style="overflow: hidden;border-radius:16px;"></div>
    <div id="schedule-list" class="mt-3">
        <h5 id="schedule-month"></h5>
        <ul id="schedule-items" class="list-unstyled"></ul>
    </div>
    {{-- <div class="card card-warning mt-2" style="border-radius: 12px;">
        <div class="card-header header-elements-inline">
            <h5 class="card-title text-bold">School Calendar</h5>
    
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
    
        <div class="card-body">
           
            
        </div>
    </div>     --}}
</div>

<!-- Modal Detail-->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="eventTitle"></p>
                <p id="eventDescription"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Other Schedule -->
<div class="modal fade" id="modalAddOtherSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom-modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Other Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperCreateOtherSchedule')}}>
                    @elseif (session('role') == 'admin')
                        <form method="POST" action={{route('actionAdminCreateOtherSchedule')}}>
                    @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-body" style="position: relative; max-height: 500px; overflow-y: auto;">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>Type Schedule</th>
                                        <th>Date</th>
                                        <th>Until</th>
                                        <th>Notes</th>
                                    </thead>
                                    <tbody id="scheduleTableBody">
                                        <tr>
                                            <td>
                                                <select required name="type_schedule[]" class="form-control" id="type_schedule">
                                                    <option value="">-- TYPE SCHEDULE --</option>
                                                    @foreach($data as $el)
                                                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input name="date[]" type="date" class="form-control" id="date" required>
                                            </td>
                                            <td>
                                                <input name="end_date[]" type="date" class="form-control" id="_end_date">
                                            </td>
                                            <td>
                                                <textarea required name="notes[]" class="form-control" id="notes" cols="10" rows="1"></textarea>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan CSS untuk modal khusus -->
<style>
.custom-modal-dialog {
    max-width: 80%; /* Atur persentase sesuai kebutuhan Anda */
    width: auto !important; /* Untuk memastikan lebar otomatis */
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

<link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var exams      = @json($exams);
        var schedules  = @json($schedules);
        var calendarEl = document.getElementById('calendar');

        if(window.innerWidth < 768){
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listMonth',
                height: 'auto', // Menyesuaikan tinggi kalender dengan event yang ada
                contentHeight: 'auto',
                expandRows: true, // Memastikan baris otomatis berkembang sesuai jumlah event
                headerToolbar: {
                    start: 'title', // will normally be on the left. if RTL, will be on the right
                    center: '',
                    end: 'prev,next' // will normally be on the right. if RTL, will be on the left
                    
                },
                events: [
                    ...exams.map(exam => ({
                        title: `${exam.type_exam} - (${exam.subject_name})`,
                        start: exam.date_exam,
                        description: `<br>${exam.name_exam} <br> ${exam.grade_name} - ${exam.grade_class} <br>Deadline : `,
                        color: exam.is_active ? 'green' : 'red',
                        jadwal: new Date(exam.date_exam).toLocaleDateString('en-EN', { month: 'long', day: 'numeric', year: 'numeric' }),
                        sampai: exam.end_date ? new Date(exam.end_date).toLocaleDateString('en-EN', { month: 'long', day: 'numeric', year: 'numeric' }) : null,
                    })),
                    ...schedules.map(schedule => {
                        let endDate = schedule.end_date ? new Date(schedule.end_date) : null;
                        if (endDate) {
                            endDate.setDate(endDate.getDate() + 1); // Menambahkan satu hari
                        }
                        return {
                            title: schedule.note,
                            start: schedule.date,
                            end: endDate ? endDate.toISOString().split('T')[0] : null,
                            description: schedule.note,
                            color: schedule.color,
                            jadwal: new Date(schedule.date).toLocaleDateString('en-EN', { month: 'long', day: 'numeric', year: 'numeric' }),
                            sampai: schedule.end_date ? new Date(schedule.end_date).toLocaleDateString('en-EN', { month: 'long', day: 'numeric', year: 'numeric' }) : null,
                        };
                    }),
                    
                ],
                eventClick: function(info) {
                    document.getElementById('eventTitle').innerText = 'Event: ' + info.event.title;
                    if (info.event.extendedProps.sampai === null) {
                        document.getElementById('eventDescription').innerHTML = 'Description: ' + info.event.extendedProps.description + ' (' + info.event.extendedProps.jadwal + ')';
                    }
                    else {
                        document.getElementById('eventDescription').innerHTML = 'Description: ' + info.event.extendedProps.description + ' (' + info.event.extendedProps.jadwal + ' until ' + info.event.extendedProps.sampai + ')';
                    }
                    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                        keyboard: false
                    });
                    eventModal.show();
                }
            });
        }
        else{
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                aspectRatio: 1.35, // Mengatur rasio aspek agar kalender tetap terlihat bagus
                windowResize: function(view) {
                    if (window.innerWidth < 768) {
                        calendar.changeView('listMonth'); // Ubah ke tampilan list jika di layar kecil
                    } else {
                        calendar.changeView('dayGridMonth'); // Kembalikan ke tampilan grid untuk layar besar
                    }
                },
                events: [
                    ...exams.map(exam => ({
                        title: `${exam.type_exam} - (${exam.subject_name})`,
                        start: exam.date_exam,
                        description: `<br>${exam.name_exam} <br> ${exam.grade_name} - ${exam.grade_class} <br>Deadline : `,
                        color: exam.is_active ? 'green' : 'red',
                        backgroundColor: exam.is_active ? 'green' : 'red',
                        jadwal: new Date(exam.date_exam).toLocaleDateString('en-EN', { month: 'long', day: 'numeric', year: 'numeric' }),
                        sampai: exam.end_date ? new Date(exam.end_date).toLocaleDateString('en-EN', { month: 'long', day: 'numeric', year: 'numeric' }) : null,
                    })),
                    ...schedules.map(schedule => {
                        let endDate = schedule.end_date ? new Date(schedule.end_date) : null;
                        if (endDate) {
                            endDate.setDate(endDate.getDate() + 1); // Menambahkan satu hari
                        }
                        return {
                            title: schedule.note,
                            start: schedule.date,
                            end: endDate ? endDate.toISOString().split('T')[0] : null,
                            description: schedule.note,
                            color: schedule.color,
                            backgroundColor: schedule.color,
                            jadwal: new Date(schedule.date).toLocaleDateString('en-EN', { month: 'long', day: 'numeric', year: 'numeric' }),
                            sampai: schedule.end_date ? new Date(schedule.end_date).toLocaleDateString('en-EN', { month: 'long', day: 'numeric', year: 'numeric' }) : null
                        };
                    }),  
                ],
                eventClick: function(info) {
                    document.getElementById('eventTitle').innerText = 'Event: ' + info.event.title;
                    if (info.event.extendedProps.sampai === null) {
                        document.getElementById('eventDescription').innerHTML = 'Description: ' + info.event.extendedProps.description + ' (' + info.event.extendedProps.jadwal + ')';
                    }
                    else {
                        document.getElementById('eventDescription').innerHTML = 'Description: ' + info.event.extendedProps.description + ' (' + info.event.extendedProps.jadwal + ' until ' + info.event.extendedProps.sampai + ')';
                    }
                    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                        keyboard: false
                    });
                    eventModal.show();
                },
                dayCellDidMount: function(info) {
                    var day = info.date.getDay(); // 0 = Minggu, 6 = Sabtu
                    if (day === 0 || day === 6) {
                        info.el.style.backgroundColor = '#ffcccc'; // Warna merah muda untuk Sabtu & Minggu
                    }
                },
                datesSet: function(info) {
                    let currentDate = new Date(info.view.currentStart); // Awal bulan yang ditampilkan
                    let currentMonth = currentDate.getMonth(); // Ambil bulan yang benar
                    let currentYear = currentDate.getFullYear(); // Ambil tahun yang benar

                    // Perbarui judul daftar jadwal
                    document.getElementById('schedule-month').innerText = currentDate.toLocaleString('en-EN', { month: 'long' });

                    // Filter event berdasarkan bulan yang sedang ditampilkan
                    let filteredEvents = calendar.getEvents().filter(event => {
                        let eventDate = new Date(event.start);
                        return eventDate.getMonth() === currentMonth && eventDate.getFullYear() === currentYear;
                    });

                    let scheduleList = document.getElementById('schedule-items');
                    scheduleList.innerHTML = ""; // Bersihkan daftar sebelumnya

                    if (filteredEvents.length === 0) {
                        scheduleList.innerHTML = "<li>No events for this month.</li>";
                    } else {
                        filteredEvents.forEach(event => {
                            let eventDate = new Date(event.start).toLocaleDateString('en-EN', { day: '2-digit', month: 'long' });
                            let endDate = event.end_date ? new Date(event.end_date).toLocaleDateString('en-EN', { day: '2-digit', month: 'long'}) : null;

                            let listItem = document.createElement('li');

                            if (eventDate == endDate){
                                listItem.innerHTML = `${eventDate} : <strong>${event.title}</strong>`;
                            }
                            else{
                                listItem.innerHTML = `${eventDate}${endDate ? ' - ' + endDate : ''} : <strong>${event.title}</strong>`;
                            }
                        
                            scheduleList.appendChild(listItem);
                        });
                    }
                }
            });
        }
        calendar.render();
        
        function updateScheduleList(startDate) {
            const monthYear = startDate.toLocaleDateString('en-EN', { month: 'long' });
            document.getElementById('schedule-month').innerText = "Detail Event " + monthYear;
    
            const scheduleList = document.getElementById('schedule-items');
            scheduleList.innerHTML = ''; // Kosongkan list sebelum diperbarui
    
            const filteredSchedules = schedules
                .filter(schedule => {
                    const scheduleDate = new Date(schedule.date);
                    return scheduleDate.getMonth() === startDate.getMonth() && scheduleDate.getFullYear() === startDate.getFullYear();
                })
                .sort((a, b) => new Date(a.date) - new Date(b.date)); // Urutkan dari tanggal kecil ke besar
    
            if (filteredSchedules.length === 0) {
                scheduleList.innerHTML = '<li>Tidak ada jadwal bulan ini</li>';
            } else {
                filteredSchedules.forEach(schedule => {
                    let formattedDate = new Date(schedule.date).toLocaleDateString('en-EN', {
                        day: '2-digit', month: 'long'
                    });
                    let endDate = schedule.end_date ? new Date(schedule.end_date).toLocaleDateString('en-EN', { day: '2-digit', month: 'long' }) : null;
                    let listItem = document.createElement('li');

                    if (formattedDate == endDate){
                        listItem.innerHTML = `${formattedDate} : <strong>${schedule.note}</strong>`;
                    }
                    else {                    
                        listItem.innerHTML = `${formattedDate}${endDate ? ' - ' + endDate : ''} : <strong>${schedule.note}</strong>`;
                    }
                    scheduleList.appendChild(listItem);
                });
            }
        }
    
        updateScheduleList(calendar.view.currentStart);
    
        calendar.on('datesSet', function(info) {
            updateScheduleList(info.view.currentStart);
        });
    });
</script>

<script src="{{asset('template')}}/plugins/jquery/jquery.min.js"></script>

@if (session('after_create_exam'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new exam in the database.',
        });
    </script>
@endif

@endsection
