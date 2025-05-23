@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
      <div class="row">
         <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-3">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/attendance/class/teacher')}}">Attendance</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Student Attend</li>
               </ol>
            </nav>
         </div>
      </div>
      <div class="card card-dark mt-2">
         <div class="card-header">
            <h3 class="card-title">{{ $data['nameGrade'] }} / {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h3>
            <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
            </div>
         </div>
         <div class="card-body ">
            <div id="calendar">
            </div>
         </div>
      </div>
   <!-- END TABLE -->
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
   document.addEventListener('DOMContentLoaded', function() {
         var calendarEl = document.getElementById('calendar');

         // Data dari server
         var eventsData = @json($calendarData);
         var startSemester = new Date(@json($startSemester));  // Awal semester
         var today = new Date();  // Hari Ini
         var endSemester = new Date(@json($endSemester));  // Akhir Semester
         // Ambil list event yang sudah ada (misalnya Done)
         var eventDates = eventsData.map(function(event) {
            return event.start;
         });

         // Loop melalui rentang semester untuk menambahkan "Not Yet" jika tidak ada "Done"
         var currentDate = new Date(startSemester); // Mulai dari awal semester
         while (currentDate <= today) {
            var formattedDate = currentDate.toISOString().split('T')[0]; // Format ke yyyy-mm-dd

            // Jika tanggal belum ada di eventDates, tambahkan "Not Yet"
            if (!eventDates.includes(formattedDate)) {
               eventsData.push({
                     title: 'Not Yet',
                     start: formattedDate,
                     color: 'red',
                     // Tidak mengatur URL di sini, karena kita akan mengatur saat klik
               });
            }

            // Pindahkan ke hari berikutnya
            currentDate.setDate(currentDate.getDate() + 1);
         }

         // Inisialisasi FullCalendar
         var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            selectable: true,
            events: eventsData,  // Masukkan data event yang sudah di-update
            // Batasi kalender hanya bisa menampilkan hingga akhir semester
            validRange: {
               start: startSemester.toISOString().split('T')[0], // Format ke yyyy-mm-dd
               end: endSemester.toISOString().split('T')[0], // Format ke yyyy-mm-dd
            },
            hiddenDays: [0, 6],
            eventClick: function(info) {
               // Ambil tanggal yang diklik
               var clickedDate = info.event.start;

               // Tambahkan 1 hari
               clickedDate.setDate(clickedDate.getDate() + 1);

               // Format tanggal ke yyyy-mm-dd
               var formattedClickedDate = clickedDate.toISOString().split('T')[0];

               var role = '{{ session('role') }}';  // Ambil role dari session
               var teacherId = '{{ $data['teacherId'] }}';  // Ambil ID user dari session
               var gradeId = '{{ $gradeId }}';  // Ambil grade ID dari variabel PHP
               var semester = '{{ session('semester') }}';
               var userId = '{{ session('id_user') }}'; 
               // Buat URL dengan tanggal yang diklik + 1 hari
               var urlDone = "{{ url('/' . session('role') . '/dashboard/attendance/edit/detail') }}/" + formattedClickedDate + "/" + gradeId + "/" + teacherId + "/" + semester; // untuk status done
               var urlNotYet = "{{ url('/' . session('role') . '/dashboard/attendance/') }}/" + userId + "/" + gradeId + "/" + formattedClickedDate; // untuk status done

               // Arahkan ke URL tersebut
               if (info.event.title === 'Done') {
                window.location.href = urlDone;
               } else if (info.event.title === 'Not Yet') {
                  window.location.href = urlNotYet;
               }
         }
      });

      calendar.render();
   });
</script>


@if(session('failed_attend')) 
   <script>
         Swal.fire({
            icon: 'error',
            title: 'Oops..',
            text: 'Attendance already recorded for this day.',
      });
   </script>
@endif

@if(session('success_attend')) 
   <script> 
      Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully Attend Student',
      });
   </script>
@endif

@endsection
