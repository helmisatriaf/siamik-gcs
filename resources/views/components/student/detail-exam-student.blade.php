@extends('layouts.admin.master')
@section('content')

@php
  $name = session('name_user');
  $currentDate = now(); // Tanggal saat ini
  $dateExam = $data->date_exam; // Tanggal ujian dari data

  // Buat objek DateTime dari tanggal saat ini dan tanggal ujian
  $currentDateTime = new DateTime($currentDate);
  $dateExamDateTime = new DateTime($dateExam);

  // Hitung selisih antara kedua tanggal
  $interval = $currentDateTime->diff($dateExamDateTime);

  // Ambil jumlah hari dari selisih tersebut
  $days = $interval->days;

  // Jika tanggal ujian lebih kecil dari tanggal saat ini, buat selisih menjadi negatif
  if ($dateExamDateTime < $currentDateTime) {
      $days = -$days;
  } else if ($dateExamDateTime > $currentDateTime && $days == 0) {
      // Jika tanggal ujian di masa depan dan selisih kurang dari 1 hari, anggap 1 hari
      $days = 1;
  }
@endphp

<div class="container-fluid">
  <div class="row">
    <div class="col">
      <nav aria-label="breadcrumb" class="p-3 mb-4" style="background-color: #ffde9e;border-radius: 12px;">
        <ol class="breadcrumb mb-0" style="background-color: #fff3c0;">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item"><a href="{{ url('/' . session('role') . '/dashboard/exam') }}">Assessment</a></li>
          <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="content">
    <div class="card" style="background-color: #ffde9e;border-radius: 12px;">
      <div class="pl-3 pt-3">
        <h3 class="card-title text-dark text-bold">Assessment Detail</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
            <div class="row">
              <div class="col-12 col-sm-4">
                <div class="info-box bg-light" style="border-radius: 12px;">
                  <div class="info-box-content">
                    <span class="info-box-text text-center text-black text-bold">{{ucwords(strtolower($data->name_exam))}}</span>
                    {{-- <span class="info-box-number text-center text-muted mb-0">2300</span> --}}
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-4">
                <div class="info-box bg-light" style="border-radius: 12px;">
                  <div class="info-box-content">
                    <span class="info-box-text text-center text-black text-bold">{{$data->type_exam}} - {{$data->subject_name}}</span>
                    {{-- <span class="info-box-number text-center text-muted mb-0">2000</span> --}}
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-4">
                <div class="info-box bg-light" style="border-radius: 12px;">
                  <div class="info-box-content">
                    <span class="info-box-text text-center text-black text-bold">Deadline {{ \Carbon\Carbon::parse($data->date_exam)->translatedFormat('d F Y') }} </span>
                    {{-- <span class="info-box-number text-center text-muted mb-0">20</span> --}}
                  </div>
                </div>
              </div>
            </div>
            
            {{-- KONTEN QUESTION --}}
            {{-- ASSESSMENT FILE PDF --}}
              @if ($data->hasFile == true)
                <div class="row">
                  <div class="col-12">
                    <p class="text-bold">Question :</p> 
                    {{-- <div id="pdf-container" style="width: 100%; max-width: 100vw; overflow-x: auto;">
                    </div> --}}
                    <div class="col-12">
                      <iframe src="{{ asset('storage/file/assessment/'.$data->file_name) }}#toolbar=0 " width="100%" height="500px"></iframe>
                    </div>
                   </div>
                </div>  
              @else
              
                {{-- ASSESSMENT MULTIPLE CHOICE DAN ESSAY --}}
                @if($data->model !== null)
                  <div class="row flex-column">
                  @if ($statusQuestion !== null)
                    @if ($data->model !== null)
                      <div class="col-12 col-md-4">
                          <div class="info-box small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="border-radius: 12px;background-color: #ffcc00;">
                              <a 
                                  href="#"
                                  id="detail-workplace"
                                  class="stretched-link d-flex flex-column text-center justify-content-center align-items-center">
                              
                                  <!-- Ribbon -->
                                  <div class="ribbon-wrapper ribbon-lg">
                                      <div class="ribbon bg-dark">
                                        CBT
                                      </div>
                                  </div>
                              
                                  <!-- Bagian Utama -->
                                  <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                      <!-- Ikon -->
                                      <div>
                                          <img loading="lazy" src="{{ asset('images/greta-greti-baju-olga.png') }}" 
                                            alt="avatar" class="profileImage img-fluid" 
                                            style="width:85px; height: 60px; cursor: pointer;">
                                      </div>
                                      <!-- Nama Subject -->
                                      <div class="inner">
                                          <p class="info-box-text text-center text-white text-bold">Click Me !</p>
                                      </div>
                                  </div>
                              </a>
                          </div>       
                      </div>
      
                      <div class="post col-12">
                        <p>Recent Activity</p>
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $profile)}}" alt="user image">
                          <span class="username">
                            <a class="text-muted" href="#">{{$name}}</a>
                          </span>
                          <span class="description">Collection time - {{ \Carbon\Carbon::parse($statusQuestion->created_at)->format('d M Y H:i') }}</span>
                        </div>
                      </div>
                    @endif
                  @else 
                    <div class="col-12 col-md-4">
                      <div class="info-box small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="border-radius: 12px;background-color: #ffcc00;">
                          <a 
                              href="#"
                              id="workplace"
                              class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                          
                              <!-- Ribbon -->
                              <div class="ribbon-wrapper ribbon-lg">
                                  <div class="ribbon bg-dark">
                                    CBT
                                  </div>
                              </div>
                          
                              <!-- Bagian Utama -->
                              <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                  <!-- Ikon -->
                                  <div>
                                      <img loading="lazy" src="{{ asset('images/greta-greti-baju-olga.png') }}" 
                                        alt="avatar" class="profileImage img-fluid" 
                                        style="width:85px; height: 60px; cursor: pointer;">
                                  </div>
                                  <!-- Nama Subject -->
                                  <div class="inner mt-2">
                                      <p class="info-box-text text-center text-lg text-dark text-bold">Click Me !</p>
                                  </div>
                              </div>
                          </a>
                      </div>       
                    </div>
                  @endif
                  </div>  
                @endif
                {{-- END  --}}
              
              @endif
            {{-- END  --}}
          </div>

          {{-- RIGHT --}}
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
                    <span class="badge badge-success">Ongoing</span>
                    @if (\Carbon\Carbon::now()->gt($data->date_exam))
                      <span class="badge badge-warning">Today</span>
                    @endif
                  @else
                    <span class="badge badge-light text-sm">Completed</span>
                  @endif
                </b>
              </p>
              @if ($data->hasFile == true)
              <p class="text-muted">Assessment File
                {{-- <a href="{{ asset('storage/file/assessment/'.$data->file_name) }}" 
                  class="btn-link text-secondary d-block" 
                  title="download file"
                  download="{{ $data->file_name }}">
                    <i class="far fa-fw fa-file-pdf"></i> {{ $data->file_name }}
                </a> --}}
                <a href="{{ route('download.watermark', ['fileName' => $data->file_name]) }}" 
                  class="btn-link text-secondary d-block" 
                  title="Download file dengan watermark">
                  <i class="far fa-fw fa-file-pdf"></i> {{ $data->file_name }}
                </a>
              </p>
              @endif
              <p class="text-muted">Score  
                <span class="text-bold  text-danger">
                  {{ $getStatus->score }}
                </span>
              </p>
            </div>


            @if (session('role') == 'student')
              @if ($data->hasFile == true)
                @if (date('Y-m-d') > $data->date_exam)
                  {{-- <p class="text-danger">Oops.. deadline has passed</p> --}}
                @else
                  @if($status == false)
                    <form action="{{route('upload.answer')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group row text-muted" id="file-form">
                        <label for="upload_file">Upload Your Answer File (Maks 1MB) <span style="color: red">*</span></label>
                        <input type="file" id="upload_file" name="upload_file" class="" accept=".pdf" required>
                        <div class="text-danger mt-2" id="fileError" style="display: none;"></div>
                      </div>      
                      <div class="form-group row">
                        <button type="submit" class="btn btn-sm btn-success w-100" id="submitBtn">Submit</button>
                      </div>
                    </form>
                  @endif
                @endif
              @endif
            @endif

            
            @if ($data->hasFile == true)
              @if ($status == true)
                <div class="post">
                  <p>Recent Activity</p>
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="{{asset('storage/file/profile/'. $profile)}}" alt="user image">
                    <span class="username">
                      <a class="text-muted" href="#">{{$name}}</a>
                    </span>
                    <span class="description">Upload at - {{ \Carbon\Carbon::parse($getStatus->time_upload)->format('d M Y H:i') }}</span>
                  </div>
                  
                  <div class="col d-flex flex-column align-items-start text-left">
                    <div>
                      <a href="{{ asset('storage/file/answers/'.$getStatus->file_name) }}"  
                        class="btn-link text-secondary d-block text-xs" 
                        target="_blank" 
                        title="See Your Answer"
                        rel="noopener noreferrer">
                        <i class="fas fa-file ml-1"></i> {{ $getStatus->file_name }}
                      </a>
                    </div>
                
                    @if (session('role') !== 'parent')
                      @if ($data->is_active)
                        <div class="mt-1">
                          <a href="#" 
                            class="btn-link text-danger d-block text-xs hover:cursor-pointer" 
                            data-toggle="modal" 
                            data-target="#changeFile">
                            <i class="fas fa-edit ml-1"></i> Change Answer
                          </a>
                        </div>              
                      @endif
                    @endif
                  </div>                
                </div>
              @endif
            @endif


          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
  </section>
