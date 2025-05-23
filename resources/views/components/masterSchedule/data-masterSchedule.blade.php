@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">

   <a type="button" href="{{ url('/' . session('role') . '/masterSchedules/create') }}" class="btn btn-success btn mt-5">
      <i class="fa-solid fa-user-plus"></i>
      Add master schedule
   </a>

    <div class="card card-dark mt-2">
        <div class="card-header">
            <h3 class="card-title">Master Schedules</h3>

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
                        <th> No </th>
                        <th> Name</th>
                        <th> Start Date</th>
                        <th> End Date</th>
                        <th style="width: 25%">
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
                                <a>{{$el->name}}</a>
                            </td>
                            <td>
                                <a>{{ date('F j, Y', strtotime($el->date)) }}</a>
                            </td>
                            <td>
                                <a>{{ date('F j, Y', strtotime($el->end_date)) }}</a>
                            </td>
                            <td class="project-actions text-right toastsDefaultSuccess">
                                <a class="btn btn-info btn"
                                    href="{{url('/' . session('role') .'/masterSchedules') . '/edit/' . $el->id}}">
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit
                                </a>
                                @if (session('role') == 'superadmin')
                                    <a class="btn btn-danger btn" data-toggle="modal" data-target="#modalDeleteMasterSchedule">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                    </a>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="modalDeleteMasterSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Delete master schedule</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure want to delete this master schedule?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a class="btn btn-danger btn" href="{{url('/' . session('role') .'/masterSchedules') . '/delete/' . $el->id}}">Yes delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                @else
                    <p class="font-md text-bold text-center mt-2">Data kosong</p>
                @endif
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@if(session('after_create_masterSchedule'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new type schedule in the database.'
        });
    </script>
@endif

@if(session('after_update_masterSchedule'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the type schedule in the database.'
        });
    </script>
@endif

@if(session('after_delete_masterSchedule'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully deleted the type schedule in the database.'
        });
    </script>
@endif

@endsection
