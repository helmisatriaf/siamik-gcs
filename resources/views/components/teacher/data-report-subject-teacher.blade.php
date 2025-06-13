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

@if (sizeof($data) != 0)
<div class="container-fluid">
   
   <!-- START TABEL -->
      <!-- Display Kindergarten Grades -->
      @if (!$kindergartenGrades->isEmpty())
         <div class="card" style="background-color: #ffde9e;border-radius:12px;">
            <div class="card-header"> 
                  <h3 class="card-title text-bold">Kindergarten Grades</h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                     </button>
                  </div>
            </div>
            <div class="card-body">
               <div class="row">
                  @foreach ($kindergartenGrades as $el)
                     <div class="col-lg-3 col-md-4 col-6">
                        <div class="small-box bg-light px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                           <a href="{{ url('teacher/dashboard/report/detailSubjectKindergarten') . '/' . $el->grade_id . '/' . $el->subject_id }}"
                              class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                           
                              <!-- Ribbon -->
                              <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon {{ $el->status == 1 ? 'bg-primary' : 'bg-secondary' }}">
                                       {{ $el->status == 1 ? 'Completed' : 'Not Submitted' }}
                                    </div>
                              </div>
                           
                              <!-- Bagian Utama -->
                              <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                    <!-- Ikon -->
                                    <div>
                                       <img src="{{ asset('storage/'.$el->icon) }}" 
                                       alt="avatar" class="profileImage img-fluid" 
                                       style="width: 50px; height: 50px; cursor: pointer;">
                                    </div>
                                    <!-- Nama Subject -->
                                    <div class="inner mt-2 text-dark">
                                       <p class="mb-0 text-lg fw-bold text-center">{{ $el->name_subject }}</p>
                                       <p>{{$el->name}} - {{$el->class}}</p>
                                    </div>
                              </div>
                           </a>
                        </div> 
                     </div>                              
                  @endforeach
               </div>
            </div>
         </div>
      @endif

      <!-- Display Primary Grades -->
      @if (!$primaryGrades->isEmpty())
         <div class="card" style="background-color: #ffde9e;border-radius:12px;">
            <div class="card-header"> 
                  <h3 class="card-title text-bold text-xl">Primary Grades</h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                     </button>
                  </div>
            </div>
            <div class="card-body">
               <div class="row">
                  @foreach ($primaryGrades as $el)
                     <div class="col-lg-3 col-md-4 col-6">
                        <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;background-color: #fff3c0;border-radius:12px;">
                           <a href="{{ url('teacher/dashboard/report/detailSubjectPrimary') . '/' . $el->grade_id . '/' . $el->subject_id }}"
                              class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                           
                              <!-- Ribbon -->
                              <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon {{ $el->status == 1 ? 'bg-primary' : 'bg-dark' }}">
                                       {{ $el->status == 1 ? 'Completed' : 'Not Submitted' }}
                                    </div>
                              </div>
                           
                              <!-- Bagian Utama -->
                              <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                    <!-- Ikon -->
                                    <div>
                                       <img src="{{ asset('storage/'.$el->icon) }}" 
                                       alt="avatar" class="profileImage img-fluid" 
                                       style="width: 50px; height: 50px; cursor: pointer;">
                                    </div>
                                    <!-- Nama Subject -->
                                    <div class="inner mt-2 text-dark">
                                       <p class="mb-0 text-lg fw-bold text-center">{{ $el->name_subject }}</p>
                                       <p>{{$el->name}} - {{$el->class}}</p>
                                    </div>
                              </div>
                           </a>
                        </div> 
                     </div>
                  @endforeach    
               </div>
            </div>
         </div>
      @endif

      <!-- Display Secondary Grades -->
      @if (!$secondaryGrades->isEmpty())
         <div class="card" style="background-color: #ffde9e;border-radius: 12px;">
            <div class="card-header"> 
                  <h3 class="card-title text-bold text-xl">Secondary Grades</h3>
                  <div class="card-tools">
                     <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                     </button>
                  </div>
            </div>
            <div class="card-body">
               <div class="row">
                  @foreach ($secondaryGrades as $el)
                        <div class="col-lg-3 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;background-color: #fff3c0;border-radius:12px;">
                              <a href="{{ url('teacher/dashboard/report/detailSubjectSecondary') . '/' . $el->grade_id . '/' . $el->subject_id }}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Ribbon -->
                                 <div class="ribbon-wrapper ribbon-lg">
                                       <div class="ribbon {{ $el->status == 1 ? 'bg-primary' : 'bg-dark' }}">
                                          {{ $el->status == 1 ? 'Completed' : 'Not Submitted' }}
                                       </div>
                                 </div>
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('storage/'.$el->icon) }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>
                                       <!-- Nama Subject -->
                                       <div class="inner mt-2 text-dark">
                                          <p class="mb-0 text-lg fw-bold text-center">{{ $el->name_subject }}</p>
                                          <p>{{$el->name}} - {{$el->class}}</p>
                                       </div>
                                 </div>
                              </a>
                           </div> 
                        </div>
                  @endforeach
               </div>
            </div>
         </div>
      @endif


   <!-- END TABLE -->
</div>
@else
   <div class="container-fluid full-height">
      <div class="icon-wrapper">
            <i class="fa-regular fa-face-laugh-wink"></i>
            <p>Oops.. <br> Maybe you haven't been plotted yet</p>
      </div>
   </div>
@endif

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
