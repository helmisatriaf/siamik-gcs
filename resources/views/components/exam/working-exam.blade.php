<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT | Great Crystal School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/style.css') }}">
    <link rel="stylesheet" href="{{ asset('template') }}/dist/css/adminlte.min.css">
    <link rel="icon" href="{{ asset('great.png') }}" type="image/x-icon">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('template') }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('fontawesome') }}/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Caveat+Brush&family=Chewy&family=DynaPuff&family=Lora:ital,wght@0,400..700;1,400..700&family=Patrick+Hand&family=Vollkorn:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #ffde9e;
            font-family: "DynaPuff", system-ui !important;
            font-weight: 400;
            font-style: normal;
        }
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

        .question-number.answered {
            background-color: green;
            border: 1px solid green;
            color: white;
        }

        .answer-box {
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .answer-box.selected {
            background-color: red;
            color: white;
        }

        .small-box {
            transition: transform 0.3s ease-in-out;
        }

        .small-box:hover {
            cursor: pointer;
            transform: scale(0.9);
        }
    </style>
</head>

@php
    $currentDateTime = (new DateTime(now()))->format('Y-m-d');
    $examDateTime = (new DateTime($assessment->date_exam))->format('Y-m-d');
    // dd($examDateTime);
@endphp


<body>
    {{-- <img src="{{ asset('/images') }}/logo-school.png"
     class=""
     alt="Watermark"
     style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            opacity: 0.7; z-index: 0; width: 90vh; pointer-events: none;"> --}}

    <div class="container mt-4">
        {{-- HEADER --}}
        <div class="row">
            <div class="col-md-6 col-lg-1">
                <div
                    class="info-box small-box bg-danger px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="border-radius: 12px;">
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
                <div class="info-box" style="background-color: #fff3c0;border-radius: 12px;">
                    <div class="info-box-content d-flex flex-row justify-content-center align-items-center flex-grow-1">
                        {{-- <div>
                            <img loading="lazy" src="{{ asset('images/greta-baca-buku.png') }}" alt="avatar"
                                class="profileImage img-fluid" style="width: 30px; height: 50px; cursor: pointer;">
                        </div> --}}
                        <span
                            class="info-box-text text-center text-dark text-bold text-lg">{{ ucwords(strtolower($assessment->name_exam)) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="info-box" style="background-color: #fff3c0;border-radius: 12px;">
                    <div class="info-box-content d-flex flex-row justify-content-center align-items-center flex-grow-1">
                        {{-- <div>
                            <img loading="lazy" src="{{ asset('images/greti-baca-buku.png') }}" alt="avatar"
                                class="profileImage img-fluid" style="width: 30px; height: 50px; cursor: pointer;">
                        </div> --}}
                        <span
                            class="info-box-text text-center text-dark text-bold text-lg">{{ ucwords($assessment->type_exam) }}
                            - {{ $assessment->subject_name }} <br> {{ $assessment->grade_name }} -
                            {{ $assessment->grade_class }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="info-box" style="background-color: #fff3c0;border-radius: 12px;">
                    <div class="info-box-content d-flex flex-row justify-content-center align-items-center flex-grow-1">
                        {{-- <div>
                            <img loading="lazy" src="{{ asset('images/greta-greti-baca-buku.png') }}" alt="avatar"
                                class="profileImage img-fluid" style="width: 65px; height: 50px; cursor: pointer;">
                        </div> --}}
                        <span class="info-box-text text-center text-dark text-bold text-lg">Deadline
                            {{ \Carbon\Carbon::parse($assessment->date_exam)->translatedFormat('d F Y') }} </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONTEN --}}
        <div class="row">
            <!-- Sidebar kiri untuk nomor soal -->
            <div class="col-md-3">
                <div class="card p-3" style="background-color: #fff3c0; border-radius: 12px;">
                    <img loading="lazy" src="{{ asset('/images') }}/logo-school.png" class="img-fluid bg-transparent" alt="Sample image">
                    <h5 class="text-center text-lg">Question Number</h5>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($questions as $index => $question)
                            <div class="question-number" id="qnum-{{ $question->id }}"
                                onclick="showQuestion({{ $question->id }}, {{ $index + 1 }})">
                                {{ $index + 1 }}
                            </div>
                        @endforeach
                    </div>
                </div>
                @if (session('role') !== 'parent')
                    @if ($assessment->is_active == true)
                        @if ($currentDateTime <= $examDateTime)
                            <button class="btn btn-danger w-100 mb-3" data-bs-toggle="modal" data-bs-target="#completed" style="border-radius: 12px;">Completed</button>
                        @endif
                    @endif
                @endif
            </div>

            <!-- Konten soal di sebelah kanan -->
            <div class="col-md-9">
                <div class="card p-4" style="background-color: #fff3c0; border-radius: 12px;">
                    @foreach ($questions as $question)
                        <div class="question-content" id="question-{{ $question->id }}" style="display: none;">
                            <h5 id="qn-{{ $question->id }}"></h5>
                            {!! $question->text !!}

                            @if ($question->type == 'mc')
                                <h5>Answer :</h5>
                                <div class="row pt-2">
                                    @foreach ($question->answer as $optionKey => $option)
                                        <div class="col-6 col-md-3">
                                            @if (session('role') !== 'parent')
                                                <div class="answer-box small-box"
                                                    onclick="selectAnswer({{ $question->id }}, '{{ $option->id }}')"
                                                    id="answer-{{ $question->id }}-{{ $option->id }}">
                                                    {{ $option->answer_text }}
                                                </div>
                                            @else
                                                <div class="answer-box small-box">
                                                    {{ $option->answer_text }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($question->type == 'essay')
                                <h5>Answer :</h5>
                                @if (session('role') !== 'parent')
                                    <textarea class="form-control w-100" name="answer_essay[{{ $question->id }}]" id="essay-answer-{{ $question->id }}"
                                        rows="5" oninput="saveEssayAnswer({{ $question->id }})"></textarea>
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
        <div class="modal-dialog modal-dialog-centered" style="max-width: fit-content;">
            <div class="modal-content bg-danger p-4" style="border-radius: 48px;">
                <div class="alert-container d-flex flex-column align-items-center text-center">
                    <!-- Logo Container -->
                    <div class="image-container mb-3" style="width: 150px; height: 150px;">
                        <img loading="lazy" src="{{ asset('images/greta-greti-angkat-tangan.png') }}" alt="alert-icon"
                            class="profileImage img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>

                    <!-- Pesan -->
                    <p class="fw-bold text-white mb-0" style="font-size: 1.8rem;">Welcome to Great Assessment</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Back --}}
    <div class="modal fade" id="backTo" tabindex="-1" aria-labelledby="backToLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger p-4" style="border-radius: 96px;">
                <div class="alert-container d-flex flex-column align-items-center text-center gap-3">

                    <!-- Pesan -->
                    @if (session('role') !== 'parent')
                        <!-- Logo -->
                        <img loading="lazy" src="{{ asset('images/greti-cina.png') }}" alt="alert-icon"
                            class="profileImage img-fluid" style="width: 120px; height: 120px; object-fit: contain;">

                        <p class="fw-bold" style="font-size: 1.6rem;">!! Your answers won't be saved if you leave now !!</p>
                    @else
                        <!-- Logo -->
                        <img loading="lazy" src="{{ asset('images/happy.png') }}" alt="alert-icon" class="profileImage img-fluid"
                            style="width: 120px; height: 120px;">

                        <p class="fw-bold">See you</p>
                    @endif

                    <!-- Tombol -->
                    <div class="d-flex justify-content-center gap-3 w-100">
                        <button type="button" class="btn-lg btn-light px-4" style="border-radius: 12px;" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn-lg btn-dark px-4" style="border-radius: 12px;" onclick="goBack()">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Completed --}}
    @if (session('role') !== 'parent')
        <div class="modal fade" id="completed" tabindex="-1" aria-labelledby="backToLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-danger p-4" style="border-radius: 72px;">
                    <div class="alert-container d-flex flex-column align-items-center text-center gap-3">
                        <!-- Logo -->
                        <div class="image-container" style="width: 250px; height: 150px;">
                            <img loading="lazy" src="{{ asset('images/greta-greti-angkat-tangan.png') }}" alt="alert-icon"
                                class="profileImage img-fluid"
                                style="width: 100%; height: 100%; object-fit: contain;">
                        </div>

                        <!-- Pesan -->
                        <p class="fw-bold text-lg">
                            Make sure your answers are correct <br> You cannot edit your answers after
                            you submitted this assessment
                        </p>

                        <!-- Tombol -->
                        <div class="col">
                            <p class="fw-bold text-lg">Do you want to submitted this assessment ?</p>
                            <div class="d-flex justify-content-center gap-3 w-100">
                                <button type="button" class="btn-lg btn-light px-4" style="border-radius: 12px;" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn-lg btn-dark px-4" style="border-radius: 12px;" onclick="submitAnswers()">Yes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

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
        function selectAnswer(questionId, answerId) {
            answers[questionId] = {
                question_id: questionId,
                answer_id: answerId,
                essay_answer: null
            };

            document.querySelectorAll(`#question-${questionId} .answer-box`).forEach(el => el.classList.remove('selected'));
            document.getElementById(`answer-${questionId}-${answerId}`).classList.add('selected');

            document.getElementById(`qnum-${questionId}`).classList.add('answered');
        }

        // Menyimpan jawaban essay
        function saveEssayAnswer(questionId) {
            let answerText = document.getElementById(`essay-answer-${questionId}`).value;

            answers[questionId] = {
                question_id: questionId,
                answer_id: null,
                essay_answer: answerText
            };

            if (answerText.trim() !== "") {
                document.getElementById(`qnum-${questionId}`).classList.add('answered');
            } else {
                document.getElementById(`qnum-${questionId}`).classList.remove('answered');
            }
        }

        // Mengirim jawaban ke server Laravel
        function submitAnswers() {
            fetch("{{ route('action.answer.student') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        answers: Object.values(answers) // Mengubah objek menjadi array
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert("❌ Error: " + data.error); // Tampilkan error di alert
                        console.error("Error Details:", data.details); // Log detail error di console
                    } else {
                        Swal.fire({
                            title: '',
                            html: `
                                <div style="text-align: center; padding: 10px;">
                                    <div style="margin-bottom: 15px;">
                                        <img loading="lazy" src="{{ asset('images/greta-greti-baju-olga.png') }}" 
                                            alt="Success Icon" 
                                            class="profileImage img-fluid"
                                            style="max-width: 100px; max-height: 120px; border-radius: 4px; margin-bottom: 15px;">
                                        <h3 style="color: #2d3748; font-weight: 600; margin-bottom: 10px; font-size: 22px;">
                                            Excellent Work!
                                        </h3>
                                        <p style="color: #4a5568; font-size: 16px; line-height: 1.5; margin-bottom: 5px;">
                                            Your answer has been successfully saved.
                                        </p>
                                        <div style="display: flex; align-items: center; justify-content: center; margin-top: 15px;">
                                            <span style="display: inline-block; background-color: #f0f9ff; border-radius: 20px; padding: 5px 15px; font-size: 14px; color: #3182ce;">
                                                <i class="fas fa-check-circle" style="margin-right: 5px; color: #38b2ac;"></i>
                                                Completed successfully
                                            </span>
                                        </div>
                                    </div>
                                    <div style="margin-top: 15px; border-top: 1px solid #edf2f7; padding-top: 15px;">
                                        <p style="color: #718096; font-size: 14px;">
                                            You'll be redirected to your dashboard shortly
                                        </p>
                                    </div>
                                </div>
                            `,
                            background: "#ffffff",
                            showConfirmButton: true,
                            confirmButtonText: "Continue",
                            confirmButtonColor: "#4299e1",
                            timer: 3000,
                            timerProgressBar: true,
                            customClass: {
                                container: 'elegant-swal-container',
                                popup: 'elegant-swal-popup',
                                confirmButton: 'elegant-swal-confirm-button'
                            },
                            didOpen: () => {
                                Swal.showLoading();

                                // Add custom styles for the SweetAlert container
                                const style = document.createElement('style');
                                style.innerHTML = `
                                    .elegant-swal-popup {
                                        border-radius: 12px !important;
                                        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
                                    }
                                    .elegant-swal-confirm-button {
                                        border-radius: 6px !important;
                                        font-weight: 500 !important;
                                        padding: 10px 24px !important;
                                        text-transform: none !important;
                                    }
                                    .swal2-timer-progress-bar {
                                        background: #4299e1 !important;
                                    }
                                `;
                                document.head.appendChild(style);
                            },
                            willClose: () => {
                                window.location.href = "{{ url('student/dashboard/exam/detail') }}";
                            }
                        });
                    }
                })
                .catch(error => {
                    alert("⚠️ Terjadi kesalahan dalam pengiriman.");
                    console.error("Fetch Error:", error);
                });
        }

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
            }, 2500);
        });

        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
