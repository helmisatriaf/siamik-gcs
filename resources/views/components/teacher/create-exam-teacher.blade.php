@extends('layouts.admin.master')
@section('content')

<style>
   .modal-header .close {
      padding: 0;
      margin: 0;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background-color: #f1f3f5;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
   }

   .modal-header .close:hover {
      background-color: #e9ecef;
      transform: rotate(90deg);
   }

   .modal-header .close i {
      font-size: 16px;
      color: #6c757d;
   }
</style>

<section class="content">
   <div class="container-fluid">
      {{-- <div class="row">
         <div class="col">
            <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-3">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/exam/teacher')}}">Assessment</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Create</li>
               </ol>
            </nav>
         </div>
      </div> --}}

      <div class="row d-flex justify-content-center">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div>
               <form method="POST" action="{{ route('actionCreateExamTeacher') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="card mb-2" style="background-color: #ffde9e;border-radius: 12px;">
                     <div class="card-header">
                        @php
                           $week = \App\Models\Section::where('sections.id', session('section_id'))
                           ->join('grade_subjects', 'sections.grade_subject_id', '=', 'grade_subjects.id')
                           ->value('sections.title');
                        @endphp
                           <h3 class="card-title">New Assessment {{$week}}</h3>
                     </div>
                     <!-- /.card-header -->
                     <!-- form start -->
                     <div class="card-body">
                        <div class="d-flex">
                           <div class="col-6">
                              <div class="form-group row">
                                 <div class="col-md-6">
                                    @php
                                       $gradeId = null;
                                       if (session('grade_subject_id')) {
                                          $gradeId = \App\Models\Grade_subject::where('id', session('grade_subject_id'))
                                          ->value('grade_id');
                                       }
                                    @endphp

                                    <label for="grade_id">Grade <span class="text-danger">*</span></label>
                                    <select required name="grade_id" class="form-control" id="grade_id">
                                       <option selected disabled>--- SELECT GRADE ---</option>
                                       @foreach($data['grade'] as $el)
                                          <option value="{{ $el->id }}" {{ $gradeId == $el->id ? 'selected' : '' }}>
                                                {{ $el->name }} - {{ $el->class }}
                                          </option>
                                       @endforeach
                                    </select>

                                    @if($errors->has('grade_id'))
                                          <p style="color: red">{{ $errors->first('grade_id') }}</p>
                                    @endif
                                 </div>
                                 <div class="col-md-6">
                                    @php
                                       $subjectId = null;
                                       if (session('grade_subject_id')) {
                                          $subjectId = \App\Models\Grade_subject::where('id', session('grade_subject_id'))
                                             ->value('subject_id');
                                       }
                                    @endphp
                                    <label for="subject_id">Subject<span style="color: red"> *</span></label>
                                    <select required name="subject_id" class="form-control" id="subject_id">
                                    </select>
                                    @if($errors->has('subject_id'))
                                          <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                    @endif
                                 </div>
                              </div>
      
                              <div class="form-group row">
                                 <div class="col-md-12">
                                    <label for="type_exam">Type <span style="color: red">*</span></label>
                                    <select name="type_exam" class="form-control" id="type_exam" required>
                                          <option selected disabled value="">--- SELECT TYPE SCORING ---</option>
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
                                 <div class="col-md-12">
                                    <label for="name">Name <span style="color: red">*</span></label>
                                    <input name="name" type="text" class="form-control" id="name"
                                          placeholder="Enter Scoring Name" value="{{ old('name') }}" autocomplete="off" required>
                                    @if($errors->has('name'))
                                          <p style="color: red">{{ $errors->first('name') }}</p>
                                    @endif
                                 </div>
                              </div>
      
                              <div class="form-group row d-none">
                                 <div class="col-md-12">
                                    <label for="teacher_id">Teacher<span style="color: red">*</span></label>
                                    <select required name="teacher_id" class="form-control" id="teacher_id">
                                          @foreach($data['teacher'] as $el)
                                             <option value="{{ $el->id}}" selected>{{ $el->name }}</option>
                                          @endforeach
                                    </select>
                                    @if($errors->has('teacher_id'))
                                          <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                                    @endif
                                 </div>
                              </div>
      
                              <div class="form-group row">
                                 <div class="col-md-12">
                                    <label for="date_exam">Deadline <span style="color: red">*</span></label>
                                    <input name="date_exam" type="date" class="form-control" id="date_exam" required>
                                    
                                    @if($errors->has('date_exam'))
                                       <p style="color: red">{{ $errors->first('exam') }}</p>
                                    @endif
                                 </div>
                              </div>
      
                              <div class="form-group row">
                                 <div class="col-md-12">
                                    <label for="materi">Materi <span style="color: red">*</span></label>
                                    <textarea required name="materi" class="form-control" id="materi" cols="10" rows="3"></textarea>
                                    
                                    @if($errors->has('materi'))
                                       <p style="color: red">{{ $errors->first('materi') }}</p>
                                    @endif
                                 </div>
                              </div>
                           </div>
                           <div class="col-6">
                              <div class="form-group row" id="model-form">
                                 <div class="col-md-12">
                                    <label for="model">Model <span style="color: red">*</span></label>
                                    <select name="model" class="form-control" id="model">
                                          <option selected disabled value="">--- SELECT MODEL ---</option>
                                          <option value="mce">Multiple Choice & Essay</option>
                                          <option value="mc">Multiple Choice</option>
                                          <option value="essay">Essay</option>
                                          <option value="uf">Upload File</option>
                                          <option value="">Scoring</option>
                                    </select>
                                    @if($errors->has('type_exam'))
                                          <p style="color: red">{{ $errors->first('type_exam') }}</p>
                                    @endif
                                 </div>
                              </div>
      
                              <div class="form-group row" id="file-form" style="display: none;">
                                 <div class="col-md-12">
                                    <label for="upload_file">Upload File (Maks 1MB)<span style="color: red">*</span></label><br>
                                    <input type="file" id="upload_file" name="upload_file" accept=".pdf">
                                    <div class="text-danger mt-2" id="fileError" style="display: none;"></div>
                                 </div>
                              </div>

                              <div class="div p-0" id="combine" style="display: none;">
                                 <div class="d-flex p-0">
                                    <div class="col-6 p-0">
                                       <div class="form-group row">
                                          <div class="col-12">
                                             <label for="question_mce">Input total of questions multiple choice <span style="color: red">*</span></label>
                                             <input name="question_mce" type="number" class="form-control" id="question_mce" max="50">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-6">
                                       <div class="form-group row">
                                          <div class="col-12">
                                             <label for="question_essay">Input total of essay <span style="color: red">*</span></label>
                                             <input name="question_essay" type="number" class="form-control" id="question_essay" max="50">
                                          </div>
                                       </div>
                                    </div>   
                                 </div>
                                 <div class="col-12">
                                    <div class="form-group row">
                                       <div class="col-12 p-0">
                                          <button class="btn btn-warning btn-md w-100 btn-create-mce">Create Question</button>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="div p-0" id="mc" style="display: none;">
                                 <div class="d-flex">
                                    <div class="col-12 p-0">
                                       <div class="form-group row">
                                          <div class="col-12">
                                             <label for="just_mc">Input total of questions multiple choice <span style="color: red">*</span></label>
                                             <input name="just_mc" type="number" class="form-control" id="just_mc" max="50">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-12">
                                    <div class="form-group row">
                                       <div class="col-12 p-0">
                                          <button class="btn btn-warning btn-mc btn-md w-100 btn-create-mc">Create Question</button>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="div p-0" id="essay" style="display: none;">
                                 <div class="d-flex p-0">
                                    <div class="col-6 p-0">
                                       <div class="form-group row">
                                          <div class="col-12">
                                             <label for="just_essay">Input total of essay <span style="color: red">*</span></label>
                                             <input name="just_essay" type="number" class="form-control" id="just_essay" max="50">
                                          </div>
                                       </div>
                                    </div>   
                                 </div>
                                 <div class="col-12">
                                    <div class="form-group row">
                                       <div class="col-12 p-0">
                                          <button class="btn btn-warning btn-essay btn-md w-100 btn-create-essay">Create Question</button>
                                       </div>
                                    </div>
                                 </div>

                              </div>
                        </div>
                     </div>
                  </div>

                  <div class="card card-light" id="question-combine" style="display: none;border-radius: 12px;">
                     <div class="card-header">
                        Multiple Choice & Essay
                     </div>
                     <div class="card-body">
                        {{-- <div class="d-flex">
                           <div class="col-4 ">
                              <div class="form-group row">
                                 <div class="col-12">
                                    <label for="question_mce">Input total of questions multiple choice <span style="color: red">*</span></label>
                                    <input name="question_mce" type="number" class="form-control" id="question_mce" max="50">
                                 </div>
                              </div>
                           </div>
                           <div class="col-4">
                              <div class="form-group row">
                                 <div class="col-12">
                                    <label for="question_essay">Input total of essay <span style="color: red">*</span></label>
                                    <input name="question_essay" type="number" class="form-control" id="question_essay" max="50">
                                 </div>
                              </div>
                           </div>   
                        </div>
                        <div class="col-12">
                           <div class="form-group row">
                              <div class="col-8">
                                 <button class="btn btn-secondary btn-md w-100 btn-create-mce">Create Question</button>
                              </div>
                           </div>
                        </div> --}}

                        <div id="question">
                        </div>
                     </div>
                  </div>

                  <div class="card card-light" id="question-essay" style="display: none;border-radius: 12px;">
                     <div class="card-header">
                        Essay
                     </div>
                     <div class="card-body">
                        {{-- <div class="d-flex">
                           <div class="col-4">
                              <div class="form-group row">
                                 <div class="col-12">
                                    <label for="just_essay">Input total of essay <span style="color: red">*</span></label>
                                    <input name="just_essay" type="number" class="form-control" id="just_essay" max="50">
                                 </div>
                              </div>
                           </div>   
                        </div>
                        <div class="col-12">
                           <div class="form-group row">
                              <div class="col-8">
                                 <button class="btn btn-dark btn-essay btn-md w-100 btn-create-essay">Create Question</button>
                              </div>
                           </div>
                        </div> --}}

                        <div id="containeressay">
                        </div>
                     </div>
                  </div>

                  <div class="card card-light" id="question-mc" style="display: none;border-radius: 12px;">
                     <div class="card-header">
                        Multiple Choice
                     </div>
                     <div class="card-body">
                        {{-- <div class="d-flex">
                           <div class="col-4 ">
                              <div class="form-group row">
                                 <div class="col-12">
                                    <label for="just_mc">Input total of questions multiple choice <span style="color: red">*</span></label>
                                    <input name="just_mc" type="number" class="form-control" id="just_mc" max="50">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="form-group row">
                              <div class="col-8">
                                 <button class="btn btn-primary btn-mc btn-md w-100 btn-create-mc">Create Question</button>
                              </div>
                           </div>
                        </div> --}}

                        <div id="containermultiplechoice">
                        </div>
                     </div>
                  </div>

                  <div class="row d-flex justify-content-center">
                     <div class="col-md-12">
                        <input role="button" type="submit" class="btn btn-success center col-12">
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
   
   document.addEventListener("DOMContentLoaded", function () {
      let gradeSelect = document.getElementById('grade_id');

      // Cek apakah ada nilai selected dan panggil fungsi jika ada
      if (gradeSelect.value) {
         loadSubjectOptionExam(gradeSelect.value, teacherSelect ? teacherSelect.value : null);
      }

      // Tambahkan event listener untuk perubahan nilai
      gradeSelect.addEventListener('change', function () {
         loadSubjectOptionExam(this.value, teacherSelect.value);
      });
   });
   
   document.getElementById('type_exam').addEventListener('change', function() {
      if (this.value == '5') {
         document.getElementById('model-form').style.display = 'none';
      }
   });

   function loadSubjectOptionExam(gradeId, teacherId) {
   // Tambahkan opsi "-- SELECT SUBJECT --" sebagai opsi pertama
      subjectSelect.innerHTML = '<option value="" selected disabled>-- SELECT SUBJECT --</option>';

      fetch(`/get-subjects/${gradeId}/${teacherId}`)
        .then(response => response.json())
        .then(data => {
            // Tambahkan opsi baru ke select subject
            if (data.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.text = 'Subject Empty';
                subjectSelect.add(option);
            } else {
                data.forEach(subject => {
                     const option = document.createElement('option');
                     option.value = subject.id;
                     option.text = subject.name_subject;
                     if (subject.id == {{ $subjectId }}) {
                        option.selected = true;
                     }
                     subjectSelect.add(option);
                });
            }
        })
        .catch(error => console.error(error));
   }

   document.getElementById('model').addEventListener('change', function() {
      if (this.value === 'uf') {
         document.getElementById('file-form').style.display = 'block';
         document.getElementById('question-combine').style.display = 'none';
         document.getElementById('question-mc').style.display = 'none';
         document.getElementById('question-essay').style.display = 'none';
         document.getElementById('combine').style.display = 'none';
         document.getElementById('mc').style.display = 'none';
         document.getElementById('essay').style.display = 'none';
      }
      else if(this.value === 'mce') {
         document.getElementById('combine').style.display = 'block';
         document.getElementById('file-form').style.display = 'none';
         document.getElementById('mc').style.display = 'none';
         document.getElementById('essay').style.display = 'none';
         document.getElementById('question-mc').style.display = 'none';
         document.getElementById('question-essay').style.display = 'none';
      }
      else if(this.value === 'mc') {
         document.getElementById('mc').style.display = 'block';
         document.getElementById('file-form').style.display = 'none';
         document.getElementById('combine').style.display = 'none';
         document.getElementById('essay').style.display = 'none';
         document.getElementById('question-combine').style.display = 'none';
         document.getElementById('question-essay').style.display = 'none';
      }
      else if(this.value === 'essay') {
         document.getElementById('essay').style.display = 'block';
         document.getElementById('file-form').style.display = 'none';
         document.getElementById('combine').style.display = 'none';
         document.getElementById('mc').style.display = 'none';
         document.getElementById('question-combine').style.display = 'none';
         document.getElementById('question-mc').style.display = 'none';
      }
      else {
         document.getElementById('file-form').style.display = 'none';
         document.getElementById('combine').style.display = 'none';
         document.getElementById('mc').style.display = 'none';
         document.getElementById('essay').style.display = 'none';
         document.getElementById('question-combine').style.display = 'none';
         document.getElementById('question-mc').style.display = 'none';
         document.getElementById('question-essay').style.display = 'none';
      }
   });

   document.getElementById("upload_file").addEventListener("change", function () {
      const file = this.files[0];
      const fileExtension = file.name.split('.').pop();      
      const fileError = document.getElementById("fileError");
      const submitBtn = document.getElementById("submitBtn");

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
      }
      else {
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
      }
   });

   // GENERATE QUESTION
   // COMBINE
   document.addEventListener("DOMContentLoaded", function () {
      document.querySelector(".btn-create-mce").addEventListener("click", function (event) {
         event.preventDefault();
         event.addClass = "d-none";
         let model = "combine";
         document.getElementById('question-combine').style.display = 'block';
         document.getElementById('question-essay').style.display = 'none';
         document.getElementById('question-mc').style.display = 'none';
         generateQuestions(model);
      });
      
      document.querySelector(".btn-create-essay").addEventListener("click", function (event) {
         event.preventDefault();
         let model = "essay";
         document.getElementById('question-essay').style.display = 'block';
         document.getElementById('question-combine').style.display = 'none';
         document.getElementById('question-mc').style.display = 'none';
         generateQuestions(model);
      });

      document.querySelector(".btn-create-mc").addEventListener("click", function (event) {
         event.preventDefault();
         let model = "mc";
         document.getElementById('question-mc').style.display = 'block';
         document.getElementById('question-essay').style.display = 'none';
         document.getElementById('question-combine').style.display = 'none';
         generateQuestions(model);
      });
   });

   function generateQuestions(model) {
      let questionContainer = document.getElementById("question");
      let mcContainer = document.getElementById("containermultiplechoice");
      let essayContainer = document.getElementById("containeressay");

      // Hapus elemen sebelum menambahkan yang baru
      clearForm("question");
      clearForm("containermultiplechoice");
      clearForm("containeressay");

      if(model == "combine"){
         let mcCount = document.getElementById("question_mce").value;
         let essayCount = document.getElementById("question_essay").value;
         let questionContainer = document.getElementById("question");
   
         questionContainer.innerHTML = ""; // Reset container
   
         // Generate Multiple Choice Questions
         let mcSection = document.createElement("div");
         mcSection.innerHTML = `<h4>Multiple Choice Questions</h4>`;
         for (let i = 0; i < mcCount; i++) {
            mcSection.innerHTML += `
                  <div class="card p-3 mb-2">
                     <label>Question ${i + 1}</label>
                     <textarea id="froala-editor" name="question_mc[${i}][question]"></textarea>
                     <div class="mt-2">
                        <label>Options:</label>
                        <input type="text" name="question_mc[${i}][answer][a]" class="form-control" placeholder="Option A" required>
                        <input type="text" name="question_mc[${i}][answer][b]" class="form-control mt-1" placeholder="Option B" required>
                        <input type="text" name="question_mc[${i}][answer][c]" class="form-control mt-1" placeholder="Option C" required>
                        <input type="text" name="question_mc[${i}][answer][d]" class="form-control mt-1" placeholder="Option D" required>
                     </div>
                     <label class="mt-2">Correct Answer:</label>
                     <select name="question_mc[${i}][question_key]" class="form-control" required>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                     </select>
                  </div>
            `;
         }
         questionContainer.appendChild(mcSection);
   
         // Generate Essay Questions
         let essaySection = document.createElement("div");
         essaySection.innerHTML = `<h4 class="pt-4">Essay Questions</h4>`;
         for (let i = 0; i < essayCount; i++) {
            essaySection.innerHTML += `
                  <div class="card p-3 mb-2">
                     <label>Question ${i + 1}</label>
                     <textarea id="froala-editor" name="essay[${i}][question]"></textarea>
                     <label class="mt-2">Answer Key:</label>
                     <input type="text" name="essay[${i}][answer]" class="form-control" placeholder="Enter correct answer" required>
                  </div>
            `;
         }
         questionContainer.appendChild(essaySection);
         setTimeout(initializeFroalaEditor, 100);
      }
      else if(model == "mc"){
         let mc = document.getElementById("just_mc").value;
         let mcContainer = document.getElementById("containermultiplechoice");
   
         mcContainer.innerHTML = ""; // Reset container
   
         // Generate Multiple Choice Questions
         let mcSection = document.createElement("div");
         mcSection.innerHTML = `<h4>Multiple Choice Questions</h4>`;
         for (let i = 0; i < mc; i++) {
            mcSection.innerHTML += `
                  <div class="card p-3 mb-2">
                     <label>Question ${i + 1}</label>
                     <textarea id="froala-editor" name="question_mc[${i}][question]"></textarea>
                     <div class="mt-2">
                        <label>Options:</label>
                        <input type="text" name="question_mc[${i}][answer][a]" class="form-control" placeholder="Option A" required>
                        <input type="text" name="question_mc[${i}][answer][b]" class="form-control mt-1" placeholder="Option B" required>
                        <input type="text" name="question_mc[${i}][answer][c]" class="form-control mt-1" placeholder="Option C" required>
                        <input type="text" name="question_mc[${i}][answer][d]" class="form-control mt-1" placeholder="Option D" required>
                     </div>
                     <label class="mt-2">Correct Answer:</label>
                     <select name="question_mc[${i}][question_key]" class="form-control" required>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                     </select>
                  </div>
            `;
         }
         mcContainer.appendChild(mcSection);
         setTimeout(initializeFroalaEditor, 100);
      }
      else if(model == "essay"){
         let essayCount = document.getElementById("just_essay").value;
         let questionContainer = document.getElementById("containeressay");
   
         questionContainer.innerHTML = "";
         console.log(essayCount);
         // Generate Essay Questions
         let essaySection = document.createElement("div");
         essaySection.innerHTML = `<h4 class="pt-4">Essay Questions</h4>`;
         for (let i = 0; i < essayCount; i++) {
            essaySection.innerHTML += `
                  <div class="card p-3 mb-2">
                     <label>Question ${i + 1}</label>
                     <textarea id="froala-editor" name="essay[${i}][question]"></textarea>
                     <label class="mt-2">Answer Key:</label>
                     <input type="text" name="essay[${i}][answer]" class="form-control" placeholder="Enter correct answer" required>
                  </div>
            `;
         }
         questionContainer.appendChild(essaySection);
         setTimeout(initializeFroalaEditor, 100);
      }
   }
   // END COMBINE

   // Fungsi untuk menginisialisasi CKEditor setelah textarea dibuat
   function initializeFroalaEditor() {
      new FroalaEditor("textarea#froala-editor", 
      {
         imageUploadURL: "/upload-image-question", // Endpoint Laravel
         imageUploadMethod: "POST",
         imageAllowedTypes: ["jpeg", "jpg", "png", "gif"],
         imageUploadParams: {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute("content"), // Kirim CSRF Token
         },
      });
   }

   function clearForm(containerId) {
      let container = document.getElementById(containerId);
      
      // Hapus validasi required sebelum menghapus elemen
      container.querySelectorAll("[required]").forEach((input) => {
         input.removeAttribute("required");
      });

      // Hapus seluruh isi container
      container.innerHTML = "";
   }

</script>



@endsection