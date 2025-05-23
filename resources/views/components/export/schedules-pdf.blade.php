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
    <title>Schedules PDF</title>
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
            page-break-inside: auto;
        }
        .table td, .table th {
            font-size: 14px;
            border: 1px black solid;
            padding: 5px;
        }
        .signature {
            text-align: center;
            margin-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
        @page {
            margin: 0mm 0mm 0mm 0mm;
        }
        .watermark {
            position: absolute;
            top: 30%;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="padding-left:50px;padding-right:50px;margin-bottom:5px;">
            <img src="<?= $logo ?>" style="width:90%;height:8%;" alt="Sample image">
        </div>
        <h1 style="margin-bottom:5px;font-size:18px;">DATA SHCEDULE</h1>
        <h1 style="margin-bottom:2px;font-size:18px;">SEMESTER {{ $semester }} ACADEMIC YEAR {{ $academicYear }}</h1>
    </div>
    @foreach ($data as $day => $grades)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="14">{{ $day }}</th>
                </tr>
                <tr>
                    <th>Time</th>
                    @foreach (array_keys($grades) as $grade)
                        <th>{{ $grade }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($grades as $grade => $schedules)
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}</td>
                            <td>
                                {{ $schedule['subject_name'] }}
                                <br>{{ $schedule['teacher_name'] }}
                                <br>{{ $schedule['assisstant'] ? $schedule['assisstant'] . ' (assistant)' : '' }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>

