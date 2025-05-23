@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
            <!-- general form elements -->
            <div>
                @if (session('role') == 'superadmin')
                    <form method="POST" action="{{ route('actionSuperEditScheduleSubtitute', ['gradeId' => $gradeId, 'scheduleId' => $data[0]->id]) }}">
                @elseif (session('role') == 'admin')
                    <form method="POST" action="{{ route('actionAdminEditScheduleSubtitute', ['gradeId' => $gradeId, 'scheduleId' => $data[0]->id]) }}">
                @endif
                @csrf
                @method('PUT')
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Edit schedule subtitute</h3>
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
                            <label for="teacher_id">Teacher<span style="color: red"> *</span></label>
                            <select name="teacher_id" class="form-control" id="teacher_id">
                                @foreach($data as $el)
                                    <option value="{{ $el->teacher_id }}" selected>{{ $el->teacher_name }}</option>
                                @endforeach
                                @foreach($teacher as $el)
                                    <option value="{{ $el->id }}" >{{ $el->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('teacher_id'))
                                <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="teacher_companion">Companion Teacher<span style="color: red"> *</span></label>
                            <select name="teacher_companion" class="form-control" id="teacher_companion">
                                @foreach($data as $el)
                                    <option value="{{ $el->teacher_companion_id }}" selected>{{ $el->teacher_companion_name }}</option>
                                @endforeach
                                @foreach($teacher as $el)
                                    <option value="{{ $el->id }}" >{{ $el->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('teacher_companion_id'))
                                <p style="color: red">{{ $errors->first('teacher_companion_id') }}</p>
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

                    <div class="form-group row">
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

            <!-- Right Column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">See Teacher Schedule</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Teacher: <span style="color: red"></span></label>
                                        <select id="teacher-select" name="teacher_select" class="form-control">
                                            <option value="" selected>-- Select Teacher --</option>
                                            @foreach ($teacher as $tc)
                                                <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Grade: <span style="color: red"></span></label>
                                        <select id="grade-select" name="grade_select" class="form-control">
                                            <option value="" selected>-- Select Grade --</option>
                                            @foreach ($grade as $gr)
                                                <option value="{{ $gr->id }}">{{ $gr->name }} - {{ $gr->class }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4 d-none">
                                    <div class="form-group">
                                        <label>Day: <span style="color: red"></span></label>
                                        <select id="day" name="day" class="form-control">
                                            <option value="" selected>-- Select Teacher --</option>
                                            @foreach($data as $el)
                                                <option value="{{ $el->day }}" selected>{{ $el->day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="scheduleTeacher">

                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">See Assisstant Schedule</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Teacher: <span style="color: red"></span></label>
                                        <select id="teacher-companion" name="teacher_companion" class="form-control">
                                            <option value="" selected>-- Select Assisstant --</option>
                                            @foreach ($teacher as $tc)
                                                <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Grade: <span style="color: red"></span></label>
                                        <select id="grade-companion" name="grade_select" class="form-control">
                                            <option value="" selected>-- Select Grade --</option>
                                            @foreach ($grade as $gr)
                                                <option value="{{ $gr->id }}">{{ $gr->name }} - {{ $gr->class }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="scheduleTeacherCompanion">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const teacherSelect = document.getElementById('teacher-select');
    const gradeSelect = document.getElementById('grade-select');
    const teacherCompanionSelect = document.getElementById('teacher-companion');
    const gradeCompanionSelect = document.getElementById('grade-companion');
    const daySelect = document.getElementById('day');
    const scheduleTeacherDiv = document.getElementById('scheduleTeacher');
    const scheduleTeacherCompanionDiv = document.getElementById('scheduleTeacherCompanion');


    teacherSelect.addEventListener('change', validateAndFetchSchedule);
    gradeSelect.addEventListener('change', validateAndFetchSchedule);
    teacherCompanionSelect.addEventListener('change', validateAndFetchCompanionSchedule);
    gradeCompanionSelect.addEventListener('change', validateAndFetchCompanionSchedule);

    function validateAndFetchSchedule() {
        const teacher = teacherSelect.value;
        const grade = gradeSelect.value;
        const day = daySelect.value;

        if (teacher && grade) {
            fetchTeacherSchedule(teacher, grade, day);
        } else {
            scheduleTeacherDiv.innerHTML = ''; // Clear the schedule div if input is incomplete
            if (!teacher) alert('Please select a teacher.');
            if (!grade) alert('Please select a grade.');
        }
    }

    function validateAndFetchCompanionSchedule() {
        const teacher = teacherCompanionSelect.value;
        const grade = gradeCompanionSelect.value;
        const day = daySelect.value;

        if (teacher && grade) {
            fetchTeacherCompanionSchedule(teacher, grade, day);
        } else {
            scheduleTeacherCompanionDiv.innerHTML = ''; // Clear the schedule div if input is incomplete
            if (!teacher) alert('Please select a companion teacher.');
            if (!grade) alert('Please select a grade.');
        }
    }

    function fetchTeacherSchedule(teacher, grade, day) {
        fetch(`/get-schedule-edit/${day}/${teacher}/${grade}`)
            .then(response => response.json())
            .then(data => {
                renderScheduleTable(data, scheduleTeacherDiv);
            })
            .catch(error => console.error('Error fetching schedule:', error));
    }

    function fetchTeacherCompanionSchedule(teacher, grade, day) {
        fetch(`/get-schedule-companion-edit/${day}/${teacher}/${grade}`)
            .then(response => response.json())
            .then(data => {
                renderScheduleTable(data, scheduleTeacherCompanionDiv);
            })
            .catch(error => console.error('Error fetching schedule:', error));
    }

    function renderScheduleTable(data, container) {
        let table = '<table class="table table-bordered">';
        table += `
            <thead>
                <tr>
                    <th>No</th>
                    <th>Teacher</th>
                    <th>Companion</th>
                    <th>Grade</th>
                    <th>Subject</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
        `;

        data.forEach((item, index) => {
            table += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.teacher_name || ''}</td>
                    <td>${item.teacher_companion || ''}</td>
                    <td>${item.grade_name} - ${item.grade_class}</td>
                    <td>${item.subject_name}</td>
                    <td>${item.start_time}</td>
                    <td>${item.end_time}</td>
                </tr>
            `;
        });

        table += '</tbody></table>';
        container.innerHTML = table;
    }
});

</script>

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

@endsection
