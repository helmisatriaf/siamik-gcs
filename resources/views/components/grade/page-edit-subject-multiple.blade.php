@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades')}}">Grade</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/edit/' . $data['grade_id'])}}">Edit {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/grades/manageSubject/' . $data['grade_id'])}}">Manage Subject & Teacher {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit {{ $data['subject_name'] }} {{ $data['grade_name'] }} - {{ $data['grade_class'] }}</li>
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
                        <form method="POST" action={{route('actionSuperUpdateGradeSubjectMultiTeacher', $data->teacher_subject_id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateGradeSubjectMultiTeacher', $data->teacher_subject_id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <input type="hidden" value="{{$data['grade_id']}}" name="grade_id">
                        <input type="hidden" value="{{$data['subject_id']}}" name="subject_id">

                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit Subject Teacher</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for="name">Subject<span style="color: red"> *</span></label>
                                        <select name="subject" id="subject" class="form-control">
                                            <option value="{{ $data->subject_id }}" selected>{{ $data->subject_name }}</option>
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('subject')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label for="name">Main Teacher<span style="color: red"> *</span></label>
                                        <select name="main_teacher" id="teacher" class="form-control js-select2">
                                            @foreach ($dataMultiple['is_lead'] as $leadTeacher)
                                                <option value="{{ $leadTeacher['id'] }}" selected>{{ $leadTeacher['name'] }}</option>
                                            @endforeach
                                            @foreach ($teacher as $teacher)
                                                <option value="{{$teacher->id}}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->any())
                                            <p style="color: red">{{$errors->first('teacher')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <table class="table table-striped projects">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align:left;">For Edit Subject Group</th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                    #
                                                    </th>
                                                    <th style="width: 20%">
                                                        Teacher
                                                    </th>
                                                    <th style="width: 80%">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dataMultiple['is_group'] as $el)
                                                <tr>
                                                    <td>
                                                        {{ $loop->index + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $el['name'] }}
                                                    </td>
                                                    
                                                    <td class="project-actions text-left toastsDefaultSuccess">
                                                        @if (session('role') == 'superadmin' || session('role') == 'admin')
                                                            <a class="btn btn-warning btn" data-toggle="modal" data-target="#modalChangeTeacher-{{$el['id']}}" data-subject-id="{{ $data->subject_id }}" data-teacher-id="{{ $el['id'] }}" data-grade-id="{{ $data->grade_id }}">
                                                                <i class="fas fa-edit"></i> Change
                                                            </a>
                                                            <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteTeacher-{{$el['id']}}" data-subject-id="{{ $data->subject_id }}" data-teacher-id="{{ $el['id'] }}" data-grade-id="{{ $data->grade_id }}">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                        
                                                <!-- Modal -->
                                                <div class="modal fade" id="modalDeleteTeacher-{{$el['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <input type="hidden" value="{{$el['fk']}}" name="data_id" id="data-grup-id-{{$el['id']}}">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Delete This Data</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">Are you sure want to delete  {{ $el['name'] }}?</div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a class="btn btn-danger btn" id="confirmDelete-{{$el['id']}}">Yes delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="modalChangeTeacher-{{$el['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Change Teacher</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                    <form method="POST"  id="formChangeTeacher">
                                                                        @csrf <!-- Pastikan CSRF token digunakan -->
                                                                        <input type="hidden" value="{{$el['id']}}" name="teacher_id" id="teacher-id-{{$el['id']}}">
                                                                        <input type="hidden" value="{{$el['fk']}}" name="data_id" id="data-id-{{$el['id']}}">

                                                                        <div class="form-group row">
                                                                            <div class="col-md-12 mt-2">
                                                                                <label for="name">Change Teacher<span style="color: red"> *</span></label>
                                                                                <select name="change_teacher" id="change_teacher-{{$el['id']}}" class="form-control">
                                                                                    <option>--- Choose Teacher ---</option>
                                                                                    @foreach ($teacherss as $t)
                                                                                        <option value="{{$t->id}}">{{ $t->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a class="btn btn-danger btn" id="confirmChange-{{$el['id']}}">Yes, change</a>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                        
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <label for="name">Add Member<span style="color: red"> *</span></label>
                                        <select name="group_teacher[]" id="teacher" class="form-control js-select2" multiple>
                                    
                                            <!-- Loop through all available teachers and display them as options -->
                                            @foreach ($teachers as $teachers)
                                                <!-- Make sure to not select teachers who are already in the 'is_group' list -->
                                                <option value="{{ $teachers->id }}" 
                                                    @if (isset($dataMultiple['is_group']) && in_array($teachers->id, array_column($dataMultiple['is_group'], 'id'))) 
                                                        disabled 
                                                    @endif>
                                                    {{ $teachers->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    

                                    <div class="col-12 mt-3">
                                        <input role="button" type="submit" class="btn btn-success center col-12">
                                    </div>

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

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmDeleteButtons = document.querySelectorAll('[id^="confirmDelete-"]');

        confirmDeleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                const id = this.id.split('-')[1];
                const dataGrupId = document.getElementById(`data-grup-id-${id}`).value; // Get the selected teacher from the corresponding modal
                
                const form = {
                    id: parseInt(dataGrupId, 10),
                    type: "member",
                };

                const options = {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    body: JSON.stringify(form)
                };

                // Send the form data using fetch
                fetch("{{ route('dsg') }}", options)
                    .then(response => response.json())
                    .then(data => {
                        // Handle the server response
                        if (data.success) {
                            console.log(data.tes);
                            Swal.fire({
                                icon: 'success',
                                text: 'Data Berhasil Dihapus',
                                showConfirmButton: false, // Hide the confirm button
                                timer: 1500, // Auto close after 2000 milliseconds (2 seconds)
                                timerProgressBar: true // Optional: show a progress bar
                            }).then(() => {
                                // Optionally, you can still perform actions after the modal closes
                                location.reload();
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: 'Maaf ada kesalahan',
                                showConfirmButton: false, // Hide the confirm button
                                timer: 1500, // Auto close after 2000 milliseconds (2 seconds)
                                timerProgressBar: true // Optional: show a progress bar
                            }).then(() => {
                                // Optionally, you can still perform actions after the modal closes
                                location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
            });
        });

        const confirmChangeButtons = document.querySelectorAll('[id^="confirmChange-"]');

        confirmChangeButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                const id = this.id.split('-')[1]; // Get the ID from the button's ID
                const changeTeacher = document.getElementById(`change_teacher-${id}`).value; // Get the selected teacher from the corresponding modal
                const teacher = document.getElementById(`teacher-id-${id}`).value; // Get the selected teacher from the corresponding modal
                const dataId = document.getElementById(`data-id-${id}`).value; // Get the selected teacher from the corresponding modal


                // console.log(changeTeacher);
                // Prepare the data to send
                // const formData = new FormData();
                // formData.append('subject_id', document.getElementById('subject-id').value);
                // formData.append('teacher_id', document.getElementById('teacher-id').value);
                // formData.append('grade_id', document.getElementById('grade-id').value);
                // formData.append('change_teacher', changeTeacher);

                const form = {
                    id: parseInt(dataId, 10),
                    grade_id: @json($data['grade_id']),
                    subject_id: @json($data['subject_id']),
                    teacher_id: teacher,
                    change_teacher: parseInt(changeTeacher, 10),
                };

                console.log(form);
                // Prepare options for the fetch request
                const options = {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json' // Set the content type to JSON
                    },
                    body: JSON.stringify(form) // Convert the form object to a JSON string
                };

                // Send the form data using fetch
                fetch("{{ route('actionAdminChangeGradeSubjectMultiTeacher') }}", options)
                    .then(response => response.json())
                    .then(data => {
                        // Handle the server response
                        if (data.success) {
                            console.log(data.tes);
                            Swal.fire({
                                icon: 'success',
                                text: 'Data Berhasil Diubah',
                                showConfirmButton: false, // Hide the confirm button
                                timer: 1500, // Auto close after 2000 milliseconds (2 seconds)
                                timerProgressBar: true // Optional: show a progress bar
                            }).then(() => {
                                // Optionally, you can still perform actions after the modal closes
                                location.reload();
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: 'Maaf ada kesalahan',
                                showConfirmButton: false, // Hide the confirm button
                                timer: 1500, // Auto close after 2000 milliseconds (2 seconds)
                                timerProgressBar: true // Optional: show a progress bar
                            }).then(() => {
                                // Optionally, you can still perform actions after the modal closes
                                location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
            });
        });
    });
        
</script>

@if(session('after_update_subject_teacher')) 
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the subject teacher in the database.',
        });
    </script>
@endif

@endsection