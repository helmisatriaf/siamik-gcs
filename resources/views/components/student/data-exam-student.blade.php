@extends('layouts.admin.master')
@section('content')

<style>
    .icon-wrapper {
        text-align: center;
    }
    .icon-wrapper i {
        font-size: 200px;
        color: #ccc;
    }
    .icon-wrapper p {
        margin: 0; /* Add margin for spacing */
        font-size: 1.2rem;
        color: black;
        text-align: center;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <div class="card" style="background-color: #ffde9e;border-radius: 12px;">
        <div class="p-3">
            @if (session('role') == 'student')
            <form class="row align-items-center" action="{{ route('student.dashboard.exam') }}" method="GET">
            @elseif (session('role') == 'parent')
            <form class="row align-items-center" action="{{ route('parent.dashboard.exam') }}" method="GET">
            @endif
                <div class="col-md-3">
                    <div class="form-group">
                        @php
                            $selectedOrder = $form->sort;
                        @endphp
            
                        <label>Sort by:</label>
                        <select name="order" class="form-control" id="subject-select" onchange="this.form.submit()">
                            <option value="all" {{ $selectedOrder === 'all' ? 'selected' : '' }}>All Subjects</option>
                            @foreach ($subjects->subject as $subject)
                                <option value="{{ $subject['id'] }}" {{ $selectedOrder == $subject['id'] ? 'selected' : '' }}>
                                    {{ ucwords($subject['name_subject']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        @php
                            $selectType = $form->type;
                        @endphp
    
                        <label>Type Assessment:</label>
                        <select name="type" class="form-control" id="type-select" onchange="this.form.submit()">
                            <option value="all" {{ $selectType === 'all' ? 'selected' : '' }}>All Types</option>
                            @foreach ($type as $type)
                                <option value="{{ $type['id'] }}" {{ $selectType == $type['id'] ? 'selected' : '' }}>
                                    {{ ucwords($type['name']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(sizeof($data) !== 0)
        <div class="row">
            @foreach ($data as $el)
            <div class="col-lg-4 col-md-6 col-12">
                <div class="small-box position-relative p-3 d-flex flex-column" style="background-color: #ffde9e;border-radius: 12px;">
                    <a id="view" data-id="{{ $el->id }}" href="javascript:void(0);">
                        <div class="ribbon-wrapper ribbon-lg">
                            @if($el->is_active)
                            <div class="ribbon bg-dark text-xs">ongoing</div>
                            @else
                            <div class="ribbon bg-light text-xs">completed</div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <!-- Avatar -->
                            <div>
                                <img loading="lazy" src="{{ asset('storage/'.$el->icon) }}" 
                                    alt="avatar" class="profileImage img-fluid" 
                                    style="width: 32px; height: 32px; cursor: pointer;">
                            </div>
                        
                            <!-- Informasi Ujian -->
                            <div class="pl-2 text-dark">
                                <p>
                                    <strong>{{ ucwords($el->type_exam) }} | {{ $el->subject_name }}</strong> <br>
                                    {{-- {{ $el->grade_name }} - {{ $el->grade_class }} <br> --}}
                                    @switch($el->model)
                                        @case("mc")
                                            <span class="text-sm">
                                                Model : Multiple Choice <br>
                                            </span>
                                            @break
                                        @case("essay")
                                            <span class="text-sm">
                                                Model : Essay <br>
                                            </span>
                                            @break
                                        @case("mce")
                                            <span class="text-sm">
                                                Model : Multiple Choice & Essay <br>
                                            </span>
                                            @break
                                        @default
                                            <span class="text-sm">
                                                Model : Scoring/Upload File <br>
                                            </span>
                                            @break
                                        @endswitch
                                    {{ ucwords($el->name_exam) }}<br>
                        
                                    <i class="fas fa-clock"></i> 
                                    @php
                                        $currentDate = \Carbon\Carbon::now();
                                        $dateExam = \Carbon\Carbon::parse($el->date_exam);
                                        $daysRemaining = $currentDate->diffInDays($dateExam, false);
                                    @endphp
                        
                                    @if($el->score !== 0)
                                        <span class="text-sm">
                                            {{ $dateExam->format('l, d F Y') }}
                                        </span> <br>
                                        <span class="badge bg-warning">Score: {{ $el->score }}</span>
                                        {{-- <span class="badge bg-success"><i class="fas fa-check"></i> Completed</span> --}}
                                    @else
                                        @if ($el->is_active)
                                            <span class="text-dark text-sm">
                                                {{ $dateExam->format('l, d F Y') }}
                                            </span>  <br>
                                            @if ($daysRemaining == 0)
                                                <span class="badge bg-danger">Today</span>
                                            @else
                                                <span class="badge bg-warning">{{ $daysRemaining }} days again</span>
                                            @endif
                                        @else
                                            <span class="text-sm">
                                                {{ $dateExam->format('l, d F Y') }}
                                            </span> <br>
                                            <span class="badge bg-warning">Score: {{ $el->score }}</span>
                                            {{-- <span class="badge bg-success"><i class="fas fa-check"></i> Completed</span> --}}
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        {{-- <div class="d-flex flex-wrap">
                            <div class="col-12 p-0">
                                <a class="btn btn-primary btn-sm text-sm w-100 rounded" id="view" data-id="{{ $el->id }}">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div> --}}
                    </a>
                </div>
            </div>
            @endforeach
        </div>  

        <div class="d-flex justify-content-center mt-3">
            <nav aria-label="...">
                <ul class="pagination" max-size="2">
                    
                    @php
                    $role = session('role');
                    $link = "/{$role}/dashboard/exam?";
                    $previousLink = $link . '&order='.$form->sort.'&type='.$form->type . '&page=' . ($data->currentPage() - 1);
                    $nextLink = $link . '&order='.$form->sort.'&type='.$form->type . '&page=' . ($data->currentPage() + 1);
                    $firstLink = $link . '&order='.$form->sort.'&type='.$form->type . '&page=1';
                    $lastLink = $link . '&order='.$form->sort.'&type='.$form->type . '&page=' . $data->lastPage();
                    
                    $arrPagination = [];
                    $flag = false;
                    
                    if ($data->lastPage() - 5 > 0) {
                        if ($data->currentPage() <= 4) {
                            for ($i = 1; $i <= 5; $i++) {
                                $temp = (object) [
                                    'page' => $i,
                                    'link' => $link . '&order='.$form->sort.'&type='.$form->type . '&page=' . $i,
                                ];
                                array_push($arrPagination, $temp);
                            }
                        } else if ($data->lastPage() - $data->currentPage() > 2) {
                            $flag = true;
                            $idx = [$data->currentPage() - 2, $data->currentPage() - 1, $data->currentPage(), $data->currentPage() + 1, $data->currentPage() + 2];
                            foreach ($idx as $value) {
                                $temp = (object) [
                                    'page' => $value,
                                    'link' => $link . '&order='.$form->sort.'&type='.$form->type . '&page=' . $value,
                                ];
                                array_push($arrPagination, $temp);
                            }
                        } else {
                            $arrFirst = [];
                            for ($i = $data->currentPage(); $i <= $data->lastPage(); $i++) {
                                $temp = (object) [
                                    'page' => $i,
                                    'link' => $link . '&order='.$form->sort.'&type='.$form->type . '&page=' . $i,
                                ];
                                array_push($arrFirst, $temp);
                            }
                            
                            $arrLast = [];
                            $diff = $data->currentPage() - (5 - sizeof($arrFirst));
                            for ($i = $diff; $i < $data->currentPage(); $i++) {
                                $temp = (object) [
                                    'page' => $i,
                                    'link' => $link . '&order='.$form->sort.'&type='.$form->type . '&page=' . $i,
                                ];
                                array_push($arrLast, $temp);
                            }
                            
                            $arrPagination = array_merge($arrLast, $arrFirst);
                        }
                    } else {
                        for ($i = 1; $i <= $data->lastPage(); $i++) {
                            $temp = (object) [
                                'page' => $i,
                                'link' => $link . '&order='.$form->sort.'&type='.$form->type . '&page=' . $i,
                            ];
                            array_push($arrPagination, $temp);
                        }
                    }
                    @endphp

                    <li class="mr-1 page-item {{$data->previousPageUrl() ? '' : 'disabled'}}">
                        <a class="page-link" href="{{$firstLink}}" tabindex="+1">
                            First
                        </a>
                    </li>

                    <li class="page-item {{$data->previousPageUrl() ? '' : 'disabled'}}">
                        <a class="page-link" href="{{$previousLink}}" tabindex="-1">
                            Previous
                        </a>
                    </li>

                    @foreach ($arrPagination as $el)
                    <li class="page-item {{$el->page === $data->currentPage() ? 'active' : ''}}">
                        <a class="page-link" href="{{$el->link}}">
                            {{$el->page}}
                        </a>
                    </li>
                    @endforeach

                    <li class="page-item {{$data->nextPageUrl() ? '' : 'disabled'}}">
                        <a class="page-link" href="{{$nextLink}}" tabindex="+1">
                            Next
                        </a>
                    </li>

                    <li class="ml-1 page-item {{$data->nextPageUrl() ? '' : 'disabled'}}">
                        <a class="page-link" href="{{$lastLink}}" tabindex="+1">
                            Last
                        </a>
                    </li>

                </ul>   
            </nav>
        </div>
    @else
        <div class="card">
            <div class="container-fluid full-height p-4">
                <div class="icon-wrapper">
                    <i class="fas fa-search"></i>
                    <p class="my-2">The assessment you are looking for is not found</p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('#view').forEach(function(button) {
         button.addEventListener('click', function() {
               var assessmentId = this.getAttribute('data-id');
               var sessionRole = @json(session('role'));
               var url;
                if (sessionRole === "parent") {
                    url = "{{ route('set.assessment.id') }}";
                } else if (sessionRole === "student") {
                    url = "{{ route('set.assessment.id.student') }}";
                }
               
               $.ajax({
                  url: url,
                  method: 'POST',
                  data: {
                     id: assessmentId,
                     _token: '{{ csrf_token() }}'
                  },
                  success: function(response) {
                     if (response.success) {
                           window.location.href = '/' + sessionRole + '/dashboard/exam/detail';
                     } else {
                           alert('Failed to set exam ID in session.');
                     }
                  },
                  error: function(xhr, status, error) {
                     alert('Error: ' + error);
                  }
               });
         });
      });
   });
</script>



@endsection
