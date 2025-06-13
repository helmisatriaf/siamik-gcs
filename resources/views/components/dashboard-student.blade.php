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

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <!-- STUDENT ACTIVE -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info" style="border-radius: 12px;">
                        <div class="inner">
                            <h3>{{ $data['totalStudent'] }}</h3>

                            <p>Total Students</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <a href="#" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->

                <!-- GRADE ACTIVE -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success" style="border-radius: 12px;">
                        <div class="inner">
                            <h3>{{ $data['totalAbsent'] }}
                                {{-- <sup style="font-size: 20px">%</sup> --}}
                            </h3>

                            <p>Total Absence</p>
                        </div>
                    
                        <div class="icon">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <a href="#" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning" style="border-radius: 12px;">
                <div class="inner">
                    <h3>{{ $data['totalSubject'] }}
                        {{-- <sup style="font-size: 20px">%</sup> --}}
                    </h3>

                    <p>Total Courses</p>
                </div>
                <div class="icon">
                    {{-- <i class="ion ion-person-add"></i> --}}
                    <i class="fa-solid fa-book"></i>
                </div>

                <a href="/student/course/" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- ASSESSMENT ACTIVE -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger" style="border-radius: 12px;">
                <div class="inner">
                    <h3>{{ $data['totalExam'] }}</h3>

                    <p>Total Assessments</p>
                </div>
                <div class="icon">
                <i class="fa-solid fa-book-open-reader"></i>
                </div>
            
                <a href="/student/dashboard/exam" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
      </div>


        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">

                <!-- Custom tabs (Charts with tabs) List Exam-->
                <div class="card bg-danger" style="border-radius: 12px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-calendar-xmark mr-1"></i>
                            Assessments Ongoing
                        </h3>
                    </div>

                    <div class="card-body" style="position: relative; height: 500px; overflow-y: auto;">
                        <!-- Morris chart - Sales -->
                        <div class="chart tab-pane active" id="revenue-chart">

                            @if (sizeof($data['dataStudent']->exam) == 0)
                                <div class="d-flex justify-content-center">
                                    <h2>There is no assessment</h2>
                                </div>
                            @else
                                {{-- <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas> --}}
                                @if (sizeof($data['exam']) !== 0)
                                    <div>
                                        <div>
                                            <ul class="todo-list"  data-widget="todo-list">

                                                @php
                                                    $currentDate = date('y-m-d');
                                                @endphp

                                                @foreach ($data['exam'] as $el)
                                                    <li id="view" data-id="{{ $el->id }}"
                                                        class="hover:cursor-pointer"
                                                        style="background-color: #ffde9e;border: 2px dashed #ffcc00;border-radius: 8px;"
                                                        >
                                                        <span class="handle">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </span>
                                                        <!-- checkbox -->
                                                        <div class="icheck-primary d-inline ml-2">
                                                            <span
                                                                class="text-muted">[{{ date('d F Y', strtotime($el->date_exam)) }}]</span>
                                                        </div>
                                                        <!-- todo text -->
                                                        <span class="text text-sm">( {{ $el->type_exam_name }} )
                                                            ({{ $el->subject }})
                                                            {{ $el->name_exam }} </span>

                                                        <span>
                                                            @if ($el->is_active)
                                                            @php
                                                                $currentDate = now(); // Tanggal saat ini
                                                                $dateExam = $el->date_exam; // Tanggal ujian dari data

                                                                // Buat objek DateTime dari tanggal saat ini dan tanggal ujian
                                                                $currentDateTime = new DateTime($currentDate);
                                                                $dateExamDateTime = new DateTime($dateExam);

                                                                $currentDateOnly = $currentDateTime->format('Y-m-d');
                                                                $dateExamOnly = $dateExamDateTime->format('Y-m-d');
                                                                
                                                                $interval = $currentDateTime->diff(
                                                                    $dateExamDateTime,
                                                                );

                                                                // Ambil jumlah hari dari selisih tersebut
                                                                $days = $interval->days;

                                                                // Jika tanggal ujian lebih kecil dari tanggal saat ini, buat selisih menjadi negatif
                                                                if ($currentDateOnly > $dateExamOnly) {
                                                                $days = 'Past Deadline';
                                                                } elseif (
                                                                $dateExamOnly > $currentDateOnly &&
                                                                $days == 0
                                                                ) {
                                                                // Jika tanggal ujian di masa depan dan selisih kurang dari 1 hari, anggap 1 hari
                                                                $days = 1;
                                                                }
                                                                elseif ($currentDateOnly === $dateExamOnly) {
                                                                // Jika tanggal ujian sama dengan tanggal saat ini, anggap 0 hari
                                                                $days = 'Today';
                                                                }
                                                            @endphp

                                                            @if ($days == 'Past Deadline')
                                                                <span class="badge badge-warning">Past
                                                                    Deadline</span>
                                                            @elseif ($days == 'Today')
                                                                <span class="badge badge-success">Today</span>
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
                                @else
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="text-center">
                                            <img loading="lazy" src="{{ asset('images/greta-no-assessment.png') }}" alt="" style="max-width: 180px; max-height: 180px;" loading="lazy">
                                            <h2 class="text-light">There is no assessment</h2>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.Left col -->

            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">
                <div class="card bg-warning" style="border-radius: 12px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa-solid fa-book mr-1"></i>
                            Courses
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="position: relative; height: 500px; overflow-y: auto;">
                        <table class="table table-borderless">
                            @if (sizeof($data['dataStudent']->subject) != 0)
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['dataStudent']->subject as $el)
                                        <tr>
                                            <td scope="row">{{ $loop->index + 1 }}</td>
                                            <td><a 
                                                    id="set-course-id"
                                                    data-id="{{ $el->id }}"
                                                    href="javascript:void(0)"
                                                    class="text-decoration-none text-dark"
                                                >
                                                {{ $el->name_subject }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        
        <div class="card bg-info" style="border-radius: 12px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-user mr-1"></i>
                    Profile Students {{$data['dataStudent']->name}}-{{$data['dataStudent']->class}}
                </h3>
            </div>
            <div class="card-body">
                <div class="grid col-12">
                    <div class="d-flex flex-nowrap" style="overflow-x:auto;">
                        {{-- @php
                            dd($data['dataStudent']->student);
                        @endphp --}}
                        @foreach($data['dataStudent']->student as $el)
                        <div class="custom-card-student col-4 col-md-3 col-lg-2">
                            <div class="widget-user-image p-1">
                                @if ($el->profil != null)
                                <img class="img-circle" src="{{asset('storage/file/profile/'.$el->profil)}}" alt="" style="max-width:80px;max-height:80px;">
                                @else
                                <img class="img-circle" src="{{asset('images/user_unknown.png')}}" alt="" style="max-width:80px;max-height:80px;">
                                @endif
                            </div>
                            <h2 class="text-sm pt-2 text-black ml-1">{{$el->name}}</h2>
                            {{-- <p>Deskripsi singkat tentang siswa pertama.</p>
                            <p><a class="btn btn-secondary" href="#">Lihat Profil »</a></p> --}}
                        </div>
                        @endforeach
        
                    </div>
                </div>
            </div>
        </div>

          {{-- bill & payment --}}
            <div class="row">
                <div class="col-md-12">
                    @if (isset($data['paymentStatus']) && $data['paymentStatus']['has_unpaid_bill'])
                        <div class="card bg-gradient-warning animate__animated animate__fadeIn" style="border-radius: 12px;">
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
                    <div class="card" style="border-radius: 12px;">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-2"></i>
                                Payment History (Last 3 Months)
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
        
    </div>
</section>
<!-- /.content-wrapper -->

<link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

@if(session('password.success'))
    <script>
        Swal.fire({
            title: 'Successfully',
            text: 'Success change password',
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            }
        });
    </script>
@endif

<script>
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
                            window.location.href = '/' + sessionRole +
                                '/dashboard/exam/detail';
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

@endsection
