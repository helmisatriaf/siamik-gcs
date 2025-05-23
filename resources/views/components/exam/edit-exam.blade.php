@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- general form elements -->
            <div class="col-12">
                <div>
                    @if (session('role') == 'superadmin')
                        <form method="POST" action={{route('actionSuperUpdateExam', $data['dataExam']->id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateExam', $data['dataExam']->id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Update exam</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="name">Exam Name<span style="color: red">*</span></label>
                                        <input name="name" type="text" class="form-control" id="name"
                                                placeholder="Enter Exam Name" value="{{ old('name') ? old('name') : $data['dataExam']->name_exam }}" autocomplete="off" required>
                                        @if($errors->has('name'))
                                                <p style="color: red">{{ $errors->first('name') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="type_exam">Type<span style="color: red">*</span></label>
                                        <select required name="type_exam" class="form-control" id="type_exam">
                                                <option selected disabled>--- SELECT TYPE EXAM ---</option>
                                                @foreach($data['typeExam'] as $el)
                                                <option value="{{ $el->id }}" {{ $el->id == $data['dataExam']->type_exam_id ? 'selected' : '' }}>{{ ucwords($el->name) }}</option>
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
                                            <option selected disabled>--- SELECT GRADE ---</option>
                                            @foreach($data['grade'] as $el)
                                                <option value="{{ $el->id }}" {{ $el->id == $data['dataExam']->grade_id ? 'selected' : '' }}>{{ $el->name }} - {{ $el->class}}</option>
                                            @endforeach
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
                                        <label for="teacher_id">Teacher<span style="color: red">*</span></label>
                                        <select required name="teacher_id" class="form-control" id="teacher_id">
                                               
                                        </select>
                                        @if($errors->has('teacher_id'))
                                                <p style="color: red">{{ $errors->first('teacher_id') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="date_exam">Date Exam<span style="color: red">*</span></label>
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

                                    <div class="col-12 mt-3">
                                        <input role="button" type="submit" class="btn btn-success center col-12">
                                    </div>
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
    var subjectSelect = document.getElementById("subject_id");
    var teacherSelect = document.getElementById("teacher_id");

    function loadSubjectOptionExam(gradeId) {
    // Tambahkan opsi "-- SELECT SUBJECT --" sebagai opsi pertama
    subjectSelect.innerHTML = '<option value="" selected disabled>-- SELECT SUBJECT --</option>';
        
        return fetch(`/get-subjects/${gradeId}`)
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
                        if (subject.id == {{ $data['dataExam']->subject_id }}) {
                            option.selected = true;
                        }
                        subjectSelect.add(option);
                    });
                }
            })
            .catch(error => console.error(error));
    }   

    function loadTeacherOption(gradeId, subjectId) {
        teacherSelect.innerHTML = '<option value="" selected disabled>-- SELECT TEACHERS --</option>';

        return fetch(`/get-teachers/${gradeId}/${subjectId}`)
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
                        if (teacher.id == {{ $data['dataExam']->teacher_id }}) {
                            option.selected = true;
                        }
                        teacherSelect.add(option);
                    });
                }
            })
            .catch(error => console.error(error));
    }

    document.getElementById('grade_id').addEventListener('change', function() {
        loadSubjectOptionExam(this.value)
        .then(() => loadTeacherOption(this.value, document.getElementById('subject_id').value));
    });

    document.getElementById('subject_id').addEventListener('change', function() {
        loadTeacherOption(document.getElementById('grade_id').value, this.value);
    });

    window.onload = function() {
        loadSubjectOptionExam(document.getElementById('grade_id').value)
        .then(() => loadTeacherOption(document.getElementById('grade_id').value, document.getElementById('subject_id').value));
    };

</script>

@endsection
