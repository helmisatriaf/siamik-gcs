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
            margin: 0mm;
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
    @foreach ($scores as $scores => $value)
        <div class="header">
            <div style="padding-left:50px;padding-right:50px;margin-bottom:5px;">
                <img src="<?= $logo ?>" style="width:90%;height:8%;" alt="Sample image">
            </div>
            <h1 style="margin-bottom:5px;font-size:18px;">DATA SUMMARY OF ASSESSMENT ACADEEMIC</h1>
            <h1 style="margin-bottom:2px;font-size:18px;">SEMESTER {{ $semester }} ACADEMIC YEAR {{ $academicYear }}</h1>
        </div>
            <table class="table">
                <tr style="border: 1px black solid;">
                    <th colspan="{{ $value['colspan'] }}">{{ $scores }}</th>
                </tr>
                <tr>
                    <td>Student Name</td>
                    @foreach ($value['column'] as $sg)
                        <td>{{ $sg }}</td>
                    @endforeach
                </tr>

                @if (count($value) > 0)
                @foreach ($value['sooaStudent'] as $student)
                <tr>
                    <td>{{ $student['student_name'] }}</td>
                    @if ($scores == 'Primary-1' || $scores == 'Primary-2' || $scores == 'Primary-3' || $scores == 'Primary-4' || $scores == 'Primary-5' || $scores == 'Primary-6')
                        <td>{{ $student['scores']['0']['academic'] }}</td>                        
                        <td>{{ $student['scores']['0']['choice'] }}</td>
                        <td>{{ $student['scores']['0']['language_and_art'] }}</td>
                        <td>{{ $student['scores']['0']['self_development'] }}</td>
                        <td>{{ $student['scores']['0']['eca_aver'] }}</td>
                        <td>{{ $student['scores']['0']['behavior'] }}</td> 
                        <td>{{ $student['scores']['0']['attendance'] }}</td>  
                        <td>{{ $student['scores']['0']['participation'] }}</td> 
                        <td>{{ $student['scores']['0']['final_score'] }}</td>                                                                                                                                                                                            
                    @elseif ($scores == 'Secondary-1' || $scores == 'Secondary-2' || $scores == 'Secondary-3')
                    <td>{{ $student['scores']['0']['academic'] }}</td>                        
                        <td>{{ $student['scores']['0']['eca_1'] }}</td>
                        <td>{{ $student['scores']['0']['eca_2'] }}</td>
                        <td>{{ $student['scores']['0']['self_development'] }}</td>
                        <td>{{ $student['scores']['0']['eca_aver'] }}</td>
                        <td>{{ $student['scores']['0']['behavior'] }}</td> 
                        <td>{{ $student['scores']['0']['attendance'] }}</td>  
                        <td>{{ $student['scores']['0']['participation'] }}</td> 
                        <td>{{ $student['scores']['0']['final_score'] }}</td>  
                    @endif
                    <td>{{ $student['ranking'] }}</td>
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
