@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   {{-- <div class="row">
      <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item">Monthly Activities</li>
                  <li class="breadcrumb-item active" aria-current="page">Data</li>
               </ol>
            </nav>
      </div>
   </div>   --}}

   <div class="row">
      <a type="button" class="btn btn-success btn mx-2" data-toggle="modal" data-target="#addMonthlyActivities">   
         <i class="fa-solid fa-plus"></i> 
         Create Monthly Activity
      </a>
   </div>


    <div class="card card-dark mt-2">
        <div class="card-header">
            <h3 class="card-title">Monthly Activity</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>
                           #
                        </th>
                        <th style="width: 15%">
                           Monthly Activity
                        </th>
                        <th style="width: 80%">
                           Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                     @if (count($data) !== 0)
                        @foreach ($data as $el)
                           <tr id={{'index_grade_' . $el->id}}>
                                 <td>{{ $loop->index + 1 }}</td>
                                 <td>{{$el->name}}</td>
                                 
                                 <td class="project-actions text-left toastsDefaultSuccess">
                                    <a class="btn btn-warning btn" data-toggle="modal" data-target="#editMonthlyActivities-{{$el->id}}">
                                       <i class="fas fa-pencil-alt">
                                       </i>
                                       Edit
                                    </a>
                                    @if (session('role') == 'superadmin' || session('role') == 'admin')
                                    {{-- <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteSubject-{{$el->id}}">
                                       <i class="fas fa-trash"></i>
                                       Delete
                                    </a> --}}
                                    <a class="btn btn-danger btn" 
                                        data-toggle="modal" 
                                        data-target="#modalDeleteSubject" 
                                        data-id="{{ $el->id}}"
                                        data-subject="{{ $el->name }}">
                                    <i class="fas fa-trash"></i> Delete {{$el->id}}
                                    </a>
                                    @endif
                                 </td>
                           </tr>

                           <!-- Modal -->
                           {{-- EDIT --}}
                           <div class="modal" id="editMonthlyActivities-{{$el->id}}" tabindex="-1" aria-labelledby="exampleModalCenterTitle" role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Change Data Monthly Activities</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                    </button>
                                 </div>
                                 <div class="modal-body">
                                    Monthly Activities
                                    <input name="class" type="text" class="form-control" id="change-name-{{$el->id}}" placeholder="" value="{{$el->name}}">
                                    <input type="hidden" value="{{$el->id}}" name="data_id" id="data-id-{{$el->id}}">
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                                    <a class="btn btn-danger btn" id="confirmChange-{{$el->id}}">Change</a>
                                 </div>
                              </div>
                           </div>                           
                           
                        @endforeach
                     @else
                        
                     @endif
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

{{-- ADD --}}
<div class="modal fade" id="addMonthlyActivities" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" >Add Data Monthly Activities</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="col-md-12">
               <form method="POST" action={{route('actionCreateMonthly')}}>
                  @csrf
                  <table class="table table-striped table-bordered">
                     <thead>
                        <th>Name Activities</th>
                        <th>Grades</th>
                        <th>Action</th>
                     </thead>
                     <tbody id="scheduleTableBody">
                        <tr>
                           <td>
                              <input name="monthly_activities[0][name]" class="form-control" id="monthlyActivities">
                           </td>
                           <td>
                              <select name="monthly_activities[0][grades]" class="form-control">
                                 <option value="">-- Select Grade --</option>
                                 <option value="lower">Kindergarten</option>
                                 <option value="upper">Primary - Secondary</option>
                              </select>
                           </td>
                           
                           <td>
                              <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Baris" id="tambah"><i class="fa fa-plus"></i></button>
                              <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris"><i class="fa fa-times"></i></button>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <input role="button" type="submit" class="btn btn-success">
               </form>
            </div>
         </div>   
      </div>
   </div>
</div>


