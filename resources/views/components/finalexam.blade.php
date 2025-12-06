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
    @if(sizeof($data) !== 0)
        <div class="row">
            @foreach ($data as $el)
                @php
                    $todayOpenTime = \Carbon\Carbon::parse($el->time_open)->translatedFormat('H:i');
                    $openDate = \Carbon\Carbon::parse($el->open_date)->translatedFormat('d-m-Y');
                    $dateNow = now()->format('d-m-Y');
                    $endTime = \Carbon\Carbon::parse($el->end_time)->translatedFormat('H:i');
                @endphp

            <div class="col-lg-6 col-md-6 col-6">
                <a 
                    @if ($endTime !== null)
                      @if($openDate == $dateNow)
                        @if (\Carbon\Carbon::parse(now())->translatedFormat('H:i') >= $todayOpenTime && \Carbon\Carbon::parse(now())->translatedFormat('H:i') <= $endTime)
                          id="workplace" data-id="{{ $el->id_exam }}" href="javascript:void(0);"
                        @endif
                      @endif
                    @endif
                >
                    <div class="small-box position-relative p-3 d-flex flex-column" style="background-color: #ffde9e;border-radius: 12px;">
                        <div class="ribbon-wrapper ribbon-md">
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
                                    <strong>{{ ucwords($el->type_exam_name) }} | {{ $el->name_subject }}</strong> <br>
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
                                </p>
                            </div>
                        </div>
                        <div>
                            <p>
                                Questions can be accessed at {{ \Carbon\Carbon::parse($el->open_date)->translatedFormat('d F Y') }} {{ \Carbon\Carbon::parse($el->time_open)->translatedFormat('H:i') }} - {{ \Carbon\Carbon::parse($el->end_time)->translatedFormat('H:i') }} 
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>  
    @else
        <div class="card" style="background-color: #ffde9e;border-radius: 12px;">
            <div class="container-fluid full-height p-4">
                <div class="icon-wrapper">
                    <i class="fas fa-search text-warning"></i>
                    <p class="my-2">Empty Data</p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
//    document.addEventListener('DOMContentLoaded', function() {
//       document.querySelectorAll('#view').forEach(function(button) {
//          button.addEventListener('click', function() {
//                var assessmentId = this.getAttribute('data-id');
//                var sessionRole = @json(session('role'));
//                var url;
               
//                 url = "{{ route('set.assessment.id.student') }}";
                
//                $.ajax({
//                   url: url,
//                   method: 'POST',
//                   data: {
//                     id: assessmentId,
//                      _token: '{{ csrf_token() }}'
//                   },
//                   success: function(response) {
//                      if (response.success) {
//                            window.location.href = '/' + sessionRole + '/dashboard/exam/detail';
//                      } else {
//                            alert('Failed to set exam ID in session.');
//                      }
//                   },
//                   error: function(xhr, status, error) {
//                      alert('Error: ' + error);
//                   }
//                });
//          });
//       });
//    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#workplace').forEach(function(button) {
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
