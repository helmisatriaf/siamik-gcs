@extends('layouts.admin.master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    {{-- <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Home</li>
                    @if(session('role') == 'admin')
                        <li class="breadcrumb-item"><a href="{{url('/admin/masterAcademics')}}">Master Academic</a></li>
                    @elseif (session('role') == 'teacher')
                        <li class="breadcrumb-item"><a href="{{url('/teacher/masterAcademics')}}">Master Academic</a></li>
                    @endif
                </ol>
            </nav>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-md-3">
            <div class="inner">
                <a class="small-box d-flex align-items-center justify-content-center text-center text-md text-dark fw-bold"
                    style="min-height: 100px;background-color: #ffde9e;border-radius: 12px;" href="{{ url('/' . session('role') . '/masterAcademics/create') }}">
                    <i class="fas fa-calendar-plus mr-1"></i>
                    Create Master Academic   
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="inner">
                <a class="small-box d-flex align-items-center justify-content-center text-center text-md text-dark fw-bold"
                    style="min-height: 100px;background-color: #ffde9e;border-radius: 12px;" href="{{url('/' . session('role') .'/masterAcademics') . '/edit'}}">
                    <i class="fas fa-solid fa-pencil mr-1"></i>
                    Edit
                </a>
            </div>
        </div>

        @php
            $admin = App\Models\User::where('username', '=', 'administrator')->first();
        @endphp
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card d-flex flex-fill" style="background-color: #ffde9e;border-radius: 12px;">
                <div class="card-header border-bottom-0 text-dark fw-bold">
                    Admin SIAMIK
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-7">
                        <h2 class="lead text-dark text-md fw-bold"><b>{{$admin->name}}</b></h2>
                        <ul class="ml-4 mb-0 fa-ul text-dark fw-bold">
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: Jl. Darmo Permai</li>
                            <li class="small mt-2"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone: {{$admin->phone}}</li>
                        </ul>
                        </div>
                        <div class="col-5 text-center">
                        <img loading="lazy" src="{{asset('images/admin.png')}}" alt="user-avatar" class="img-circle img-fluid" style="width:50px;height:50px;">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-right">
                        @if (session('role') == 'superadmin')
                        <a href="#" class="btn btn-sm bg-primary"
                            data-toggle="modal" data-target="#changeData">
                            <i class="fas fa-user"></i> Change Data
                        </a>
                        @endif
                        <a href="#" class="btn btn-sm bg-teal"
                        data-toggle="modal" data-target="#changeNumber">
                        <i class="fas fa-lg fa-phone"></i> Change Number
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-2" style="background-color: #ffde9e;">
        <div class="card-header d-flex flex-column">
            <div>
                <h5 class="card-title">Data Master Academic</h5>
            </div>
            <div class="col-12 p-0 mt-1"> 
                <label for="master_academic">Choose Master Academic :</label>
                <select name="master_academic" id="master_academic" class="form-control" onchange="changeMasterAcademic(this.value)">
                    <option value="">-- SELECT MASTER ACADEMIC --</option>
                    @foreach ($masterAcademic as $ma)
                        <option value="{{ $ma->id }}" {{ $data['academic_year'] == $ma->academic_year ? 'selected' : '' }}>
                            Academic Year {{ $ma->academic_year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                @if(!empty($data))
                <tbody>
                        <tr>
                            <td style="width:25%">Academic Year</td>
                            <td style="width:75%"><a>: {{$data['academic_year']}}</a></td>
                        </tr>
                        <tr>
                            <td>Semester 1</td>
                            <td><a>: {{ \Carbon\Carbon::parse($data['semester1'])->format('d F Y') }}  until  {{ \Carbon\Carbon::parse($data['end_semester1'])->format('d F Y') }}</a></td>
                        </tr>
                        <tr>
                            <td> Semester 2</td>
                            <td><a>: {{ \Carbon\Carbon::parse($data['semester2'])->format('d F Y') }}  until  {{ \Carbon\Carbon::parse($data['end_semester2'])->format('d F Y') }}</a></td>
                        </tr>
                        <tr>
                            <td>Date Mid Report Card Semester 1</td>
                            <td><a>: {{ $data['mid_report_card1'] !== null ? \Carbon\Carbon::parse($data['mid_report_card1'])->format('d F Y') : "-" }} </a></td>
                        </tr>
                        <tr>
                            <td>Date Report Card Semester 1</td>
                            <td><a>: {{ $data['report_card1'] !== null ? \Carbon\Carbon::parse($data['report_card1'])->format('d F Y') : "-" }}</a></td>
                        </tr>
                        <tr>
                            <td>Date Mid Report Card Semester 2</td>
                            <td><a>: {{ $data['mid_report_card2'] !== null ? \Carbon\Carbon::parse($data['mid_report_card2'])->format('d F Y') : "-" }}</a></td>
                        </tr>
                        <tr>
                            <td>Date Report Card Semester 2</td>
                            <td><a>: {{ $data['report_card2'] !== null ? \Carbon\Carbon::parse($data['report_card2'])->format('d F Y') : "-" }}</a></td>
                        </tr>
                        <tr>
                            <td>Periode</td>
                            <td><a>: Semester {{ $data['now_semester'] }}</a></td>
                        </tr>
                    </tbody>
                @else
                    <p class="font-md text-bold text-center mt-2">Data kosong</p>
                @endif
            </table>
        </div>
    </div>
    
    {{-- <h5>Export Data :</h5>
    <a type="button" href="{{ url('/' . session('role') . '/export/excel') }}" class="btn btn-success mr-2">
        <i class="fa-regular fa-file-excel"></i>
        Excel
    </a>
    <a type="button" href="{{ url('/' . session('role') . '/export/pdf') }}" class="btn btn-success mr-2">
        <i class="fa-regular fa-file-pdf"></i>
        PDF
    </a> --}}
    

</div>

<div class="modal fade" id="changeNumber" tabindex="-1" role="dialog" aria-labelledby="changeNumberLabel"
aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    <i class="fas fa-lg fa-phone mr-2"></i>Change Phone Admin
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body p-4">

                <form action="{{route('change.number.phone')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">+62</span>
                        <input type="phone" name="phone" class="form-control" placeholder="contoh : 89********" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtnUpdate">
                            <i class="fas fa-save mr-2"></i>Update Number
                        </button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeData" tabindex="-1" role="dialog" aria-labelledby="changeNumberLabel"
aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    <i class="fas fa-user mr-2"></i>Change Data Admin
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form action="{{route('actionDataAdmin')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                           <label for="materi">Name  <span style="color: red">*</span></label>
                           <input type="text" name="name" value="{{$admin->name}}">
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtnUpdate">
                            <i class="fas fa-save mr-2"></i>Change Data
                        </button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
    function changeMasterAcademic(selectedId) {
        if (selectedId) {
            fetch(`/changeMasterAcademic/${selectedId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload(); // Reload page after successful change
                } else {
                    alert('Failed to update Master Academic. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating Master Academic.');
            });
        }
    }
</script>

@if(session('after_create_masterAcademic'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully created new master academic in the database.'
        });
    </script>
@endif

@if(session('after_update_masterAcademic'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully updated the master academic in the database.'
        });
    </script>
@endif

@if(session('after_delete_masterAcademic'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successfully',
            text: 'Successfully deleted the type schedule in the database.'
        });
    </script>
@endif

@endsection