{{-- DELETE --}}
 <!-- Modal -->
 <div class="modal fade" id="modalDeleteSubject" tabindex="-1" role="dialog" aria-labelledby="modalDeleteSubjectLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLongTitle">Delete Monthly Activity</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body" id="modalBodyContentSubject">
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <a href="#" class="btn btn-danger" id="confirmDelete">Yes, delete</a>
           </div>
       </div>
   </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_subject')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new subject in the database.',
        });
      </script>
   @endif

   @if(session('after_update_subject')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the subject in the database.'
         });
      </script>
   @endif

   @if(session('after_delete_subject')) 
      <script>
            Swal.fire({
              icon: 'success',
              title: 'Successfully',
              text: 'Successfully deleted subject in the database.',
        });
      </script>
  @endif

  {{-- ACTION DELETE & UPDATE --}}
   <script>
      const confirmChangeButtons = document.querySelectorAll('[id^="confirmChange-"]');

      confirmChangeButtons.forEach(button => {
         button.addEventListener('click', function(event) {
            const id = this.id.split('-')[1]; // Get the ID from the button's ID
            const changeName = document.getElementById(`change-name-${id}`).value; // Get the selected teacher from the corresponding modal
            const dataId = document.getElementById(`data-id-${id}`).value; // Get the selected teacher from the corresponding modal

            // console.log(changeName);

            const form = {
                  id: parseInt(dataId, 10),
                  change_name: changeName,
            };

            // console.log(form);
            // // Prepare options for the fetch request
            const options = {
                  method: 'PUT',
                  headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                     'Content-Type': 'application/json' // Set the content type to JSON
                  },
                  body: JSON.stringify(form) // Convert the form object to a JSON string
            };

            // Send the form data using fetch
            fetch("{{ route('actionUpdateMonthly') }}", options)
                  .then(response => response.json())
                  .then(data => {
                     // Handle the server response
                     if (data.success) {
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

      document.addEventListener('DOMContentLoaded', function() {
         let row = 1;

         function addRow() {
            var newRow = `<tr>
                  <td>
                     <input name="monthly_activities[${row}][name]" class="form-control" id="monthlyActivities_${row}"></input>
                  </td>
                  <td>
                     <select name="monthly_activities[${row}][grades]" id="grades_${row}" class="form-control">
                        <option value="">-- Select Grade --</option>
                        <option value="lower">Kindergarten</option>
                        <option value="upper">Primary - Secondary</option>
                     </select>
                  </td>
                  <td>
                     <button type="button" class="btn btn-success btn-sm btn-tambah mt-1" title="Tambah Baris" id="tambah"><i class="fa fa-plus"></i></button>
                     <button type="button" class="btn btn-danger btn-sm btn-hapus mt-1 d-none" title="Hapus Baris"><i class="fa fa-times"></i></button>
                  </td>
            </tr>`;
            $('#scheduleTableBody').append(newRow);
            row++;
         

            updateHapusButtons();
         }

         function updateHapusButtons() {
            const rows = $('#scheduleTableBody tr');

            rows.each(function(index, row) {
                  var tambahButton = $(row).find('.btn-tambah');
                  var hapusButton = $(row).find('.btn-hapus');

                  if (rows.length === 1) {
                     // Jika hanya ada satu baris, hanya tampilkan tombol "Tambah"
                     tambahButton.removeClass('d-none');
                     hapusButton.addClass('d-none');
                  } else {
                     // Baris terakhir tampilkan tombol "Tambah" dan "Hapus"
                     if (index === rows.length - 1) {
                        tambahButton.removeClass('d-none');
                        hapusButton.removeClass('d-none');
                     } else {
                        // Baris lainnya hanya tampilkan tombol "Hapus"
                        tambahButton.addClass('d-none');
                        hapusButton.removeClass('d-none');
                     }
                  }
            });
         }

         $('#scheduleTableBody').on('click', '.btn-tambah', function() {
            addRow();
         });

         $('#scheduleTableBody').on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();
            updateHapusButtons();
         });

         // Initial call to update the visibility of the "Hapus" and "Tambah" buttons
         updateHapusButtons();







         // DELETE ACTIVITY
         const modalSubject = document.getElementById('modalDeleteSubject');
         const modalBodyContentSubject = document.getElementById('modalBodyContentSubject');
         const confirmDelete = document.getElementById('confirmDelete');
         
         document.querySelectorAll('[data-target="#modalDeleteSubject"]').forEach(button => {
               button.addEventListener('click', function () {
                  // Ambil data dari atribut tombol
                  id = this.getAttribute('data-id'); // Simpan ID ke dalam variable
                  const subjectName = this.getAttribute('data-subject');

                  // Update konten modal
                  modalBodyContentSubject.innerHTML = `Are you sure want to delete this group <strong>${subjectName}</strong>?`;
               });
         });
         confirmDelete.addEventListener('click', function () {
            const form = {
               id: parseInt(id, 10),
            };

            // Konfigurasi request
            const options = {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                body: JSON.stringify(form)
            };

            fetch("{{ route('deleteMonthly') }}", options)
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                  Swal.fire({
                        icon: 'success',
                        text: 'Data Berhasil Dihapus',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                  }).then(() => {
                        location.reload();
                  });
               } else {
                  Swal.fire({
                        icon: 'error',
                        text: 'Maaf ada kesalahan',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                  }).then(() => {
                        location.reload();
                  });
               }
            })
            .catch(error => {
               console.error('Fetch error:', error);
            });
         });
      })
      
   </script>
@endsection
