@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            @if (count($data) > 0)
                @foreach ($data as $subject)
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="min-height: 110px;background-color: #ffde9e;border-radius: 12px;">
                            <a 
                                @if (session('role') == 'student' || session('role') == 'parent')
                                    id="set-course-id"
                                    data-id="{{ $subject->id }}"
                                    href="javascript:void(0)"
                                @elseif (session('role') == 'teacher')
                                    href="{{ route('eca.section.activity.teacher', [
                                        'id' => $subject->id,
                                    ]) }}"
                                @else
                                    href="{{ route('eca.section.activity.teacher', [
                                        'role' => session('role'),
                                        'id' => $subject->id,
                                        'grade_id' => $grade->id,
                                    ]) }}"
                                @endif  
                                class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                            
                                <!-- Ribbon -->
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div class="ribbon bg-dark text-sm">
                                        {{ session('role') !== 'teacher' ? 'ECA' : $subject->name }}
                                    </div>
                                </div>
                            
                                <!-- Bagian Utama -->
                                <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                    <!-- Ikon -->
                                    <div>
                                        <img loading="lazy" src="{{ asset('storage/'.$subject->icon) }}" 
                                        alt="avatar" class="profileImage img-fluid" 
                                        style="width: 50px; height: 50px; cursor: pointer;" loading="lazy">
                                    </div>
                                    <!-- Nama Subject -->
                                    <div class="inner mt-2">
                                        <p class="mb-0 text-lg fw-bold text-center text-dark">{{ $subject->name }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>       
                    </div>
                @endforeach
            @else
            @endif
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#set-course-id').forEach(function(button) {
                button.addEventListener('click', function() {
                    var courseId = this.getAttribute('data-id'); // Ambil ID dari data-id
                    var sessionRole = @json(session('role'));
                    var url;

                    if (sessionRole === "parent") {
                        url = "{{ route('set.course.id.parent') }}";
                    } else if (sessionRole === "student") {
                        url = "{{ route('set.course.id.student') }}";
                    }

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            id: courseId, // Ganti assessmentId dengan courseId
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.href = '/' + sessionRole + '/eca/section/activity';
                            } else {
                                alert('Failed to set course ID in session.');
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

    @if (session('data_is_empty'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops..',
                    text: 'Data Attendance is Empty !!!',
                });
            }, 500);
        </script>
    @endif
@endsection
