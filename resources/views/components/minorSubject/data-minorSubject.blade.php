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
                  <li class="breadcrumb-item active" aria-current="page">Minor Subject</li>
               </ol>
            </nav>
      </div>
   </div>  


   <div class="row">
      <a type="button" href="{{ url('/' . session('role') . '/minorSubjects/create') }}" class="btn btn-success btn mx-2">   
         <i class="fa-solid fa-book"></i>
         Add Minor subject
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
                              <a class="btn btn-danger btn" data-toggle="modal" data-target="#exampleModalCenter_{{ $da->subject->id }}">
                                 <i class="fas fa-trash"></i> Delete
                              </a>
                           @endif
                     </td>
                  </tr>

                  <!-- Modal -->
                  <div class="modal fade" id="exampleModalCenter_{{ $da->subject->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="exampleModalLongTitle">Delete subject</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                                 Are you sure want to delete minor subject?
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
                  <p>Minor Subject Empty !!</p>   
               </div>
               @endif
         </table>
      </div>
      <!-- /.card-body -->
   </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

   @if(session('after_create_minorSubject')) 
      <script>
         Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new minor subject in the database.',
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
            text: 'Successfully deleted minor subject in the database.',
        });
      </script>
  @endif

@endsection
