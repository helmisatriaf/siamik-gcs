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
                     <form method="POST" action={{route('actionSuperCreateGrade')}}>
                  @elseif (session('role') == 'admin')
                     <form method="POST" action={{route('actionAdminCreateGrade')}}>
                  @endif
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create grade</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                 <div class="form-group row">
                                    <div class="col-md-6">
                                       <label for="name">Grade<span style="color: red">*</span></label>
                                       <select required name="name" class="form-control" id="name">
                                          <option selected disabled>--- SELECT GRADE ---</option>
                                          <option value="Toddler">Toddler</option>
                                          <option value="Nursery">Nursery</option>
                                          <option value="Kindergarten">Kindergarten</option>
                                          <option value="Primary">Primary</option>
                                          <option value="Secondary">Secondary</option>
                                          <option value="IGCSE">IGCSE</option>
                                       </select>
                                       @if($errors->any())
                                       <p style="color: red">{{$errors->first('name')}}</p>
                                       @endif
                                    </div>
                                    <div class="col-md-6">
                                       <label for="class">Class<span style="color: red">*</span></label>
                                       <input name="class" type="text" class="form-control" id="class"
                                          placeholder="Enter class" value="{{old('class')}}" autocomplete="off" required>
                                       @if($errors->any())
                                       <p style="color: red">{{$errors->first('class')}}</p>
                                       @endif
                                    </div>
                                 </div>

                                 <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="teacher_class_id">Class Teacher</label>
                                        <select name="teacher_class_id" class="form-control grade" id="teacher_class_id">
                                            <option value=""> -- SELECTED TEACHER CLASS --</option>
                                            @foreach($data['teacher'] as $el)
                                                <option value="{{$el->id}}">{{$el->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                 </div>

                                 <table class="table table-striped table-bordered">
                                    <thead>
                                        <th style="width: 40%;">Subject</th>
                                        <th style="width: 40%;">Teacher</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="scheduleTableBody">
                                        <tr>
                                            <td>
                                                <select required name="subject_id[]" class="form-control" id="subject_id">
                                                    <option value="" selected>  SELECT SUBJECT </option>
                                                    @foreach($data['subject'] as $el)
                                                        <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('subject_id'))
                                                <p style="color: red">{{ $errors->first('subject_id') }}</p>
                                                @endif
                                            </td>
                                            <td>
                                                <select required name="teacher_subject_id[]" class="form-control" id="teacher_subject_id">
                                                    <option value="" selected>  SELECT TEACHER </option>
                                                    @foreach($data['teacher'] as $el)
                                                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('teacher_subject_id'))
                                                <p style="color: red">{{ $errors->first('teacher_subject_id') }}</p>
                                                @endif
                                            </td>
                                            
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data" id="tambah"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris" id="hapus"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                 </table>

                              
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
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<! Script untuk manipulasi tambah dan hapus row grade & subject teacher >
<script>
$(document).ready(function() {
    // Function to add a new row
    function addRow() {
        var newRow = `<tr>
            <td>
                <select required name="subject_id[]" class="form-control" id="subject_id">
                    <option value="" selected>  SELECT SUBJECT </option>
                    @foreach($data['subject'] as $el)
                        <option value="{{ $el->id }}">{{ $el->name_subject }}</option>
                    @endforeach
                </select>
                @if($errors->has('subject_id'))
                <p style="color: red">{{ $errors->first('subject_id') }}</p>
                @endif
            </td>
            <td>
                <select required name="teacher_subject_id[]" class="form-control" id="teacher_subject_id">
                    <option value="" selected>  SELECT TEACHER </option>
                    @foreach($data['teacher'] as $el)
                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('teacher_subject_id'))
                <p style="color: red">{{ $errors->first('teacher_subject_id') }}</p>
                @endif
            </td>
            <td>
                <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Data"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1" title="Hapus Baris"><i class="fa fa-times"></i></button>
            </td>
        </tr>`;
        $('#scheduleTableBody').append(newRow);

        // Call the function to populate subject and teacher options for the new row
        const newSubjectSelect = $('#scheduleTableBody tr:last .subject_id');
        const newTeacherSelect = $('#scheduleTableBody tr:last .teacher_id');

        loadSubjectOptionExam($('#grade_id').val(), newSubjectSelect);
        newSubjectSelect.change(function() {
            loadTeacherOption($('#grade_id').val(), $(this).val(), newTeacherSelect);
        });

        updateHapusButtons();
    }

    // Function to update the visibility of the "Hapus" buttons
    function updateHapusButtons() {
        $('#scheduleTableBody tr').each(function(index, row) {
            var hapusButton = $(row).find('.btn-hapus');
            if (index === $('#scheduleTableBody tr').length - 1) {
                hapusButton.removeClass('d-none');
            } else {
                hapusButton.addClass('d-none');
            }
        });
    }

    // Event listener for the "Tambah" button
    $('#scheduleTableBody').on('click', '.btn-tambah', function() {
        addRow();
    });

    // Event listener for the "Hapus" button
    $('#scheduleTableBody').on('click', '.btn-hapus', function() {
        $(this).closest('tr').remove();
        updateHapusButtons();
    });

    // Initial call to update the visibility of the "Hapus" buttons
    updateHapusButtons();
});

</script>
@endsection
