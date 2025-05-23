@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

   <div class="row">
      <div class="col">
      <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
         <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="{{url('' .session('role'). '/schedules/schools')}}">Schedule Academic</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manage</li>
         </ol>
      </nav>
      </div>
   </div>

   <div class="card card-dark ">
      <div class="card-header">
         <h3 class="card-title">Manage Schedule</h3>

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
                        Type Schedule
                     </th>
                     <th>
                        Date
                     </th>
                     <th>
                        Note
                     </th>
                     <th class="text-center">
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
                           {{$el->type_schedule}}
                        </a>
                     </td>
                     <td>
                        <a>
                           @if($el->end_date != null)
                                 ({{ $el->date }}) - ({{ $el->end_date }})
                           @else
                                 {{ $el->date }}
                           @endif
                        </a>
                     </td>
                     <td>
                        <a>
                           {{$el->note}}
                        </a>
                     </td>
                     
                     <td class="project-actions text-right toastsDefaultSuccess">
                        <a type="button" data-toggle="modal" data-target="#modalEditOtherSchedule" class="btn btn-primary btn edit-schedule-btn" data-id="{{ $el->id }}">
                           <i class="fas fa-pen"></i> Edit
                        </a>

                        <a class="btn btn-danger btn"
                           href="{{url('/' . session('role') .'/schedules/otherSchedule/delete') . '/' . $el->id}}">
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
   </div>   
</div>

<div class="modal fade" id="modalEditOtherSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLongTitle">Edit Schedule</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <form method="POST">
                  @csrf
                  <div class="card card-dark">
                        <div class="card-body">
                           <div class="form-group row">
                              <div class="col-md-12">
                                    <label for="type_schedule">Type Schedule<span style="color: red"> *</span></label>
                                    <select required name="type_schedule" class="form-control" id="type_schedule">
                                       <option value="">-- TYPE SCHEDULE --</option>
                                          @foreach($typeSchedule as $ts)
                                             <option value="{{ $ts->id }}" {{ old('type_schedule') == $ts->id ? 'selected' : '' }}>{{ $ts->name }}</option>
                                             @endforeach
                                 </select>
                                    @if($errors->has('type_schedule'))
                                       <p style="color: red">{{ $errors->first('type_schedule') }}</p>
                                    @endif
                              </div>
                           </div>

                           <div class="form-group row">
                              <div class="col-md-12">
                                    <label for="date">Date<span style="color: red"> *</span></label>
                                    <input name="date" type="date" class="form-control" id="date" required>
                                    @if($errors->has('date'))
                                       <p style="color: red">{{ $errors->first('date') }}</p>
                                    @endif
                              </div>
                           </div>

                           <div class="form-group row">
                              <div class="col-md-12">
                                    <label for="end_date">Until<span style="color: red"></span></label>
                                    <input name="end_date" type="date" class="form-control" id="_end_date">
                                    @if($errors->has('end_date'))
                                       <p style="color: red">{{ $errors->first('end_date') }}</p>
                                    @endif
                              </div>
                           </div>

                           <div class="form-group row">
                              <div class="col-md-12">
                                    <label for="notes">Notes<span style="color: red"> *</span></label>
                                    <textarea required name="notes" class="form-control" id="notes" cols="10" rows="1"></textarea>
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


<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>


<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Menggunakan event delegation untuk menambahkan event listener ke semua tombol dengan kelas 'edit-schedule-btn'
      document.querySelectorAll('.edit-schedule-btn').forEach(function(button) {
         button.addEventListener('click', function() {
            var scheduleId = this.getAttribute('data-id');
            
            // Membuat permintaan AJAX menggunakan Fetch API
            fetch('/get-schedule/' + scheduleId)
               .then(function(response) {
                  if (!response.ok) {
                        throw new Error('Network response was not ok');
                  }
                  return response.json();
               })
               .then(function(data) {
                  // Isi modal dengan data yang diambil
                  document.getElementById('type_schedule').value = data.type_schedule_id;
                  document.getElementById('date').value = data.date;
                  document.getElementById('_end_date').value = data.end_date;
                  document.getElementById('notes').value = data.note;

                  // Ubah atribut action dari form
                  document.querySelector('#modalEditOtherSchedule form').setAttribute('action', '/update-schedule/' + scheduleId);
               })
               .catch(function(error) {
                  console.error('Error fetching schedule data:', error);
               });
         });
      });
   });

</script>


   @if(session('after_update_schedule')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the schedule in the database.',
         });
      </script>
   @endif

   @if(session('after_edit_schedule')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully edit schedule in the database.',
         });
      </script>
   @endif

   @if(session('after_delete_schedule')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully deleted the schedule in the database.',
         });
      </script>
   @endif

@endsection
