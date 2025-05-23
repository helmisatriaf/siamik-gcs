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
                        <form method="POST" action={{route('actionSuperUpdateGrade', $data->id)}}>
                    @elseif (session('role') == 'admin')    
                        <form method="POST" action={{route('actionAdminUpdateGrade', $data->id)}}>
                    @endif
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Edit grade</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6" style="display:none">
                                        <label for="class">ID<span style="color: red">*</span></label>
                                        <input name="gradeId" type="text" class="form-control" id="gradeId" value="{{ $data->id }}" >
                                    </div>

                                    <div class="col-md-6">
                                        @php
                                          $selected = old('name') ? old('name') : $data->name;
                                       @endphp

                                        <label for="name">Grade<span style="color: red">*</span></label>
                                        <select required name="name" class="form-control" id="name">
                                            <option {{$selected === 'Preeschool' ? 'selected' : ''}}>Preeschool</option>
                                            <option {{$selected === 'Toddler' ? 'selected' : ''}}>Toddler</option>
                                            <option {{$selected === 'Nursery' ? 'selected' : ''}}>Nursery</option>
                                            <option {{$selected === 'Kindergarten' ? 'selected' : ''}}>Kindergarten</option>
                                            <option {{$selected === 'Primary' ? 'selected' : ''}}>Primary</option>
                                            <option {{$selected === 'Junior High School' ? 'selected' : ''}}>Junior High School</option>
                                            <option {{$selected === 'Junior College' ? 'selected' : ''}}>Junior College</option>
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="class">Class<span style="color: red">*</span></label>
                                        <input name="class" type="text" class="form-control" id="class"
                                            placeholder="Enter class" value="{{old('class')? old('class') : $data->class}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('class')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- SELECT TEACHER -->
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       @php
                                          $selectedTeacher = $teacherGrade;
                                       @endphp
                                        <label for="teacher_id">Teacher class</label>
                                        <select name="teacher_id[]" class="js-select2 form-control" id="teacher_id" multiple="multiple">
                                            @if(count($teacherGrade) == 0)
                                                @foreach($teacher as $el)
                                                    <option value="{{$el->id}}" >{{$el->name}}</option>
                                                @endforeach
                                            @else
                                                @foreach ($teacher as $el)
                                                    @if (!in_array($el->id, $teacherGrade))
                                                        <option value="{{$el->id}}">{{$el->name}}</option>
                                                    @endif
                                                @endforeach

                                                @foreach ($teacherGrade as $tg)
                                                    @foreach ($teacher as $t)
                                                        @if ($t->id == $tg)
                                                            <option value="{{$t->id}}" selected>{{$t->name}}</option>
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <!-- END SELECT TEACHER -->


                                <!-- SELECT SUBJECT -->
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="subject_id">Subject Class</label>
                                        <select name="subject_id[]" class="js-select2 form-control" id="subject_id" multiple="multiple">
                                            @if(count($subjectGrade) == 0)
                                                @foreach($subject as $se)
                                                    <option value="{{$se->id}}" >{{$se->name_subject}}</option>
                                                @endforeach
                                            @else

                                                @foreach ($subject as $se)
                                                    @if (!in_array($se->id, $subjectGrade))
                                                        <option value="{{$se->id}}">{{$se->name_subject}}</option>
                                                    @endif
                                                @endforeach

                                                @foreach ($subjectGrade as $sg)
                                                    @foreach ($subject as $s)
                                                        @if ($s->id == $sg)
                                                            <option value="{{$s->id}}" selected>{{$s->name_subject}}</option>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </select>

                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('subject_id')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <!-- END SUBJECT -->
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

@endsection