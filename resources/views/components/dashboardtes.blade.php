@extends('layouts.admin.master')
@section('content')
   <!-- Content Header (Page header) -->
   <!-- /.content-header -->

  @php
    $user = session('role');
    $cardStudent = 'col-lg-3 col-6';
    $cardTeacher = 'col-lg-3 col-6';
    $cardBill = 'col-lg-3 col-6';
    $cardPastDue = 'col-lg-3 col-6';
    $listStudent = "";
    $listTeacher = "";
    $listBill = "";
    $listPastDue = "";
    
    if($user == 'admin') {
      $cardStudent = 'col';
      $cardTeacher = 'col';
      $cardBill = 'd-none col';
      $cardPastDue = 'col';
      $listStudent = "";
      $listTeacher = "";
      $listBill = "d-none";
      $listPastDue = "d-none";
    } else if ($user == 'accounting') {
      $cardStudent = 'd-none col';
      $cardTeacher = 'd-none col';
      $cardBill = 'col';
      $cardPastDue = 'col';
      $listStudent = "d-none";
      $listTeacher = "d-none";
      $listBill = "";
      $listPastDue = "";
    }


  @endphp

   <!-- Main content -->
   <section class="content">
     <div class="container-fluid">
       <!-- Small boxes (Stat box) -->
       <div class="row">
        <!-- STUDENT ACTIVE -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{$data['totalStudent']}}</h3>

              <p>Total Students Active</p>
            </div>
            <div class="icon">
              <i class="fa-solid fa-graduation-cap"></i>
            </div>
            @if (session('role') == 'superadmin')
            <a href="/superadmin/list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            @elseif (session('role') == 'admin')
            <a href="/admin/list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            @endif
           
            <div class="small-box-footer" style="padding: 0.93rem"></div>
          </div>
        </div>
        <!-- ./col -->
        
        <!-- TEACHER ACTIVE -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{$data['totalTeacher']}}
              {{-- <sup style="font-size: 20px">%</sup> --}}
            </h3>

              <p>Total Teacher Active</p>
            </div>
            <div class="icon">
              {{-- <i class="ion ion-stats-bars"></i> --}}
              <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            @if (session('role') == 'superadmin')
            <a href="/superadmin/teachers" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            @elseif (session('role') == 'admin')
            <a href="/admin/teachers" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            @endif
            
            <div class="small-box-footer" style="padding: 0.93rem"></div>
          </div>
        </div>
        <!-- ./col -->

        <!-- GRADE ACTIVE -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{$data['totalGrade']}}
              {{-- <sup style="font-size: 20px">%</sup> --}}
              </h3>

              <p>Total Grade Active</p>
            </div>
            <div class="icon">
              {{-- <i class="ion ion-person-add"></i> --}}
              <i class="fa-solid fa-receipt"></i>
            </div>
            @if (session('role') == 'superadmin')
            <a href="/superadmin/grades" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            @elseif (session('role') == 'admin')
            <a href="/admin/grades" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            @endif
            <div class="small-box-footer" style="padding:0.93rem;"></div>
          </div>
        </div>
        <!-- ./col -->

        <!-- EXAM ACTIVE -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $data['totalExam']}}</h3>

              <p>Total Assessment</p>
            </div>
            <div class="icon">
              {{-- <i class="ion ion-pie-graph"></i> --}}
              <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            
            @if (session('role') == 'superadmin')
            <a href="/superadmin/exams" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            @elseif (session('role') == 'admin')
            <a href="/admin/exams" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            @endif
            <div class="small-box-footer" style="padding:0.93rem;"></div>
            
          </div>
        </div>
        <!-- ./col -->
       </div>
       <!-- /.row -->
       <!-- Main row -->
       <div class="row">
         <!-- Left col -->
         <section class="col-lg-7 connectedSortable">
           
          <!-- Custom tabs (Charts with tabs) List Exam-->
          <div class="card bg-danger">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa-solid fa-calendar-xmark mr-1"></i>
                  Assessment On Progress
              </h3>
              <!-- card tools -->
              <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content p-0">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane active" id="revenue-chart"
                     style="position: relative; height: 500px; overflow-y: auto;">

                     @if (sizeof($data['exam']) == 0)
                      <div class="d-flex justify-content-center">
                        <h2>Assessment empty !!!</h2>
                      </div>
                     @else
                      <div>
                       <!-- /.card-header -->
                       <div>
                         <ul class="todo-list bg-danger" data-widget="todo-list">
    
                          @php
                           $currentDate = date('y-m-d');
                          @endphp 
    
                           @foreach ($data['exam'] as $el)
                           <li>
                             <!-- drag handle -->
                             <span class="handle">
                               <i class="fas fa-ellipsis-v"></i>
                               <i class="fas fa-ellipsis-v"></i>
                             </span>
                             <!-- checkbox -->
                             <div  class="icheck-primary d-inline ml-2">
                               <span class="text-muted">[ {{date( 'd F Y',strtotime($el->date_exam))}} ]</span>
                             </div>
                             <!-- todo text -->
                             <span class="text text-sm">( {{$el->type_exam_name}} ) {{$el->name_exam}} ({{ $el->subject }}) ({{ $el->grade_name .'-'. $el->grade_class }})</span>
                             <!-- Emphasis label -->

                             <span>
                                @if ($el->is_active == true)
                                  @php
                                    $currentDate = now(); // Tanggal saat ini
                                    $dateExam = $el->date_exam; // Tanggal exam dari data
                                    $diff = strtotime($dateExam) - strtotime($currentDate);
                                    $days = floor($diff / (60 * 60 * 24));
                                  @endphp
                                  
                                  <span class="badge badge-warning">{{$days}} days again</span>
                                @else
                                 <span class="badge badge-success">Done</span>
                                @endif
                              </span>
                              
                             
                             <div class="tools">
                               <a href="/admin/exams/{{$el->id}}" target="_blank">
                                 <i class="fas fa-search"></i>
                               </a>
                             </div>
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
          <div class=" card bg-info">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="fa-solid fa-graduation-cap mr-1"></i>
                Student's
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
                   <th scope="col">Grade</th>
                 </tr>
               </thead>
               <tbody>

               @foreach ($data['dataStudent'] as $el)
                 <tr>
                   <td scope="row">{{$loop->index+1}}</td>
                   <td>{{ ucwords(strtolower($el->name)) }}</td>
                   <td>{{ $el->gender }}</td>
                   <td>{{ $el->grade_name }} - {{ $el->grade_class }}</td>
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
          <!-- /.card -->
       
         </section>
         <!-- /.Left col -->
         <!-- right col (We are only adding the ID to make the widgets sortable)-->
         <section class="col-lg-5 connectedSortable">        
          <!-- Grade List -->
          <div class=" card bg-warning">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="fa-solid fa-receipt mr-1"></i>
                Grade's
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
             <table class="table table-borderless">
               <thead>
                 <tr>
                   <th scope="col">#</th>
                   <th scope="col">Name</th>
                   <th scope="col">Class Teacher</th>
                 </tr>
               </thead>
               <tbody>

               @foreach ($data['grade'] as $el)
                 <tr>
                   <td scope="row">{{$loop->index+1}}</td>
                   <td>{{$el->name}} - {{$el->class}}</td>
                   @foreach ($el->gradeTeacher as $teacher)
                   <td>{{$teacher->name}}</td>
                   @endforeach
                 </tr>
               @endforeach  
                 
               </tbody>
             </table>
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
          </div>

          <!-- Teacher List -->
          <div class=" card bg-success">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="fa-solid fa-chalkboard-user mr-1"></i>
                Teacher's
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
             <table class="table table-borderless">
               <thead>
                 <tr>
                   <th scope="col">#</th>
                   <th scope="col">Name</th>
                   <th scope="col">Gender</th>
                 </tr>
               </thead>
               <tbody>

               @foreach ($data['dataTeacher'] as $el)
                 <tr>
                   <td scope="row">{{$loop->index+1}}</td>
                   <td>{{$el->name}}</td>
                   <td>{{$el->gender}}</td>
                 </tr>
               @endforeach  
                 
               </tbody>
             </table>
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
          </div>
          <!-- /.card -->
         </section>
         <!-- right col -->
       </div>
       <!-- /.row (main row) -->
     </div><!-- /.container-fluid -->
   </section>
 <!-- /.content-wrapper -->
@endsection
