@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   <div class="row">
      <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item">Subjects</li>
                  <li class="breadcrumb-item active" aria-current="page">Major Subject</li>
               </ol>
            </nav>
      </div>
   </div>  
   
   <div class="row">
      <a type="button" class="btn btn-success btn mx-2" data-toggle="modal" data-target="#addMajorSubjects">   
         <i class="fa-solid fa-book"></i>
         Add Major subject
      </a>

   </div>
   
   <div class="card card-dark mt-2">
      <div class="card-header">
         <h3 class="card-title">Subjects</h3>

         <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
               <i class="fas fa-minus"></i>
            </button>
         </div>
      </div>
      <div class="card-body p-0">
         <table class="table table-striped projects">
            @if(!empty($data))
               <thead>
                  <tr>
                     <th>
                        #
                     </th>
                     <th style="width: 15%;">
                        Subjects
                     </th>
                     <th style="width: 80%;">
                        Action
                     </th>
                  </tr>
               </thead>
               <tbody>
               @foreach ($data as $da)
                  <tr id="{{ 'index_grade_' . $da->subject->id }}">
                     <td>{{ $loop->index + 1 }}</td>
                     <td>
                           <a>{{ $da->subject->name_subject }}</a>
                     </td>
                     <td class="project-actions text-left toastsDefaultSuccess">
                           @if (session('role') == 'superadmin' || session('role') == 'admin')
                              <a class="btn btn-danger btn" data-toggle="modal" data-target="#deleteMajorSubject{{ $da->subject->id }}">
                                 <i class="fas fa-trash"></i> Delete
                              </a>
                           @endif
                     </td>
                  </tr>

                  <!-- Modal -->
                  <div class="modal fade" id="deleteMajorSubject{{ $da->subject->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="exampleModalLongTitle">Delete subject</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                                 Are you sure want to delete major subject?
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                 <a class="btn btn-danger btn" href="{{ url('/' . session('role') . '/subjects/delete/' . $da->subject->id) }}">Yes delete</a>
                              </div>
                           </div>
                     </div>
                  </div>
               @endforeach

               </tbody>
               @else
               <div class="text-center">
                  <p>Major Subject Empty !!</p>   
               </div>
               @endif
         </table>
      </div>
      <!-- /.card-body -->
   </div>

   {{-- ADD --}}
   <div class="modal fade" id="addMajorSubjects" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" >Add Data Major Subjects</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="form-group row">
               <div class="col-md-12">
                  <label for="major_subject">Major Subject<span style="color: red">*</span></label>
                  <select required name="major_subject[]" class="js-select2 form-control" id="major_subject" multiple="multiple">
                     <option value="" > SELECT MAJOR SUBJECT </option>
                     @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name_subject }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
            <a class="btn btn-danger btn" id="confirmAdd">Change</a>
         </div>
      </div>
   </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_create_majorSubject')) 
   <script>
      Swal.fire({
         icon: 'success',
         title: 'Successfully',
         text: 'Successfully created new major subject in the database.',
      });
   </script>
@endif
  
@if(session('after_update_subject')) 
   <script>
      Swal.fire({
         icon: 'success',
         title: 'Successfully',
         text: 'Successfully updated the major subject in the database.',
      });
   </script>
@endif

@if(session('after_delete_subject')) 
   <script>
      Swal.fire({
         icon: 'success',
         title: 'Successfully',
         text: 'Successfully deleted major subject in the database.',
      });
   </script>
@endif

<script>
   $(document).ready(function() {
    // Inisialisasi Select2 secara global
    $('.js-select2').select2();

    // Re-inisialisasi Select2 setiap kali modal ditampilkan
    $('#addMajorSubjects').on('shown.bs.modal', function() {
        $('#major_subject').select2({
            placeholder: "SELECT MAJOR SUBJECT",
        });
    });

    const confirmAdd = document.querySelectorAll('[id^="confirmAdd"]');

    confirmAdd.forEach(button => {
         button.addEventListener('click', function(event) {
               const majorSubject = document.getElementById("major_subject").value;
               
               console.log(majorSubject);

               const form = {
                  major_subject: parseInt(majorSubject, 10),
               };

               // Prepare options for the fetch request
               // const options = {
               //    method: 'POST',
               //    headers: {
               //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               //       'Content-Type': 'application/json' // Set the content type to JSON
               //    },
               //    body: JSON.stringify(form) // Convert the form object to a JSON string
               // };

               // // Send the form data using fetch
               // fetch("{{ route('actionAdminChangeGradeSubjectMultiTeacher') }}", options)
               //    .then(response => response.json())
               //    .then(data => {
               //       // Handle the server response
               //       if (data.success) {
               //             console.log(data.tes);
               //             Swal.fire({
               //                icon: 'success',
               //                text: 'Data Berhasil Diubah',
               //                showConfirmButton: false, // Hide the confirm button
               //                timer: 1500, // Auto close after 2000 milliseconds (2 seconds)
               //                timerProgressBar: true // Optional: show a progress bar
               //             }).then(() => {
               //                // Optionally, you can still perform actions after the modal closes
               //                location.reload();
               //             });

               //       } else {
               //             Swal.fire({
               //                icon: 'error',
               //                text: 'Maaf ada kesalahan',
               //                showConfirmButton: false, // Hide the confirm button
               //                timer: 1500, // Auto close after 2000 milliseconds (2 seconds)
               //                timerProgressBar: true // Optional: show a progress bar
               //             }).then(() => {
               //                // Optionally, you can still perform actions after the modal closes
               //                location.reload();
               //             });
               //       }
               //    })
               //    .catch(error => {
               //       console.error('Fetch error:', error);
               //    });
         });
      });
});

</script>

@endsection
