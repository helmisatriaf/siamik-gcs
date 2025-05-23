<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Teacher_subject;
use App\Models\Grade_subject;
use App\Models\Subject_exam;
use App\Models\Exam;
use App\Models\Grade_exam;
use App\Models\Score;
use App\Models\Acar;
use App\Models\Comment;
use App\Models\Acar_comment;
use App\Models\Sooa_primary;
use App\Models\Sooa_secondary;
use App\Models\Report_card;
use App\Models\Report_card_status;
use App\Models\Scoring_status;
use App\Models\Acar_status;
use App\Models\Sooa_status;
use App\Models\Score_attendance;
use App\Models\Score_attendance_status;
use App\Models\Nursery_toddler;
use App\Models\Kindergarten;
use App\Models\Tcop;
use App\Models\Master_academic;
use App\Models\Mid_kindergarten;
use App\Models\Mid_report;
use App\Models\MonthlyActivity;
use App\Models\Student_Monthly_Activity;
use Exception;
use Illuminate\Http\Request;
class ScoringController extends Controller
{
    public function actionPostMajorPrimary(Request $request){
        try {
            // dd($request);
            
            $type = "major_subject_assessment";

            for ($i = 0; $i < count($request->student_id); $i++) {
                $final_score = round($request->final_score[$i]);
                $grade = $this->determineGrade($final_score);

                $matchingScoring = [
                    'student_id'         => $request->student_id[$i],
                    'grade_id'           => $request->grade_id,
                    'subject_id'         => $request->subject_id,
                    'subject_teacher_id' => $request->subject_teacher,
                    'semester'           => $request->semester,
                    'academic_year'      => session('academic_year'),
                ];

                $updateScoring = [
                    'comment'     => $request->comment[$i],
                    // 'grades'      => $grade,
                    // 'final_score' => $final_score,
                ];
            
                Acar::updateOrCreate($matchingScoring, $updateScoring);
            
                $matchingComment = [
                    'student_id'         => $request->student_id[$i],
                    'grade_id'           => $request->grade_id,
                    'subject_id'         => $request->subject_id,
                    'subject_teacher_id' => $request->subject_teacher,
                    'semester'           => session('semester'),
                    'academic_year'      => session('academic_year'),
                ];
            
                $updateComment = [
                    'type'    => $type,
                    'comment' => $request->comment[$i],
                ];
            
                Comment::updateOrCreate($matchingComment, $updateComment);
            }
            
            $primaryStatus = [
                'grade_id'      => $request->grade_id,
                'subject_id'    => $request->subject_id,
                'teacher_id'    => $request->subject_teacher,
                'status'        => 1,
                'semester'      => session('semester'),
                'academic_year' => session('academic_year'),
            ];

            $status = [
                'updated_at'    => now()
            ];

            Scoring_status::updateOrCreate($primaryStatus, $status);

            session()->flash('after_post_final_score');

            return redirect()->back()->with('role', session('role'));

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostMinorPrimary(Request $request){
        try {
            $type = "minor_subject_assessment";
            
            $subject = Subject::where('id', $request->subject_id)->value('name_subject');
            $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');

            if (strtolower($subject) == "religion islamic" || 
                strtolower($subject) == "religion catholic" || 
                strtolower($subject) == "religion christian" || 
                strtolower($subject) == "religion buddhism" || 
                strtolower($subject) == "religion hinduism" || 
                strtolower($subject) == "religion confucianism") {
                $subject_id = $getReligionId;
            } else {
                $subject_id = $request->subject_id;
            }

            for ($i = 0; $i < count($request->student_id); $i++) {
                $final_score = round($request->final_score[$i]);
                $grade = $this->determineGrade($final_score);

                $scoringKeys = [
                    'student_id'         => $request->student_id[$i],
                    'grade_id'           => $request->grade_id,
                    'subject_id'         => $subject_id,
                    'subject_teacher_id' => $request->subject_teacher,
                    'semester'           => $request->semester,
                    'academic_year'      => session('academic_year'),
                ];

                $scoringValues = [
                    'comment'     => $request->comment[$i],
                    // 'grades'      => $grade,
                    // 'final_score' => $final_score,
                ];

                Acar::updateOrCreate($scoringKeys, $scoringValues);

                $commentKeys = [
                    'student_id'         => $request->student_id[$i],
                    'grade_id'           => $request->grade_id,
                    'subject_id'         => $request->subject_id,
                    'subject_teacher_id' => $request->subject_teacher,
                    'semester'           => $request->semester,
                    'academic_year'      => session('academic_year'),
                ];

                $commentValues = [
                    'type'    => $type,
                    'comment' => $request->comment[$i],
                ];

                Comment::updateOrCreate($commentKeys, $commentValues);
            }
            

            $status = [
                'grade_id'      => $request->grade_id,
                'subject_id'    => $request->subject_id,
                'teacher_id'    => $request->subject_teacher,
                'status'        => 1,
                'semester'      => $request->semester,
                'academic_year' => session('academic_year'),
                'created_at'    => now()
            ];

            Scoring_status::create($status);

            session()->flash('after_post_final_score');

            return redirect()->back()->with('role', session('role'));

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostSecondary(Request $request){
        try {
            $type = "subject_assessment_secondary";

            $checkSubject = Subject::where('id', $request->subject_id)->value('name_subject');

            if (strtolower($checkSubject) == "chinese higher" || strtolower($checkSubject) == "chinese lower") {
                $subject = Subject::where('id', $request->subject_id)->value('name_subject');
                $getChineseId = Subject::where('name_subject', '=', 'chinese')->value('id');
    
                if (strtolower($subject) == "chinese lower" || 
                    strtolower($subject) == "chinese higher") {
                    $subject_id = $getChineseId;
                }
            }
            elseif (strtolower($checkSubject) == "religion islamic" || 
                    strtolower($checkSubject) == "religion catholic" || 
                    strtolower($checkSubject) == "religion christian" || 
                    strtolower($checkSubject) == "religion buddhism" || 
                    strtolower($checkSubject) == "religion hinduism" || 
                    strtolower($checkSubject) == "religion confucianism") {
                $subject = Subject::where('id', $request->subject_id)->value('name_subject');
                $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');
    
                if (strtolower($subject) == "religion islamic" || 
                    strtolower($subject) == "religion catholic" || 
                    strtolower($subject) == "religion christian" || 
                    strtolower($subject) == "religion buddhism" || 
                    strtolower($subject) == "religion hinduism" || 
                    strtolower($subject) == "religion confucianism") {
                    $subject_id = $getReligionId;
                }
            }else {
                $subject_id = $request->subject_id;
            }

             for ($i = 0; $i < count($request->student_id); $i++) {
                $final_score = round($request->final_score[$i]);
            
                // Tentukan grade berdasarkan nilai akhir
                $grade = $this->determineGrade($final_score);
                // Kondisi untuk menentukan apakah data sudah ada
                $scoringConditions = [
                    'student_id'         => $request->student_id[$i],
                    'grade_id'           => $request->grade_id,
                    'subject_id'         => $subject_id,
                    'subject_teacher_id' => $request->subject_teacher,
                    'semester'           => $request->semester,
                    'academic_year'      => session('academic_year'),
                ];
            
                // Data yang akan diperbarui atau ditambahkan untuk scoring
                $scoringData = [
                    // 'final_score' => $final_score,
                    // 'grades'      => $grade,
                    'comment'     => $request->comment[$i],
                ];
            
                // Kondisi untuk menentukan apakah data sudah ada untuk komentar
                $commentConditions = [
                    'student_id'         => $request->student_id[$i],
                    'grade_id'           => $request->grade_id,
                    'subject_id'         => $request->subject_id,
                    'subject_teacher_id' => $request->subject_teacher,
                    'semester'           => $request->semester,
                    'academic_year'      => session('academic_year'),
                ];
            
                // Data yang akan diperbarui atau ditambahkan untuk komentar
                $commentData = [
                    'type'    => $type,
                    'comment' => $request->comment[$i],
                ];
            
                // Gunakan updateOrCreate untuk tabel Acar
                Acar::updateOrCreate($scoringConditions, $scoringData);
            
                // Gunakan updateOrCreate untuk tabel Comment
                Comment::updateOrCreate($commentConditions, $commentData);
            }
            

            $status = [
                'grade_id'      => $request->grade_id,
                'subject_id'    => $request->subject_id,
                'teacher_id'    => $request->subject_teacher,
                'status'        => 1,
                'semester'      => $request->semester,
                'academic_year' => session('academic_year'),
                'created_at'    => now()
            ];

            Scoring_status::create($status);

            session()->flash('after_post_final_score');

            return redirect()->back()->with('role', session('role'));

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostKindergarten(Request $request){
        try {
            $subjectName = Subject::where('id', '=', $request->subject_id)->value('name_subject');

            // for($i=0; $i < count($request->student_id); $i++){
            //     $final_score = round($request->final_score[$i]);
    
            //     $grade = $this->determineGrade($final_score);

            //     if (strtolower($subjectName) == "english") {
            //         $scoring = [
            //             'student_id'        => $request->student_id[$i],
            //             'grade_id'          => $request->grade_id,
            //             'class_teacher_id'  => $request->subject_teacher,
            //             'semester'          => $request->semester,
            //             'english'           => $request->final_score[$i],
            //             'academic_year'     => session('academic_year'),
            //         ];
                    
            //         Kindergarten::updateOrCreate(
            //             ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
            //             $scoring
            //         );
            //     }
            //     elseif (strtolower($subjectName) == "mathematics") {
            //         $scoring = [
            //             'student_id'       => $request->student_id[$i],
            //             'grade_id'         => $request->grade_id,
            //             'class_teacher_id' => $request->subject_teacher,
            //             'semester'         => $request->semester,
            //             'mathematics'      => $request->final_score[$i],
            //             'academic_year'    => session('academic_year'),
            //         ];
                    
            //         Kindergarten::updateOrCreate(
            //             ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
            //             $scoring
            //         );
            //     }
            //     elseif (strtolower($subjectName) == "chinese") {
            //         $scoring = [
            //             'student_id'       => $request->student_id[$i],
            //             'grade_id'         => $request->grade_id,
            //             'class_teacher_id' => $request->subject_teacher,
            //             'semester'         => $request->semester,
            //             'chinese'          => $request->final_score[$i],
            //             'academic_year'    => session('academic_year'),
            //         ];
                    
            //         Kindergarten::updateOrCreate(
            //             ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
            //             $scoring
            //         );
            //     }
            //     elseif (strtolower($subjectName) == "science") {
            //         $scoring = [
            //             'student_id'       => $request->student_id[$i],
            //             'grade_id'         => $request->grade_id,
            //             'class_teacher_id' => $request->subject_teacher,
            //             'semester'         => $request->semester,
            //             'science'          => $request->final_score[$i],
            //             'academic_year'    => session('academic_year'),
            //         ];
                    
            //         // dd($scoring);
            //         Kindergarten::updateOrCreate(
            //             ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year', session('academic_year')],
            //             $scoring
            //         );
            //     }
            //     elseif (strtolower($subjectName) == "character building") {
            //         $scoring = [
            //             'student_id'         => $request->student_id[$i],
            //             'grade_id'           => $request->grade_id,
            //             'class_teacher_id'   => $request->subject_teacher,
            //             'semester'           => $request->semester,
            //             'character_building' => $request->final_score[$i],
            //             'academic_year'      => session('academic_year'),
            //         ];
                    
            //         Kindergarten::updateOrCreate(
            //             ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
            //             $scoring
            //         );
            //     }
            //     elseif (strtolower($subjectName) == "art and craft") {
            //         $scoring = [
            //             'student_id'       => $request->student_id[$i],
            //             'grade_id'         => $request->grade_id,
            //             'class_teacher_id' => $request->subject_teacher,
            //             'semester'         => $request->semester,
            //             'art_and_craft'    => $request->final_score[$i],
            //             'academic_year'    => session('academic_year'),
            //         ];
                    
            //         Kindergarten::updateOrCreate(
            //             ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
            //             $scoring
            //         );
            //     }
            //     elseif (strtolower($subjectName) == "it") {
            //         $scoring = [
            //             'student_id'       => $request->student_id[$i],
            //             'grade_id'         => $request->grade_id,
            //             'class_teacher_id' => $request->subject_teacher,
            //             'semester'         => $request->semester,
            //             'it'               => $request->final_score[$i],
            //             'academic_year'    => session('academic_year'),
            //         ];
                    
            //         Kindergarten::updateOrCreate(
            //             ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
            //             $scoring
            //         );
            //     }
            //     elseif (strtolower($subjectName) == "phonic") {
            //         $scoring = [
            //             'student_id'       => $request->student_id[$i],
            //             'grade_id'         => $request->grade_id,
            //             'class_teacher_id' => $request->subject_teacher,
            //             'semester'         => $request->semester,
            //             'phonic'           => $request->final_score[$i],
            //             'academic_year'    => session('academic_year'),
            //         ];
                    
            //         Kindergarten::updateOrCreate(
            //             ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
            //             $scoring
            //         );
            //     }
            // }

            $status = [
                'grade_id'      => $request->grade_id,
                'subject_id'    => $request->subject_id,
                'teacher_id'    => $request->subject_teacher,
                'status'        => 1,
                'semester'      => $request->semester,
                'academic_year' => session('academic_year'),
                'created_at'    => now()
            ];

            Scoring_status::create($status);

            session()->flash('after_post_final_score');

            return redirect()->back()->with('role', session('role'));

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostAcarPrimary(Request $request){
        try {
            for($i=0; $i < count($request->student_id); $i++){
                $final_score = $request->final_score[$i];
    
                $grade = $this->determineGrade($final_score);
                $type = "academic_assessment_report";
    
                $scoring = [
                    'student_id'       => $request->student_id[$i],
                    'grade_id'         => $request->grade_id,
                    'class_teacher_id' => $request->class_teacher,
                    'semester'         => $request->semester,
                    'academic_year'    => session('academic_year'),
                    'academic'         => $request->final_score[$i],
                    'grades_academic'  => $grade,
                ];
    
                $comment = [
                    'student_id' => $request->student_id[$i],
                    'grade_id'         => $request->grade_id,
                    'class_teacher_id' => $request->class_teacher,
                    'semester'         => $request->semester,
                    'academic_year'    => session('academic_year'),
                    'type'             => $type,
                    'comment'          => $request->comment[$i],
                ];
    
                Sooa_primary::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
                    $scoring
                );

                Acar_comment::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year'    => session('academic_year')],
                    $comment
                );
            }

            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->class_teacher,
                'status'           => 1,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now()
            ];

            Acar_status::create($status);

            session()->flash('after_post_final_score');

            return redirect()->back()->with('role', session('role'));

        } catch (Exception $err) {
            dd($err);
        }
       
    }

    public function actionPostAcarSecondary(Request $request){
        try {
            for($i=0; $i < count($request->student_id); $i++){
                // $final_score = $request->final_score[$i];
    
                // $grade = $this->determineGrade($final_score);
                $type = "academic_assessment_report";
    
                $scoring = [
                    'student_id'       => $request->student_id[$i],
                    'grade_id'         => $request->grade_id,
                    'class_teacher_id' => $request->class_teacher,
                    'semester'         => $request->semester,
                    'academic_year'    => session('academic_year'),
                    // 'academic'         => $request->final_score[$i],
                    // 'grades_academic'  => $grade,
                ];
    
                $comment = [
                    'student_id'       => $request->student_id[$i],
                    'grade_id'         => $request->grade_id,
                    'class_teacher_id' => $request->class_teacher,
                    'semester'         => $request->semester,
                    'academic_year'    => session('academic_year'),
                    'type'             => $type,
                    'comment'          => $request->comment[$i],
                ];
    
                Sooa_secondary::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
                    $scoring
                );

                Acar_comment::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
                    $comment
                );
            }

            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->class_teacher,
                'status'           => 1,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now()
            ];

            Acar_status::create($status);

            session()->flash('after_post_final_score');

            return redirect()->back()->with('role', session('role'));

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostSooaPrimary(Request $request){
        try {
            for($i=0; $i < count($request->student_id); $i++){
                $academic = Sooa_primary::where('sooa_primaries.grade_id', $request->grade_id)
                    // ->where('sooa_primaries.class_teacher_id', $request->class_teacher)
                    ->where('student_id', $request->student_id[$i])
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))
                    ->value('academic');

                $attendance = Sooa_primary::where('sooa_primaries.grade_id', $request->grade_id)
                    // ->where('sooa_primaries.class_teacher_id', $request->class_teacher)
                    ->where('student_id', $request->student_id[$i])
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))
                    ->value('attendance');

                    $eca_aver = ($request->choice[$i] + $request->language_and_art[$i])/2;

                    $final_score = ($academic * 0.6)  
                    + ($eca_aver * 0.1)
                    + ($request->behavior[$i] * 0.1) 
                    + ($attendance * 0.1) 
                    + ($request->participation[$i] * 0.1);

                    // dd($final_score);
                    $scoring = [
                        'student_id' => $request->student_id[$i],
                        'grade_id' => $request->grade_id,
                        'class_teacher_id' => $request->class_teacher,
                        'semester' => $request->semester,
                        'academic_year' => session('academic_year'),
                        'choice' => $request->choice[$i],
                        'grades_choice' => $this->determineGrade($request->choice[$i]),
                        'language_and_art' => $request->language_and_art[$i],
                        'grades_language_and_art' => $this->determineGrade($request->language_and_art[$i]),
                        'self_development' => $request->self_development[$i],
                        'grades_self_development' => $this->determineGrade($request->self_development[$i]),
                        'eca_aver' => $eca_aver,
                        'grades_eca_aver' => $this->determineGrade($eca_aver),
                        'behavior' => $request->behavior[$i],
                        'grades_behavior' => $this->determineGrade($request->behavior[$i]),
                        'participation' => $request->participation[$i],
                        'grades_participation' => $this->determineGrade($request->participation[$i]),
                        'final_score' => round($final_score),
                        'grades_final_score' => $this->determineGrade(round($final_score)),
                        'created_at' => now()
                    ];
                
                Sooa_primary::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year'),],
                    $scoring
                );
            }
    
            $allScores = Sooa_primary::where('grade_id', $request->grade_id)
                ->where('semester', $request->semester)
                ->where('academic_year', session('academic_year'))
                ->orderBy('final_score', 'desc')
                ->get();
    
            foreach ($allScores as $index => $student) {
                $student->ranking = $index + 1;
                $student->save();
            }
            
            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->class_teacher,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'status'           => 1,
                'created_at'       => now()
            ];
    
