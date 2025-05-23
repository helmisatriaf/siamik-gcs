@extends('layouts.admin.master')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    {{-- <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Schedules</li>
                    <li class="breadcrumb-item active" aria-current="page">Type Schedules</li>
                </ol>
            </nav>
        </div>
    </div> --}}

    <div class="row">
        <a type="button" href="{{ url('/' . session('role') . '/typeSchedules/create') }}" class="btn btn-danger btn mx-2">   
            <i class="fa-solid fa-calendar-plus"></i>
            New Type Schedule
        </a>
    </div>

    <div class="card card-dark mt-2">
        <div class="card-header">
            <h3 class="card-title">Type Schedules</h3>

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
                        <th >
                           #
                        </th>
                        <th>
                           Type Schedules
                        </th>
                        <th>
                           Color
                        </th>
                        <th style="width: 70%">
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
                                {{$el->name}}
                           </a>
                        </td>
                        <td>
                           <a style="color:{{$el->color}}">
                                {{$el->color}}
                           </a>
                        </td>
                        
                        <td class="project-actions text-left toastsDefaultSuccess">
                            <a class="btn btn-info btn"
                              href="{{url('/' . session('role') .'/typeSchedules') . '/edit/' . $el->id}}">
                              {{-- <i class="fa-solid fa-user-graduate"></i> --}}
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                            </a>
                            @if (session('role') == 'superadmin' || session('role') == 'admin')
                                <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteTypeSchedule" data-id="{{ $el->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modalDeleteTypeSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Delete type schedule</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">Are you sure want to delete this type?</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a class="btn btn-danger btn" id="confirmDelete">Yes delete</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    $('#modalDeleteTypeSchedule').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var confirmDelete = document.getElementById('confirmDelete');
        confirmDelete.href = "{{ url('/' . session('role') . '/typeSchedules/delete') }}/" + id;
    });
});
</script>

    @if(session('after_create_typeSchedule')) 
      <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new type schedule in the database.',
        });
      </script>
    @endif
  
    @if(session('after_update_typeSchedule')) 
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Successfully',
                text: 'Successfully updated the type schedule in the database.',
            });
        </script>
    @endif

    @if(session('after_delete_typeSchedule')) 
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Successfully',
                text: 'Successfully delete the type schedule in the database.',
            });
        </script>
    @endif

@endsection
