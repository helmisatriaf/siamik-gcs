<?php
// Set the maximum execution time to 300 seconds
set_time_limit(300);

// Your script logic here
$pathlogo = public_path('images/logo-school.png');
$typelogo = pathinfo($pathlogo, PATHINFO_EXTENSION);
$datalogo = file_get_contents($pathlogo);
$logo = 'data:image/' . $typelogo . ';base64,' . base64_encode($datalogo);

$pathcambridge = public_path('images/lcnew.png');
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
    <title>Mid Report Card Semester {{session('semester')}}</title>
    <link rel="icon" href="{{ asset('great.png') }}" type="image/x-icon">
    
    <style>
       @page {
            size: A5 portrait; /* bisa diganti landscape kalau perlu */
            margin: 10mm;      /* margin tepi kertas */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 148mm;
            height: 210mm;
            padding: 10mm;
            box-sizing: border-box;
        }

        .container {
            min-height: 100vh;
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
            width: 100%;
            text-align: center;
        }
        

        .header h1, .header h2, .header h4, .header h5 {
            font-size: 10px;
            margin: 0;
        }
        
        .header h5 {
            font-size: 14px;
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

        /* .mid {
            display: flex;
            justify-content: center;
        } */

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .tablesubject {
            width: 100%;
            border-collapse: collapse;
        }
        .tablesubjectName {
            width: 50%;
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
        .watermark-internal {
            position: absolute;
            top: 45%; /* Posisi vertikal tengah */
            left: 20%; /* Posisi horizontal tengah */
            transform: translate(-50%, -50%) rotate(-45deg); /* Pusatkan dan rotasi */
            font-size: 40px; /* Ukuran font */
           color: rgba(254,147,6, 0.5); /* Warna abu-abu dengan transparansi */
            white-space: nowrap; /* Tidak memecah teks */
            z-index: -1; /* Pastikan di belakang konten */
            width: 100%; /* Lebar teks */
            text-align: center; /* Penataan teks */
            user-select: none; /* Teks tidak bisa disorot */
            pointer-events: none; /* Tidak mengganggu interaksi pengguna */
        }
        .watermark-internal-2 {
            position: absolute;
            top: 100%; /* Posisi vertikal tengah */
            left: 20%; /* Posisi horizontal tengah */
            transform: translate(-50%, -50%) rotate(-45deg); /* Pusatkan dan rotasi */
            font-size: 40px; /* Ukuran font */
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
                /* display: flex; */
                /* flex-direction: column;
                justify-content: space-between; */
            }

            /* .content {
                flex: 1;
            } */

            .noto-serif-sc-simbol {
                font-family: "Noto Serif SC", serif;
                font-optical-sizing: auto;
                font-style: normal;
            }
        }
    </style>
</head>
<body>
    <div class="page"> 
        <div class="container">
            <div class="content">
                <!-- PAGE 1 -->
                    @if (session('role') == 'student' || session('role') == 'parent' || session('role') == 'teacher')
                        {{-- <p class="watermark-school">Great Crystal School</p>  --}}
                        <p class="watermark-internal">For Internal Purposes Only</p> 
                    @endif
                    <div class="header">
                        <div style="padding-left:50px;padding-right:50px;">
                            <img src="<?= $logo ?>" style="width:100%;height:10%;" alt="Sample image">
                        </div>
                        <h5>MID-SEMESTER REPORT</h5>
                        <span class="noto-serif-sc-simbol" style="font-size: 12px;">期中成绩报告</span>
                    </div>
        
                    <div style="margin-top:10px;">
                        <table class="tablesubjectName" style="border:none">
                            <!-- STUDENT STATUS -->
                            <tr>
                                <td style="text-align:left;font-size:10px;border: 1px solid black;padding: 2px;width:40%;"><b>Name</b> <span class="noto-serif-sc-simbol">姓名</span></td>
                                <td style="text-align:left;font-size:10px;border: 1px solid black;padding: 2px;">{{ ucwords(strtolower($student['student_name'])) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-size:10px;border: 1px solid black;padding: 2px;width:40%;"><b>Grade</b> <span class="noto-serif-sc-simbol">年级</span></td>
                                <td style="text-align:left;font-size:10px;border: 1px solid black;padding: 2px;">{{ $student->grade_name }} - {{ $student->grade_class }}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-size:10px;border: 1px solid black;padding: 2px;width:40%;"><b>Semester</b> <span class="noto-serif-sc-simbol">学期</span></td>
                                <td style="text-align:left;font-size:10px;border: 1px solid black;padding: 2px;">{{ $semester }}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-size:10px;border: 1px solid black;padding: 2px;width:40%;"><b>School Year</b> <span class="noto-serif-sc-simbol">学年</span></td>
                                <td style="text-align:left;font-size:10px;border: 1px solid black;padding: 2px;">{{ $academicYear }}</td>
                            </tr>
                            <!-- END STUDENT STATUS -->
                        </table>
                    </div>
        
                    <div>
                        <table class="tablesubject" style="margin-top:10px;">
                            <tr>
                                <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;width:5%;" colspan="1" rowspan="2"><b>No</b></td>
                                <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="4" rowspan="2"><b>Subject</b> <br> <span class="noto-serif-sc-simbol" style="font-size: 8px;">科目</span></td>
                                <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3"><b>Homework</b> <br> <span class="noto-serif-sc-simbol" style="font-size: 8px;">家庭作业 </span></td>
                                <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3"><b>Exercise</b> <br> <span class="noto-serif-sc-simbol" style="font-size: 8px;">课堂练习</span></td>
                                <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3"><b>Quiz</b> <br> <span class="noto-serif-sc-simbol" style="font-size: 8px;">小测验</span></td>
                                <td style="text-align:center;vertical-align : middle;font-size:10px;border: 1px solid black;" colspan="3"><b>Project    </b> <br> <span class="noto-serif-sc-simbol" style="font-size: 8px;">项目作业</span></td>
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
                                    <td style="text-align: left;vertical-align : middle;font-size:10px;padding-left: 5px;border: 1px solid black;" colspan="4">
                                        {{ $rs['subject_name'] }} @switch($rs['subject_name'])
                                            @case($rs['subject_name'] == 'English')
                                                <span class="noto-serif-sc-simbol">英语</span>
                                                @break
                                            @case($rs['subject_name'] == 'Chinese Higher')
                                                <span class="noto-serif-sc-simbol">中文（高级）</span>
                                                @break
                                            @case($rs['subject_name'] == 'Chinese Lower')
                                                <span class="noto-serif-sc-simbol">中文（初级）</span>
                                                @break
                                            @case($rs['subject_name'] == 'Chinese')
                                                <span class="noto-serif-sc-simbol">汉语</span>
                                                @break
                                            @case($rs['subject_name'] == 'Mathematics')
                                                <span class="noto-serif-sc-simbol">数学</span>
                                                @break
                                            @case($rs['subject_name'] == 'Science')
                                                <span class="noto-serif-sc-simbol">科学</span>
                                                @break
                                            @case($rs['subject_name'] == 'Religion')
                                                <span class="noto-serif-sc-simbol">科学</span>
                                                @break
                                            @case($rs['subject_name'] == 'Bahasa Indonesia')
                                                <span class="noto-serif-sc-simbol">印度尼西亚语</span>
                                                @break
                                            @case($rs['subject_name'] == 'CB & Manner')
                                                <span class="noto-serif-sc-simbol">品格培养</span>
                                                @break
                                            @case($rs['subject_name'] == 'Character Building')
                                                <span class="noto-serif-sc-simbol">品格培养</span>
                                                @break
                                            @case($rs['subject_name'] == 'PE')
                                                <span class="noto-serif-sc-simbol">体育</span>
                                                @break
                                            @case($rs['subject_name'] == 'IT')
                                                <span class="noto-serif-sc-simbol">信息技术</span>
                                                @break
                                            @case($rs['subject_name'] == 'Financial Literacy')
                                                <span class="noto-serif-sc-simbol">金融素养</span>
                                                @break
                                            @case($rs['subject_name'] == 'General Knowledge')
                                                <span class="noto-serif-sc-simbol">常识</span>
                                                @break
                                            @case($rs['subject_name'] == 'PPKn')
                                                <span class="noto-serif-sc-simbol">国家与公民教育</span>
                                                @break
                                            @case($rs['subject_name'] == 'Art and Craft')
                                                <span class="noto-serif-sc-simbol">美术与手工</span>
                                                @break
                                            @case($rs['subject_name'] == 'Health Education')
                                                <span class="noto-serif-sc-simbol">健康教育</span>
                                                @break
                                            @case($rs['subject_name'] == 'IPS')
                                                <span class="noto-serif-sc-simbol">社会科学</span>
                                                @break
                                            @default
                                                
                                        @endswitch
    
                                    
                                    </td>
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
                @if (session('role') == 'student' || session('role') == 'parent' || session('role') == 'teacher')
                    {{-- <p class="watermark-school">Great Crystal School</p>  --}}
                    <p class="watermark-internal-2">For Internal Purposes Only</p> 
                @endif
    
                {{-- <div class="header">
                    <div style="padding-left:50px;padding-right:50px;">
                        <img src="<?= $logo ?>" style="width:100%;height:10%;" alt="Sample image">
                    </div>
                </div> --}}
    
                <div>
                    <table class="tableMonthly" style="margin-top: 10px;">
                        <tr>
                            <td colspan="{{count($monthlyAct) + 1}}"style="text-align:center;"><b>Monthly Activities</b> <span class="noto-serif-sc-simbol">每月活动</span></td>
                        </tr>
                        <tr>
                            <td style="width:25%;text-align:left;"><b>Participation</b> <span class="noto-serif-sc-simbol">参与情况</span></td>
                            @foreach ($monthlyAct as $ma)
                                <td style="text-align:center;">{{$ma->name}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td style="width:25%;"><b>Grade</b> <span class="noto-serif-sc-simbol">分数</span></td>
                            @foreach ($scoreMonthly as $sm)
                            <td style="text-align:center;">{{$sm->grades}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td style="width:25%;"><b>Score</b> <span class="noto-serif-sc-simbol">分数</span></td>
                            @foreach ($scoreMonthly as $ssm)
                            <td style="text-align:center;">{{$ssm->score}}</td>
                            @endforeach
                        </tr>
                    </table>
                </div>
    
                <!-- Container pakai flex -->
                {{-- <div style="display:flex; justify-content:flex-start; gap:30px; margin-top:10px;">
    
                    <!-- Kolom 1: Scores -->
                    <div>
                        <table class="tableScore">
                            <tr>
                                <td style="text-align:center;font-size:10px;padding:0 16px;">
                                    <b>Scores</b><br><span class="noto-serif-sc-simbol">分数</span>
                                </td>
                                <td style="text-align:center;font-size:10px;padding:0 16px;">
                                    <b>Grade</b><br><span class="noto-serif-sc-simbol">等级</span>
                                </td>
                            </tr>
                            <tr><td style="text-align:center;font-size:10px;">95-100</td><td style="text-align:center;font-size:10px;">A+</td></tr>
                            <tr><td style="text-align:center;font-size:10px;">85-94</td><td style="text-align:center;font-size:10px;">A</td></tr>
                            <tr><td style="text-align:center;font-size:10px;">75-84</td><td style="text-align:center;font-size:10px;">B</td></tr>
                            <tr><td style="text-align:center;font-size:10px;">65-74</td><td style="text-align:center;font-size:10px;">C</td></tr>
                            <tr><td style="text-align:center;font-size:10px;">45-64</td><td style="text-align:center;font-size:10px;">D</td></tr>
                            <tr><td style="text-align:center;font-size:10px;">&lt; 44</td><td style="text-align:center;font-size:10px;">R</td></tr>
                        </table>
                    </div>
    
                    <!-- Kolom 2: Attendance -->
                    <div>
                        <table class="tableScore" style="width:250px;">
                            <tr>
                                <th colspan="2" style="text-align:center;font-size:10px;padding:0 16px;">Student's Attendance</th>
                            </tr>
                            <tr>
                                <td style="font-size:10px;padding-left:3px;">Days Attended <span class="noto-serif-sc-simbol">出勤天数</span></td>
                                <td style="font-size:10px;padding-left:3px;">
                                    @if ($attendance[0]['present'] > 0) {{ $attendance[0]['present'] }} days @else 0 days @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;padding-left:3px;">Days Absent <span class="noto-serif-sc-simbol">勤天数</span></td>
                                <td style="font-size:10px;padding-left:3px;">
                                    @if ($attendance[0]['days_absent'] > 0) {{ $attendance[0]['days_absent'] }} day @else 0 day @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;padding-left:3px;">Days Permission <span class="noto-serif-sc-simbol">请假天数</span></td>
                                <td style="font-size:10px;padding-left:3px;">
                                    @if ($attendance[0]['permission'] > 0) {{ $attendance[0]['permission'] }} day @else 0 day @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;padding-left:3px;">Days Sick <span class="noto-serif-sc-simbol">病假天数</span></td>
                                <td style="font-size:10px;padding-left:3px;">
                                    @if ($attendance[0]['sick'] > 0) {{ $attendance[0]['sick'] }} day @else 0 day @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;padding-left:3px;">Late <span class="noto-serif-sc-simbol">迟到</span></td>
                                <td style="font-size:10px;padding-left:3px;">
                                    @if ($attendance[0]['total_late'] > 0) {{ $attendance[0]['total_late'] }} time @else 0 time @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div> --}}
    
                <!-- Tabel pembungkus 2 kolom -->
                <div>
                    <table style="width:95%; border:none;">
                        <tr style="padding:0px;">
                            <!-- Kolom kiri: Scores -->
                            <td style="vertical-align:top; width:50%; border:none;padding:0px;">
                                <table class="tableScore" style="width:auto;">
                                    <tr>
                                        <td style="text-align:center;font-size:10px;padding:0 16px;">
                                            <b>Scores</b><br><span class="noto-serif-sc-simbol">分数</span>
                                        </td>
                                        <td style="text-align:center;font-size:10px;padding:0 16px;">
                                            <b>Grade</b><br><span class="noto-serif-sc-simbol">等级</span>
                                        </td>
                                    </tr>
                                    <tr><td style="text-align:center;font-size:10px;">95-100</td><td style="text-align:center;font-size:10px;">A+</td></tr>
                                    <tr><td style="text-align:center;font-size:10px;">85-94</td><td style="text-align:center;font-size:10px;">A</td></tr>
                                    <tr><td style="text-align:center;font-size:10px;">75-84</td><td style="text-align:center;font-size:10px;">B</td></tr>
                                    <tr><td style="text-align:center;font-size:10px;">65-74</td><td style="text-align:center;font-size:10px;">C</td></tr>
                                    <tr><td style="text-align:center;font-size:10px;">45-64</td><td style="text-align:center;font-size:10px;">D</td></tr>
                                    <tr><td style="text-align:center;font-size:10px;">&lt; 44</td><td style="text-align:center;font-size:10px;">R</td></tr>
                                </table>
                            </td>
        
                            <!-- Kolom kanan: Attendance -->
                            <td style="vertical-align:top; width:50%; border:none;">
                                <table class="tableScore" style="width:250px;">
                                    <tr>
                                        <th colspan="2" style="text-align:center;font-size:10px;padding:0 16px;">Student's Attendance</th>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;padding-left:3px;">Days Attended <span class="noto-serif-sc-simbol">出勤天数</span></td>
                                        <td style="font-size:10px;padding-left:3px;">
                                            @if ($attendance[0]['present'] > 0) {{ $attendance[0]['present'] }} days @else 0 days @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;padding-left:3px;">Days Absent <span class="noto-serif-sc-simbol">勤天数</span></td>
                                        <td style="font-size:10px;padding-left:3px;">
                                            @if ($attendance[0]['days_absent'] > 0) {{ $attendance[0]['days_absent'] }} day @else 0 day @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;padding-left:3px;">Days Permission <span class="noto-serif-sc-simbol">请假天数</span></td>
                                        <td style="font-size:10px;padding-left:3px;">
                                            @if ($attendance[0]['permission'] > 0) {{ $attendance[0]['permission'] }} day @else 0 day @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;padding-left:3px;">Days Sick <span class="noto-serif-sc-simbol">病假天数</span></td>
                                        <td style="font-size:10px;padding-left:3px;">
                                            @if ($attendance[0]['sick'] > 0) {{ $attendance[0]['sick'] }} day @else 0 day @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px;padding-left:3px;">Late <span class="noto-serif-sc-simbol">迟到</span></td>
                                        <td style="font-size:10px;padding-left:3px;">
                                            @if ($attendance[0]['total_late'] > 0) {{ $attendance[0]['total_late'] }} time @else 0 time @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
               
                {{-- ADDITIONAL EVALUATION --}}
                <div>
                    <table class="tableAdditional" style="margin-top:0px;">
                        <tr>
                            <td colspan="3" style="border-bottom: 5px double black"></td>
                        </tr>
                    </table>
                    <table class="tableAdditional" style="margin-top: -2px;">
                        <tr>
                            <td colspan="3"style="text-align:center;border-left:1px solid black;border-right:1px solid black;"><b>Additional Evaluation</b> <br><span class="noto-serif-sc-simbol">额外评估</span></td>
                        </tr>
                        <tr>
                            <td class="col-left" style="font-style: bold;border-left:1px solid black;">Critical Thinking <span class="noto-serif-sc-simbol">批判性思维</span></td>
                            <td class="col-mid">:</td>
                            <td class="col-right" style="border-right:1px solid black;">{{$ct}}</td>
                        </tr>
                        <tr>
                            <td class="col-left" style="font-style: bold;border-left:1px solid black;">Cognitive Skills <span class="noto-serif-sc-simbol">生活技能</span></td>
                            <td class="col-mid">:</td>
                            <td class="col-right" style="border-right:1px solid black;">{{$cs}}</td>
                        </tr>
                        <tr>
                            <td class="col-left" style="font-style: bold;border-left:1px solid black;">Life Skills <span class="noto-serif-sc-simbol">额外评估</span></td>
                            <td class="col-mid">:</td>
                            <td class="col-right" style="border-right:1px solid black;">{{$ls}}</td>
                        </tr>
                        <tr>
                            <td class="col-left" style="font-style: bold;border-left:1px solid black;">Learning Skills <span class="noto-serif-sc-simbol">学习技能</span></td>
                            <td class="col-mid">:</td>
                            <td class="col-right" style="border-right:1px solid black;">{{$les}}</td>
                        </tr>
                        <tr style="border-bottom:1px solid black;">
                            <td class="col-left" style="font-style: bold;border-left:1px solid black;">Social and Emotional Development <span class="noto-serif-sc-simbol">社会与情感发展</span></td>
                            <td class="col-mid">:</td>
                            <td class="col-right" style="border-right:1px solid black;">{{$saed}}</td>
                        </tr>
                    </table>
                </div>
    
                <div>
                    <table class="table" style="margin-top:50px;">
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
                        <tr>
                            <td style="text-align:center;padding-top:5px;"> <img src="<?= $cambridge ?>" style="width:120px;height:20px;"></td>
                        </tr>
                    </table>
                </div>
    
                {{-- END PAGE 2 --}}
            </div>
        </div>
    </div>
</body>
</html>