            Sooa_status::updateOrCreate(
                ['grade_id' => $request->grade_id, 'class_teacher_id' => $request->class_teacher, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
                $status
            );
    
            session()->flash('after_post_sooa');
    
            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);   
        }
    }

    public function actionPostSooaSecondary(Request $request){
        // dd($request);
        try {
            for($i=0; $i < count($request->student_id); $i++){
                $academic = Sooa_secondary::where('sooa_secondaries.grade_id', $request->grade_id)
                    ->where('sooa_secondaries.class_teacher_id', $request->class_teacher)
                    ->where('student_id', $request->student_id[$i])
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))
                    ->value('academic');

                $attendance = Sooa_secondary::where('sooa_secondaries.grade_id', $request->grade_id)
                    ->where('sooa_secondaries.class_teacher_id', $request->class_teacher)
                    ->where('student_id', $request->student_id[$i])
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))
                    ->value('attendance');

                    // dd($request);

                // if ($request->eca_1[$i] == 0 || $request->eca_1[$i] == 0 && $request->eca_2[$i] ==  0) {
                    $eca_aver = ($request->eca_1[$i] + $request->eca_2[$i])/2;

                    $final_score = ($academic * 0.6)  
                    + ($eca_aver * 0.1)
                    + ($request->behavior[$i] * 0.1) 
                    + ($attendance * 0.1) 
                    + ($request->participation[$i] * 0.1);
                
                    // dd($final_score);
                    $scoring = [
                        'student_id' => $request->student_id[$i],
                        'grade_id' => $request->grade_id,
                        'class_teacher_id' => $request->class_teacher,
                        'semester' => $request->semester,
                        'academic_year' => session('academic_year'),
                        'eca_1' => $request->eca_1[$i],
                        'grades_eca_1' => $this->determineGrade($request->eca_1[$i]),
                        'eca_2' => $request->eca_2[$i],
                        'grades_eca_2' => $this->determineGrade($request->eca_2[$i]),
                        'self_development' => $request->self_development[$i],
                        'grades_self_development' => $this->determineGrade($request->self_development[$i]),
                        'eca_aver' => $eca_aver,
                        'grades_eca_aver' => $this->determineGrade($eca_aver),
                        'behavior' => $request->behavior[$i],
                        'grades_behavior' => $this->determineGrade($request->behavior[$i]),
                        'participation' => $request->participation[$i],
                        'grades_participation' => $this->determineGrade($request->participation[$i]),
                        'final_score' => round($final_score),
                        'grades_final_score' => $this->determineGrade($final_score),
                        'created_at' => now(),
                    ];    
                // }
                // elseif ($request->eca_1[$i] != 0 && $request->eca_2[$i] == 0) {
                //     $final_score = ($academic 
                //     + $request->eca_1[$i]
                //     + $request->self_development[$i]  
                //     + $request->eca_aver[$i] 
                //     + $request->behavior[$i] 
                //     + $attendance 
                //     + $request->participation[$i]) / 7;
                
                //     $scoring = [
                //         'student_id' => $request->student_id[$i],
                //         'grade_id' => $request->grade_id,
                //         'class_teacher_id' => $request->class_teacher,
                //         'semester' => $request->semester,
                //         'academic_year' => session('academic_year'),
                //         'eca_1' => $request->eca_1[$i],
                //         'grades_eca_1' => $this->determineGrade($request->eca_1[$i]),
                //         'eca_2' => 0,
                //         'grades_eca_2' => "-",
                //         'self_development' => $request->self_development[$i],
                //         'grades_self_development' => $this->determineGrade($request->self_development[$i]),
                //         'eca_aver' => $request->eca_aver[$i],
                //         'grades_eca_aver' => $this->determineGrade($request->eca_aver[$i]),
                //         'behavior' => $request->behavior[$i],
                //         'grades_behavior' => $this->determineGrade($request->behavior[$i]),
                //         'participation' => $request->participation[$i],
                //         'grades_participation' => $this->determineGrade($request->participation[$i]),
                //         'final_score' => round($final_score),
                //         'grades_final_score' => $this->determineGrade($final_score),
                //         'created_at' => now()
                //     ];
                // }
                // else {
                //     $final_score = ($academic 
                //     + $request->eca_1[$i]
                //     + $request->eca_2[$i]
                //     + $request->self_development[$i]  
                //     + $request->eca_aver[$i] 
                //     + $request->behavior[$i] 
                //     + $attendance 
                //     + $request->participation[$i]) / 8;
                
                //     $scoring = [
                //         'student_id' => $request->student_id[$i],
                //         'grade_id' => $request->grade_id,
                //         'class_teacher_id' => $request->class_teacher,
                //         'semester' => $request->semester,
                //         'academic_year' => session('academic_year'),
                //         'eca_1' => $request->eca_1[$i],
                //         'grades_eca_1' => $this->determineGrade($request->eca_1[$i]),
                //         'eca_2' => $request->eca_2[$i],
                //         'grades_eca_2' => $this->determineGrade($request->eca_2[$i]),
                //         'self_development' => $request->self_development[$i],
                //         'grades_self_development' => $this->determineGrade($request->self_development[$i]),
                //         'eca_aver' => $request->eca_aver[$i],
                //         'grades_eca_aver' => $this->determineGrade($request->eca_aver[$i]),
                //         'behavior' => $request->behavior[$i],
                //         'grades_behavior' => $this->determineGrade($request->behavior[$i]),
                //         'participation' => $request->participation[$i],
                //         'grades_participation' => $this->determineGrade($request->participation[$i]),
                //         'final_score' => round($final_score),
                //         'grades_final_score' => $this->determineGrade($final_score),
                //         'created_at' => now()
                //     ];
                // }
                
                Sooa_secondary::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
                    $scoring
                );
            }
    
            $allScores = Sooa_secondary::where('grade_id', $request->grade_id)
                ->where('semester', $request->semester)
                ->where('academic_year', session('academic_year'))
                ->orderBy('final_score', 'desc')
                ->get();
    
            foreach ($allScores as $index => $student) {
                $student->ranking = $index + 1;
                $student->save();
            }
            
            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->class_teacher,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'status'           => 1,
                'created_at'       => now()
            ];
    
            Sooa_status::updateOrCreate(
                ['grade_id' => $request->grade_id, 'class_teacher_id' => $request->class_teacher, 'semester' => $request->semester, 'academic_year' => session('academic_year')],
                $status
            );
    
            session()->flash('after_post_sooa');
    
            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostScoreAttendance(Request $request){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance class teacher',
            ]);

            $getGrade = Grade::where('id', $request->grade_id)->value('name');

            for($i=0; $i < count($request->student_id); $i++){
                $where = [
                    'grade_id'      => $request->grade_id,
                    'teacher_id'    => $request->class_teacher,
                    'student_id'    => $request->student_id[$i],
                    'semester'      => $request->semester,
                    'academic_year' => session('academic_year'),
                ];
                
                $score = [
                    'score' => $request->final_score[$i],
                ];

                if (strtolower($getGrade) == "primary") {
                    $scoring = [
                        'attendance' => $request->final_score[$i],
                        'grades_attendance' => $this->determineGrade($request->final_score[$i]),
                    ];

                    Sooa_primary::updateOrCreate(
                        ['student_id' => $request->student_id[$i], 
                        'grade_id' => $request->grade_id,
                        'class_teacher_id' => $request->class_teacher, 
                        'semester' => $request->semester, 
                        'academic_year' => session('academic_year')], 
                        $scoring
                    );
                }
                elseif (strtolower($getGrade) == "secondary") {
                    $scoring = [
                        'attendance' => $request->final_score[$i],
                        'grades_attendance' => $this->determineGrade($request->final_score[$i]),
                    ];

                    Sooa_secondary::updateOrCreate(
                        ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id,
                        'semester' => $request->semester, 'class_teacher_id' => $request->class_teacher,
                        'academic_year' => session('academic_year')], 
                        $scoring
                    );
                }
                
                Score_attendance::updateOrcreate($where, $score);
            }

            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->class_teacher,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'status'           => 1,
            ];

            Score_attendance_status::create($status);
            session()->flash('after_post_attendance_score');

            return redirect()->back()->with('role', session('role'));

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostTcop(Request $request){
        // dd($request);
        try {
            for($i=0; $i < count($request->student_id); $i++){
                $data = [
                    'student_id'         => $request->student_id[$i],
                    'grade_id'           => $request->grade_id,
                    'class_teacher_id'   => $request->class_teacher,
                    'final_score'        => $request->final_score[$i],
                    'grades_final_score' => $request->grades_final_score[$i],
                    'promotion'          => 1,
                    'academic_year'      => session('academic_year'),
                    'created_at'         => now(),
                ];

                Tcop::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 
                    'class_teacher_id' => $request->class_teacher, 'academic_year' => session('academic_year')],
                    $data
                );
            }
    
            session()->flash('after_post_tcop');
    
            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);   
        }
    }

    public function actionPostMidReportCard(Request $request)
    {
        try {

            // dd($request);

            $monthlyActivity = MonthlyActivity::where('grades', '=', 'upper')->get();

            for($i=0; $i < count($request->student_id); $i++){

                $scoring = [
                    'critical_thinking' => $request->critical_thinking[$i],
                    'cognitive_skills'  => $request->cognitive_skills[$i],
                    'life_skills'       => $request->life_skills[$i],
                    'learning_skills'   => $request->learning_skills[$i],
                    'social_and_emotional_development' => $request->social_and_emotional_development[$i],
                    'created_at'       => now()
                ];
                
                Mid_report::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                    'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')], 
                    $scoring
                );
                
                foreach($monthlyActivity as $ma){
                    $name = strtolower(str_replace(' ', '_', trim($ma->name)));

                    $monthly = [
                        'score' => $request->$name[$i],
                        'monthly_activity_id' => $ma->id,
                        'grades' => $this->determineGrade($request->$name[$i]),
                    ];

                    Student_Monthly_Activity::updateOrCreate(
                        ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                        'monthly_activity_id' => $ma->id, 'academic_year' => session('academic_year')], 
                        $monthly
                    );
                }
            }

            if (session('semester') == 1) {
                $semester = 0.5;
            }
            elseif (session('semester') == 2) {
                $semester = 1.5;
            }
            
            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->teacher_id,
                'status'           => 1,
                'semester'         => $semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now()
            ];

            Report_card_status::create($status);

            session()->flash('after_post_mid_report_card');

            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostReportCard1(Request $request)
    {
        try {
            for($i=0; $i < count($request->student_id); $i++){
                $scoring = [
                    'student_id' => $request->student_id[$i],
                    'grade_id' => $request->grade_id,
                    'class_teacher_id' => $request->teacher_id,
                    'semester' => $request->semester,
                    'academic_year' => session('academic_year'),
                    'independent_work' => $request->independent_work[$i],
                    'initiative' => $request->initiative[$i],
                    'homework_completion' => $request->homework_completion[$i],
                    'use_of_information' => $request->use_of_information[$i],
                    'cooperation_with_other' => $request->cooperation_with_other[$i],
                    'conflict_resolution' => $request->conflict_resolution[$i],
                    'class_participation' => $request->class_participation[$i],
                    'problem_solving' => $request->problem_solving[$i],
                    'goal_setting_to_improve_work' => $request->goal_setting_to_improve_work[$i],
                    'strength_weakness_nextstep' => $request->strength_weakness_nextstep[$i],
                    // 'remarks' => $request->remarks[$i],
                    'created_at' => now()
                ];
                
                
                Report_card::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                    'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')],
                    $scoring
                );
            }
            
            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->teacher_id,
                'status'           => 1,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now()
            ];

            Report_card_status::create($status);

            session()->flash('after_post_report_card1');

            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostReportCard2(Request $request)
    {
        try {

            for($i=0; $i < count($request->student_id); $i++){

                $scoring = [
                    'student_id' => $request->student_id[$i],
                    'grade_id' => $request->grade_id,
                    'class_teacher_id' => $request->teacher_id,
                    'semester' => $request->semester,
                    'academic_year' => session('academic_year'),
                    'independent_work' => $request->independent_work[$i],
                    'initiative' => $request->initiative[$i],
                    'homework_completion' => $request->homework_completion[$i],
                    'use_of_information' => $request->use_of_information[$i],
                    'cooperation_with_other' => $request->cooperation_with_other[$i],
                    'conflict_resolution' => $request->conflict_resolution[$i],
                    'class_participation' => $request->class_participation[$i],
                    'problem_solving' => $request->problem_solving[$i],
                    'goal_setting_to_improve_work' => $request->goal_setting_to_improve_work[$i],
                    'strength_weakness_nextstep' => $request->strength_weakness_nextstep[$i],
                    'promotion_status' => $request->status[$i],
                    'created_at' => now()
                ];
                
                
                Report_card::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                    'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')],
                    $scoring
                );
            }
            
            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->teacher_id,
                'status'           => 1,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now()
            ];

            Report_card_status::create($status);

            session()->flash('after_post_report_card2');

            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostReportCardToddler(Request $request)
    {
        try {
            $monthlyActivity = MonthlyActivity::where('grades', '=', 'lower')->get();
            
            for($i=0; $i < count($request->student_id); $i++){

                $student_id = $request->student_id[$i];

                $scoring = [
                    'student_id' => $request->student_id[$i],
                    'grade_id' => $request->grade_id,
                    'class_teacher_id' => $request->teacher_id,
                    'songs' => $request->songs[$student_id],
                    'prayer' => $request->prayer[$student_id],
                    'colour' => $request->colour[$student_id],
                    'number' => $request->number[$student_id],
                    'object' => $request->object[$student_id],
                    'body_movement' => $request->body_movement[$student_id],
                    'colouring' => $request->colouring[$student_id],
                    'painting' => $request->painting[$student_id],
                    'chinese_songs' => $request->chinese_songs[$student_id],
                    'ability_to_recognize_the_objects' => $request->ability_to_recognize_the_objects[$student_id],
                    'able_to_own_up_to_mistakes' => $request->able_to_own_up_to_mistakes[$student_id],
                    'takes_care_of_personal_belongings_and_property' => $request->takes_care_of_personal_belongings_and_property[$student_id],
                    'demonstrates_importance_of_self_control' => $request->demonstrates_importance_of_self_control[$student_id],
                    'management_emotional_problem_solving' => $request->management_emotional_problem_solving[$student_id],
                    'remarks' => $request->remarks[$student_id],
                    'semester' => $request->semester,
                    'academic_year' => session('academic_year'),
                    'created_at' => now()
                ];
                
                if($request->semester == 0.5 || $request->semester == 1.5){
                    foreach($monthlyActivity as $ma){
                        $name = str_replace(' ', '_', trim($ma->name));
                        $monthly = [
                            'score' => $request->$name[$student_id],
                            'monthly_activity_id' => $ma->id,
                            'grades' => $this->gradeKindergarten($request->$name[$student_id]),
                        ];
                        
                        Student_Monthly_Activity::updateOrCreate(
                            ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                            'monthly_activity_id' => $ma->id, 'academic_year' => session('academic_year')], 
                            $monthly
                        );
                    }
                }
                
                Nursery_toddler::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                    'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')],
                    $scoring
                );
            }

            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->teacher_id,
                'status'           => 1,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now()
            ];

            Report_card_status::create($status);

            session()->flash('after_post_report_card_toddler');

            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostReportCardNursery(Request $request)
    {
        try {
            $semester = Master_academic::first()->value('now_semester');
            $monthlyActivity = MonthlyActivity::where('grades', '=', 'lower')->get();
            

            if ($semester == 1) {
                for($i=0; $i < count($request->student_id); $i++){
                
                    $student_id = $request->student_id[$i];
                    $scoring = [
                        'student_id' => $request->student_id[$i],
                        'grade_id' => $request->grade_id,
                        'class_teacher_id' => $request->teacher_id,
                        'semester' => $request->semester,
                        'academic_year' => session('academic_year'),
                        'songs' => $request->songs[$student_id],
                        'prayer' => $request->prayer[$student_id],
                        'colour' => $request->colour[$student_id],
                        'number' => $request->number[$student_id],
                        'object' => $request->object[$student_id],
                        'body_movement' => $request->body_movement[$student_id],
                        'colouring' => $request->colouring[$student_id],
                        'painting' => $request->painting[$student_id],
                        'chinese_songs' => $request->chinese_songs[$student_id],
                        'ability_to_recognize_the_objects' => $request->ability_to_recognize_the_objects[$student_id],
                        'able_to_own_up_to_mistakes' => $request->able_to_own_up_to_mistakes[$student_id],
                        'takes_care_of_personal_belongings_and_property' => $request->takes_care_of_personal_belongings_and_property[$student_id],
                        'demonstrates_importance_of_self_control' => $request->demonstrates_importance_of_self_control[$student_id],
                        'management_emotional_problem_solving' => $request->management_emotional_problem_solving[$student_id],
                        'remarks' => $request->remarks[$student_id],
                        'created_at' => now()
                    ];
                    
                    Nursery_toddler::updateOrCreate(
                        ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                        'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')],
                        $scoring
                    );

                    if($request->semester == 0.5){
                        foreach($monthlyActivity as $ma){
                            $name = str_replace(' ', '_', trim($ma->name));
                            $monthly = [
                                'score' => $request->$name[$student_id],
                                'monthly_activity_id' => $ma->id,
                                'grades' => $this->gradeKindergarten($request->$name[$student_id]),
                            ];
                            
                            Student_Monthly_Activity::updateOrCreate(
                                ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                                'monthly_activity_id' => $ma->id, 'academic_year' => session('academic_year')], 
                                $monthly
                            );
                        }
                    }
                }
            }
            elseif ($semester == 2) {
                for($i=0; $i < count($request->student_id); $i++){
                
                    $student_id = $request->student_id[$i];
                    $scoring = [
                        'student_id' => $request->student_id[$i],
                        'grade_id' => $request->grade_id,
                        'class_teacher_id' => $request->teacher_id,
                        'semester' => $request->semester,
                        'academic_year' => session('academic_year'),
                        'songs' => $request->songs[$student_id],
                        'prayer' => $request->prayer[$student_id],
                        'colour' => $request->colour[$student_id],
                        'number' => $request->number[$student_id],
                        'object' => $request->object[$student_id],
                        'body_movement' => $request->body_movement[$student_id],
                        'colouring' => $request->colouring[$student_id],
                        'painting' => $request->painting[$student_id],
                        'chinese_songs' => $request->chinese_songs[$student_id],
                        'ability_to_recognize_the_objects' => $request->ability_to_recognize_the_objects[$student_id],
                        'able_to_own_up_to_mistakes' => $request->able_to_own_up_to_mistakes[$student_id],
                        'takes_care_of_personal_belongings_and_property' => $request->takes_care_of_personal_belongings_and_property[$student_id],
                        'demonstrates_importance_of_self_control' => $request->demonstrates_importance_of_self_control[$student_id],
                        'management_emotional_problem_solving' => $request->management_emotional_problem_solving[$student_id],
                        'remarks' => $request->remarks[$student_id],
                        'promote' => 1,
                        'created_at' => now()
                    ];
                    
                    Nursery_toddler::updateOrCreate(
                        ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                        'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')],
                        $scoring
                    );

                    if($request->semester == 1.5){
                        foreach($monthlyActivity as $ma){
                            $name = str_replace(' ', '_', trim($ma->name));
                            $monthly = [
                                'score' => $request->$name[$student_id],
                                'monthly_activity_id' => $ma->id,
                                'grades' => $this->gradeKindergarten($request->$name[$student_id]),
                            ];
                            
                            Student_Monthly_Activity::updateOrCreate(
                                ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                                'monthly_activity_id' => $ma->id, 'academic_year' => session('academic_year')], 
                                $monthly
                            );
                        }
                    }
                }
            }

            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->teacher_id,
                'status'           => 1,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now(),
            ];

            Report_card_status::create($status);

            session()->flash('after_post_report_card_nursery');

            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostMidReportCardKindergarten(Request $request)
    {
        $monthlyActivity = MonthlyActivity::where('grades', '=', 'lower')->get();
        try {
            for($i=0; $i < count($request->student_id); $i++){
                
                $student_id = $request->student_id[$i];

                $scoring = [
                    'brain_gym' => $request->brain_gym[$student_id],
                    'cursive_writing' => $request->cursive_writing[$student_id],
                    'dictation' => $request->dictation[$student_id],
                    'english_language' => $request->english_language[$student_id],
                    'mandarin_language' => $request->mandarin_language[$student_id],
                    'writing_skill' => $request->writing_skill[$student_id],
                    'reading_skill' => $request->reading_skill[$student_id],
                    'phonic' => $request->phonic[$student_id],
                    'science' => $request->science[$student_id],
                    'art_and_craft' => $request->art_and_craft[$student_id],
                    'character_building' => $request->character_building[$student_id],
                    'physical_education' => $request->physical_education[$student_id],
                    'able_to_sit_quietly' => $request->able_to_sit_quietly[$student_id],
                    'willingness_to_listen' => $request->willingness_to_listen[$student_id],
                    'willingness_to_work' => $request->willingness_to_work[$student_id],
                    'willingness_to_sing' => $request->willingness_to_sing[$student_id],
                    'remarks' => $request->remarks[$student_id],
                    'created_at' => now(),
                ];

                foreach($monthlyActivity as $ma){
                    $name = str_replace(' ', '_', trim($ma->name));
                    $monthly = [
                        'score' => $request->$name[$student_id],
                        'monthly_activity_id' => $ma->id,
                        'grades' => $this->gradeKindergarten($request->$name[$student_id]),
                    ];
                    
                    Student_Monthly_Activity::updateOrCreate(
                        ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                        'monthly_activity_id' => $ma->id, 'academic_year' => session('academic_year')], 
                        $monthly
                    );
                }
                
                Mid_kindergarten::updateOrCreate(
                    ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => session('semester'),
                    'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')],
                    $scoring
                );
            }

            if (session('semester') == 1) {
                $semester = 0.5;
            }
            elseif (session('semester') == 2) {
                $semester = 1.5;
            }
            
            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->teacher_id,
                'status'           => 1,
                'semester'         => $semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now()
            ];

            Report_card_status::create($status);

            session()->flash('after_post_mid_report');

            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function actionPostReportCardKindergarten(Request $request)
    {
        try {       
            if ($request->semester == 1) {
                for($i=0; $i < count($request->student_id); $i++){
                
                    $student_id = $request->student_id[$i];
                    $scoring = [
                        'conduct' => $request->conduct[$student_id],
                        'remarks' => $request->remarks[$student_id],
                        'updated_at' => now()
                    ];
                    
                    Kindergarten::updateOrCreate(
                        ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                        'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')],
                        $scoring
                    );
                }
            }
            elseif ($request->semester == 2) {
                for($i=0; $i < count($request->student_id); $i++){
                
                    $student_id = $request->student_id[$i];
                    $scoring = [
                        'conduct' => $request->conduct[$student_id],
                        'remarks' => $request->remarks[$student_id],
                        'promote' => 1,
                        'updated_at' => now()
                    ];
                    
                    Kindergarten::updateOrCreate(
                        ['student_id' => $request->student_id[$i], 'grade_id' => $request->grade_id, 'semester' => $request->semester,
                        'class_teacher_id' => $request->teacher_id, 'academic_year' => session('academic_year')],
                        $scoring
                    );
                }
            }

            $status = [
                'grade_id'         => $request->grade_id,
                'class_teacher_id' => $request->teacher_id,
                'status'           => 1,
                'semester'         => $request->semester,
                'academic_year'    => session('academic_year'),
                'created_at'       => now()
            ];

            Report_card_status::create($status);

            session()->flash('after_post_report_card_kindergarten');

            return redirect()->back()->with('role', session('role'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    private function determineGrade($finalScore)
    {
        if ($finalScore >= 95 && $finalScore <= 100) {
            return 'A+';
        } elseif ($finalScore >= 85 && $finalScore <= 94) {
            return 'A';
        } elseif ($finalScore >= 75 && $finalScore <= 84) {
            return 'B';
        } elseif ($finalScore >= 65 && $finalScore <= 74) {
            return 'C';
        } elseif ($finalScore >= 45 && $finalScore <= 64) {
            return 'D';
        } else {
            return 'R';
        }
    }

    private function gradeKindergarten($score){
        switch($score){
            case(1) :
                return 'Excellent';
                break;
            case(2) :
                return 'Good';
                break;
            case(3) :
                return 'Satisfactory';
                break;
            case(4) :
                return 'Need Improvement';
                break;
        }
    }
}
