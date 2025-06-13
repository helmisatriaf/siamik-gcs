<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\Grade_exam;
use App\Models\Grade_subject;
use App\Models\Subject_exam;
use App\Models\Exam;
use App\Models\Student_relationship;
use App\Models\Relationship;
use App\Models\Attendance;
use App\Models\Student_eca;
use App\Services\BillingService;
use App\Models\Master_academic;
use App\Models\Chinese_lower;
use App\Models\Chinese_higher;
use App\Models\Plan_visit;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
   protected $billingService;

   public function __construct(BillingService $billingService)
   {
      $this->billingService = $billingService;
   }

   public function index()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'dashboard',
            'child' => 'dashboard',
         ]);

         $checkRole = session('role');
         // dd(session('id_user'));

         if ($checkRole == 'admin' || $checkRole == 'superadmin') {
            $totalStudent  = Student::where('is_active', true)->orderBy('created_at', 'desc')->get()->count('id');
            $totalTeacher  = Teacher::where('is_active', true)->get()->count('id');
            $totalGrade    = Grade::all()->count('id');
            $totalExam     = Exam::where('semester', session('semester'))
               ->where('academic_year', session('academic_year'))
               ->get()->count('id');

            $studentData   = Student::where('is_active', true)
               ->join('grades', 'grades.id', '=', 'students.grade_id')
               ->select('students.*', 'grades.name as grade_name', 'grades.class as grade_class')
               ->orderByRaw('grades.id ASC, students.name ASC')
               ->get();

            $teacherData   = Teacher::where('is_active', true)->orderBy('name', 'asc')->get();

            $examData  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
               ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
               ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
               ->where('exams.semester', session('semester'))
               ->where('exams.academic_year', session('academic_year'))
               ->where('exams.is_active', true)
               ->get();

            foreach ($examData as $ed) {
               $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
                  ->where('exam_id', $ed->id)
                  ->value('name_subject');
            };

            $gradeData     = Grade::with(['gradeTeacher' => function ($query) {
               $query->where('academic_year', session('academic_year'));
            }])->get();

            $subjectData   = Subject::all();

            $data = [
               'totalStudent' => (int)$totalStudent,
               'totalTeacher' => (int)$totalTeacher,
               'totalGrade'   => (int)$totalGrade,
               'totalExam'    => (int)$totalExam,
               'grade' => $gradeData,
               'subject' => $subjectData,
               'exam' => $examData,
               'dataTeacher' => $teacherData,
               'dataStudent' => $studentData,
            ];

            // dd($data);
            return view('components.dashboardtes')->with('data', $data);
         } elseif ($checkRole == 'teacher') {
            $id = Teacher::where('user_id', session('id_user'))->value('id');

            $dataTeacher  = Teacher::where('id', $id)->get();
            $gradeTeacher = Teacher_grade::join('grades', 'teacher_grades.grade_id', '=', 'grades.id')
               ->where('teacher_id', $id)
               ->where('academic_year', session('academic_year'))
               ->select('grades.*')
               ->get();

            $teacherSubject = Teacher_subject::join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
               ->join('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
               ->where('teacher_id', $id)
               ->where('academic_year', session('academic_year'))
               ->select(
                  'subjects.*',
                  'grades.id as grade_id',
                  DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name")
               )
               ->orderBy('subjects.name_subject', 'asc')
               ->orderBy('teacher_subjects.grade_id', 'asc')
               ->get();

            $dataExam = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
               ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
               ->select(
                  'exams.*',
                  'type_exams.name as type_exam_name',
                  'grades.name as grade_name',
                  'grades.class as grade_class'
               )
               ->where('exams.teacher_id', $id)
               ->where('exams.semester', session('semester'))
               ->where('exams.academic_year', session('academic_year'))
               ->where('exams.is_active', TRUE)
               // Prioritaskan is_active = 0 terlebih dahulu, lalu urutkan berdasarkan date_exam terbaru
               ->orderByRaw('is_active = 0 ASC, date_exam DESC')
               ->get();


            foreach ($dataExam as $ed) {
               $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
                  ->where('exam_id', $ed->id)
                  ->value('name_subject');
            };

            $student = Teacher_grade::join('students', 'students.grade_id', '=', 'teacher_grades.grade_id')
               ->join('grades', 'grades.id', '=', 'students.grade_id')
               ->where('teacher_id', $id)
               ->where('is_active', true)
               ->where('teacher_grades.academic_year', session('academic_year'))
               ->select('students.*', 'grades.name as grade_name', 'grades.class as grade_class')
               ->get();

            $totalStudent = Teacher_grade::join('students', 'students.grade_id', '=', 'teacher_grades.grade_id')
               ->where('teacher_id', $id)
               ->where('teacher_grades.academic_year', session('academic_year'))
               ->get()
               ->count('id');

            $totalExam = Exam::where('teacher_id', $id)
               ->where('semester', session('semester'))
               ->where('academic_year', session('academic_year'))
               ->get()->count('id');

            $totalGrade     = Teacher_grade::where('teacher_id', $id)
               ->where('academic_year', session('academic_year'))->get()->count('id');
            $totalSubject   = Teacher_subject::where('teacher_id', $id)
               ->where('academic_year', session('academic_year'))->get()->count('id');

            $data = [
               'dataTeacher'    => $dataTeacher,
               'gradeTeacher'   => $gradeTeacher,
               'teacherSubject' => $teacherSubject,
               'student'        => $student,
               'exam'           => $dataExam,
               'totalStudent'   => (int)$totalStudent,
               'totalExam'      => (int)$totalExam,
               'totalGrade'     => (int)$totalGrade,
               'totalSubject'   => (int)$totalSubject,
            ];

            return view('components.dashboard-teacher')->with('data', $data);
         } elseif ($checkRole == 'student') {
            $id                = Student::where('user_id', session('id_user'))->value('id');

            $student = Student::where('user_id', session('id_user'))->first();
            // Get payment status from billing system
            $paymentStatus = $this->billingService->checkPaymentStatus($student->unique_id);
            $paymentHistory = $this->billingService->getPaymentHistory($student->unique_id);


            $gradeIdStudent    = Student::where('user_id', session('id_user'))->value('grade_id');

            $religion = Student::where('id', $id)->value('religion');
            if ($religion == "Islam") {
               $cek = 36;
            } elseif ($religion == "Catholic Christianity") {
               $cek = 34;
            } elseif ($religion == "Protestant Christianity") {
               $cek = 37;
            } elseif ($religion == "Buddhism") {
               $cek = 35;
            }

            $chineseLower = Chinese_lower::where('student_id', $id)->exists();
            $chineseHigher = Chinese_higher::where('student_id', $id)->exists();
            if($chineseLower == true){
               $chinese = 38;
            }
            if($chineseHigher == true){
               $chinese = 39;
            }

            $totalExam = Grade_exam::join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->where('grade_id', $gradeIdStudent)
               ->where('exams.semester', session('semester'))
               ->where('exams.academic_year', session('academic_year'))
               ->get()
               ->count('id');

            $totalSubject      = Grade_subject::where('grade_id', $gradeIdStudent)
            ->where(function ($query) use ($cek) {
               $query->whereNotIn('grade_subjects.subject_id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
               ->orWhere('grade_subjects.subject_id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
            })->get()
            ->count('id');
            
            $totalStudentGrade = Student::where('grade_id', $gradeIdStudent)->where('is_active', true)->get()->count('id');
            $totalAbsent       = Attendance::where('student_id', $id)
               ->where('semester', session('semester'))
               ->where('academic_year', session('academic_year'))
               ->where('alpha', 1)
               ->get()
               ->count();

            $eca = Student_eca::where('student_id', $id)
               ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
               ->get();

            $dataExam  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
               ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
               ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
               ->where('grades.id', $gradeIdStudent)
               ->where('exams.semester', session('semester'))
               ->where('exams.academic_year', session('academic_year'))
               ->where('exams.is_active', true)  
               ->orderByRaw('is_active = 1 ASC, date_exam DESC')
               ->get();
            
            if($gradeIdStudent >= 11){
               $dataStudent  = Grade::with(['subject' => function ($query) use($cek, $chinese){
                  $query->whereNotIn('subjects.id', [34, 35, 36, 37])
                  ->where(function ($query) use ($chinese) {
                     $query->whereNotIn('subjects.id', [38, 39])
                     ->orWhere('subjects.id', $chinese);
                  })
                  ->orWhere('subjects.id', $cek)
                  ->orderBy('name_subject', 'asc');
               }, 'exam', 'teacher', 'student' => function ($query) {
                  $query->where('is_active', true)
                     ->orderBy('name', 'asc');
               }])->where('id', $gradeIdStudent)->first();

               $dataExam  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
                  ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                  ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
                  ->join('subject_exams', 'subject_exams.exam_id', '=', 'exams.id')
                  ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
                  ->where('grades.id', $gradeIdStudent)
                  ->where('exams.semester', session('semester'))
                  ->where('exams.academic_year', session('academic_year'))
                  ->where('exams.is_active', true)
                  ->where(function ($query) use ($cek) {
                     $query->whereNotIn('subject_exams.subject_id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                     ->orWhere('subject_exams.subject_id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
                  })  
                  ->where(function ($query) use ($chinese) {
                     $query->whereNotIn('subject_exams.subject_id', [38, 39])
                     ->orWhere('subject_exams.subject_id', $chinese);
                  })
                  ->orderByRaw('is_active = 1 ASC, date_exam DESC')
                  ->get();

               // dd($dataExam);

               foreach ($dataExam as $ed ) {
                  $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
                     ->where('exam_id', $ed->id)
                     ->value('name_subject');
               };
            }
            else{
               $dataStudent  = Grade::with(['subject' => function ($query) use($cek){
                  $query->whereNotIn('subjects.id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                  ->orWhere('subjects.id', $cek)
                  ->orderBy('name_subject', 'asc');
               }, 'exam', 'teacher', 'student' => function ($query) {
                  $query->where('is_active', true)
                     ->orderBy('name', 'asc');
               }])->where('id', $gradeIdStudent)->first();

               $dataExam  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
                  ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                  ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
                  ->join('subject_exams', 'subject_exams.exam_id', '=', 'exams.id')
                  ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
                  ->where('grades.id', $gradeIdStudent)
                  ->where('exams.semester', session('semester'))
                  ->where('exams.academic_year', session('academic_year'))
                  ->where('exams.is_active', true)
                  ->where(function ($query) use ($cek) {
                     $query->whereNotIn('subject_exams.subject_id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                     ->orWhere('subject_exams.subject_id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
                  })  
                  ->orderByRaw('is_active = 1 ASC, date_exam ASC')
                  ->get();

               foreach ($dataExam as $ed ) {
                  $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
                     ->where('exam_id', $ed->id)
                     ->value('name_subject');
               };
            }

            // dd($dataExam);

            $data = [
               'eca'          => $eca,
               'dataStudent'  => $dataStudent,
               'exam'         => $dataExam,
               'totalExam'    => (int)$totalExam,
               'totalSubject' => (int)$totalSubject,
               'totalStudent' => (int)$totalStudentGrade,
               'totalAbsent'  => (int)$totalAbsent,
               'paymentStatus' => $paymentStatus, // Add payment status to data array
               'paymentHistory' => $paymentHistory

            ];

            return view('components.dashboard-student')->with('data', $data);
         } elseif ($checkRole == 'parent') {
            $id              = Relationship::where('user_id', session('id_user'))->value('id');
            $setStudentFirst = session('studentId');

            // Get student data including unique_id
            $student = Student::find($setStudentFirst);

            // Get payment status for the selected student
            $paymentStatus = $this->billingService->checkPaymentStatus($student->unique_id);
            $paymentHistory = $this->billingService->getPaymentHistory($student->unique_id);

            $getIdStudent    = Student_relationship::where('relation_id', $id)->pluck('student_id')->toArray();
            $academicYears = Master_academic::pluck('academic_year');
            $religion = Student::where('id', session('studentId'))->value('religion');
            if ($religion == "Islam") {
               $cek = 36;
            } elseif ($religion == "Catholic Christianity") {
            $cek = 34;
            } elseif ($religion == "Protestant Christianity") {
            $cek = 37;
            } elseif ($religion == "Buddhism") {
            $cek = 35;
            }

            $chineseLower = Chinese_lower::where('student_id', $setStudentFirst)->exists();
            $chineseHigher = Chinese_higher::where('student_id', $setStudentFirst)->exists();
            if($chineseLower == true){
               $chinese = 38;
            }
            if($chineseHigher == true){
               $chinese = 39;
            }

            $getDataStudent = Student::whereIn('students.id', $getIdStudent)
               ->leftJoin('grades', 'grades.id', '=', 'students.grade_id')
               ->select(
                  'students.name as student_name',
                  'students.id as student_id',
                  'students.gender as gender',
                  'grades.id as grade_id',
                  'grades.name as grade_name',
                  'grades.class as grade_class'
               )
               ->orderBy('grades.class', 'asc')
               ->get();

            $detailStudent = Student::where('students.id', $setStudentFirst)
               ->leftJoin('grades', 'grades.id', '=', 'students.grade_id')
               ->select(
                  'students.name as student_name',
                  'students.id as student_id',
                  'students.gender as gender',
                  'grades.id as grade_id',
                  'grades.name as grade_name',
                  'grades.class as grade_class',
                  'students.profil as profile'
               )
               ->first();

            $eca = Student_eca::where('student_id', $setStudentFirst)
               ->leftJoin('ecas', 'ecas.id', '=', 'student_ecas.eca_id')
               ->select('ecas.name as eca_name')
               ->get();

            $gradeIdStudent = Student::where('students.id', $setStudentFirst)->value('grade_id');

            $totalExam = Grade_exam::join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->where('grade_id', $gradeIdStudent)
               ->where('exams.academic_year', session('academic_year'))
               ->where('exams.semester', session('semester'))
               ->get()
               ->count('id');

            $examCompleted     = Grade_exam::join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->where('grade_exams.grade_id', $gradeIdStudent)
               ->where('exams.is_active', FALSE)
               ->where('exams.academic_year', session('academic_year'))
               ->where('exams.semester', session('semester'))
               ->get()
               ->count('grade_exams.id');

            $examProcess     = Grade_exam::join('exams', 'exams.id', '=', 'grade_exams.exam_id')
               ->where('grade_exams.grade_id', $gradeIdStudent)
               ->where('exams.is_active', TRUE)
               ->where('exams.academic_year', session('academic_year'))
               ->where('exams.semester', session('semester'))
               ->get()
               ->count('grade_exams.id');

            $totalSubject     = Grade_subject::where('grade_id', $gradeIdStudent)
               ->where('academic_year', session('academic_year'))
               ->where(function ($query) use ($cek) {
                  $query->whereNotIn('grade_subjects.subject_id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                  ->orWhere('grade_subjects.subject_id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
               })->get()
               ->count('id');


            $totalStudentGrade = Student::where('grade_id', $gradeIdStudent)->get()->count('id');
            $parent            = Relationship::where('user_id', session('id_user'))->first();

            $totalAbsent  = Attendance::where('student_id', $setStudentFirst)
               ->where('semester', session('semester'))
               ->where('academic_year', session('academic_year'))
               ->where('alpha', 1)
               ->get()
               ->count();

            $totalLate = Attendance::where('student_id', $setStudentFirst)
               ->where('semester', session('semester'))
               ->where('academic_year', session('academic_year'))
               ->where('late', 1)
               ->get()
               ->count();


               if($gradeIdStudent >= 11){
                  $dataStudent  = Grade::with(['subject' => function ($query) use($cek, $chinese){
                     $query->whereNotIn('subjects.id', [34, 35, 36, 37])
                     ->where(function ($query) use ($chinese) {
                        $query->whereNotIn('subjects.id', [38, 39])
                        ->orWhere('subjects.id', $chinese);
                     })
                     ->orWhere('subjects.id', $cek)
                     ->orderBy('name_subject', 'asc');
                  }, 'exam', 'teacher', 'student' => function ($query) {
                     $query->where('is_active', true)
                        ->orderBy('name', 'asc');
                  }])->where('id', $gradeIdStudent)->first();
   
                  $dataExam  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
                     ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                     ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
                     ->join('subject_exams', 'subject_exams.exam_id', '=', 'exams.id')
                     ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
                     ->where('grades.id', $gradeIdStudent)
                     ->where('exams.semester', session('semester'))
                     ->where('exams.academic_year', session('academic_year'))
                     ->where('exams.is_active', true)
                     ->where(function ($query) use ($cek) {
                        $query->whereNotIn('subject_exams.subject_id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                        ->orWhere('subject_exams.subject_id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
                     })  
                     ->where(function ($query) use ($chinese) {
                        $query->whereNotIn('subject_exams.subject_id', [38, 39])
                        ->orWhere('subject_exams.subject_id', $chinese);
                     })
                     ->orderByRaw('is_active = 1 ASC, date_exam DESC')
                     ->get();
   
                  // dd($dataExam);
   
                  foreach ($dataExam as $ed ) {
                     $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
                        ->where('exam_id', $ed->id)
                        ->value('name_subject');
                  };
               }else{
                  $dataStudent  = Grade::with(['subject' => function ($query) use($cek){
                     $query->whereNotIn('subjects.id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                     ->orWhere('subjects.id', $cek)
                     ->orderBy('name_subject', 'asc');
                  }, 'exam', 'teacher', 'student' => function ($query) {
                     $query->where('is_active', true)
                        ->orderBy('name', 'asc');
                  }])->where('id', $gradeIdStudent)->first();
   
                  $dataExam  = Grade_exam::join('grades', 'grades.id', '=', 'grade_exams.grade_id')
                     ->join('exams', 'exams.id', '=', 'grade_exams.exam_id')
                     ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
                     ->join('subject_exams', 'subject_exams.exam_id', '=', 'exams.id')
                     ->select('exams.*', 'type_exams.name as type_exam_name', 'grades.name as grade_name', 'grades.class as grade_class')
                     ->where('grades.id', $gradeIdStudent)
                     ->where('exams.semester', session('semester'))
                     ->where('exams.academic_year', session('academic_year'))
                     ->where('exams.is_active', true)
                     ->where(function ($query) use ($cek) {
                        $query->whereNotIn('subject_exams.subject_id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                        ->orWhere('subject_exams.subject_id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
                     })  
                     ->orderByRaw('is_active = 1 ASC, date_exam DESC')
                     ->get();
   
                  foreach ($dataExam as $ed ) {
                     $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
                        ->where('exam_id', $ed->id)
                        ->value('name_subject');
                  };
               }

            foreach ($dataExam as $ed) {
               $ed->subject = Subject_exam::join('subjects', 'subjects.id', '=', 'subject_exams.subject_id')
                  ->where('exam_id', $ed->id)
                  ->value('name_subject');
            };

            $data = [
               'parent'        => $parent,
               'eca'           => $eca,
               'totalRelation' => $getDataStudent,
               'detailStudent' => $detailStudent,
               'dataStudent'   => $dataStudent,
               'exam'          => $dataExam,
               'totalExam'     => (int)$totalExam,
               'examCompleted' => (int)$examCompleted,
               'examProcess'   => (int)$examProcess,
               'totalSubject'  => (int)$totalSubject,
               'totalStudent'  => (int)$totalStudentGrade,
               'totalAbsent'   => (int)$totalAbsent,
               'totalLate'     => $totalLate,
               'academicYears' => $academicYears,
               'paymentStatus' => $paymentStatus, // Add payment status to data array
               'paymentHistory' => $paymentHistory
            ];

            return view('components.dashboard-parent')->with('data', $data);
         } elseif ($checkRole == 'library') {

            // $planVisit = Plan_visit::get();
            // dd($planVisit);
            return view('components.dashboard-library');
         }
      } catch (Exception $err) {
         return dd($err);
      }
   }
}
