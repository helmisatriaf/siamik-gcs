@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
   {{-- <div class="row">
      <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
               <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item">Home</li>
                  <li class="breadcrumb-item active" aria-current="page">Ekstra Culicular Academy</li>
               </ol>
            </nav>
      </div>
   </div>   --}}

   <div class="row">
      <a type="button" href="{{ url('/eca/create') }}" class="btn btn-success btn mx-2">   
         <i class="fa-solid fa-plus"></i>
         Create ECA
      </a>
   </div>

   <div class="card card-dark mt-2">
      <div class="card-header">
         <h3 class="card-title">Extra Curicullar Academic</h3>

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
                     Name
                  </th>
                  <th>
                     Icon
                  </th>
                  <th style="width: 80%">
                     Action
                  </th>
               </tr>
            </thead>
            @if (!empty($data['data']))
            <tbody>
               @foreach ($data['data'] as $el)
               <tr id={{'index_grade_' . $el->id}}>
                  <td>
                        {{ $loop->index + 1 }}
                  </td>
                  <td>
                     <a>
                           {{$el->name}}
                     </a>
                  </td>
                  <td>
                     <div class="position-relative" style="width: 100px;">
                        <!-- Input file (disembunyikan) -->
                        <input type="file" class="profileInput d-none" data-id="{{ $el->id }}" 
                              accept="image/png, image/jpg, image/jpeg">

                        <!-- Gambar Profil -->
                        <img src="{{ asset('storage/'.$el->icon) }}" 
                           alt="avatar" class="profileImage img-fluid" 
                           style="width: 50px; height: 50px; cursor: pointer;" 
                           data-id="{{ $el->id }}">

                        <!-- Overlay Edit Text -->
                        <div class="position-absolute top-50 start-50 translate-middle text-white bg-dark bg-opacity-50 
                                    rounded-circle d-flex align-items-center justify-content-center editOverlay"
                           style="width: 50px; height: 50px; opacity: 0; transition: opacity 0.3s;" 
                           data-id="{{ $el->id }}">
                           <span>Edit</span>
                        </div>
                     </div>
                  </td>
                  
                  <td class="project-actions text-left toastsDefaultSuccess">
                     <a class="btn btn-info btn"
                        href="{{url('/eca') . '/add/' . $el->id}}">
                        <i class="fas fa-user-plus">
                        </i>
                        Add Student
                     </a>
                     <!-- <a class="btn btn-warning btn"
                        href="{{url('/' . session('role') .'/eca') . '/edit/' . $el->id}}">
                        <i class="fas fa-pencil-alt">
                        </i>
                        Edit
                     </a> -->
                     <a class="btn btn-warning btn"
                        href="{{url('/eca') . '/view/' . $el->id}}">
                        <i class="fas fa-pencil">
                        </i>
                        Manage
                     </a>
                     @if (session('role') == 'superadmin' || session('role') == 'admin')
                        <a class="btn btn-danger btn" href="{{url('/' . session('role') .'/eca/delete/')  . '/' . $el->id}}">
                        <i class="fas fa-trash"></i>
                        Delete
                        </a>
                     @endif
                  </td>
               </tr>
               @endforeach
            @else
               <tr colspan="3">Eksta Culicular Academy Empty</tr>
            @endif
            </tbody>
         </table>

         
      </div>
      <!-- /.card-body -->
   </div>
  
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
   document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll(".profileImage").forEach(img => {
         img.addEventListener("mouseenter", function () {
            const id = this.getAttribute("data-id");
            document.querySelector(`.editOverlay[data-id="${id}"]`).style.opacity = '0';
         });

         img.addEventListener("mouseleave", function () {
            const id = this.getAttribute("data-id");
            document.querySelector(`.editOverlay[data-id="${id}"]`).style.opacity = '0';
         });

         img.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            document.querySelector(`.profileInput[data-id="${id}"]`).click();
         });
      });

      document.querySelectorAll(".profileInput").forEach(input => {
         input.addEventListener("change", function (event) {
            previewImage(event, this.getAttribute("data-id"));
         });
      });
   });

   function previewImage(event, id) {
      const file = event.target.files[0]; 
      if (!file) return;

      let formData = new FormData();
      formData.append('file', file);
      formData.append('id', id);

      fetch("{{ route('change.icon.eca') }}", {
         method: "POST",
         body: formData,
         headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
         }
      })
      .then(response => response.json())
      .then(data => {
         if (data.success) {
            Swal.fire({
               icon: 'success',
               title: data.message,
            });

            // Perbaikan: Menggunakan ID unik agar hanya gambar yang sesuai diperbarui
            const reader = new FileReader();
            reader.onload = function (e) {
               const imgElement = document.querySelector(`.profileImage[data-id="${id}"]`);
               if (imgElement) {
                  imgElement.src = e.target.result;
               }
            };
            reader.readAsDataURL(file);
         } else {
            Swal.fire({
               icon: 'error',
               title: 'Oops... failed to change profile',
            });
         }
      })
      .catch(error => {
         Swal.fire({
            icon: 'error',
            title: 'Oops... something went wrong',
         });
      });
   }
</script>

   @if(session('after_create_eca')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new eca in the database.',
        });
      </script>
  @endif

  @if(session('after_add_student_eca')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully add student eca in the database.',
         });
      </script>
  @endif

  @if(session('after_update_eca')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the eca in the database.',
         });
      </script>
   @endif

   @if(session('after_delete_eca')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully deleted eca in the database.',
        });
      </script>
  @endif



@endsection
