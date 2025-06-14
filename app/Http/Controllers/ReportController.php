<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Relationship;
use App\Models\Student_relationship;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Grade_subject;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\Major_subject;
use App\Models\Minor_subject;
use App\Models\Supplementary_subject;
use App\Models\Comment;
use App\Models\Acar_comment;
use App\Models\Acar;
use App\Models\Sooa_primary;
use App\Models\Sooa_secondary;
use App\Models\Report_card;
use App\Models\Report_card_status;
use App\Models\Scoring_status;
use App\Models\Acar_status;
use App\Models\Sooa_status;
use App\Models\Nursery_toddler;
use App\Models\Kindergarten;
use App\Models\Student_eca;
use App\Models\Chinese_higher;
use App\Models\Chinese_lower;
use App\Models\Master_academic;
use App\Models\Type_exam;
use App\Models\Tcop;
use App\Models\Mid_kindergarten;
use App\Models\Mid_report;
use App\Models\MonthlyActivity;
use App\Models\Student_Monthly_Activity;
use App\Services\BillingService;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index(){
        try {
            //code...
            session()->flash('page',  $page = (object)[
               'page' => 'reports',
               'child' => 'reports',
            ]);
   
            $grade = grade::get();
   
                foreach ($grade as $gt) {
                    $gt->students = Student::where('grade_id', $gt->id)->get();
                }

            $other = Grade::with(['student', 'subject'])
                ->join('teacher_grades', 'teacher_grades.grade_id', '=', 'grades.id')
                ->leftJoin('teachers', function ($join) {
                    $join->on('teachers.id', '=', 'teacher_grades.teacher_id');
                })
                ->whereIn('grades.id', [1, 2, 3, 4, 14])
                ->select('grades.id as id', 'grades.name as grade_name', 'grades.class as grade_class',
                'teachers.name as teacher_class')
                ->withCount([
                    'student as active_student_count', 
                    'subject as active_subject_count' => function ($query) {
                        $query->where('grade_subjects.academic_year', session('academic_year'));
                    }
                ])
                ->orderBy('grades.id', 'asc')
                ->get();
   
            $primary  = Grade::with(['student', 'subject'])
                ->join('teacher_grades', 'teacher_grades.grade_id', '=', 'grades.id')
                ->leftJoin('teachers', function ($join) {
                    $join->on('teachers.id', '=', 'teacher_grades.teacher_id');
                })
                ->whereIn('grades.id', [5, 6, 7, 8, 9, 10])
                ->select('grades.id as id', 'grades.name as grade_name', 'grades.class as grade_class',
                'teachers.name as teacher_class')
                ->withCount([
                    'student as active_student_count' => function($query) {
                        $query->where('students.is_active', true);
                    }, 
                    'subject as active_subject_count' => function ($query) {
                        $query->where('grade_subjects.academic_year', session('academic_year'));
                    }
                ])
                ->orderBy('grades.id', 'asc')
                ->get();

            $secondary = Grade::with(['student', 'subject'])
                ->join('teacher_grades', 'teacher_grades.grade_id', '=', 'grades.id')
                ->leftJoin('teachers', function ($join) {
                    $join->on('teachers.id', '=', 'teacher_grades.teacher_id');
                })
                ->whereIn('grades.id', [11, 12 ,13])
                ->select('grades.id as id', 'grades.name as grade_name', 'grades.class as grade_class',
                'teachers.name as teacher_class')
                ->withCount([
                    'student as active_student_count', 
                    'subject as active_subject_count' => function ($query) {
                        $query->where('grade_subjects.academic_year', session('academic_year'));
                    }
                ])
                ->orderBy('grades.id', 'asc')
                ->get();

            $data = [
                'other'     => $other,
                'grade'     => $grade,
                'primary'   => $primary,
                'secondary' => $secondary,
            ];

            // dd($data);
   
            return view('components.report.data-report')->with('data', $data);
   
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function detailSubjectClass($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'reports',
            ]);
  
            $subject = Grade_subject::join('subjects', 'subjects.id', '=', 'grade_subjects.subject_id')
                ->leftJoin('teacher_subjects', function ($join) {
                    $join->on('teacher_subjects.subject_id', '=', 'grade_subjects.subject_id')
                        ->on('teacher_subjects.grade_id', '=', 'grade_subjects.grade_id');
                })
                ->leftJoin('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->where('grade_subjects.grade_id', $id)
                ->where('grade_subjects.academic_year', session('academic_year'))
                ->select(
                    'subjects.id as subject_id',
                    'teachers.id as teacher_id',
                    'subjects.name_subject as subject_name',
                    'teachers.name as teacher_name',
                )
                ->orderBy('subjects.name_subject', 'asc')
                ->get();

            $getIdTeacher = Teacher_grade::where('grade_id', $id)->value('teacher_id');

            $status = Teacher_subject::where('teacher_subjects.grade_id', $id)
                ->join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                ->join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
                ->join('scoring_statuses', function ($join) {
                    $join->on('scoring_statuses.grade_id', '=', 'grades.id')
                        ->on('scoring_statuses.subject_id', '=', 'subjects.id');
                })
                ->where('scoring_statuses.semester', session('semester'))
                ->where('scoring_statuses.academic_year', session('academic_year'))
                ->select('subjects.id as subject_id', 'grades.id as grade_id', 'scoring_statuses.status')
                ->get();

                
            foreach ($subject as $item) {
                $item->status = $status->firstWhere('subject_id', $item->subject_id)
                ->status ?? 'Not Submitted';
            }
        
            $grade = grade::where('id', $id)->get();

            $data = [
                'grade' => $grade,
                'subject' => $subject,
            ];

            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.report.subject-teacher')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail-report')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function detailSubjectClassSec($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'reports',
            ]);
  
            $subject = Grade_subject::join('subjects', 'subjects.id', '=', 'grade_subjects.subject_id')
                ->leftJoin('teacher_subjects', function ($join) {
                    $join->on('teacher_subjects.subject_id', '=', 'grade_subjects.subject_id')
                        ->on('teacher_subjects.grade_id', '=', 'grade_subjects.grade_id');
                })
                ->leftJoin('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->where('grade_subjects.grade_id', $id)
                ->where('grade_subjects.academic_year', session('academic_year'))
                ->select(
                    'subjects.id as subject_id',
                    'teachers.id as teacher_id',
                    'subjects.name_subject as subject_name',
                    'teachers.name as teacher_name',
                )
                ->get();

            $getIdTeacher = Teacher_grade::where('grade_id', $id)->value('teacher_id');

            $status = Teacher_subject::where('teacher_subjects.grade_id', $id)
                ->join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                ->join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
                ->join('scoring_statuses', function ($join) {
                    $join->on('scoring_statuses.grade_id', '=', 'grades.id')
                        ->on('scoring_statuses.subject_id', '=', 'subjects.id');
                })
                ->where('scoring_statuses.semester', session('semester'))
                ->where('scoring_statuses.academic_year', session('academic_year'))
                ->select('subjects.id as subject_id', 'grades.id as grade_id', 'scoring_statuses.status')
                ->get();

            foreach ($subject as $item) {
                $item->status = $status->firstWhere('subject_id', $item->subject_id)
                ->status ?? 'Not Submitted';
            }

            $grade = grade::where('id', $id)->get();

            $data = [
                'grade' => $grade,
                'subject' => $subject,
            ];

            // dd($subject);   
            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.report.subject-teacher-sec')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail-report-sec')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }
    
    // Melihat seluruh nilai siswa berdasarkan kelas & mapel Primary
    public function detailSubjectClassStudent($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'reports',
            ]);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $subject = Subject::where('id', $subjectId)
                ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                ->first();

            $teacherId = $subjectTeacher->teacher_id;
            $semester = session('semester');
            $academic_year = session('academic_year');

            // check apakah major subject
            $majorSubject = Major_subject::select('subject_id')->get();
            $isMajorSubject = $majorSubject->pluck('subject_id')->contains($subjectId);

            $homework       = Type_exam::where('name', 'homework')->value('id');
            $exercise       = Type_exam::where('name', 'exercise')->value('id');
            $quiz           = Type_exam::where('name', 'quiz')->value('id');
            $finalExam      = Type_exam::where('name', 'final exam')->value('id');
            $participation  = Type_exam::where('name', 'participation')->value('id');
 
            $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId) {
                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                    $subQuery->where('subject_id', $subjectId);
                });
            }])
            ->where('grades.id', $gradeId)
            ->withCount([
                'exam as total_homework' => function ($query) use ($subjectId,  $homework, $semester, $academic_year) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', $homework)
                    ->where('semester', $semester)
                    ->where('exams.academic_year', $academic_year);
                },
                'exam as total_exercise' => function ($query) use ($subjectId,  $exercise, $semester, $academic_year) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', $exercise)
                    ->where('semester', $semester)
                    ->where('exams.academic_year', $academic_year);
                },
                'exam as total_quiz' => function ($query) use ($subjectId,  $quiz, $semester, $academic_year) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->where('type_exam', $quiz)
                    ->where('semester', $semester)
                    ->where('exams.academic_year', $academic_year);
                },
                'exam as total_final_exam' => function ($query) use ($subjectId,  $finalExam, $semester, $academic_year) {
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
                    ->where('students.religion', '=', 'catholic cristianity')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
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
                ->where('students.religion', '=', 'protestant cristianity')
                ->where('students.is_active', true)
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->where('exams.teacher_id', $teacherId)
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
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "chinese lower") {
                $chineseLowerStudent = Chinese_lower::pluck('student_id')->toArray();
                
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
                ->where('students.is_active', true)
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "chinese higher") {
                $chineseHigherStudent = Chinese_higher::pluck('student_id')->toArray();
                
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
                ->where('students.is_active', true)
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->where('exams.teacher_id', $teacherId)
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
                    ->where('grades.id', $gradeId)
                    ->where('students.is_active', true)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('students.is_active', true)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $subjectTeacher->teacher_id)
                    ->get();
            }

            if ($isMajorSubject) {
            
                $type = "major_subject_assessment";

                $comments = Comment::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                    ->where('type', $type)
                    ->get()
                    ->keyBy('student_id');


                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {
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
                        'avg_homework'      => round($homeworkScores->avg(), 1),
                        'avg_exercise'      => round($exerciseScores->avg(), 1),
                        'avg_participation' => round($participationScore->avg(), 1),
                        'avg_quiz'          => round($quizScores->avg(), 1),

                        'percent_homework'      => round($homeworkScores->avg() * 0.1, 1),
                        'percent_exercise'      => round($exerciseScores->avg() * 0.15, 1),
                        'percent_participation' => round($participationScore->avg() * 0.05, 1),
                        'h+e+p'                 => (round($homeworkScores->avg() * 0.1, 1)) + round(($exerciseScores->avg() * 0.15), 1) + round(($participationScore->avg() * 0.05)),
                       
                        'percent_quiz' => round($quizScores->avg() * 0.3),
                        'percent_fe'   => round($finalExamScores->avg() * 0.4),
                        'total_score'  => (round(($homeworkScores->avg() * 0.1)) + round(($exerciseScores->avg() * 0.15)) + round(($participationScore->avg() * 0.05))) + round(($quizScores->avg() * 0.3)) + round(($finalExamScores->avg() * 0.4)),
                        
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();
            } else {

                $type = "minor_subject_assessment";

                $comments = Comment::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                    ->where('type', $type)
                    ->get()
                    ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {
                    $homework = Type_exam::where('name', '=', 'homework')->value('id');
                    $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                    $participation = Type_exam::where('name', '=', 'participation')->value('id');
                    $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
                    $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final assessment'])
                        ->pluck('id')
                        ->toArray();
                    
                    $student            = $scores->first();
                    $homeworkScores     = $scores->where('type_exam', $homework)->pluck('score');
                    $exerciseScores     = $scores->where('type_exam', $exercise)->pluck('score');
                    $participationScore = $scores->where('type_exam', $participation)->pluck('score');
                    $quizScores         = $scores->where('type_exam', $quiz)->pluck('score');
                    $finalExamScores    = $scores->where('type_exam', $finalAssessment)->pluck('score');

                    $homeworkAvg       = $homeworkScores->avg() ?: 0;
                    $exerciseAvg       = $exerciseScores->avg() ?: 0;
                    $participationAvg  = $participationScore->avg() ?: 0;
                    $quizAvg          = $quizScores->avg() ?: 0;
                    $finalExamAvg     = $finalExamScores->avg() ?: 0;

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
                        
                        'total_score' => (($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + ($finalExamScores->avg() * 0.35),

                        'grades' => $grade,
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();
            }

            // dd($scoresByStudent);

            
            $status = Scoring_status::where('grade_id', $gradeId)
                ->where('subject_id', $subject->subject_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->where('teacher_id', $subjectTeacher->teacher_id)
                ->first();

            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);   
            
            if($isMajorSubject){
                return view('components.report.detail_scoring_major_subject_primary')->with('data', $data);
            }
            else {
                return view('components.report.detail_scoring_subject_primary')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function scoringDecline($gradeId, $teacherId, $subjectId, $semester)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' reports',
                'child' => 'report class teacher',
            ]);

            $academic_year = session('academic_year');

            Scoring_status::where('grade_id', $gradeId)
                // ->where('subject_id', $subjectId)
                // ->where('teacher_id', $teacherId)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->delete();

            session()->flash('after_decline_scoring');

            return redirect()->back();
        } catch (Exception $err) {
            dd($err);
        }
    }

    // Melihat seluruh nilai siswa berdasarkan kelas & mapel Secondary
    public function detailSubjectClassStudentSec($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'reports',
            ]);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $teacherId = $subjectTeacher->teacher_id;

            $subject = Subject::where('id', $subjectId)
                ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                ->first();
            $tasks = Type_exam::whereIn('name', ['homework', 'small project', 'presentation'])
                ->pluck('id')
                ->toArray();
            $mid = Type_exam::whereIn('name', ['quiz', 'practical', 'exam', 'project'])
                ->pluck('id')
                ->toArray();
            $finalExam = Type_exam::whereIn('name', ['written tes', 'big project'])
                ->pluck('id')
                ->toArray();

            $semester = session('semester');
            $academic_year = session('acadmeic_year');

            $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $mid, $tasks, $finalExam) {
                $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                    $subQuery->where('subject_id', $subjectId);
                });
            }])
            ->where('grades.id', $gradeId)
            ->withCount([
                'exam as total_tasks' => function ($query) use ($subjectId, $tasks, $semester, $academic_year) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->whereIn('type_exam', $tasks)
                    ->where('semester', $semester)
                    ->where('exams.academic_year', $academic_year);
                },
                'exam as total_mid' => function ($query) use ($subjectId, $mid, $semester, $academic_year) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->whereIn('type_exam', $mid)
                    ->where('semester', $semester)
                    ->where('exams.academic_year', $academic_year);
                },
                'exam as total_final_exam' => function ($query) use ($subjectId, $finalExam, $semester, $academic_year) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    })
                    ->whereIn('type_exam', $finalExam)
                    ->where('semester', $semester)
                    ->where('exams.academic_year', $academic_year);
                },
            ])
            ->first();

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
                    ->where('students.religion', '=', 'catholic cristianity')
                    ->where('students.is_active', true)
                    ->where('grades.id', $gradeId)
                    ->where('subject_exams.subject_id', $subjectId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('exams.teacher_id', $teacherId)
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
                ->where('students.religion', '=', 'protestant cristianity')
                ->where('students.is_active', true)
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->where('exams.teacher_id', $teacherId)
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
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "chinese lower") {
                $chineseLowerStudent = Chinese_lower::pluck('student_id')->toArray();
                
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
                ->where('students.is_active', true)
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "chinese higher") {
                $chineseHigherStudent = Chinese_higher::pluck('student_id')->toArray();
                
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
                ->where('students.is_active', true)
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->where('exams.teacher_id', $teacherId)
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
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->where('exams.teacher_id', $teacherId)
                ->get();
            }
            
            $type = "subject_assessment_secondary";

            $comments = Comment::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->where('type', $type)
                ->get()
                ->keyBy('student_id');

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments, $tasks, $mid, $finalExam) {

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
                    
                    'comment' => $comments->get($student->student_id)?->comment ?? '',
                ];
            })->values()->all();

            $checkPermission = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->where('teacher_id', $teacherId)
                ->first();

            if($checkPermission->is_lead == null && $checkPermission->is_group == null){
                $permission = true;
            }
            elseif($checkPermission->is_lead !== null && $checkPermission->is_group == null){
                $permission = true;
            }
            elseif($checkPermission->is_lead == null && $checkPermission->is_group !== null){
                $permission = false;
            }

            $status = Scoring_status::where('grade_id', $gradeId)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->where('teacher_id', $subjectTeacher->teacher_id)
                ->where('subject_id', $subject->subject_id)
                ->first();

            
            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
                'status' => $status,
                'permission' => $permission,
            ];

            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.report.detail_scoring_subject_secondary')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail_scoring_subject_secondary')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    //Academic Assessment Report
    public function acarPrimary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'academic assessment report',
            ]);

            $semester = session('semester');
            $academic_year = session('academic_year');
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->where('academic_year', session('academic_year'))
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->where('academic_year', session('academic_year'))
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->where('acars.grade_id', $gradeId)
                ->where('acars.semester', $semester)
                ->where('acars.academic_year', $academic_year)
                ->where('students.is_active', TRUE)
                ->orderBy('students.name', 'asc')
                ->get();

            $comments = Acar_comment::where('grade_id', $gradeId)
                ->where('type', 'academic_assessment_report')
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->get()
                ->keyBy('student_id');

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {
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
                    'comment' => $comments->get($student->student_id)?->comment ?? '',
                ];
            })->values()->all();

            // dd($scoresByStudent);

            $status = Acar_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                // ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $healthEducation = Subject::where('name_subject', 'Health Education')->value('id');
            $checkHE = Grade_subject::where('grade_id', $gradeId)
                ->where('subject_id', $healthEducation)
                ->first();

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
                'healthEducation' => $checkHE,
            ];
            
            return view('components.report.acar_primary')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function acarSecondary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'academic assessment report',
            ]);

            $semester = session('semester');
            $academic_year = session('academic_year');
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->where('acars.grade_id', $gradeId)
                ->where('acars.semester', $semester)
                ->where('acars.academic_year', $academic_year)
                ->where('students.is_active', TRUE)
                ->orderBy('students.name', 'asc')
                ->get();

            $comments = Acar_comment::where('grade_id', $gradeId)
                ->where('type', 'academic_assessment_report')
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->get()
                ->keyBy('student_id');

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments) {
                $student = $scores->first();
                $majorSubject = Major_subject::pluck('subject_id')->toArray();
                $minorSubject = Minor_subject::pluck('subject_id')->toArray();
                $supplementarySubject = Supplementary_subject::pluck('subject_id')->toArray();

                $majorSubjectsScores = $scores->whereIn('subject_id', $majorSubject)->pluck('final_score');
                $minorSubjectsScores = $scores->whereIn('subject_id', $minorSubject)->pluck('final_score');
                $supplementarySubjectsScores = $scores->whereIn('subject_id', $supplementarySubject)->pluck('final_score');

                // dd($majorSubjectsScores);

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'subject_id' => $score->subject_id,
                            'final_score' => $score->final_score,
                            'grades' => $score->grades,
                        ];
                    })->all(),
                    'percent_majorSubjects' => round($majorSubjectsScores->avg() * 0.7),
                    'percent_minorSubjects' => round( $minorSubjectsScores->avg() * 0.2),
                    'percent_supplementarySubjects' => round($supplementarySubjectsScores->avg() * 0.1),
                    'total_score' => round((($majorSubjectsScores->avg() * 0.7) + ($minorSubjectsScores->avg() * 0.2) + $supplementarySubjectsScores->avg() * 0.1)),
                    'comment' => $comments->get($student->student_id)?->comment ?? '',
                ];
            })->values()->all();

            // dd($scoresByStudent);

            $status = Acar_status::where('grade_id', $grade->grade_id)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            // ->where('class_teacher_id', $classTeacher->teacher_id)
            ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);
            
            return view('components.report.acar_secondary')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function acarDecline($gradeId, $teacherId, $semester)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' reports',
                'child' => 'report class teacher',
            ]);

            $academic_year = session('academic_year');

            Acar_status::where('grade_id', $gradeId)
                // ->where('class_teacher_id', $teacherId)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->delete();

            session()->flash('after_decline_acar');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline ACAR Primary',
                    'text' => 'Succesfully decline ACAR primary'
                ]
            ]);

        } catch (Exception $err) {
            dd($err);
        }
    }
    //End Academic Assessment Report

    // Summary of Academic Assesment

    public function sooaPrimary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'summary of academic assessment',
            ]);

            $semester = session('semester');
            $academic_year = session('academic_year');
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id', 'grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();
    
            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();
    
            $results = Sooa_primary::leftJoin('students', 'students.id', '=', 'sooa_primaries.student_id')
                ->where('sooa_primaries.grade_id', $gradeId)
                ->where('students.is_active', true)
                ->where('sooa_primaries.semester', $semester)
                ->where('sooa_primaries.academic_year', $academic_year)
                ->orderBy('students.name', 'asc')
                ->get();
    
            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();

                if (Student_eca::where('student_id', $student->student_id)->exists()) {
                    $haveEca = 1;
                    $nameEca = Student_eca::where('student_ecas.student_id', $student->student_id)
                        ->leftJoin('ecas', 'ecas.id', 'student_ecas.eca_id')
                        ->get()->value('name');
                } else{
                    $haveEca = 0;
                    $nameEca = "Not Choice";
                }
    
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'ranking' => $student->ranking,
                    'haveEca' => $haveEca,
                    'nameEca' => $nameEca,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'academic' => $score->academic,
                            'grades_academic' => $score->grades_academic,
                            'choice' => $score->choice,
                            'grades_choice' => $score->grades_choice,
                            'language_and_art' => $score->language_and_art,
                            'grades_language_and_art' => $score->grades_language_and_art,
                            'self_development' => $score->self_development,
                            'grades_self_development' => $score->grades_self_development,
                            'eca_aver' => $score->eca_aver,
                            'grades_eca_aver' => $score->grades_eca_aver,
                            'behavior' => $score->behavior,
                            'grades_behavior' => $score->grades_behavior,
                            'attendance' => $score->attendance,
                            'grades_attendance' => $score->grades_attendance,
                            'participation' => $score->participation,
                            'grades_participation' => $score->grades_participation,
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                        ];
                    })->all(),
                ];
            })->values()->all();
    
            $status = Sooa_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                // ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];
    
            // dd($data);
            
            return view('components.report.sooa_primary')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }
    
    public function sooaSecondary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'summary of academic assessment',
            ]);
            
            $semester = session('semester');
            $academic_year = session('academic_year');

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id', 'grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                ->where('sooa_secondaries.grade_id', $gradeId)
                ->where('sooa_secondaries.semester', $semester)
                ->where('sooa_secondaries.academic_year', $academic_year)
                ->where('students.is_active', TRUE)
                ->orderBy('students.name', 'asc')
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();

                // if (Student_eca::where('student_id', $student->student_id)->exists()) {
                //     $haveEca = 1;
                //     $ecaData = Student_eca::where('student_ecas.student_id', $student->student_id)
                //         ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
                //         ->get(['student_ecas.student_id', 'ecas.name as eca_name']);

                //     $groupedEcaData = [];
                //     $counter = 1;

                //     // dd(count($ecaData));

                //     if (count($ecaData) == 1) {
                //         $groupedEcaData['student_id'] = $ecaData[0]->student_id;
                //         $groupedEcaData['eca_1'] = $ecaData[0]->eca_name;
                //         $groupedEcaData['eca_2'] = "Not Choice";
                //     }
                //     elseif (count($ecaData) == 2) {
                //         for ($i=0; $i < 2; $i++) { 
                //             $groupedEcaData['student_id'] = $ecaData[$i]->student_id;
                //             $groupedEcaData['eca_' . $i+1] = $ecaData[$i]->eca_name;
                //         }
                //     }

                // } else{
                //     $haveEca = 0;
                //     $groupedEcaData = "Not Choice";
                // }

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'ranking' => $student->ranking,
                    // 'haveEca' => $haveEca,
                    // 'nameEca' => $groupedEcaData,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'academic' => $score->academic,
                            'grades_academic' => $score->grades_academic,
                            'eca_1' => $score->eca_1,
                            'grades_eca_1' => $score->grades_eca_1,
                            'eca_2' => $score->eca_2,
                            'grades_eca_2' => $score->grades_eca_2,
                            'self_development' => $score->self_development,
                            'grades_self_development' => $score->grades_self_development,
                            'eca_aver' => $score->eca_aver,
                            'grades_eca_aver' => $score->grades_eca_aver,
                            'behavior' => $score->behavior,
                            'grades_behavior' => $score->grades_behavior,
                            'attendance' => $score->attendance,
                            'grades_attendance' => $score->grades_attendance,
                            'participation' => $score->participation,
                            'grades_participation' => $score->grades_participation,
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Sooa_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                // ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status
            ];

            // dd($data);
            
            return view('components.report.sooa_secondary')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function sooaPrimaryDecline($gradeId, $teacherId, $semester)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' reports',
                'child' => 'report class teacher',
            ]);

            $academic_year = session('academic_year');

            Sooa_status::where('grade_id', $gradeId)
                // ->where('class_teacher_id', $teacherId)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->delete();

            session()->flash('after_decline_sooa');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline SOOA',
                    'text' => 'Succesfully decline SOOA'
                ]
            ]);


        } catch (Exception $err) {
            dd($err);
        }
    }

    // End Summary of Academic Assesment

    public function tcopPrimary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'the certificate of promotion',
            ]);

            $academic_year = session('academic_year');
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $promoteGrade = Grade::where('id', $gradeId + 1)->first();
                
            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                ->where('sooa_primaries.grade_id', $gradeId)
                ->where('sooa_primaries.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $semester = session('semester');

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();

                // dd($majorSubjectsScores);
                $scoresBySemester = $scores->groupBy('semester')->map(function ($semesterScores) {
                    return $semesterScores->map(function ($score) {
                        return [
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                            'semester' => $score->semester,
                        ];
                    })->all();
                });

                $finalScores = $scores->pluck('final_score');
                $averageFinalScore = $finalScores->count() > 0 ? round($finalScores->sum() / $finalScores->count(), 1) : 0;
                $marks = $this->determineGrade($averageFinalScore);

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scoresBySemester,
                    'average_final_score' => $averageFinalScore,
                    'marks' => $marks,
                ];
                
            })->values()->all();

            // dd($scoresByStudent);
            $status = Tcop::where('grade_id', $grade->grade_id)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->where('academic_year', $academic_year)
                ->first();

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'promote' => $promoteGrade,
                'status' => $status,
            ];

            // dd($data);
            
            return view('components.report.tcop')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function tcopSecondary($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'reports',
            ]);
            
            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $promoteGrade  = Grade::where('id', $gradeId + 1)->first();
            $academic_year = session('academic_year');
                
            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                ->where('sooa_secondaries.grade_id', $gradeId)
                ->where('sooa_secondaries.academic_year', $academic_year)
                ->where('students.is_active', TRUE)
                ->orderBy('students.name', 'asc')
                ->get();

            $semester = session('semester');

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();

                // dd($majorSubjectsScores);
                $scoresBySemester = $scores->groupBy('semester')->map(function ($semesterScores) {
                    
                    return $semesterScores->map(function ($score) {
                        return [
                            'final_score' => $score->final_score,
                            'grades_final_score' => $score->grades_final_score,
                            'semester' => $score->semester,
                        ];
                    })->all();
                });

                $finalScores = $scores->pluck('final_score');
                $averageFinalScore = $finalScores->count() > 0 ? round($finalScores->sum() / $finalScores->count(), 1) : 0;
                $marks = $this->determineGrade($averageFinalScore);

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scoresBySemester,
                    'average_final_score' => $averageFinalScore,
                    'marks' => $marks,
                ];
                
            })->values()->all();

            // dd($scoresByStudent);

            $status = Tcop::where('grade_id', $grade->grade_id)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->where('academic_year', $academic_year)
                ->first();

            $data = [
                'grade' => $grade,
                'students' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'promote' => $promoteGrade,
                'status' => $status
            ];

            // dd($data);
            
            return view('components.report.tcop')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function tcopDecline($gradeId, $teacherId)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' reports',
                'child' => 'report class teacher',
            ]);

            $academic_year = session('academic_year');

            Tcop::where('grade_id', $gradeId)
                // ->where('class_teacher_id', $teacherId)
                ->where('academic_year', $academic_year)
                ->delete();

            session()->flash('after_decline_tcop');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline TCOP',
                    'text' => 'Succesfully decline TCOP'
                ]
            ]);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function detail(){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'scores',
                'child' => 'scores',
            ]);

            $getIdParent = Relationship::where('user_id', '=', session('id_user'))->value('id');

            $id = session('studentId');
  
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
                ->where('scores.student_id', $id)
                ->select('exams.id as exam_id', 'exams.name_exam as exam_name', 'exams.date_exam as date_exam',
                    'grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class',
                    'subjects.name_subject as subject_name', 'subjects.id as subject_id',
                    'teachers.name as teacher_name', 'teachers.id as teacher_id', 
                    'type_exams.name as type_exam', 'type_exams.id as type_exam_id',
                    'students.id as student_id', 'students.name as student_name',
                    'scores.score as score')
                ->paginate(15);


            return view('components.teacher.detail-report')->with('data', $data);
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    // TEACHER
    public function detailSubjectClassStudentKindergartenTeacher($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report subject teacher',
            ]);

            $userId        = session('id_user');
            $teacherId     = Teacher::where('user_id', $userId)->value('id');
            $semester      = session('semester');
            $academic_year = session('academic_year');

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

            // $homework = Type_exam::where('name', 'homework')->value('id');
            $exercise       = Type_exam::where('name', 'exercise')->value('id');
            $quiz           = Type_exam::where('name', 'quiz')->value('id');
            $participation  = Type_exam::where('name', 'participation')->value('id');

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

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($participation, $exercise, $quiz) {

                $student = $scores->first();
                $exercise       = $scores->where('type_exam', $exercise)->pluck('score');
                $quiz           = $scores->where('type_exam', $quiz)->pluck('score');
                $participation  = $scores->where('type_exam', $participation)->pluck('score');

                // dd($quizScores);
                
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
            
            $status = Scoring_status::where('grade_id', $gradeId)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->where('teacher_id', $subjectTeacher->teacher_id)
                ->where('subject_id', $subject->subject_id)
                ->first();
            
            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
                'status' => $status,
                'exercise' => $exercise,
                'quiz'   => $quiz,
                'participation' => $participation,
            ];

            // dd($data);   

            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.report.detail_scoring_subject_kindergarten')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail_scoring_subject_kindergarten')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function detailSubjectClassStudentTeacher($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report subject teacher',
            ]);

            $userId = session('id_user');

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
                ->where('students.is_active', true)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                // ->where('exams.teacher_id', $teacherId)
                ->orderBy('students.name', 'asc')
                ->get();
            }

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

                $comments = Comment::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    // ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                    ->where('semester', $semester)
                    ->where('academic_year', $academic_year)
                    ->where('type', $type)
                    ->get()
                    ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments, $subjectId) {
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
                        'acar'      => ACAR::where('student_id', $student->student_id)
                            ->where('subject_id', $subjectId)
                            ->where('semester', session('semester'))
                            ->where('academic_year', session('academic_year'))
                            ->value('final_score'),

                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();

                // foreach($scoresByStudent as $student){
                //     $matchingScoring = [
                //         'student_id'         => $student['student_id'],
                //         'grade_id'           => $gradeId,
                //         'subject_id'         => $subjectId,
                //         'subject_teacher_id' => $subjectTeacher->teacher_id,
                //         'semester'           => session('semester'),
                //         'academic_year'      => session('academic_year'),
                //     ];
                
                //     // Data untuk diupdate atau disimpan
                //     $updateScoring = [
                //         'grades'      => $this->determineGrade($student['total_score']),
                //         'final_score' => $student['total_score'],
                //     ];
                
                //     // Gunakan updateOrCreate untuk tabel Acar
                //     Acar::updateOrCreate($matchingScoring, $updateScoring);
                // }

            } else {
                

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

                $comments = Comment::where('grade_id', $gradeId)
                    ->where('subject_id', $subjectId)
                    // ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                    ->where('semester', $semester)
                    ->where('academic_year', $academic_year)
                    ->where('type', $type)
                    ->get()
                    ->keyBy('student_id');

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments, $subjectId, $subject) {
                    
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


                    $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');
        
                    if (strtolower($subject->subject_name) == "religion islamic" || 
                        strtolower($subject->subject_name) == "religion catholic" || 
                        strtolower($subject->subject_name) == "religion christian" || 
                        strtolower($subject->subject_name) == "religion buddhism" || 
                        strtolower($subject->subject_name) == "religion hinduism" || 
                        strtolower($subject->subject_name) == "religion confucianism") {
                        $subjectId = $getReligionId;
                    } else {
                        $subjectId = $subjectId;
                    }
                    
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
                        
                        'percent_homework' => round($homeworkScores->avg() * 0.2, 2),
                        'percent_exercise' => round($exerciseScores->avg() * 0.35, 2),
                        'percent_participation' => round($participationScore->avg() * 0.1, 2),
                        'percent_fe' => round($finalExamScores->avg() * 0.35, 2),
                        
                        'total_score' => round(($homeworkScores->avg() * 0.2) + ($exerciseScores->avg() * 0.35) + ($participationScore->avg() * 0.10)) + round($finalExamScores->avg() * 0.35),
                        
                        'acar'      => ACAR::where('student_id', $student->student_id)
                            ->where('subject_id', $subjectId)
                            ->where('semester', session('semester'))
                            ->where('academic_year', session('academic_year'))
                            ->value('final_score'),

                        'grades' => $grade,
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                    ];
                })->values()->all();
            }

            $status = Scoring_status::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                // ->where('teacher_id', $subjectTeacher->teacher_id)
                ->first();

            $homework = Type_exam::where('name', '=', 'homework')->value('id');
            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
            $participation = Type_exam::where('name', '=', 'participation')->value('id');
            $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
            $project = Type_exam::where('name', 'project', )->value('id');
            $practical = Type_exam::where('name', 'practical', )->value('id');
            $final_assessment = Type_exam::where('name', 'final assessment', )->value('id');
            $final_exam = Type_exam::where('name', 'final exam', )->value('id');

            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
                'status' => $status,
                'homework' => $homework,
                'exercise' => $exercise,
                'participation' => $participation,
                'quiz' => $quiz,
                'project' => $project,
                'practical' => $practical,
                'finalExam' => $final_exam,
                'finalAssessment' => $final_assessment,
            ];

            // dd($data);
            // dd($scoresByStudent); 

            if($isMajorSubject){
                return view('components.teacher.detail_scoring_major_subject_primary')->with('data', $data);
            }
            else {
                return view('components.teacher.detail_scoring_subject_primary')->with('data', $data);
            }
           
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function detailSubjectClassStudentSecTeacher($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report subject teacher',
            ]);
            // dd($gradeId);

            $userId = session('id_user');
            $teacherId = Teacher::where('user_id', $userId)->value('id');
            $semester = session('semester');
            $academic_year = session('academic_year');

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

            $tasks = Type_exam::whereIn('name', ['homework', 'small project', 'presentation', 'exercice', 'Exercise'])
                ->pluck('id')
                ->toArray();

            // dd($tasks);  
            $mid = Type_exam::whereIn('name', ['quiz', 'practical exam', 'project', 'exam'])
                ->pluck('id')
                ->toArray();
            $finalExam = Type_exam::whereIn('name', ['written tes', 'big project', 'final assessment', 'final exam'])
                ->pluck('id')
                ->toArray();

            if(strtolower($subject->subject_name) !== 'science' &&
            strtolower($subject->subject_name) !== 'english' &&
            strtolower($subject->subject_name) !== 'mathematics' &&
            strtolower($subject->subject_name) !== 'chinese higher' &&
            strtolower($subject->subject_name) !== 'chinese lower'){
                $homework = Type_exam::where('name', '=', 'homework')->value('id');
                $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
                $participation = Type_exam::where('name', '=', 'participation')->value('id');
                $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
                $finalExam = Type_exam::where('name', '=', 'final exam')->value('id'); 
                $finalAssessment = Type_exam::whereIn('name', ['project', 'practical', 'final assessment', 'final exam'])
                    ->pluck('id')
                    ->toArray();
                    
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
                    'exam as total_final_exam' => function ($query) use ($subjectId, $finalAssessment, $semester, $academic_year) {
                        $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                            $subQuery->where('subject_id', $subjectId);
                        })
                        ->whereIn('type_exam', $finalAssessment)
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
            }
            else{
                $totalExam = Grade::with(['student', 'exam' => function ($query) use ($subjectId, $mid, $tasks, $finalExam) {
                    $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                        $subQuery->where('subject_id', $subjectId);
                    });
                }])
                ->where('grades.id', $gradeId)
                ->withCount([
                    'exam as total_tasks' => function ($query) use ($subjectId, $tasks, $semester, $academic_year) {
                        $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                            $subQuery->where('subject_id', $subjectId);
                        })
                        ->whereIn('type_exam', $tasks)
                        ->where('semester', $semester)
                        ->where('exams.academic_year', $academic_year);
                    },
                    'exam as total_mid' => function ($query) use ($subjectId, $mid, $semester, $academic_year) {
                        $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                            $subQuery->where('subject_id', $subjectId);
                        })
                        ->whereIn('type_exam', $mid)
                        ->where('semester', $semester)
                        ->where('exams.academic_year', $academic_year);
                    },
                    'exam as total_final_exam' => function ($query) use ($subjectId, $finalExam, $semester, $academic_year) {
                        $query->whereHas('subject', function ($subQuery) use ($subjectId) {
                            $subQuery->where('subject_id', $subjectId);
                        })
                        ->whereIn('type_exam', $finalExam)
                        ->where('semester', $semester)
                        ->where('exams.academic_year', $academic_year);
                    },
                ])
                ->first();
            }  


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
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "chinese lower") {
                $chineseLowerStudent = Chinese_lower::pluck('student_id')->toArray();
                
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
                ->where('students.is_active', true)
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->get();
            }
            elseif (strtolower($subject->subject_name) == "chinese higher") {
                $chineseHigherStudent = Chinese_higher::pluck('student_id')->toArray();
                
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
                ->where('students.is_active', true)
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
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
                ->where('grades.id', $gradeId)
                ->where('subject_exams.subject_id', $subjectId)
                ->where('students.is_active', true)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->orderBy('students.name', 'asc')
                ->get();
            }
            
            $type = "subject_assessment_secondary";

            $comments = Comment::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                // ->where('subject_teacher_id', $subjectTeacher->teacher_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->where('type', $type)
                ->get()
                ->keyBy('student_id');

            if(strtolower($subject->subject_name) !== 'science' &&
                strtolower($subject->subject_name) !== 'english' &&
                strtolower($subject->subject_name) !== 'mathematics' &&
                strtolower($subject->subject_name) !== 'chinese higher' &&
                strtolower($subject->subject_name) !== 'chinese lower')
            {
                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments, $subjectId, $subject) {
                    
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
                        if (strtolower($subject->subject_name) == "religion islamic" || 
                        strtolower($subject->subject_name) == "religion catholic" || 
                        strtolower($subject->subject_name) == "religion christian" || 
                        strtolower($subject->subject_name) == "religion buddhism" || 
                        strtolower($subject->subject_name) == "religion hinduism" || 
                        strtolower($subject->subject_name) == "religion confucianism") {
                        $getReligionId = Subject::where('name_subject', '=', 'religion')->value('id');
                        $subjectId = $getReligionId;
                    }else {
                        $subjectId = $subjectId;
                    }
                    
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
                        'comment' => $comments->get($student->student_id)?->comment ?? '',

                        
                        'acar'      => ACAR::where('student_id', $student->student_id)
                            ->where('subject_id', $subjectId)
                            ->where('semester', session('semester'))
                            ->where('academic_year', session('academic_year'))
                            ->value('final_score'),
                    ];
                })->values()->all();
            }else{
                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($comments,$subjectId, $tasks, $mid, $finalExam, $subject) {
    
                    $student            = $scores->first();
                    $tasks              = $scores->whereIn('type_exam', $tasks)->pluck('score');
                    $mid                = $scores->whereIn('type_exam', $mid)->pluck('score');
                    $finalExamScores    = $scores->whereIn('type_exam', $finalExam)->pluck('score');
                    if (strtolower($subject->subject_name) == "chinese higher" || strtolower($subject->subject_name) == "chinese lower") {
                        $getChineseId = Subject::where('name_subject', '=', 'chinese')->value('id');
                        $subjectId = $getChineseId;
                    }
                    // dd($quizScores);
                    
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
                        
                        'comment' => $comments->get($student->student_id)?->comment ?? '',
                        'acar'      => ACAR::where('student_id', $student->student_id)
                            ->where('subject_id', $subjectId)
                            ->where('semester', session('semester'))
                            ->where('academic_year', session('academic_year'))
                            ->value('final_score'),
                        'acar_id'      => ACAR::where('student_id', $student->student_id)
                            ->where('subject_id', $subjectId)
                            ->where('semester', session('semester'))
                            ->where('academic_year', session('academic_year'))
                            ->value('id'),
                    ];
                })->values()->all();
            }

            // dd($scoresByStudent);
            $status = Scoring_status::where('grade_id', $gradeId)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                // ->where('teacher_id', $subjectTeacher->teacher_id)
                ->where('subject_id', $subject->subject_id)
                ->first();

            $checkPermission = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                // ->where('teacher_id', $teacherId)
                ->first();

            if($checkPermission !== null){
                if($checkPermission->is_lead == null && $checkPermission->is_group == null){
                    $permission = true;
                }
                elseif($checkPermission->is_lead !== null && $checkPermission->is_group == null){
                    $permission = true;
                }
                elseif($checkPermission->is_lead == null && $checkPermission->is_group !== null){
                    $permission = false;
                }
            }
            else{
                $permission = false;
            }

            $homework = Type_exam::where('name', '=', 'homework')->value('id');
            $exercise = Type_exam::where('name', '=', 'exercise')->value('id');
            $participation = Type_exam::where('name', '=', 'participation')->value('id');
            $quiz = Type_exam::where('name', '=', 'quiz')->value('id');
            $project = Type_exam::where('name', 'project', )->value('id');
            $practical = Type_exam::where('name', 'practical', )->value('id');
            $final_assessment = Type_exam::where('name', 'final assessment', )->value('id');
            $final_exam = Type_exam::whereIn('name', ['final exam', 'final assessment'], )->value('id');
            
            // dd($scoresByStudent);

            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'grade' => $totalExam,
                'students' => $scoresByStudent,
                'semester' => $semester,
                'status' => $status,
                'tasks' => $tasks,
                'mid'   => $mid,
                'finalExam' => $finalExam,
                'permission' => $permission,
                'homework' => $homework,
                'exercise' => $exercise,
                'participation' => $participation,
                'quiz' => $quiz,
                'project' => $project,
                'practical' => $practical,
                'finalAssessment' => $final_assessment,
            ];

            // dd($data);   

            if(session('role') == 'superadmin' || session('role') == 'admin'){
                return view('components.teacher.detail_scoring_subject_secondary')->with('data', $data);
            }
            elseif (session('role') == 'teacher') {
                return view('components.teacher.detail_scoring_subject_secondary')->with('data', $data);
            }
            
        } catch (Exception $err) {
           return dd($err);
        }
    }

    public function teacherReport($id){
        try {
            //code...
            session()->flash('page',  $page = (object)[
               'page' => 'reports',
               'child' => 'reports',
            ]);
   
            $getIdTeacher = Teacher::where('user_id', $id)->value('id');
   
            $gradeTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.*', )
               ->get();
   
               foreach ($gradeTeacher as $gt) {
                  $gt->students = Student::where('grade_id', $gt->id)->get();
              }
   
            $data = [
               'gradeTeacher' => $gradeTeacher,
            ];
   
            // dd($data);
   
            return view('components.teacher.data-report-teacher')->with('data', $data);
   
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function classTeacher(){

        try {
            //code...
            session()->flash('page',  $page = (object)[
               'page' => 'reports',
               'child' => 'report class teacher',
            ]);

            $id = session('id_user');

            $getIdTeacher = Teacher::where('user_id', $id)->value('id');
   
            $classTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.*', )
               ->orderBy('grades.id', 'asc')
               ->where('academic_year', session('academic_year'))
               ->get();



            $data = [
               'classTeacher' => $classTeacher,
            ];

            return view('components.teacher.data-report-teacher')->with('data', $data);
   
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function subjectTeacher()
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report subject teacher',
            ]);

            $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');

            $subjectTeacher = Teacher_subject::where('teacher_subjects.teacher_id', $getIdTeacher)
                ->join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                ->join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
                ->select('teacher_subjects.*', 'subjects.name_subject as name_subject', 'subjects.icon as icon', 'grades.name', 'grades.class')
                ->where('academic_year', session('academic_year'))
                ->get();

            $status = Teacher_subject::where('teacher_subjects.teacher_id', $getIdTeacher)
                ->join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                ->join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
                ->join('scoring_statuses', function ($join) {
                    $join->on('scoring_statuses.grade_id', '=', 'grades.id')
                        ->on('scoring_statuses.subject_id', '=', 'subjects.id');
                })
                ->where('scoring_statuses.semester', session('semester'))
                ->where('scoring_statuses.academic_year', session('academic_year'))
                ->select('subjects.id as subject_id', 'grades.id as grade_id', 'scoring_statuses.status', 
                'scoring_statuses.semester', 'scoring_statuses.created_at', 'scoring_statuses.academic_year')
                ->get();

            // dd($status);

            // Add status to each subjectTeacher item
            foreach ($subjectTeacher as $item) {
                $item->status = $status
                    ->where('grade_id', $item->grade_id)
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))
                    ->firstWhere('subject_id', $item->subject_id)
                    ->status ?? 'Not Submitted';
            }
            

            // Filter grades
            $kindergartenGrades = $subjectTeacher->filter(function($item) {
                return stripos($item->name, 'kindergarten') !== false;
            });

            $primaryGrades = $subjectTeacher->filter(function($item) {
                return stripos($item->name, 'primary') !== false;
            });

            $secondaryGrades = $subjectTeacher->filter(function($item) {
                return stripos($item->name, 'secondary') !== false;
            });

            // dd($subjectTeacher);

            return view('components.teacher.data-report-subject-teacher', compact('primaryGrades', 'secondaryGrades', 'kindergartenGrades'))->with('data', $subjectTeacher);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function cardSemesterMid($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester == 1) {
                $mid = 0.5;
            }
            elseif ($semester == 2) {
                $mid = 1.5;
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('mid_reports', 'mid_reports.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('mid_reports.semester', $semester)
                ->where('mid_reports.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $monthlyActivity = MonthlyActivity::where('grades', '=', 'upper')->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.grade_id', $gradeId)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();


            // dd($studentMonthlyActivity);

            // $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
            //     $student = $scores->first();
               
            //     return [
            //         'student_id' => $student->student_id,
            //         'student_name' => $student->name,
            //         'critical_thinking' => $student->critical_thinking,
            //         'cognitive_skills' => $student->cognitive_skills,
            //         'life_skills' => $student->life_skills,
            //         'learning_skills' => $student->learning_skills,
            //         'social_and_emotional_development' => $student->social_and_emotional_development,
            //     ];
            // })->values()->all();

            // Gabungkan nilai dari $studentMonthlyActivity ke dalam $scoresByStudent
            $scoresByStudent = collect($results)->map(function ($studentScore) use ($studentMonthlyActivity) {
                // Ambil aktivitas bulanan untuk siswa ini berdasarkan student_id
                $monthlyActivities = $studentMonthlyActivity->where('student_id', $studentScore['student_id']);
                
                // Transformasikan aktivitas bulanan menjadi array yang dapat dimasukkan
                $activities = $monthlyActivities->map(function ($activity) {
                    return [
                        'activity_id' => $activity->monthly_activity_id,
                        'activity_name' => $activity->name_activity,
                        'score' => $activity->score, // Misal, jika ada status pada aktivitas
                        'grades' => $activity->grades, // Misal, jika ada status pada aktivitas
                    ];
                })->values()->all();

                // Gabungkan aktivitas bulanan ke dalam data siswa
                $studentScore['monthly_activities'] = $activities;

                return $studentScore;
            })->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $mid)
                ->where('academic_year', $academic_year)
                ->first();

            $countMA = MonthlyActivity::count();

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
                'monthlyActivities' => $monthlyActivity,
                'countMA' => $countMA,
            ];

            return view('components.report.mid_semester')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardSemester1($id){
        // dd($id);
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report card',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            // dd($semester);

            if ($semester !== 1) {
                return redirect()->back()->with([
                    'role' => session('role'),
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Invalid Semester',
                        'text' => 'This operation cannot be performed in Semester 2.'
                    ]
                ]);
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('report_cards', 'report_cards.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('report_cards.semester', $semester)
                ->where('report_cards.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'independent_work' => $score->independent_work,
                            'initiative' => $score->initiative,
                            'homework_completion' => $score->homework_completion,
                            'use_of_information' => $score->use_of_information,
                            'cooperation_with_other' => $score->cooperation_with_other,
                            'conflict_resolution' => $score->conflict_resolution,
                            'class_participation' => $score->class_participation,
                            'problem_solving' => $score->problem_solving,
                            'goal_setting_to_improve_work' => $score->goal_setting_to_improve_work,
                            'strength_weakness_nextstep' => $score->strength_weakness_nextstep,
                            'remarks' => $score->remarks,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                // ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($status);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);

            return view('components.report.semester1')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardSemester2($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);

            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester !== 2) {
                return redirect()->back()->with([
                    'role' => session('role'),
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Invalid Semester',
                        'text' => 'This operation cannot be performed in Semester 1.'
                    ]
                ]);
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('report_cards', 'report_cards.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('report_cards.semester', $semester)
                ->where('report_cards.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'independent_work' => $score->independent_work,
                            'initiative' => $score->initiative,
                            'homework_completion' => $score->homework_completion,
                            'use_of_information' => $score->use_of_information,
                            'cooperation_with_other' => $score->cooperation_with_other,
                            'conflict_resolution' => $score->conflict_resolution,
                            'class_participation' => $score->class_participation,
                            'problem_solving' => $score->problem_solving,
                            'goal_setting_to_improve_work' => $score->goal_setting_to_improve_work,
                            'strength_weakness_nextstep' => $score->strength_weakness_nextstep,
                            'promotion_status' => $score->promotion_status,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                // ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);

            return view('components.report.semester2')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardSemester1Sec($id){
        // dd($id);
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            // dd($semester);

            if ($semester !== 1) {
                return redirect()->back()->with([
                    'role' => session('role'),
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Invalid Semester',
                        'text' => 'This operation cannot be performed in Semester 2.'
                    ]
                ]);
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('report_cards', 'report_cards.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('report_cards.semester', $semester)
                ->where('report_cards.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            // dd($student);

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'independent_work' => $score->independent_work,
                            'initiative' => $score->initiative,
                            'homework_completion' => $score->homework_completion,
                            'use_of_information' => $score->use_of_information,
                            'cooperation_with_other' => $score->cooperation_with_other,
                            'conflict_resolution' => $score->conflict_resolution,
                            'class_participation' => $score->class_participation,
                            'problem_solving' => $score->problem_solving,
                            'goal_setting_to_improve_work' => $score->goal_setting_to_improve_work,
                            'strength_weakness_nextstep' => $score->strength_weakness_nextstep,
                            'remarks' => $score->remarks,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);

            return view('components.report.semester1')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardSemester2Sec($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);

            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester !== 2) {
                return redirect()->back()->with([
                    'role' => session('role'),
                    'swal' => [
                        'type' => 'error',
                        'title' => 'Invalid Semester',
                        'text' => 'This operation cannot be performed in Semester 1.'
                    ]
                ]);
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('report_cards', 'report_cards.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('report_cards.semester', $semester)
                ->where('report_cards.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            // dd($results);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'independent_work' => $score->independent_work,
                            'initiative' => $score->initiative,
                            'homework_completion' => $score->homework_completion,
                            'use_of_information' => $score->use_of_information,
                            'cooperation_with_other' => $score->cooperation_with_other,
                            'conflict_resolution' => $score->conflict_resolution,
                            'class_participation' => $score->class_participation,
                            'problem_solving' => $score->problem_solving,
                            'goal_setting_to_improve_work' => $score->goal_setting_to_improve_work,
                            'strength_weakness_nextstep' => $score->strength_weakness_nextstep,
                            'promotion_status' => $score->promotion_status,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            // dd($scoresByStudent);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            // dd($data);

            return view('components.report.semester2')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardToddler($id){
        // dd($id);
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;

            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            // dd($semester);

            // if ($semester !== 1) {
            //     return redirect()->back()->with([
            //         'role' => session('role'),
            //         'swal' => [
            //             'type' => 'error',
            //             'title' => 'Invalid Semester',
            //             'text' => 'This operation cannot be performed in Semester 2.'
            //         ]
            //     ]);
            // }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('nursery_toddlers', 'nursery_toddlers.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('nursery_toddlers.semester', $semester)
                ->where('nursery_toddlers.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            // dd($results);

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.grade_id', $gradeId)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.grade_id', $gradeId)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($studentMonthlyActivity){
                $student = $scores->first();
                $monthlyActivities = $studentMonthlyActivity->where('student_id', $scores[0]['student_id']);
                
                // Transformasikan aktivitas bulanan menjadi array yang dapat dimasukkan
                $activities = $monthlyActivities->map(function ($activity) {
                    return [
                        'activity_id' => $activity->monthly_activity_id,
                        'activity_name' => $activity->name_activity,
                        'score' => $activity->score, // Misal, jika ada status pada aktivitas
                        'grades' => $activity->grades, // Misal, jika ada status pada aktivitas
                    ];
                })->values()->all();
            
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'remarks' => $student->remarks,
                    'scores' => $scores->map(function ($score) use ($activities) {
                        $scoreData = [
                            'songs' => $score->songs,
                            'prayer' => $score->prayer,
                            'colour' => $score->colour,
                            'number' => $score->number,
                            'object' => $score->object,
                            'body_movement' => $score->body_movement,
                            'colouring' => $score->colouring,
                            'painting' => $score->painting,
                            'chinese_songs' => $score->chinese_songs,
                            'ability_to_recognize_the_objects' => $score->ability_to_recognize_the_objects,
                            'able_to_own_up_to_mistakes' => $score->able_to_own_up_to_mistakes,
                            'takes_care_of_personal_belongings_and_property' => $score->takes_care_of_personal_belongings_and_property,
                            'demonstrates_importance_of_self_control' => $score->demonstrates_importance_of_self_control,
                            'management_emotional_problem_solving' => $score->management_emotional_problem_solving,
                            'promote' => $score->promote,
                        ];
                        
                        foreach ($activities as $activity) {
                            $name = str_replace(' ', '_', trim($activity['activity_name']));
                            $scoreData[$name] = $activity['score']; 
                        }
                
                        return $scoreData;
                    })->all(),
                ];
            })->values()->all();

            // dd($mid);
            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', session('semester'))
                ->where('academic_year', $academic_year)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $monthly = MonthlyActivity::where('grades', '=', 'lower')->get();
            $monthlyTitle = MonthlyActivity::where('grades', '=', 'lower')->pluck('name')->toArray();
            
            foreach ($monthlyTitle as &$title) {
                $title = str_replace(' ', '_', trim($title));
            }
            unset($title); // Hapus referensi untuk menghindari bug

            // dd($monthlyTitle);
            
            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'mid'   => 0,
                'status' => $status,
                'monthly' => $monthly,
                'title' => $monthlyTitle,
                // 'scoreMonthly' => $studentMonthlyActivity,
            ];

            // dd($data);

            return view('components.report.toddler')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardToddlerMid($id){
        // dd($id);
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester == 1) {
                $mid = 0.5;
            }
            elseif ($semester == 2) {
                $mid = 1.5;
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('nursery_toddlers', 'nursery_toddlers.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('nursery_toddlers.semester', $mid)
                ->where('nursery_toddlers.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.grade_id', $gradeId)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($studentMonthlyActivity){
                $student = $scores->first();
                $monthlyActivities = $studentMonthlyActivity->where('student_id', $scores[0]['student_id']);
                
                // Transformasikan aktivitas bulanan menjadi array yang dapat dimasukkan
                $activities = $monthlyActivities->map(function ($activity) {
                    return [
                        'activity_id' => $activity->monthly_activity_id,
                        'activity_name' => $activity->name_activity,
                        'score' => $activity->score, // Misal, jika ada status pada aktivitas
                        'grades' => $activity->grades, // Misal, jika ada status pada aktivitas
                    ];
                })->values()->all();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'remarks' => $student->remarks,
                    'scores' => $scores->map(function ($score) use ($activities) {
                        $scoreData = [
                            'songs' => $score->songs,
                            'prayer' => $score->prayer,
                            'colour' => $score->colour,
                            'number' => $score->number,
                            'object' => $score->object,
                            'body_movement' => $score->body_movement,
                            'colouring' => $score->colouring,
                            'painting' => $score->painting,
                            'chinese_songs' => $score->chinese_songs,
                            'ability_to_recognize_the_objects' => $score->ability_to_recognize_the_objects,
                            'able_to_own_up_to_mistakes' => $score->able_to_own_up_to_mistakes,
                            'takes_care_of_personal_belongings_and_property' => $score->takes_care_of_personal_belongings_and_property,
                            'demonstrates_importance_of_self_control' => $score->demonstrates_importance_of_self_control,
                            'management_emotional_problem_solving' => $score->management_emotional_problem_solving,
                            'promote' => $score->promote,
                        ];
                        
                        foreach ($activities as $activity) {
                            $name = str_replace(' ', '_', trim($activity['activity_name']));
                            $scoreData[$name] = $activity['score']; 
                        }
                
                        return $scoreData;
                    })->all(),
                ];
            })->values()->all();

            // dd($mid);
            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $mid)
                ->where('academic_year', $academic_year)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $monthly = MonthlyActivity::where('grades', '=', 'lower')->get();
            $monthlyTitle = MonthlyActivity::where('grades', '=', 'lower')->pluck('name')->toArray();
            
            foreach ($monthlyTitle as &$title) {
                $title = str_replace(' ', '_', trim($title));
            }
            unset($title); // Hapus referensi untuk menghindari bug

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'mid'   => $mid,
                'status' => $status,
                'monthly' => $monthly,
                'title' => $monthlyTitle,
            ];

            // dd($data['result']);

            return view('components.report.toddler')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardNursery($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('nursery_toddlers', 'nursery_toddlers.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('nursery_toddlers.semester', $semester)
                ->where('nursery_toddlers.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.grade_id', $gradeId)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            // dd($studentMonthlyActivity);

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($studentMonthlyActivity){
                $student = $scores->first();

                $monthlyActivities = $studentMonthlyActivity->where('student_id', $scores[0]['student_id']);
                
                // Transformasikan aktivitas bulanan menjadi array yang dapat dimasukkan
                $activities = $monthlyActivities->map(function ($activity) {
                    return [
                        'activity_id' => $activity->monthly_activity_id,
                        'activity_name' => $activity->name_activity,
                        'score' => $activity->score, // Misal, jika ada status pada aktivitas
                        'grades' => $activity->grades, // Misal, jika ada status pada aktivitas
                    ];
                })->values()->all();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'remarks' => $student->remarks,
                    'scores' => $scores->map(function ($score) use($activities) {
                            $scoreData = [
                                'songs' => $score->songs,
                                'prayer' => $score->prayer,
                                'colour' => $score->colour,
                                'number' => $score->number,
                                'object' => $score->object,
                                'body_movement' => $score->body_movement,
                                'colouring' => $score->colouring,
                                'painting' => $score->painting,
                                'chinese_songs' => $score->chinese_songs,
                                'ability_to_recognize_the_objects' => $score->ability_to_recognize_the_objects,
                                'able_to_own_up_to_mistakes' => $score->able_to_own_up_to_mistakes,
                                'takes_care_of_personal_belongings_and_property' => $score->takes_care_of_personal_belongings_and_property,
                                'demonstrates_importance_of_self_control' => $score->demonstrates_importance_of_self_control,
                                'management_emotional_problem_solving' => $score->management_emotional_problem_solving,
                                'promote' => $score->promote,
                            ];
                            
                            foreach ($activities as $activity) {
                                $name = str_replace(' ', '_', trim($activity['activity_name']));
                                $scoreData[$name] = $activity['score']; 
                            }
                    
                            return $scoreData;

                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', session('semester'))
                ->where('academic_year', $academic_year)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $monthly = MonthlyActivity::where('grades', '=', 'lower')->get();
            $monthlyTitle = MonthlyActivity::where('grades', '=', 'lower')->pluck('name')->toArray();
            
            foreach ($monthlyTitle as &$title) {
                $title = str_replace(' ', '_', trim($title));
            }
            unset($title);

            // dd($scoresByStudent);
            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'mid' => 0,
                'status' => $status,
                'monthly' => $monthly,
                'monthlyTitle' => $monthlyTitle,
                'scoreMonthly' => $studentMonthlyActivity,
            ];

            return view('components.report.nursery')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardNurseryMid($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester == 1) {
                $mid = 0.5;
            }
            elseif ($semester == 2) {
                $mid = 1.5;
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('nursery_toddlers', 'nursery_toddlers.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('nursery_toddlers.semester', $mid)
                ->where('nursery_toddlers.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.grade_id', $gradeId)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use($studentMonthlyActivity){
                $student = $scores->first();

                $monthlyActivities = $studentMonthlyActivity->where('student_id', $scores[0]['student_id']);
                
                // Transformasikan aktivitas bulanan menjadi array yang dapat dimasukkan
                $activities = $monthlyActivities->map(function ($activity) {
                    return [
                        'activity_id' => $activity->monthly_activity_id,
                        'activity_name' => $activity->name_activity,
                        'score' => $activity->score, // Misal, jika ada status pada aktivitas
                        'grades' => $activity->grades, // Misal, jika ada status pada aktivitas
                    ];
                })->values()->all();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'remarks' => $student->remarks,
                    'scores' => $scores->map(function ($score) use($activities) {
                            $scoreData = [
                                'songs' => $score->songs,
                                'prayer' => $score->prayer,
                                'colour' => $score->colour,
                                'number' => $score->number,
                                'object' => $score->object,
                                'body_movement' => $score->body_movement,
                                'colouring' => $score->colouring,
                                'painting' => $score->painting,
                                'chinese_songs' => $score->chinese_songs,
                                'ability_to_recognize_the_objects' => $score->ability_to_recognize_the_objects,
                                'able_to_own_up_to_mistakes' => $score->able_to_own_up_to_mistakes,
                                'takes_care_of_personal_belongings_and_property' => $score->takes_care_of_personal_belongings_and_property,
                                'demonstrates_importance_of_self_control' => $score->demonstrates_importance_of_self_control,
                                'management_emotional_problem_solving' => $score->management_emotional_problem_solving,
                                'promote' => $score->promote,
                            ];
                            
                            foreach ($activities as $activity) {
                                $name = str_replace(' ', '_', trim($activity['activity_name']));
                                $scoreData[$name] = $activity['score']; 
                            }
                    
                            return $scoreData;

                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $mid)
                ->where('academic_year', $academic_year)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $monthly = MonthlyActivity::where('grades', '=', 'lower')->get();
            $monthlyTitle = MonthlyActivity::where('grades', '=', 'lower')->pluck('name')->toArray();
            
            foreach ($monthlyTitle as &$title) {
                $title = str_replace(' ', '_', trim($title));
            }
            unset($title);

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'mid' => $mid,
                'status' => $status,
                'monthly' => $monthly,
                'monthlyTitle' => $monthlyTitle,
            ];

            return view('components.report.nursery')->with('data', $data);
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardKindergarten($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('kindergartens', 'kindergartens.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('kindergartens.semester', $semester)
                ->where('kindergartens.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) {
                $student = $scores->first();
               
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $scores->map(function ($score) {
                        return [
                            'english' => $score->english,
                            'mathematics' => $score->mathematics,
                            'chinese' => $score->chinese,
                            'science' => $score->science,
                            'character_building' => $score->character_building,
                            'art_and_craft' => $score->art_and_craft,
                            'it' => $score->it,
                            'phonic' => $score->phonic,
                            'conduct' => $score->conduct,
                            'remarks' => $score->remarks,
                            'promote' => $score->promote,
                        ];
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'status' => $status,
            ];

            return view('components.report.kindergarten')->with('data', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function cardKindergartenMid($id){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'report class teacher',
            ]);
            
            $gradeId = $id;
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester == 1) {
                $mid = 0.5;
            }
            elseif ($semester == 2) {
                $mid = 1.5;
            }

            $grade = Teacher_grade::join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class', 
                'teachers.name as teacher_name')
                ->where('teacher_grades.grade_id', $gradeId)
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->join('mid_kindergartens', 'mid_kindergartens.student_id', '=', 'students.id')
                ->where('grades.id', $gradeId)
                ->where('mid_kindergartens.semester', $semester)
                ->where('mid_kindergartens.academic_year', $academic_year)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $student = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.grade_id', $gradeId)
                ->where('student_monthly_activities.semester', $mid)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            // dd($semester);
            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($studentMonthlyActivity) {
                $student = $scores->first();
                $monthlyActivities = $studentMonthlyActivity->where('student_id', $scores[0]['student_id']);
                
                // Transformasikan aktivitas bulanan menjadi array yang dapat dimasukkan
                $activities = $monthlyActivities->map(function ($activity) {
                    return [
                        'activity_id' => $activity->monthly_activity_id,
                        'activity_name' => $activity->name_activity,
                        'score' => $activity->score, // Misal, jika ada status pada aktivitas
                        'grades' => $activity->grades, // Misal, jika ada status pada aktivitas
                    ];
                })->values()->all();

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'remarks' => $student->remarks,
                    'scores' => $scores->map(function ($score) use ($activities) {
                        $scoreData = [
                            'brain_gym' => $score->brain_gym,
                            'cursive_writing' => $score->cursive_writing,
                            'dictation' => $score->dictation,
                            'english_language' => $score->english_language,
                            'mandarin_language' => $score->mandarin_language,
                            'writing_skill' => $score->writing_skill,
                            'reading_skill' => $score->reading_skill,
                            'phonic' => $score->phonic,
                            'science' => $score->science,
                            'art_and_craft' => $score->art_and_craft,
                            'character_building' => $score->character_building,
                            'physical_education' => $score->physical_education,
                            'able_to_sit_quietly' => $score->able_to_sit_quietly,
                            'willingness_to_listen' => $score->willingness_to_listen,
                            'willingness_to_work' => $score->willingness_to_work,
                            'willingness_to_sing' => $score->willingness_to_sing,
                        ];
                        
                        foreach ($activities as $activity) {
                            $name = str_replace(' ', '_', trim($activity['activity_name']));
                            $scoreData[$name] = $activity['score']; 
                        }
                
                        return $scoreData;
                    })->all(),
                ];
            })->values()->all();

            $status = Report_card_status::where('grade_id', $grade->grade_id)
                ->where('semester', $mid)
                ->where('academic_year', $academic_year)
                ->where('class_teacher_id', $classTeacher->teacher_id)
                ->first();

            $monthly = MonthlyActivity::where('grades', '=', 'lower')->get();
            $monthlyTitle = MonthlyActivity::where('grades', '=', 'lower')->pluck('name')->toArray();
            
            foreach ($monthlyTitle as &$title) {
                $title = str_replace(' ', '_', trim($title));
            }
            unset($title); // Hapus referensi untuk menghindari bug

            // dd($scoresByStudent);
            $data = [
                'grade' => $grade,
                'students' => $student,
                'result' => $scoresByStudent,
                'classTeacher' => $classTeacher,
                'semester' => $semester,
                'mid' => $mid,
                'status' => $status,
                'monthly' => $monthly,
                'title' => $monthlyTitle,
            ];
            return view('components.report.mid_kindergarten')->with('data', $data);
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function reportCardDecline($gradeId, $teacherId, $semester)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => ' reports',
                'child' => 'report class teacher',
            ]);
            
            Report_card_status::where('grade_id', $gradeId)
                // ->where('class_teacher_id', $teacherId)
                ->where('semester', $semester)
                ->where('academic_year', session('academic_year'))
                ->delete();

            session()->flash('after_decline_report_card');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline Report Card',
                    'text' => 'Succesfully decline Report Card'
                ]
            ]);


        } catch (Exception $err) {
            dd($err);
        }
    }

    public function midreportCardDecline($gradeId, $teacherId, $semester)
    {

        try {
            session()->flash('page',  $page = (object)[
                'page' => ' reports',
                'child' => 'report class teacher',
            ]);

            if($semester == 1){
                $semester = 0.5;
            }
            elseif($semester == 2){
                $semester = 1.5;
            }

            // dd($semester);

            Report_card_status::where('grade_id', $gradeId)
                // ->where('class_teacher_id', $teacherId)
                ->where('semester', $semester)
                ->where('academic_year', session('academic_year'))
                ->delete();

            session()->flash('after_decline_report_card');

            return redirect()->back()->with([
                'role' => session('role'),
                'swal' => [
                    'type' => 'success',
                    'title' => 'Decline Report Card',
                    'text' => 'Succesfully decline Report Card'
                ]
            ]);


        } catch (Exception $err) {
            dd($err);
        }
    }

    private function determineGrade($finalScore)
    {
        $finalScore = round($finalScore);

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

    public function downloadPDFMidSemester($id)
    {
        try {
            $semester = session('semester');
            $academic_year = session('academic_year');

            $learningSkills = Report_card::where('student_id', $id)
                ->where('semester', $semester)
                ->first();

            $gradeId = Student::where('id', $id)->value('grade_id');

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();

            // $remarks = Mid_report::where('mid_reports.student_id', '=', $id)->value('remarks'); 
            $ct = Mid_report::where('mid_reports.student_id', '=', $id)->value('critical_thinking'); 
            $cs = Mid_report::where('mid_reports.student_id', '=', $id)->value('cognitive_skills'); 
            $ls = Mid_report::where('mid_reports.student_id', '=', $id)->value('life_skills'); 
            $les = Mid_report::where('mid_reports.student_id', '=', $id)->value('learning_skills'); 
            $saed = Mid_report::where('mid_reports.student_id', '=', $id)->value('social_and_emotional_development'); 
            $academicYear = Master_academic::first()->value('academic_year');
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalPresent     = $attendances->where('present', 1)->count();
                $totalAlpha       = $attendances->where('alpha', 1)->count();
                $totalSick        = $attendances->where('sick', 1)->count();
                $totalPermission  = $attendances->where('permission', 1)->count();
                $totalLate        = $attendances->where('late', 1)->count();
                $timesLate        = $attendances->whereNotNull('latest')->sum('latest');
                
                return [
                    'present'     => $totalPresent,
                    'days_absent' => $totalAlpha,
                    'sick'        => $totalSick,
                    'permission'  => $totalPermission,
                    'total_late'   => $totalLate,
                    'late'        => $timesLate,
                ];
            })->values()->all();

            $homework  = Type_exam::where('name', 'homework')->value('id');
            $exercise  = Type_exam::where('name', 'exercise')->value('id');
            $quiz      = Type_exam::where('name', 'quiz')->value('id');
            $project   = Type_exam::where('name', 'project')->value('id');
            $practical = Type_exam::where('name', 'practical')->value('id');
                
            if(strtolower($student->grade_name) === "primary"){
                $checkReligion = Student::where('id', $id)->value('religion');

                if ($checkReligion == "Islam") {
                    $religion = "Religion Islamic";
                }
                elseif ($checkReligion == "Catholic Christianity") {
                    $religion = "Religion Catholic";
                }
                elseif ($checkReligion == "Protestant Christianity") {
                    $religion = "Religion Christian";
                }
                elseif ($checkReligion == "Buddhism") {
                    $religion = "Religion Buddhism";
                }
                elseif ($checkReligion == "Hinduism") {
                    $religion = "Religion Hinduism";
                }
                elseif ($checkReligion == "Confucianism") {
                    $religion = "Religion Confucianism";
                }

                if($gradeId == 5 || $gradeId == 6 || $gradeId == 7){
                    $order = [
                        'English',
                        'Chinese',
                        'Mathematics',
                        'Science',
                        $religion,
                        'Bahasa Indonesia',
                        'Character Building',
                        'PE',
                        'IT',
                        'Financial Literacy',
                        'PPKn',
                        'Art and Craft',
                        'Health Education',
                    ];
                }
                else{
                    $order = [
                        'English',
                        'Chinese',
                        'Mathematics',
                        'Science',
                        $religion,
                        'Bahasa Indonesia',
                        'Character Building',
                        'PE',
                        'IT',
                        'Financial Literacy',
                        'PPKn',
                        'Art and Craft',
                    ];
                }


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
                    ->leftJoin('subjects', function($join){
                        $join->on('subjects.id', '=', 'subject_exams.subject_id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                        'subjects.name_subject as subject_name',
                    )
                    ->where('grades.id', $gradeId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('students.id', $id)
                    ->where('students.is_active', true)
                    ->whereIn('exams.type_exam', [$homework, $exercise, $quiz, $project, $practical])
                    ->orderBy('students.name', 'asc')
                    ->get();

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($order, $homework, $exercise, $quiz, $project, $practical) {
                    $student = $scores->first();
                    $scoresBySubject = $scores->groupBy('subject_name')->map(function ($subjectScores) use ($homework, $exercise, $quiz, $project, $practical) {
                
                        $homeworkScores = $subjectScores->where('type_exam', $homework)->pluck('score');
                        $exerciseScores = $subjectScores->where('type_exam', $exercise)->pluck('score');
                        $quizScores = $subjectScores->where('type_exam', $quiz)->pluck('score');
                        $projectScores = $subjectScores->whereIn('type_exam', [$practical, $project])->pluck('score');
                        $practicalScores = $subjectScores->where('type_exam', $practical)->pluck('score');
                
                        return [
                            'subject_name' => $subjectScores->first()->subject_name,
                            'scores' => [
                                'homework' => $homeworkScores->all(),
                                'exercise' => $exerciseScores->all(),
                                'quiz' => $quizScores->all(),
                                'project' => $projectScores->all(),
                                'practical' => $practicalScores->all()
                            ],
                        ];
                    });

                    $isRestricted = collect($scoresBySubject)->contains(function ($subject) {
                        return collect($subject['scores'])->contains(function ($examScores) {
                            return collect($examScores)->contains(function ($score) {
                                return $score !== null && $score <= 70;
                            });
                        });
                    });
                
                    // Urutkan subjek berdasarkan urutan dalam $order
                    $orderedSubjects = collect($order)->mapWithKeys(function ($subject) use ($scoresBySubject) {
                        return [$subject => $scoresBySubject->get($subject, ['subject_name' => $subject, 'scores' => []])];
                    });
                
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'subjects' => $orderedSubjects->values()->all(),
                        'isRestricted' => $isRestricted,
                    ];
                })->values()->all();

            } 
            elseif (strtolower($student->grade_name) === "secondary") {
                $chineseLower  = Chinese_lower::where('student_id', $id)->exists();
                $chineseHigher = Chinese_higher::where('student_id', $id)->exists();

                $checkReligion = Student::where('id', $id)->value('religion');

                if ($checkReligion == "Islam") {
                    $religion = "Religion Islamic";
                }
                elseif ($checkReligion == "Catholic Christianity") {
                    $religion = "Religion Catholic";
                }
                elseif ($checkReligion == "Protestant Christianity") {
                    $religion = "Religion Christian";
                }
                elseif ($checkReligion == "Buddhism") {
                    $religion = "Religion Buddhism";
                }
                elseif ($checkReligion == "Hinduism") {
                    $religion = "Religion Hinduism";
                }
                elseif ($checkReligion == "Confucianism") {
                    $religion = "Religion Confucianism";
                }
                // dd($chineseHigher);
            
                if ($chineseLower) {
                    $chinese = "Chinese Lower";
                }
                elseif ($chineseHigher) {
                    $chinese = "Chinese Higher";
                }
                
                $order = [
                    'English',
                    $chinese,
                    'Mathematics',
                    'Science',
                    $religion,
                    'Bahasa Indonesia',
                    'Character Building',
                    'PE',
                    'IT',
                    'Financial Literacy',
                    'Art and Design',
                    'PPKn',
                    'IPS',
                ];

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
                    ->leftJoin('subjects', function($join){
                        $join->on('subjects.id', '=', 'subject_exams.subject_id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                        'subjects.name_subject as subject_name',
                    )
                    ->where('grades.id', $gradeId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('students.id', $id)
                    ->whereIn('exams.type_exam', [$homework, $exercise, $quiz, $project, $practical])
                    ->where('students.is_active', true)
                    ->orderBy('students.name', 'asc')
                    ->get();

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($order, $homework, $exercise, $quiz, $project, $practical) {
                    $student = $scores->first();
                    $scoresBySubject = $scores->groupBy('subject_name')->map(function ($subjectScores) use ($homework, $exercise, $quiz, $project, $practical) {
                
                        $homeworkScores = $subjectScores->where('type_exam', $homework)->pluck('score');
                        $exerciseScores = $subjectScores->where('type_exam', $exercise)->pluck('score');
                        $quizScores = $subjectScores->where('type_exam', $quiz)->pluck('score');
                        $projectScores = $subjectScores->whereIn('type_exam', [$practical, $project])->pluck('score');
                        $practicalScores = $subjectScores->where('type_exam', $practical)->pluck('score');
                
                        return [
                            'subject_name' => $subjectScores->first()->subject_name,
                            'scores' => [
                                'homework' => $homeworkScores->all(),
                                'exercise' => $exerciseScores->all(),
                                'quiz' => $quizScores->all(),
                                'project' => $projectScores->all(),
                                'practical' => $practicalScores->all()
                            ],
                        ];
                    });

                    $isRestricted = collect($scoresBySubject)->contains(function ($subject) {
                        return collect($subject['scores'])->contains(function ($examScores) {
                            return collect($examScores)->contains(function ($score) {
                                return $score !== null && $score <= 70;
                            });
                        });
                    });
                
                    // Urutkan subjek berdasarkan urutan dalam $order
                    $orderedSubjects = collect($order)->mapWithKeys(function ($subject) use ($scoresBySubject) {
                        return [$subject => $scoresBySubject->get($subject, ['subject_name' => $subject, 'scores' => []])];
                    });
                
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'subjects' => $orderedSubjects->values()->all(),
                        'isRestricted' => $isRestricted,
                    ];
                })->values()->all();
            }

            $monthlyActivity = MonthlyActivity::where('grades', '=', 'upper')->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.student_id', $id)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->orderBy('students.name', 'asc')
                ->get();

            $data = [
                'semester'      => $semester,
                'student'       => $student,
                'classTeacher'  => $classTeacher,
                'subjectReports'=> $scoresByStudent,
                'attendance'    => $attendancesByStudent,
                'academicYear'  => $academicYear,
                'homework'      => $homework,
                'exercise'      => $exercise,
                'quiz'          => $quiz,
                'project'       => $project,
                'practical'     => $practical,
                'ct'            => $ct,
                'cs'            => $cs,
                'ls'            => $ls,
                'les'           => $les,
                'saed'          => $saed,
                'monthlyAct'    => $monthlyActivity,
                'countMA'       => count($monthlyActivity),
                'scoreMonthly'  => $studentMonthlyActivity,
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.mid_semester-pdf', $data)->setPaper('a5', 'portrait');
            return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');
            // return view('components.report.pdf.mid_semester-pdf', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }
    

    public function downloadPDFSemester1($id)
    {
        try {
            $semester = session('semester');
            $academic_year = session('academic_year');

            $learningSkills = Report_card::where('student_id', $id)
                ->where('semester', $semester)
                ->first();

            $gradeId = Student::where('id', $id)->value('grade_id');
            $gradeSerial = Grade::where('id', $gradeId)->value('class');
            
            if (session('semester') == 1) {
                $getSerial = (2 * $gradeSerial) - 1; // Semester 1
            } elseif (session('semester') == 2) {
                $getSerial = 2 * $gradeSerial; // Semester 2
            } else {
                $getSerial = '-'; // Default jika semester tidak valid
            }

            switch ($gradeId) {
                case "5":
                case "11":
                    $date_of_registration = "June 22, 2024";
                    break;
            
                case "6":
                case "12":
                    $date_of_registration = "July 24, 2023";
                    break;
            
                case "7":
                case "13":
                    $date_of_registration = "July 10, 2022";
                    break;
            
                case "8":
                    $date_of_registration = "July 12, 2021";
                    break;
            
                case "9":
                    $date_of_registration = "July 10, 2020";
                    break;
            
                case "10":
                    $date_of_registration = "July 18, 2019";
                    break;
            
                default:
                    $date_of_registration = "Unknown date"; // Nilai default jika $gradeId tidak sesuai
                    break;
            }
        
            $date = Master_academic::where('is_use', TRUE)->value('report_card1');

            // $serial = Grade::join('students', 'students.grade_id', '=', 'grades.id')
            //     ->where('grades.id', $gradeId)
            //     ->orderBy('students.name', 'asc')
            //     ->select('students.id', 'students.name', 'grades.id as grade_id')
            //     ->get();

            // Tambahkan nomor urut ke setiap siswa
            // $serial->each(function($serial, $index) {
            //     $serial->serial_number = $index + 1; // Nomor urut mulai dari 1
            // });

            // foreach ($serial as $student) {
            //     if ($student->id == $id) {
            //         $getSerial = $student->serial_number;
            //         break;
            //     }
            // }
            // Hitung serial berdasarkan primary dan semester
            

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 'students.id as student_id',
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();

            $relation = Student_relationship::where('student_relations.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relations.relation_id')
                ->select('relationships.name as relationship_name')
                ->first();
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

                $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
            
                    $totalAlpha = $attendances->where('alpha', 1)->count();
                    $totalPermission = $attendances->where('permission', 1)->count();
                    $totalAbsent = $totalAlpha + $totalPermission;
                    $totalLate = $attendances->where('late', 1)->count();
                    $timesLate = $attendances->whereNotNull('latest')->sum('latest');
                    
                    return [
                        'days_absent' => $totalAbsent,
                        'total_late' => $totalLate,
                        'times_late' => $timesLate,
                    ];
                })->values()->all();

            $resultsScore = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->leftJoin('subjects', function ($join) {
                    $join->on('subjects.id', '=', 'acars.subject_id')
                    ->select('subjects.name');
                })
                ->where('acars.student_id', $id)
                ->where('acars.semester', $semester)
                ->where('acars.academic_year', $academic_year)
                ->get();

            // dd($resultsScore);
                
            if(strtolower($student->grade_name) === "primary")
            {
                if($student->grade_id === 5 || $student->grade_id === 6){
                    $order = [
                        'Religion',
                        'PPKn',
                        'Character Building',
                        'Bahasa Indonesia',
                        'Mathematics',
                        'Science',
                        'Financial Literacy',
                        'Art and Craft',
                        'PE',
                        'Health Education',
                        'IT',
                        'English',
                        'Chinese'
                    ];
                }
                else{
                    $order = [
                        'Religion',
                        'PPKn',
                        'Character Building',
                        'Bahasa Indonesia',
                        'Mathematics',
                        'Science',
                        'Financial Literacy',
                        'Art and Craft',
                        'PE',
                        'IT',
                        'English',
                        'Chinese'
                    ];
                }

                $ecaData = Student_eca::where('student_ecas.student_id', $id)
                    ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
                    ->get(['student_ecas.student_id', 'ecas.name as eca_name']);

                $groupedEcaData = [];
                $counter = 1;

                foreach ($ecaData as $eca) {
                    $groupedEcaData['student_id'] = $eca->student_id;
                    $groupedEcaData['eca_' . $counter] = $eca->eca_name;
                    $counter++;
                }

            } elseif (strtolower($student->grade_name) === "secondary") {
                $order = [
                    'Religion',
                    'PPKn',
                    'Character Building',
                    'Bahasa Indonesia',
                    'Mathematics',
                    'Science',
                    'Financial Literacy',
                    'IPS',
                    'Art and Design',
                    'PE',
                    'IT',
                    'English',
                    'Chinese',
                ];

                $ecaData = Student_eca::where('student_ecas.student_id', $id)
                ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
                ->get(['student_ecas.student_id', 'ecas.name as eca_name']);

                $groupedEcaData = [];
                $counter = 1;

                foreach ($ecaData as $eca) {
                    $groupedEcaData['student_id'] = $eca->student_id;
                    $groupedEcaData['eca_' . $counter] = $eca->eca_name;
                    $counter++;
                }
            }

            $comments = comment::where('student_id', $id)
                ->get()
                ->keyBy('student_id');


            $scoresByStudent = $resultsScore->groupBy('student_id')->map(function ($scores) use ($comments, $order) {
                $student = $scores->first();
            
                // Initialize an array for each subject in the order
                $sortedScores = collect($order)->map(function($subject) use ($scores) {
                    // Find the score for this subject
                    $score = $scores->firstWhere('name_subject', $subject);
                    
                    // Return the score details or empty values if not found
                    return [
                        'subject_name' => $subject,
                        'subject_id' => $score ? $score->subject_id : null,
                        'final_score' => $score ? $score->final_score : null,
                        'grades' => $score ? $score->grades : null,
                        'comment' => $score ? $score->comment : null,
                        'isChinese' => $this->containsChinese($score ? $score->comment : null),
                    ];
                });
            
                $isRestricted = $sortedScores->contains(function ($score) {
                    return $score['final_score'] !== null && $score['final_score'] < 70;
                    return $score['final_score'] !== null && $score['final_score'] < 70;
                });
            
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $sortedScores->all(),
                    'isRestricted' => $isRestricted, // Include the restricted flag in the output
                ];
            })->values()->all();
            
            if (strtolower($student->grade_name) === "primary") {
                $resultsSooa = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                    ->where('sooa_primaries.student_id', $id)
                    ->where('sooa_primaries.semester', $semester)
                    ->where('sooa_primaries.academic_year', $academic_year)
                    ->get();

                $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
        
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'ranking' => $student->ranking,
                        'scores' => $scores->map(function ($score) {
                            return [
                                'academic' => $score->academic,
                                'grades_academic' => $score->grades_academic,
                                'choice' => $score->choice,
                                'grades_choice' => $score->grades_choice,
                                'language_and_art' => $score->language_and_art,
                                'grades_language_and_art' => $score->grades_language_and_art,
                                'self_development' => $score->self_development,
                                'grades_self_development' => $score->grades_self_development,
                                'eca_aver' => $score->eca_aver,
                                'grades_eca_aver' => $score->grades_eca_aver,
                                'behavior' => $score->behavior,
                                'grades_behavior' => $score->grades_behavior,
                                'attendance' => $score->attendance,
                                'grades_attendance' => $score->grades_attendance,
                                'participation' => $score->participation,
                                'grades_participation' => $score->grades_participation,
                                'final_score' => $score->final_score,
                                'grades_final_score' => $score->grades_final_score,
                            ];
                        })->all(),
                    ];
                })->values()->all();
            }
            elseif (strtolower($student->grade_name) === "secondary") {
                $resultsSooa = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                    ->where('sooa_secondaries.student_id', $id)
                    ->where('sooa_secondaries.semester', $semester)
                    ->where('sooa_secondaries.academic_year', $academic_year)
                    ->get();

                $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
        
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'ranking' => $student->ranking,
                        'scores' => $scores->map(function ($score) {
                            return [
                                'academic' => $score->academic,
                                'grades_academic' => $score->grades_academic,
                                'eca_1' => $score->eca_1 == 0 ? "-" : $score->eca_1,
                                'grades_eca_1' => $score->grades_eca_1,
                                'eca_2' => $score->eca_2 == 0 ? "-" : $score->eca_2,
                                'grades_eca_2' => $score->grades_eca_2,
                                'self_development' => $score->self_development,
                                'grades_self_development' => $score->grades_self_development,
                                'eca_aver' => $score->eca_aver,
                                'grades_eca_aver' => $score->grades_eca_aver,
                                'behavior' => $score->behavior,
                                'grades_behavior' => $score->grades_behavior,
                                'attendance' => $score->attendance,
                                'grades_attendance' => $score->grades_attendance,
                                'participation' => $score->participation,
                                'grades_participation' => $score->grades_participation,
                                'final_score' => $score->final_score,
                                'grades_final_score' => $score->grades_final_score,
                            ];
                        })->all(),
                    ];
                })->values()->all();
            }

            $student->date_of_registration = Carbon::parse($student->date_of_registration);

            $academicYear = Master_academic::first()->value('academic_year');

            $remarks = Acar_comment::where('student_id', $id)->value('comment');

            $data = [
                'student' => $student,
                'classTeacher' => $classTeacher,
                'learningSkills' => $learningSkills,
                'subjectReports' => $scoresByStudent,
                'sooa' => $scoresByStudentSooa,
                'attendance' => $attendancesByStudent,
                'relation' => $relation,
                'serial' => $getSerial,
                'academicYear' => $academicYear,
                'eca' => $groupedEcaData,
                'remarks' => $remarks,
                'date' => $date,
                'date_of_registration' => $date_of_registration,
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.semester1-pdf', $data)->setPaper('a5', 'portrait');
            return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');
            
            // return view('components.report.pdf.semester1-pdf', $data);
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function downloadPDFSemester2($id)
    {
        try {
            $semester = session('semester');
            $academic_year = session('academic_year');

            $learningSkills = Report_card::where('student_id', $id)
                ->where('semester', $semester)
                ->first();
    
            $date = Master_academic::where('is_use', TRUE)->value('report_card2');

            $gradeId = Student::where('id', $id)->value('grade_id');
            $gradeSerial = Grade::where('id', $gradeId)->value('class');
            
            if (session('semester') == 1) {
                $getSerial = (2 * $gradeSerial) - 1; // Semester 1
            } elseif (session('semester') == 2) {
                $getSerial = 2 * $gradeSerial; // Semester 2
            } else {
                $getSerial = '-'; // Default jika semester tidak valid
            }
            
            if ($learningSkills->promotion_status === 1 || $learningSkills->promotion_status === 2) {
                $nextGrade = Grade::where('id', $gradeId+1)
                ->select('grades.name as grade_name', 'grades.class as grade_class')
                ->first();
                $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
            } 
            elseif ($learningSkills->promotion_status === 3) {
                $nextGrade =  $nextGrade = Grade::where('id', $gradeId)
                ->select('grades.name as grade_name', 'grades.class as grade_class')
                ->first();
                $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
            }

            switch ($gradeId) {
                case "5":
                case "11":
                    $date_of_registration = "June 22, 2024";
                    break;
            
                case "6":
                case "12":
                    $date_of_registration = "July 24, 2023";
                    break;
            
                case "7":
                case "13":
                    $date_of_registration = "July 10, 2022";
                    break;
            
                case "8":
                    $date_of_registration = "July 12, 2021";
                    break;
            
                case "9":
                    $date_of_registration = "July 10, 2020";
                    break;
            
                case "10":
                    $date_of_registration = "July 18, 2019";
                    break;
            
                default:
                    $date_of_registration = "Unknown date"; // Nilai default jika $gradeId tidak sesuai
                    break;
            }
            

            // $serial = Grade::join('students', 'students.grade_id', '=', 'grades.id')
            //     ->where('grades.id', $gradeId)
            //     ->orderBy('students.name', 'asc')
            //     ->select('students.id', 'students.name', 'grades.id as grade_id')
            //     ->get();

            // Tambahkan nomor urut ke setiap siswa
            // $serial->each(function($serial, $index) {
            //     $serial->serial_number = $index + 1; // Nomor urut mulai dari 1
            // });

            // foreach ($serial as $student) {
            //     if ($student->id == $id) {
            //         $getSerial = $student->serial_number;
            //         break;
            //     }
            // }

            $ecaData = Student_eca::where('student_ecas.student_id', $id)
                ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
                ->get(['student_ecas.student_id', 'ecas.name as eca_name']);

            $groupedEcaData = [];
            $counter = 1;

            foreach ($ecaData as $eca) {
                $groupedEcaData['student_id'] = $eca->student_id;
                $groupedEcaData['eca_' . $counter] = $eca->eca_name;
                $counter++;
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 'students.id as student_id',
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();

            $relation = Student_relationship::where('student_relations.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relations.relation_id')
                ->select('relationships.name as relationship_name')
                ->first();
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

                $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
            
                    $totalAlpha = $attendances->where('alpha', 1)->count();
                    $totalPermission = $attendances->where('permission', 1)->count();
                    $totalAbsent = $totalAlpha + $totalPermission;
                    $totalLate = $attendances->where('late', 1)->count();
                    $timesLate = $attendances->whereNotNull('latest')->sum('latest');
                    
                    return [
                        'days_absent' => $totalAbsent,
                        'total_late' => $totalLate,
                        'times_late' => $timesLate,
                    ];
                })->values()->all();

            $comments = comment::where('student_id', $id)
                ->get()
                ->keyBy('student_id');

            $resultsScore = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->leftJoin('subjects', function ($join) {
                    $join->on('subjects.id', '=', 'acars.subject_id')
                    ->select('subjects.name');
                })
                ->where('acars.student_id', $id)
                ->where('acars.semester', $semester)
                ->where('acars.academic_year', $academic_year)
                ->get();
                
                if(strtolower($student->grade_name) === "primary")
                {
                    if($student->grade_id === 5 || $student->grade_id === 6){
                        $order = [
                            'Religion',
                            'PPKn',
                            'Character Building',
                            'Bahasa Indonesia',
                            'Mathematics',
                            'Science',
                            'Financial Literacy',
                            'Art and Craft',
                            'PE',
                            'Health Education',
                            'IT',
                            'English',
                            'Chinese'
                        ];
                    }
                    else{
                        $order = [
                            'Religion',
                            'PPKn',
                            'Character Building',
                            'Bahasa Indonesia',
                            'Mathematics',
                            'Science',
                            'Financial Literacy',
                            'Art and Craft',
                            'PE',
                            'IT',
                            'English',
                            'Chinese'
                        ];
                    }

                } elseif (strtolower($student->grade_name) === "secondary") {
                    $order = [
                        'Religion',
                        'PPKn',
                        'Character Building',
                        'Bahasa Indonesia',
                        'Mathematics',
                        'Science',
                        'Financial Literacy',
                        'IPS',
                        'Art and Design',
                        'PE',
                        'IT',
                        'English',
                        'Chinese',
                    ];
                }

                $scoresByStudent = $resultsScore->groupBy('student_id')->map(function ($scores) use ($comments, $order) {
                    $student = $scores->first();
                
                    // Initialize an array for each subject in the order
                    $sortedScores = collect($order)->map(function($subject) use ($scores) {
                        // Find the score for this subject
                        $score = $scores->firstWhere('name_subject', $subject);
                
                        // Return the score details or empty values if not found
                        return [
                            'subject_name' => $subject,
                            'subject_id' => $score ? $score->subject_id : null,
                            'final_score' => $score ? $score->final_score : null,
                            'grades' => $score ? $score->grades : null,
                            'comment' => $score ? $score->comment : null,
                            'isChinese' => $this->containsChinese($score ? $score->comment : null),
                        ];
                    });
                
                    $isRestricted = $sortedScores->contains(function ($score) {
                        return $score['final_score'] !== null && $score['final_score'] < 70;
                    });
                
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'scores' => $sortedScores->all(),
                        'isRestricted' => $isRestricted, // Include the restricted flag in the output
                    ];
                })->values()->all();

            if (strtolower($student->grade_name) === "primary") {
                $resultsSooa = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                    ->where('sooa_primaries.student_id', $id)
                    ->where('sooa_primaries.semester', $semester)
                    ->where('sooa_primaries.academic_year', $academic_year)
                    ->get();

                    $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                        $student = $scores->first();
            
                        return [
                            'student_id' => $student->student_id,
                            'student_name' => $student->name,
                            'ranking' => $student->ranking,
                            'scores' => $scores->map(function ($score) {
                                return [
                                    'academic' => $score->academic,
                                    'grades_academic' => $score->grades_academic,
                                    'choice' => $score->choice,
                                    'grades_choice' => $score->grades_choice,
                                    'language_and_art' => $score->language_and_art,
                                    'grades_language_and_art' => $score->grades_language_and_art,
                                    'self_development' => $score->self_development,
                                    'grades_self_development' => $score->grades_self_development,
                                    'eca_aver' => $score->eca_aver,
                                    'grades_eca_aver' => $score->grades_eca_aver,
                                    'behavior' => $score->behavior,
                                    'grades_behavior' => $score->grades_behavior,
                                    'attendance' => $score->attendance,
                                    'grades_attendance' => $score->grades_attendance,
                                    'participation' => $score->participation,
                                    'grades_participation' => $score->grades_participation,
                                    'final_score' => $score->final_score,
                                    'grades_final_score' => $score->grades_final_score,
                                ];
                            })->all(),
                        ];
                    })->values()->all();
            }
            elseif (strtolower($student->grade_name) === "secondary") {
                $resultsSooa = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                    ->where('sooa_secondaries.student_id', $id)
                    ->where('sooa_secondaries.semester', $semester)
                    ->where('sooa_secondaries.academic_year', $academic_year)
                    ->get();

                    $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                        $student = $scores->first();
            
                        return [
                            'student_id' => $student->student_id,
                            'student_name' => $student->name,
                            'ranking' => $student->ranking,
                            'scores' => $scores->map(function ($score) {
                                return [
                                    'academic' => $score->academic,
                                    'grades_academic' => $score->grades_academic,
                                    'eca_1' => $score->eca_1,
                                    'grades_eca_1' => $score->grades_eca_1,
                                    'eca_2' => $score->grades_eca_2,
                                    'grades_eca_2' => $score->grades_eca_2,
                                    'self_development' => $score->self_development,
                                    'grades_self_development' => $score->grades_self_development,
                                    'eca_aver' => $score->eca_aver,
                                    'grades_eca_aver' => $score->grades_eca_aver,
                                    'behavior' => $score->behavior,
                                    'grades_behavior' => $score->grades_behavior,
                                    'attendance' => $score->attendance,
                                    'grades_attendance' => $score->grades_attendance,
                                    'participation' => $score->participation,
                                    'grades_participation' => $score->grades_participation,
                                    'final_score' => $score->final_score,
                                    'grades_final_score' => $score->grades_final_score,
                                ];
                            })->all(),
                        ];
                    })->values()->all();
            }

            $tcop = Tcop::where('student_id', $id)
                ->where('academic_year', $academic_year)
                ->select('tcops.final_score as final_score', 'tcops.grades_final_score as grades_final_score')
                ->get();

            $academicYear = Master_academic::first()->value('academic_year');

            $data = [
                'student' => $student,
                'classTeacher' => $classTeacher,
                'learningSkills' => $learningSkills,
                'subjectReports' => $scoresByStudent,
                'sooa' => $scoresByStudentSooa,
                'attendance' => $attendancesByStudent,
                'relation' => $relation,
                'serial' => $getSerial,
                'promotionGrade' => $grade,
                'academicYear' => $academicYear,
                'eca' => $groupedEcaData,
                'tcop' => $tcop,
                'date' => $date,
                'date_of_registration' => $date_of_registration,
            ];

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.semester2-pdf', $data)->setPaper('a5', 'portrait');
            return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function downloadPDFToddler($id)
    {
        try {
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            $score = Nursery_toddler::where('student_id', $id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->first();
    
            $gradeId = Student::where('id', $id)->value('grade_id');

            if ($semester == 2) {
                if ($score->promote === 1) {
                    $nextGrade = Grade::where('id', $gradeId+1)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                } 
                elseif ($score->promote === null) {
                    $nextGrade =  $nextGrade = Grade::where('id', $gradeId)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                }
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            $monthlyActivity = MonthlyActivity::where('grades', '=', 'lower')->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.student_id', $id)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalSick = $attendances->where('sick', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                
                return [
                    'days_absent' => $totalAlpha,
                    'sick' => $totalSick,
                    'permission' => $totalPermission,
                ];
            })->values()->all();

            $academicYear = Master_academic::first()->value('academic_year');

            if ($semester == 1) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'semester' => $semester,
                    'mid' => 0,
                    'academicYear' => $academicYear,
                    'monthlyAct'    => $monthlyActivity,
                    'countMA'       => count($monthlyActivity),
                    'scoreMonthly'  => $studentMonthlyActivity,
                ];
            } 
            elseif ($semester == 2) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'promotionGrade' => $grade,
                    'semester' => $semester,
                    'mid' => 0,
                    'academicYear' => $academicYear,
                    'monthlyAct'    => $monthlyActivity,
                    'countMA'       => count($monthlyActivity),
                    'scoreMonthly'  => $studentMonthlyActivity,
                ];
            }

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.toddler-pdf', $data)->setPaper('a5', 'portrait');
            return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function downloadPDFToddlerMid($id)
    {
        try {
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester == 1) {
                $mid = 0.5;
            }
            elseif ($semester == 2) {
                $mid = 1.5;
            }

            $score = Nursery_toddler::where('student_id', $id)
                ->where('semester', $mid)
                ->where('academic_year', $academic_year)
                ->first();
    
            $gradeId = Student::where('id', $id)->value('grade_id');

            if ($semester == 2) {
                if ($score->promote === 1) {
                    $nextGrade = Grade::where('id', $gradeId+1)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                } 
                elseif ($score->promote === null) {
                    $nextGrade =  $nextGrade = Grade::where('id', $gradeId)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                }
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalSick = $attendances->where('sick', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                
                return [
                    'days_absent' => $totalAlpha,
                    'sick' => $totalSick,
                    'permission' => $totalPermission,
                ];
            })->values()->all();

            $academicYear = Master_academic::first()->value('academic_year');

            $monthlyActivity = MonthlyActivity::where('grades', '=', 'lower')->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.student_id', $id)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            if ($semester == 1) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'semester' => $semester,
                    'mid' => $mid,
                    'academicYear' => $academicYear,
                    'monthlyAct'    => $monthlyActivity,
                    'countMA'       => count($monthlyActivity),
                    'scoreMonthly'  => $studentMonthlyActivity,
                ];
            } 
            elseif ($semester == 2) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'promotionGrade' => $grade,
                    'semester' => $semester,
                    'mid' => $mid,
                    'academicYear' => $academicYear,
                    'monthlyAct'    => $monthlyActivity,
                    'countMA'       => count($monthlyActivity),
                    'scoreMonthly'  => $studentMonthlyActivity,
                ];
            }

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.toddler-pdf', $data)->setPaper('a5', 'portrait');
            return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function downloadPDFNursery($id)
    {
        try {
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            $score = Nursery_toddler::where('student_id', $id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->first();
    
            $gradeId = Student::where('id', $id)->value('grade_id');

            if ($semester == 2) {
                if ($score->promote === 1) {
                    $nextGrade = Grade::where('id', $gradeId+1)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                } 
                elseif ($score->promote === null) {
                    $nextGrade =  $nextGrade = Grade::where('id', $gradeId)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                }
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();
            
            $relation = Student_relationship::where('student_relations.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relations.relation_id')
                ->select('relationships.name as relationship_name')
                ->first();

            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalSick = $attendances->where('sick', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                
                return [
                    'days_absent' => $totalAlpha,
                    'sick' => $totalSick,
                    'permission' => $totalPermission,
                ];
            })->values()->all();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.grade_id', $gradeId)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->orderBy('student_monthly_activities.created_at', 'desc')
                ->take(3)
                ->get();

            // dd($studentMonthlyActivity);

            $academicYear = Master_academic::first()->value('academic_year');

            if ($semester == 1) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'relation' => $relation,
                    'semester' => $semester,
                    'mid' => 0,
                    'academicYear' => $academicYear,
                    'scoreMonthly' => $studentMonthlyActivity,
                ];
            } 
            elseif ($semester == 2) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'promotionGrade' => $grade,
                    'relation' => $relation,
                    'semester' => $semester,
                    'mid' => 0,
                    'academicYear' => $academicYear,
                    'scoreMonthly' => $studentMonthlyActivity,
                ];
            }

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.nursery-pdf', $data)->setPaper('a5', 'portrait');

            return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function downloadPDFNurseryMid($id)
    {
        try {
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester == 1) {
                $mid = 0.5;
            }
            elseif ($semester == 2) {
                $mid = 1.5;
            }

            $score = Nursery_toddler::where('student_id', $id)
                ->where('semester', $mid)
                ->where('academic_year', $academic_year)
                ->first();
    
            $gradeId = Student::where('id', $id)->value('grade_id');

            if ($semester == 2) {
                if ($score->promote === 1) {
                    $nextGrade = Grade::where('id', $gradeId+1)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                } 
                elseif ($score->promote === null) {
                    $nextGrade =  $nextGrade = Grade::where('id', $gradeId)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                }
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();
            
            $relation = Student_relationship::where('student_relations.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relations.relation_id')
                ->select('relationships.name as relationship_name')
                ->first();

            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalSick = $attendances->where('sick', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                
                return [
                    'days_absent' => $totalAlpha,
                    'sick' => $totalSick,
                    'permission' => $totalPermission,
                ];
            })->values()->all();

            $academicYear = Master_academic::first()->value('academic_year');

            $monthlyActivity = MonthlyActivity::where('grades', '=', 'lower')->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.student_id', $id)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            if ($semester == 1) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'relation' => $relation,
                    'semester' => $semester,
                    'mid' => $mid,
                    'academicYear' => $academicYear,
                    'monthlyAct'    => $monthlyActivity,
                    'countMA'       => count($monthlyActivity),
                    'scoreMonthly'  => $studentMonthlyActivity,
                ];
            } 
            elseif ($semester == 2) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'promotionGrade' => $grade,
                    'relation' => $relation,
                    'semester' => $semester,
                    'mid' => $mid,
                    'academicYear' => $academicYear,
                    'monthlyAct'    => $monthlyActivity,
                    'countMA'       => count($monthlyActivity),
                    'scoreMonthly'  => $studentMonthlyActivity,
                ];
            }


            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.nursery-pdf', $data)->setPaper('a5', 'portrait');
            return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function downloadPDFKindergartenMid($id)
    {
        try {
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            if ($semester == 1) {
                $mid = 0.5;
            }
            elseif ($semester == 2) {
                $mid = 1.5;
            }

            $score = Mid_kindergarten::where('student_id', $id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->first();
    
            $gradeId = Student::where('id', $id)->value('grade_id');

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();
            
            $relation = Student_relationship::where('student_relations.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relations.relation_id')
                ->select('relationships.name as relationship_name')
                ->first();

            $exercise       = Type_exam::where('name', 'exercise')->value('id');
            $quiz           = Type_exam::where('name', 'quiz')->value('id');
            $participation  = Type_exam::where('name', 'participation')->value('id');

            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            // dd($resultsAttendance);

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAttendances =  $attendances->where('present', 1)->count();
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalSick = $attendances->where('sick', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                
                return [
                    'attendance' => $totalAttendances,
                    'days_absent' => $totalAlpha,
                    'sick' => $totalSick,
                    'permission' => $totalPermission,
                ];
            })->values()->all();

            $academicYear = Master_academic::first()->value('academic_year');

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
                ->leftJoin('subjects', function($join){
                    $join->on('subjects.id', '=', 'subject_exams.subject_id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'exams.id as exam_id',
                    'exams.type_exam as type_exam',
                    'scores.score as score',
                    'subjects.name_subject as subject_name',
                )
                ->where('grades.id', $gradeId)
                ->where('exams.semester', $semester)
                ->where('exams.academic_year', $academic_year)
                ->where('students.id', $id)
                ->whereIn('exams.type_exam', [$exercise, $quiz])
                ->where('students.is_active', true)
                ->orderBy('students.name', 'asc')
                ->get();

            $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($exercise, $quiz) {

                $student = $scores->first();
                $scoresBySubject = $scores->groupBy('subject_name')->map(function ($subjectScores) use ($exercise, $quiz) {
            
                    $exercise = $subjectScores->where('type_exam', $exercise)->pluck('score');
                    $quiz = $subjectScores->where('type_exam', $quiz)->pluck('score');
            
                    return [
                        'subject_name' => $subjectScores->first()->subject_name,
                        'scores' => $subjectScores->map(function ($score) {
                            return [
                                'exam_id' => $score->exam_id,
                                'type_exam' => $score->type_exam,
                                'score' => $score->score,
                            ];
                        })->all(),
                    ];
                });
            
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'subjects' => $scoresBySubject->values()->all(),
                ];
            })->values()->all();

            $subjects = [
                ['name' => 'Brain Gym', 'field' => 'brain_gym'],
                ['name' => 'Cursive Writing', 'field' => 'cursive_writing'],
                ['name' => 'Dictation', 'field' => 'dictation'],
                ['name' => 'English Language', 'field' => 'english_language'],
                ['name' => 'Mandarin Language', 'field' => 'mandarin_language'],
                ['name' => 'Writing Skills', 'field' => 'writing_skill'],
                ['name' => 'Reading Skills', 'field' => 'reading_skill'],
                ['name' => 'Phonics', 'field' => 'phonic'],
                // ['name' => 'Science', 'field' => 'science'],
                ['name' => 'Character Building', 'field' => 'character_building'],
                ['name' => 'Art and Craft', 'field' => 'art_and_craft'],
                ['name' => 'Physical Education', 'field' => 'physical_education'],
                ['name' => 'Able to sit quietly', 'field' => 'able_to_sit_quietly'],
                ['name' => 'Willingness to listen', 'field' => 'willingness_to_listen'],
                ['name' => 'Willingness to work', 'field' => 'willingness_to_work'],
                ['name' => 'Willingness to sing', 'field' => 'willingness_to_sing'],
            ];

            $monthlyActivity = MonthlyActivity::where('grades', '=', 'lower')->get();

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.student_id', $id)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->get();

            // dd($studentMonthlyActivity);

            if ($semester == 1) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'relation' => $relation,
                    'semester' => $semester,
                    'mid' => $mid,
                    'result' => $scoresByStudent,
                    'academicYear' => $academicYear,
                    'subjects' => $subjects,
                    'monthlyAct'    => $monthlyActivity,
                    'countMA'       => count($monthlyActivity),
                    'scoreMonthly'  => $studentMonthlyActivity,
                ];
            } 
            elseif ($semester == 2) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'attendance' => $attendancesByStudent,
                    'relation' => $relation,
                    'semester' => $semester,
                    'mid' => $mid,
                    'result' => $scoresByStudent,
                    'academicYear' => $academicYear,
                    'subjects' => $subjects,
                    'monthlyAct'    => $monthlyActivity,
                    'countMA'       => count($monthlyActivity),
                    'scoreMonthly'  => $studentMonthlyActivity,
                ];
            }

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.kindergartenmid-pdf', $data)->setPaper('a5', 'portrait');
            return $pdf->stream(ucwords(strtolower($student->student_name)) . '_midreport_semester' . $semester . '.pdf');
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function downloadPDFKindergarten($id)
    {
        try {
            $semester = intval(session('semester'));
            $academic_year = session('academic_year');

            $score = Kindergarten::where('student_id', $id)
                ->where('semester', $semester)
                ->where('academic_year', $academic_year)
                ->first();
    
            $gradeId = Student::where('id', $id)->value('grade_id');

            if ($semester == 2) {
                if ($score->promote === 1) {
                    $nextGrade = Grade::where('id', $gradeId+1)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                } 
                elseif ($score->promote === null) {
                    $nextGrade =  $nextGrade = Grade::where('id', $gradeId)
                    ->select('grades.name as grade_name', 'grades.class as grade_class')
                    ->first();
                    $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
                }
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();
            
            $relation = Student_relationship::where('student_relations.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relations.relation_id')
                ->select('relationships.name as relationship_name')
                ->first();

            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalSick = $attendances->where('sick', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                
                return [
                    'days_absent' => $totalAlpha,
                    'sick' => $totalSick,
                    'permission' => $totalPermission,
                ];
            })->values()->all();

            $academicYear = Master_academic::first()->value('academic_year');

            if ($semester == 1) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'english' => $this->determineGrade($score->english),
                    'mathematics' => $this->determineGrade($score->mathematics),
                    'chinese' => $this->determineGrade($score->chinese),
                    'science' => $this->determineGrade($score->science),
                    'character_building' => $this->determineGrade($score->character_building),
                    'art_and_craft' => $this->determineGrade($score->art_and_craft),
                    'it' => $this->determineGrade($score->it),
                    'phonic' => $this->determineGrade($score->phonic),
                    'conduct' => $score->conduct,
                    'attendance' => $attendancesByStudent,
                    'relation' => $relation,
                    'semester' => $semester,
                    'academicYear' => $academicYear,
                ];
            } 
            elseif ($semester == 2) {
                $data = [
                    'student' => $student,
                    'classTeacher' => $classTeacher,
                    'score' => $score,
                    'english' => $this->determineGrade($score->english),
                    'mathematics' => $this->determineGrade($score->mathematics),
                    'chinese' => $this->determineGrade($score->chinese),
                    'science' => $this->determineGrade($score->science),
                    'character_building' => $this->determineGrade($score->character_building),
                    'art_and_craft' => $this->determineGrade($score->art_and_craft),
                    'it' => $this->determineGrade($score->it),
                    'conduct' => $score->conduct,
                    'attendance' => $attendancesByStudent,
                    'promotionGrade' => $grade,
                    'relation' => $relation,
                    'semester' => $semester,
                    'academicYear' => $academicYear,
                ];
            }

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.kindergarten-pdf', $data)->setPaper('a5', 'portrait');
            return $pdf->stream($student->student_name . 'report_card_semester' . $semester . '.pdf');

        } catch (Exception $err) {
            dd($err);
        }
    }

    public function remedial()
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'remedial',
            ]);

            $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');

            $teacherSubjects = Teacher_subject::where('teacher_subjects.teacher_id', $getIdTeacher)
                ->join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                ->join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
                ->select(
                    'grades.id as grade_id',
                    'grades.name as grade_name',
                    'grades.class as grade_class',
                    'subjects.id as subject_id',
                    'subjects.name_subject as subject_name'
                )
                ->where('academic_year', session('academic_year'))
                ->get()
                ->groupBy('grade_id');

            $groupedData = [];

            foreach ($teacherSubjects as $gradeId => $subjects) {
                $gradeKey = strtolower(str_replace(' ', '-', $subjects->first()->grade_name . '-' . $subjects->first()->grade_class));
                $groupedData[$gradeKey] = [
                    'grade_name' => $subjects->first()->grade_name,
                    'grade_class' => $subjects->first()->grade_class,
                    'subjects' => $subjects->map(function ($subject) {
                        if($subject->grade_id > 10){
                            if(strtolower($subject->subject_name) == "chinese lower" || strtolower($subject->subject_name) == "chinese higher"){
                                $getChineseId = Subject::where('name_subject', 'chinese')->value('id');
                                $subjectChinese = $getChineseId;
                                
                                // Tambahkan siswa dengan nilai di bawah 70 untuk setiap subject
                                $subject->students = Grade::where('grades.id', $subject->grade_id)
                                ->join('students', 'students.grade_id', '=', 'grades.id')
                                ->join('acars', 'acars.student_id', '=', 'students.id')
                                ->where('students.is_active', TRUE)
                                ->where('acars.subject_id', $subjectChinese)
                                ->where('acars.final_score', '<', 70)
                                ->where('acars.semester', session('semester'))
                                ->where('acars.academic_year', session('academic_year'))
                                ->select(
                                    'students.id as student_id',
                                    'students.name as student_name',
                                    'acars.final_score'
                                )
                                ->get();
                            }
                            else{
                                // Tambahkan siswa dengan nilai di bawah 70 untuk setiap subject
                                $subject->students = Grade::where('grades.id', $subject->grade_id)
                                ->join('students', 'students.grade_id', '=', 'grades.id')
                                ->join('acars', 'acars.student_id', '=', 'students.id')
                                ->where('students.is_active', TRUE)
                                ->where('acars.subject_id', $subject->subject_id)
                                ->where('acars.final_score', '<', 70)
                                ->where('acars.semester', session('semester'))
                                ->where('acars.academic_year', session('academic_year'))
                                ->select(
                                    'students.id as student_id',
                                    'students.name as student_name',
                                    'acars.final_score'
                                )
                                ->get();
                            }

                        }
                        else{
                            // Tambahkan siswa dengan nilai di bawah 70 untuk setiap subject
                            $subject->students = Grade::where('grades.id', $subject->grade_id)
                            ->join('students', 'students.grade_id', '=', 'grades.id')
                            ->join('acars', 'acars.student_id', '=', 'students.id')
                            ->where('students.is_active', TRUE)
                            ->where('acars.subject_id', $subject->subject_id)
                            ->where('acars.final_score', '<', 70)
                            ->where('acars.semester', session('semester'))
                            ->where('acars.academic_year', session('academic_year'))
                            ->select(
                                'students.id as student_id',
                                'students.name as student_name',
                                'acars.final_score'
                            )
                            ->get();
                        }

                        return $subject;
                    }),
                ];
            }


            // dd($teacherSubjects);
            return view('components.teacher.remedial', [
                'data' => $teacherSubjects,
            ]);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function remedialSuper($id)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'reports',
                'child' => 'remedial',
            ]);

            if(session('role') == "superadmin" || session('role') == "admin"){
                $teacherSubjects = Teacher_subject::join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                    ->join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
                    ->select(
                        'grades.id as grade_id',
                        'grades.name as grade_name',
                        'grades.class as grade_class',
                        'subjects.id as subject_id',
                        'subjects.name_subject as subject_name'
                    )
                    ->where('grades.id', $id)
                    ->where('academic_year', session('academic_year'))
                    ->orderBy('subjects.name_subject', 'asc')
                    ->get()
                    ->groupBy('grade_id');

                $groupedData = [];

                foreach ($teacherSubjects as $gradeId => $subjects) {
                    $gradeKey = strtolower(str_replace(' ', '-', $subjects->first()->grade_name . '-' . $subjects->first()->grade_class));
                    $groupedData[$gradeKey] = [
                        'grade_name' => $subjects->first()->grade_name,
                        'grade_class' => $subjects->first()->grade_class,
                        'subjects' => $subjects->map(function ($subject) {
                            if($subject->grade_id > 10){
                                if(strtolower($subject->subject_name) == "chinese lower" || strtolower($subject->subject_name) == "chinese higher"){
                                    $getChineseId = Subject::where('name_subject', 'chinese')->value('id');
                                    $subjectChinese = $getChineseId;
                                    
                                    // Tambahkan siswa dengan nilai di bawah 70 untuk setiap subject
                                    $subject->students = Grade::where('grades.id', $subject->grade_id)
                                    ->join('students', 'students.grade_id', '=', 'grades.id')
                                    ->join('acars', 'acars.student_id', '=', 'students.id')
                                    ->where('students.is_active', TRUE)
                                    ->where('acars.subject_id', $subjectChinese)
                                    ->where('acars.final_score', '<', 70)
                                    ->where('acars.semester', session('semester'))
                                    ->where('acars.academic_year', session('academic_year'))
                                    ->select(
                                        'students.id as student_id',
                                        'students.name as student_name',
                                        'acars.final_score'
                                    )
                                    ->get();
                                }
                                else{
                                    // Tambahkan siswa dengan nilai di bawah 70 untuk setiap subject
                                    $subject->students = Grade::where('grades.id', $subject->grade_id)
                                    ->join('students', 'students.grade_id', '=', 'grades.id')
                                    ->join('acars', 'acars.student_id', '=', 'students.id')
                                    ->where('students.is_active', TRUE)
                                    ->where('acars.subject_id', $subject->subject_id)
                                    ->where('acars.final_score', '<', 70)
                                    ->where('acars.semester', session('semester'))
                                    ->where('acars.academic_year', session('academic_year'))
                                    ->select(
                                        'students.id as student_id',
                                        'students.name as student_name',
                                        'acars.final_score'
                                    )
                                    ->get();
                                }
    
                            }
                            else{
                                // Tambahkan siswa dengan nilai di bawah 70 untuk setiap subject
                                $subject->students = Grade::where('grades.id', $subject->grade_id)
                                ->join('students', 'students.grade_id', '=', 'grades.id')
                                ->join('acars', 'acars.student_id', '=', 'students.id')
                                ->where('students.is_active', TRUE)
                                ->where('acars.subject_id', $subject->subject_id)
                                ->where('acars.final_score', '<', 70)
                                ->where('acars.semester', session('semester'))
                                ->where('acars.academic_year', session('academic_year'))
                                ->select(
                                    'students.id as student_id',
                                    'students.name as student_name',
                                    'acars.final_score'
                                )
                                ->get();
                            }
    
                            return $subject;
                        }),
                    ];
                }
            }

            return view('components.teacher.remedial', [
                'data' => $teacherSubjects,
            ]);

        } catch (Exception $err) {
            return dd($err);
        }
    }

   
    protected $billingService;

    public function __construct()
    {
        $this->billingService = new BillingService();
    }

    private function checkOutstandingPayments($studentId)
    {
        try {
            // Get the student data
            $student = Student::find($studentId);
            if (!$student || !$student->unique_id) {
                Log::error("Student not found or no unique_id available for student ID: {$studentId}");
                return false;
            }
            
            $uniqueId = $student->unique_id;
            Log::info("Checking payments for student: {$student->name}, ID: {$studentId}, Unique ID: {$uniqueId}");
            
            // Check current month payment
            $currentMonthStatus = $this->billingService->checkPaymentStatus($uniqueId);
            
            // Check previous month payment
            $paymentHistory = $this->billingService->getPaymentHistory($uniqueId);
            
            // If we couldn't get any payment data, log the issue but allow access
            if ($currentMonthStatus === null && $paymentHistory === null) {
                Log::warning("Could not retrieve payment data for student ID: {$studentId}. Allowing access by default.");
                return true; // Allow access if billing service is down
            }
            
            // Dump full response for debugging
            Log::info("Current month status for student {$studentId}: " . json_encode($currentMonthStatus));
            Log::info("Payment history for student {$studentId}: " . json_encode($paymentHistory));
            
            // Check if current month has unpaid bills - handle different response formats
            if ($currentMonthStatus) {
                // Try different possible response formats, starting with the actual format we see in logs
                $isPaid = false;
                
                // New format: has_unpaid_bill field (the actual format from the logs)
                if (isset($currentMonthStatus['has_unpaid_bill'])) {
                    $isPaid = !$currentMonthStatus['has_unpaid_bill']; // If has_unpaid_bill is false, then paid is true
                }
                // Format 1: status field
                else if (isset($currentMonthStatus['status'])) {
                    $isPaid = strtolower($currentMonthStatus['status']) === 'paid' || 
                            strtolower($currentMonthStatus['status']) === 'lunas'; // Handle Indonesian "Lunas" (paid)
                }
                // Format 2: paid field
                else if (isset($currentMonthStatus['paid'])) {
                    $isPaid = (bool)$currentMonthStatus['paid'];
                }
                // Format 3: is_paid field
                else if (isset($currentMonthStatus['is_paid'])) {
                    $isPaid = (bool)$currentMonthStatus['is_paid'];
                }
                
                Log::info("Current month payment status determined as: " . ($isPaid ? 'PAID' : 'UNPAID'));
                
                if (!$isPaid) {
                    return false;
                }
            }
            
            // Check if previous month has unpaid bills - also handle different formats
            if ($paymentHistory && !empty($paymentHistory)) {
                // The history might be an array or an object with items field
                $historyItems = $paymentHistory;
                if (isset($paymentHistory['items'])) {
                    $historyItems = $paymentHistory['items'];
                }
                
                // If it's still not an array, try to find any relevant fields
                if (!is_array($historyItems)) {
                    Log::warning("Payment history is not in expected format: " . json_encode($paymentHistory));
                    // Default to allowing access if we can't parse the history
                    return true;
                }
                
                // Get the previous month's payment (if any)
                if (count($historyItems) > 0) {
                    $previousMonth = end($historyItems);
                    Log::info("Previous month payment record: " . json_encode($previousMonth));
                    
                    $isPaid = false;
                    
                    // Handle 'status' with value 'Lunas' (Indonesian for 'Paid')
                    if (isset($previousMonth['status'])) {
                        $status = strtolower($previousMonth['status']);
                        $isPaid = $status === 'paid' || $status === 'lunas';
                    }
                    // Format 2: paid field
                    else if (isset($previousMonth['paid'])) {
                        $isPaid = (bool)$previousMonth['paid'];
                    }
                    // Format 3: is_paid field
                    else if (isset($previousMonth['is_paid'])) {
                        $isPaid = (bool)$previousMonth['is_paid'];
                    }
                    
                    Log::info("Previous month payment status determined as: " . ($isPaid ? 'PAID' : 'UNPAID'));
                    
                    if (!$isPaid) {
                        return false;
                    }
                }
            }
            
            // All checks passed - no unpaid bills found
            Log::info("No unpaid bills found for student ID: {$studentId}. Granting access.");
            return true;
        } catch (Exception $e) {
            Log::error("Error in checkOutstandingPayments: {$e->getMessage()}");
            // Allow access if there's an error to avoid blocking valid users
            return true;
        }
    }

    public function checkMidreportAccess()
    {
        try {
            $role = session('role');
            Log::info("Checking midreport access for role: {$role}");
            
            if ($role == "parent") {
                $getIdStudent = session('studentId');
            } elseif ($role == "student") {
                $getIdStudent = Student::where('user_id', session('id_user'))->value('id');
            } else {
                Log::warning("Invalid role trying to access midreport: {$role}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid role',
                ]);
            }
            
            Log::info("Student ID for midreport access check: {$getIdStudent}");
            
            // Check if student record exists
            $student = Student::find($getIdStudent);
            if (!$student) {
                Log::warning("Student not found for ID: {$getIdStudent}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Student data not found',
                ]);
            }
            
            // Check payment status
            $paymentStatus = $this->checkOutstandingPayments($getIdStudent);
            Log::info("Payment status check result for student {$getIdStudent}: " . ($paymentStatus ? 'PAID' : 'UNPAID'));
            
            if (!$paymentStatus) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have not fully paid the bill for this or the previous month. Please pay your bill to access the report.',
                ]);
            }
            
            // Check if report exists
            $checkReport = Mid_report::where('student_id', $getIdStudent)
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))->exists();
            
            if ($checkReport == false) {
                Log::info("Midreport doesn't exist for student {$getIdStudent}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mid semester report not available',
                ]);
            }
            
            Log::info("Granting midreport access for student {$getIdStudent}");
            return response()->json([
                'status' => 'success',
                'message' => 'Access granted',
            ]);

        } catch (Exception $err) {
            Log::error("Error in checkMidreportAccess: {$err->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while checking access to the report. Please try again.',
            ]);
        }
    }

    public function checkReportAccess()
    {
        try {
            $role = session('role');
            
            if ($role == "parent") {
                $getIdStudent = session('studentId');
            } elseif ($role == "student") {
                $getIdStudent = Student::where('user_id', session('id_user'))->value('id');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid role',
                ]);
            }
            
            // Check if student record exists
            $student = Student::find($getIdStudent);
            if (!$student) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Student data not found',
                ]);
            }
            
            // Check payment status
            if (!$this->checkOutstandingPayments($getIdStudent)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have not fully paid the bill for this or the previous month. Please pay your bill to access the report.',
                ]);
            }
            
            // Check if report exists
            $checkReport = Report_card::where('student_id', $getIdStudent)
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))->exists();
            
            if ($checkReport == false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Semester report not available',
                ]);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Access granted',
            ]);
        } catch (Exception $err) {
            Log::error('Error in checkReportAccess: ' . $err->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while checking access to the report. Please try again.',
            ]);
        }
    }


    public function midreport(){
        try{
            $semester = session('semester');
            $role = session('role');

            if ($role == "parent") {
                $getIdStudent = session('studentId');

                // Check payments before showing report
                if (!$this->checkOutstandingPayments($getIdStudent)) {
                    session()->flash('outstanding_payment');
                    return redirect($role . '/dashboard');
                }

                $checkReport = Report_card::where('student_id', $getIdStudent)
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))->exists();
                
                if($checkReport == false){
                    session()->flash('midreport_doesnt_exists');
                    return redirect($role . '/dashboard');
                }
                
            } elseif ($role == "student") {
                $getIdStudent = Student::where('user_id', session('id_user'))->value('id');

                // Check payments before showing report
                if (!$this->checkOutstandingPayments($getIdStudent)) {
                    session()->flash('outstanding_payment');
                    return redirect($role . '/dashboard');
                }

                $checkReport = Mid_report::where('student_id', $getIdStudent)
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))->exists();
                
                if($checkReport == false){
                    session()->flash('midreport_doesnt_exists');
                    return redirect()->back();
                }
            }
            
            $data = $this->reportmid($getIdStudent);

            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView('components.report.pdf.mid_semester-pdf', $data)->setPaper('a5', 'portrait');
            
            return $pdf->stream($data['student']->student_name . '_midsemester' . $semester . '.pdf');            
        }   
        catch(Exception $err){
            dd($err);
        }     
    }

    public function report(){
        try{
            $semester = session('semester');
            $role = session('role');

            if ($role == "parent") {
                $getIdStudent = session('studentId');

                // Check payments before showing report
                if (!$this->checkOutstandingPayments($getIdStudent)) {
                    session()->flash('outstanding_payment');
                    return redirect($role . '/dashboard');
                }

                $checkReport = Report_card::where('student_id', $getIdStudent)
                    ->where('semester', session('semester'))
                    ->where('academic_year', session('academic_year'))->exists();
                
                if($checkReport == false){
                    session()->flash('report_doesnt_exists');
                    return redirect($role . '/dashboard');
                }
                
            } elseif ($role == "student") {
                $getIdStudent = Student::where('user_id', session('id_user'))->value('id');

                // Check payments before showing report
                if (!$this->checkOutstandingPayments($getIdStudent)) {
                    session()->flash('outstanding_payment');
                    return redirect($role . '/dashboard');
                }

                $checkReport = Report_card::where('student_id', $getIdStudent)
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))->exists();
            
                if($checkReport == false){
                    session()->flash('report_doesnt_exists');
                    return redirect($role . '/dashboard');
                }
            }
            
            switch ($semester) {
                case 1:
                    $data = $this->report1($getIdStudent);
                    $view = 'components.report.pdf.semester1-pdf';
                    break;
                case 2:
                    $data = $this->report2($getIdStudent);
                    $view = 'components.report.pdf.semester2-pdf';
                    break;
                default:
                    throw new Exception('Invalid semester value.');
            }
            
            $pdf = app('dompdf.wrapper');
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->loadView($view, $data)->setPaper('a5', 'portrait');
            
            return $pdf->stream($data['student']->student_name . '_semester' . $semester . '.pdf');            
        }   
        catch(Exception $err){
            dd($err);
        }     
    }

    // public function report(){
    //     try{
    //         $semester = session('semester');
    //         $role = session('role');

    //         if ($role == "parent") {
    //             $getIdStudent = session('studentId');
    //             $checkReport = Report_card::where('student_id', $getIdStudent)
    //                 ->where('semester', session('semester'))
    //                 ->where('academic_year', session('academic_year'))->exists();
                
    //             if($checkReport == false){
    //                 session()->flash('report_doesnt_exists');
    //                 return redirect($role . '/dashboard');
    //             }
                
    //         } elseif ($role == "student") {
    //             $getIdStudent = Student::where('user_id', session('id_user'))->value('id');
    //             $checkReport = Report_card::where('student_id', $getIdStudent)
    //             ->where('semester', session('semester'))
    //             ->where('academic_year', session('academic_year'))->exists();
            
    //             if($checkReport == false){
    //                 session()->flash('report_doesnt_exists');
    //                 return redirect($role . '/dashboard');
    //             }
    //         }
            
    //         switch ($semester) {
    //             case 1:
    //                 $data = $this->report1($getIdStudent);
    //                 $view = 'components.report.pdf.semester1-pdf';
    //                 break;
    //             case 2:
    //                 $data = $this->report2($getIdStudent);
    //                 $view = 'components.report.pdf.semester2-pdf';
    //                 break;
    //             default:
    //                 throw new Exception('Invalid semester value.');
    //         }
            
    //         $pdf = app('dompdf.wrapper');
    //         $pdf->set_option('isRemoteEnabled', true);
    //         $pdf->set_option('isHtml5ParserEnabled', true);
    //         $pdf->loadView($view, $data)->setPaper('a5', 'portrait');
            
    //         return $pdf->stream($data['student']->student_name . '_semester' . $semester . '.pdf');            
    //     }   
    //     catch(Exception $err){
    //         dd($err);
    //     }     
    // }

    // public function midreport(){
    //     try{
    //         $semester = session('semester');
    //         $role = session('role');

    //         if ($role == "parent") {
    //             $getIdStudent = session('studentId');
    //             $checkReport = Report_card::where('student_id', $getIdStudent)
    //                 ->where('semester', session('semester'))
    //                 ->where('academic_year', session('academic_year'))->exists();
                
    //             if($checkReport == false){
    //                 session()->flash('midreport_doesnt_exists');
    //                 return redirect($role . '/dashboard');
    //             }
                
    //         } elseif ($role == "student") {
    //             $getIdStudent = Student::where('user_id', session('id_user'))->value('id');
    //             $checkReport = Report_card::where('student_id', $getIdStudent)
    //                 ->where('semester', session('semester'))
    //                 ->where('academic_year', session('academic_year'))->exists();
                
    //             if($checkReport == false){
    //                 session()->flash('midreport_doesnt_exists');
    //                 return redirect()->back();
    //             }
    //         }
            
    //         $data = $this->reportmid($getIdStudent);

    //         $pdf = app('dompdf.wrapper');
    //         $pdf->set_option('isRemoteEnabled', true);
    //         $pdf->set_option('isHtml5ParserEnabled', true);
    //         $pdf->loadView('components.report.pdf.mid_semester-pdf', $data)->setPaper('a5', 'portrait');
            
    //         return $pdf->stream($data['student']->student_name . '_midsemester' . $semester . '.pdf');            
    //     }   
    //     catch(Exception $err){
    //         dd($err);
    //     }     
    // }

    private function containsChinese($string) {
        return preg_match('/[\x{4E00}-\x{9FFF}]/u', $string);
    }

    private function report1($id)
    {
        try {
            // dd($id);
            $semester = session('semester');
            $academic_year = session('academic_year');

            $learningSkills = Report_card::where('student_id', $id)
                ->where('semester', $semester)
                ->first();

            $gradeId = Student::where('id', $id)->value('grade_id');
            $gradeSerial = Grade::where('id', $gradeId)->value('class');
            
            if (session('semester') == 1) {
                $getSerial = (2 * $gradeSerial) - 1; // Semester 1
            } elseif (session('semester') == 2) {
                $getSerial = 2 * $gradeSerial; // Semester 2
            } else {
                $getSerial = '-'; // Default jika semester tidak valid
            }

            switch ($gradeId) {
                case "5":
                case "11":
                    $date_of_registration = "June 22, 2024";
                    break;
            
                case "6":
                case "12":
                    $date_of_registration = "July 24, 2023";
                    break;
            
                case "7":
                case "13":
                    $date_of_registration = "July 10, 2022";
                    break;
            
                case "8":
                    $date_of_registration = "July 12, 2021";
                    break;
            
                case "9":
                    $date_of_registration = "July 10, 2020";
                    break;
            
                case "10":
                    $date_of_registration = "July 18, 2019";
                    break;
            
                default:
                    $date_of_registration = "Unknown date"; // Nilai default jika $gradeId tidak sesuai
                    break;
            }
        
            $date = Master_academic::where('is_use', TRUE)->value('report_card1');

            // $serial = Grade::join('students', 'students.grade_id', '=', 'grades.id')
            //     ->where('grades.id', $gradeId)
            //     ->orderBy('students.name', 'asc')
            //     ->select('students.id', 'students.name', 'grades.id as grade_id')
            //     ->get();

            // Tambahkan nomor urut ke setiap siswa
            // $serial->each(function($serial, $index) {
            //     $serial->serial_number = $index + 1; // Nomor urut mulai dari 1
            // });

            // foreach ($serial as $student) {
            //     if ($student->id == $id) {
            //         $getSerial = $student->serial_number;
            //         break;
            //     }
            // }
            // Hitung serial berdasarkan primary dan semester
            

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();

            $relation = Student_relationship::where('Student_relations.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relations.relation_id')
                ->select('relationships.name as relationship_name')
                ->first();
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                $totalAbsent = $totalAlpha + $totalPermission;
                $totalLate = $attendances->where('late', 1)->count();
                $timesLate = $attendances->whereNotNull('latest')->sum('latest');
                
                return [
                    'days_absent' => $totalAbsent,
                    'total_late' => $totalLate,
                    'times_late' => $timesLate,
                ];
            })->values()->all();

            

            $resultsScore = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->leftJoin('subjects', function ($join) {
                    $join->on('subjects.id', '=', 'acars.subject_id')
                    ->select('subjects.name');
                })
                ->where('acars.student_id', $id)
                ->where('acars.semester', $semester)
                ->where('acars.academic_year', $academic_year)
                ->get();
                
            if(strtolower($student->grade_name) === "primary")
            {
                $order = [
                    'Religion',
                    'PPKn',
                    'Character Building',
                    'Bahasa Indonesia',
                    'Mathematics',
                    'Science',
                    'Financial Literacy',
                    'Art and Craft',
                    'PE',
                    'IT',
                    'English',
                    'Chinese'
                ];

                $ecaData = Student_eca::where('student_ecas.student_id', $id)
                    ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
                    ->get(['student_ecas.student_id', 'ecas.name as eca_name']);

                $groupedEcaData = [];
                $counter = 1;

                foreach ($ecaData as $eca) {
                    $groupedEcaData['student_id'] = $eca->student_id;
                    $groupedEcaData['eca_' . $counter] = $eca->eca_name;
                    $counter++;
                }

            } elseif (strtolower($student->grade_name) === "secondary") {
                $order = [
                    'Religion',
                    'PPKn',
                    'Character Building',
                    'Bahasa Indonesia',
                    'Mathematics',
                    'Science',
                    'IPS',
                    'Art and Design',
                    'PE',
                    'IT',
                    'English',
                    'Chinese',
                    'Financial Literacy'
                ];

                $ecaData = Student_eca::where('student_ecas.student_id', $id)
                ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
                ->get(['student_ecas.student_id', 'ecas.name as eca_name']);

                $groupedEcaData = [];
                $counter = 1;

                foreach ($ecaData as $eca) {
                    $groupedEcaData['student_id'] = $eca->student_id;
                    $groupedEcaData['eca_' . $counter] = $eca->eca_name;
                    $counter++;
                }
            }

            $comments = comment::where('student_id', $id)
                ->get()
                ->keyBy('student_id');

            $scoresByStudent = $resultsScore->groupBy('student_id')->map(function ($scores) use ($comments, $order) {
                $student = $scores->first();
            
                // Initialize an array for each subject in the order
                $sortedScores = collect($order)->map(function($subject) use ($scores) {
                    // Find the score for this subject
                    $score = $scores->firstWhere('name_subject', $subject);
            
                    // Return the score details or empty values if not found
                    return [
                        'subject_name' => $subject,
                        'subject_id' => $score ? $score->subject_id : null,
                        'final_score' => $score ? $score->final_score : null,
                        'grades' => $score ? $score->grades : null,
                        'comment' => $score ? $score->comment : null,
                        'isChinese' => $this->containsChinese($score ? $score->comment : null),
                    ];
                });
            
                $isRestricted = $sortedScores->contains(function ($score) {
                    return $score['final_score'] !== null && $score['final_score'] < 70;
                });
            
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->name,
                    'scores' => $sortedScores->all(),
                    'isRestricted' => $isRestricted, // Include the restricted flag in the output
                ];
            })->values()->all();
                

            
            if (strtolower($student->grade_name) === "primary") {
                $resultsSooa = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                    ->where('sooa_primaries.student_id', $id)
                    ->where('sooa_primaries.semester', $semester)
                    ->where('sooa_primaries.academic_year', $academic_year)
                    ->get();

                $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
        
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'ranking' => $student->ranking,
                        'scores' => $scores->map(function ($score) {
                            return [
                                'academic' => $score->academic,
                                'grades_academic' => $score->grades_academic,
                                'choice' => $score->choice,
                                'grades_choice' => $score->grades_choice,
                                'language_and_art' => $score->language_and_art,
                                'grades_language_and_art' => $score->grades_language_and_art,
                                'self_development' => $score->self_development,
                                'grades_self_development' => $score->grades_self_development,
                                'eca_aver' => $score->eca_aver,
                                'grades_eca_aver' => $score->grades_eca_aver,
                                'behavior' => $score->behavior,
                                'grades_behavior' => $score->grades_behavior,
                                'attendance' => $score->attendance,
                                'grades_attendance' => $score->grades_attendance,
                                'participation' => $score->participation,
                                'grades_participation' => $score->grades_participation,
                                'final_score' => $score->final_score,
                                'grades_final_score' => $score->grades_final_score,
                            ];
                        })->all(),
                    ];
                })->values()->all();
            }
            elseif (strtolower($student->grade_name) === "secondary") {
                $resultsSooa = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                    ->where('sooa_secondaries.student_id', $id)
                    ->where('sooa_secondaries.semester', $semester)
                    ->where('sooa_secondaries.academic_year', $academic_year)
                    ->get();

                $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                    $student = $scores->first();
        
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'ranking' => $student->ranking,
                        'scores' => $scores->map(function ($score) {
                            return [
                                'academic' => $score->academic,
                                'grades_academic' => $score->grades_academic,
                                'eca_1' => $score->eca_1 == 0 ? "-" : $score->eca_1,
                                'grades_eca_1' => $score->grades_eca_1,
                                'eca_2' => $score->eca_2 == 0 ? "-" : $score->eca_2,
                                'grades_eca_2' => $score->grades_eca_2,
                                'self_development' => $score->self_development,
                                'grades_self_development' => $score->grades_self_development,
                                'eca_aver' => $score->eca_aver,
                                'grades_eca_aver' => $score->grades_eca_aver,
                                'behavior' => $score->behavior,
                                'grades_behavior' => $score->grades_behavior,
                                'attendance' => $score->attendance,
                                'grades_attendance' => $score->grades_attendance,
                                'participation' => $score->participation,
                                'grades_participation' => $score->grades_participation,
                                'final_score' => $score->final_score,
                                'grades_final_score' => $score->grades_final_score,
                            ];
                        })->all(),
                    ];
                })->values()->all();
            }

            $student->date_of_registration = Carbon::parse($student->date_of_registration);

            $academicYear = Master_academic::first()->value('academic_year');

            $remarks = Acar_comment::where('student_id', $id)->value('comment');

            // dd($scoresByStudent);

            $data = [
                'student' => $student,
                'classTeacher' => $classTeacher,
                'learningSkills' => $learningSkills,
                'subjectReports' => $scoresByStudent,
                'sooa' => $scoresByStudentSooa,
                'attendance' => $attendancesByStudent,
                'relation' => $relation,
                'serial' => $getSerial,
                'academicYear' => $academicYear,
                'eca' => $groupedEcaData,
                'remarks' => $remarks,
                'date' => $date,
                'date_of_registration' => $date_of_registration,
            ];
            return $data;
        } catch (Exception $err) {
            dd($err);
        }
    }

    private function report2($id)
    {
        try {
            $semester = session('semester');
            $academic_year = session('academic_year');

            $learningSkills = Report_card::where('student_id', $id)
                ->where('semester', $semester)
                ->first();
    
            $date = Master_academic::where('is_use', TRUE)->value('report_card2');

            $gradeId = Student::where('id', $id)->value('grade_id');
            $gradeSerial = Grade::where('id', $gradeId)->value('class');
            
            if (session('semester') == 1) {
                $getSerial = (2 * $gradeSerial) - 1; // Semester 1
            } elseif (session('semester') == 2) {
                $getSerial = 2 * $gradeSerial; // Semester 2
            } else {
                $getSerial = '-'; // Default jika semester tidak valid
            }
            
            if ($learningSkills->promotion_status === 1 || $learningSkills->promotion_status === 2) {
                $nextGrade = Grade::where('id', $gradeId+1)
                ->select('grades.name as grade_name', 'grades.class as grade_class')
                ->first();
                $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
            } 
            elseif ($learningSkills->promotion_status === 3) {
                $nextGrade =  $nextGrade = Grade::where('id', $gradeId)
                ->select('grades.name as grade_name', 'grades.class as grade_class')
                ->first();
                $grade = $nextGrade->grade_name . ' - ' . $nextGrade->grade_class;
            }

            switch ($gradeId) {
                case "5":
                case "11":
                    $date_of_registration = "June 22, 2024";
                    break;
            
                case "6":
                case "12":
                    $date_of_registration = "July 24, 2023";
                    break;
            
                case "7":
                case "13":
                    $date_of_registration = "July 10, 2022";
                    break;
            
                case "8":
                    $date_of_registration = "July 12, 2021";
                    break;
            
                case "9":
                    $date_of_registration = "July 10, 2020";
                    break;
            
                case "10":
                    $date_of_registration = "July 18, 2019";
                    break;
            
                default:
                    $date_of_registration = "Unknown date"; // Nilai default jika $gradeId tidak sesuai
                    break;
            }
            

            // $serial = Grade::join('students', 'students.grade_id', '=', 'grades.id')
            //     ->where('grades.id', $gradeId)
            //     ->orderBy('students.name', 'asc')
            //     ->select('students.id', 'students.name', 'grades.id as grade_id')
            //     ->get();

            // Tambahkan nomor urut ke setiap siswa
            // $serial->each(function($serial, $index) {
            //     $serial->serial_number = $index + 1; // Nomor urut mulai dari 1
            // });

            // foreach ($serial as $student) {
            //     if ($student->id == $id) {
            //         $getSerial = $student->serial_number;
            //         break;
            //     }
            // }

            $ecaData = Student_eca::where('student_ecas.student_id', $id)
                ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
                ->get(['student_ecas.student_id', 'ecas.name as eca_name']);

            $groupedEcaData = [];
            $counter = 1;

            foreach ($ecaData as $eca) {
                $groupedEcaData['student_id'] = $eca->student_id;
                $groupedEcaData['eca_' . $counter] = $eca->eca_name;
                $counter++;
            }

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();

            $relation = Student_relationship::where('student_relations.student_id', $id)
                ->join('relationships', 'relationships.id', '=', 'student_relations.relation_id')
                ->select('relationships.name as relationship_name')
                ->first();
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                $totalAbsent = $totalAlpha + $totalPermission;
                $totalLate = $attendances->where('late', 1)->count();
                $timesLate = $attendances->whereNotNull('latest')->sum('latest');
                
                return [
                    'days_absent' => $totalAbsent,
                    'total_late' => $totalLate,
                    'times_late' => $timesLate,
                ];
            })->values()->all();

            $comments = comment::where('student_id', $id)
                ->get()
                ->keyBy('student_id');

            $resultsScore = Acar::join('students', 'students.id', '=', 'acars.student_id')
                ->leftJoin('subjects', function ($join) {
                    $join->on('subjects.id', '=', 'acars.subject_id')
                    ->select('subjects.name');
                })
                ->where('acars.student_id', $id)
                ->where('acars.semester', $semester)
                ->where('acars.academic_year', $academic_year)
                ->get();
                
                if(strtolower($student->grade_name) === "primary")
                {
                    $order = [
                        'Religion',
                        'PPKn',
                        'Character Building',
                        'Bahasa Indonesia',
                        'Mathematics',
                        'Science',
                        'Financial Literacy',
                        'Art and Craft',
                        'PE',
                        'IT',
                        'English',
                        'Chinese'
                    ];
                } elseif (strtolower($student->grade_name) === "secondary") {
                    $order = [
                        'Religion',
                        'PPKn',
                        'Character Building',
                        'Bahasa Indonesia',
                        'Mathematics',
                        'Science',
                        'IPS',
                        'Art and Design',
                        'PE',
                        'IT',
                        'English',
                        'Chinese',
                        'Financial Literacy',
                    ];
                }

                $scoresByStudent = $resultsScore->groupBy('student_id')->map(function ($scores) use ($comments, $order) {
                    $student = $scores->first();
                
                    // Initialize an array for each subject in the order
                    $sortedScores = collect($order)->map(function($subject) use ($scores) {
                        // Find the score for this subject
                        $score = $scores->firstWhere('name_subject', $subject);
                
                        // Return the score details or empty values if not found
                        return [
                            'subject_name' => $subject,
                            'subject_id' => $score ? $score->subject_id : null,
                            'final_score' => $score ? $score->final_score : null,
                            'grades' => $score ? $score->grades : null,
                            'comment' => $score ? $score->comment : null,
                            'isChinese' => $this->containsChinese($score ? $score->comment : null),
                        ];
                    });
                
                    $isRestricted = $sortedScores->contains(function ($score) {
                        return $score['final_score'] !== null && $score['final_score'] <= 70;
                    });
                
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->name,
                        'scores' => $sortedScores->all(),
                        'isRestricted' => $isRestricted, // Include the restricted flag in the output
                    ];
                })->values()->all();

            // dd($student->grade_name);
                

            if (strtolower($student->grade_name) === "primary") {
                $resultsSooa = Sooa_primary::join('students', 'students.id', '=', 'sooa_primaries.student_id')
                    ->where('sooa_primaries.student_id', $id)
                    ->where('sooa_primaries.semester', $semester)
                    ->where('sooa_primaries.academic_year', $academic_year)
                    ->get();

                    $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                        $student = $scores->first();
            
                        return [
                            'student_id' => $student->student_id,
                            'student_name' => $student->name,
                            'ranking' => $student->ranking,
                            'scores' => $scores->map(function ($score) {
                                return [
                                    'academic' => $score->academic,
                                    'grades_academic' => $score->grades_academic,
                                    'choice' => $score->choice,
                                    'grades_choice' => $score->grades_choice,
                                    'language_and_art' => $score->language_and_art,
                                    'grades_language_and_art' => $score->grades_language_and_art,
                                    'self_development' => $score->self_development,
                                    'grades_self_development' => $score->grades_self_development,
                                    'eca_aver' => $score->eca_aver,
                                    'grades_eca_aver' => $score->grades_eca_aver,
                                    'behavior' => $score->behavior,
                                    'grades_behavior' => $score->grades_behavior,
                                    'attendance' => $score->attendance,
                                    'grades_attendance' => $score->grades_attendance,
                                    'participation' => $score->participation,
                                    'grades_participation' => $score->grades_participation,
                                    'final_score' => $score->final_score,
                                    'grades_final_score' => $score->grades_final_score,
                                ];
                            })->all(),
                        ];
                    })->values()->all();
            }
            elseif (strtolower($student->grade_name) === "secondary") {
                $resultsSooa = Sooa_secondary::join('students', 'students.id', '=', 'sooa_secondaries.student_id')
                    ->where('sooa_secondaries.student_id', $id)
                    ->where('sooa_secondaries.semester', $semester)
                    ->where('sooa_secondaries.academic_year', $academic_year)
                    ->get();

                    $scoresByStudentSooa = $resultsSooa->groupBy('student_id')->map(function ($scores) {
                        $student = $scores->first();
            
                        return [
                            'student_id' => $student->student_id,
                            'student_name' => $student->name,
                            'ranking' => $student->ranking,
                            'scores' => $scores->map(function ($score) {
                                return [
                                    'academic' => $score->academic,
                                    'grades_academic' => $score->grades_academic,
                                    'eca_1' => $score->eca_1,
                                    'grades_eca_1' => $score->grades_eca_1,
                                    'eca_2' => $score->grades_eca_2,
                                    'grades_eca_2' => $score->grades_eca_2,
                                    'self_development' => $score->self_development,
                                    'grades_self_development' => $score->grades_self_development,
                                    'eca_aver' => $score->eca_aver,
                                    'grades_eca_aver' => $score->grades_eca_aver,
                                    'behavior' => $score->behavior,
                                    'grades_behavior' => $score->grades_behavior,
                                    'attendance' => $score->attendance,
                                    'grades_attendance' => $score->grades_attendance,
                                    'participation' => $score->participation,
                                    'grades_participation' => $score->grades_participation,
                                    'final_score' => $score->final_score,
                                    'grades_final_score' => $score->grades_final_score,
                                ];
                            })->all(),
                        ];
                    })->values()->all();
            }
    

            

            $tcop = Tcop::where('student_id', $id)
                ->where('academic_year', $academic_year)
                ->select('tcops.final_score as final_score', 'tcops.grades_final_score as grades_final_score')
                ->get();

            $student->date_of_registration = Carbon::parse($student->date_of_registration);

            $academicYear = Master_academic::first()->value('academic_year');

            // dd($scoresByStudentSooa);

            $data = [
                'student' => $student,
                'classTeacher' => $classTeacher,
                'learningSkills' => $learningSkills,
                'subjectReports' => $scoresByStudent,
                'sooa' => $scoresByStudentSooa,
                'attendance' => $attendancesByStudent,
                'relation' => $relation,
                'serial' => $getSerial,
                'promotionGrade' => $grade,
                'academicYear' => $academicYear,
                'eca' => $groupedEcaData,
                'tcop' => $tcop,
                'date' => $date,
                'date_of_registration' => $date_of_registration,
            ];
            return $data;
        } catch (Exception $err) {
            dd($err);
        }
    }

    private function reportmid($id)
    {
        try {
            $semester = session('semester');
            $academic_year = session('academic_year');

            $gradeId = Student::where('id', $id)->value('grade_id');

            $student = Student::where('students.id', $id)
                ->join('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.name as student_name', 'students.created_at as date_of_registration', 
                'grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $classTeacher = Teacher_grade::where('teacher_grades.grade_id', $student->grade_id)
                ->join('teachers', 'teachers.id', 'teacher_grades.teacher_id')
                ->select('teachers.name as teacher_name')
                ->first();

            // $remarks = Mid_report::where('mid_reports.student_id', '=', $id)->value('remarks'); 
            $ct = Mid_report::where('mid_reports.student_id', '=', $id)->value('critical_thinking'); 
            $cs = Mid_report::where('mid_reports.student_id', '=', $id)->value('cognitive_skills'); 
            $ls = Mid_report::where('mid_reports.student_id', '=', $id)->value('life_skills'); 
            $les = Mid_report::where('mid_reports.student_id', '=', $id)->value('learning_skills'); 
            $saed = Mid_report::where('mid_reports.student_id', '=', $id)->value('social_and_emotional_development'); 
            $academicYear = Master_academic::first()->value('academic_year');
            
            $resultsAttendance = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $student->grade_id)
                ->where('students.id', $id)
                ->where('attendances.semester', $semester)
                ->get();

            $attendancesByStudent = $resultsAttendance->groupBy('student_id')->map(function($attendances) {
                
                $totalPresent     = $attendances->where('present', 1)->count();
                $totalAlpha       = $attendances->where('alpha', 1)->count();
                $totalSick        = $attendances->where('sick', 1)->count();
                $totalPermission  = $attendances->where('permission', 1)->count();
                $totalLate        = $attendances->where('late', 1)->count();
                $timesLate        = $attendances->whereNotNull('latest')->sum('latest');
                
                return [
                    'present'     => $totalPresent,
                    'days_absent' => $totalAlpha,
                    'sick'        => $totalSick,
                    'permission'  => $totalPermission,
                    'total_late'   => $totalLate,
                    'late'        => $timesLate,
                ];
            })->values()->all();

            $homework  = Type_exam::where('name', 'homework')->value('id');
            $exercise  = Type_exam::where('name', 'exercise')->value('id');
            $quiz      = Type_exam::where('name', 'quiz')->value('id');
            $project   = Type_exam::where('name', 'project')->value('id');
            $practical = Type_exam::where('name', 'practical')->value('id');
                
            if(strtolower($student->grade_name) === "primary"){
                $checkReligion = Student::where('id', $id)->value('religion');

                if ($checkReligion == "Islam") {
                    $religion = "Religion Islamic";
                }
                elseif ($checkReligion == "Catholic Christianity") {
                    $religion = "Religion Catholic";
                }
                elseif ($checkReligion == "Protestant Christianity") {
                    $religion = "Religion Christian";
                }
                elseif ($checkReligion == "Buddhism") {
                    $religion = "Religion Buddhism";
                }
                elseif ($checkReligion == "Hinduism") {
                    $religion = "Religion Hinduism";
                }
                elseif ($checkReligion == "Confucianism") {
                    $religion = "Religion Confucianism";
                }

                $order = [
                    'English',
                    'Chinese',
                    'Mathematics',
                    'Science',
                    $religion,
                    'Bahasa Indonesia',
                    'Character Building',
                    'PE',
                    'IT',
                    'General Knowledge',
                    'PPKn',
                    'Art and Craft',
                    'Health Education'
                ];

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
                    ->leftJoin('subjects', function($join){
                        $join->on('subjects.id', '=', 'subject_exams.subject_id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                        'subjects.name_subject as subject_name',
                    )
                    ->where('grades.id', $gradeId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('students.id', $id)
                    ->whereIn('exams.type_exam', [$homework, $exercise, $quiz, $project, $practical])
                    ->get();

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($order, $homework, $exercise, $quiz, $project, $practical) {
                    $student = $scores->first();
                    $scoresBySubject = $scores->groupBy('subject_name')->map(function ($subjectScores) use ($homework, $exercise, $quiz, $project, $practical) {
                
                        $homeworkScores = $subjectScores->where('type_exam', $homework)->pluck('score');
                        $exerciseScores = $subjectScores->where('type_exam', $exercise)->pluck('score');
                        $quizScores = $subjectScores->where('type_exam', $quiz)->pluck('score');
                        $projectScores = $subjectScores->where('type_exam', $project)->pluck('score');
                        $practicalScores = $subjectScores->where('type_exam', $practical)->pluck('score');
                
                        return [
                            'subject_name' => $subjectScores->first()->subject_name,
                            'scores' => [
                                'homework' => $homeworkScores->all(),
                                'exercise' => $exerciseScores->all(),
                                'quiz' => $quizScores->all(),
                                'project' => $projectScores->all(),
                                'practical' => $practicalScores->all()
                            ],
                        ];
                    });

                    $isRestricted = collect($scoresBySubject)->contains(function ($subject) {
                        return collect($subject['scores'])->contains(function ($examScores) {
                            return collect($examScores)->contains(function ($score) {
                                return $score !== null && $score <= 70;
                            });
                        });
                    });
                
                    // Urutkan subjek berdasarkan urutan dalam $order
                    $orderedSubjects = collect($order)->mapWithKeys(function ($subject) use ($scoresBySubject) {
                        return [$subject => $scoresBySubject->get($subject, ['subject_name' => $subject, 'scores' => []])];
                    });
                
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'subjects' => $orderedSubjects->values()->all(),
                        'isRestricted' => $isRestricted,
                    ];
                })->values()->all();

            } 
            elseif (strtolower($student->grade_name) === "secondary") {
                $chineseLower  = Chinese_lower::where('student_id', $id)->exists();
                $chineseHigher = Chinese_higher::where('student_id', $id)->exists();

                $checkReligion = Student::where('id', $id)->value('religion');

                if ($checkReligion == "Islam") {
                    $religion = "Religion Islamic";
                }
                elseif ($checkReligion == "Catholic Christianity") {
                    $religion = "Religion Catholic";
                }
                elseif ($checkReligion == "Protestant Christianity") {
                    $religion = "Religion Christian";
                }
                elseif ($checkReligion == "Buddhism") {
                    $religion = "Religion Buddhism";
                }
                elseif ($checkReligion == "Hinduism") {
                    $religion = "Religion Hinduism";
                }
                elseif ($checkReligion == "Confucianism") {
                    $religion = "Religion Confucianism";
                }
                // dd($chineseHigher);
            
                if ($chineseLower) {
                    $chinese = "Chinese Lower";
                }
                elseif ($chineseHigher) {
                    $chinese = "Chinese Higher";
                }
                
                $order = [
                    'English',
                    $chinese,
                    'Mathematics',
                    'Science',
                    $religion,
                    'Bahasa Indonesia',
                    'Character Building',
                    'PE',
                    'IT',
                    'Art and Design',
                    'PPKn',
                    'IPS',
                ];

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
                    ->leftJoin('subjects', function($join){
                        $join->on('subjects.id', '=', 'subject_exams.subject_id');
                    })
                    ->select(
                        'students.id as student_id',
                        'students.name as student_name',
                        'exams.id as exam_id',
                        'exams.type_exam as type_exam',
                        'scores.score as score',
                        'subjects.name_subject as subject_name',
                    )
                    ->where('grades.id', $gradeId)
                    ->where('exams.semester', $semester)
                    ->where('exams.academic_year', $academic_year)
                    ->where('students.id', $id)
                    ->whereIn('exams.type_exam', [$homework, $exercise, $quiz, $project, $practical])
                    ->get();

                $scoresByStudent = $results->groupBy('student_id')->map(function ($scores) use ($order, $homework, $exercise, $quiz, $project, $practical) {
                    $student = $scores->first();
                    $scoresBySubject = $scores->groupBy('subject_name')->map(function ($subjectScores) use ($homework, $exercise, $quiz, $project, $practical) {
                
                        $homeworkScores = $subjectScores->where('type_exam', $homework)->pluck('score');
                        $exerciseScores = $subjectScores->where('type_exam', $exercise)->pluck('score');
                        $quizScores = $subjectScores->where('type_exam', $quiz)->pluck('score');
                        $projectScores = $subjectScores->where('type_exam', $project)->pluck('score');
                        $practicalScores = $subjectScores->where('type_exam', $practical)->pluck('score');
                
                        return [
                            'subject_name' => $subjectScores->first()->subject_name,
                            'scores' => [
                                'homework' => $homeworkScores->all(),
                                'exercise' => $exerciseScores->all(),
                                'quiz' => $quizScores->all(),
                                'project' => $projectScores->all(),
                                'practical' => $practicalScores->all()
                            ],
                        ];
                    });

                    $isRestricted = collect($scoresBySubject)->contains(function ($subject) {
                        return collect($subject['scores'])->contains(function ($examScores) {
                            return collect($examScores)->contains(function ($score) {
                                return $score !== null && $score <= 70;
                            });
                        });
                    });
                
                    // Urutkan subjek berdasarkan urutan dalam $order
                    $orderedSubjects = collect($order)->mapWithKeys(function ($subject) use ($scoresBySubject) {
                        return [$subject => $scoresBySubject->get($subject, ['subject_name' => $subject, 'scores' => []])];
                    });
                
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'subjects' => $orderedSubjects->values()->all(),
                        'isRestricted' => $isRestricted,
                    ];
                })->values()->all();
            }

            $monthlyActivity = MonthlyActivity::get();

            if($gradeId <= 7){
                $grades = 'lower';
            }
            elseif($gradeId > 7){
                $grades = 'upper';
            }

            $studentMonthlyActivity = Student_Monthly_Activity::join('students', 'students.id', '=', 'student_monthly_activities.student_id')
                ->join('monthly_activities', 'monthly_activities.id', '=', 'student_monthly_activities.monthly_activity_id')
                ->where('student_monthly_activities.student_id', $id)
                ->where('student_monthly_activities.semester', $semester)
                ->where('student_monthly_activities.academic_year', $academic_year)
                ->select('student_monthly_activities.*', 'monthly_activities.name as name_activity')
                ->orderBy('students.name', 'asc')
                ->get();

            // dd($studentMonthlyActivity);

            $countMA = MonthlyActivity::count();

            $data = [
                'semester'      => $semester,
                'student'       => $student,
                'classTeacher'  => $classTeacher,
                'subjectReports'=> $scoresByStudent,
                'attendance'    => $attendancesByStudent,
                'academicYear'  => $academicYear,
                'homework'      => $homework,
                'exercise'      => $exercise,
                'quiz'          => $quiz,
                'project'       => $project,
                'practical'     => $practical,
                'ct'            => $ct,
                'cs'            => $cs,
                'ls'            => $ls,
                'les'           => $les,
                'saed'          => $saed,
                'monthlyAct'    => $studentMonthlyActivity,
                'scoreMonthly'  => $studentMonthlyActivity,
                'countMA'       => $countMA,
            ];

            // dd($studentMonthlyActivity);

            // $pdf = app('dompdf.wrapper');
            // $pdf->set_option('isRemoteEnabled', true);
            // $pdf->set_option('isHtml5ParserEnabled', true);
            // $pdf->loadView('components.report.pdf.mid_semester-pdf', $data)->setPaper('a5', 'portrait');
            // return $pdf->stream($student->student_name . '_semester' . $semester . '.pdf');
            return $data;
            
            // return view('components.report.pdf.mid_semester-pdf', $data);

        } catch (Exception $err) {
            dd($err);
        }
    }
}







