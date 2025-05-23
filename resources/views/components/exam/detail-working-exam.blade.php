<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/style.css') }}">
    <link rel="stylesheet" href="{{ asset('template') }}/dist/css/adminlte.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{asset('template')}}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{asset('fontawesome')}}/css/all.min.css">

    <style>
        .question-number {
            width: 35px;
            height: 35px;
            text-align: center;
            line-height: 35px;
            border: 1px solid grey;
            border-radius: 5px;
            cursor: pointer;
        }
        .question-number.active {
            background-color: orange;
            border: 1px solid orange;
            color: black;
        }
        .question-number.wrong {
            background-color: red;
            border: 1px solid red;
            color: white;
        }
        .question-number.answered {
            background-color: green;
            border: 1px solid green;
            color: white;
        }
        .answer-box {
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }
        .answer-box.selected {
            background-color: green;
            color: white;
        }
        .answer-box.is_correct {
            background-color: gray;
            color: white;
        }
        
    </style>
</head>
<body>
    <div class="container mt-4">
        {{-- HEADER --}}
        <div class="row">
            <div class="col-md-6 col-lg-1">
                <div class="info-box small-box bg-danger px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#backTo"
                        class="stretched-link d-flex flex-column p-2 text-center justify-content-center align-items-center">
                        <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                            <div class="inner mt-2">
                                <p class="mb-0 text-lg fw-bold text-center text-white">Back</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="info-box bg-orange">
                  <div class="info-box-content d-flex flex-row justify-content-center align-items-center flex-grow-1">
                    <div>
                        <img loading="lazy" src="{{ asset('images/online-exam.png') }}" 
                          alt="avatar" class="profileImage img-fluid" 
                          style="width: 50px; height: 50px; cursor: pointer;">
                    </div>
                    <span class="info-box-text text-center text-white text-bold">{{ucwords(strtolower($assessment->name_exam))}}</span>
                  </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
            <div class="info-box bg-orange">
                <div class="info-box-content d-flex flex-row justify-content-center align-items-center flex-grow-1">
                <div>
                    <img loading="lazy" src="{{ asset('images/elearning.png') }}" 
                        alt="avatar" class="profileImage img-fluid" 
                        style="width: 50px; height: 50px; cursor: pointer;">
                </div>
                <span class="info-box-text text-center text-white text-bold">{{ucwords($assessment->type_exam)}} - {{$assessment->subject_name}} <br> {{$assessment->grade_name}} - {{$assessment->grade_class}}</span>
                </div>
            </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="info-box bg-orange">
                  <div class="info-box-content d-flex flex-row justify-content-center align-items-center flex-grow-1">
                    <div>
                        <img loading="lazy" src="{{ asset('images/underline.png') }}" 
                          alt="avatar" class="profileImage img-fluid" 
                          style="width: 50px; height: 50px; cursor: pointer;">
                    </div>
                    <span class="info-box-text text-center text-white text-bold">Deadline {{ \Carbon\Carbon::parse($assessment->date_exam)->translatedFormat('d F Y') }} </span>
                  </div>
                </div>
            </div>
        </div>

        {{-- KONTEN --}}
        <div class="row">
            <!-- Sidebar kiri untuk nomor soal -->
            <div class="col-md-3">
                <div class="card p-3">
                    <h5 class="text-center">Question Number</h5>
                    
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($questions as $index => $question)
                            <div class="question-number
                                @if (session('role') == 'parent' || session('role') == 'student')
                                    {{ $question->studentAnswer[0]['point'] != 0 ? 'answered' : 'wrong' }}
                                @else
                                @endif
                                " 
                                id="qnum-{{ $question->id }}" onclick="showQuestion({{ $question->id }}, {{$index+1}})
                            ">
                                {{ $index+1 }}
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#completed">Completed</button> --}}
            </div>
            
            <!-- Konten soal di sebelah kanan -->
            <div class="col-md-9">
                <div class="card p-4">
                    @foreach ($questions as $question)
                    <div class="question-content" id="question-{{ $question->id }}" style="display: none;">
                        <h5 id="qn-{{ $question->id }}"></h5>
                        <p>{!! $question->text !!}</p>

                        @if ($question->type == "mc")
                            <h5>Answer :</h5>
                            <div class="row pt-2">
                                @foreach ($question->answer as $optionKey => $option)
                                <div class="col-6 col-md-3">
                                    <div class="answer-box small-box 
                                    
                                    @if (session('role') == 'parent' || session('role') == 'student')
                                        {{ $question->studentAnswer[0]['answer_id'] == $option->id ? 'selected' : ($option->is_correct ? 'is_correct' : '') }}
                                    @else
                                        {{ $option->is_correct ? 'is_correct' : '' }}
                                    @endif
                                    {{-- {{ $question->studentAnswer[0]['answer_id'] !== $option->id ? ($option->is_correct ? 'selected' : '') : ($option->is_correct ? 'is_correct' : '') }} --}}
                                    
                                    ">
                                        
                                    {{ $option->answer_text }}
                                    </div>
                                </div>
                                @endforeach
                            </div>


                        @elseif($question->type == "essay")
                            <h5>Answer :</h5>
                            @if (session('role') == 'parent' || session('role') == 'student')
                                {{$question->studentAnswer[0]['essay_answer']}}
                            @else
                                {{$question->answer[0]['answer_text']}}
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- END KONTEN --}}
    </div>

    {{-- Modal Welcome --}}
    <div class="modal fade" id="welcome" tabindex="-1" aria-labelledby="welcomeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger p-4 rounded">
                <div class="alert-container d-flex flex-column align-items-center text-center gap-3">
                    <!-- Logo -->
                    <img loading="lazy" src="{{ asset('images/happy.png') }}" 
                        alt="alert-icon" class="profileImage img-fluid"
                        style="width: 120px; height: 120px;">
            
                    <!-- Pesan -->
                    <p class="fw-bold text-white">Welcome to Great Assessment</p>
                </div>
            </div>            
        </div>
    </div>

    {{-- Modal Back --}}
    <div class="modal fade" id="backTo" tabindex="-1" aria-labelledby="backToLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger p-4 rounded">
                <div class="alert-container d-flex flex-column align-items-center text-center gap-3">
                    <!-- Logo -->
                    <img loading="lazy" src="{{ asset('images/happy.png') }}" 
                        alt="alert-icon" class="profileImage img-fluid"
                        style="width: 120px; height: 120px;">
            
                    <!-- Pesan -->
                    <p class="fw-bold">See you later</p>
            
                    <!-- Tombol -->
                    <div class="d-flex justify-content-center gap-3 w-100">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-dark px-4" onclick="goBack()">Yes</button>
                    </div>
                </div>
            </div>            
        </div>
    </div>
    
    {{-- Modal Completed --}}
    {{-- <div class="modal fade" id="completed" tabindex="-1" aria-labelledby="backToLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger p-4 rounded">
                <div class="alert-container d-flex flex-column align-items-center text-center gap-3">
                    <!-- Logo -->
                    <img loading="lazy" src="{{ asset('images/confuse.png') }}" 
                        alt="alert-icon" class="profileImage img-fluid"
                        style="width: 120px; height: 120px;">
            
                    <!-- Pesan -->
                    <p class="fw-bold">Make sure your answer is correct <br> You cannot edit your answer after completed this assessment</p>
                    
                    <!-- Tombol -->
                    <div class="col">
                        <p class="fw-bold">Do you want to completed this assessment ?</p>
                        <div class="d-flex justify-content-center gap-3 w-100">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-dark px-4" onclick="submitAnswers()">Yes</button>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div> --}}

    <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script>
        let answers = {}; // Menyimpan jawaban siswa
    
        // Menampilkan soal berdasarkan ID
        function showQuestion(id, number) {
            document.querySelectorAll('.question-content').forEach(q => q.style.display = 'none');
            document.getElementById('question-' + id).style.display = 'block';
            document.getElementById('qn-' + id).innerText = 'Question ' + number + ' :';
    
            document.querySelectorAll('.question-number').forEach(el => el.classList.remove('active'));
            document.getElementById('qnum-' + id).classList.add('active');
        }
    
        // Menyimpan jawaban pilihan ganda
        // function selectAnswer(questionId, answerId) {
        //     answers[questionId] = {
        //         question_id: questionId,
        //         answer_id: answerId,
        //         essay_answer: null
        //     };
    
        //     document.querySelectorAll(`#question-${questionId} .answer-box`).forEach(el => el.classList.remove('selected'));
        //     document.getElementById(`answer-${questionId}-${answerId}`).classList.add('selected');
    
        //     document.getElementById(`qnum-${questionId}`).classList.add('answered');
        // }
    
        // Menyimpan jawaban essay
        // function saveEssayAnswer(questionId) {
        //     let answerText = document.getElementById(`essay-answer-${questionId}`).value;
    
        //     answers[questionId] = {
        //         question_id: questionId,
        //         answer_id: null,
        //         essay_answer: answerText
        //     };
    
        //     if (answerText.trim() !== "") {
        //         document.getElementById(`qnum-${questionId}`).classList.add('answered');
        //     } else {
        //         document.getElementById(`qnum-${questionId}`).classList.remove('answered');
        //     }
        // }
    
        // Mengirim jawaban ke server Laravel
        // function submitAnswers() {
        //     fetch("{{ route('action.answer.student') }}", {
        //         method: "POST",
        //         headers: {
        //             "Content-Type": "application/json",
        //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //         },
        //         body: JSON.stringify({
        //             answers: Object.values(answers) // Mengubah objek menjadi array
        //         })
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.error) {
        //             alert("❌ Error: " + data.error); // Tampilkan error di alert
        //             console.error("Error Details:", data.details); // Log detail error di console
        //         } else {
        //             Swal.fire({
        //                 title: "<strong>Your answer is successfully saved</strong>",
        //                 background: "red",
        //                 html: `
        //                     <img loading="lazy" src="{{ asset('images/happy.png') }}" 
        //                     alt="alert-icon" class="profileImage img-fluid"
        //                     style="width: 120px; height: 120px;">
        //                 `,
        //                 focusConfirm: false,
        //                 confirmButtonText: `
        //                     <i class="fa fa-thumbs-up"></i> Great!
        //                 `,
        //                 timer: 1000,  // ⏳ Auto-close dalam 1 detik
        //                 timerProgressBar: true,  // 🔵 Menampilkan progress bar
        //             }).then(() => {
        //                 // 🔄 Redirect setelah alert ditutup
        //                 window.location.href = "{{ url('student/dashboard/exam/detail') }}";
        //             });
        //         }
        //     })
        //     .catch(error => {
        //         alert("⚠️ Terjadi kesalahan dalam pengiriman.");
        //         console.error("Fetch Error:", error);
        //     });
        // }
    
        // Menampilkan soal pertama secara default
        window.onload = function() {
            let firstQuestionId = document.querySelector('.question-content')?.id.split('-')[1];
            if (firstQuestionId) {
                showQuestion(firstQuestionId, 1);
            }
        };
    </script>
    

    {{-- POP-UP --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var welcomeModal = new bootstrap.Modal(document.getElementById('welcome'));
            welcomeModal.show();

            // Tutup modal setelah 2 detik
            setTimeout(function() {
                welcomeModal.hide();
            }, 1000);
        });

        function goBack() {
            window.history.back();
        }
    </script>
</body>
  
</html>
