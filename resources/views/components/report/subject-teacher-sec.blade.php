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
            <li class="breadcrumb-item active" aria-current="page">Grade Subject</li>
          </ol>
        </nav>
      </div>
    </div>

    @if (sizeof($data) != 0)
         <!-- SUBJECT -->
        @foreach ($data['grade'] as $dg)
            <div class="card card-dark mt-1">
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
                                <th style="width: 5%">#</th>
                                <th style="width: 15%">Subject</th>
                                <th style="width: 15%">Teacher Subject</th>
                                <th style="width: 10%">Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['subject'] as $pr)
                                <tr id="{{ 'index_grade_' . $pr->id }}">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a>  {{ $pr->subject_name}}</a></td>
                                    <td><a>  {{ $pr->teacher_name }}</a></td>
                                    @if (session('role') == 'superadmin')
                                    <td class="project-actions text-left toastsDefaultSuccess">
                                        <a class="btn btn-primary btn"
                                            href="{{url('superadmin/reports') . '/detailSubjectSec/student/' . $dg->id . '/' . $pr->subject_id}}">
                                            <i class="fas fa-eye">
                                            </i>
                                            View
                                        </a>
                                    </td>
                                    @elseif (session('role') == 'admin')
                                    <td class="project-actions text-left toastsDefaultSuccess">
                                        <a class="btn btn-primary btn"
                                            href="{{url('admin/reports') . '/detailSubjectSec/student/' . $dg->id . '/' . $pr->subject_id}}">
                                            <i class="fas fa-eye">
                                            </i>
                                            View
                                        </a>
                                    </td>
                                    @endif
                                    <td>
                                        @if ($pr->status == 1)
                                            Already Submitted
                                        @else
                                            {{ $pr->status }}
                                        @endif
                                    </td>
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
