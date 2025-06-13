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

@if (sizeof($data['gradeTeacher']) != 0)
   <div class="container-fluid">
      <div class="row">
         <div class="col">
               <nav aria-label="breadcrumb" class="p-3 mb-4" style="background-color: #ffde9e;border-radius:12px;">
                  <ol class="breadcrumb mb-0"  style="background-color: #fff3c0;">
                     <li class="breadcrumb-item">Home</li>
                     <li class="breadcrumb-item active" aria-current="page">Class</li>
                  </ol>
               </nav>
         </div>
      </div>
      @foreach ($data['gradeTeacher'] as $dgt)
         <div class="card" style="background-color: #ffde9e;border-radius:12px;">
            <div class="card-header">
               <h3 class="card-title text-xl">Data {{ $dgt->name . ' - ' . $dgt->class }}</h3>
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
                           <th>
                              #
                           </th>
                           <th style="width: 35%">
                              Student
                           </th>
                           <th>
                              NISN
                           </th>
                           <th>
                              Gender
                           </th>
                           <th>
                              Religion
                           </th>
                           <th>
                              Place Birth
                           </th>
                        </tr>
                  </thead>
                  <tbody>
                        @if (sizeof($dgt->students) != 0)
                           @foreach ($dgt->students as $el)
                              <tr id="{{ 'index_grade_' . $el->id }}">
                                    <td>
                                       {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                       @if ($el->profil == null)
                                          <img src="{{ asset('template/dist/img/user1-128x128.jpg') }}" class="img-circle elevation-2"
                                             alt="" style="width: 40px;height: 40px;">
                                       @else
                                          <img src="{{ asset('storage/file/profile/' . $el->profil) }}" class="img-circle elevation-2"
                                             alt="" style="width: 40px;height: 40px;">
                                       @endif
                                       <a>
                                          {{ $el->name }}
                                       </a>
                                    </td>
                                    <td>
                                       <a>
                                          {{ $el->unique_id }}
                                       </a>
                                    </td>
                                    <td>
                                       {{ $el->gender }}
                                    </td>
                                    <td>
                                       {{ $el->religion }}
                                    </td>
                                    <td>
                                       {{ $el->place_birth }}
                                    </td>
                              </tr>
                           @endforeach
                        @else
                           <tr>
                              <td colspan="7" class="text-center">No student in this grade!!!</td>
                           </tr>
                        @endif
                  </tbody>
               </table>
            </div>      
         </div>

         
         <div class="card" style="background-color:#ffde9e;border-radius:12px;">
            <div class="card-header">
               <h3 class="card-title text-xl">Course {{ $dgt->name . ' - ' . $dgt->class }}</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                  </button>
               </div>
            </div>
            <div class="card-body">
            <div class="row">
               @foreach ($subjects as $subject)
                  <div class="col-lg-3 col-md-4 col-6">
                     <div class="small-box bg px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;background-color:#fff3c0;border-radius:12px;">
                        <a 
                           @if (session('role') == 'teacher')
                                 href="{{ route('course.sections.class.teacher', [
                                    'id' => $subject->id,
                                    'grade_id' => $data['gradeTeacher'][0]->id,
                                 ]) }}"
                           @endif  
                           class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                        
                           <!-- Ribbon -->
                           <div class="ribbon-wrapper ribbon-lg">
                                 <div class="ribbon bg-dark text-md">
                                    {{ session('role') !== 'teacher' ? 'Course' : $data['gradeTeacher'][0]->name . '-' . $data['gradeTeacher'][0]->class }}
                                 </div>
                           </div>
                        
                           <!-- Bagian Utama -->
                           <div class="">
                                 <!-- Ikon -->
                                 <div>
                                    <img src="{{ asset('storage/'.$subject->icon) }}" 
                                    alt="avatar" class="profileImage img-fluid" 
                                    style="width: 50px; height: 50px; cursor: pointer;">
                                 </div>
                                 <!-- Nama Subject -->
                                 <div class="inner mt-2">
                                    <p class="mb-0 text-lg fw-bold text-center text-dark">{{ $subject->name_subject }}</p>
                                 </div>
                           </div>
                        </a>
                     </div>       
               </div>
               @endforeach
            </div>
            </div>
         </div>
      @endforeach
   </div>

   @else
   <div class="container-fluid full-height">
      <div class="icon-wrapper">
         <i class="fa-regular fa-face-laugh-wink"></i>
         <p> Oops... <br> This page can only be accessed by class teachers</p>
      </div>
   </div>
@endif

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_grade')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new grade in the database.',
        });
      </script>
  @endif

  @if(session('after_update_grade')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully ',
            text: 'Successfully updated the grade in the database.',
         });
    </script>
   @endif

@endsection
