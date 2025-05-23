<?php 
    $pathlogo = public_path('images/logo-school.png');
    $typelogo = pathinfo($pathlogo, PATHINFO_EXTENSION);
    $datalogo = file_get_contents($pathlogo);
    $logo = 'data:image/' . $typelogo . ';base64,' . base64_encode($datalogo);
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
            margin: 0;
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
        .header h1, .header h2, .header h1, .header h4, .header h1 {
            font-size: 10px;
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
            page-break-inside: auto; /* Important for page breaks */
        }
        .table td, .table th {
            font-size: 14px;
            border: 1px black solid;
            padding: 5px; /* Adjust padding as needed */
        }
        .signature {
            text-align: center;
            margin-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
        @page {
            margin: 20mm 20mm 20mm 20mm;
        }
        .watermark {
            position: absolute;
            top: 30%;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="container"> 
    <!-- PAGE 1 -->    
    @foreach ($grades as $grade)
        <div class="header">
            <div style="padding-left:50px;padding-right:50px;margin-bottom:5px;">
                <img src="<?= $logo ?>" style="width:90%;height:8%;" alt="Sample image">
            </div>
            <h1 style="margin-bottom:5px;font-size:18px;">DATA GRADES</h1>
            <h1 style="margin-bottom:2px;font-size:18px;">SEMESTER {{ $semester }} ACADEMIC YEAR {{ $academicYear }}</h1>
        </div>
            <table class="table">
                <tr style="border: 1px black solid;">
                    <th colspan="2">{{ $grade->grade_name }} (Class Teacher : {{ $grade->class_teacher }})</th>
                </tr>
                <tr>
                    <th style="border: 1px black solid;">Subject</th>
                    <th style="border: 1px black solid;">Teacher</th>
                </tr>
                @if (is_array($gradeSubjectTeacher) && array_key_exists($grade->grade_name, $gradeSubjectTeacher) && count($gradeSubjectTeacher[$grade->grade_name]['subjects']) > 0)
                    @foreach ($gradeSubjectTeacher[$grade->grade_name]['subjects'] as $index => $subject)
                        <tr>
                            <td>{{ $subject }}</td>
                            <td>{{ $gradeSubjectTeacher[$grade->grade_name]['teachers'][$index] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>    
                        <td colspan="2" style="text-align:center">Data empty</td>
                    </tr>
                @endif
            </table>
            <br><br>
            <!-- Page break if necessary -->
            <div class="page-break"></div>
        @endforeach
    </div>
</body>
</html>
