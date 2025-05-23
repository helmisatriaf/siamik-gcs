<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Teacher_subject;
use App\Models\Grade_exam;
use App\Models\Subject_exam;
use App\Models\Type_exam;
use App\Models\Score;
use App\Models\Student_exam;
use App\Models\Relationship;
use App\Models\Chinese_higher;
use App\Models\Chinese_lower;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuestionStatus;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\Fpdi;
use Exception;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
   public function index(Request $request)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'scorings',
            'child' => 'assessment',
         ]);

         $subjects = Subject::orderBy('name_subject', 'asc')->get();
         $grades = Grade::all();
         $teachers = Teacher::orderBy('name')->get();
         $type = Type_exam::orderBy('name')->get();

         $form = (object) [
            'subjects' => $request->subject ?? 'all',
            'grades' => $request->grade ?? 'all',
            'teachers' => $request->teacher ?? 'all',
            'type' => $request->type ?? 'all',
            'search' => $request->search ?? null,
         ];

         $data = Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.semester', session('semester'))
            ->where('exams.academic_year', session('academic_year'))
            ->orderByRaw('exams.is_active = 0 ASC');

         // Jika search diisi, abaikan filter lainnya
         if (!is_null($form->search)) {
            $data->where('exams.name_exam', 'like', '%' . $form->search . '%');
         } else {
            // Jika search tidak diisi, gunakan filter lainnya
            if ($form->subjects !== 'all') {
               $data->where('subjects.id', $form->subjects);
            }
            if ($form->grades !== 'all') {
               $data->where('grades.id', $form->grades);
            }
            if ($form->teachers !== 'all') {
               $data->where('teachers.id', $form->teachers);
            }
            if ($form->type !== 'all') {
               $data->where('type_exams.id', $form->type);
            }
         }

         // Akhir dari query
         $data = $data->orderBy('grades.id', 'asc')
            ->select(
               'exams.*',
               'grades.name as grade_name',
               'grades.class as grade_class',
               'subjects.name_subject as subject_name',
               'grades.id as grade_id',
               'subjects.id as subject_id',
               'teachers.name as teacher_name',
               'type_exams.name as type_exam'
            )
            ->paginate(15);

         return view('components.exam.data-exam', [
            'data' => $data,
            'form' => $form,
            'grades' => $grades,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'type' => $type,
         ]);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pageCreate()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'scorings',
            'child' => 'assessment',
         ]);

         $dataTeacher = Teacher::get();
         $dataSubject = Subject::get();
         $dataGrade   = Grade::get();
         $dataType    = Type_exam::orderBy('name', 'asc')->get();

         $data = [
            'teacher' => $dataTeacher,
            'subject' => $dataSubject,
            'grade' => $dataGrade,
            'type_exam' => $dataType,
         ];

         // dd($data);

         return view('components.exam.create-exam')->with('data', $data);
      } catch (Exception) {
         return abort(500);
      }
   }

   public function actionPost(Request $request)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'database exam',
         ]);
         // dd($request);
         // dd(session('section_id'));
         $rules = [
            'type_exam' => $request->type_exam,
            'name_exam' => $request->name,
            'is_active' => 1,
            'date_exam' => $request->date_exam,
            'materi' => $request->materi,
            'teacher_id' => $request->teacher_id,
            'created_at' => now(),
         ];

         if (Exam::where('name_exam', $request->name)
            ->where('teacher_id', $request->teacher_id)
            ->where('section_id', session('section_id'))
            ->where('semester', session('semester'))
            ->where('academic_year', session('academic_year'))
            ->first()
         ) {
            DB::rollBack();
            return redirect('/' . session('role') . '/dashboard/exam/create')->withErrors([
               'name' => 'Exams is has been created ',
            ])->withInput($rules);
         }

         $file = $request->file('upload_file');

         $subject = Subject::where('id', $request->subject_id)->value('name_subject');
         $grade   = Grade::where('id', $request->grade_id)
            ->selectRaw("CONCAT(name, '-', class) as grade_name")
            ->first();

         $teacher = Teacher::where('id', $request->teacher_id)->value('name');
         $typeExam = Type_exam::where('id', $request->type_exam)->value('name');
         $time = session('academic_year') . '_' . session('semester');

         switch($request->model){
            // MENYIMPAN DATA MULTIPLE CHOICE DAN ESSAY (COMBINE)
            case "mce" : 
               $post = [
                  'section_id' => session('section_id'),
                  'type_exam' => $request->type_exam,
                  'name_exam' => $request->name,
                  'date_exam' => $request->date_exam,
                  'materi' => $request->materi,
                  'teacher_id' => $request->teacher_id,
                  'is_active' => 1,
                  'model'    => "mce",
                  'hasFile' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
   
               $exam = Exam::create($post);
   
               foreach($request->question_mc as $mc){
                  $qmc = [
                     'exam_id' => $exam->id,
                     'text' => $mc['question'],
                     'type' => 'mc',
                     'semester' => session('semester'),
                     'academic_year' => session('academic_year'),
                  ];
                  $question = Question::create($qmc);
   
                  foreach($mc['answer'] as $index => $a){
                     // dd($a);
                     $amc = [
                        'question_id' => $question->id,
                        'answer_text' => $a,
                        'is_correct'  => $index == $mc['question_key'] ? TRUE : FALSE,
                     ];
                     Answer::create($amc);
                  }
               }
   
               foreach($request->essay as $essay){
                  $qe = [
                     'exam_id' => $exam->id,
                     'text' => $essay['question'],
                     'type' => 'essay',
                     'semester' => session('semester'),
                     'academic_year' => session('academic_year'),
                  ];
                  $questEssay = Question::create($qe);
                  
                  $ae = [
                     'question_id' => $questEssay->id,
                     'answer_text' => $essay['answer'],
                     'is_correct'  => TRUE,
                  ];
                  Answer::create($ae);
               }
               break;

            // MENYIMPAN DATA MULTPIPLE CHOICE SAJA
            case "mc" :
               $post = [
                  'section_id' => session('section_id'),
                  'type_exam' => $request->type_exam,
                  'name_exam' => $request->name,
                  'date_exam' => $request->date_exam,
                  'materi' => $request->materi,
                  'teacher_id' => $request->teacher_id,
                  'is_active' => 1,
                  'model'    => "mc",
                  'hasFile' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
   
               $exam = Exam::create($post);
   
               foreach($request->question_mc as $mc){
                  $qmc = [
                     'exam_id' => $exam->id,
                     'text' => $mc['question'],
                     'type' => 'mc',
                     'semester' => session('semester'),
                     'academic_year' => session('academic_year'),
                  ];
                  $question = Question::create($qmc);
   
                  foreach($mc['answer'] as $index => $a){
                     // dd($a);
                     $amc = [
                        'question_id' => $question->id,
                        'answer_text' => $a,
                        'is_correct'  => $index == $mc['question_key'] ? TRUE : FALSE,
                     ];
                     Answer::create($amc);
                  }
               }
               break;
            // MENYIMPAN DATA ESSAY SAJA
            case "essay" :
               $post = [
                  'section_id' => session('section_id'),
                  'type_exam' => $request->type_exam,
                  'name_exam' => $request->name,
                  'date_exam' => $request->date_exam,
                  'materi' => $request->materi,
                  'teacher_id' => $request->teacher_id,
                  'is_active' => 1,
                  'model'    => "essay",
                  'hasFile' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];
   
               $exam = Exam::create($post);

               foreach($request->essay as $essay){
                  $qe = [
                     'exam_id' => $exam->id,
                     'text' => $essay['question'],
                     'type' => 'essay',
                     'semester' => session('semester'),
                     'academic_year' => session('academic_year'),
                  ];
                  $questEssay = Question::create($qe);
                  
                  $ae = [
                     'question_id' => $questEssay->id,
                     'answer_text' => $essay['answer'],
                     'is_correct'  => TRUE,
                  ];
                  Answer::create($ae);
               }
               break;
            // MENYIMPAN ASSESSMENT UPLOAD FILE / NO FILE
            default :
               if($file !== null){
                  $fileName = ucwords($typeExam).'_'.ucwords(strtolower($request->name)).'_'.$subject .'_'.$grade->grade_name.'_'.$time.'.pdf';
                  $filePath = $file->storeAs('public/file/assessment', $fileName);
      
                  $post = [
                     'section_id' => session('section_id'),
                     'type_exam' => $request->type_exam,
                     'name_exam' => $request->name,
                     'date_exam' => $request->date_exam,
                     'materi' => $request->materi,
                     'teacher_id' => $request->teacher_id,
                     'created_at' => now(),
                     'is_active' => 1,
                     'hasFile' => $request->file('upload_file') ? 1 : 0,
                     'file_name' => $fileName,
                     'file_path' => $filePath,
                     'semester' => session('semester'),
                     'academic_year' => session('academic_year'),
                  ];
               } else {
                  $post = [
                     'section_id' => session('section_id'),
                     'type_exam' => $request->type_exam,
                     'name_exam' => $request->name,
                     'date_exam' => $request->date_exam,
                     'materi' => $request->materi,
                     'teacher_id' => $request->teacher_id,
                     'created_at' => now(),
                     'is_active' => 1,
                     'hasFile' => $request->file('upload_file') ? 1 : 0,
                     'semester' => session('semester'),
                     'academic_year' => session('academic_year'),
                  ];
               }
               Exam::create($post);
         }

         $getLastIdExam = DB::table('exams')->latest('id')->value('id');
         $postSubjectExam = [
            'subject_id' => $request->subject_id,
            'exam_id' => $getLastIdExam,
            'academic_year' => session('academic_year'),
            'created_at' => now(),
         ];
         $postGradeExam = [
            'grade_id' => $request->grade_id,
            'exam_id' => $getLastIdExam,
            'academic_year' => session('academic_year'),
            'created_at' => now(),
         ];

         Subject_exam::create($postSubjectExam);
         Grade_exam::create($postGradeExam);

         $checkSubject = Subject_exam::where('subject_exams.exam_id', '=', $getLastIdExam)->value('subject_id');
         $subject = Subject::where('id', $checkSubject)->value('name_subject');

         if (strtolower($subject) == "religion islamic") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
               ->where('is_active', true)
               ->where('religion', '=', 'islam')
               ->pluck('id')->toArray();

            for ($i = 0; $i < sizeof($getStudentId); $i++) {
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         } elseif (strtolower($subject) == "religion catholic") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
               ->where('is_active', true)
               ->where('religion', '=', 'Catholic Christianity')
               ->pluck('id')->toArray();

            for ($i = 0; $i < sizeof($getStudentId); $i++) {
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         } elseif (strtolower($subject) == "religion christian") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
               ->where('is_active', true)
               ->where('religion', '=', 'Protestant Christianity')
               ->pluck('id')->toArray();

            for ($i = 0; $i < sizeof($getStudentId); $i++) {
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         } elseif (strtolower($subject) == "religion buddhism") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
               ->where('is_active', true)
               ->where('religion', '=', 'Buddhism')
               ->pluck('id')->toArray();

            for ($i = 0; $i < sizeof($getStudentId); $i++) {
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         } elseif (strtolower($subject) == "religion hinduism") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
               ->where('is_active', true)
               ->where('religion', '=', 'Hinduism')
               ->pluck('id')->toArray();

            for ($i = 0; $i < sizeof($getStudentId); $i++) {
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         } elseif (strtolower($subject) == "religion confucianism") {
            $getStudentId = Student::where("grade_id", $request->grade_id)
               ->where('is_active', true)
               ->where('religion', '=', 'Confucianism')
               ->pluck('id')->toArray();

            for ($i = 0; $i < sizeof($getStudentId); $i++) {
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         } elseif (strtolower($subject) == "chinese lower") {
            $chineseLowerStudent = Chinese_lower::where('grade_id', $request->grade_id)->pluck('student_id')->toArray();

            // $getStudentId = Student::where("grade_id", $request->grade_id)->pluck('id')->toArray();

            for ($i = 0; $i < sizeof($chineseLowerStudent); $i++) {
               $postStudentExam = [
                  'student_id' => $chineseLowerStudent[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $chineseLowerStudent[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $Student_exam = Student_exam::create($postStudentExam);
               $scores = Score::create($score);
            }
         } elseif (strtolower($subject) == "chinese higher") {
            $chineseHigherStudent = Chinese_higher::where('grade_id', $request->grade_id)->pluck('student_id')->toArray();

            // $getStudentId = Student::where("grade_id", $request->grade_id)->pluck('id')->toArray();
            // dd($request->grade_id);

            // dd($chineseHigherStudent);

            for ($i = 0; $i < sizeof($chineseHigherStudent); $i++) {
               $postStudentExam = [
                  'student_id' => $chineseHigherStudent[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $chineseHigherStudent[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $Student_exam = Student_exam::create($postStudentExam);
               $scores = Score::create($score);
            }
         } else {
            $getStudentId = Student::where("grade_id", $request->grade_id)
               ->where('is_active', true)
               ->pluck('id')->toArray();

            for ($i = 0; $i < sizeof($getStudentId); $i++) {
               $postStudentExam = [
                  'student_id' => $getStudentId[$i],
                  'exam_id' => $getLastIdExam,
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               $score = [
                  'exam_id' => $getLastIdExam,
                  'subject_id' => $request->subject_id,
                  'grade_id' => $request->grade_id,
                  'teacher_id' => $request->teacher_id,
                  'type_exam_id' => $request->type_exam,
                  'student_id' => $getStudentId[$i],
                  'score' => 0,
                  'semester' => session('semester'),
                  'academic_year' => session('academic_year'),
                  'created_at' => now(),
               ];

               Student_exam::create($postStudentExam);
               Score::create($score);
            }
         }

         session()->flash('after_create_exam');

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

   public function getById($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'scorings',
         'child' => 'assessment',
      ]);

      try {
         // dd($id);
         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         if (session('role') == 'superadmin' || session('role') == 'admin') {
            // return view('components.exam.detail-exam')->with('data', $data);
            $status = Score::join('students', 'students.id', 'scores.student_id')
               ->where('exam_id', $id)
               ->where('hasFile', '=', 1)
               ->select('scores.*', 'students.name as student_name', 'students.profil as student_profile')
               ->get();

            $notYet = Score::join('students', 'students.id', '=', 'scores.student_id')
               ->where('scores.exam_id', $id)
               ->where(function ($query) {
                  $query->where('scores.hasFile', 0)
                     ->orWhereNull('scores.hasFile'); // Cek juga jika NULL
               })
               ->select('scores.*', 'students.name as student_name', 'students.profil as student_profile')
               ->get();

            return view('components.teacher.detail-exam-teacher', [
               'data' => $data,
               'status' => $status,
               'notyet' => $notYet,
            ]);
         } elseif (session('role') == 'teacher') {
            if($data->model !== null){

               switch($data->model){
                  case 'mce' :
                     $status = QuestionStatus::join('students', 'students.id', 'question_statuses.student_id')
                        ->join('scores', 'scores.student_id', 'question_statuses.student_id')
                        ->where('question_statuses.exam_id', $id)
                        ->where('scores.exam_id', $id)
                        ->select('scores.*','students.name as student_name',  'students.profil as student_profile')
                        ->get();

                     $notYet = Score::join('students', 'students.id', '=', 'scores.student_id')
                        ->where('exam_id', $id)
                        ->where('score', '=', 0)
                        ->select('scores.*', 'students.name as student_name',  'students.profil as student_profile')
                        ->get();
                     break;

                  case 'mc' :
                     $status = Score::join('students', 'students.id', 'scores.student_id')
                        ->where('exam_id', $id)
                        ->where('score', '!=', 0)
                        ->select('scores.*', 'students.name as student_name',  'students.profil as student_profile')
                        ->get();
                     $notYet = Score::join('students', 'students.id', '=', 'scores.student_id')
                        ->where('exam_id', $id)
                        ->where('score', '=', 0)
                        ->select('scores.*', 'students.name as student_name',  'students.profil as student_profile')
                        ->get();
                     break;

                  case 'essay' :
                     $status = QuestionStatus::join('students', 'students.id', 'question_statuses.student_id')
                        ->join('scores', 'scores.student_id', 'question_statuses.student_id')
                        ->where('question_statuses.exam_id', $id)
                        ->where('scores.exam_id', $id)
                        ->select('scores.*','students.name as student_name',  'students.profil as student_profile')
                        ->get();
         
                     $notYet = Score::join('students', 'students.id', '=', 'scores.student_id')
                        ->where('exam_id', $id)
                        ->where('score', '=', 0)
                        // ->where('scores.student_id', '!=', function($query) use ($id){
                        //    $query->select('student_id')
                        //       ->from('question_statuses')
                        //       ->where('exam_id', $id);
                        // })
                        ->select('scores.*', 'students.name as student_name',  'students.profil as student_profile')
                        ->get();
                        break;
               }
            }
            else {
               if($data->type_exam == "Participation"){
                  $status = Score::join('students', 'students.id', 'scores.student_id')
                     ->where('exam_id', $id)
                     ->select('scores.*', 'students.name as student_name',  'students.profil as student_profile')
                     ->get();
                  $notYet = null;
               }
               else{
                  $status = Score::join('students', 'students.id', 'scores.student_id')
                     ->where('exam_id', $id)
                     ->where('hasFile', '=', 1)
                     ->select('scores.*', 'students.name as student_name',  'students.profil as student_profile')
                     ->get();
      
                  $notYet = Score::join('students', 'students.id', '=', 'scores.student_id')
                     ->where('exam_id', $id)
                     ->where('hasFile', '=', null)
                     ->select('scores.*', 'students.name as student_name',  'students.profil as student_profile')
                     ->get();
               }
            }
            $questions = Question::with(['answer'])->where('exam_id', $id)->get();

            // dd($status);
            return view('components.teacher.detail-exam-teacher', [
               'data' => $data,
               'status' => $status,
               'notyet' => $notYet,
               'questions' => $questions,
            ]);
         }
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function getByIdSession()
   {
      session()->flash('page',  $page = (object)[
         'page' => 'scorings',
         'child' => 'assessment',
      ]);

      $id = session('exam_id');

      try {
         if (session('role') == 'student') {
            $student = Student::where('user_id', session('id_user'))->value('id');
            $getStatus = Score::where('exam_id', $id)
               ->where('student_id', $student)
               ->first();

            $status = $getStatus->hasFile == 1 ? true : false;
            $profile = Student::where('user_id', session('id_user'))->value('profil');
            $statusQuestion = QuestionStatus::where('exam_id', $id)
               ->where('student_id', $student)
               ->where('semester', session('semester'))
               ->where('academic_year', session('academic_year'))
               ->first();
         } elseif (session('role') == 'parent') {
            $getStatus = Score::where('exam_id', $id)
               ->where('student_id', session('studentId'))
               ->first();
            $status = $getStatus->hasFile == 1 ? true : false;
            $profile = Student::where('id', session('studentId'))->value('profil');
            $statusQuestion = QuestionStatus::where('exam_id', $id)
               ->where('student_id', session('studentId'))
               ->where('semester', session('semester'))
               ->where('academic_year', session('academic_year'))
               ->first();
         }


         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         // dd($statusQuestion);

         return view('components.student.detail-exam-student', [
            'data' => $data,
            'status' => $status,
            'getStatus' => $getStatus,
            'profile' => $profile,
            'statusQuestion' => $statusQuestion,
         ]);
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function pageEdit($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'scorings',
            'child' => 'assessment',
         ]);

         $dataExam = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.id as grade_id', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.id as subject_id', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.id as type_exam_id', 'type_exams.name as type_exam')
            ->first();

         $teacher    = Teacher::orderBy('id', 'ASC')->get();
         $subject    = Subject::orderBy('id', 'ASC')->get();
         $grade      = Grade::orderBy('id', 'ASC')->get();
         $typeExam   = Type_exam::orderBy('id', 'ASC')->get();

         $data = [
            'teacher'   => Teacher::orderBy('id', 'ASC')->get(),
            'subject'   => Subject::orderBy('id', 'ASC')->get(),
            'grade'     => Grade::orderBy('id', 'ASC')->get(),
            'typeExam'  => Type_exam::orderBy('name', 'ASC')->get(),
            'dataExam'  => $dataExam,
         ];

         // dd($data);
         if (session('role') == 'admin' || session('role') == 'superadmin') {
            return view('components.teacher.edit-exam-teacher')->with('data', $data);
         } elseif (session('role') == 'teacher') {
            return view('components.teacher.edit-exam-teacher')->with('data', $data);
         }
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function actionPut(Request $request, $id)
   {
      // DB::beginTransaction();
      try {

         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'database exam',
         ]);

         $rules = [
            'name_exam'  => $request->name,
            'type_exam'  => $request->type_exam,
            'date_exam'  => $request->date_exam,
            'materi'     => $request->materi,
            'teacher_id' => $request->teacher_id,
            'semester'   => session('semester'),
            'academic_year' => session('academic_year'),
            'updated_at' => now(),
         ];

         $validator = Validator::make($rules, [
            'name_exam'  => 'required|string',
            'type_exam'  => 'required|string',
            'date_exam'  => 'required|date',
            'materi'     => 'required|string',
            'teacher_id' => 'required|string',
         ]);

         if ($validator->fails()) {
            DB::rollBack();
            return redirect('/teacher/dashboard/exams/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
         }

         $check = Exam::where('name_exam', $request->name)->where('teacher_id', $request->teacher_id)->first();

         if ($check && $check->id != $id) {
            DB::rollBack();
            return redirect('/teacher/dashboard/exams/edit/' . $id)->withErrors(['name' => ["The exam name " . $request->name . " with grade " . $request->grade_name . " subject " . $request->Grade_subject . " teacher " . $request->teacher_name . " is already created !!!"]])->withInput($rules);
         }

         // dd($rules);

         Subject_exam::where('exam_id', $id)->update([
            'subject_id' => $request->subject_id,
         ]);
         Grade_exam::where('exam_id', $id)->update([
            'grade_id' => $request->grade_id,
         ]);
         Exam::where('id', $id)->update($rules);

         DB::commit();

         session()->flash('after_update_exam');

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

   public function teacherExam(Request $request)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'scorings',
            'child' => 'assessment',
         ]);

         $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');

         $subjects = Subject::join('teacher_subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->where('teacher_subjects.teacher_id', $getIdTeacher)
            ->where('teacher_subjects.academic_year', session('academic_year'))
            ->select('subjects.*')
            ->orderBy('name_subject', 'asc')
            ->distinct()
            ->get();

         $grades = Grade::all();
         $type = Type_exam::orderBy('name')->get();

         $form = (object) [
            'subjects' => $request->subject ?? 'all',
            'grades' => $request->grade ?? 'all',
            'type' => $request->type ?? 'all',
            'status' => $request->status ?? 'all',
            'search' => $request->search ?? null,
         ];

         $teacherSubject = Teacher_subject::where('teacher_id', $getIdTeacher)->pluck('subject_id')->toArray();
         $teacherGrade   = Teacher_subject::where('teacher_id', $getIdTeacher)->pluck('grade_id')->toArray();

         // dd($teacherSubject);
         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->whereIn('subject_exams.subject_id', $teacherSubject)
            ->whereIn('grade_exams.grade_id', $teacherGrade)
            ->where('exams.teacher_id', $getIdTeacher)
            ->where('exams.semester', session('semester'))
            ->where('exams.academic_year', session('academic_year'))
            ->orderBy('exams.created_at', 'desc')
            ->orderByRaw('exams.is_active = 0 ASC');

         // $data = Teacher_subject::where('teacher_subjects.teacher_id', $getIdTeacher)
         //    // Join tabel grade_exams dan grades
         //    ->join('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
         //    ->join('grade_exams', 'teacher_subjects.grade_id', '=', 'grade_exams.grade_id')
         //    ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')

         //    // Join tabel subject_exams dan subjects
         //    ->join('subject_exams', 'teacher_subjects.subject_id', '=', 'subject_exams.subject_id')
         //    ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')

         //    // Join tabel exams dan type_exams
         //    ->join('exams', 'subject_exams.exam_id', '=', 'exams.id')
         //    ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')

         //    // Filter berdasarkan semester dan tahun akademik
         //    ->where('exams.semester', session('semester'))
         //    ->where('exams.academic_year', session('academic_year'))

         //    // Pilih kolom yang diperlukan (gunakan DISTINCT untuk menghindari duplikasi)
         //    ->select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
         //    ->distinct()

         //    // Urutkan data
         //    ->orderByRaw('exams.is_active = 0 ASC');

         // Jika search diisi, abaikan filter lainnya
         if (!is_null($form->search)) {
            $data->where('exams.name_exam', 'like', '%' . $form->search . '%');
         } else {
            // Jika search tidak diisi, gunakan filter lainnya
            if ($form->subjects !== 'all') {
               $data->where('subjects.id', $form->subjects);
            }
            if ($form->grades !== 'all') {
               $data->where('grades.id', $form->grades);
            }
            if ($form->type !== 'all') {
               $data->where('type_exams.id', $form->type);
            }
            if ($form->status !== 'all') {
               $data->where('exams.is_active', $form->status);
            }
         }

         $data = $data->orderBy('grades.id', 'asc')
            ->select(
               'exams.*',
               'grades.name as grade_name',
               'grades.class as grade_class',
               'subjects.name_subject as subject_name',
               'grades.id as grade_id',
               'subjects.id as subject_id',
               'subjects.icon as icon',
               'teachers.name as teacher_name',
               'type_exams.name as type_exam'
            )
            ->paginate(15);

         // dd($data);  

         return view('components.teacher.data-exam-teacher', [
            'data' => $data,
            'form' => $form,
            'grades' => $grades,
            'subjects' => $subjects,
            'type' => $type,
         ]);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function detailTeacherExam($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'database exam',
         'child' => 'database exam',
      ]);

      try {
         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         // dd($data);

         return view('components.teacher.detail-exam')->with('data', $data);
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function createTeacherExam()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'scorings',
            'child' => 'assessment',
         ]);

         $id = session('id_user');

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');

         $dataTeacher = Teacher::where('id', $getIdTeacher)->get();

         // $dataSubject = Teacher_subject::join('subjects','subjects.id','=','teacher_subjects.subject_id')
         //    ->where('teacher_subjects.teacher_id', $getIdTeacher)
         //    ->where('academic_year', session('academic_year'))
         //    ->select('subjects.*')
         //    ->orderBy('subjects.name_subject', 'asc')
         //    ->get();

         $dataSubject = Subject::orderBy('name_subject', 'asc')
            ->get();

         $dataGrade = Teacher_subject::join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
            ->where('teacher_subjects.teacher_id', $getIdTeacher)
            ->where('academic_year', session('academic_year'))
            ->select('grades.*')
            ->distinct('grades.name')
            ->orderBy('grades.id', 'asc')
            ->get();

         $dataType    = Type_exam::orderBy('name', 'asc')->get();

         $data = [
            'teacher' => $dataTeacher,
            'subject' => $dataSubject,
            'grade' => $dataGrade,
            'type_exam' => $dataType,
         ];

         // dd($data);

         return view('components.teacher.create-exam-teacher')->with('data', $data);
      } catch (Exception $err) {
         dd($err);
         return abort(500);
      }
   }

   public function gradeExam(Request $request)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'scorings',
            'child' => 'assessment',
         ]);

         $setStudentFirst = session('studentId');
         $gradeIdStudent  = Student::where('students.id', $setStudentFirst)->value('grade_id');
         $subjects        = Grade::with(['subject' => function ($query) {
            $query->orderBy('name_subject', 'asc');
         }])
            ->where('grades.id', $gradeIdStudent)
            ->first();

         $form = (object) [
            'sort' => $request->order ?? 'all',
            'type' => $request->type ?? 'all',
         ];

         $sort = $request->order ?? 'all';
         $type = $request->type ?? 'all';
         $selectType    = Type_exam::get();

         if (session('role') == 'parent') {
            $getIdUser         = session('id_user');
            $id                = Relationship::where('user_id', $getIdUser)->value('id');
            $getIdStudent      = session('studentId');
            $gradeIdStudent    = Student::where('students.id', $getIdStudent)->value('grade_id');
            $subjects          = Grade::with(['subject' => function ($query) {
               $query->orderBy('name_subject', 'asc');
            }])
               ->where('grades.id', $gradeIdStudent)
               ->first();

            $data = Score::join('exams', 'exams.id', '=', 'scores.exam_id')
               ->join('grades', 'scores.grade_id', '=', 'grades.id')
               ->join('subjects', 'scores.subject_id', '=', 'subjects.id')
               ->join('teachers', 'scores.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'scores.type_exam_id', '=', 'type_exams.id')
               ->join('students', 'scores.student_id', '=', 'students.id')
               ->where('scores.student_id', $getIdStudent)
               ->where('scores.semester', session('semester'))
               ->where('scores.academic_year', session('academic_year'))
               ->when($sort !== 'all', function ($query) use ($sort) {
                  return $query->where('scores.subject_id', $sort);
               })
               ->when($type !== 'all', function ($query) use ($type) {
               return $query->where('type_exams.id', $type);
               })
               ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class',
                  'subjects.name_subject as subject_name', 'teachers.name as teacher_name',
                  'type_exams.name as type_exam' , 'scores.score as score', 'students.name as student_name',
                  'subjects.icon as icon')
               ->orderByRaw('exams.is_active = 1 desc')
               ->paginate(12);
         } elseif (session('role') == 'student') {
            $getIdUser     = session('id_user');
            $id            = Student::where('user_id', $getIdUser)->value('grade_id');
            $getGradeId    = Grade::where('id', $id)->value('id');
            $getIdStudent  = Student::where('user_id', $getIdUser)->value('id');
            $selectType    = Type_exam::get();

            $data = Score::join('exams', 'exams.id', '=', 'scores.exam_id')
               ->join('grades', 'scores.grade_id', '=', 'grades.id')
               ->join('subjects', 'scores.subject_id', '=', 'subjects.id')
               ->join('teachers', 'scores.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'scores.type_exam_id', '=', 'type_exams.id')
               ->join('students', 'scores.student_id', '=', 'students.id')
               ->where('scores.student_id', $getIdStudent)
               ->where('exams.semester', session('semester'))
               ->where('exams.academic_year', session('academic_year'))
               ->when($sort !== 'all', function ($query) use ($sort) {
                  return $query->where('scores.subject_id', $sort);
               })
               ->when($type !== 'all', function ($query) use ($type) {
                  return $query->where('type_exams.id', $type);
               })
               ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class',
                  'subjects.name_subject as subject_name', 'teachers.name as teacher_name',
                  'type_exams.name as type_exam' , 'scores.score as score', 'students.name as student_name',
                  'subjects.icon as icon')
               ->orderByRaw('exams.is_active = 1 desc')
               ->orderBy('exams.created_at', 'desc')
               ->paginate(12);
         }


         return view('components.student.data-exam-student', [
            "data" => $data,
            "form" => $form,
            "subjects" => $subjects,
            "type" => $selectType,
         ]);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function detailGradeExam($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'database exam',
         'child' => 'database exam',
      ]);

      try {
         $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         // dd($data);

         return view('components.teacher.detail-exam')->with('data', $data);
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function pagePDF($id)
   {
      try {

         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'database grades',
         ]);

         $data = Grade::with(['student' => function ($query) {
            $query->where('is_active', true)->orderBy('name', 'asc');
         }])->find($id);

         $nameFormatPdf = Carbon::now()->format('YmdHis') . mt_rand(1000, 9999) . '_' . date('d-m-Y') . '_' . $data->name . '_' . $data->class . '.pdf';

         $pdf = app('dompdf.wrapper');
         $pdf->loadView('components.grade.pdf.dom-pdf', ['data' => $data])->setPaper('a4', 'portrait');
         return $pdf->stream($nameFormatPdf);
      } catch (Exception $err) {

         return dd($err);
      }
   }

   public function doneExam($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'database exam',
            'child' => 'database exam',
         ]);

         $rules = [
            'is_active' => 0,
         ];

         Exam::where('id', $id)->update($rules);

         session()->flash('after_done_exam');

         if (session('role') == 'superadmin') {
            return redirect('superadmin/exams');
         } elseif (session('role') == 'admin') {
            return redirect('admin/exams');
         } elseif (session('role') == 'teacher') {
            return redirect('teacher/dashboard/exam');
         }
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function setAssessmentId(Request $request)
   {
      session(['exam_id' => $request->id]);
      return response()->json(['success' => true]);
   }

   public function delete(Request $request)
   {
      Exam::where('id', $request->exam_id)->delete();
      return response()->json([
         'success',
         true,
      ]);
   }

   public function changeFile(Request $request)
   {
      try {

         $file = $request->file('upload_file');
         $exam = Exam::where('id', $request->exam_id)->first();
         $subjectId = Subject_exam::where('exam_id', $request->exam_id)->value('subject_id');
         $subject = Subject::where('id', $subjectId)->value('name_subject');

         $gradeId = Grade_exam::where('exam_id', $request->exam_id)->value('grade_id');
         $grade   = Grade::where('id', $gradeId)
            ->selectRaw("CONCAT(name, '-', class) as grade_name")
            ->first();

         $teacher = Teacher::where('id', $exam->teacher_id)->value('name');
         $typeExam = Type_exam::where('id', $exam->type_exam)->value('name');
         $time = session('academic_year') . '_' . session('semester');
         $fileName = ucwords($typeExam) . '_' . ucwords(strtolower($exam->name_exam)) . '_' . $subject . '_' . $grade->grade_name . '_' . ucwords(strtolower($teacher) . '_' . $time . '.pdf');
         $filePath = $file->storeAs('public/file/assessment', $fileName);

         $checkFile = Exam::where('id', $request->exam_id)->value('file_name');

         if ($checkFile !== null) {
            if (Storage::exists($checkFile)) {
               Storage::delete('public/file/assessment/' . $checkFile);
            }
         }

         $data = [
            'hasFile' => True,
            'file_name' => $fileName,
            'file_path' => $filePath,
         ];

         Exam::where('id', $request->exam_id)
            ->update($data);

         session()->flash('after_change_file');
         return redirect()->back();
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function uploadAnswer(Request $request)
   {
      // dd($request);
      $id   = session('exam_id');
      $file = $request->file('upload_file');
      $exam = Exam::where('id', $id)->first();
      $subjectId = Subject_exam::where('exam_id', $id)->value('subject_id');
      $subject = Subject::where('id', $subjectId)->value('name_subject');
      $gradeId = Grade_exam::where('exam_id', $id)->value('grade_id');
      $grade   = Grade::where('id', $gradeId)->selectRaw("CONCAT(name, '-', class) as grade_name")->first();
      $student  = Student::where('user_id', session('id_user'))->first();
      $time = session('academic_year') . '_' . session('semester');
      $fileName = 'Answer_' . ucwords(strtolower($exam->name_exam)) . '_' . $subject . '_' . $grade->grade_name . '_' . ucwords(strtolower($student->name) . '_' . $time . '.pdf');

      // Simpan file sementara
      $tempPath = $file->storeAs('temp', $fileName);

      // Kompres file PDF
      $compressedFilePath = storage_path("app/public/file/answers/" . $fileName);
      $this->compressPdf(storage_path("app/" . $tempPath), $compressedFilePath);

      // Hapus file sementara
      // Storage::delete($tempPath);

      // Cek apakah file lama ada
      // $checkFile = Score::where('exam_id', $id)
      //    ->where('student_id', $student->id)
      //    ->value('file_name');

      // if ($checkFile !== null) {
      //    if (Storage::exists('public/file/answers/' . $checkFile)) {
      //       Storage::delete('public/file/answers/' . $checkFile);
      //    }
      // }

      $data = [
         'hasFile' => true,
         'file_name' => $fileName,
         'file_path' => 'public/file/answers/' . $fileName,
         'time_upload' => now(),
      ];

      Score::where('exam_id', $id)
         ->where('student_id', $student->id)
         ->update($data);

      session()->flash('after_upload_answer');
      return redirect()->back();
   }

   /**
    * Fungsi untuk mengompres PDF menggunakan FPDI + TCPDF
    */
   private function compressPdf($inputPath, $outputPath)
   {
      $pdf = new FPDI();

      $pageCount = $pdf->setSourceFile($inputPath);

      for ($i = 1; $i <= $pageCount; $i++) {
         $tplIdx = $pdf->importPage($i);
         $pdf->AddPage();
         $pdf->useTemplate($tplIdx, 10, 10, 190); // Mengurangi ukuran halaman

         // Kompres gambar dalam PDF
         $pdf->SetCompression(true);
      }

      $pdf->Output($outputPath, 'F');
   }

   public function detailWorkplace(){
      try{
         if(session('role') == 'teacher'){
            $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', session('exam_id'))
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();
         
            $questions = Question::with(['answer'])->where('exam_id', session('exam_id'))->get();
         }
         elseif(session('role') == 'student' || session('role') == 'parent'){
            if(session('role') == 'student'){
               $studentId = Student::where('user_id', session('id_user'))->value('id');
            }
            elseif(session('role') == 'parent'){
               $studentId = session('studentId');
            }
   
            $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
               ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
               ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
               ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
               ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
               ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
               ->where('exams.id', session('exam_id'))
               ->where('exams.academic_year', session('academic_year'))
               ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
               ->first();
            
            $questions = Question::with(['answer', 'studentAnswer' => function($query) use($studentId){
               $query->where('student_id', $studentId);
            }])->where('exam_id', session('exam_id'))->get();
         }
         else{
            $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', session('exam_id'))
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();
         
            $questions = Question::with(['answer'])->where('exam_id', session('exam_id'))->get();
         }

         return view('components.exam.detail-working-exam', [
            'questions' => $questions,
            'assessment' => $assessment,
         ]);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function workplace()
   {
      try {
         $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', session('exam_id'))
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         $questions = Question::with(['answer'])->where('exam_id', session('exam_id'))->get();

         return view('components.exam.working-exam', [
            'questions' => $questions,
            'assessment' => $assessment,
         ]);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function getWorkId($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'scorings',
         'child' => 'assessment',
      ]);

      try {
         $data = Exam::with(['question.answer'])
            ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.id', $id)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->first();

         return view('components.exam.edit-question', [
            'data' => $data,
         ]);
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function actionUpdateQuestion(Request $request)
   {
      try {
         // dd($request);

         switch($request->model){
            case 'mce':
               // MULTIPLE CHOICE
               // QUESTION
               foreach($request->question_mc as $mc){
                  Question::where('id', $mc['question_id'])->update([
                     'text' => $mc['question'],
                     'updated_at' => now(),
                  ]);
                  
                  // ANSWER
                  foreach($mc['answer'] as $index => $mca){
                     Answer::where('id', $index)->update([
                        'answer_text' => $mca,
                        'is_correct' => $mc['question_key'] == $index ? TRUE : FALSE,
                        'updated_at' => now(),
                     ]);
                  }
               }

               // ESSAY
               // QUESTION
               foreach($request->essay as $essay){
                  Question::where('id', $essay['question_id'])->update([
                     'text' => $essay['question'],
                     'updated_at' => now(),
                  ]);

                  // ANSWER
                  Answer::where('question_id', $essay['question_id'])->update([
                     'answer_text' => $essay['answer'],
                  ]);
               }
               break;

            case 'mc':
               foreach($request->question_mc as $mc){
                  Question::where('id', $mc['question_id'])->update([
                     'text' => $mc['question'],
                     'updated_at' => now(),
                  ]);
                  
                  // ANSWER
                  foreach($mc['answer'] as $index => $mca){
                     Answer::where('id', $index)->update([
                        'answer_text' => $mca,
                        'is_correct' => $mc['question_key'] == $index ? TRUE : FALSE,
                        'updated_at' => now(),
                     ]);
                  }
               }
               break;
            case 'essay' :
               foreach($request->essay as $essay){
                  Question::where('id', $essay['question_id'])->update([
                     'text' => $essay['question'],
                     'updated_at' => now(),
                  ]);

                  // ANSWER
                  Answer::where('question_id', $essay['question_id'])->update([
                     'answer_text' => $essay['answer'],
                  ]);
               }
         }

         session()->flash('success_edit_question');
         return redirect()->back();
      } catch (Exception $err) {
         dd($err);
      }
   }
}