</div>

@if (session('role') == 'student')
  <div class="modal fade" id="changeFile" tabindex="-1" role="dialog" aria-labelledby="changeFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-sm" style="border-radius: 12px;background-color: #ffde9e;">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Change Your Answer</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{route('upload.answer')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="upload_file">Upload Your Answer File (Maks 1MB) <span style="color: red">*</span></label>
                <div class="form-group row text-muted" id="file-form">
                  <input type="file" id="upload_file" name="upload_file" class="" accept=".pdf" required>
                  <div class="text-danger mt-2" id="fileError" style="display: none;"></div>
                </div>      
              </div>
            <div class="modal-footer">
              <div class="form-group row">
                <button type="submit" class="btn btn-sm btn-success w-100" id="submitBtn">Submit</button>
              </div>
              </form>
            </div>
        </div>
    </div>
  </div>

  <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

  <script>
    document.getElementById("upload_file").addEventListener("change", function () {
      const file = this.files[0];
      const fileError = document.getElementById("fileError");
      const submitBtn = document.getElementById("submitBtn");
      const allowedExtensions = /(\.pdf)$/i;
      const fileExtension = file.name.split('.').pop();

      if(fileExtension !== 'pdf') {
        fileError.textContent = "Format file must be pdf !";
        fileError.style.display = "block";
        this.value = "";
        submitBtn.disabled = true;
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Format file must be pdf !',
        });
      } else {
        if (file) {
            const fileSize = file.size;
            if (fileSize > 1048576) { // 1MB = 1048576 bytes
              fileError.textContent = "Ukuran file terlalu besar! Maksimal 1MB.";
              fileError.style.display = "block";
              this.value = "";
              submitBtn.disabled = true;
              Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  html: `Too Much! Maksimum Size 1MB.<br><br>
                        <strong>Please compress your file in:</strong> <br>
                        <a href="https://www.ilovepdf.com/compress_pdf" target="_blank" style="color: #3085d6; text-decoration: underline;">
                            iLovePDF - Compress PDF
                        </a>`,
                  confirmButtonText: 'Oke',
              });
            } else {
              fileError.style.display = "none";
              submitBtn.disabled = false;
            }
        }
      }
    });
  </script>
