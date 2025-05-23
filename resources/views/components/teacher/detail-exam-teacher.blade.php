@extends('layouts.admin.master')
@section('content')
@php
$currentDate = now(); // Tanggal saat ini
$dateExam = $data->date_exam; // Tanggal exam dari data

// Hitung selisih antara tanggal exam dengan tanggal saat ini
$diff = strtotime($dateExam) - strtotime($currentDate);
$days = floor($diff / (60 * 60 * 24)); // Konversi detik ke hari
@endphp

<section>
  <div class="row">
    <div class="col">
      <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-4">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item">Home</li>
          @if (session('role') == 'teacher')
          <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/teacher') }}">Assessment</a></li>
          @elseif (session('role') == 'admin' || session('role') == 'superadmin')
          <li class="breadcrumb-item"><a href="{{url('/'. session('role') .'/exams') }}">Assessment</a></li>
          @endif
          <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
      </nav>
    </div>
  </div>
  
  <div class="row">
    <div class="col">
      <div class="card card-orange">
        <div class="card-header">
          <h3 class="card-title text-bold">Detail</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
              <div class="row">
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-black text-bold">{{$data->name_exam}}</span>
                      {{-- <span class="info-box-number text-center text-muted mb-0">2300</span> --}}
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-black text-bold">{{$data->type_exam}} - {{$data->subject_name}}</span>
                      {{-- <span class="info-box-number text-center text-muted mb-0">2000</span> --}}
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-black text-bold">Deadline {{ \Carbon\Carbon::parse($data->date_exam)->translatedFormat('d F Y') }} </span>
                      {{-- <span class="info-box-number text-center text-muted mb-0">20</span> --}}
                    </div>
                  </div>
                </div>
              </div>
              
              {{-- KONTEN QUESTION --}}
              {{-- ASSESSMENT BERUPA UPLOAD FILE --}}
              @if($data->hasFile == true)
                <div class="row">
                  <div class="col-12">
                    <iframe src="{{ asset('storage/file/assessment/'.$data->file_name) }}" width="100%" height="500px"></iframe>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="post">
                      <p class="text-bold">Recent Activity :</p>
                      @foreach ($status as $st)
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $st->student_profile)}}" alt="user image" loading="lazy">
                          <span class="username">
                            <a class="text-muted" href="#">{{$st->student_name}}</a>
                          </span>
                          <span class="description">Upload at - {{ \Carbon\Carbon::parse($st->time_upload)->format('d M Y H:i') }}</span>
                        </div>
                        <p class="text-secondary text-sm">
                          <a href="{{ asset('storage/file/answers/'.$st->file_name) }}"  
                            class="btn-link text-secondary d-block" 
                            target="_blank" 
                            title="See Your Answer"
                            rel="noopener noreferrer">
                              <i class="fas fa-link mr-1"></i> {{ $st->file_name }}
                          </a>
                          |
                          Score : {{$st->score}}  
                        </p>
                        
                      @endforeach
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="post">
                      <p class="text-bold text-danger">No Activity :</p>
                      @foreach ($notyet as $ny)
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $ny->student_profile)}}" alt="user image">
                          <span class="username">
                            <a class="text-danger" href="#">{{$ny->student_name}}</a>
                          </span>
                          <span class="description text-danger"> Score : {{$ny->score}}  </span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endif
              {{-- END --}}

              @if($data->type_exam == "Participation")
                <div class="row">
                  <div class="col-12">
                    <div class="post">
                      <p class="text-bold">Recent Activity :</p>
                      @foreach ($status as $st)
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $st->student_profile)}}" alt="user image">
                          <span class="username">
                            <a class="text-muted" href="#">{{$st->student_name}}</a>
                          </span>
                          <span class="description text-danger">Score : {{$st->score}} </span>
                        </div> 
                        </p>
                        
                      @endforeach
                    </div>
                  </div>
                </div>
              @endif

              {{-- ASSESSMENT MULTIPLE CHOICE DAN ESSAY --}}
              @if ($data->model !== null)
                <div class="row">
                  <div class="col-12 col-md-4">
                      <div class="info-box small-box bg-orange px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;">
                          <a 
                              href="#"
                              id="workplace"
                              class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                          
                              <!-- Ribbon -->
                              <div class="ribbon-wrapper ribbon-lg">
                                  <div class="ribbon bg-warning">
                                    Assessment
                                  </div>
                              </div>
                          
                              <!-- Bagian Utama -->
                              <div class="d-flex flex-column justify-content-center align-items-center" style="max-width: 150px;">
                                <div class="image-container" style="width: 100px; height: 120px;">
                                    <img loading="lazy" src="{{ asset('images/greta-greti-baju-olga.png') }}" 
                                         alt="avatar" 
                                         class="profileImage img-fluid"
                                         style="width: 100%; height: 100%; object-fit: contain; cursor: pointer;">
                                </div>
                                <div class="text-container mt-2">
                                    <p class="text-center mb-0" style="font-size: 14px; color: white; font-weight: bold;">
                                        Click Me!
                                    </p>
                                </div>
                            </div>
                            
                          </a>
                      </div>       
                  </div>
                </div>  

                @if ($data->model == "mce")
                  <div class="row">
                    <div class="col-6">
                      <div class="post">
                        <p class="text-bold">Recent Activity :</p>
                        @foreach ($status as $st)
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $st->student_profile)}}" alt="user image">
                            <span class="username">
                              <a class="text-muted text-sm" href="#">{{$st->student_name}}</a>
                            </span>
                            <span class="description text-sm">Upload at - {{ \Carbon\Carbon::parse($st->time_upload)->format('d M Y H:i') }}</span>
                            <span class="description text-sm text-danger mt-1">Score : {{$st->score}}</span>
                          </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="post">
                        <p class="text-bold text-danger">No Activity :</p>
                        @foreach ($notyet as $ny)
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $ny->student_profile)}}" alt="user image">
                            <span class="username">
                              <a class="text-danger text-sm" href="#">{{$ny->student_name}}</a>
                            </span>
                            <span class="description text-danger text-sm"> Score : {{$ny->score}}  </span>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>

                @elseif ($data->model == "mc")
                  <div class="row">
                    <div class="col-6">
                      <div class="post">
                        <p class="text-bold">Recent Activity :</p>
                        @foreach ($status as $st)
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $st->student_profile)}}" alt="user image">
                            <span class="username">
                              <a class="text-muted text-sm" href="#">{{$st->student_name}}</a>
                            </span>
                            <span class="description text-sm">Upload at - {{ \Carbon\Carbon::parse($st->time_upload)->format('d M Y H:i') }}</span>
                            <span class="description text-sm text-danger mt-1">Score : {{$st->score}}</span>
                          </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="post">
                        <p class="text-bold text-danger">No Activity :</p>
                        @foreach ($notyet as $ny)
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $ny->student_profile)}}" alt="user image">
                            <span class="username">
                              <a class="text-danger text-sm" href="#">{{$ny->student_name}}</a>
                            </span>
                            <span class="description text-danger text-sm"> Score : {{$ny->score}}  </span>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  
                @elseif ($data->model == "essay")
                  <div class="row">
                    <div class="col-6">
                      <div class="post">
                        <p class="text-bold">Recent Activity :</p>
                        @foreach ($status as $st)
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $st->student_profile)}}" alt="user image">
                            <span class="username">
                              <a class="text-muted text-sm" href="#">{{$st->student_name}}</a>
                            </span>
                            <span class="description text-sm">Upload at - {{ \Carbon\Carbon::parse($st->time_upload)->format('d M Y H:i') }}</span>
                            <span class="description text-sm text-danger mt-1">Score : {{$st->score}}</span>
                          </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="post">
                        <p class="text-bold text-danger">No Activity :</p>
                        @foreach ($notyet as $ny)
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $ny->student_profile)}}" alt="user image">
                            <span class="username">
                              <a class="text-danger text-sm" href="#">{{$ny->student_name}}</a>
                            </span>
                            <span class="description text-danger text-sm"> Score : {{$ny->score}}  </span>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                @endif
                
              @endif

            </div>

            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
              <h3 ><i class="fas fa-pencil"></i> Material</h3>
              <p class="text-muted">{{$data->materi}}</p>
              <br>
              <div class="text-muted">
                <p class="">Grade
                  <b class="d-block">{{$data->grade_name}} - {{$data->grade_class}}</b>
                </p>
                <p class="">Status
                  <b class="d-block">
                    @if($data->is_active)
                      <span class="badge badge-success">Active</span>
                      @if (\Carbon\Carbon::now()->gt($data->date_exam))
                        <span class="badge badge-warning">Deadline Today | You Must Input Score Students</span>
                      @endif
                    @else
                      <span class="badge badge-secondary">Completed</span>
                    @endif
                  </b>
                </p>
                @if ($data->hasFile == true)
                <p class="text-muted">Assessment files
                  <a href="{{ asset('storage/file/assessment/'.$data->file_name) }}" 
                    class="btn-link text-secondary d-block" 
                    title="download file"
                    download="{{ $data->file_name }}">
                      <i class="far fa-fw fa-file-pdf"></i> {{ $data->file_name }}
                  </a>
                </p>
                @endif
                <div class="row flex justify-content-center ">
                  <div class="col-md-12">
                    @if (session('role') == 'teacher')
                      @php
                        $idTeacher =  \App\Models\Teacher::where('user_id', session('id_user'))->value('id');
                        $gradeId = \App\Models\Grade::where('name', $data->grade_name)->where('class', $data->grade_class)->value('id');
                        $subjectTeacher = \App\Models\Exam::where('exams.id', $data->id)
                        ->leftJoin('subject_exams', 'subject_exams.exam_id', 'exams.id')
                        ->leftJoin('teacher_subjects', 'teacher_subjects.subject_id', 'subject_exams.subject_id')
                        ->where('teacher_subjects.grade_id', $gradeId)
                        ->select('teacher_subjects.teacher_id as id')
                        ->first();
                      @endphp

                      @if ($idTeacher == $subjectTeacher->id)
                        @if ($idTeacher == $data->teacher_id)
                          <a href="/teacher/dashboard/exam/score/{{ $data->id }}" class="btn btn-success btn-sm col-12">
                            @if ($data->model == "mce" || $data->model == "essay")
                              Correction Student Answers
                            @elseif ($data->model == "mc")
                              View Student Answers
                            @else
                              View Student Scores
                            @endif
                          </a>
                        @else
                          <a href="/teacher/dashboard/exam/score/{{ $data->id }}" class="btn btn-success btn-sm col-12">
                            @if ($data->model == "mce" || $data->model == "essay")
                              Correction Student Answers
                            @elseif ($data->model == "mc")
                              View Student Answers
                            @else
                              View Student Scores
                            @endif
                          </a>
                        @endif
                      @endif
                      
                      
                    @elseif (session('role') == 'admin' || session('role') == 'superadmin')
                      <a href="/exams/score/{{ $data->id }}" class="btn btn-success btn-sm col-12">See Score Student</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
    </div>
  </div>

</section>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#workplace').forEach(function(button) {
        button.addEventListener('click', function() {
              var assessmentId = {{$data->id}};
              var sessionRole = @json(session('role'));
              var url;
              if (sessionRole === "parent") {
                url = "{{ route('set.assessment.id') }}";
              } else if (sessionRole === "student") {
                url = "{{ route('set.assessment.id.student') }}";
              } else if (sessionRole === "teacher" || sessionRole === "admin" || sessionRole === "superadmin"){
                url = "{{ route('set.exam.id') }}";
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
                      window.location.href = '/view-assessment-work';
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
  });
</script>

@if(session('after_create_teacher')) 
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Successfully',
      text: 'Successfully registered the teacher in the database !!!',
    });
  </script>
@endif 


@if (session('after_update_teacher'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Successfully',
      text: 'Successfully updated the teacher in the database !!!',
    });
  </script>
@endif



@endsection