@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3">
                <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/schedules/midexams')}}">Mid Exam Schedule</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Schedule Mid Exam {{ $data[0]['grade_name'] }} - {{ $data[0]['grade_class'] }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card card-dark mt-2">
      <div class="card-header">
         <h3 class="card-title">Date Mid Exam {{ $data[0]['grade_name'] }}-{{ $data[0]['grade_class'] }}</h3>

         <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
         </div>
      </div>
      @if (session('role') == 'superadmin')
      <form method="POST" action="{{ route('actionSuperEditDateMidExam') }}">
      @elseif (session('role') == 'admin')
      <form method="POST" action="{{ route('actionAdminEditDateMidExam') }}">
      @endif
      @csrf
      @method('PUT')
         <div class="card-body">
            <table class="table table-striped projects">
                  <thead>
                     <tr>
                        <th style="width: 15%;">
                           Start Date Mid Exam
                        </th>
                        <th style="width: 15%;">
                           End Date Mid Exam
                        </th>
                        <th style="width: 70%;">
                           Action
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>
                           <input name="date" type="date" class="form-control" id="date" value="{{$date['date']}}" required>
                        </td>
                        <td>
                           <input name="end_date" type="date" class="form-control" id="_end_date" value="{{$date['end_date']}}" required>
                        </td>
                        <td class="project-actions text-left toastsDefaultSuccess">
                           <input role="button" type="submit" class="btn btn-success center">   
                        </td>
                     </tr>
                  </tbody>
            </table>
         </div>
      </form>
   </div>

   <div class="card card-dark mt-2">
      <div class="card-header">
         <h3 class="card-title">Schedule Mid Exam {{ $data[0]['grade_name'] }}-{{ $data[0]['grade_class'] }}</h3>

         <div class="card-tools">
               <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
               </button>
         </div>
      </div>
      <div class="card-body">
         <table class="table table-striped projects">
               <thead>
                  <tr>
                     <th >
                        #
                     </th>
                     <th>
                        Subject
                     </th>
                     <th>
                        Invigilater
                     </th>
                     <th>
                        Day
                     </th>
                     <th>
                        Start_time
                     </th>
                     <th>
                        End_time
                     </th>
                     <th style="width: 25%;">
                        Action
                     </th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($data as $el)
                  <tr id={{'index_grade_' . $el->id}}>
                     <td>
                        {{ $loop->index + 1 }}
                     </td>
                     <td>
                        <a>
                           {{$el->subject_name}}
                        </a>
                     </td>
                     <td>
                        <a>
                           {{$el->teacher_name}}
                        </a>
                     </td>
                     <td >
                        <a>
                           @if ($el->day == 1)
                              Monday
                           @elseif ($el->day == 2)
                              Thursday
                           @elseif ($el->day == 3)
                              Wednesday
                           @elseif ($el->day == 4)
                              Tuesday
                           @elseif ($el->day == 5)
                              Friday
                           @endif
                        </a>
                     </td>
                     <td >
                        {{$el->start_time}}
                     </td>
                     <td >
                        {{$el->end_time}}
                     </td>
                     
                     <td class="project-actions text-left toastsDefaultSuccess">
                        <a class="btn btn-primary btn"
                           href="{{url('/' . session('role') .'/schedules/edit/midexam') . '/'. $el->grade_id .'/' . $el->id}}">
                           <i class="fas fa-pen">
                           </i>
                           Edit
                        </a>
                        <a class="btn btn-danger btn"
                           href="{{url('/' . session('role') .'/schedules/delete/midexam') . '/' . $el->id}}">
                           <i class="fas fa-trash">
                           </i>
                           Delete
                        </a>
                     </td>
                  </tr>

                  @endforeach
               </tbody>
         </table>

      </div>
      <!-- /.card-body -->
   </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_update_midexam_schedule')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully updated the mid exam schedule in the database.',
      });
      </script>
   @endif

   @if(session('after_edit_midexam_date_schedule')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully edit date mid exam schedule in the database.',
         });    
      </script>
   @endif

   @if(session('after_edit_midexam_schedule')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully edit data mid exam schedule in the database.',
         });
      </script>
   @endif

   @if(session('after_delete_midexam')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully deleted mid exam schedule in the database.'
         });
      </script>
   @endif

@endsection
