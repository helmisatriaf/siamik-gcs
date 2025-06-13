@extends('layouts.admin.master')
@section('content')
   <!-- Content Header (Page header) -->
   <!-- /.content-header -->

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
               <h3>{{$data['totalStudent']}}</h3>

               <p>Total Students Active</p>
             </div>
             <div class="icon">
              <i class="fa-solid fa-child"></i>
             </div>
             <a href="/teacher/dashboard/grade" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <!-- ./col -->

         <!-- GRADE ACTIVE -->
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-success" style="border-radius: 12px;">
             <div class="inner">
               <h3>{{$data['totalGrade']}}
                {{-- <sup style="font-size: 20px">%</sup> --}}
              </h3>

               <p>Class Teacher</p>
             </div>
             <div class="icon">
               {{-- <i class="ion ion-stats-bars"></i> --}}
               <i class="fa-solid fa-chalkboard-user"></i>
             </div>
              <a href="/teacher/dashboard/grade" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->

         <!-- SUBJECT ACTIVE -->
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-warning" style="border-radius: 12px;">
             <div class="inner">
               <h3>{{$data['totalSubject']}}
               {{-- <sup style="font-size: 20px">%</sup> --}}
               </h3>

               <p>Total Courses Active</p>
              </div>
              <div class="icon">
                {{-- <i class="ion ion-person-add"></i> --}}
                <i class="fa-solid fa-book"></i>
              </div>
              <a href="/teacher/course" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
           </div>
         </div>
         <!-- ./col -->

         <!-- EXAM ACTIVE -->
         <div class="col-lg-3 col-6">
           <!-- small box -->
           <div class="small-box bg-danger" style="border-radius: 12px;">
             <div class="inner">
               <h3>{{ $data['totalExam']}}</h3>

               <p>Total Assessments</p>
             </div>
             <div class="icon">
               {{-- <i class="ion ion-pie-graph"></i> --}}
               <i class="fa-solid fa-book-open-reader"></i>
             </div>
             
             <a href="/teacher/dashboard/exam/teacher" class="small-box-footer" style="border-radius: 12px;">More info <i class="fas fa-arrow-circle-right"></i></a>
             
           </div>
         </div>
         <!-- ./col -->
       </div>

       <!-- /.row -->
       <!-- Main row -->
       <div class="row">
         <!-- Left col -->
         <section class="col-lg-8 connectedSortable">
           
          <!-- Custom tabs (Charts with tabs) List Exam-->
          <div class="card bg-danger" style="border-radius: 12px;">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa-solid fa-calendar-xmark mr-1"></i>
                  Assessments Ongoing
              </h3>
            </div><!-- /.card-header -->
            <div class="card-body" style="position: relative; height: 500px; overflow-y: auto;">
              <div class="tab-content p-0">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane active" id="revenue-chart">

                     @if (sizeof($data['exam']) == 0)
                      <div class="d-flex justify-content-center"> 
                        <h5 class="text-center">Oops.. <br> You don't have any assessment</h5>
                      </div>
                     @else
    
                      {{-- <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas> --}}
                      <div>
                       <!-- /.card-header -->
                       <div>
                         <ul class="todo-list bg-danger" data-widget="todo-list">
    
                          @php
                           $currentDate = date('y-m-d');
                          @endphp 
    
                            @foreach ($data['exam'] as $el)
                            <li  
                            class="hover:cursor-pointer"
                            style="background-color: #ffde9e;border: 2px dashed #ffcc00;border-radius: 8px;"
                            >
                              <a href="{{ '/teacher/dashboard/exam/detail/' .$el->id }}" class="text-decoration-none text-dark">
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
                              </a>
                            </li>
                            @endforeach
                         </ul>
                       </div>
                     </div>
    
                     @endif
                 

                 </div>
              </div>
            </div><!-- /.card-body -->
          </div>
          <!-- /.card -->

          <!-- Map card -->
          {{-- @if (sizeof($data['student']) !== 0)
            <div class=" card bg-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fa-solid fa-graduation-cap mr-1"></i>
                  Students {{$data['gradeTeacher'][0]['name']}} - {{$data['gradeTeacher'][0]['class']}} 
                </h3>
                <!-- card tools -->
                <div class="card-tools">
                  <button type="button" class="btn btn-info btn-sm" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <div class="card-body" style="position: relative; height: 500px; overflow-y: auto;">
                <table class="table table-borderless">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">Gender</th>
                      <th scope="col">NISN</th>
                      <th scope="col">Religion</th>
                    </tr>
                  </thead>
                  <tbody>

                  @foreach ($data['student'] as $el)
                    <tr>
                      <td scope="row">{{$loop->index+1}}</td>
                      <td>{{ $el->name }}</td>
                      <td>{{ $el->gender }}</td>
                      <td>{{ $el->unique_id }}</td>
                      <td>{{ ucwords(strtolower($el->religion)) }}</td>
                    </tr>
                  @endforeach  
                    
                  </tbody>
                </table>
              </div>
              <div class="card-footer bg-transparent">
                <div class="d-none row">
                  <div class="col-4 text-center">
                    <div id="sparkline-1"></div>
                    <div class="text-white">Visitors</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <div id="sparkline-2"></div>
                    <div class="text-white">Online</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <div id="sparkline-3"></div>
                    <div class="text-white">Sales</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
          @endif --}}
          <!-- /.card -->
       
         </section>
         <!-- /.Left col -->

         <!-- right col (We are only adding the ID to make the widgets sortable)-->
         <section class="col-lg-4 connectedSortable">        
          <!-- Subject List -->
          <div class="card bg-warning" style="border-radius: 12px;">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa-solid fa-book mr-1"></i>
                Courses
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="position: relative; height:500px; overflow-y: auto;">
              @if(sizeof($data['teacherSubject']) != 0)
                <table class="table table-borderless">      
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">Grade</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data['teacherSubject'] as $el)
                      <tr>
                        <td scope="row">{{$loop->index+1}}</td>
                        <td>
                          <a href="course/{{$el->id}}/sections/{{$el->grade_id}}" class="text-dark">
                            {{$el->name_subject}}
                          </a>
                        </td>
                        <td>{{$el->grade_name}}</td>
                      </tr>
                    @endforeach  
                  </tbody>
                </table>
              @else
                <div class="d-flex justify-content-center">
                  <h5 class="text-center">Oops.. <br>Maybe you haven't been plotted yet</h5>
                </div>
              @endif
            </div>
          </div>

          <!-- Grade List -->
          {{-- <div class=" card bg-success">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="fa-solid fa-chalkboard-user mr-1"></i>
                Grades
              </h3>
              <!-- card tools -->
              <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <div class="card-body" style="position: relative; height: 500px; overflow-y: auto;">
              @if (sizeof($data['gradeTeacher']) == 0)
                <div class="d-flex justify-content-center">
                  <h5 class="text-center">Oops.. <br>You are not class teacher</h5>
                </div>
              @else
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Class</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach ($data['gradeTeacher'] as $el)
                    <tr>
                      <td scope="row">{{$loop->index+1}}</td>
                      <td>{{$el->name}}</td>
                      <td>{{$el->class}}</td>
                    </tr>
                  @endforeach  
                  </tbody>
                </table>
              @endif

                 
            </div>
            <div class="card-footer">
              <div class="d-none row">
                <div class="col-4 text-center">
                  <div id="sparkline-1"></div>
                  <div class="text-white">Visitors</div>
                </div>
                <!-- ./col -->
                <div class="col-4 text-center">
                  <div id="sparkline-2"></div>
                  <div class="text-white">Online</div>
                </div>
                <!-- ./col -->
                <div class="col-4 text-center">
                  <div id="sparkline-3"></div>
                  <div class="text-white">Sales</div>
                </div>
                <!-- ./col -->
              </div>
              <!-- /.row -->
            </div>
          </div> --}}
          <!-- /.card -->
         </section>
         <!-- right col -->
       </div>
       <!-- /.row (main row) -->
     </div><!-- /.container-fluid -->
   </section>
 <!-- /.content-wrapper -->
@endsection
