@extends('layouts.admin.master')
@section('content')

<section class="content">
   <div class="container-fluid">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-3">
                    <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/schedules/finalexams')}}">Final Exam Schedule</a></li>
                    <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/schedules/manage/finalexam/' . $data[0]['grade_id'])}}">Manage Schedule Final Exam {{ $data[0]['grade_name'] }}-{{ $data[0]['grade_class'] }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Schedule Final Exam</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div>
                @if (session('role') == 'superadmin')
                    <form method="POST" action="{{ route('actionSuperEditFinalExam', ['gradeId' => $gradeId, 'scheduleId' => $data[0]->id]) }}">
                @elseif (session('role') == 'admin')
                    <form method="POST" action="{{ route('actionAdminEditFinalExam', ['gradeId' => $gradeId, 'scheduleId' => $data[0]->id]) }}">
                @endif
                @csrf
                @method('PUT')
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Edit final exam schedule</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="type_schedule">Type Schedule<span style="color: red"> *</span></label>
                            <select required name="type_schedule" class="form-control" id="type_schedule">
                                @foreach($data as $el)
                                    <option value="{{ $el->type_schedule_id }}" selected>{{ $el->type_schedule_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type_schedule'))
                                <p style="color: red">{{ $errors->first('type_schedule') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="grade_id">Grade<span style="color: red"> *</span></label>
                            <select required name="grade_id" class="form-control" id="grade_id">
                                @foreach($data as $el)
                                    <option value="{{ $el->grade_id }}" selected>{{ $el->grade_name }} - {{ $el->grade_class}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('grade_id'))
                                <p style="color: red">{{ $errors->first('grade_id') }}</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="subject_id">Subject<span style="color: red"> *</span></label>
                            <select name="subject_id" class="form-control" id="subject_id">
                            </select>
                            @if($errors->has('subject_id'))
                                <p style="color: red">{{ $errors->first('subject_id') }}</p>
                            @endif
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="teacher_id">Invigilater<span style="color: red"> *</span></label>
                            <select name="teacher_id" class="form-control" id="teacher_id">
                                @foreach ($teacher as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $teacher->id == $data[0]->teacher_id ? "selected" : "" }}>{{ $teacher->name }}</option>
                                @endforeach
                                <!-- @foreach($data as $el)
                                    <option value="{{ $el->teacher_id }}" selected>{{ $el->teacher_name }}</option>
                                @endforeach -->
                            </select>
                            @if($errors->has('teacher_id'))
                                <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="day">Days<span style="color: red"> *</span></label>
                            <select name="day" class="form-control">
                                <option value="" >--SELECT DAY-- </option>
                                <option value="1" @if($data[0]->day == 1) selected @endif>Monday</option>
                                <option value="2" @if($data[0]->day == 2) selected @endif>Tuesday</option>
                                <option value="3" @if($data[0]->day == 3) selected @endif>Wednesday</option>
                                <option value="4" @if($data[0]->day == 4) selected @endif>Thursday</option>
                                <option value="5" @if($data[0]->day == 5) selected @endif>Friday</option>
                                <option value="6" @if($data[0]->day == 6) selected @endif>Saturday</option>
                            </select>   
                        
                            @if($errors->has('day'))
                                <p style="color: red">{{ $errors->first('day') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="day">Start Time<span style="color: red"> *</span></label>
                            <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $data[0]->start_time }}">
                        
                            @if($errors->has('start_time'))
                                <p style="color: red">{{ $errors->first('start_time') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="day">End Time<span style="color: red"> *</span></label>
                            <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $data[0]->end_time }}">
                        
                            @if($errors->has('end_time'))
                                <p style="color: red">{{ $errors->first('end_time') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row d-none">
                        <div class="col-md-12">
                            <label for="notes">Notes<span style="color: red"> *</span></label>
                            <textarea name="notes" class="form-control" id="notes" cols="10" rows="1">{{$data[0]->note}}</textarea>
                            
                            @if($errors->has('notes'))
                                <p style="color: red">{{ $errors->first('notes') }}</p>
                            @endif
                        </div>
                    </div>
                    </div>

                    <div class="row d-flex justify-content-center">
                    <input role="button" type="submit" class="btn btn-success center col-11 m-3">
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
    
    gradeSelect.addEventListener('change', function() {
        loadSubjectOptionExam(this.value);
    });

    subjectSelect.addEventListener('change', function() {
        loadTeacherOption(gradeSelect.value, this.value);
    });

   // Panggil loadSubjectOptionExam jika grade_id sudah terpilih
    window.onload = function() {
        if (gradeSelect.value) {
            loadSubjectOptionExam(gradeSelect.value);
        }
    };

    function loadSubjectOptionExam(gradeId) {
    // Tambahkan opsi "-- SELECT SUBJECT --" sebagai opsi pertama
    // subjectSelect.innerHTML = '<option value="" selected disabled>-- SELECT SUBJECT --</option>';

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
                let firstOption = true;
                data.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.text = subject.name_subject;
                    if (firstOption && subject.id == {{ $data[0]->subject_id }}) {
                        option.selected = true;
                        firstOption = false;
                    }
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

</script>

@if(session('after_edit_midexam_schedule')) 
   <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully edit mid exam schedule in the database.',
        });
   </script>

@endif

@endsection
