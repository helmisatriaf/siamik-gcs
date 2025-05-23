@extends('layouts.admin.master')
@section('content')

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 ">
                  <ol class="breadcrumb mb-0">
                     <li class="breadcrumb-item">Home</li>
                     <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/exams')}}">Scorings</a></li>
                     <li class="breadcrumb-item active" aria-current="page">Create</li>
                  </ol>
            </nav>
         </div>
      </div>

      <div class="row d-flex justify-content-center mt-3">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div>
               @if (session('role') == 'superadmin')
                  <form method="POST" action={{route('actionSuperCreateExam')}} enctype="multipart/form-data">
               @elseif (session('role') == 'admin')
                  <form method="POST" action={{route('actionAdminCreateExam')}} enctype="multipart/form-data">
               @endif
                  @csrf
                  <div class="card card-dark">
                     <div class="card-header">
                           <h3 class="card-title">Create Scoring</h3>
                     </div>
                     <!-- /.card-header -->
                     <!-- form start -->
                     <div class="card-body">
                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="name">Scoring Name <span style="color: red">*</span></label>
                              <input name="name" type="text" class="form-control" id="name"
                                    placeholder="Enter Scoring Name" value="{{ old('name') }}" autocomplete="off" required>
                              @if($errors->has('name'))
                                    <p style="color: red">{{ $errors->first('name') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="type_exam">Type <span style="color: red">*</span></label>
                              <select required name="type_exam" class="form-control" id="type_exam">
                                    <option selected disabled>--- SELECT TYPE ---</option>
                                    @foreach($data['type_exam'] as $el)
                                       <option value="{{ $el->id }}">{{ ucwords($el->name) }}</option>
                                    @endforeach
                              </select>
                              @if($errors->has('type_exam'))
                                    <p style="color: red">{{ $errors->first('type_exam') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-6">
                              <label for="grade_id">Grade <span style="color: red">*</span></label>
                              <select required name="grade_id" class="form-control" id="grade_id">
                                    <option selected disabled>--- SELECT GRADE ---</option>
                                    @foreach($data['grade'] as $el)
                                       <option value="{{ $el->id }}">{{ $el->name }} - {{ $el->class}}</option>
                                    @endforeach
                              </select>
                              @if($errors->has('grade_id'))
                                    <p style="color: red">{{ $errors->first('grade_id') }}</p>
                              @endif
                           </div>
                           <div class="col-md-6">
                              <label for="subject_id">Subject <span style="color: red">*</span></label>
                              <select required name="subject_id" class="form-control" id="subject_id">
                                    
                              </select>
                              @if($errors->has('subject_id'))
                                    <p style="color: red">{{ $errors->first('subject_id') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="teacher_id">Teacher <span style="color: red">*</span></label>
                              <select required name="teacher_id" class="form-control" id="teacher_id">
                                    
                              </select>
                              @if($errors->has('teacher_id'))
                                    <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="date_exam">Date <span style="color: red">*</span></label>
                              <input name="date_exam" type="date" class="form-control" id="date_exam" required>
                              
                              @if($errors->has('date_exam'))
                                 <p style="color: red">{{ $errors->first('exam') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="materi">Materi  <span style="color: red">*</span></label>
                              <textarea required name="materi" class="form-control" id="materi" cols="10" rows="3"></textarea>
                              
                              @if($errors->has('materi'))
                                 <p style="color: red">{{ $errors->first('materi') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-12">
                              <label for="switch">Assessment Paperless ?</label>
                              <input id="switch" type="checkbox" data-bootstrap-switch selected data-off-color="danger" data-on-color="success">
                           </div>
                        </div>

                        <div class="form-group row" id="model-form" style="display: none">
                           <div class="col-md-12">
                              <label for="model">Model <span style="color: red">*</span></label>
                              <select required name="model" class="form-control" id="model">
                                    <option selected disabled>--- SELECT MODEL ---</option>
                                    <option value="mce">Multiple Choice & Essai</option>
                                    <option value="uf">Upload File</option>
                              </select>
                              @if($errors->has('type_exam'))
                                    <p style="color: red">{{ $errors->first('type_exam') }}</p>
                              @endif
                           </div>
                        </div>

                        <div class="form-group row" id="file-form">
                           <div class="col-md-12">
                              <label for="upload_file">Upload File (Maks 1MB)<span style="color: red">*</span></label>
                              <input type="file" id="upload_file" name="upload_file" class="form-control" accept=".pdf" required>
                              <div class="text-danger mt-2" id="fileError" style="display: none;"></div>
                           </div>
                        </div>

                        {{-- <div class="form-group row">
                           <div class="col-md-12">
                              <label for="question"></label>
                           </div>
                        </div> --}}

                        <div class="row d-flex justify-content-center">
                           <input role="button" type="submit" class="btn btn-success center col-12">
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<script>
   var gradeSelect   = document.getElementById("grade_id");
   var subjectSelect = document.getElementById("subject_id");
   var teacherSelect = document.getElementById("teacher_id");
   var modelSelect   = document.getElementById("model");
   
   document.getElementById('grade_id').addEventListener('change', function() {
      loadSubjectOptionExam(this.value);
   });

   document.getElementById('subject_id').addEventListener('change', function() {
      loadTeacherOption(document.getElementById('grade_id').value, this.value);
   });

   document.getElementById('model').addEventListener('change', function() {
      if (this.value === 'uf') {
         document.getElementById('file-form').style.display = 'block';
      } else {
         document.getElementById('file-form').style.display = 'none';
      }
   });

   document.getElementById('switch').addEventListener('change', function() {
      if (this.checked) {
         document.getElementById('model-form').style.display = 'block';
      } else {
         document.getElementById('model-form').style.display = 'none';
         document.getElementById('file-form').style.display = 'none';
      }
   });

   function loadSubjectOptionExam(gradeId) {
   // Tambahkan opsi "-- SELECT SUBJECT --" sebagai opsi pertama
      subjectSelect.innerHTML = '<option value="" selected disabled>-- SELECT SUBJECT --</option>';

      fetch(`/get-subjects/${gradeId}`)
        .then(response => response.json())
        .then(data => {
            // Tambahkan opsi baru ke select subject
            if (data.length === 0) {
                // Jika data kosong, tambahkan opsi "Subject kosong"
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Subject Empty';
                subjectSelect.add(option);
            } else {
               data.forEach(subject => {
                  const option = document.createElement('option');
                  option.value = subject.id;
                  option.text = subject.name_subject;
                  subjectSelect.add(option);
               });
            }
        })
        .catch(error => console.error(error));
   }


   function loadTeacherOption(gradeId, subjectId) {
      teacherSelect.innerHTML = '<option value="" selected disabled>-- SELECT TEACHERS --</option>';
      
      fetch(`/get-teachers/${gradeId}/${subjectId}`)
         .then(response => response.json())
         .then(data => {
               if (data.length === 0) {
                  // Jika data kosong, tambahkan opsi "Teacher empty"
                  const option = document.createElement('option');
                  option.value = '';
                  option.text = 'Teacher Empty';
                  teacherSelect.add(option);
               } else {
                  data.forEach(teacher => {
                     const option = document.createElement('option');
                     option.value = teacher.id;
                     option.text = teacher.name;
                     teacherSelect.add(option);
                  });
               }
         })
         .catch(error => console.error(error));
   }

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
         } else {
               fileError.style.display = "none";
               submitBtn.disabled = false;
         }
      }
   });
</script>

@endsection
