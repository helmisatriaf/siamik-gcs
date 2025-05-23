@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 mb-4">
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
   </div>
   <div class="form-group row">
      <div class="col-md-2">
         <select required name="semester" class="form-control" id="semester" onchange="saveSemesterToSession()">
            <option value="">-- Semester -- </option>
            <option value="1" {{ session('semester') == '1' ? "selected" : "" }}>Semester 1</option>
            <option value="2" {{ session('semester') == '2' ? "selected" : "" }}>Semester 2</option>
         </select>
      </div>
   </div>

   <!-- START TABEL -->
   @if (sizeof($data['grade']) != 0)
         <!-- PRIMARY -->
         <div class="card card-dark mt-2">
               <div class="card-header">
                  <h3 class="card-title">Primary</h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                           <i class="fas fa-minus"></i>
                     </button>
                  </div>
               </div>
               <div class="card-body p-0">
                  <table class="table table-striped projects">
                     <thead>
                           <tr>
                              <th>#</th>
                              <th style="width:10%;">Class</th>
                              <th>Teacher Class</th>
                              <th>Total Student</th>
                              <th>Total Subject</th>
                              <th style="width:50%;"></th>
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
                                    @if (session('role') == 'superadmin')
                                    <td class="project-actions text-right toastsDefaultSuccess">
                                       <a class="btn btn-primary btn"
                                          href="{{url(session('role'). '/reports') . '/detail/' . $pr->id}}">
                                          
                                          </i>
                                          Scoring
                                       </a>
                                       <a class="btn btn-success btn"
                                          href="{{url(session('role') . '/reports') . '/acar/detail/' . $pr->id}}">
                                          
                                          </i>
                                          ACAR
                                       </a>
                                       <a class="btn btn-warning btn"
                                          href="{{url(session('role') . '/reports') . '/sooa/detail/' . $pr->id}}">
                                          
                                          </i>
                                          SOOA
                                       </a>
                                       <a class="btn btn-warning btn"
                                          href="{{url(session('role') . '/reports') . '/tcop/detail/' . $pr->id}}">
                                          
                                          </i>
                                          TCOP
                                       </a>
                                       <a class="btn btn-primary btn"
                                          href="{{url(session('role') . '/reports') . '/semester1/detail/' . $pr->id}}">
                                          
                                          </i>
                                          SEMESTER 1
                                       </a>
                                       <a class="btn btn-primary btn"
                                          href="{{url(session('role') . '/reports') . '/semester2/detail/' . $pr->id}}">
                                          
                                          </i>
                                          SEMESTER 2
                                       </a>
                                    </td>
                                    @elseif (session('role') == 'admin')
                                    <td class="project-actions text-right toastsDefaultSuccess">
                                       <a class="btn btn-primary btn"
                                          href="{{url('admin/reports') . '/detail/' . $pr->id}}">
                                          
                                          </i>
                                          View
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
         <div class="card card-dark mt-5">
               <div class="card-header">
                  <h3 class="card-title">Secondary</h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                           <i class="fas fa-minus"></i>
                     </button>
                  </div>
               </div>
               <div class="card-body p-0">
                  <table class="table table-striped projects">
                     <thead>
                           <tr>
                              <th>#</th>
                              <th>Class</th>
                              <th>Class Teacher</th>
                              <th>Total Student</th>
                              <th>Total Subject</th>
                              <th></th>
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
                                    @if (session('role') == 'superadmin')
                                    <td class="project-actions text-right toastsDefaultSuccess">
                                       <a class="btn btn-primary btn"
                                          href="{{url('superadmin/reports') . '/detailSec/' . $pr->id}}">
                                          
                                          </i>
                                          Scoring
                                       </a>
                                       <a class="btn btn-success btn"
                                          href="{{url('superadmin/reports') . '/acar/detailSec/' . $pr->id}}">
                                          
                                          </i>
                                          ACAR
                                       </a>
                                       <a class="btn btn-warning btn"
                                          href="{{url('superadmin/reports') . '/sooa/detailSec/' . $pr->id}}">
                                          
                                          </i>
                                          SOOA
                                       </a>
                                    </td>
                                    @elseif (session('role') == 'admin')
                                    <td class="project-actions text-right toastsDefaultSuccess">
                                       <a class="btn btn-primary btn"
                                          href="{{url('admin/reports') . '/detailSec/' . $pr->id}}">
                                          
                                          </i>
                                          View
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
      <p class="text-center">You don't have data grade</p>
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