@endif

@if (session('role') == 'student' || session('role') == 'parent')
  @if($data->hasFile == true)
    <script>
      const url = "{{ asset('storage/file/assessment/'.$data->file_name) }}";

      const pdfjsLib = window['pdfjs-dist/build/pdf'];
      pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.12.313/pdf.worker.min.js';

      const container = document.getElementById('pdf-container');

      pdfjsLib.getDocument(url).promise.then(pdf => {
          for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
              pdf.getPage(pageNum).then(page => {
                  const scale = 1.5;
                  const viewport = page.getViewport({ scale: scale });

                  // Buat elemen div pembungkus untuk watermark
                  const pageContainer = document.createElement('div');
                  pageContainer.classList.add('page-container');
                  pageContainer.style.position = 'relative';

                  // Buat canvas untuk menampilkan halaman PDF
                  const canvas = document.createElement('canvas');
                  const context = canvas.getContext('2d');
                  canvas.height = viewport.height;
                  canvas.width = viewport.width;

                  pageContainer.appendChild(canvas);

                  // Buat elemen watermark
                  const watermark = document.createElement('div');
                  watermark.classList.add('watermark');
                  watermark.innerText = "Document Great Crystal School";
                  watermark.style.position = "absolute";
                  watermark.style.top = "50%";
                  watermark.style.left = "50%";
                  watermark.style.transform = "translate(-50%, -50%)";
                  pageContainer.appendChild(watermark);

                  container.appendChild(pageContainer);

                  const renderContext = { canvasContext: context, viewport: viewport };
                  page.render(renderContext);
              });
          }
      });
    </script>
  @endif
@endif

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
                      window.location.href = '/assessment-work';
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

    document.querySelectorAll('#detail-workplace').forEach(function(button) {
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

@if(session('after_upload_answer')) 
  <script>
    Swal.fire({
      title: 'Successfully',
      text: 'Successfully Upload Answer',
      timer: 1000,
      showConfirmButton: false,
      imageUrl: '/images/happy.png', 
      imageWidth: 100,
      imageHeight: 100,
      imageAlt: 'Custom image',
      customClass: {
        popup: 'custom-swal-style'
      },
    });
</script>
@endif

@if(session('error_handling_file_pdf')) 
  <script>
    Swal.fire({
      icon: 'error',
      title: 'error',
      html: `Sorry, the type of pdf you uploaded is not supported by our system.<br><br>
      <strong>Please compress your file in:</strong> <br>
      <a href="https://www.ilovepdf.com/compress_pdf" target="_blank" style="color: #3085d6; text-decoration: underline;">
          iLovePDF - Compress PDF
      </a>`,
      showConfirmButton: true // Sembunyikan tombol "OK",
    });
</script>
@endif

@endsection