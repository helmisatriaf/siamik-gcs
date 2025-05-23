@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                @if (session('role') == 'superadmin')
                <li class="breadcrumb-item"><a href="{{url('/superadmin/reports')}}">Reports</a></li>
                @elseif (session('role') == 'admin')
                <li class="breadcrumb-item"><a href="{{url('/admin/reports')}}">Reports</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Grade Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Detail Report Subject</a></li>
            </ol>
        </nav>
      </div>
    </div>

    @if (sizeof($data) != 0)
         <!-- SUBJECT -->
        @foreach ($data['grade'] as $dg)
            <div class="card card-dark mt-5">
                <div class="card-header">
                    <h3 class="card-title">{{ $dg->name }} - {{ $dg->class }}</h3>
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
                                <th style="width: 10%">#</th>
                                <th style="width: 25%">Subject</th>
                                <th style="width: 25%">Teacher Subject</th>
                                <th style="width: 30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['subject'] as $pr)
                                <tr id="{{ 'index_grade_' . $pr->id }}">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a>  {{ $pr->subject_name}}</a></td>
                                    <td><a>  {{ $pr->teacher_name }}</a></td>
                                    @if (session('role') == 'superadmin')
                                    <td class="project-actions text-right toastsDefaultSuccess">
                                        <a class="btn btn-primary btn"
                                            href="{{url('superadmin/reports') . '/detailSubject/student' . $pr->id}}">
                                            <i class="fas fa-folder">
                                            </i>
                                            View
                                        </a>
                                    </td>
                                    @elseif (session('role') == 'admin')
                                    <td class="project-actions text-right toastsDefaultSuccess">
                                        <a class="btn btn-primary btn"
                                            href="{{url('admin/reports') . '/detailSubject/student' . $pr->id}}">
                                            <i class="fas fa-folder">
                                            </i>
                                            View
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
        <!-- END SUBJECT -->

        <!-- END TABLE -->
   @else
      <p class="text-center">You don't have data grade</p>
   @endif
</div>

<link rel="stylesheet" href="{{ asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

@endsection
