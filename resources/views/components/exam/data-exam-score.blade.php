@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">   
    @if(count($data) !== 0)
        <div class="row">
            <div class="col">
                 <nav aria-label="breadcrumb" class="p-3 mb-4" style="background-color: #ffde9e;border-radius: 12px;">
                    <ol class="breadcrumb mb-0" style="background-color: #fff3c0;">
                        <li class="breadcrumb-item">Home</li>
                        @if(session('role') == 'admin' || session('role') == 'superadmin')
                        @else
                        <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/detail/'. $data[0]->exam_id )}}">Assessment</a></li>
                        {{-- <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/teacher')}}">Scoring</a></li> --}}
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">Scoring {{ $data[0]['exam_name'] }} {{ $data[0]['subject_name'] }} ({{ $data[0]['grade_name'] }} - {{ $data[0]['grade_class'] }})</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card"  style="background-color: #ffde9e;border-radius: 12px;">
            <div class="card-header">
                <h3 class="card-title">Scorings</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if (session('role') == 'superadmin' || session('role') == 'admin')
                    <form method="POST" action="{{ route('actionUpdateScoreExam') }}" enctype="multipart/form-data">
                @else
                    <form method="POST" action="{{ route('actionUpdateScoreExamTeacher') }}" enctype="multipart/form-data">
                @endif
                    @csrf
                    @method('PUT')
                    <table class="table table-striped projects">
                        <tbody>
                            @foreach ($data as $el)
                            <tr id="{{'index_grade_' . $el->id}}">
                                <td style="width: 65%">
                                    @php
                                        $ext = pathinfo($el->file_name, PATHINFO_EXTENSION);
                                    @endphp

                                    @if ($el->file_name !== null)
                                        <a href="{{ asset('storage/file/answers/'.$el->file_name) }}" 
                                            class="btn-link text-secondary d-block" 
                                            title="download file"
                                            download="{{ $el->file_name }}">
                                            <i class="far fa-fw fa-file-pdf"></i>Download Answer
                                        </a>
                                        @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic']))
                                            <img src="{{ asset('storage/file/answers/'.$el->file_name) }}" class="img-fluid">
                                        @elseif (strtolower($ext) === 'pdf')
                                            {{-- <a href="{{ asset('storage/file/answers/'.$el->file_name) }}" target="_blank">Lihat PDF</a> --}}
                                            <iframe src="{{ asset('storage/file/answers/'.$el->file_name) }}" width="100%" height="500px"></iframe>
                                        @endif
                                        {{-- <img src="{{ asset('storage/file/answers/'.$el->file_name) }}" class="img-fluid" alt=""> --}}
                                    @else
                                        <p class="text-center text-danger">Student has not submitted the answer yet.</p>
                                    @endif
                                </td>
                                <td class="">
                                    @if ($el->late !== NULL)
                                        <span class="badge badge-warning">Late : {{$el->late}}</span>
                                    @endif
                                    <p class="text-sm">
                                        {{ $loop->index + 1 }}. {{ $el->student_name }}
                                    </p>
                                    <div class="input-group">
                                        <input name="exam_id" type="text" class="form-control d-none" id="exam_id-{{$el->student_id}}" value="{{ $el->exam_id }}">
                                        <input name="subject_id" type="text" class="form-control d-none" id="subject_id-{{$el->student_id}}" value="{{ $el->subject_id }}">
                                        <input name="grade_id" type="text" class="form-control d-none" id="grade_id-{{$el->student_id}}" value="{{ $el->grade_id }}">
                                        <input name="teacher_id" type="text" class="form-control d-none" id="teacher_id-{{$el->student_id}}" value="{{ $el->teacher_id }}">
                                        <input name="type_exam_id" type="text" class="form-control d-none" id="type_exam_id-{{$el->student_id}}" value="{{ $el->type_exam_id }}">
                                        <input name="student_id[]" type="text" class="form-control d-none" id="student_id-{{$el->student_id}}" value="{{ $el->student_id }}">
                                        <input name="score[]" type="number" class="form-control score-input" id="score-{{$el->student_id}}" placeholder="Score" value="{{ old('score', $el->score) }}" autocomplete="off" min="0" max="100" required>
                                    </div>
                                    
                                    <p class="mt-3 text-sm">
                                        Comment <br> <span class="text-danger text-xs">**(Boleh diisi Apresiasi/Pembenaran jawaban)</span>
                                    </p>
                                    
                                    <nav>
                                        <div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
                                            @if ($el->justification == NULL && $el->justification_file == NULL)
                                                <a id="commentText" class="nav-item nav-link active text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" onclick="toggleInput('text', {{$el->student_id}})">Text</a>
                                                <a id="commentUploadFile" class="nav-item nav-link text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" onclick="toggleInput('file', {{$el->student_id}})">Upload File</a>
                                            @else
                                                @if ($el->justification !== NULL)
                                                    <a id="commentText" class="nav-item nav-link active text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" onclick="toggleInput('text', {{$el->student_id}})">Text</a>
                                                    <a id="commentUploadFile" class="nav-item nav-link   text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" onclick="toggleInput('file', {{$el->student_id}})">Upload File</a>
                                                    @elseif($el->justification_file !== NULL)
                                                    <a id="commentText" class="nav-item nav-link text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" onclick="toggleInput('text', {{$el->student_id}})">Text</a>
                                                    <a id="commentUploadFile" class="nav-item nav-link active text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" onclick="toggleInput('file', {{$el->student_id}})">Upload File</a>
                                                @else
                                                    <a id="commentText" class="nav-item nav-link active text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" onclick="toggleInput('text', {{$el->student_id}})">Text</a>
                                                    <a id="commentUploadFile" class="nav-item nav-link text-[8px] md:text-[12px] lg:text-[14px] xl:text-[16px]" onclick="toggleInput('file', {{$el->student_id}})">Upload File</a>
                                                @endif
                                            @endif
                                        </div>
                                    </nav>


                                    @if ($el->justification == NULL && $el->justification_file == NULL)
                                        <div id="text-input-{{$el->student_id}}">
                                            <textarea name="justification[]" id="justification-{{$el->student_id}}" class="summernote">{{$el->justification}}</textarea>
                                        </div>

                                        <!-- Konten upload file -->
                                        <div id="file-input-{{$el->student_id}}" style="display: none;">
                                            <input type="file" name="upload_file_justification[{{$el->student_id}}]" accept=".pdf, .png, .jpg, .jpeg, .heic">
                                        </div>
                                    @else
                                        @if ($el->justification !== NULL)
                                            <div id="text-input-{{$el->student_id}}">
                                                <textarea name="justification[]" id="justification-{{$el->student_id}}" class="summernote">{{$el->justification}}</textarea>
                                            </div>

                                            <!-- Konten upload file -->
                                            <div id="file-input-{{$el->student_id}}" style="display: none;">
                                                <input type="file" name="upload_file_justification[{{$el->student_id}}]" accept=".pdf, .png, .jpg, .jpeg, .heic">
                                            </div>
                                        @elseif($el->justification_file !== NULL)
                                             <div id="text-input-{{$el->student_id}}" style="display: none;">
                                                <textarea name="justification[]" id="justification-{{$el->student_id}}" class="summernote">{{$el->justification}}</textarea>
                                            </div>

                                            <!-- Konten upload file -->
                                            <div id="file-input-{{$el->student_id}}">
                                                <a href="{{ asset('storage/file/correction/'.$el->justification_file) }}" 
                                                    class="btn-link text-secondary d-block" 
                                                    target="_blank" 
                                                    rel="noopener noreferrer">
                                                    <i class="fas fa-link mr-1"></i> See Correction
                                                </a>
                                                <input type="file" name="upload_file_justification[{{$el->student_id}}]" accept=".pdf, .png, .jpg, .jpeg, .heic">
                                            </div>
                                        @else
                                            <div id="text-input-{{$el->student_id}}">
                                                <textarea name="justification[]" id="justification-{{$el->student_id}}" class="summernote">{{$el->justification}}</textarea>
                                            </div>

                                            <!-- Konten upload file -->
                                            <div id="file-input-{{$el->student_id}}" style="display: none;">
                                                <input type="file" name="upload_file_justification[{{$el->student_id}}]" accept=".pdf, .png, .jpg, .jpeg, .heic">
                                            </div>
                                        @endif
                                    @endif

                                    @if($errors->has('score'))
                                    <p style="color: red">{{ $errors->first('score') }}</p>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right" name="update_all">Update Scores</button>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    @else
        <p>Kosong</p>
    @endif
</div>

<link rel="stylesheet" href="{{ asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scoreInputs = document.querySelectorAll('.score-input');

        scoreInputs.forEach(function(scoreInput) {

            // Prevent non-numeric characters from being entered
            scoreInput.addEventListener('keypress', function(event) {
                let charCode = event.which ? event.which : event.keyCode;
                if (charCode < 48 || charCode > 57) {
                    event.preventDefault(); // Block any input that isn't a number (0-9)
                }
            });

            // Validate input on the fly
            scoreInput.addEventListener('input', function() {
                let value = parseInt(this.value, 10);

                if (value > 100) {
                    this.value = 100;
                } else if (value < 0) {
                    this.value = 0;
                }
            });

            // Ensure the value stays within the range on blur (when the input loses focus)
            scoreInput.addEventListener('blur', function() {
                let value = parseInt(this.value, 10);

                if (isNaN(value) || value > 100) {
                    this.value = 100;
                } else if (value < 0) {
                    this.value = 0;
                }
            });

        });
    });
