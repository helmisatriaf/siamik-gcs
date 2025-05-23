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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@200..900&display=swap');

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
            /* margin-top: 95px; */
            width: 100%;
            text-align: center;
        }

        .header h1, .header h2, .header h5, .header h4, .header h5 {
            font-size: 11px;
            margin: 0;
        }

        /* .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .footer table {
            width: 100%;
        } */

        .mid {
            display: flex;
            justify-content: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            font-size: 10px;
        }

        table td {
            font-size: 10px;
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
            top: 30%;
            z-index: -1;
        }

        .page-break {
            page-break-before: always;
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
        <div style="padding-left:50px;padding-right:50px;">
           <img src="<?= $logo ?>" style="width:100%;height:10%;" alt="Sample image">
       </div>
        <div class="header">
            <!-- PAGE 1 -->
            <div class="">
                @if ($semester == 1)
                    <h5 style="margin-bottom:1px;"><u>Mid-Semester I Progress Report</u></h5>
                    <h5 style="margin-bottom:1px;"><u>School Year {{ $academicYear }}</u></h5>
                @elseif ($semester == 2)
                    <h5 style="margin-bottom:1px;"><u>Mid-Semester II Progress Report</u></h5>
                    <h5 style="margin-bottom:1px;"><u>School Year {{ $academicYear }}</u></h5>
                @endif
            </div>

            <div style="margin-top:5px;">
                <table class="" style="border:none">
                    <!-- STUDENT STATUS -->
                    <tr>
                        <td style="text-align:left;font-size:10px;padding:0px;"><b>Student Name</b></td>
                        <td style="text-align:left;font-size:10px;padding:0px;"><b> : {{ ucwords(strtolower($student['student_name'])) }}</b></td>
                    </tr>
                    <tr>
                        <td style="text-align:left;font-size:10px;padding:0px;"><b>Grade</b></td>
                        <td style="text-align:left;font-size:10px;padding:0px;"><b> : {{ $student->grade_name }} - {{ $student->grade_class }}</b></td>
                    </tr>
                    <tr>
                        <td style="font-size:10px;padding:0px;"><b>Attendance</b></td>
                        <td style="font-size:10px;padding:0px;">
                            @if ($attendance[0]['attendance'] > 0)
                            <b> : {{ $attendance[0]['attendance'] }} days</b>
                            @else
                            <b> : -</b>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:10px;padding:0px;"><b>Absent</b></td>
                        <td style="font-size:10px;padding:0px;">
                            @if ($attendance[0]['days_absent'] > 0)
                            <b> : {{ $attendance[0]['days_absent'] }} days</b>
                            @else
                            <b> : - </b>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:10px;padding:0px;"><b>Sick</b></td>
                        <td style="font-size:10px;padding:0px;">
                            @if ($attendance[0]['sick'] > 0)
                            <b> : {{ $attendance[0]['sick'] }} days</b>
                            @else
                            <b> : - </b>
                            @endif
                        </td>
                    </tr>
                    <td style="font-size:10px;padding:0px;"><b>Permission</b></td>
                    <td style="font-size:10px;padding:0px;">
                        @if ($attendance[0]['permission'] > 0)
                        <b> : {{ $attendance[0]['permission'] }} days</b>
                        @else
                        <b> : - </b>
                        @endif
                    </td>
                    <!-- END STUDENT STATUS -->
                </table>
            </div>

            <div>
                <table class="table" style="margin-top:10px;">
                    <tr>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Subjects</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Exercise 1</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Quiz 1</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Exercise 2</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Quiz 2</th>
                    </tr>
                    @if (count($result) > 0)
                        @foreach ($result[0]['subjects'] as $rs)
                        <tr>
                            <td style="text-align: left;vertical-align : middle;font-size:10px;padding-left: 5px;border: 1px solid black;">{{ $rs['subject_name'] }}</td>
                            @php
                                $exercise1 = '&nbsp;';
                                $quiz1 = '&nbsp;';
                                $exercise2 = '&nbsp;';
                                $quiz2 = '&nbsp;';
                            @endphp
                            @foreach ($rs['scores'] as $rss)
                                @if ($rss['type_exam'] == 2)
                                    @if ($exercise1 == '&nbsp;')
                                        @php $exercise1 = $rss['score']; @endphp
                                    @else
                                        @php $exercise2 = $rss['score']; @endphp
                                    @endif
                                @elseif ($rss['type_exam'] == 3)
                                    @if ($quiz1 == '&nbsp;')
                                        @php $quiz1 = $rss['score']; @endphp
                                    @else
                                        @php $quiz2 = $rss['score']; @endphp
                                    @endif
                                @endif
                            @endforeach
                            <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $exercise1 !!}</td>
                            <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $quiz1 !!}</td>
                            <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $exercise2 !!}</td>
                            <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">{!! $quiz2 !!}</td>
                        </tr>
                        @endforeach
                    @endif
                </table>
            </div>

            <div>
                <table class="table" style="margin-top:2px;">
                    <tr>
                        <th style="text-align: left;vertical-align : middle;font-size:10px;" colspan="5"> My Ability Chart</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;width:27%;">Subject / Attitude</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Excellent</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Good</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Satisfactory</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;width:27%;">Needs Improvement</th>
                    </tr>
                    @foreach($subjects as $subject)
                    <tr>
                        <td style="text-align: left;border:1px solid black;padding-left: 5px;">
                            {{ $subject['name'] }}
                        </td>
                        @for ($i = 1; $i <= 4; $i++)
                            <td style="text-align:center;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                                @if ($score->{$subject['field']} == $i)
                                    √
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endforeach
                </table>
            </div>

            <div>
                <table class="table" style="margin-top:2px;">
                    <tr>
                        <th style="text-align: left;vertical-align : middle;font-size:10px;" colspan="5"> Monthly Activities</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;width:27%;">Theme</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Excellent</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Good</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Satisfactory</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;width:27%;">Needs Improvement</th>
                    </tr>
                    @foreach($scoreMonthly as $monthly)
                    <tr>
                        <td style="text-align: left;border:1px solid black;padding-left: 5px;">
                            {{ $monthly['name_activity'] }}
                        </td>
                        @for ($i = 1; $i <= 4; $i++)
                            <td style="text-align:center;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                                @if ($monthly['score'] == $i)
                                    √
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endforeach
                </table>
            </div>

            {{-- <div>
                <table class="table" style="margin-top:10px;">
                    <tr>
                        <td style="text-align: left;vertical-align : middle;font-size:10px;" colspan="5"> Monthly Activitiest</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Theme</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Excellent</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Good</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Satisfactory</th>
                        <th style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;">Needs Improvement</th>
                    </tr>
                    @foreach($scoreMonthly as $monthly)
                    <tr>
                        <td style="text-align: left;border:1px solid black;padding-left: 5px;">
                            {{ $monthly['name_activity'] }}
                        </td>
                        @for ($i = 1; $i <= 4; $i++)
                            <td style="text-align:center;border: 1px solid black;padding-left:3px;" class="noto-serif-sc-simbol">
                                @if ($monthly['score'] == $i)
                                    √
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endforeach
                </table>
            </div> --}}
        </div>

        {{-- <div class="footer">
            <table class="table" style="">
                <tr>
                    <td style="text-align:center;text-decoration:underline;">Yuliana Harijanto, B.Eng (Hons).</td>
                </tr>
                <tr>
                    <td style="text-align:center;font-size:10px;"><b>Head of Preschool and KG</b></td>
                </tr>
                <tr>
                    <td style="text-align:center;padding-top:5px;font-size:10px;font-color:gray;"><i>This report card is for internal circulation only.</i></td>
                </tr>
                <tr>
                    <td style="text-align:center;padding-top:5px;"> <img src="<?= $cambridge ?>" style="width:23%;height:2,5%;"></td>
                </tr>
            </table>
        </div> --}}
        <!-- END PAGE 1 -->

        <div class="page-break"></div>

        {{-- PAGE 2 --}}
        <div class="">
            <div>
                <table class="table" style="margin-top:10px;">
                    <tr>
                        <td style="text-align: center;vertical-align : middle;font-size:10px;border: 1px solid black;padding: 10px;width:29%;">Remarks</td>
                        <td style="text-align: justify;vertical-align : middle;font-size:10px;border: 1px solid black;padding: 4px;width: 71%;">{{$score['remarks']}}</td>
                    </tr>
                </table>
            </div>

            <table class="table">
                <tr>
                    <td style="height:30px;" colspan="2"></td>
                </tr>
                <tr>
                    <td style="text-align:center;" colspan="2">
                        <div style="display: inline-block; width: 45%; border-bottom: 1px solid black;font-size:12px;">Yuliana Harijanto, B.Eng (Hons)</div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;font-size:12px;" colspan="2"><b>Head of Preschool and KG</b></td>
                </tr>
                <tr>
                    <td style="text-align:center;padding-top:10px;font-size:12px;font-color:black;"colspan="2"><i>This report card is for internal circulation only.</i></td>
                </tr>
                {{-- <tr>
                    <td style="text-align:center;" colspan="2"><b><span class="noto-serif-sc-chinese">老师签名</span></b></td>
                </tr> --}}
            </table>
            <div class="footer" style="margin-top: 25px;text-align:center;">
                <img src="<?= $cambridge ?>" style="width:45%;height:6%;">
            </div>
        </div>
        {{-- END PAGE 2 --}}
    </div>
</body>
</html>
