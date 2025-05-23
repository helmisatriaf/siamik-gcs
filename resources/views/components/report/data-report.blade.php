@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   {{-- <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            @if (session('role') == 'superadmin')
              <li class="breadcrumb-item"><a href="{{url('/superadmin/reports')}}">Reports</a></li>
            @elseif (session('role') == 'admin')
            <li class="breadcrumb-item"><a href="{{url('/admin/reports')}}">Reports</a></li>
            @endif
          </ol>
        </nav>
      </div>
   </div> --}}

   {{-- <div class="form-group row">
      <div class="col-md-2">
         <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
            <option value="">-- Semester -- </option>
            <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
            <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
         </select>
      </div>
   </div> --}}

   <!-- START TABEL -->
   @if (sizeof($data['grade']) != 0)
         <!-- UNDERGRADE -->
         <div class="card card-orange">
            <div class="card-header">
               <h3 class="card-title">Except Primary dan Secondary</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                  </button>
               </div>
            </div> 
            <div class="card-body p-0" style="overflow-x: auto;">
               <table class="table table-striped projects">
                  <thead>
                        <tr>
                           <th>#</th>
                           <th style="width:15%;">Class</th>
                           <th>Class Teacher</th>
                           <th>Student</th>
                           <th>Subject</th>
                           <th style="width:55%;">Action</th>
                        </tr>
                  </thead>
                  <tbody>
                        @foreach ($data['other'] as $pr)
                           <tr id="{{ 'index_grade_' . $pr->id }}">
                                 <td>{{ $loop->index + 1 }}</td>
                                 <td><a>  {{ $pr->grade_name }} - {{ $pr->grade_class }}</a></td>
                                 <td><a>  {{ $pr->teacher_class }}</a></td>
                                 <td><a>  {{ $pr->active_student_count }}</a></td>
                                 <td><a>  {{ $pr->active_subject_count }}</a></td>
                                 @if (session('role') == 'superadmin' || session('role') == 'admin')
                                 <td class="project-actions text-left toastsDefaultSuccess">
                                    @if (strtolower($pr->grade_name) === "toddler")
                                       <a class="btn btn-secondary btn" href="{{ url(session('role'). '/reports') . '/mid/cardToddler' . '/' . $pr->id }}">
                                          Mid Report Card
                                       </a>
                                       <a class="btn btn-primary btn" href="{{ url(session('role') . '/reports') . '/cardToddler' . '/' . $pr->id }}">
                                          Report Card
                                       </a>
                                    @elseif (strtolower($pr->grade_name) === "nursery")
                                       <a class="btn btn-secondary btn" href="{{ url(session('role') . '/reports') . '/mid/cardNursery' . '/' . $pr->id }}">
                                          Mid Report Card 
                                       </a>
                                       <a class="btn btn-primary btn" href="{{url(session('role') . '/reports') . '/cardNursery' . '/' . $pr->id }}">
                                          Report Card
                                       </a>
                                    @elseif (strtolower($pr->grade_name) === "kindergarten")
                                       <a class="btn btn-primary btn"
                                          href="{{url(session('role'). '/reports') . '/detail/' . $pr->id}}">
                                          Scoring
                                       </a>
                                       <a class="btn btn-secondary btn" href="{{ url(session('role') . '/reports') . '/mid/cardKindergarten' . '/' . $pr->id }}">
                                          Mid Report Card 
                                       </a>
                                       <a class="btn btn-primary btn" href="{{ url(session('role') . '/reports') . '/cardKindergarten' . '/' . $pr->id }}">
                                          Report Card 
                                       </a>
                                    @endif
                                 </td>
                                 @endif
                           </tr>
                        @endforeach
                  </tbody>
               </table>
            </div>
         </div>
         <!-- END UNDERGRADE -->

         <!-- PRIMARY -->
         <div class="card card-orange mt-2">
               <div class="card-header">
                  <h3 class="card-title">Primary</h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                           <i class="fas fa-minus"></i>
                     </button>
                  </div>
               </div>
               <div class="card-body p-0" style="overflow-x: auto;">
                  <table class="table table-striped projects">
                     <thead>
                           <tr>
                              <th>#</th>
                              <th style="width:15%;">Class</th>
                              <th>Class Teacher</th>
                              <th>Student</th>
                              <th>Subject</th>
                              <th style="width:55%;"> Action</th>
                           </tr>
                     </thead>
                     <tbody>
                           @foreach ($data['primary'] as $pr)
                              <tr id="{{ 'index_grade_' . $pr->id }}">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a>  {{ $pr->grade_name }} - {{ $pr->grade_class }}</a></td>
                                    <td><a>  {{ $pr->teacher_class }}</a></td>
                                    <td><a>  {{ $pr->active_student_count }}</a></td>
                                    <td><a>  {{ $pr->active_subject_count }}</a></td>
                                    @if (session('role') == 'superadmin' || session('role') == 'admin')
                                    <td class="project-actions text-left toastsDefaultSuccess">
                                       <a class="btn btn-primary btn"
                                          href="{{url(session('role'). '/reports') . '/detail/' . $pr->id}}">
                                          Scoring
                                       </a>
                                       <a class="btn btn-success btn"
                                          href="{{url(session('role') . '/reports') . '/acar/detail/' . $pr->id}}">
                                          ACAR
                                       </a>
                                       <a class="btn btn-warning btn"
                                          href="{{url(session('role') . '/reports') . '/sooa/detail/' . $pr->id}}">
                                          SOOA
                                       </a>
                                       <a class="btn btn-secondary btn"
                                          href="{{url(session('role') . '/reports') . '/tcop/detail/' . $pr->id}}">
                                          TCOP
                                       </a>
                                       @if (session('semester') == 1)
                                          <a class="btn btn-secondary btn" href="{{ url(session('role') . '/reports') . '/midcard/semestersatu' . '/' . $pr->id }}">
                                             Mid Report Card 
                                          </a>
                                          <a class="btn btn-danger btn"
                                             href="{{url(session('role') . '/reports') . '/semestersatu/detail/' . $pr->id}}">
                                             Report Card
                                          </a>
                                       @elseif (session('semester') == 2)
                                          <a class="btn btn-secondary btn" href="{{ url(session('role') . '/reports') . '/midcard/semestersatu' . '/' . $pr->id }}">
                                             Mid Report Card 
                                          </a>
                                          <a class="btn btn-danger btn"
                                             href="{{url(session('role') . '/reports') . '/semesterdua/detail/' . $pr->id}}">
                                             Report Card
                                          </a>
                                       @endif
                                       <a class="btn btn-secondary btn"
                                          href="{{'/reports/remedial/' . $pr->id}}">
                                          Remedial
                                       </a>
                                    </td>
                                    @endif
                              </tr>
                           @endforeach
                     </tbody>
                  </table>
               </div>
         </div>
         <!-- END PRIMARY -->
   
         <!-- SECONDARY -->
         <div class="card card-orange mt-5">
               <div class="card-header">
                  <h3 class="card-title">Secondary</h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                           <i class="fas fa-minus"></i>
                     </button>
                  </div>
               </div>
               <div class="card-body p-0" style="overflow-x: auto;">
                  <table class="table table-striped projects">
                     <thead>
                           <tr>
                              <th>#</th>
                              <th style="width:15;">Class</th>
                              <th>Class Teacher</th>
                              <th>Student</th>
                              <th>Subject</th>
                              <th style="width:55%;">Action</th>
                           </tr>
                     </thead>
                     <tbody>
                           @foreach ($data['secondary'] as $pr)
                              <tr id="{{ 'index_grade_' . $pr->id }}">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a>  {{ $pr->grade_name }} - {{ $pr->grade_class }}</a></td>
                                    <td><a>  {{ $pr->teacher_class }}</a></td>
                                    <td><a>  {{ $pr->active_student_count }}</a></td>
                                    <td><a>  {{ $pr->active_subject_count }}</a></td>
                                    @if (session('role') == 'superadmin' || session('role') == 'admin')
                                    <td class="project-actions text-left toastsDefaultSuccess">
                                       <a class="btn btn-primary btn"
                                          href="{{url(session('role'). '/reports') . '/detailSec/' . $pr->id}}">
                                          Scoring
                                       </a>
                                       <a class="btn btn-success btn"
                                          href="{{url(session('role') . '/reports') . '/acar/detailSec/' . $pr->id}}">
                                          ACAR
                                       </a>
                                       <a class="btn btn-warning btn"
                                          href="{{url(session('role') . '/reports') . '/sooa/detailSec/' . $pr->id}}">
                                          SOOA
                                       </a>
                                       <a class="btn btn-secondary btn"
                                          href="{{url(session('role') . '/reports') . '/tcop/detailSec/' . $pr->id}}">
                                          TCOP
                                       </a>
                                       <a class="btn btn-secondary btn" href="{{ url(session('role') . '/reports') . '/midcard/semestersatu' . '/' . $pr->id }}">
                                          Mid Report Card 
                                       </a>
                                       @if (session('semester') == 1)
                                       <a class="btn btn-danger btn"
                                          href="{{url(session('role') . '/reports') . '/semestersatu/detailSec/' . $pr->id}}">
                                          Report Card
                                       </a>
                                       @elseif (session('semester') == 2)
                                       <a class="btn btn-danger btn"
                                          href="{{url(session('role') . '/reports') . '/semesterdua/detailSec/' . $pr->id}}">
                                          Report Card
                                       </a>
                                       @endif
                                       <a class="btn btn-secondary btn"
                                          href="{{ '/reports/remedial/' . $pr->id}}">
                                          Remedial
                                       </a>
                                    </td>
                                    @endif
                              </tr>
                           @endforeach
                     </tbody>
                  </table>
               </div>
         </div>
         <!-- END SECONDARY -->

         <!-- END TABLE -->
   @else
      <p class="text-center">Teacher Dont create any assessment</p>
   @endif
   <!-- END TABEL -->
</div>

<script src="{{asset('template')}}/plugins/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
   function saveSemesterToSession() {
      var semesterSelect = document.getElementById('semester');
      var selectedSemester = semesterSelect.value;
      
      // Simpan nilai semester ke dalam session
      $.ajax({
         url: '{{ route('save.semester.session') }}',
         type: 'POST',
         data: {
            semester: selectedSemester,
            _token: '{{ csrf_token() }}'
         },
         success: function(response) {
            console.log('Semester saved to session:', response.semester);
         },
         error: function(xhr, status, error) {
            console.error('Error saving semester to session:', error);
         }
      });
   }
</script>

@endsection
