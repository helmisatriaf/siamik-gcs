<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Subject_exam;
use App\Models\Score;
use App\Models\Acar;
use App\Models\Teacher;
use App\Models\Teacher_subject;
use App\Models\Major_subject;
use App\Models\Minor_subject;
use App\Models\Supplementary_subject;
use App\Models\Type_exam;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentAnswer;
use App\Models\QuestionStatus;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Grade_exam;

use App\Models\Sooa_primary;
use App\Models\Sooa_secondary;
use App\Models\Chinese_higher;
use App\Models\Chinese_lower;
use App\Models\Teacher_grade;
use App\Models\Kindergarten;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoreController extends Controller
{

    public function score($id)
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'scorings',
            'child' => 'scorings',
            ]);

            $model = Exam::where('id', $id)->value('model');
            $dataExam = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                ->join('students', 'student_exams.student_id', '=', 'students.id')
                ->join('scores', function($join) {
                        $join->on('student_exams.student_id', '=', 'scores.student_id')
                            ->on('exams.id', '=', 'scores.exam_id');
                    })
                ->where('exams.id', $id, 'exams.is_active')
                ->where('students.is_active', true)
                ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                'students.id as student_id', 'students.name as student_name',
                'scores.score as score', 'scores.file_name as file_name')
                ->orderBy('student_name', 'asc')
                ->get();

            // SCORE MULTIPLE CHOICE DAN ESSAY
            if($model == "mce"){
                $exam = Exam::with(['question'])->where('id', $id)->first();
                $questions = Question::where('exam_id', $id)
                    ->with(['answer' => function($query){
                        $query->where('is_correct', TRUE);
                    }])
                    ->get();

                $students = Student::join('student_exams', 'students.id', '=', 'student_exams.student_id')
                    ->where('student_exams.exam_id', $id)
                    ->with(['studentAnswer' => function ($query) use ($id) {
                        $query->where('exam_id', $id)
                            ->leftJoin('answers', function ($join) {
                                $join->on('answers.id', '=', 'student_answers.answer_id')
                                     ->whereNotNull('student_answers.answer_id'); // Hanya join jika answer_id tidak null
                            });
                    }])
                    ->select('students.*')
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                
                $questions->each(function ($question) use ($students) {
                    $question->students = $students;
                });

                $totalMC = $exam->question->where('type', '=', 'mc')->count();
                $totalEssay = $exam->question->where('type', '=', 'essay')->count();
                $pointEssay = (100 - $totalMC)/$totalEssay;


                // dd($questions);
                return view('components.exam.data-exam-mce-score', [
                    'data' => $questions,
                    'exam' => $exam,
                    'pointEssay' => $pointEssay,
                    'dataExam' => $dataExam,
                ]);
            }

            else if($model == "mc"){
                $exam = Exam::with(['question'])->where('id', $id)->first();
                $questions = Question::where('exam_id', $id)
                    ->with(['answer' => function($query){
                        $query->where('is_correct', TRUE);
                    }])
                    ->get();

                $students = Student::join('student_exams', 'students.id', '=', 'student_exams.student_id')
                    ->where('student_exams.exam_id', $id)
                    ->with(['studentAnswer' => function ($query) use ($id) {
                        $query->where('exam_id', $id)
                            ->leftJoin('answers', function ($join) {
                                $join->on('answers.id', '=', 'student_answers.answer_id')
                                     ->whereNotNull('student_answers.answer_id'); // Hanya join jika answer_id tidak null
                            });
                    }])
                    ->select('students.*')
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                
                $questions->each(function ($question) use ($students) {
                    $question->students = $students;
                });

                $totalMC = $exam->question->where('type', '=', 'mc')->count();

                // dd($questions);
                return view('components.exam.data-exam-mc-score', [
                    'data' => $questions,
                    'exam' => $exam,
                    'dataExam' => $dataExam,
                ]);
            }

            else if($model == "essay"){
                $exam = Exam::with(['question'])->where('id', $id)->first();
                $questions = Question::where('exam_id', $id)->get();

                $students = Student::join('student_exams', 'students.id', '=', 'student_exams.student_id')
                    ->where('student_exams.exam_id', $id)
                    ->with(['studentAnswer' => function ($query) use ($id) {
                        $query->where('exam_id', $id)
                            ->leftJoin('answers', function ($join) {
                                $join->on('answers.id', '=', 'student_answers.answer_id')
                                     ->whereNotNull('student_answers.answer_id'); // Hanya join jika answer_id tidak null
                            });
                    }])
                    ->select('students.*')
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                
                $questions->each(function ($question) use ($students) {
                    $question->students = $students;
                });

                $totalEssay = $exam->question->count();
                $pointEssay = 100/$totalEssay;

                return view('components.exam.data-exam-essay-score', [
                    'data' => $questions,
                    'exam' => $exam,
                    'pointEssay' => $pointEssay,
                    'dataExam' => $dataExam,
                ]);
            }

            // INPUT SCORE MANUAL 
            else{
                $checkSubject = Subject_exam::where('subject_exams.exam_id', '=', $id)->value('subject_id');
                $subject = Subject::where('id', $checkSubject)->value('name_subject');

                if (strtolower($subject) == "religion islamic") {
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                            $join->on('student_exams.student_id', '=', 'scores.student_id')
                                ->on('exams.id', '=', 'scores.exam_id');
                        })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->where('students.religion', '=', 'islam')
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score' , 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();
                }
                elseif (strtolower($subject) == "religion catholic") {
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                            $join->on('student_exams.student_id', '=', 'scores.student_id')
                                ->on('exams.id', '=', 'scores.exam_id');
                        })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->where('students.religion', '=', 'Catholic Christianity')
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score' , 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();
                }
                elseif (strtolower($subject) == "religion christian") {
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                            $join->on('student_exams.student_id', '=', 'scores.student_id')
                                ->on('exams.id', '=', 'scores.exam_id');
                        })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->where('students.religion', '=', 'Protestant Christianity')
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score' , 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();
                }
                elseif (strtolower($subject) == "religion buddhism") {
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                            $join->on('student_exams.student_id', '=', 'scores.student_id')
                                ->on('exams.id', '=', 'scores.exam_id');
                        })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->where('students.religion', '=', 'Buddhism')
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score' , 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();
                }
                elseif (strtolower($subject) == "religion hinduism") {
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                            $join->on('student_exams.student_id', '=', 'scores.student_id')
                                ->on('exams.id', '=', 'scores.exam_id');
                        })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->where('students.religion', '=', 'Hinduism')
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score' , 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();
                }
                elseif (strtolower($subject) == "religion confucianism") {
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->where('students.is_active', true)
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                            $join->on('student_exams.student_id', '=', 'scores.student_id')
                                ->on('exams.id', '=', 'scores.exam_id');
                        })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->where('students.religion', '=', 'Confucianism')
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score' , 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();
                }
                elseif (strtolower($subject) == "chinese lower") {
                    $chineseLowerStudent = Chinese_lower::pluck('student_id')->toArray();
                    
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                            $join->on('student_exams.student_id', '=', 'scores.student_id')
                                ->on('exams.id', '=', 'scores.exam_id');
                        })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->whereIn('students.id', $chineseLowerStudent)
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score' , 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();
                }
                elseif (strtolower($subject) == "chinese higher") {
                    $chineseHigherStudent = Chinese_higher::pluck('student_id')->toArray();
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                            $join->on('student_exams.student_id', '=', 'scores.student_id')
                                ->on('exams.id', '=', 'scores.exam_id');
                        })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->whereIn('students.id', $chineseHigherStudent)
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score' , 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();

                }
                else{
                    $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                        ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                        ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                        ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                        ->join('student_exams', 'exams.id', '=', 'student_exams.exam_id')
                        ->join('students', 'student_exams.student_id', '=', 'students.id')
                        ->join('scores', function($join) {
                                $join->on('student_exams.student_id', '=', 'scores.student_id')
                                    ->on('exams.id', '=', 'scores.exam_id');
                            })
                        ->where('exams.id', $id, 'exams.is_active')
                        ->where('students.is_active', true)
                        ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                        'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                        'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                        'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                        'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                        'students.id as student_id', 'students.name as student_name',
                        'scores.score as score', 'scores.file_name as file_name')
                        ->orderBy('student_name', 'asc')
                        ->get();
                }

                return view('components.exam.data-exam-score')->with('data', $data);
            }

        } catch (Exception $err) {
            return dd($err);
        }
    }
  
    public function actionUpdateScore(Request $request)
    {
        try {
            session()->flash('page', $page = (object)[
                'page' => 'exams',
                'child' => 'database exams',
            ]);

            $students = $request->student_id;
            $scores = $request->score;

            // Update scores for each student
            for ($i = 0; $i < count($students); $i++) {
                $post = [
                    'score' => $scores[$i],
                    'updated_at' => now(),
                ];

                Score::where('student_id', $students[$i])
                    ->where('exam_id', $request->exam_id)
                    ->update($post);
            }

            $userId = session('id_user');
            $gradeId = $request->grade_id;
            $subjectId = $request->subject_id;

            if($gradeId == 3  || $gradeId == 4){
                $exercise       = Type_exam::where('name', 'exercise')->value('id');
                $quiz           = Type_exam::where('name', 'quiz')->value('id');
                $participation  = Type_exam::where('name', 'participation')->value('id');
                $semester       = session('semester');
                $academic_year  = session('academic_year');
                $subjectName    = Subject::where('id', '=', $request->subject_id)->value('name_subject');
                $classTeacher   = Teacher_grade::where('grade_id', $gradeId)->value('teacher_id');
                // dd($subjectName);
                if($subjectName == "Character Building"){
                    $subjectName = "character_building";
                }
                if($subjectName == "Art and Craft"){
                    $subjectName = "art_and_craft";
                }
                
                if(session('role') == 'teacher'){
                    $teacherId = Teacher::where('user_id', $userId)->value('id');
                }
                else{
                    $teacherId = Teacher_subject::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->value('teacher_id');
                }

                $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $exercise, $quiz) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    });
                }])
                ->where('grades.id', $gradeId)
                ->withCount([
                    'exam as total_exercise' => function ($query) use ($subjectId, $exercise, $semester, $academic_year) {
                        $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                            $subQuery->where('subject_id', $subjectId);
                        })
                        ->where('type_exam', $exercise)
                        ->where('semester', $semester)
                        ->where('exams.academic_year', $academic_year);
                    },
                    'exam as total_quiz' => function ($query) use ($subjectId, $quiz, $semester, $academic_year) {
                        $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                            $subQuery->where('subject_id', $subjectId);
                        })
                        ->where('type_exam', $quiz)
                        ->where('semester', $semester)
                        ->where('exams.academic_year', $academic_year);
                    },
                    'exam as total_participation' => function ($query) use ($subjectId, $participation, $semester, $academic_year) {
                        $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                            $subQuery->where('subject_id', $subjectId);
                        })
                        ->where('type_exam', $participation)
                        ->where('semester', $semester)
                        ->where('exams.academic_year', $academic_year);
                    },
                ])
                ->first(); 

                $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('grades.id', $gradeId)
                    ->where('students.is_active', true)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->get();

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($participation, $exercise, $quiz) {

                    $student = $scores->first();
                    $exercise       = $scores->where('type_exam', $exercise)->pluck('score');
                    $quiz           = $scores->where('type_exam', $quiz)->pluck('score');
                    $participation  = $scores->where('type_exam', $participation)->pluck('score');
                    
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'scores' => $scores->map(function ($score) {
                            return [
                                'exam_id' => $score->exam_id,
                                'type_exam' => $score->type_exam,
                                'score' => $score->score,
                            ];
                        })->all(),
                        
                        'percent_exercise'      => round(($exercise->avg() * 0.3), 2),
                        'percent_quiz'          => round(($quiz->avg() * 0.4), 2),
                        'percent_participation' => round(($participation->avg() * 0.3), 2),
    
                        'total_score'       => (round(($exercise->avg() * 0.3), 2) + round(($quiz->avg() * 0.4), 2) + round(($participation->avg() * 0.3), 2)),
                        'total_score_mark'  => round(round(($exercise->avg() * 0.3), 2) + round(($quiz->avg() * 0.4), 2) + round(($participation->avg() * 0.3), 2)),
                        'grade'             => $this->determineGrade(round(round(($exercise->avg() * 0.3), 2) + round(($quiz->avg() * 0.4), 2) + round(($participation->avg() * 0.3), 2))),
                    ];
                })->values()->all();

                foreach($scoresByStudent as $score){
                    $scoring = [
                        strtolower($subjectName) => $score['total_score_mark'],
                    ];
                    Kindergarten::updateOrCreate(
                        ['student_id' => $score['student_id'], 'grade_id' => $gradeId, 'class_teacher_id' => $classTeacher, 'semester' => session('semester'), 'academic_year' => session('academic_year')],
                        $scoring
                    );
                }
            }
            else{
                // check apakah major subject
                $majorSubject = Major_subject::select('subject_id')->get();
                $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);
    
                if(session('role') == 'teacher'){
                    $teacherId = Teacher::where('user_id', $userId)->value('id');
                }
                else{
                    $teacherId = Teacher_subject::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->value('teacher_id');
                }
    
                $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->first();
    
                $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->first();
    
                $subject = Subject::where('id', $subjectId)
                    ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                    ->first();
    
                // check apakah major subject
                $majorSubject = Major_subject::select('subject_id')->get();
                $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);
    
                $homework = Type_exam::where('name', '=', 'homework')->value('id');
                $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                $participation = Type_exam::where('name', '=', 'participation')->value('id');
                $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
                $finalExam = Type_exam::where('name', '=', 'final exam')->value('id'); 
                $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final assessment', 'final exam'])
                    ->pluck('id')
                    ->toArray();
    
                $semester       = session('semester');
                $academic_year  = session('academic_year');
    
                if (strtolower($subject->subject_name) == "religion islamic") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'islam')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                }
                elseif (strtolower($subject->subject_name) == "religion catholic") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                        ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->leftJoin('subject_exams', function($join){
                            $join->on('subject_exams.exam_id', '=', 'exams.id');
                        })
                        ->leftJoin('scores', function ($join) {
                            $join->on('scores.student_id', '=', 'students.id')
                                ->on('scores.exam_id', '=', 'exams.id');
                        })
                        ->select(
                            'students.id as student_id',
                            'students.name as student_name',
                            'exams.id as exam_id',
                            'exams.type_exam as type_exam',
                            'scores.score as score',
                        )
                        ->where('students.religion', '=', 'catholic christianity')
                        ->where('students.is_active', true)
                        ->where('grades.id', $gradeId)
                        ->where('subject_exams.subject_id', $subjectId)
                        ->where('exams.semester', $semester)
                        ->where('exams.academic_year', $academic_year)
                        ->where('exams.teacher_id', $teacherId)
                        ->orderBy('students.name', 'asc')
                        ->get();
                    
                }
                elseif (strtolower($subject->subject_name) == "religion christian") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'protestant christianity')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject->subject_name) == "religion buddhism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'buddhism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject->subject_name) == "religion hinduism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'hinduism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject->subject_name) == "religion confucianism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'confucianism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                else{
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                }
    
                // Perhitungan ACAR PRIMARY
                if ($gradeId <= 10){
                    // dd($request);
                    // Perhitungan ACAR Primary Major Subject
                    if ($isMajorSubject) {
                        $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalExam) {
                            $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                $subQuery->where('subject_id', $subjectId);
                            });
                        }])
                        ->where('grades.id', $gradeId)
                        ->withCount([
                            'exam as total_homework' => function ($query) use ($subjectId, $homework, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $homework)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_exercise' => function ($query) use ($subjectId, $exercise, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $exercise)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_quiz' => function ($query) use ($subjectId, $quiz, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $quiz)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_final_exam' => function ($query) use ($subjectId, $finalExam, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $finalExam)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_participation' => function ($query) use ($subjectId, $participation, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $participation)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                        ])
                        ->first();
                    
                        $type = "major_subject_assessment";
    
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
                            $finalExam = Type_exam::where('name', '=', 'final exam')->value('id');
    
                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $quizScores         = $scores->where('type_exam', $quiz)->pluck('score');
                            $finalExamScores    = $scores->where('type_exam', $finalExam)->pluck('score');
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework'      => round($homeworkScores->avg()),
                                'avg_exercise'      => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_quiz'          => round($quizScores->avg()),
    
                                'percent_homework'      => round($homeworkScores->avg() * 0.1),
                                'percent_exercise'      => round($exerciseScores->avg() * 0.15),
                                'percent_participation' => round($participationScore->avg() * 0.05),
                                'h+e+p'                 => (round($homeworkScores->avg() * 0.1)) + round(($exerciseScores->avg() * 0.15)) + round(($participationScore->avg() * 0.05)),
                            
                                'percent_quiz' => round($quizScores->avg() * 0.3),
                                'percent_fe'   => round($finalExamScores->avg() * 0.4),
                                'total_score'  => round(($homeworkScores->avg() * 0.1) + ($exerciseScores->avg() * 0.15) + ($participationScore->avg() * 0.05) + ($quizScores->avg() * 0.3) + ($finalExamScores->avg() * 0.4)),
                                
                                'comment' => '',
                            ];
                        })->values()->all();
    
                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
    
                    } 
                    // Perhitungan ACAR Primary Minor & Supplementary Subject
                    else {
                        $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester) {
                            $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                $subQuery->where('subject_id', $subjectId);
                            });
                        }])
                        ->where('grades.id', $gradeId)
                        ->withCount([
                            'exam as total_homework' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $homework)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_exercise' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $exercise)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_quiz' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $quiz)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_final_exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->whereIn('type_exam', $finalAssessment)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_participation' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $participation)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                        ])
                        ->first();
    
                        $type = "minor_subject_assessment";
    
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                            
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $quiz = Type_exam::where('name', '=', 'quiz')->value('id'); 
                            $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final exam', 'final assessment'])
                                ->pluck('id')
                                ->toArray();
    
                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $quizScores         = $scores->where('type_exam', $quiz)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalAssessment)->pluck('score');
    
                            $homeworkAvg       = round($homeworkScores->avg()) ?: 0;
                            $exerciseAvg       = round($exerciseScores->avg()) ?: 0;
                            $participationAvg  = round($participationScore->avg()) ?: 0;
                            $quizAvg          = round($quizScores->avg()) ?: 0;
                            $finalExamAvg     = round($finalExamScores->avg()) ?: 0;
    
    
                            $final_score = round(($homeworkAvg * 0.2) + ($exerciseAvg * 0.35) + ($participationAvg * 0.10) + ($finalExamAvg * 0.35));
                            $grade = $this->determineGrade($final_score);
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework' => round($homeworkScores->avg()),
                                'avg_exercise' => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_fe' => round($finalExamScores->avg()),
                                
                                'percent_homework' => round($homeworkScores->avg() * 0.2),
                                'percent_exercise' => round($exerciseScores->avg() * 0.35),
                                'percent_participation' => round($participationScore->avg() * 0.1, 2),
                                'percent_fe' => round($finalExamScores->avg() * 0.35, 2),
                                'total_score' => round(($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + round($finalExamScores->avg() * 0.35),
    
                                'grades' => $grade,
                                'comment' => '',
                            ];
                        })->values()->all();
    
                        
                        // dd($scoresByStudent);
    
                        $subject = Subject::where('id', $request->subject_id)->value('name_subject');
                        $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');
            
                        if (strtolower($subject) == "religion islamic" || 
                            strtolower($subject) == "religion catholic" || 
                            strtolower($subject) == "religion christian" || 
                            strtolower($subject) == "religion buddhism" || 
                            strtolower($subject) == "religion hinduism" || 
                            strtolower($subject) == "religion confucianism") {
                            $subjectId = $getReligionId;
                        } else {
                            $subjectId = $request->subject_id;
                        }
    
                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                }
                // Perhitungan ACAR SECONDARY
                else{
                    $checkSubject = Subject::where('id', $request->subject_id)->value('name_subject');
                    
                    if (strtolower($checkSubject) == "religion islamic" || 
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
                            $subjectId = $getReligionId;
                        }
                    }else {
                        $subjectId = $request->subject_id;
                    }
                    // Perhitungan ACAR Secondary Major Subject
                    if (strtolower($checkSubject) !== 'science' &&
                        strtolower($checkSubject) !== 'english' &&
                        strtolower($checkSubject) !== 'mathematics' &&
                        strtolower($checkSubject) !== 'chinese higher' &&
                        strtolower($checkSubject) !== 'chinese lower')
                    {                
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores){
                            
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final exam', 'final assessment'])
                                ->pluck('id')
                                ->toArray();
                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalAssessment)->pluck('score');
    
                            $homeworkAvg       = round($homeworkScores->avg()) ?: 0;
                            $exerciseAvg       = round($exerciseScores->avg()) ?: 0;
                            $participationAvg  = round($participationScore->avg()) ?: 0;
                            $finalExamAvg     = round($finalExamScores->avg()) ?: 0;
    
    
                            $final_score = round(($homeworkAvg * 0.2) + ($exerciseAvg * 0.35) + ($participationAvg * 0.10) + ($finalExamAvg * 0.35));
                            $grade = $this->determineGrade($final_score);
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework' => round($homeworkScores->avg()),
                                'avg_exercise' => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_fe' => round($finalExamScores->avg()),
                                
                                'percent_homework' => round($homeworkScores->avg() * 0.2),
                                'percent_exercise' => round($exerciseScores->avg() * 0.35),
                                'percent_participation' => round($participationScore->avg() * 0.1),
                                'percent_fe' => round($finalExamScores->avg() * 0.35),
                                
                                'total_score' => round(($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + round($finalExamScores->avg() * 0.35),
    
                                'grades' => $grade,
                                'comment' => '',
                            ];
                        })->values()->all();
    
                        
    
                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                    // Perhitungan ACAR Secondary Major Subject
                    else{
                        $tasks = Type_exam::whereIn('name', ['homework', 'small project', 'presentation', 'exercice', 'Exercise'])
                            ->pluck('id')
                            ->toArray();
                        $mid = Type_exam::whereIn('name', ['quiz', 'practical exam', 'project', 'exam'])
                            ->pluck('id')
                            ->toArray();
                        $finalExam = Type_exam::whereIn('name', ['written tes', 'big project', 'final assessment', 'final exam'])
                            ->pluck('id')
                            ->toArray();
    
                        // CHINESE LOWER & HIGHER
                        if(strtolower($checkSubject) == 'chinese lower'){
                            $chineseLowerStudent = Chinese_lower::where('grade_id', $request->grade_id)->pluck('student_id')->toArray();
                            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                                ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                                ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                                ->leftJoin('subject_exams', function($join){
                                    $join->on('subject_exams.exam_id', '=', 'exams.id');
                                })
                                ->leftJoin('scores', function ($join) {
                                    $join->on('scores.student_id', '=', 'students.id')
                                        ->on('scores.exam_id', '=', 'exams.id');
                                })
                                ->select(
                                    'students.id as student_id',
                                    'students.name as student_name',
                                    'exams.id as exam_id',
                                    'exams.type_exam as type_exam',
                                    'scores.score as score',
                                )
                                ->whereIn('students.id', $chineseLowerStudent)
                                ->where('grades.id', $gradeId)
                                ->where('subject_exams.subject_id', $subjectId)
                                ->where('exams.semester', $semester)
                                ->where('exams.academic_year', $academic_year)
                                ->where('exams.teacher_id', $teacherId)
                                ->orderBy('students.name', 'asc')
                                ->get();
                        }
                        elseif(strtolower($checkSubject) == 'chinese higher'){
                            $chineseHigherStudent = Chinese_higher::where('grade_id', $request->grade_id)->pluck('student_id')->toArray();
                            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                                ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                                ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                                ->leftJoin('subject_exams', function($join){
                                    $join->on('subject_exams.exam_id', '=', 'exams.id');
                                })
                                ->leftJoin('scores', function ($join) {
                                    $join->on('scores.student_id', '=', 'students.id')
                                        ->on('scores.exam_id', '=', 'exams.id');
                                })
                                ->select(
                                    'students.id as student_id',
                                    'students.name as student_name',
                                    'exams.id as exam_id',
                                    'exams.type_exam as type_exam',
                                    'scores.score as score',
                                )
                                ->whereIn('students.id', $chineseHigherStudent)
                                ->where('grades.id', $gradeId)
                                ->where('subject_exams.subject_id', $subjectId)
                                ->where('exams.semester', $semester)
                                ->where('exams.academic_year', $academic_year)
                                ->where('exams.teacher_id', $teacherId)
                                ->orderBy('students.name', 'asc')
                                ->get();
                        }
    
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($tasks, $mid, $finalExam) {
                            $student            = $scores->first();
                            $tasks              = $scores->whereIn('type_exam', $tasks)->pluck('score');
                            $mid                = $scores->whereIn('type_exam', $mid)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalExam)->pluck('score');
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_tasks' => round($tasks->avg()),
                                'avg_mid'   => round($mid->avg()),
                                'avg_fe'    => round($finalExamScores->avg()),
            
                                'percent_tasks' => round($tasks->avg() * 0.25),
                                'percent_mid'  => round($mid->avg() * 0.35),
                                'percent_fe'    => round($finalExamScores->avg() * 0.4),
                                'total_score'   => (round(($tasks->avg() * 0.25)) +  round(($mid->avg() * 0.35)) + round(($finalExamScores->avg() * 0.4))),
                                'comment' => '',
                            ];
                        })->values()->all();
    
    
                        
                        if (strtolower($checkSubject) == "chinese higher" || strtolower($checkSubject) == "chinese lower") {
                            $subject = Subject::where('id', $request->subject_id)->value('name_subject');
                            $getChineseId = Subject::where('name_subject', '=', 'chinese')->value('id');
                
                            if (strtolower($subject) == "chinese lower" || 
                                strtolower($subject) == "chinese higher") {
                                $subjectId = $getChineseId;
                            }
                        }
    
                        foreach($scoresByStudent as $student){
                            // dd($student['total_score']);
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                }
    
    
                // Perhitungan SOOA Academic
                $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                    ->where('acars.grade_id', $gradeId)
                    ->where('acars.semester', $semester)
                    ->where('acars.academic_year', $academic_year)
                    ->get();
                
                $sooaByStudent = $results->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
                    $majorSubject = Major_subject::pluck('subject_id')->toArray();
                    $minorSubject = Minor_subject::pluck('subject_id')->toArray();
                    $supplementarySubject = Supplementary_subject::pluck('subject_id')->toArray();
                    $majorSubjectsScores = $scores->whereIn('subject_id', $majorSubject)->pluck('final_score');
                    $minorSubjectsScores = $scores->whereIn('subject_id', $minorSubject)->pluck('final_score');
                    $supplementarySubjectsScores = $scores->whereIn('subject_id', $supplementarySubject)->pluck('final_score');
                    
                    $sortedScores = $scores->sortBy('subject_id');
                    // dd($majorSubjectsScores);
    
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'scores' => $sortedScores->map(function ($score) {
                            return [
                                'subject_id' => $score->subject_id,
                                'final_score' => $score->final_score,
                                'grades' => $score->grades,
                            ];
                        })->all(),
                        'percent_majorSubjects' => round($majorSubjectsScores->avg() * 0.7),
                        'percent_minorSubjects' => round($minorSubjectsScores->avg() * 0.2),
                        'percent_supplementarySubjects' => round($supplementarySubjectsScores->avg() * 0.1),
                        'total_score' => round((($majorSubjectsScores->avg() * 0.7) + ($minorSubjectsScores->avg() * 0.2) + $supplementarySubjectsScores->avg() * 0.1)),
                        'comment' => '',
                    ];
                })->values()->all();
                
                // dd($sooaByStudent);
    
                foreach($sooaByStudent as $sooa){
                    $matchingScoring = [
                        'student_id'         => $sooa['student_id'],
                        'grade_id'           => $gradeId,
                        'class_teacher_id'   => $classTeacher->teacher_id,
                        'semester'           => session('semester'),
                        'academic_year'      => session('academic_year'),
                    ];
                
                    // Data untuk diupdate atau disimpan
                    $updateScoring = [
                        'academic'           => $sooa['total_score'],
                        'grades_academic'    => $this->determineGrade($sooa['total_score']),
                    ];
                
                    // Gunakan updateOrCreate untuk tabel Acar
                    if($gradeId == 11 || $gradeId == 12 || $gradeId == 13){
                        Sooa_secondary::updateOrCreate($matchingScoring, $updateScoring);
                    }
                    else {
                        Sooa_primary::updateOrCreate($matchingScoring, $updateScoring);
                    }
                }
            }

            session()->flash('after_update_score');
            
            if (session('role') == 'superadmin') {
                return redirect('/superadmin/exams');
            } elseif (session('role') == 'admin') {
                return redirect('/admin/exams');
            } elseif (session('role') == 'teacher') {
                return redirect('/teacher/dashboard/exam/teacher');
            }
        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }

    public function remedial(Request $request)
    {
        try{
            $classTeacher = Teacher_grade::where('grade_id', $request->grade_id)
                ->where('academic_year', session('academic_year'))
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            if($request->subject_id == 39 || $request->subject_id == 38){
                $subjectId = 1;
                $comment = Acar::where('student_id', $request->student_id)
                    ->where('grade_id', $request->grade_id)
                    ->where('subject_id', $subjectId)
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))
                    ->value('comment');

                $subjectTeacherId = Acar::where('student_id', $request->student_id)
                    ->where('grade_id', $request->grade_id)
                    ->where('subject_id', $subjectId)
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))
                    ->value('subject_teacher_id');

                $matchingScoring = [
                    'student_id'         => $request->student_id,
                    'subject_id'         => $subjectId,
                    'grade_id'           => $request->grade_id,
                    'semester'           => session('semester'),
                    'academic_year'      => session('academic_year'),
                    'subject_teacher_id' => $subjectTeacherId,
                ];
            }else{
                if($request->subject_id == 34 || $request->subject_id == 35 || $request->subject_id == 36 || $request->subject_id == 37){
                    $subjectId = 20;
                    $comment = Acar::where('student_id', $request->student_id)
                        // ->where('subject_teacher_id', $request->subject_teacher_id)
                        ->where('grade_id', $request->grade_id)
                        ->where('subject_id', $subjectId)
                        ->where('semester', session('semester'))
                        ->where('academic_year', session('academic_year'))
                        ->value('comment');
                    $subjectTeacherId = Acar::where('student_id', $request->student_id)
                        ->where('grade_id', $request->grade_id)
                        ->where('subject_id', $subjectId)
                        ->where('semester', session('semester'))
                        ->where('academic_year', session('academic_year'))
                        ->value('subject_teacher_id');
    
                    $matchingScoring = [
                        'student_id'         => $request->student_id,
                        'subject_id'         => $subjectId,
                        'grade_id'           => $request->grade_id,
                        'semester'           => session('semester'),
                        'academic_year'      => session('academic_year'),
                        'subject_teacher_id' => $subjectTeacherId,
                    ];
                }
                else{
                    $comment = Acar::where('student_id', $request->student_id)
                        // ->where('subject_teacher_id', $request->subject_teacher_id)
                        ->where('grade_id', $request->grade_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('semester', session('semester'))
                        ->where('academic_year', session('academic_year'))
                        ->value('comment');

                    $subjectTeacherId = Acar::where('student_id', $request->student_id)
                        ->where('grade_id', $request->grade_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('semester', session('semester'))
                        ->where('academic_year', session('academic_year'))
                        ->value('subject_teacher_id');
    
                    $matchingScoring = [
                        'student_id'         => $request->student_id,
                        'subject_id'         => $request->subject_id,
                        'grade_id'           => $request->grade_id,
                        'semester'           => session('semester'),
                        'academic_year'      => session('academic_year'),
                        'subject_teacher_id' => $subjectTeacherId,
                    ];
                }
            }

            $updateScoring = [
                'grades'      => $this->determineGrade($request->remedial),
                'final_score' => $request->remedial,
                'comment'     => $comment,
            ];

            // dd($matchingScoring);
    
            Acar::updateOrCreate($matchingScoring, $updateScoring);
    
            $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->where('acars.student_id', $request->student_id)
                ->where('acars.grade_id', $request->grade_id)
                ->where('acars.semester', session('semester'))
                ->where('acars.academic_year', session('academic_year'))
                ->get();
            
            $sooaByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
                $majorSubject = Major_subject::pluck('subject_id')->toArray();
                $minorSubject = Minor_subject::pluck('subject_id')->toArray();
                $supplementarySubject = Supplementary_subject::pluck('subject_id')->toArray();
                $majorSubjectsScores = $scores->whereIn('subject_id', $majorSubject)->pluck('final_score');
                $minorSubjectsScores = $scores->whereIn('subject_id', $minorSubject)->pluck('final_score');
                $supplementarySubjectsScores = $scores->whereIn('subject_id', $supplementarySubject)->pluck('final_score');
                $sortedScores = $scores->sortBy('subject_id');

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $sortedScores->map(function ($score) {
                        return [
                            'subject_id' => $score->subject_id,
                            'final_score' => $score->final_score,
                            'grades' => $score->grades,
                        ];
                    })->all(),
                    'percent_majorSubjects' => round($majorSubjectsScores->avg() * 0.7),
                    'percent_minorSubjects' => round($minorSubjectsScores->avg() * 0.2),
                    'percent_supplementarySubjects' => round($supplementarySubjectsScores->avg() * 0.1),
                    'total_score' => round((($majorSubjectsScores->avg() * 0.7) + ($minorSubjectsScores->avg() * 0.2) + $supplementarySubjectsScores->avg() * 0.1)),
                    'comment' => '',
                ];
            })->values()->all();

            foreach($sooaByStudent as $sooa){
                $matchingScoring = [
                    'student_id'         => $sooa['student_id'],
                    'grade_id'           => $request->grade_id,
                    // 'class_teacher_id'   => $classTeacher->teacher_id,
                    'semester'           => session('semester'),
                    'academic_year'      => session('academic_year'),
                ];
            
                // Data untuk diupdate atau disimpan
                $updateScoring = [
                    'academic'           => $sooa['total_score'],
                    'grades_academic'    => $this->determineGrade($sooa['total_score']),
                ];
            
                if($request->grade_id == 11 || $request->grade_id == 12 || $request->grade_id == 13){
                    Sooa_secondary::updateOrCreate($matchingScoring, $updateScoring);
                    $sooa_secondary = Sooa_secondary::where('student_id', $request->student_id)
                        ->where('grade_id', $request->grade_id)
                        ->where('semester', session('semester'))
                        ->where('academic_year', session('academic_year'))
                        // ->where('class_teacher_id', $classTeacher->teacher_id)
                        ->first();

                    $updateFinalScore = ($sooa_secondary->academic *0.6)
                    + ($sooa_secondary->eca_aver * 0.1)
                    + ($sooa_secondary->behavior * 0.1)
                    + ($sooa_secondary->attendance * 0.1)
                    + ($sooa_secondary->participation * 0.1);

                    $scoring = [
                        'final_score' => $updateFinalScore,
                        'grades_final_score' =>  $this->determineGrade($updateFinalScore),
                    ];

                    // dd($scoring);
                    Sooa_secondary::updateOrCreate($matchingScoring, $scoring);
                }
                else {
                    Sooa_primary::updateOrCreate($matchingScoring, $updateScoring);

                    $sooa_primary = Sooa_primary::where('student_id', $request->student_id)
                        ->where('grade_id', $request->grade_id)
                        ->where('semester', session('semester'))
                        ->where('academic_year', session('academic_year'))
                        // ->where('class_teacher_id', $classTeacher->teacher_id)
                        ->first();

                    // dd($sooa_primary);

                    $updateFinalScore = ($sooa_primary->academic *0.6)
                    + ($sooa_primary->eca_aver * 0.1)
                    + ($sooa_primary->behavior * 0.1)
                    + ($sooa_primary->attendance * 0.1)
                    + ($sooa_primary->participation * 0.1);

                    $scoring = [
                        'final_score' => $updateFinalScore,
                        'grades_final_score' =>  $this->determineGrade($updateFinalScore),
                    ];

                    // dd($scoring);

                    Sooa_primary::updateOrCreate($matchingScoring, $scoring);
                }
            }

            session()->flash('remedial_posted');
            return redirect()->back();
        }
        catch (Exception $err) {
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

    public function actionAnswerQuestionStudent(Request $request){
        
        try{
            $studentId = Student::where('user_id', session('id_user'))->value('id');
            $model     = Exam::where('id', session('exam_id'))->value('model');

            switch ($model) {
                // MENYIMPAN JAWABAN QUESTION COMBINE
                case "mce" :
                    foreach ($request['answers'] as $answer) {
                        // Multiple Choice
                        if($answer['answer_id'] !== null){
                            $correction = Answer::where('id', $answer['answer_id'])->value('is_correct');
                            $point = $correction == TRUE ? 1 : 0;
                        }

                        // Essay
                        elseif($answer['essay_answer']){
                            $point = NULL;
                        }   

                        StudentAnswer::create(
                            [
                                'student_id' => $studentId,
                                'exam_id' => session('exam_id'),
                                'question_id' => $answer['question_id'],
                                'answer_id' => $answer['answer_id'] !== null ? $answer['answer_id'] : null,
                                'essay_answer' => $answer['essay_answer'] !== null ? $answer['essay_answer'] : null,
                                'point' => $point,
                            ],
                        );
                    }
                    break;

                // MENYIMPAN JAWABAN QUESTION MULTIPLE CHOICE
                case "mc" :
                    foreach ($request['answers'] as $answer) {
                        // Multiple Choice
                        if($answer['answer_id'] !== null){
                            $correction = Answer::where('id', $answer['answer_id'])->value('is_correct');
                            $point = $correction == TRUE ? 1 : 0;
                        }  

                        StudentAnswer::create(
                            [
                                'student_id' => $studentId,
                                'exam_id' => session('exam_id'),
                                'question_id' => $answer['question_id'],
                                'answer_id' => $answer['answer_id'] !== null ? $answer['answer_id'] : null,
                                'essay_answer' => $answer['essay_answer'] !== null ? $answer['essay_answer'] : null,
                                'point' => $point,
                            ],
                        );

                        $totalPoint = StudentAnswer::where('exam_id', session('exam_id'))
                            ->where('student_id', $studentId)
                            ->sum('point');

                        $question = Question::where('exam_id', session('exam_id'))
                            ->count();
                        
                        $finalScore = ($question > 0) ? ($totalPoint / $question) * 100 : 0;

                        Score::where('student_id', $studentId)
                            ->where('exam_id', session('exam_id'))
                            ->update([
                                'score' => $finalScore,
                            ]);

                        $this->updateScoreMC(session('exam_id'));
                    }
                    break;
                case "essay" :
                    foreach ($request['answers'] as $answer) {
                        $point = NULL;
                        StudentAnswer::create(
                            [
                                'student_id' => $studentId,
                                'exam_id' => session('exam_id'),
                                'question_id' => $answer['question_id'],
                                'answer_id' => $answer['answer_id'] !== null ? $answer['answer_id'] : null,
                                'essay_answer' => $answer['essay_answer'] !== null ? $answer['essay_answer'] : null,
                                'point' => $point,
                            ],
                        );
                    }
                    break;
            }

            QuestionStatus::create([
                'student_id' => $studentId,
                'exam_id' => session('exam_id'),
                'semester' => session('semester'),
                'academic_year' => session('academic_year'),
            ]);

            return response()->json(['message' => 'Jawaban berhasil disimpan!']);
        }
        catch (Exception $err) {
            // Log error ke file Laravel untuk debugging
            Log::error("Gagal menyimpan jawaban: " . $err->getMessage());
    
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan jawaban',
                'details' => $err->getMessage()
            ], 500);
        }
    }

    public function scoreMCE(Request $request){
        try{

            // dd($request);
            foreach($request->student as $studentId => $student){
                foreach($student as $questionId => $point){ 
                    // UPDATE SCORE ESSAY
                    StudentAnswer::where('exam_id', $request->exam_id)
                        ->where('student_id', $studentId)
                        ->where('question_id', $questionId)
                        ->update(['point' => $point['point']]);
                }
                
                // UPDATE SCORE DI TABEL SCORES
                $totalPoint = StudentAnswer::where('exam_id', $request->exam_id)
                    ->where('student_id', $studentId)
                    ->sum('point');

                Score::where('student_id', $studentId)
                    ->where('exam_id', $request->exam_id)
                    ->update([
                        'score' => $totalPoint,
                    ]);
            }

            // UPDATE ACAR & SOOA
            $this->updateScoreMC($request->exam_id);

            session()->flash('success_update_score_essay');
            return redirect()->back();
        }
        catch(Exception $err){
            dd($err);
        }
    }

    private function updateScoreMC($id)
    {
        try {
            $exam = Exam::find($id);
            
            // Calculate and store final scores for Academic Assessment Report
                $userId = session('id_user');
                $gradeId = Grade_exam::where('exam_id', $id)->value('grade_id');
                $subjectId = Subject_exam::where('exam_id', $id)->value('subject_id');

                // check apakah major subject
                $majorSubject = Major_subject::select('subject_id')->get();
                $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);

                if(session('role') == 'teacher'){
                    $teacherId = Teacher::where('user_id', $userId)->value('id');
                }
                else{
                    $teacherId = Teacher_subject::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->value('teacher_id');
                }

                $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->first();

                $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                    ->where('academic_year', session('academic_year'))
                    ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                    ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                    ->first();

                $subject = Subject::where('id', $subjectId)
                    ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                    ->first();

                // check apakah major subject
                $majorSubject = Major_subject::select('subject_id')->get();
                $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);

                $homework = Type_exam::where('name', '=', 'homework')->value('id');
                $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                $participation = Type_exam::where('name', '=', 'participation')->value('id');
                $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
                $finalExam = Type_exam::where('name', '=', 'final exam')->value('id'); 
                $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final assessment', 'final exam'])
                    ->pluck('id')
                    ->toArray();

                $semester       = session('semester');
                $academic_year  = session('academic_year');

                if (strtolower($subject->subject_name) == "religion islamic") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'islam')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                }
                elseif (strtolower($subject->subject_name) == "religion catholic") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                        ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                        ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                        ->leftJoin('subject_exams', function($join){
                            $join->on('subject_exams.exam_id', '=', 'exams.id');
                        })
                        ->leftJoin('scores', function ($join) {
                            $join->on('scores.student_id', '=', 'students.id')
                                ->on('scores.exam_id', '=', 'exams.id');
                        })
                        ->select(
                            'students.id as student_id',
                            'students.name as student_name',
                            'exams.id as exam_id',
                            'exams.type_exam as type_exam',
                            'scores.score as score',
                        )
                        ->where('students.religion', '=', 'catholic christianity')
                        ->where('students.is_active', true)
                        ->where('grades.id', $gradeId)
                        ->where('subject_exams.subject_id', $subjectId)
                        ->where('exams.semester', $semester)
                        ->where('exams.academic_year', $academic_year)
                        ->where('exams.teacher_id', $teacherId)
                        ->orderBy('students.name', 'asc')
                        ->get();
                    
                }
                elseif (strtolower($subject->subject_name) == "religion christian") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'protestant christianity')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject->subject_name) == "religion buddhism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'buddhism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject->subject_name) == "religion hinduism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'hinduism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                elseif (strtolower($subject->subject_name) == "religion confucianism") {
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.religion', '=', 'confucianism')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                
                }
                else{
                    $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                    ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->leftJoin('subject_exams', function($join){
                        $join->on('subject_exams.exam_id', '=', 'exams.id');
                    })
                    ->leftJoin('scores', function ($join) {
                        $join->on('scores.student_id', '=', 'students.id')
                            ->on('scores.exam_id', '=', 'exams.id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                    )
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
                    ->orderBy('students.name', 'asc')
                    ->get();
                }
                // dd($request);

                // Perhitungan ACAR PRIMARY
                if ($gradeId <= 10){
                    // dd($request);
                    // Perhitungan ACAR Primary Major Subject
                    if ($isMajorSubject) {
                        $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalExam) {
                            $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                $subQuery->where('subject_id', $subjectId);
                            });
                        }])
                        ->where('grades.id', $gradeId)
                        ->withCount([
                            'exam as total_homework' => function ($query) use ($subjectId, $homework, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $homework)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_exercise' => function ($query) use ($subjectId, $exercise, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $exercise)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_quiz' => function ($query) use ($subjectId, $quiz, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $quiz)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_final_exam' => function ($query) use ($subjectId, $finalExam, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $finalExam)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_participation' => function ($query) use ($subjectId, $participation, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $participation)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                        ])
                        ->first();
                    
                        $type = "major_subject_assessment";

                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
                            $finalExam = Type_exam::where('name', '=', 'final exam')->value('id');

                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $quizScores         = $scores->where('type_exam', $quiz)->pluck('score');
                            $finalExamScores    = $scores->where('type_exam', $finalExam)->pluck('score');
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework'      => round($homeworkScores->avg()),
                                'avg_exercise'      => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_quiz'          => round($quizScores->avg()),

                                'percent_homework'      => round($homeworkScores->avg() * 0.1, 2),
                                'percent_exercise'      => round($exerciseScores->avg() * 0.15, 2),
                                'percent_participation' => round($participationScore->avg() * 0.05, 2),
                                'h+e+p'                 => (round($homeworkScores->avg() * 0.1, 2) + round($exerciseScores->avg() * 0.15, 2) + round($participationScore->avg() * 0.05, 2)),
                            
                                'percent_quiz' => $quizScores->avg() * 0.3,
                                'percent_fe'   => $finalExamScores->avg() * 0.4,
                                'total_score'  => round(($homeworkScores->avg() * 0.1) + ($exerciseScores->avg() * 0.15) + ($participationScore->avg() * 0.05) + ($quizScores->avg() * 0.3) + ($finalExamScores->avg() * 0.4)),
                                
                                'comment' => '',
                            ];
                        })->values()->all();

                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }

                    } 
                    // Perhitungan ACAR Primary Minor & Supplementary Subject
                    else {
                        $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester) {
                            $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                $subQuery->where('subject_id', $subjectId);
                            });
                        }])
                        ->where('grades.id', $gradeId)
                        ->withCount([
                            'exam as total_homework' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $homework)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_exercise' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $exercise)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_quiz' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $quiz)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_final_exam' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->whereIn('type_exam', $finalAssessment)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                            'exam as total_participation' => function ($query) use ($subjectId, $homework, $exercise, $participation, $quiz, $finalAssessment, $semester, $academic_year) {
                                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                                    $subQuery->where('subject_id', $subjectId);
                                })
                                ->where('type_exam', $participation)
                                ->where('semester', $semester)
                                ->where('exams.academic_year', $academic_year);
                            },
                        ])
                        ->first();

                        $type = "minor_subject_assessment";

                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                            
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $quiz = Type_exam::where('name', '=', 'quiz')->value('id'); 
                            $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final exam', 'final assessment'])
                                ->pluck('id')
                                ->toArray();

                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $quizScores         = $scores->where('type_exam', $quiz)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalAssessment)->pluck('score');

                            $homeworkAvg       = round($homeworkScores->avg()) ?: 0;
                            $exerciseAvg       = round($exerciseScores->avg()) ?: 0;
                            $participationAvg  = round($participationScore->avg()) ?: 0;
                            $quizAvg          = round($quizScores->avg()) ?: 0;
                            $finalExamAvg     = round($finalExamScores->avg()) ?: 0;


                            $final_score = round(($homeworkAvg * 0.2) + ($exerciseAvg * 0.35) + ($participationAvg * 0.10) + ($finalExamAvg * 0.35));
                            $grade = $this->determineGrade($final_score);
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework' => round($homeworkScores->avg()),
                                'avg_exercise' => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_fe' => round($finalExamScores->avg()),
                                
                                'percent_homework' => round($homeworkScores->avg() * 0.2),
                                'percent_exercise' => round($exerciseScores->avg() * 0.35),
                                'percent_participation' => round($participationScore->avg() * 0.1, 2),
                                'percent_fe' => round($finalExamScores->avg() * 0.35, 2),
                                'total_score' => round(($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + round($finalExamScores->avg() * 0.35),

                                'grades' => $grade,
                                'comment' => '',
                            ];
                        })->values()->all();

                        $subject = Subject::where('id', $subjectId)->value('name_subject');
                        $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');
            
                        if (strtolower($subject) == "religion islamic" || 
                            strtolower($subject) == "religion catholic" || 
                            strtolower($subject) == "religion christian" || 
                            strtolower($subject) == "religion buddhism" || 
                            strtolower($subject) == "religion hinduism" || 
                            strtolower($subject) == "religion confucianism") {
                            $subjectId = $getReligionId;
                        } else {
                            $subjectId = $subjectId;
                        }

                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                }
                // Perhitungan ACAR SECONDARY
                else{
                    
                    $checkSubject = Subject::where('id', $subjectId)->value('name_subject');
                    // dd($checkSubject);
                    if (strtolower($checkSubject) == "religion islamic" || 
                            strtolower($checkSubject) == "religion catholic" || 
                            strtolower($checkSubject) == "religion christian" || 
                            strtolower($checkSubject) == "religion buddhism" || 
                            strtolower($checkSubject) == "religion hinduism" || 
                            strtolower($checkSubject) == "religion confucianism") {
                        $subject = Subject::where('id', $subjectId)->value('name_subject');
                        $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');
            
                        if (strtolower($subject) == "religion islamic" || 
                            strtolower($subject) == "religion catholic" || 
                            strtolower($subject) == "religion christian" || 
                            strtolower($subject) == "religion buddhism" || 
                            strtolower($subject) == "religion hinduism" || 
                            strtolower($subject) == "religion confucianism") {
                            $subjectId = $getReligionId;
                        }
                    }else {
                        $subjectId = $subjectId;
                    }
                    
                    // Perhitungan ACAR Secondary Major Subject
                    if (strtolower($checkSubject) !== 'science' &&
                        strtolower($checkSubject) !== 'english' &&
                        strtolower($checkSubject) !== 'mathematics' &&
                        strtolower($checkSubject) !== 'chinese higher' &&
                        strtolower($checkSubject) !== 'chinese lower')
                    {                
                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores){
                            
                            $homework = Type_exam::where('name', '=', 'homework')->value('id');
                            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                            $participation = Type_exam::where('name', '=', 'participation')->value('id');
                            $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final exam', 'final assessment'])
                                ->pluck('id')
                                ->toArray();
                            $student            = $scores->first();
                            $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                            $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                            $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalAssessment)->pluck('score');

                            $homeworkAvg       = round($homeworkScores->avg()) ?: 0;
                            $exerciseAvg       = round($exerciseScores->avg()) ?: 0;
                            $participationAvg  = round($participationScore->avg()) ?: 0;
                            $finalExamAvg     = round($finalExamScores->avg()) ?: 0;


                            $final_score = round(($homeworkAvg * 0.2) + ($exerciseAvg * 0.35) + ($participationAvg * 0.10) + ($finalExamAvg * 0.35));
                            $grade = $this->determineGrade($final_score);
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_homework' => round($homeworkScores->avg()),
                                'avg_exercise' => round($exerciseScores->avg()),
                                'avg_participation' => round($participationScore->avg()),
                                'avg_fe' => round($finalExamScores->avg()),
                                
                                'percent_homework' => round($homeworkScores->avg() * 0.2),
                                'percent_exercise' => round($exerciseScores->avg() * 0.35),
                                'percent_participation' => round($participationScore->avg() * 0.1),
                                'percent_fe' => round($finalExamScores->avg() * 0.35),
                                
                                'total_score' => round(($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + round($finalExamScores->avg() * 0.35),

                                'grades' => $grade,
                                'comment' => '',
                            ];
                        })->values()->all();

                        foreach($scoresByStudent as $student){
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            // Gunakan updateOrCreate untuk tabel Acar
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                    // Perhitungan ACAR Secondary Major Subject
                    else{
                        $tasks = Type_exam::whereIn('name', ['homework', 'small project', 'presentation', 'exercice', 'Exercise'])
                            ->pluck('id')
                            ->toArray();
                        $mid = Type_exam::whereIn('name', ['quiz', 'practical exam', 'project', 'exam'])
                            ->pluck('id')
                            ->toArray();
                        $finalExam = Type_exam::whereIn('name', ['written tes', 'big project', 'final assessment', 'final exam'])
                            ->pluck('id')
                            ->toArray();

                        // dd($subjectId);

                        // CHINESE LOWER & HIGHER
                        if(strtolower($checkSubject) == 'chinese lower'){
                            $chineseLowerStudent = Chinese_lower::where('grade_id', $gradeId)->pluck('student_id')->toArray();
                            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                                ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                                ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                                ->leftJoin('subject_exams', function($join){
                                    $join->on('subject_exams.exam_id', '=', 'exams.id');
                                })
                                ->leftJoin('scores', function ($join) {
                                    $join->on('scores.student_id', '=', 'students.id')
                                        ->on('scores.exam_id', '=', 'exams.id');
                                })
                                ->select(
                                    'students.id as student_id',
                                    'students.name as student_name',
                                    'exams.id as exam_id',
                                    'exams.type_exam as type_exam',
                                    'scores.score as score',
                                )
                                ->whereIn('students.id', $chineseLowerStudent)
                                ->where('grades.id', $gradeId)
                                ->where('subject_exams.subject_id', $subjectId)
                                ->where('exams.semester', $semester)
                                ->where('exams.academic_year', $academic_year)
                                ->where('exams.teacher_id', $teacherId)
                                ->orderBy('students.name', 'asc')
                                ->get();
                        }
                        elseif(strtolower($checkSubject) == 'chinese higher'){
                            $chineseHigherStudent = Chinese_higher::where('grade_id', $gradeId)->pluck('student_id')->toArray();
                            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                                ->join('grade_exams', 'grade_exams.grade_id', '=', 'grades.id')
                                ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                                ->leftJoin('subject_exams', function($join){
                                    $join->on('subject_exams.exam_id', '=', 'exams.id');
                                })
                                ->leftJoin('scores', function ($join) {
                                    $join->on('scores.student_id', '=', 'students.id')
                                        ->on('scores.exam_id', '=', 'exams.id');
                                })
                                ->select(
                                    'students.id as student_id',
                                    'students.name as student_name',
                                    'exams.id as exam_id',
                                    'exams.type_exam as type_exam',
                                    'scores.score as score',
                                )
                                ->whereIn('students.id', $chineseHigherStudent)
                                ->where('grades.id', $gradeId)
                                ->where('subject_exams.subject_id', $subjectId)
                                ->where('exams.semester', $semester)
                                ->where('exams.academic_year', $academic_year)
                                ->where('exams.teacher_id', $teacherId)
                                ->orderBy('students.name', 'asc')
                                ->get();
                        }

                        $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($tasks, $mid, $finalExam) {
                            $student            = $scores->first();
                            $tasks              = $scores->whereIn('type_exam', $tasks)->pluck('score');
                            $mid                = $scores->whereIn('type_exam', $mid)->pluck('score');
                            $finalExamScores    = $scores->whereIn('type_exam', $finalExam)->pluck('score');
                            
                            return [
                                'student_id' => $student->student_id,
                                'student_name' => $student->student_name,
                                'scores' => $scores->map(function ($score) {
                                    return [
                                        'exam_id' => $score->exam_id,
                                        'type_exam' => $score->type_exam,
                                        'score' => $score->score,
                                    ];
                                })->all(),
                                'avg_tasks' => round($tasks->avg()),
                                'avg_mid'   => round($mid->avg()),
                                'avg_fe'    => round($finalExamScores->avg()),
            
                                'percent_tasks' => round($tasks->avg() * 0.25),
                                'percent_mid'  => round($mid->avg() * 0.35),
                                'percent_fe'    => round($finalExamScores->avg() * 0.4),
                                'total_score'   => (round(($tasks->avg() * 0.25)) +  round(($mid->avg() * 0.35)) + round(($finalExamScores->avg() * 0.4))),
                                
                                'comment' => '',
                            ];
                        })->values()->all();

                        // dd($scoresByStudent);

                        if (strtolower($checkSubject) == "chinese higher" || strtolower($checkSubject) == "chinese lower") {
                            $subject = Subject::where('id', $subjectId)->value('name_subject');
                            $getChineseId = Subject::where('name_subject', '=', 'chinese')->value('id');
                
                            if (strtolower($subject) == "chinese lower" || 
                                strtolower($subject) == "chinese higher") {
                                $subjectId = $getChineseId;
                            }
                        }

                        foreach($scoresByStudent as $student){
                            // dd($student['total_score']);
                            $matchingScoring = [
                                'student_id'         => $student['student_id'],
                                'grade_id'           => $gradeId,
                                'subject_id'         => $subjectId,
                                'subject_teacher_id' => $subjectTeacher->teacher_id,
                                'semester'           => session('semester'),
                                'academic_year'      => session('academic_year'),
                            ];
                        
                            // Data untuk diupdate atau disimpan
                            $updateScoring = [
                                'grades'      => $this->determineGrade($student['total_score']),
                                'final_score' => $student['total_score'],
                                'comment'     => "",
                            ];
                        
                            Acar::updateOrCreate($matchingScoring, $updateScoring);
                        }
                    }
                }


                // Perhitungan SOOA Academic
                $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                    ->where('acars.grade_id', $gradeId)
                    ->where('acars.semester', $semester)
                    ->where('acars.academic_year', $academic_year)
                    ->get();
                
                $sooaByStudent = $results->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
                    $majorSubject = Major_subject::pluck('subject_id')->toArray();
                    $minorSubject = Minor_subject::pluck('subject_id')->toArray();
                    $supplementarySubject = Supplementary_subject::pluck('subject_id')->toArray();
                    $majorSubjectsScores = $scores->whereIn('subject_id', $majorSubject)->pluck('final_score');
                    $minorSubjectsScores = $scores->whereIn('subject_id', $minorSubject)->pluck('final_score');
                    $supplementarySubjectsScores = $scores->whereIn('subject_id', $supplementarySubject)->pluck('final_score');
                    
                    $sortedScores = $scores->sortBy('subject_id');

                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'scores' => $sortedScores->map(function ($score) {
                            return [
                                'subject_id' => $score->subject_id,
                                'final_score' => $score->final_score,
                                'grades' => $score->grades,
                            ];
                        })->all(),
                        'percent_majorSubjects' => round($majorSubjectsScores->avg() * 0.7),
                        'percent_minorSubjects' => round($minorSubjectsScores->avg() * 0.2),
                        'percent_supplementarySubjects' => round($supplementarySubjectsScores->avg() * 0.1),
                        'total_score' => round((($majorSubjectsScores->avg() * 0.7) + ($minorSubjectsScores->avg() * 0.2) + $supplementarySubjectsScores->avg() * 0.1)),
                        'comment' => '',
                    ];
                })->values()->all();
                
                // dd($sooaByStudent);

                foreach($sooaByStudent as $sooa){
                    $matchingScoring = [
                        'student_id'         => $sooa['student_id'],
                        'grade_id'           => $gradeId,
                        'class_teacher_id'   => $classTeacher->teacher_id,
                        'semester'           => session('semester'),
                        'academic_year'      => session('academic_year'),
                    ];
                
                    // Data untuk diupdate atau disimpan
                    $updateScoring = [
                        'academic'           => $sooa['total_score'],
                        'grades_academic'    => $this->determineGrade($sooa['total_score']),
                    ];
                
                    // Gunakan updateOrCreate untuk tabel Acar
                    if($gradeId == 11 || $gradeId == 12 || $gradeId == 13){
                        Sooa_secondary::updateOrCreate($matchingScoring, $updateScoring);
                    }
                    else {
                        Sooa_primary::updateOrCreate($matchingScoring, $updateScoring);
                    }
                }
  
                session()->flash('after_update_score');

        } catch (Exception $err) {
            DB::rollBack();
            Log::error('Terjadi kesalahan:', [
                'message' => $err->getMessage(),
                'file' => $err->getFile(),
                'line' => $err->getLine(),
                'trace' => $err->getTraceAsString(),
            ]);
        }
    }

}
