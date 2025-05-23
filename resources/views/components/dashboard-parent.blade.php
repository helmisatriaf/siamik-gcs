@extends('layouts.admin.master')
@section('content')

  <style>
      .bg-gradient-warning {
          background: linear-gradient(45deg, #ffc107, #ffdb4d);
      }

      .badge-soft-success {
          color: #0f5132;
          background-color: #d1e7dd;
      }

      .badge-soft-danger {
          color: #842029;
          background-color: #f8d7da;
      }

      .waves-effect {
          position: relative;
          overflow: hidden;
          transform: translate3d(0, 0, 0);
      }

      .fa-shake {
          animation: fa-shake 2.5s infinite linear;
      }

      @keyframes fa-shake {
          0% {
              transform: rotate(0deg);
          }

          4% {
              transform: rotate(-10deg);
          }

          8% {
              transform: rotate(10deg);
          }

          12% {
              transform: rotate(-10deg);
          }

          16% {
              transform: rotate(10deg);
          }

          20% {
              transform: rotate(0deg);
          }

          100% {
              transform: rotate(0deg);
          }
      }

      .animate__fadeIn {
          animation: fadeIn 0.5s ease-in;
      }

      @keyframes fadeIn {
          from {
              opacity: 0;
              transform: translateY(-20px);
          }

          to {
              opacity: 1;
              transform: translateY(0);
          }
      }
  </style>

<section class="content">
  <div class="container-fluid">
    {{-- <div class="card p-2">
      <div class="row">
        <div class="col-6">
          <label class="text-sm">Academic Year :</label>
          <select name="choose_academic_year" class="form-control text-sm text-black" id="cay" onchange="setAcademicYear()">
            @foreach ($data['academicYears'] as $year)
              <option value="{{$year}}" {{session('academicYear') == $year ? 'selected' : ''}}>{{$year}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6">
          <label class="text-sm">Semester :</label>
          <select name="choose_semester" class="form-control text-sm text-black" id="cs" onchange="setSemester()">
            <option value="1" {{session('semester') == 1 ? 'selected' : ''}}>Semester 1</option>
            <option value="2"  {{session('semester') == 2 ? 'selected' : ''}}>Semester 2</option>
          </select>
        </div>
      </div>
    </div> --}}

    <div class="row">
      <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
              <div class="inner">
                  <h3>{{ $data['totalAbsent'] }}</h3>
                  <p>Total Absence</p>
              </div>
              <div class="icon">
                  <i class="fa-solid fa-chalkboard-user"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i
                      class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div>

      <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
              <div class="inner">
                  <h3>{{ $data['totalLate'] }}</h3>
                  <p>Total Late</p>
              </div>
              <div class="icon">
                  <i class="fa-solid fa-chalkboard-user"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i
                      class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div>

      <!-- SUBJECT ACTIVE -->
      <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
              <div class="inner">
                  <h3>{{ $data['totalSubject'] }}
                      {{-- <sup style="font-size: 20px">%</sup> --}}
                  </h3>

                  <p>Total Active Courses</p>
              </div>
              <div class="icon">
                  {{-- <i class="ion ion-person-add"></i> --}}
                  <i class="fa-solid fa-book"></i>
              </div>
              <a href="course" class="small-box-footer">More info <i
                      class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div>
      <!-- ./col -->

      <!-- EXAM ACTIVE -->
      <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
              <div class="inner">
                  <h3>{{ $data['totalExam'] }}</h3>

                  <p>Total Assessments</p>
              </div>
              <div class="icon">
                  <i class="fa-solid fa-book-open-reader"></i>
              </div>

              <a href="/parent/dashboard/exam" class="small-box-footer">More info <i
                      class="fas fa-arrow-circle-right"></i></a>

          </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="card bg-info">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fa-solid fa-person mr-1"></i>
              Relationship Details
            </h3>
          </div>
  
          <div class="pt-3 px-4 px-md-3">
            <select required name="studentId" class="form-control" id="studentId" onchange="saveStudentId()">
              <option value="">-- Your Relation -- </option>
              @foreach ($data['totalRelation'] as $dtr)
                <option value="{{ $dtr->student_id }}" {{ session('studentId') == $dtr->student_id ? "selected" : "" }}> {{ ucwords(strtolower($dtr->student_name)) }}</option>
              @endforeach
            </select>
          </div>
  
          <div class="card-body">
            <!-- Widget: user widget style 2 -->
            <div class="card card-widget widget-user-2 shadow-xl">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-info">
                <div class="widget-user-image">
                    <img class="img-circle elevation-4" src="{{asset('storage/file/profile/'.$data['detailStudent']->profile)}}" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{ ucwords(strtolower($data['detailStudent']->student_name)) }}</h3>
                <h5 class="widget-user-desc">{{ $data['detailStudent']->grade_name }} - {{$data['detailStudent']->grade_class}}</h5>
              </div>
              <div class="card-footer p-0">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Total Assessments <span class="float-right badge bg-primary">{{$data['totalExam']}}</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Assessments Completed <span class="float-right badge bg-info">{{$data['examCompleted']}}</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      Assessments In Process <span class="float-right badge bg-info">{{$data['examProcess']}}</span>
                    </a>
                  </li>
                  @foreach($data['eca'] as $de)
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      ECA {{ $loop->index+1 }}<span class="float-right badge bg-success">{{ $de->eca_name }}</span>
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <!-- /.widget-user -->
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div class="widget-user-header bg-info">
            <h3 class="widget-user-username text-lg">{{ucwords(strtolower(session('name_user')))}}</pp>
            <h5 class="widget-user-desc text-md">{{ucwords(strtolower($data['parent']->relation))}}</h5>
          </div>
          <div class="widget-user-image">
            <img class="img-circle elevation-1" src="{{asset('images/admin.png')}}" alt="User Avatar">
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-12">
                <div class="description-block">
                  <h5 class="description-header">Children</h5>
                  <div class="text-start">
                    @foreach ($data['totalRelation'] as $dtr)
                      <li class="">{{ucwords(strtolower($dtr->student_name))}} ({{$dtr->grade_name}} - {{$dtr->grade_class}})</li>
                    @endforeach
                  </div>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fa-solid fa-book"></i>
              Courses
            </h3>
          </div>
          
          <div class="card-body" style="position: relative; max-height: 305px; overflow-y: auto;">
            <table class="table table-borderless">      
                @if(sizeof($data['dataStudent']->subject) != 0)
                  <thead>
                    <tr>
                      <th style="width: 10%;">#</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data['dataStudent']->subject as $el)
                      <tr>
                        <td scope="row">{{$loop->index+1}}</td>
                        <td>
                          <a 
                            id="set-course-id"
                            data-id="{{ $el->id }}"
                            href="javascript:void(0)"
                            class="text-decoration-none text-dark"
                          >
                            {{$el->name_subject}}
                          </a>
                        </td>
                      </tr>
                    @endforeach  
                @else
                  <p>Subject Grade is empty !!!</p>
                @endif
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

            <div class="row">
                <div class="col-md-12">
                    @if (isset($data['paymentStatus']) && $data['paymentStatus']['has_unpaid_bill'])
                        <div class="card bg-gradient-warning animate__animated animate__fadeIn">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                    <i class="fas fa-bell fa-shake mr-2"></i>
                                    Payment Notification
                                </h3>
                            </div>
                            <div class="card-body">
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-white rounded-lg p-4 shadow-sm">
                                    <div class="d-flex align-items-center mb-3 mb-md-0">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-warning fa-2x"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="font-weight-bold text-dark mb-1">
                                                {{ $data['paymentStatus']['message'] }}
                                            </h5>
                                            <p class="text-muted mb-0">
                                                Please make the payment as soon as possible to avoid late fees.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-center text-md-right">
                                        <div class="mb-2">
                                            <span class="text-muted">Total Bill:</span>
                                            <h4 class="text-danger font-weight-bold mb-0">
                                                Rp {{ number_format($data['paymentStatus']['amount'], 0, ',', '.') }}
                                            </h4>
                                        </div>
                                        {{-- <a href="#"
                                            class="btn btn-sm btn-danger btn-lg px-5 waves-effect waves-light">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            Pay Now
                                        </a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment History Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-2"></i>
                                Payment History 
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Payment Date</th>
                                            <th>Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payment-history-body">
                                        @foreach ($data['paymentHistory'] ?? [] as $payment)
                                            <tr>
                                                <td>{{ $payment['month'] }}</td>
                                                <td>{{ $payment['year'] }}</td>
                                                <td>Rp {{ number_format($payment['amount'], 0, ',', '.') }}</td>
                                                <td>
                                                    @if ($payment['status'] === 'Lunas')
                                                        <span class="badge badge-soft-success px-3 py-2">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Paid
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger px-3 py-2">
                                                            <i class="fas fa-times-circle mr-1"></i>
                                                            Unpaid
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $payment['payment_date'] ?? '-' }}</td>
                                                <td>{{ $payment['due_date'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



    <div class="row">
        <section class="col-12 connectedSortable">
            <div class="card bg-danger">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa-solid fa-calendar-xmark mr-1"></i>
                        Assessments
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="revenue-chart"
                            style="position: relative; max-height: 500px; overflow-y: auto;">

            @if(sizeof($data['exam']) == 0)
            <div class="d-flex justify-content-center">
              <p>There is no assessment</p>
            </div>
            @else

            {{-- <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas> --}}
            <div>
              <!-- /.card-header -->
              <div>
                <ul class="todo-list bg-danger" data-widget="todo-list" >

                  @php
                    $currentDate = date('y-m-d');
                  @endphp 

                  @foreach ($data['exam'] as $el)
                    <li id="view" data-id="{{ $el->id }}" class="hover:cursor-pointer">
                      <span class="handle" class="hover:cursor-pointer">
                          <i class="fas fa-ellipsis-v"></i>
                          <i class="fas fa-ellipsis-v"></i>
                      </span>
                      <!-- checkbox -->
                      <div class="icheck-primary d-inline ml-2" class="hover:cursor-pointer">
                          <span class="text-muted">[{{ date('d F Y', strtotime($el->date_exam)) }}]</span>
                      </div>
                      <!-- todo text -->
                      <span class="text text-sm" class="hover:cursor-pointer">( {{$el->type_exam_name}} ) ({{ $el->subject }}) {{$el->name_exam}} </span>
                      
                      <span class="hover:cursor-pointer">
                        @if ($el->is_active)
                          @php
                          $currentDate = now(); // Tanggal saat ini
                          $dateExam = $el->date_exam; // Tanggal ujian dari data

                                      // Buat objek DateTime dari tanggal saat ini dan tanggal ujian
                                  $currentDateTime = new DateTime($currentDate);
                                  $dateExamDateTime = new DateTime($dateExam);

                                  // Hitung selisih antara kedua tanggal
                                  $interval = $currentDateTime->diff(
                                      $dateExamDateTime,
                                  );

                                  // Ambil jumlah hari dari selisih tersebut
                                  $days = $interval->days;

                                  // Jika tanggal ujian lebih kecil dari tanggal saat ini, buat selisih menjadi negatif
                                  if ($dateExamDateTime < $currentDateTime) {
                                      $days = 'Past Deadline';
                                  } elseif (
                                      $dateExamDateTime > $currentDateTime &&
                                      $days == 0
                                  ) {
                                      // Jika tanggal ujian di masa depan dan selisih kurang dari 1 hari, anggap 1 hari
                                      $days = 1;
                                  }
                              @endphp

                              @if ($days == 'Past Deadline')
                                  <span class="badge badge-warning">Today</span>
                              @else
                                  <span
                                      class="badge badge-warning">{{ $days }}
                                      days again</span>
                              @endif
                          @else
                              <span class="badge badge-success">Done</span>
                          @endif
                      </span>

                      <div class="tools">
                        <i class="fas fa-search hover:cursor-pointer"></i>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>

            @endif
        </div>
    </div>

  </div>
</section>

<!-- Modal Loading -->
<div id="loadingModal" class="modal" tabindex="-1" role="dialog" style="display: none;">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content text-center">
          <div class="modal-body p-4">
              <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden"></span>
              </div>
              <p class="mt-3">Processing, please wait...</p>
          </div>
      </div>
  </div>
</div>



<link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
  function saveStudentId() {
    var studentIdSelect = document.getElementById('studentId');
    var selectedStudentId = studentIdSelect.value;

    // Simpan nilai semester ke dalam session
    $.ajax({
        url: '{{ route('save.student.session') }}',
        type: 'POST',
        data: {
            studentId: selectedStudentId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            window.location.href = '/parent/dashboard/';
        },
        error: function(xhr, status, error) {
            console.error('Error saving semester to session:', error);
        }
    });
  }

// function showLoading() {
//     document.getElementById('loadingModal').style.display = 'block';
// }

// function hideLoading() {
//     document.getElementById('loadingModal').style.display = 'none';
// }

// function setSemester() {
//     var semesterIdSelect = document.getElementById('cs');
//     var selectedSemesterId = semesterIdSelect.value;

//     showLoading(); // Tampilkan modal loading sebelum fetch

//     fetch('/save-semester-session', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
//         },
//         body: JSON.stringify({
//             semester: selectedSemesterId,
//         })
//     })
//     .then(response => response.json())
//     .then(data => {
//         hideLoading(); // Sembunyikan modal setelah selesai
//         if (data.success) {
//             location.reload();
//         } else {
//             alert('Failed to set semester. Please try again.');
//         }
//     })
//     .catch(error => {
//         hideLoading();
//         console.error('Error:', error);
//     });
// }

// function setAcademicYear() {
//     var yearIdSelect = document.getElementById('cay');
//     var selectedYear = yearIdSelect.value;

//     showLoading(); // Tampilkan modal loading sebelum fetch

//     fetch('/save-academicyear-session', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
//         },
//         body: JSON.stringify({
//             year: selectedYear,
//         })
//     })
//     .then(response => response.json())
//     .then(data => {
//         hideLoading(); // Sembunyikan modal setelah selesai
//         if (data.success) {
//             location.reload();
//         } else {
//             alert('Failed to change academic year. Please try again.');
//         }
//     })
//     .catch(error => {
//         hideLoading();
//         console.error('Error:', error);
//     });
// }


  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#view').forEach(function(button) {
        button.addEventListener('click', function() {
              var assessmentId = this.getAttribute('data-id');
              var sessionRole = @json(session('role'));
              var url;
              if (sessionRole === "parent") {
                  url = "{{ route('set.assessment.id') }}";
              } else if (sessionRole === "student") {
                  url = "{{ route('set.assessment.id.student') }}";
              }
              
              $.ajax({
                url: url,
                method: 'POST',
                data: {
                    id: assessmentId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                          window.location.href = '/' + sessionRole + '/dashboard/exam/detail';
                    } else {
                          alert('Failed to set exam ID in session.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
              });
        });
    });

    document.querySelectorAll('#set-course-id').forEach(function(button) {
      button.addEventListener('click', function() {
          var courseId = this.getAttribute('data-id'); // Ambil ID dari data-id
          var sessionRole = @json(session('role'));
          var url;

          if (sessionRole === "parent") {
              url = "{{ route('set.course.id.parent') }}";
          } else if (sessionRole === "student") {
              url = "{{ route('set.course.id.student') }}";
          }

          $.ajax({
              url: url,
              method: 'POST',
              data: {
                  id: courseId, // Ganti assessmentId dengan courseId
                  _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                  if (response.success) {
                      window.location.href = '/' + sessionRole + '/course' +
                          '/detail';
                  } else {
                      alert('Failed to set course ID in session.');
                  }
              },
              error: function(xhr, status, error) {
                  alert('Error: ' + error);
              }
          });
      });
  });
  });
</script>

@if(session('report_doesnt_exists')) 
    <script>
      Swal.fire({
          icon: 'error',
          title: `Report Semester {{ session('semester') }} Doesn't Exist`,
          timer: 2000,
          timerProgressBar: true
      });
    </script>
@endif

@if(session('midreport_doesnt_exists')) 
    <script>
      Swal.fire({
          icon: 'error',
          title: `Mid Report Semester {{ session('semester') }} Doesn't Exist`,
          timer: 2000,
          timerProgressBar: true
      });
    </script>
@endif

@if(session('password.success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfuly',
            text: 'Success change password',
        });
    </script>
@endif

@endsection