</script>

<script>
    function toggleInput(type, studentId) {
        const textTab = document.getElementById("commentText");
        const fileTab = document.getElementById("commentUploadFile");

        const textInput = document.getElementById("text-input-" + studentId);
        const fileInput = document.getElementById("file-input-" + studentId);

        if (type === 'text') {
            textInput.style.display = 'block';
            fileInput.style.display = 'none';
            textTab.classList.add("active");
            fileTab.classList.remove("active");
        } else {
            textInput.style.display = 'none';
            fileInput.style.display = 'block';
            textTab.classList.remove("active");
            fileTab.classList.add("active");
        }
    }
</script>



@if(session('after_create_score'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new score in the database.'
        });
    </script>
@endif

@if(session('after_update_score'))
    <script>
        Swal.fire({
            title: 'Successfully update score',
            showConfirmButton: false, // Sembunyikan tombol "OK",
            timer: 1800, // Swal akan hilang dalam 2000ms (2 detik)
            showConfirmButton: false, // Sembunyikan tombol "OK",
            imageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
        });
    </script>
@endif

<script src={{asset('assets/vendors/summernote/summernote-lite.min.js')}}></script>

<script>
    $('.summernote').summernote({
        tabsize: 2,
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['insert', ['picture']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                for (let i = 0; i < files.length; i++) {
                    uploadImage(files[i], this);
                }
            }
        }
    });

    $("#hint").summernote({
        height: 100,
        toolbar: false,
        placeholder: 'type with apple, orange, watermelon and lemon',
        hint: {
        words: ['apple', 'orange', 'watermelon', 'lemon'],
        match: /\b(\w{1,})$/,
            search: function(keyword, callback) {
                callback($.grep(this.words, function(item) {
                    return item.indexOf(keyword) === 0;
                }));
            }
        }
    });
    function uploadImage(file, editor) {
      let data = new FormData();
      data.append("file", file);
      data.append("_token", $('meta[name="csrf-token"]').attr('content'));

      $.ajax({
         url: '/upload-image-question',
         method: "POST",
         data: data,
         contentType: false,
         cache: false,
         processData: false,
         success: function(resp) {
            $(editor).summernote('insertImage', resp.link);
         },
         error: function(err) {
            alert("Upload gagal");
            console.error(err);
         }
      });
   }
</script>

@endsection
