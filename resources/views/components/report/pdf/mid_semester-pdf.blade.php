<?php
// Set the maximum execution time to 300 seconds
set_time_limit(300);

// Your script logic here
$pathlogo = public_path('images/logo-school.png');
$typelogo = pathinfo($pathlogo, PATHINFO_EXTENSION);
$datalogo = file_get_contents($pathlogo);
$logo = 'data:image/' . $typelogo . ';base64,' . base64_encode($datalogo);

$pathcambridge = public_path('images/lcn.png');
$typecambridge = pathinfo($pathcambridge, PATHINFO_EXTENSION);
$datacambridge = file_get_contents($pathcambridge);
$cambridge = 'data:image/' . $typecambridge . ';base64,' . base64_encode($datacambridge);

$grade_name = $student->grade_name;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
        }

        .noto-serif-sc-chinese {
            font-family: "Noto Serif SC", serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .noto-serif-sc-simbol {
            font-family: "Noto Serif SC", serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .header {
            margin: 0;
            margin-top: 100px;
            width: 100%;
            text-align: center;
        }
        

        .header h1, .header h2, .header h5, .header h4, .header h5 {
            font-size: 10px;
            margin: 0;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .footer table {
            width: 100%;
        }

        .mid {
            display: flex;
            justify-content: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .tablesubject {
            width: 100%;
            border-collapse: collapse;
        }

        .tableScore {
            margin-top: 10px;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tableScore th{
            border: 1px solid black;
            border-collapse: collapse;
        }
        .tableScore td{
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tableMonthly td{
            border: 1px solid black;
            border-collapse: collapse;
            padding: 3px;
        }

        .table td {
            font-size: 10px;
        }

        .tablesubject td {
            font-size: 10px;
            padding: 4px;
        }

        table td {
            font-size: 10px;
        }

        .tableAdditional, .tableMonthly {
            width: 100%;
            border-collapse: collapse;
        }


        .tableAdditional td {
            padding: 5px;
            text-align: left;
        }
        .tableAdditional .col-left {
            width: 28%; /* Lebar kolom kiri */
        }
        .tableAdditional .col-mid {
            width: 2%; /* Lebar kolom kiri */
        }
        .tableAdditional .col-right {
            width: 70%; /* Lebar kolom kanan */
        }

        .signature {
            text-align: center;
            margin-top: 20px;
        }

        .page-break {
            page-break-before: always;
        }

        .watermark {
            position: absolute;
            top: 40%; /* Posisi vertikal tengah */
            left: 50%; /* Posisi horizontal tengah */
            transform: translate(-50%, -50%) rotate(-45deg); /* Pusatkan dan rotasi */
            font-size: 80px; /* Ukuran font */
            color: rgba(128, 128, 128, 0.5); /* Warna abu-abu dengan transparansi */
            white-space: nowrap; /* Tidak memecah teks */
            z-index: -1; /* Pastikan di belakang konten */
            width: 200%; /* Lebar teks */
            text-align: center; /* Penataan teks */
            user-select: none; /* Teks tidak bisa disorot */
            pointer-events: none; /* Tidak mengganggu interaksi pengguna */
        }

        .watermark-school {
            position: absolute;
            top: 40%; /* Posisi vertikal tengah */
            left: 50%; /* Posisi horizontal tengah */
            transform: translate(-50%, -50%) rotate(-45deg); /* Pusatkan dan rotasi */
            font-size: 80px; /* Ukuran font */
            color: rgba(254,147,6, 0.5); /* Warna abu-abu dengan transparansi */
            white-space: nowrap; /* Tidak memecah teks */
            z-index: -1; /* Pastikan di belakang konten */
            width: 100%; /* Lebar teks */
            text-align: center; /* Penataan teks */
            user-select: none; /* Teks tidak bisa disorot */
            pointer-events: none; /* Tidak mengganggu interaksi pengguna */
        }

        .page-break {
            page-break-before: always;
        }

        .double-border {
            border-bottom: 5px double black; /* Garis ganda */
            padding: 5px;
        }

        @page {
            margin: 0mm 10mm 5mm 10mm;
        }

        @media print {
            body {
                height: auto;
                margin: 0;
                padding: 0;
            }

            .container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .content {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container"> 
        <div class="content">
            <!-- PAGE 1 -->
                @if (session('role') == 'student' || session('role') == 'parent')
                <p class="watermark-school">Great Crystal School</p> 
                @endif
                <div class="header">
                    {{-- <div style="padding-left:50px;padding-right:50px;">
                        <img src="<?= $logo ?>" style="width:100%;height:10%;" alt="Sample image">
                    </div> --}}
                    <h5>MID-SEMESTER REPORT</h5>
                </div>
    
                <div style="margin-top:10px;">
                    <table class="" style="border:none">
                        <!-- STUDENT STATUS -->
                        <tr>
                            <td style="text-align:left;font-size:10px;">Name</td>
                            <td style="text-align:left;font-size:10px;"> : {{ ucwords(strtolower($student['student_name'])) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;font-size:10px;">Grade</td>
                            <td style="text-align:left;font-size:10px;"> : {{ $student->grade_name }} - {{ $student->grade_class }}</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;font-size:10px;">Semester</td>
                            <td style="text-align:left;font-size:10px;"> : {{ $semester }}</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;font-size:10px;">School Year</td>
                            <td style="text-align:left;font-size:10px;"> : {{ $academicYear }}</td>
                        </tr>
                        <!-- END STUDENT STATUS -->
                    </table>
                </div>
    
                <div>
                    <table class="tablesubject" style="margin-top:10px;">
                        <tr>
                            <th style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="1" rowspan="2">No</td>
                            <th style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:20%;" colspan="4" rowspan="2">Subject</td>
                            <th style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3">Homework</td>
                            <th style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3">Exercise</td>
                            <th style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3">Quiz</td>
                            <th style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3">Project</td>
                            {{-- <th style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3">Practical</td> --}}
                        </tr>

                        <tr>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">1</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">2</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">3</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">1</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">2</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">3</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">1</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">2</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">3</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">1</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">2</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">3</td>
                            {{-- <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">1</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">2</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;">3</td> --}}
                        </tr>

                        <tr>
                            <td style="border: 1px solid black;text-align:center;font-style:semibold" colspan="17">Academic Performance</td>
                        </tr>

                        @if (count($subjectReports) == 0)
                            
                        @else
                            @foreach ($subjectReports[0]['subjects'] as $rs)
                            <tr>
                                <td style="text-align: center; vertical-align: middle; font-size: 10px; border: 1px solid black;" colspan="1">{{ $loop->index + 1 }}.</td>
                                <td style="text-align: left;vertical-align : middle;font-size:10px;padding-left: 5px;border: 1px solid black;" colspan="4">{{ $rs['subject_name'] }}</td>
                                @php
                                    $homeworkScores = array_fill(0, 3, '&nbsp;');
                                    $exerciseScores = array_fill(0, 3, '&nbsp;');
                                    $quizScores = array_fill(0, 3, '&nbsp;');
                                    $projectScores = array_fill(0, 3, '&nbsp;');
                                    // $practicalScores = array_fill(0, 3, '&nbsp;');

                                    if (!empty($rs['scores'])) {
                                        if (isset($rs['scores']['homework']) && count($rs['scores']['homework']) > 0) {
                                            foreach ($rs['scores']['homework'] as $index => $score) {
                                                if ($index < 3) {
                                                    $homeworkScores[$index] = $score;
                                                }
                                            }
                                        }

                                        if (isset($rs['scores']['exercise']) && count($rs['scores']['exercise']) > 0) {
                                            foreach ($rs['scores']['exercise'] as $index => $score) {
                                                if ($index < 3) {
                                                    $exerciseScores[$index] = $score;
                                                }
                                            }
                                        }

                                        if (isset($rs['scores']['quiz']) && count($rs['scores']['quiz']) > 0) {
                                            foreach ($rs['scores']['quiz'] as $index => $score) {
                                                if ($index < 3) {
                                                    $quizScores[$index] = $score;
                                                }
                                            }
                                        }

                                        if (isset($rs['scores']['project']) && count($rs['scores']['project']) > 0) {
                                            foreach ($rs['scores']['project'] as $index => $score) {
                                                if ($index < 3) {
                                                    $projectScores[$index] = $score;
                                                }
                                            }
                                        }

                                        // if (isset($rs['scores']['practical']) && count($rs['scores']['practical']) > 0) {
                                        //     foreach ($rs['scores']['practical'] as $index => $score) {
                                        //         if ($index < 3) {
                                        //             $practicalScores[$index] = $score;
                                        //         }
                                        //     }
                                        // }
                                    }
                                @endphp

                                    @for ($i = 0; $i < 3; $i++)
                                    <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $homeworkScores[$i] !!}</td>
                                    @endfor
                                    
                                    @for ($j = 0; $j < 3; $j++)
                                    <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $exerciseScores[$j] !!}</td>
                                    @endfor
                                    
                                    @for ($k = 0; $k < 3; $k++)
                                    <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $quizScores[$k] !!}</td>
                                    @endfor
                                    
                                    @for ($l = 0; $l < 3; $l++)                            
                                    <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $projectScores[$l] !!}</td>
                                    @endfor
                                    
                                    {{-- @for ($m = 0; $m < 3; $m++)
                                    <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $practicalScores[$m] !!}</td>
                                    @endfor --}}
                            </tr>
                            @endforeach
                        @endif

                    </table>
                </div>

                <div>
                    <table class="tableMonthly" style="margin-top: 10px;">
                        <tr>
                            <td colspan="{{count($monthlyAct) + 1}}"style="text-align:center;"><b>Monthly Activities</b></td>
                        </tr>
                        <tr>
                            <td style="width:15%;text-align:center;"><b>Participation</b></td>
                            @foreach ($monthlyAct as $ma)
                                <td style="text-align:center;"><b>{{$ma->name}}</b></td>
                            @endforeach
                        </tr>
                        <tr>
                            <td style="width:15%;"><b>Grade</b></td>
                            @foreach ($scoreMonthly as $sm)
                            <td style="text-align:center;">{{$sm->grades}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td style="width:15%;"><b>Score</b></td>
                            @foreach ($scoreMonthly as $ssm)
                            <td style="text-align:center;">{{$ssm->score}}</td>
                            @endforeach
                        </tr>
                        
                    </table>
                </div>

                <div>
                    <table class="tableScore">
                        <tr>
                            <th style="text-align:center;vertical-align : middle;font-size:10px;padding-right:16px;padding-left:16px;">Scores</td>
                            <th style="text-align:center;vertical-align : middle;font-size:10px;padding-right:16px;padding-left:16px;">Grade</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">95-100</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">A+</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">85-94</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">A</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">75-84</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">B</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">65-74</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">C</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">45-64</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">D</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">&lt; 44</td>
                            <td style="text-align:center;vertical-align : middle;font-size:10px;">R</td>
                        </tr>
                    </table>
                </div>
    
                {{-- <div style="margin-top:10px;padding-left:15px;">
                    <table class="" style="border:none">
                        <!-- STUDENT STATUS -->
                        <tr>
                            <td style="font-size:10px;">Absence</td>
                            <td style="font-size:10px;">
                                @if ($attendance[0]['days_absent'] > 0)
                                : {{ $attendance[0]['days_absent'] }} day(s)
                                @else
                                : 0 day(s)
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10px;">Sick</td>
                            <td style="font-size:10px;">
                                @if ($attendance[0]['sick'] > 0)
                                : {{ $attendance[0]['sick'] }} day(s)
                                @else
                                : 0 day(s)
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10px;">Permission</td>
                            <td style="font-size:10px;">
                                @if ($attendance[0]['permission'] > 0)
                                : {{ $attendance[0]['permission'] }} day(s)
                                @else
                                : 0 day(s)
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:10px;">Late</td>
                            <td style="font-size:10px;">
                                @if ($attendance[0]['late'] > 0)
                                : {{ $attendance[0]['late'] }} time(s)
                                @else
                                : 0 time(s)
                                @endif
                            </td>
                        </tr>
                        <!-- END STUDENT STATUS -->
                    </table>
                </div>
    
                <div style="margin-top:10px;">
                    <table class="table" style="border:1px solid black;">
                        <!-- REMARKS -->
                        <tr>
                            <td style="text-align:left;font-size:10px;padding:5px;">Comment : {{ $remarks }}</td>
                        </tr>
                        <!-- END REMARKS -->
                    </table>
                </div> --}}
            <!-- END PAGE 1 -->

            <div class="page-break"></div>

            {{-- PAGE 2 --}}
            @if (session('role') == 'student' || session('role') == 'parent')
            <p class="watermark-school">Great Crystal School</p> 
            @endif
            <div style="margin-top:50px;">
                <table class="tableScore" style="width:200px;">
                    <tr>
                        <th style="text-align:center;vertical-align : middle;font-size:10px;padding-right:16px;padding-left:16px;" colspan="2">Student's Attendance</td>
                    </tr>
                    <tr>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">Days Attended</td>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">
                            @if ($attendance[0]['present'] > 0)
                            {{ $attendance[0]['present'] }} days
                            @else
                            0 days
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">Days Absent</td>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">
                            @if ($attendance[0]['days_absent'] > 0)
                            {{ $attendance[0]['days_absent'] }} day
                            @else
                            0 day
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">Days Permission</td>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">
                            @if ($attendance[0]['permission'] > 0)
                            {{ $attendance[0]['permission'] }} day
                            @else
                            0 day
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">Days Sick</td>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">
                            @if ($attendance[0]['sick'] > 0)
                            {{ $attendance[0]['sick'] }} day
                            @else
                            0 day
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">Late</td>
                        <td style="text-align:left;vertical-align : middle;font-size:10px;padding-left:3px;">
                            @if ($attendance[0]['total_late'] > 0)
                            {{ $attendance[0]['total_late'] }} time
                            @else
                            0 time
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            {{-- ADDITIONAL EVALUATION --}}
            <div>
                <table class="tableAdditional" style="margin-top:20px;">
                    <tr>
                        <td colspan="3" style="border-bottom: 5px double black"></td>
                    </tr>
                </table>
                <table class="tableAdditional" style="margin-top: -2px;">
                    <tr>
                        <td colspan="3"style="text-align:center;border-left:1px solid black;border-right:1px solid black;"><b>Additional Evaluation</b></td>
                    </tr>
                    <tr>
                        <td class="col-left" style="font-style: bold;border-left:1px solid black;">Critical Thinking</td>
                        <td class="col-mid">:</td>
                        <td class="col-right" style="border-right:1px solid black;">{{$ct}}</td>
                    </tr>
                    <tr>
                        <td class="col-left" style="font-style: bold;border-left:1px solid black;">Cognitive Skills</td>
                        <td class="col-mid">:</td>
                        <td class="col-right" style="border-right:1px solid black;">{{$cs}}</td>
                    </tr>
                    <tr>
                        <td class="col-left" style="font-style: bold;border-left:1px solid black;">Life Skills</td>
                        <td class="col-mid">:</td>
                        <td class="col-right" style="border-right:1px solid black;">{{$ls}}</td>
                    </tr>
                    <tr>
                        <td class="col-left" style="font-style: bold;border-left:1px solid black;">Learning Skills</td>
                        <td class="col-mid">:</td>
                        <td class="col-right" style="border-right:1px solid black;">{{$les}}</td>
                    </tr>
                    <tr style="border-bottom:1px solid black;">
                        <td class="col-left" style="font-style: bold;border-left:1px solid black;">Social and Emotional Development</td>
                        <td class="col-mid">:</td>
                        <td class="col-right" style="border-right:1px solid black;">{{$saed}}</td>
                    </tr>
                </table>
            </div>

            <div>
                <table class="table" style="margin-top:60px;">
                    @if(strtolower($student->grade_name) == "primary")
                        <tr>
                            <td style="text-align:center;text-decoration:underline;">Yuliana Harijanto, B.Eng (Hons)</td>
                        </tr>
                    @elseif (strtolower($student->grade_name) == "secondary")
                        <tr>
                            <td style="text-align:center;text-decoration:underline;">Donny Prasetya, S.Kom.</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="text-align:center;"><b>Principal's Signature'</b></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;padding-top:5px;font-size:8px;font-color:orange;"><i>This report card is for internal circulation only.</i></td>
                    </tr>
                    {{-- <tr>
                        <td style="text-align:center;padding-top:5px;"> <img src="<?= $cambridge ?>" style="width:23%;height:4%;"></td>
                    </tr> --}}
                </table>
            </div>

            {{-- END PAGE 2 --}}


        </div>
    </div>
</body>
</html>
