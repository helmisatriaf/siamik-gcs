<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
        }
        .header h1, .header h2 ,.header h3, .header h4, .header h5 {
            margin: 0;
        }

        .footer {
            margin: 0;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
        }
        .footer p {
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
        .signature {
            text-align: center;
            margin-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
        .watermark {
            position: absolute;
            top: 75%;
            z-index: -1;
        }
    </style>
</head>
<body>

<div class="container"><!-- PAGE 1 -->
        <div class="header">
            <h3>Report Card</h3>
            <h3>Semester I School Year 2023 – 2024</h3>
        </div>

        <div>
            <table class="table">
                <!-- STUDENT STATUS -->
                <tr>
                    <th colspan="6" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;"><strong>Student Status</strong></th>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;border-left: none;">Name:</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;" colspan="2">Muhammad Helmi </td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;">Date:</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;border-right: none;" colspan="2">06/06/2024</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;border-left: none;">Class:</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;" colspan="2">Grade-1</td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;">Class Teacher</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;border-right: none;" colspan="2">Mr.Helmi</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;border-left: none;">Serial:</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;" colspan="2">01</td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;">Date of Registration</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;border-right: none;" colspan="2">06/06/2024</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;border-left: none;">Days Absent:</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;" colspan="2">... day(s)</td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;">Total Days Absent:</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;border-right: none;" colspan="2">... day(s)</td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;border-left: none;">Times Late:</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;" colspan="2">0</td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;">Total Times Late:</td>
                    <td style="border: 1.5px dotted black;padding-left:8px;border-right: none;" colspan="2">0</td>
                </tr>
                <!-- END STUDENT STATUS -->

                <!-- DESCRIPTION OF GRADES -->
                <tr>
                    <th colspan="6" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;"><strong>Description of Grades</strong></th>
                </tr>
                <tr>
                    <th style="text-align:center;border: 1px solid black;border-left: none;">Scores</th>
                    <th style="text-align:center;border: 1px solid black;">Grade</th>
                    <th style="text-align:center;border: 1px solid black;border-right: none;" colspan="4">Achievement of the Curriculum Expectations</th>
                </tr>
                <tr>
                    <td style="border: 1.5px dotted black;text-align:center;border-left: none;">95 – 100</td>
                    <td style="border: 1.5px dotted black;text-align:center;">A<sup>+</sup></td>
                    <td style="border: 1.5px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated excellent knowledge and skills, <br> Achievement far exceeds the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1.5px dotted black;text-align:center;border-left: none;">85 – 94</td>
                    <td style="border: 1.5px dotted black;text-align:center;">A</td>
                    <td style="border: 1.5px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated the required knowledge and skills <br> Achievement exceeds the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1.5px dotted black;text-align:center;border-left: none;">75 – 84</td>
                    <td style="border: 1.5px dotted black;text-align:center;">B</td>
                    <td style="border: 1.5px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated most of the required knowledge and skills <br> Achievement meets the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1.5px dotted black;text-align:center;border-left: none;">65 – 74</td>
                    <td style="border: 1.5px dotted black;text-align:center;">C</td>
                    <td style="border: 1.5px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated some of the required knowledge and skills <br> Achievement approaches the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1.5px dotted black;text-align:center;border-left: none;">45 – 64</td>
                    <td style="border: 1.5px dotted black;text-align:center;">D</td>
                    <td style="border: 1.5px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has demonstrated some of the required knowledge and skills in limited ways. <br> Achievement falls much below the standard.</td>
                </tr>
                <tr>
                    <td style="border: 1.5px dotted black;text-align:center;border-left: none;">&lt; 44</td>
                    <td style="border: 1.5px dotted black;text-align:center;">E</td>
                    <td style="border: 1.5px dotted black;padding-left:20px;border-right: none;" colspan="4">The student has failed to demonstrate the required knowledge and skills. <br> Extensive remediation is required.</td>
                </tr>
                <!-- END DESCRIPTION OF GRADES -->

                <!-- LEARNING SKILLS -->
                <tr>
                    <th  colspan="6" style="text-align:center;border-top: 3px solid black;border-bottom: 3px solid black;"><strong>Learning Skills</strong></th>
                </tr>
                <tr>
                    <td style="text-align:center;border: 1px solid black;border-left: none;"><strong>Legend:</strong></td>
                    <td colspan="5" style="text-align:center;border: 1px solid black;border-right: none;"><strong>E – Excellent   G – Good   S – Satisfactory   N – Needs Improvement</strong></td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;width:20%;border-left: none;">Independent Work</td>
                    <td style="border: 1.5px dotted black;text-align:center;"> A </td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;width:20%;">Use of information</td>
                    <td style="border: 1.5px dotted black;text-align:center;"> A </td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;width:20%;">Class participation</td>
                    <td style="border: 1.5px dotted black;text-align:center;border-right: none;"> 20 </td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;border-left: none;">Initiative</td>
                    <td style="border: 1.5px dotted black;text-align:center;"> A </td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;">Cooperation with others</td>
                    <td style="border: 1.5px dotted black;text-align:center;"> A </td>
                    <td style="text-align:right;border: 1.5px dotted black;padding-right:8px;">Problem solving</td>
                    <td style="border: 1.5px dotted black;text-align:center;border-right: none;"> 20 </td>
                </tr>
                <tr>
                    <td style="text-align:right;border: 1.5px dotted black;border-bottom: 1.5px solid black;padding-right:8px;border-left: none;">Homework completion</td>
                    <td style="border: 1.5px dotted black;border-bottom: 1.5px solid black;text-align:center;"> A </td>
                    <td style="text-align:right;border: 1.5px dotted black;border-bottom: 1.5px solid black;padding-right:8px;">Conflict resolution</td>
                    <td style="border: 1.5px dotted black;border-bottom: 1.5px solid black;text-align:center;"> A </td>
                    <td style="text-align:right;border: 1.5px dotted black;border-bottom: 1.5px solid black;padding-right:8px;">Goal setting to improve work</td>
                    <td style="border: 1.5px dotted black;border-bottom: 1.5px solid black;text-align:center;border-right: none;"> 20 </td>
                </tr>
                <tr>
                    <td colspan="6" style="border: 1.5px dotted black;padding-left: 20px;border-left: none;border-right: none;">Strengths/Weaknesses/Next Steps</td>
                </tr>
                <tr>
                    <td colspan="6" style="border: 1.5px dotted black;text-align:left;height:100px;padding-left: 20px;border-left: none;border-right: none;">tes</td>
                </tr>
                <!-- END LEARNING SKILLS -->

                <!-- SIGNATURE -->
                <tr>
                    <td style="text-align:left;height:150px;padding-left:20px;text-decoration:underline;" colspan="2">TTD</td>
                    <td style="text-align:center;height:150px;" colspan="2">TTD</td>
                    <td style="text-align:right;height:150px;padding-right:20px" colspan="2">TTD</td>
                </tr>
                <tr>
                <td style="text-align:left;padding-left:20px;text-decoration:underline;" colspan="2">Aris Cahyono</td>
                    <td style="text-align:center;text-decoration:underline;" colspan="2">Yuliana Harijanto, B.Eng (Hons)</td>
                    <td style="text-align:right;padding-right:20px;text-decoration:underline;" colspan="2">Satria Fedianto, S.Kom</td>
                </tr>
                <tr>
                    <th style="text-align:left;border-bottom: 3px solid black;width:33%;padding-left:20px;" colspan="2"><strong>Class Teacher's Signature</strong></td>
                    <th style="text-align:center;border-bottom: 3px solid black;width:33%;" colspan="2"><strong>Principal's Signature</strong></td>
                    <th style="text-align:right;border-bottom: 3px solid black;width:33%;padding-right:20px" colspan="2"><strong>Parent's Signature</strong></td>
                </tr>
                <!-- END SIGNATURE -->
            </table>
        </div>

        <div class="footer">
            <div style="width:10%;text-align:left;padding-left:20px">
                <p>M/D/Y</p>
            </div>
            <div class="mid" style="width:80%;margin-top:10px;">
            </div>
            <div style="width:10%;text-align:right;padding-right:20px;">
                <p>Page 1 of 2</p>
            </div>
        </div>
    <!-- END PAGE 1 -->
</div>

</body>
</html>
