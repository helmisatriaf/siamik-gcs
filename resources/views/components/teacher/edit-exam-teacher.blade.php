@extends('layouts.admin.master')
@section('content')

<section>
    {{-- <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/teacher')}}">Scorings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
            </nav>
        </div>
    </div> --}}

    <div class="row d-flex justify-content-center">
        <!-- general form elements -->
        <div class="col-md-12">
            <div>
                <form method="POST" action={{route('actionUpdateExamTeacher', $data['dataExam']->id)}}>
                    @csrf
                    @method('PUT')
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title text-bold text-white">Edit Assessment</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="name">Name<span style="color: red">*</span></label>
                                            <input name="name" type="text" class="form-control" id="name"
                                                    placeholder="Enter Assessment Name" value="{{ old('name') ? old('name') : $data['dataExam']->name_exam }}" autocomplete="off" required>
                                            @if($errors->has('name'))
                                                    <p style="color: red">{{ $errors->first('name') }}</p>
                                            @endif
                                        </div>
                                    </div>
    
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="type_exam">Type<span style="color: red">*</span></label>
                                            <select required name="type_exam" class="form-control" id="type_exam">
                                                    <option selected disabled>--- SELECT TYPE ASSESSMENT ---</option>
                                                    @foreach($data['typeExam'] as $el)
                                                    <option value="{{ $el->id }}" {{ $el->id == $data['dataExam']->type_exam_id ? 'selected' : '' }}>{{ $el->name }}</option>
                                                    @endforeach
                                            </select>
                                            @if($errors->has('type_exam'))
                                                    <p style="color: red">{{ $errors->first('type_exam') }}</p>
                                            @endif
                                        </div>
                                    </div>
    
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="grade_id">Grade<span style="color: red">*</span></label>
                                            <select required name="grade_id" class="form-control" id="grade_id">
                                                
                                            </select>
                                            @if($errors->has('grade_id'))
                                                <p style="color: red">{{ $errors->first('grade_id') }}</p>
                                            @endif
                                        </div>
    
                                        
                                        <div class="col-md-6">
                                            <label for="subject_id">Subject<span style="color: red">*</span></label>
                                            <select required name="subject_id" class="form-control" id="subject_id">
                                                    
                                            </select>
                                            @if($errors->has('subject_id'))
                                                    <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                            @endif
                                        </div>
                                    </div>
    
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="semester">Semester<span style="color: red">*</span></label>
                                            <select required name="semester" class="form-control" id="semester">
                                                    <option value="1" {{ $data['dataExam']['semester'] == 1 ? "selected" : "" }}>Semester 1</option>
                                                    <option value="2" {{ $data['dataExam']['semester'] == 2 ? "selected" : "" }}>Semester 2</option>
                                            </select>
                                            @if($errors->has('type_exam'))
                                                    <p style="color: red">{{ $errors->first('type_exam') }}</p>
                                            @endif
                                        </div>
                                    </div>
    
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="teacher_id">Teacher<span style="color: red">*</span></label>
                                            <select required name="teacher_id" class="form-control" id="teacher_id">
                                                    @foreach($data['teacher'] as $el)
                                                        <option value="{{ $el->id }}" {{ $el->id == $data['dataExam']->teacher_id ? 'selected' : '' }}>{{ $el->name }}</option>
                                                    @endforeach
                                            </select>
                                            @if($errors->has('teacher_id'))
                                                    <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                                            @endif
                                        </div>
                                    </div>
    
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="date_exam">Date<span style="color: red">*</span></label>
                                            <input name="date_exam" type="date" class="form-control" id="date_exam" value="{{ $data['dataExam']->date_exam }}" required>
                                            
                                            @if($errors->has('date_exam'))
                                            <p style="color: red">{{ $errors->first('date_exam') }}</p>
                                            @endif
                                        </div>
                                    </div>
    
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="materi">Materi<span style="color: red">*</span></label>
                                            <textarea required name="materi" class="form-control" id="materi" cols="10" rows="3">{{ $data['dataExam']->materi }}</textarea>
                                            
                                            @if($errors->has('materi'))
                                                <p style="color: red">{{ $errors->first('materi') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="materi">Question</label>
                                    @if ($data['dataExam']->model !== null)
                                        <div class="form-group">
                                            <a class="btn btn-app bg-warning" href="{{route('get.work.id', $data['dataExam']->id)}}">
                                                <i class="fas fa-edit"></i> Edit Question
                                            </a>
                                        </div>
                                    @elseif ($data['dataExam']->hasFile == 1)
                                        <div class="form-group row">
                                            <iframe src="{{ asset('storage/file/assessment/'.$data['dataExam']->file_name) }}" width="100%" height="500px"></iframe>
                                        </div>
                                        <div class="form-group row d-flex justify-content-end">
                                            <a href="#" 
                                                class="btn-link text-secondary text-danger hover:cursor-pointer" 
                                                data-toggle="modal" 
                                                data-target="#changeFile">
                                                <i class="fas fa-edit ml-1"></i> Change File
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <input role="button" type="submit" class="btn btn-success center col-12">
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="changeFile" tabindex="-1" role="dialog" aria-labelledby="changeFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('change.file.exam')}}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="form-group row text-muted" id="file-form">
                        <label for="upload_file">Upload File (Maks 1MB) <span style="color: red">*</span></label>
                        <input type="file" id="upload_file" name="upload_file" class="form-control" accept=".pdf" required>
                        <input type="number" id="exam_id" name="exam_id" class="form-control" value="{{$data['dataExam']->id}}" hidden>
                        <div class="text-danger mt-2" id="fileError" style="display: none;"></div>
                    </div>      
                </div>
            <div class="modal-footer">
                <div class="form-group row">
                    <button type="submit" class="btn btn-sm btn-danger w-100" id="submitBtn">Confirm</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var gradeSelect   = document.getElementById("grade_id");
    var subjectSelect = document.getElementById("subject_id");
    var teacherSelect = document.getElementById("teacher_id");

    document.getElementById('teacher_id').addEventListener('change', function() {
        loadGradeOption(this.value)
            .then(() => loadSubjectOption(gradeSelect.value, this.value));
    });

    document.getElementById('grade_id').addEventListener('change', function() {
        loadSubjectOption(this.value, document.getElementById('teacher_id').value )
    });

    function loadGradeOption(teacherId) {
        gradeSelect.innerHTML = `<option value="" selected disabled>-- SELECT GRADE --</option>`;

        return fetch(`/get-grades/${teacherId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    // Jika data kosong, tambahkan opsi "Grade Empty"
                    const option = document.createElement('option');
                    option.value = '';
                    option.text = 'Grade Empty';
                    gradeSelect.add(option);
                } else {
                    data.forEach(grade => {
                        const option = document.createElement('option');
                        option.value = grade.id;
                        option.text = grade.name + ' - ' + grade.class;
                        if (grade.id == {{ $data['dataExam']->grade_id }}) {
                            option.selected = true;
                        }
                        gradeSelect.add(option);
                    });
                }
            })
            .catch(error => console.error(error));
    }

    function loadSubjectOption(gradeId, teacherId) {
        if (!gradeId) {
            return;
        }

        subjectSelect.innerHTML = `<option value="" selected disabled>-- SELECT SUBJECT --</option>`;

        fetch(`/get-subjects/${gradeId}/${teacherId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    // Jika data kosong, tambahkan opsi "Subject Empty"
                    const option = document.createElement('option');
                    option.value = '';
                    option.text = 'Subject Empty';
                    subjectSelect.add(option);
                } else {
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.text = subject.name_subject;
                        if (subject.id == {{ $data['dataExam']->subject_id }}) {
                            option.selected = true;
                        }
                        subjectSelect.add(option);
                    });
                }
            })
            .catch(error => console.error(error));
    }

    window.onload = function() {
        loadGradeOption(document.getElementById('teacher_id').value)
            .then(() => loadSubjectOption(document.getElementById('grade_id').value, document.getElementById('teacher_id').value));
    };

    document.getElementById("upload_file").addEventListener("change", function () {
      const file = this.files[0];
      const fileError = document.getElementById("fileError");
      const submitBtn = document.getElementById("submitBtn");

      //console.log(file);
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
                html: `Ukuran file terlalu besar! Maksimal 1MB.<br><br>
                      <strong>Silakan kompres file Anda di:</strong> <br>
                      <a href="https://www.ilovepdf.com/compress_pdf" target="_blank" style="color: #3085d6; text-decoration: underline;">
                          iLovePDF - Compress PDF
                      </a>`,
                confirmButtonText: 'Oke, Saya Mengerti',
            });
         } else {
            fileError.style.display = "none";
            submitBtn.disabled = false;
         }
      }
    });
</script>


<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
@if(session('after_change_file')) 
    <script>
        Swal.fire({
        icon: 'success',
        title: 'Successfully',
        text: 'Successfully Change File',
        });
    </script>
@endif
@endsection
