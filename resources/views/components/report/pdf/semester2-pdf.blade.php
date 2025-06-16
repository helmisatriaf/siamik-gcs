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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Report Card Semester 2</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@900&display=swap');

        .noto-serif-sc-chinese {
            font-family: "Noto Sans SC", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        body {
            font-family: Arial, sans-serif;
        }
        .header {
            margin-top: 100px;
            text-align: center;
        }
        .header h1, .header h2 ,.header h5, .header h4, .header h5 {
            margin: 0;
        }

        .footer {
            margin: 0;
        }

        .mid {
            display: flex;
            justify-content: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table td {
            font-size:10px;
        }
        .table th {
            font-size:12px;
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
        
        @page {
            margin: 5mm 5mm 0mm 5mm;
        }
    </style>
</head>
<body>
<div class="container"> 
    <!-- PAGE 1 -->
        @if ($subjectReports[0]['isRestricted'] === TRUE)
        <p class="watermark">Internal Use Only</p>  
        @endif
        <div class="header">
            <!-- <div style="padding-left:50px;padding-right:50px;margin-bottom:5px;">
                <img src="<?= $logo ?>" style="width:90%;height:8%;" alt="Sample image">
            </div> -->
            <h5>Report Card</h5>
            <h5>Semester II School Year {{ $academicYear }}</h5>
        </div>

        <div>
            <table class="table">
                <!-- STUDENT STATUS -->
                <tr>
                    <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;"><b>Student Status</b></th>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;border-left: solid 1px black;">Name:</td>
                    <td style="border: 1px solid black;padding-left:8px;" colspan="3">{{ ucwords(strtolower($student->student_name)) }}</td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;"  colspan="2">Date:</td>
                    <td style="border: 1px solid black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;border-left: solid 1px black;">Class:</td>
                    <td style="border: 1px solid black;padding-left:8px;" colspan="3">{{ $student->grade_name}} - {{ $student->grade_class }}</td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;"  colspan="2">Class Teacher</td>
                    <td style="border: 1px solid black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ $classTeacher->teacher_name }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;border-left: solid 1px black;">Serial:</td>
                    <td style="border: 1px solid black;padding-left:8px;" colspan="3">{{ $serial }}</td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;"  colspan="2">Date of Registration</td>
                    <td style="border: 1px solid black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ $date_of_registration }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;border-left: solid 1px black;">Days Absent:</td>
                    <td style="border: 1px solid black;padding-left:8px;" colspan="3">{{ $attendance[0]['days_absent'] }} day</td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;" colspan="2">Total Days Absent:</td>
                    <td style="border: 1px solid black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ $attendance[0]['days_absent'] }}  days</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;border-left: solid 1px black;">Times Late:</td>
                    <td style="border: 1px solid black;padding-left:8px;" colspan="3">{{ $attendance[0]['total_late'] }}</td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;"  colspan="2">Total Times Late:</td>
                    <td style="border: 1px solid black;padding-left:8px;border-right: solid 1px black;" colspan="2">{{ $attendance[0]['total_late'] }}</td>
                </tr>
                <!-- END STUDENT STATUS -->

                <!-- PROMOTION STATUS -->
                <tr>
                    <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-left: 1px solid black;border-right: 1px solid black"><strong>Promotion</strong></th>
                </tr>
                <tr>
                    <td style="text-align:center;border: 1px solid black;padding-right:8px;border-left: none;border-left: 1px solid black;" rowspan="3" colspan="1"><strong>Promotion Status</strong></td>
                    <td style="border: 1px solid black;padding-left:8px;border-right: 1px solid black;" colspan="7">
                        @if ($learningSkills->promotion_status === 1)
                        <i class="fa-solid fa-circle"></i>
                        Progressing well towards promotion
                        @else
                        <i class="fa-regular fa-circle"></i>
                        Progressing well towards promotion
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;padding-left:8px;border-right: 1px solid black;" colspan="7">
                        <div style="display: flex; align-items: center;">
                            @if ($learningSkills->promotion_status === 2)
                            <i class="fa-solid fa-circle"></i>
                            Progressing with some difficulty towards promotion
                            @else
                            <i class="fa-regular fa-circle"></i>
                            Progressing with some difficulty towards promotion
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;padding-left:8px;border-right: 1px solid black;" colspan="7">
                        <div style="display: flex; align-items: center;">
                            @if ($learningSkills->promotion_status === 3)
                            <i class="fa-solid fa-circle"></i>
                            No promotion
                            @else
                            <i class="fa-regular fa-circle"></i>
                            No promotion
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                <td style="text-align:center;border: 1px solid black;padding-right:8px;border-left: 1px solid black;"colspan="1"><strong>Next Grade</strong></td>
                    <td style="border: 1px solid black;padding-left:8px;border-right: 1px solid black;" colspan="7">{{ $promotionGrade }}</td>
                </tr>
                <!-- END PROMOTION STATUS -->

                <!-- DESCRIPTION OF GRADES -->
                <tr>
                    <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;"><b>Description of Grades</b></th>
                </tr>
                <tr>
                    <td style="text-align:center;border: 1px solid black;border-left: solid 1px black;">Scores</td>
                    <td style="text-align:center;border: 1px solid black;">Grade</td>
                    <td style="text-align:center;border: 1px solid black;border-right: solid 1px black;" colspan="6">Achievement of the Curriculum Expectations</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align:center;border-left: solid 1px black;">95 – 100</td>
                    <td style="border: 1px solid black;text-align:center;">A<sup>+</sup></td>
                    <td style="border: 1px solid black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated excellent knowledge and skills, <br> Achievement far exceeds the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align:center;border-left: solid 1px black;">85 – 94</td>
                    <td style="border: 1px solid black;text-align:center;">A</td>
                    <td style="border: 1px solid black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated the required knowledge and skills <br> Achievement exceeds the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align:center;border-left: solid 1px black;">75 – 84</td>
                    <td style="border: 1px solid black;text-align:center;">B</td>
                    <td style="border: 1px solid black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated most of the required knowledge and skills <br> Achievement meets the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align:center;border-left: solid 1px black;">65 – 74</td>
                    <td style="border: 1px solid black;text-align:center;">C</td>
                    <td style="border: 1px solid black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated some of the required knowledge and skills <br> Achievement approaches the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align:center;border-left: solid 1px black;">45 – 64</td>
                    <td style="border: 1px solid black;text-align:center;">D</td>
                    <td style="border: 1px solid black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has demonstrated some of the required knowledge and skills in limited ways. Achievement falls much below the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align:center;border-left: solid 1px black;">&lt; 44</td>
                    <td style="border: 1px solid black;text-align:center;">R</td>
                    <td style="border: 1px solid black;border-right: solid 1px black;padding-left:10px;" colspan="6">The student has failed to demonstrate the required knowledge and skills. <br> Extensive remediation is required.</td>
                </tr>
                <!-- END DESCRIPTION OF GRADES -->

                <!-- LEARNING SKILLS -->
                <tr>
                    <th  colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;"><b>Learning Skills</b></th>
                </tr>
                <tr>
                    <td style="text-align:center;border: 1px solid black;border-left: solid 1px black;"><b>Legend:</b></td>
                    <td colspan="7" style="text-align:center;border: 1px solid black;border-right: solid 1px black;"><b>E – Excellent   G – Good   S – Satisfactory   N – Needs Improvement</b></td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">Independent Work</td>
                    <td style="border: 1px solid black;text-align:center;"> {{ strtoUpper($learningSkills->independent_work) }} </td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;">Use of information</td>
                    <td style="border: 1px solid black;text-align:center;"> {{ strtoUpper($learningSkills->use_of_information) }} </td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;" colspan="2">Class participation</td>
                    <td style="border: 1px solid black;text-align:center;border-right: solid 1px black;"> {{ strtoUpper($learningSkills->class_participation) }} </td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">Initiative</td>
                    <td style="border: 1px solid black;text-align:center;"> {{ strtoUpper($learningSkills->initiative) }} </td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;" >Cooperation with others</td>
                    <td style="border: 1px solid black;text-align:center;"> {{ strtoUpper($learningSkills->cooperation_with_other) }} </td>
                    <td style="text-align:right;border: 1px solid black;padding-right:8px;" colspan="2">Problem solving</td>
                    <td style="border: 1px solid black;text-align:center;border-right: solid 1px black;"> {{ strtoUpper($learningSkills->problem_solving) }} </td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1px solid black;border-bottom: 1.5px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">Homework completion</td>
                    <td style="border: 1px solid black;border-bottom: 1.5px solid black;text-align:center;"> {{ strtoUpper($learningSkills->homework_completion) }} </td>
                    <td style="text-align:right;border: 1px solid black;border-bottom: 1.5px solid black;padding-right:8px;">Conflict resolution</td>
                    <td style="border: 1px solid black;border-bottom: 1.5px solid black;text-align:center;"> {{ strtoUpper($learningSkills->conflict_resolution) }} </td>
                    <td style="text-align:right;border: 1px solid black;border-bottom: 1.5px solid black;padding-right:8px;" colspan="2">Goal setting to improve work</td>
                    <td style="border: 1px solid black;border-bottom: 1.5px solid black;text-align:center;border-right: solid 1px black;"> {{ strtoUpper($learningSkills->goal_setting_to_improve_work) }} </td>
                </tr>
                <!-- END LEARNING SKILLS -->

                <!-- SIGNATURE -->
                <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                    <td style="text-align:left;height:80px;text-decoration:underline;" colspan="3"></td>
                    <td style="text-align:center;height:80px;" colspan="2"></td>
                    <td style="text-align:right;height:80px;padding-right:20px" colspan="3"></td>
                </tr>
                <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                    <td style="text-align:center;text-decoration:underline;" colspan="3">
                        @if ($subjectReports[0]['isRestricted'] === FALSE)
                        {{ $classTeacher->teacher_name }}
                        @endif
                    </td>
                    @if(strtolower($student->grade_name) == "primary")
                        <td style="text-align:center;text-decoration:underline;" colspan="2">Yuliana Harijanto, B.Eng (Hons)</td>
                    @elseif (strtolower($student->grade_name) == "secondary")
                        <td style="text-align:center;text-decoration:underline;" colspan="2">Donny Prasetya, S.Kom.</td>
                    @endif
                    <td style="text-align:center;text-decoration:underline;" colspan="3">
                        {{-- @if ($relation == null)
                        -
                        @else
                        {{ ucwords(strtolower($relation['relationship_name'])) }}
                        @endif --}}
                    </td>
                </tr>
                <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                    <td style="text-align:center;border-bottom: 3px solid black;" colspan="3">
                        @if ($subjectReports[0]['isRestricted'] === FALSE)
                        <b>Class Teacher's Signature</b>
                        @endif
                    </td>
                    <td style="text-align:center;border-bottom: 3px solid black;" colspan="2"><b>Principal's Signature</b></td>
                    <td style="text-align:center;border-bottom: 3px solid black;" colspan="3">
                        @if ($subjectReports[0]['isRestricted'] === FALSE)
                        <b>Parent's Signature</b>
                        @endif
                    </td>
                </tr>
            </table>
            <table class="table">
                <tbody>
                    <tr>
                        <td  style="vertical-align : top;text-align:left;width:15%;">{{ \Carbon\Carbon::parse($date)->format('m/d/Y') }}</td>
                        <td  style="text-align:center;padding-top: 4px;"> 
                            {{-- <img src="<?= $cambridge ?>" style="width:40%;" alt="Sample image"> --}}
                        </td>
                        <td  style="vertical-align : top;text-align:right;width:15%;">Page 1 of 2</td>
                    </tr>
                </tbody>
            </table>
        </div>
    <!-- END PAGE 1 -->
    

    <div class="page-break"></div>


    <!-- PAGE 2 -->
        @if ($subjectReports[0]['isRestricted'] === TRUE)
        <p class="watermark">Internal Use Only</p>
        @endif
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;">Subjects Report</th>
                    </tr>
                    <tr style="text-align:center;border-bottom: 1px solid black;">
                        <th style="text-align:center;border: 1px solid black;border-left:solid 1px black;width:10%">Subjects</th>
                        <th style="text-align:center;border: 1px solid black;width:10%">Marks</th>
                        <th style="text-align:center;border: 1px solid black;width:10%">Grades</th>
                        <th style="text-align:center;border: 1px solid black;border-right:solid 1px black;width:70%" colspan="5">Strengths/Weaknesses/Next Steps</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($subjectReports[0]['scores'] as $scores)
                        <!-- SUBJECT REPORT -->
                        <tr>
                            <td style="text-align:left;border: 1px solid black;padding:3px;border-left: solid 1px black;style">
                                @if ($student->student_id === 356)
                                    @if ($scores['subject_name'] === "Chinese")
                                        Chinese-basic
                                    @else   
                                        {{ $scores['subject_name'] }}
                                    @endif
                                @else
                                    {{ $scores['subject_name'] }}
                                @endif
                            </td>
                            <td style="text-align:center;border: 1px solid black;padding:3px;">{{ $scores['final_score'] }}</td>
                            <td style="text-align:center;border: 1px solid black;padding:3px;">{{ $scores['grades'] }}</td>
                            <td style="text-align:justify;border: 1px solid black;border-right: solid 1px black;padding-left:3px;padding-right:3px;" colspan="5">
                                <span 
                                @if ($scores['isChinese'] === 1) 
                                    class="noto-serif-sc-chinese" 
                                @else 
                                    style="font-style: italic;" 
                                @endif
                                >
                                    {{ $scores['comment'] }}
                                </span>
                            </td>
                        </tr>
                        <!-- END SUBJECT REPORT -->
                    @endforeach
                    
                <!-- ECA -->
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;">Non-Academic Activity</th>
                    </tr>

                    @if (strtolower($grade_name) == "primary")
                        <tr>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;" colspan="2">ECA (1)</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-left:4px;">Grade</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;">
                                ECA (2)
                                {{-- @if (empty($eca))
                                @else
                                    ({{ $eca['eca_1'] }})
                                @endif --}}
                            </td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-left:4px;border-right: solid 1px black;">Grade</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-left:4px;border-right: solid 1px black;" colspan="2">Self-Development</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-left:4px;border-right: solid 1px black;">Grade</td>
                        </tr>
                    @elseif (strtolower($grade_name) == "secondary")
                        <tr>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;" colspan="2">
                                ECA (1)
                                {{-- @if (empty($eca))
                                @else
                                    ({{ $eca['eca_1'] }})
                                @endif --}}
                            </td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-left:4px;">Grade</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;">
                                ECA (2)
                                {{-- @if (empty($eca) || count($eca) == 3)
                                @else
                                ({{ $eca['eca_2'] }})
                                @endif --}}
                            </td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-left:4px;border-right: solid 1px black;">Grade</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-left:4px;border-right: solid 1px black;" colspan="2">Self-Development</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-left:4px;border-right: solid 1px black;">Grade</td>
                        </tr>
                    @endif
                    
                    <tr>
                        @if (strtolower($grade_name) == "primary")
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;" colspan="2">{{ $sooa[0]['scores'][0]['language_and_art'] }}</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;">{{  $sooa[0]['scores'][0]['grades_language_and_art'] }}</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;">
                                @if ($sooa[0]['scores'][0]['choice'] == 0)
                                    -
                                @else
                                    {{  $sooa[0]['scores'][0]['choice'] }}
                                @endif
                            </td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: dottted 1px black;border-right: 1px solid black;">{{  $sooa[0]['scores'][0]['grades_choice'] }}</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: dottted 1px black;border-right: 1px solid black;" colspan="2">{{  $sooa[0]['scores'][0]['self_development'] }}</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: dottted 1px black;border-right: 1px solid black;">{{  $sooa[0]['scores'][0]['grades_self_development'] }}</td>
                        @elseif (strtolower($grade_name) == "secondary")
                            @if (count($subjectReports) !== 0)
                                <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;" colspan="2">
                                    @if ($sooa[0]['scores'][0]['eca_1'] == 0)
                                        -
                                    @else
                                        {{ $sooa[0]['scores'][0]['eca_1'] }}
                                    @endif
                                </td>
                                <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;">{{  $sooa[0]['scores'][0]['grades_eca_1'] }}</td>
                                <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;">{{  $sooa[0]['scores'][0]['eca_2'] }}</td>
                                <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: solid 1px black;border-right: 1px solid black;">{{  $sooa[0]['scores'][0]['grades_eca_2'] }}</td>
                                <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: dottted 1px black;border-right: 1px solid black;" colspan="2">{{  $sooa[0]['scores'][0]['self_development'] }}</td>
                                <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:4px;border-left: dottted 1px black;border-right: 1px solid black;">{{  $sooa[0]['scores'][0]['grades_self_development'] }}</td>
                            @else
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">-</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;">-</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;">-</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;">-</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;">-</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;" colspan="2">-</td>
                            <td style="text-align:center;border: 1px solid black;border-bottom: 1px solid black;padding-right:8px;border-left: solid 1px black;border-right: 1px solid black;">-</td>
                            @endif
                        @endif
                    </tr>
                <!-- END ECA -->

                <!-- OVERALL MARK -->
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;">Overall Mark</th>
                    </tr>
                    <tr>
                        <td style="text-align:center;border: 1px solid black;border-left: solid 1px black;width:12.5%;border-bottom: 1px solid black;">Academic</td>
                        <td style="text-align:center;border: 1px solid black;width:11.5%;border-bottom: 1px solid black;">Non-Academic</td>
                        <td style="text-align:center;border: 1px solid black;width:12.5%;border-bottom: 1px solid black;">Behaviour</td>
                        <td style="text-align:center;border: 1px solid black;width:15.5%;border-bottom: 1px solid black;">Attendance</td>
                        <td style="text-align:center;border: 1px solid black;width:15.5%;border-bottom: 1px solid black;">Participation</td>
                        <td style="text-align:center;border: 1px solid black;width:11.5%;border-bottom: 1px solid black;">Marks</td>
                        <td style="text-align:center;border: 1px solid black;width:10.5%;border-bottom: 1px solid black;">Grade</td>
                        <td style="text-align:center;border: 1px solid black;border-right: solid 1px black;width:10.5%;border-bottom: 1px solid black;">Rank</td>
                    </tr>
                    <tr>
                        @if (count($subjectReports) !== 0)
                        <td style="text-align:center;border: 1px solid black;padding-right:8px;border-left: solid 1px black;">{{  $sooa[0]['scores'][0]['academic'] }}</td>
                        <td style="text-align:center;border: 1px solid black;padding-left:8px;">{{ $sooa[0]['scores'][0]['eca_aver'] }}</td>
                        <td style="text-align:center;border: 1px solid black;padding-right:8px;">{{  $sooa[0]['scores'][0]['behavior'] }}</td>
                        <td style="text-align:center;border: 1px solid black;padding-left:8px;">{{  $sooa[0]['scores'][0]['attendance'] }}</td>
                        <td style="text-align:center;border: 1px solid black;padding-right:8px;">{{  $sooa[0]['scores'][0]['participation'] }}</td>
                        <td style="text-align:center;border: 1px solid black;padding-left:8px;">{{ $sooa[0]['scores'][0]['final_score'] }}</td>
                        <td style="text-align:center;border: 1px solid black;padding-right:8px;">{{ $sooa[0]['scores'][0]['grades_final_score'] }}</td>
                        <td style="text-align:center;border: 1px solid black;padding-left:8px;border-right: solid 1px black;">
                            -
                            {{-- {{  $sooa[0]['ranking'] }} --}}
                        </td>
                        @else
                        @endif
                    </tr>
                <!-- END OVERALL MARK -->

                    @if (count($tcop) !== 0)
                    <tr>
                        <th colspan="8" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;border-right: 1px solid black;border-left: 1px solid black;">Final Score</th>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:center;border-left:1px solid black;border-bottom: 1px solid black;border-right: 1px solid black;"><b>Average Mark</b></td>
                        <td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;"><b>Grade</b></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align:center;border-left:1px solid black;border-bottom: 1px solid black;border-right: 1px solid black;">{{ $tcop[0]['final_score'] }}</td>
                        <td colspan="4" style="text-align:center;border-right:1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;">{{ $tcop[0]['grades_final_score'] }}</td>
                    </tr>
                    @else
                    @endif
                <!-- FINAL SCORE -->
                <!-- END FINAL SCORE -->


                <!-- SIGNATURE -->
                    <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                        <td style="height:50px;" colspan="3"></td>
                        <td style="height:50px;" colspan="2"></td>
                        <td style="height:50px;" colspan="3"></td>
                    </tr>
                    <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                        <td style="text-align:center;text-decoration:underline;" colspan="3">
                            @if ($subjectReports[0]['isRestricted'] === FALSE)
                            {{ $classTeacher->teacher_name }}
                            @endif
                        </td>
                        @if(strtolower($student->grade_name) == "primary")
                            <td style="text-align:center;text-decoration:underline;" colspan="2">Yuliana Harijanto, B.Eng (Hons)</td>
                        @elseif (strtolower($student->grade_name) == "secondary")
                            <td style="text-align:center;text-decoration:underline;" colspan="2">Donny Prasetya, S.Kom.</td>
                        @endif
                        <td style="text-align:center;text-decoration:underline;" colspan="3">
                            {{-- @if ($relation == null)
                            
                            @else
                            {{ ucwords(strtolower($relation['relationship_name'])) }}
                            @endif --}}
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid black;border-left: 1px solid black;">
                        <td style="text-align:center;border-bottom: 3px solid black;" colspan="3">
                            @if ($subjectReports[0]['isRestricted'] === FALSE)
                            <b>Class Teacher's Signature</b>
                            @endif
                        </td>
                        <td style="text-align:center;border-bottom: 3px solid black;" colspan="2"><b>Principal's Signature</b></td>
                        <td style="text-align:center;border-bottom: 3px solid black;" colspan="3">
                            @if ($subjectReports[0]['isRestricted'] === FALSE)
                            <b>Parent's Signature</b>
                            @endif
                        </td>
                    </tr>
                <!-- END SIGNATURE -->
                </tbody>
            </table>
            <table class="table">
                <tbody>
                    <tr>
                        <td  style="vertical-align : top;text-align:left;width:15%;">{{ \Carbon\Carbon::parse($date)->format('m/d/Y') }}</td>
                        <td  style="text-align:center;padding-top: 4px;"> 
                            {{-- <img src="<?= $cambridge ?>" style="width:40%;" alt="Sample image"> --}}
                        </td>
                        <td  style="vertical-align : top;text-align:right;width:15%;">Page 2 of 2</td>
                    </tr>
                </tbody>
            </table>
        </div>
    <!-- END PAGE 2 -->
</div>

</body>
</html>
