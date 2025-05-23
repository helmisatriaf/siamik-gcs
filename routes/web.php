<?php

use App\Http\Controllers\Admin\{
   AdminController,
   DashboardController,
   GradeController,
   RegisterController,
   StudentController,
   TeacherController,
   RelationController,
   ExamController,
   SubjectController,
   TypeExamController,
   TypeScheduleController,
   ScoreController,
   MajorSubjectController,
   MinorSubjectController,
   SupplementarySubjectController,
};

use App\Http\Controllers\Excel\Import;

use App\Http\Controllers\EcaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\ChineseHigherController;
use App\Http\Controllers\ChineseLowerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MonthlyActivitiesController;
use App\Http\Controllers\MasterAcademicsController;
use App\Http\Controllers\PageTutorialController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LibraryController;

use App\Http\Controllers\SuperAdmin\{
   SuperAdminController,
   StudentController as SuperStudentController
};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Grade_subject;
use App\Models\Teacher_subject;
use App\Models\Schedule;
use App\Models\Type_schedule;
use App\Models\Page_Tutorials;
use App\Models\Chat_bot;

use App\Services\BillingService;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, 'login']);
Route::post('/login', [UserController::class, 'actionLogin'])->name('actionLogin');
Route::post('/login-library', [UserController::class, 'actionLoginLibrary'])->name('login.library');
Route::get('/logout', [UserController::class, 'logout']);


// Route untuk mengambil data subject
Route::get('/get-subjects/{gradeId}', function ($gradeId) {
   $subjects = Grade_subject::join('subjects', 'grade_subjects.subject_id', '=', 'subjects.id')
      ->where('grade_id', $gradeId)
      ->where('academic_year', session('academic_year'))
      ->orderBy('subjects.name_subject', 'asc')
      ->get();
   return response()->json($subjects);
});

// Route untuk mengambil data guru
Route::get('/get-teachers/{gradeId}/{subjectId}', function ($gradeId, $subjectId) {
   $subjects = Teacher_subject::join('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
      ->where('grade_id', $gradeId)
      ->where('subject_id', $subjectId)
      ->where('academic_year', session('academic_year'))
      ->get();
   return response()->json($subjects);
});

// Route untuk mengambil data subject teacher
Route::get('/get-subjects/{gradeId}/{teacherId}', function ($gradeId, $teacherId) {
   $teachers = Teacher_subject::join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
      ->where('grade_id', $gradeId)
      ->where('teacher_id', $teacherId)
      ->where('academic_year', session('academic_year'))
      ->orderBy('subjects.name_subject', 'asc')
      ->get();
   return response()->json($teachers);
});

// Route untuk mengambil data grade teacher
Route::get('/get-grades/{teacherId}', function ($teacherId) {
   $grades = Teacher_subject::join('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
      ->where('teacher_id', $teacherId)
      ->where('academic_year', session('academic_year'))
      ->get();
   return response()->json($grades);
});

// Untuk melihat filter di add schedule subject teacher di menu grade
Route::get('/get-all-schedule-filter/{teacher?}/{grade?}/{day?}', function ($teacher = null, $grade = null, $day = null) {
   $lesson = Type_schedule::where('name', '=', 'Lesson')->value('id');

   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
      ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->where('type_schedule_id', $lesson)
      ->where('semester', session('semester'))
      ->where('academic_year', session('academic_year'))
      ->orderBy('grade_id', 'asc')
      ->orderBy('day', 'asc')
      ->orderBy('start_time', 'asc');

   if ($teacher !== 'null') {
      $query->where('teacher_id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   if ($day !== 'null') {
      $query->where('day', $day);
   }

   $schedules = $query->select(
      'schedules.*',
      'teachers.name as teacher_name',
      't2.name as assisstant',
      DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"),
      'subjects.name_subject as subject_name'
   )->get();

   // Define day names
   $dayNames = [
      1 => 'Monday',
      2 => 'Tuesday',
      3 => 'Wednesday',
      4 => 'Thursday',
      5 => 'Friday'
   ];

   // Group schedules by day and grade_name
   $groupedSchedules = $schedules->groupBy('day')->mapWithKeys(function ($group, $day) use ($dayNames) {
      $dayName = $dayNames[$day] ?? 'Unknown';

      $gradeGrouped = $group->groupBy('grade_name')->map(function ($gradeGroup) {
         return $gradeGroup->map(function ($schedule) {
            return [
               'id' => $schedule->id,
               'subject_name' => $schedule->subject_name,
               'teacher_name' => $schedule->teacher_name,
               'assisstant' => $schedule->assisstant,
               'day' => $schedule->day,
               'start_time' => $schedule->start_time,
               'end_time' => $schedule->end_time,
               'notes' => $schedule->note
            ];
         })->values();
      });

      return [$dayName => $gradeGrouped];
   });

   return response()->json($groupedSchedules);
});

Route::get('/get-all-schedulemidexam-filter/{teacher?}/{grade?}/{day?}', function ($teacher = null, $grade = null, $day = null) {
   $lesson = Type_schedule::where('name', '=', 'Mid Exam')->value('id');

   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
      ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->where('type_schedule_id', $lesson)
      ->where('semester', session('semester'))
      ->orderBy('grade_id', 'asc')
      ->orderBy('day', 'asc')
      ->orderBy('start_time', 'asc');

   if ($teacher !== 'null') {
      $query->where('teacher_id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   if ($day !== 'null') {
      $query->where('day', $day);
   }

   $schedules = $query->select(
      'schedules.*',
      'teachers.name as teacher_name',
      't2.name as assisstant',
      DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"),
      'subjects.name_subject as subject_name'
   )->get();

   // Define day names
   $dayNames = [
      1 => 'Monday',
      2 => 'Tuesday',
      3 => 'Wednesday',
      4 => 'Thursday',
      5 => 'Friday'
   ];

   // Group schedules by day and grade_name
   $groupedSchedules = $schedules->groupBy('day')->mapWithKeys(function ($group, $day) use ($dayNames) {
      $dayName = $dayNames[$day] ?? 'Unknown';

      $gradeGrouped = $group->groupBy('grade_name')->map(function ($gradeGroup) {
         return $gradeGroup->map(function ($schedule) {
            return [
               'id' => $schedule->id,
               'subject_name' => $schedule->subject_name,
               'teacher_name' => $schedule->teacher_name,
               'assisstant' => $schedule->assisstant,
               'day' => $schedule->day,
               'start_time' => $schedule->start_time,
               'end_time' => $schedule->end_time,
               'notes' => $schedule->note
            ];
         })->values();
      });

      return [$dayName => $gradeGrouped];
   });

   return response()->json($groupedSchedules);
});

Route::get('/get-all-schedulefinalexam-filter/{teacher?}/{grade?}/{day?}', function ($teacher = null, $grade = null, $day = null) {
   $lesson = Type_schedule::where('name', '=', 'Final Exam')->value('id');

   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
      ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->where('type_schedule_id', $lesson)
      ->where('semester', session('semester'))
      ->orderBy('grade_id', 'asc')
      ->orderBy('day', 'asc')
      ->orderBy('start_time', 'asc');

   if ($teacher !== 'null') {
      $query->where('teacher_id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   if ($day !== 'null') {
      $query->where('day', $day);
   }

   $schedules = $query->select(
      'schedules.*',
      'teachers.name as teacher_name',
      't2.name as assisstant',
      DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"),
      'subjects.name_subject as subject_name'
   )->get();

   // Define day names
   $dayNames = [
      1 => 'Monday',
      2 => 'Tuesday',
      3 => 'Wednesday',
      4 => 'Thursday',
      5 => 'Friday'
   ];

   // Group schedules by day and grade_name
   $groupedSchedules = $schedules->groupBy('day')->mapWithKeys(function ($group, $day) use ($dayNames) {
      $dayName = $dayNames[$day] ?? 'Unknown';

      $gradeGrouped = $group->groupBy('grade_name')->map(function ($gradeGroup) {
         return $gradeGroup->map(function ($schedule) {
            return [
               'id' => $schedule->id,
               'subject_name' => $schedule->subject_name,
               'teacher_name' => $schedule->teacher_name,
               'assisstant' => $schedule->assisstant,
               'day' => $schedule->day,
               'start_time' => $schedule->start_time,
               'end_time' => $schedule->end_time,
               'notes' => $schedule->note
            ];
         })->values();
      });

      return [$dayName => $gradeGrouped];
   });

   return response()->json($groupedSchedules);
});

Route::get('/get-schedule-filter/{teacher?}/{grade?}/{day?}', function ($teacher = null, $grade = null, $day = null) {
   $lesson = Type_schedule::where('name', '=', 'Lesson')->value('id');

   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->where('type_schedule_id', $lesson)
      ->where('semester', session('semester'))
      ->orderBy('grade_id', 'asc')
      ->orderBy('day', 'asc')
      ->orderBy('start_time', 'asc');

   if ($teacher !== 'null') {
      $query->where('teacher_id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   if ($day !== 'null') {
      $query->where('day', $day);
   }

   $schedules = $query->select(
      'schedules.*',
      'teachers.name as teacher_name',
      DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"),
      'subjects.name_subject as subject_name'
   )->get();

   return response()->json($schedules);
});

Route::get('/get-schedule-assist-filter/{teacher?}/{grade?}/{day?}', function ($teacher = null, $grade = null, $day = null) {
   $lesson = Type_schedule::where('name', '=', 'Lesson')->value('id');

   $query = Schedule::leftJoin('teachers', 'schedules.teacher_companion', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->where('type_schedule_id', $lesson)
      ->where('semester', session('semester'))
      ->where('academic_year', session('academic_year'))
      ->orderBy('grade_id', 'asc')
      ->orderBy('day', 'asc')
      ->orderBy('start_time', 'asc');

   if ($teacher !== 'null') {
      $query->where('teacher_companion', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   if ($day !== 'null') {
      $query->where('day', $day);
   }

   $schedules = $query->select(
      'schedules.*',
      'teachers.name as teacher_name',
      DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"),
      'subjects.name_subject as subject_name'
   )->get();

   return response()->json($schedules);
});
// END

// Untuk filter data awal schedule substitute
Route::get('/get-schedule-teacher/{day}/{startTime}/{endTime}', function ($day, $startTime, $endTime) {
   $teacher = request('teacher');
   $grade = request('grade');

   $query = Schedule::where('day', $day)
      ->where('start_time', '>=', '08:00')
      ->where('end_time', '<', '14:00')
      ->where('note', '=', NULL)
      ->leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->select(
         'schedules.*',
         'teachers.id as teacher_id',
         'teachers.name as teacher_name',
         'grades.id as grade_id',
         'grades.name as grade_name',
         'grades.class as grade_class',
         'subjects.id as subject_id',
         'subjects.name_subject as subject_name'
      );

   if ($teacher) {
      $query->where('teachers.id', $teacher);
   }

   if ($grade) {
      $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});

Route::get('/get-schedule-companion/{day}/{startTime}/{endTime}', function ($day, $startTime, $endTime) {
   $teacher = request('teacher');
   $grade = request('grade');

   $query = Schedule::where('day', $day)
      ->where('start_time', '>=', '08:00')
      ->where('end_time', '<', '14:00')
      ->where('note', '=', NULL)
      ->where('teacher_companion', '!=', null)
      ->leftJoin('teachers', 'schedules.teacher_companion', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->select(
         'schedules.*',
         'teachers.id as teacher_id',
         'teachers.name as teacher_companion',
         'grades.id as grade_id',
         'grades.name as grade_name',
         'grades.class as grade_class',
         'subjects.id as subject_id',
         'subjects.name_subject as subject_name'
      );

   if ($teacher) {
      $query->where('teachers.id', $teacher);
   }

   if ($grade) {
      $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});
// END


// Untuk filter berdasarkan teacher / grade saat substitute teacher
Route::get('/get-schedule-subtitute-filter/{day}/{teacher?}/{grade?}', function ($day, $teacher = null, $grade = null) {
   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->select(
         'schedules.*',
         'teachers.id as teacher_id',
         'teachers.name as teacher_name',
         'grades.id as grade_id',
         'grades.name as grade_name',
         'grades.class as grade_class',
         'subjects.id as subject_id',
         'subjects.name_subject as subject_name'
      )
      ->where('semester', session('semester'))
      ->where('academic_year', session('academic_year'))
      ->where('day', $day)
      ->orderBy('grade_id', 'asc')
      ->orderBy('day', 'asc')
      ->orderBy('start_time', 'asc');

   if ($teacher !== 'null') {
      $query->where('teacher_id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});

Route::get('/get-schedule-companion-filter/{teacher?}/{grade?}', function ($teacher = null, $grade = null) {
   $query = Schedule::leftJoin('teachers', 'schedules.teacher_companion', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->select(
         'schedules.*',
         'teachers.id as teacher_id',
         'teachers.name as teacher_companion',
         'grades.id as grade_id',
         'grades.name as grade_name',
         'grades.class as grade_class',
         'subjects.id as subject_id',
         'subjects.name_subject as subject_name'
      )
      ->where('semester', session('semester'))
      ->where('academic_year', session('academic_year'))
      ->orderBy('grade_id', 'asc')
      ->orderBy('day', 'asc')
      ->orderBy('start_time', 'asc');

   if ($teacher !== 'null') {
      $query->where('teacher_id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});
// END

// Filter di mid & final exam
Route::get('/get-schedulemidexam-edit/{teacher?}/{grade?}', function ($teacher = null, $grade = null) {
   $exam = session('semester') == 1 ? "mid exam semester 1" : "mid exam semester 2";

   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->where('note', $exam);

   if ($teacher !== 'null') {
      $query->where('teacher_id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   $schedules = $query->select(
      'schedules.*',
      'teachers.name as teacher_name',
      DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"),
      'subjects.name_subject as subject_name'
   )->get();

   return response()->json($schedules);
});

Route::get('/get-schedulefinalexam-edit/{teacher?}/{grade?}', function ($teacher = null, $grade = null) {
   $exam = session('semester') == 1 ? "final exam semester 1" : "final exam semester 2";

   $query = Schedule::leftJoin('teachers', 'schedules.teacher_id', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->where('note', $exam);

   if ($teacher !== 'null') {
      $query->where('teacher_id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grade_id', $grade);
   }

   $schedules = $query->select(
      'schedules.*',
      'teachers.name as teacher_name',
      DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"),
      'subjects.name_subject as subject_name'
   )->get();

   return response()->json($schedules);
});
// END


Route::get('/get-schedule-companion-edit/{day}/{teacher}/{grade}', function ($day, $teacher, $grade) {
   $query = Schedule::leftJoin('teachers', 'schedules.teacher_companion', '=', 'teachers.id')
      ->leftJoin('grades', 'schedules.grade_id', '=', 'grades.id')
      ->leftJoin('subjects', 'schedules.subject_id', '=', 'subjects.id')
      ->where('schedules.day', '=', $day)
      ->select(
         'schedules.*',
         'teachers.id as teacher_id',
         'teachers.name as teacher_companion',
         'grades.id as grade_id',
         'grades.name as grade_name',
         'grades.class as grade_class',
         'subjects.id as subject_id',
         'subjects.name_subject as subject_name'
      );

   if ($teacher !== 'null') {
      $query->where('teachers.id', $teacher);
   }

   if ($grade !== 'null') {
      $query->where('grades.id', $grade);
   }

   $schedules = $query->get();

   return response()->json($schedules);
});


// Route untuk mengambil data other schedule
Route::get('/get-schedule/{id}', function ($id) {
   $schedule = Schedule::where('schedules.id', $id)->first();

   return response()->json($schedule);
});

// Route untuk mengupdate other schedule
Route::post('/update-schedule/{id}', [ScheduleController::class, 'actionUpdateOtherSchedule'])->name('update.otherSchedule');

// Route untuk save semester kedalam session
Route::post('/save-semester-session', [UserController::class, 'saveSemesterToSession'])->name('save.semester.session');
Route::post('/save-academicyear-session', [UserController::class, 'saveAcademicYearToSession'])->name('save.academicyear.session');


// Route untuk save semester kedalam session
Route::post('/save-studentId-session', [UserController::class, 'saveStudentIdToSession'])->name('save.student.session');

// Route untuk menyimpan substitute teacher
Route::post('/subtitute-teacher', [ScheduleController::class, 'subtituteTeacher'])->name('subtitute.teacher');

Route::middleware(['auth.login', 'role:superadmin'])->prefix('/superadmin')->group(function () {

   Route::prefix('/teachers')->group(function () {
      Route::get('/', [TeacherController::class, 'index']);
      Route::post('/', [TeacherController::class, 'actionPost'])->name('actionSuperadminRegisterTeacher');
      Route::put('/{id}', [TeacherController::class, 'actionEdit'])->name('actionSuperUpdateTeacher');
      Route::get('/register', [TeacherController::class, 'pagePost']);
      Route::get('/{id}', [TeacherController::class, 'editPage']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
      Route::get('/delete/{id}', [TeacherController::class, 'delete'])->name('delete-teacher');
      Route::get('/teachers/{teacherId}/{gradeId}/{subjectId}', [TeacherController::class, 'deleteGradeSubject'])->name('deleteGradeSubject');
      Route::put('/deactivated/{id}', [TeacherController::class, 'deactivated']);
      Route::put('/activated/{id}', [TeacherController::class, 'activated']);
   });

   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
   });

   Route::prefix('/user')->group(function () {
      Route::get('/change-password', [AdminController::class, 'changeMyPassword']);
      Route::put('/change-password', [AdminController::class, 'actionChangeMyPassword']);
   });

   Route::prefix('/detail')->group(function () {
      Route::get('/{id}', [StudentController::class, 'detail']);
   });

   Route::prefix('/users')->group(function () {
      Route::get('/', [SuperAdminController::class, 'getUser']);
      Route::get('/register-user', [SuperAdminController::class, 'registerUser']);
      Route::get('/{id}', [SuperAdminController::class, 'getById']);
      Route::post('/register-action', [SuperAdminController::class, 'registerUserAction']);
      Route::put('/change-password/{id}', [SuperAdminController::class, 'changePassword'])->name('user.editPassword');
      Route::get('delete/{id}', [SuperAdminController::class, 'deleteUser']);
   });

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
      Route::post('/post', [RegisterController::class, 'register'])->name('actionRegisterSuper');
      Route::get('/imports', [Import::class, 'index']);
      Route::post('/imports', [Import::class, 'upload'])->name('import.register_super');
      Route::get('/templates/students', [Import::class, 'downloadTemplate']);
   });

   Route::prefix('/list')->group(function () {
      Route::get('/', [StudentController::class, 'index']);
      Route::get('/detail/{id}', [StudentController::class, 'detail']);
   });

   Route::prefix('/update')->group(function () {
      Route::put('/{id}', [StudentController::class, 'actionEdit'])->name('student.update_super');
      Route::get('/{id}', [StudentController::class, 'edit']);
   });

   Route::prefix('/relations')->group(function () {
      Route::get('/', [RelationController::class, 'index']);
      Route::get('/detail/{id}', [RelationController::class, 'getById']);
      Route::get('/edit/{id}', [RelationController::class, 'editPage']);
      Route::put('/edit/{id}', [RelationController::class, 'actionEdit'])->name('actionUpdateRelation');
   });

   Route::prefix('/grades')->group(function () {
      Route::get('/', [GradeController::class, 'index']);
      Route::get('/create', [GradeController::class, 'pageCreate']);
      Route::get('/{id}', [GradeController::class, 'detailGrade']);
      Route::get('/edit/{id}', [GradeController::class, 'pageEdit']);
      Route::get('/manageSubject/{id}', [GradeController::class, 'pageEditSubject']);
      Route::get('/manageSubject/teacher/edit/{id}/{subjectId}/{teacherId}', [GradeController::class, 'pageEditSubjectTeacher']);
      Route::get('/manageSubject/teacher/multiple/edit/{id}/{subjectId}', [GradeController::class, 'pageEditSubjectTeacherMultiple']);
      Route::put('manageSubject/{id}', [GradeController::class, 'actionPutSubjectTeacher'])->name('actionSuperUpdateGradeSubjectTeacher');
      Route::put('manageSubject/multi/{id}', [GradeController::class, 'actionPutSubjectMultiTeacher'])->name('actionSuperUpdateGradeSubjectMultiTeacher');
      Route::post('/', [GradeController::class, 'actionPost'])->name('actionSuperCreateGrade');
      Route::put('/{id}', [GradeController::class, 'actionPut'])->name('actionSuperUpdateGrade');
      Route::get('/delete/{id}', [GradeController::class, 'delete'])->name('delete-grade');
      Route::get('/subject/delete/{gradeId}/{subjectId}/{teacherId}', [GradeController::class, 'deleteSubjectGrade'])->name('delete-subject-grade');
      Route::get('/subject/multiple/delete/{gradeId}/{subjectId}/{teacherId}', [GradeController::class, 'deleteSubjectMultipleGrade'])->name('delete-subject-multiple-grade');

      Route::get('/manageSubject/addSubject/{id}', [GradeController::class, 'pageAddSubjectTeacher']);
      Route::get('/manageSubject/addSubject/multiple/{id}', [GradeController::class, 'pageAddSubjectTeacherMultiple']);
      Route::post('/manageSubject', [GradeController::class, 'actionPostAddSubjectGrade'])->name('actionSuperAddSubjectGrade');
      Route::post('/manageSubject/multiple', [GradeController::class, 'actionPostAddSubjectGradeMultiple'])->name('actionSuperAddSubjectGradeMultiple');
   });

   Route::prefix('/exams')->group(function () {
      Route::get('/', [ExamController::class, 'index'])->name('exams.index');
      Route::get('/create', [ExamController::class, 'pageCreate']);
      Route::get('/{id}', [ExamController::class, 'getById']);
      Route::get('/edit/{id}', [ExamController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [ExamController::class, 'pagePDF']);
      Route::post('/', [ExamController::class, 'actionPost'])->name('actionSuperCreateExam');
      Route::put('/{id}', [ExamController::class, 'actionPut'])->name('actionSuperUpdateExam');
      // Route::get('/done/{id}', [ExamController::class, 'doneExam'])->name('doneExam');
   });

   Route::prefix('/student')->group(function () {
      Route::get('/re-registration/{student_id}', [SuperStudentController::class, 'pageReRegis']);
      Route::patch('/{id}', [SuperStudentController::class, 'inactiveStudent']);
      Route::patch('/activate/{student_id}', [SuperStudentController::class, 'activateStudent']);
      Route::patch('/re-registration/{student_id}', [SuperStudentController::class, 'actionReRegis'])->name('action.re-regis');
   });

   Route::prefix('/subjects')->group(function () {
      Route::get('/', [SubjectController::class, 'index']);
      Route::get('/create', [SubjectController::class, 'pageCreate']);
      Route::get('/edit/{id}', [SubjectController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [SubjectController::class, 'pagePDF']);
      Route::post('/', [SubjectController::class, 'actionPost'])->name('actionSuperCreateSubject');
      Route::get('/delete/{id}', [SubjectController::class, 'delete'])->name('delete-subject');
   });

   Route::prefix('/majorSubjects')->group(function () {
      Route::get('/', [MajorSubjectController::class, 'index']);
      Route::get('/create', [MajorSubjectController::class, 'pageCreate']);
      Route::post('/', [MajorSubjectController::class, 'actionPost'])->name('actionSuperCreateMajorSubject');
      Route::get('/delete/{id}', [MajorSubjectController::class, 'delete'])->name('delete-majorsubject');
   });

   Route::prefix('/minorSubjects')->group(function () {
      Route::get('/', [MinorSubjectController::class, 'index']);
      Route::get('/create', [MinorSubjectController::class, 'pageCreate']);
      Route::post('/', [MinorSubjectController::class, 'actionPost'])->name('actionSuperCreateMinorSubject');
      Route::get('/delete/{id}', [MinorSubjectController::class, 'delete'])->name('delete-minorsubject');
   });



   Route::prefix('/typeExams')->group(function () {
      Route::get('/', [TypeExamController::class, 'index']);
      Route::get('/create', [TypeExamController::class, 'pageCreate']);
      Route::get('/edit/{id}', [TypeExamController::class, 'pageEdit']);
      Route::post('/', [TypeExamController::class, 'actionPost'])->name('actionSuperCreateTypeExam');
      Route::put('/{id}', [TypeExamController::class, 'actionPut'])->name('actionSuperUpdateTypeExam');
      Route::get('/delete/{id}', [TypeExamController::class, 'delete']);
   });

   Route::prefix('/reports')->group(function () {
      Route::get('/', [ReportController::class, 'index']);
      Route::get('detail/{id}', [ReportController::class, 'detailSubjectClass']);
      Route::get('detailSec/{id}', [ReportController::class, 'detailSubjectClassSec']);
      Route::get('detailSubject/student/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentTeacher']);
      Route::get('detailSubjectSec/student/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentSecTeacher']);

      Route::post('/scoringMajorPrimary', [ScoringController::class, 'actionPostMajorPrimary'])->name('actionPostScoringMajorPrimary');
      Route::post('/scoringMinorPrimary', [ScoringController::class, 'actionPostMinorPrimary'])->name('actionPostScoringMinorPrimary');
      Route::post('/scoringSecondary', [ScoringController::class, 'actionPostSecondary'])->name('actionPostScoringSecondary');


      Route::get('acar/detail/{id}', [ReportController::class, 'acarPrimary']);
      Route::post('/acarPrimary', [ScoringController::class, 'actionPostAcarPrimary'])->name('actionPostScoringAcarPrimary');
      Route::post('/acarSecondary', [ScoringController::class, 'actionPostAcarSecondary'])->name('actionPostScoringAcarSecondary');
      Route::get('acar/detailSec/{id}', [ReportController::class, 'acarSecondary']);

      Route::get('sooa/detail/{id}', [ReportController::class, 'sooaPrimary']);
      Route::get('sooa/detailSec/{id}', [ReportController::class, 'sooaSecondary']);
      Route::post('/sooaPrimary', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionPostScoringSooaPrimary');
      Route::post('/sooaSecondary', [ScoringController::class, 'actionPostSooaSecondary'])->name('actionPostScoringSooaSecondary');
      Route::post('/updateSooaPrimary/{id}', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionUpdateSooaPrimarySuper');
      Route::post('/updateSooaSecondary/{id}', [ScoringController::class, 'actionPostSooaSecondary']);

      Route::get('tcop/detail/{id}', [ReportController::class, 'tcopPrimary']);

      Route::get('midcard/semestersatu/{id}', [ReportController::class, 'cardSemesterMid']);
      Route::get('semestersatu/detail/{id}', [ReportController::class, 'cardSemester1']);
      Route::get('semesterdua/detail/{id}', [ReportController::class, 'cardSemester2']);
      Route::get('semestersatu/detailSec/{id}', [ReportController::class, 'cardSemester1Sec']);
      Route::get('semesterdua/detailSec/{id}', [ReportController::class, 'cardSemester2Sec']);

      Route::get('midreport/print/{id}', [ReportController::class, 'downloadPDFMidSemester']);
      Route::get('semester1/print/{id}', [ReportController::class, 'downloadPDFSemester1']);
      Route::get('semester2/print/{id}', [ReportController::class, 'downloadPDFSemester2']);
      Route::get('toddler/print/{id}', [ReportController::class, 'downloadPDFToddler']);
      Route::get('nursery/print/{id}', [ReportController::class, 'downloadPDFNursery']);
      Route::get('kindergarten/print/{id}', [ReportController::class, 'downloadPDFKindergarten']);
   });

   Route::prefix('/schedules')->group(function () {
      Route::get('/all', [ScheduleController::class, 'allScheduleSchools']);
      Route::get('/schools', [ScheduleController::class, 'scheduleSchools']);
      Route::get('/grades', [ScheduleController::class, 'scheduleGrades']);
      Route::get('/manage/{id}', [ScheduleController::class, 'managePage']);
      Route::get('/schools/manage/otherSchedule', [ScheduleController::class, 'manageOtherSchedulePage']);
      Route::get('grade/create/{id}', [ScheduleController::class, 'create']);
      Route::post('/scheduleGrade', [ScheduleController::class, 'actionCreate'])->name('actionSuperCreateSchedule');
      Route::post('schedules/schools', [ScheduleController::class, 'actionCreateOther'])->name('actionSuperCreateOtherSchedule');
      Route::get('detail/{id}', [ScheduleController::class, 'detail']);
      Route::get('edit/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPage']);
      Route::put('/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeSchedule'])->name('actionSuperEditSchedule');
      Route::put('subtitute/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeScheduleSubtitute'])->name('actionSuperEditScheduleSubtitute');

      Route::get('/midexam/create/{id}', [ScheduleController::class, 'createMidExam']);
      Route::post('/midExam', [ScheduleController::class, 'actionCreateMidExam'])->name('actionSuperCreateMidExam');
      Route::get('/midexams', [ScheduleController::class, 'scheduleMidExams']);
      Route::get('/manage/midexam/{id}', [ScheduleController::class, 'managePageMidExam']);
      Route::get('/detail/midexam/{id}', [ScheduleController::class, 'detailMidExam']);
      Route::get('/edit/midexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageMidExam']);
      Route::put('/midexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateMidExam'])->name('actionSuperEditMidExam');
      Route::put('/edit/date', [ScheduleController::class, 'actionUpdateDateMidExam'])->name('actionSuperEditDateMidExam');
      Route::get('/delete/midexam/{id}', [ScheduleController::class, 'deleteMidExam']);

      Route::get('/finalexam/create/{id}', [ScheduleController::class, 'createFinalExam']);
      Route::post('/finalExam', [ScheduleController::class, 'actionCreateFinalExam'])->name('actionSuperCreateFinalExam');
      Route::get('/finalexams', [ScheduleController::class, 'scheduleFinalExams']);
      Route::get('/manage/finalexam/{id}', [ScheduleController::class, 'managePageFinalExam']);
      Route::get('/detail/finalexam/{id}', [ScheduleController::class, 'detailFinalExam']);
      Route::get('/edit/finalexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageFinalExam']);
      Route::put('/finalexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateFinalExam'])->name('actionSuperEditFinalExam');
      Route::put('/manage/finalexam/edit/date', [ScheduleController::class, 'actionUpdateDateFinalExam'])->name('actionSuperEditDateFinalExam');
      Route::get('/delete/finalexam/{id}', [ScheduleController::class, 'deleteFinalExam']);

      Route::get('/delete/{id}', [ScheduleController::class, 'delete']);
      Route::get('/deleteSubtitute/{id}', [ScheduleController::class, 'deleteSubtitute']);
      Route::get('/otherSchedule/delete/{id}', [ScheduleController::class, 'deleteOtherSchedule']);

      Route::get('/schools/manage/otherSchedule', [ScheduleController::class, 'manageOtherSchedulePage']);
      Route::get('/editSubtitute/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageSubtitute']);
      Route::put('/subtitute/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeScheduleSubtitute'])->name('actionSuperEditScheduleSubtitute');

      Route::get('/deleteSubtitute/{id}', [ScheduleController::class, 'deleteSubtitute']);
      Route::get('/otherSchedule/delete/{id}', [ScheduleController::class, 'deleteOtherSchedule']);
   });

   Route::prefix('/typeSchedules')->group(function () {
      Route::get('/', [TypeScheduleController::class, 'index']);
      Route::get('/create', [TypeScheduleController::class, 'pageCreate']);
      Route::get('/edit/{id}', [TypeScheduleController::class, 'pageEdit']);
      Route::post('/', [TypeScheduleController::class, 'actionPost'])->name('actionSuperCreateTypeSchedule');
      Route::put('/{id}', [TypeScheduleController::class, 'actionPut'])->name('actionSuperUpdateTypeSchedule');
      Route::get('/delete/{id}', [TypeScheduleController::class, 'delete']);
   });

   Route::prefix('/masterAcademics')->group(function () {
      Route::get('/', [MasterAcademicsController::class, 'index']);
      Route::get('/create', [MasterAcademicsController::class, 'pageCreate']);
      Route::get('/edit', [MasterAcademicsController::class, 'pageEdit']);
      Route::post('/', [MasterAcademicsController::class, 'actionPost'])->name('actionSuperCreateMasterAcademic');
      Route::put('/{id}', [MasterAcademicsController::class, 'actionPut'])->name('actionSuperUpdateMasterAcademic');
      Route::get('/delete/{id}', [MasterAcademicsController::class, 'delete']);
   });

   Route::prefix('/attendances')->group(function () {
      Route::get('/', [AttendanceController::class, 'index']);
      Route::get('/subject/{id}', [AttendanceController::class, 'subject']);
      Route::get('/subject/student/{gradeId}/{subjectId}', [AttendanceController::class, 'detailAttend']);
      Route::post('/postScoreAttendance', [ScoringController::class, 'actionPostScoreAttendance'])->name('actionPostScoringAttendance');

      Route::get('/teacher/grade/subject', [AttendanceController::class, 'detailAttendance'])->name('super.attendance.detail');
   });

   Route::prefix('/chineseHigher')->group(function () {
      Route::get('/', [ChineseHigherController::class, 'index']);
      Route::get('/add', [ChineseHigherController::class, 'addStudent']);
      Route::post('/', [ChineseHigherController::class, 'actionPost'])->name('actionSuperAddStudentChineseHigher');
      Route::get('/student/delete/{id}', [ChineseHigherController::class, 'delete']);
   });

   Route::prefix('/chineseLower')->group(function () {
      Route::get('/', [ChineseLowerController::class, 'index']);
      Route::get('/add', [ChineseLowerController::class, 'addStudent']);
      Route::post('/', [ChineseLowerController::class, 'actionPost'])->name('actionSuperAddStudentChineseLower');
      Route::get('/student/delete/{id}', [ChineseLowerController::class, 'delete']);
   });

   Route::post('/change-data-admin', [SuperAdminController::class, 'changeDataAdmin'])->name('actionDataAdmin');
});

Route::middleware(['auth.login', 'role:admin'])->prefix('/admin')->group(function () {

   Route::get('/export/excel', [ExportController::class, 'excel']);
   Route::get('/export/pdf', [ExportController::class, 'attendance']);

   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
   });

   Route::prefix('/user')->group(function () {

      Route::get('/change-password', [AdminController::class, 'changeMyPassword']);
      Route::put('/change-password', [AdminController::class, 'actionChangeMyPassword']);
   });

   Route::prefix('/users')->group(function () {
      Route::get('/', [SuperAdminController::class, 'getUser']);
      Route::get('/register-user', [SuperAdminController::class, 'registerUser']);
      Route::get('/{id}', [SuperAdminController::class, 'getById']);
      Route::post('/register-action', [SuperAdminController::class, 'registerUserAction']);
      Route::put('/change-password/{id}', [SuperAdminController::class, 'changePassword'])->name('admin.editPassword');
      Route::get('delete/{id}', [SuperAdminController::class, 'deleteUser']);
   });

   Route::prefix('/detail')->group(function () {
      Route::get('/{id}', [StudentController::class, 'detail']);
   });

   Route::prefix('/register')->group(function () {
      Route::get('/', [RegisterController::class, 'index']);
      Route::post('/post', [RegisterController::class, 'register'])->name('actionRegisterAdmin');
      Route::get('/imports', [Import::class, 'index']);
      Route::post('/imports', [Import::class, 'upload'])->name('import.register_admin');
      Route::get('/templates/students', [Import::class, 'downloadTemplate']);
   });

   Route::prefix('/list')->group(function () {
      Route::get('/', [StudentController::class, 'index']);
      Route::get('/detail/{id}', [StudentController::class, 'detail']);
   });

   Route::prefix('/update')->group(function () {
      Route::put('/{id}', [StudentController::class, 'actionEdit'])->name('student.update_admin');
      Route::get('/{id}', [StudentController::class, 'edit']);
   });

   Route::prefix('/teachers')->group(function () {
      Route::get('/', [TeacherController::class, 'index']);
      Route::post('/', [TeacherController::class, 'actionPost'])->name('actionAdminRegisterTeacher');
      Route::put('/{id}', [TeacherController::class, 'actionEdit'])->name('actionAdminUpdateTeacher');
      Route::get('/register', [TeacherController::class, 'pagePost']);
      Route::get('/edit/{id}', [TeacherController::class, 'editPage']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
      Route::put('/deactivated/{id}', [TeacherController::class, 'deactivated']);
      Route::put('/activated/{id}', [TeacherController::class, 'activated']);
   });

   Route::prefix('/relations')->group(function () {
      Route::get('/', [RelationController::class, 'index']);
      Route::get('/detail/{id}', [RelationController::class, 'getById']);
      Route::get('/edit/{id}', [RelationController::class, 'editPage']);
   });

   Route::prefix('/grades')->group(function () {
      Route::get('/', [GradeController::class, 'index']);
      Route::get('/create', [GradeController::class, 'pageCreate']);
      Route::get('/{id}', [GradeController::class, 'detailGrade']);
      Route::get('/edit/{id}', [GradeController::class, 'pageEdit']);
      Route::get('/manageSubject/{id}', [GradeController::class, 'pageEditSubject']);
      Route::get('/manageSubject/teacher/edit/{id}/{subjectId}/{teacherId}', [GradeController::class, 'pageEditSubjectTeacher']);
      Route::get('/manageSubject/teacher/multiple/edit/{id}/{subjectId}', [GradeController::class, 'pageEditSubjectTeacherMultiple']);

      Route::get('/manageSubject/addSubject/{id}', [GradeController::class, 'pageAddSubjectTeacher']);
      Route::get('/manageSubject/addSubject/multiple/{id}', [GradeController::class, 'pageAddSubjectTeacherMultiple']);
      Route::post('/manageSubject', [GradeController::class, 'actionPostAddSubjectGrade'])->name('actionAdminAddSubjectGrade');
      Route::post('/manageSubjectMultiple', [GradeController::class, 'actionPostAddSubjectGradeMultiple'])->name('actionAdminAddSubjectGradeMultiple');
      Route::delete('/manageSubject/delete', [GradeController::class, 'deleteSubjectGrade'])->name('dsg');
      Route::get('/subjectGroup/delete/{gradeId}/{subjectId}', [GradeController::class, 'deleteGroupSubjectGrade'])->name('deleteGroupSubjectGrade');
      Route::get('/subject/multiple/delete', [GradeController::class, 'deleteSubjectMultipleGrade'])->name('deleteSubjectMultipleGrade');

      Route::put('manageSubject/{id}', [GradeController::class, 'actionPutSubjectTeacher'])->name('actionAdminUpdateGradeSubjectTeacher');
      Route::put('manageSubject/multi/{id}', [GradeController::class, 'actionPutSubjectMultiTeacher'])->name('actionAdminUpdateGradeSubjectMultiTeacher');
      Route::post('changeTeacherSubject/multi', [GradeController::class, 'actionChangeSubjectMultiTeacher'])->name('actionAdminChangeGradeSubjectMultiTeacher');
      Route::post('/', [GradeController::class, 'actionPost'])->name('actionAdminCreateGrade');
      Route::put('/{id}', [GradeController::class, 'actionPut'])->name('actionAdminUpdateGrade');
   });

   Route::prefix('/exams')->group(function () {
      Route::get('/', [ExamController::class, 'index']);
      Route::get('/create', [ExamController::class, 'pageCreate']);
      Route::get('/{id}', [ExamController::class, 'getById']);
      Route::get('/edit/{id}', [ExamController::class, 'pageEdit']);
      Route::post('/', [ExamController::class, 'actionPost'])->name('actionAdminCreateExam');
      Route::put('/{id}', [ExamController::class, 'actionPut'])->name('actionAdminUpdateExam');
   });

   Route::prefix('/student')->group(function () {
      Route::get('/re-registration/{student_id}', [SuperStudentController::class, 'pageReRegis']);
      Route::patch('/{id}', [SuperStudentController::class, 'inactiveStudent']);
      Route::patch('/activate/{student_id}', [SuperStudentController::class, 'activateStudent']);
   });

   Route::prefix('/subjects')->group(function () {
      Route::get('/', [SubjectController::class, 'index']);
      Route::get('/create', [SubjectController::class, 'pageCreate']);
      Route::get('/edit/{id}', [SubjectController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [SubjectController::class, 'pagePDF']);
      Route::post('/', [SubjectController::class, 'actionPost'])->name('actionAdminCreateSubject');
      Route::put('/{id}', [SubjectController::class, 'actionPut'])->name('actionAdminUpdateSubject');
   });

   Route::prefix('/reports')->group(function () {
      Route::get('/', [ReportController::class, 'index']);
      Route::get('detail/{id}', [ReportController::class, 'detailSubjectClass']);
      Route::get('detailSec/{id}', [ReportController::class, 'detailSubjectClassSec']);
      Route::get('detailSubject/student/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentTeacher']);
      Route::get('detailSubjectSec/student/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentSecTeacher']);

      Route::post('scoringMajorPrimary', [ScoringController::class, 'actionPostMajorPrimary'])->name('actionAdminPostScoringMajorPrimary');
      Route::post('scoringMinorPrimary', [ScoringController::class, 'actionPostMinorPrimary'])->name('actionAdminPostScoringMinorPrimary');
      Route::post('scoringSecondary', [ScoringController::class, 'actionPostSecondary'])->name('actionAdminPostScoringSecondary');


      Route::get('acar/detail/{id}', [ReportController::class, 'acarPrimary']);
      Route::post('acarPrimary', [ScoringController::class, 'actionPostAcarPrimary'])->name('actionAdminPostScoringAcarPrimary');
      Route::post('acarSecondary', [ScoringController::class, 'actionPostAcarSecondary'])->name('actionAdminPostScoringAcarSecondary');
      Route::get('acar/detailSec/{id}', [ReportController::class, 'acarSecondary']);


      Route::get('sooa/detail/{id}', [ReportController::class, 'sooaPrimary']);
      Route::get('sooa/detailSec/{id}', [ReportController::class, 'sooaSecondary']);
      Route::post('sooaPrimary', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionAdminPostScoringSooaPrimary');
      Route::post('sooaSecondary', [ScoringController::class, 'actionPostSooaSecondary'])->name('actionAdminPostScoringSooaSecondary');
      Route::post('updateSooaPrimary/{id}', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionAdminUpdateSooaPrimary');
      Route::post('updateSooaSecondary/{id}', [ScoringController::class, 'actionPostSooaSecondary']);

      Route::get('tcop/detail/{id}', [ReportController::class, 'tcopPrimary']);
      Route::get('tcop/detailSec/{id}', [ReportController::class, 'tcopSecondary']);

      Route::get('acar/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'acarDecline']); // Sudah termasuk acar primary dan secondary
      Route::get('sooa/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'sooaPrimaryDecline']);
      Route::get('scoring/decline/{gradeId}/{teacherId}/{subjectId}/{semester}', [ReportController::class, 'scoringDecline']);
      Route::get('reportCard/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'reportCardDecline']);
      Route::get('tcop/decline/{gradeId}/{teacherId}', [ReportController::class, 'tcopDecline']);

      Route::get('midcard/semestersatu/{id}', [ReportController::class, 'cardSemesterMid']);
      Route::get('semestersatu/detail/{id}', [ReportController::class, 'cardSemester1']);
      Route::get('semesterdua/detail/{id}', [ReportController::class, 'cardSemester2']);
      Route::get('semestersatu/detailSec/{id}', [ReportController::class, 'cardSemester1Sec']);
      Route::get('semesterdua/detailSec/{id}', [ReportController::class, 'cardSemester2Sec']);

      Route::post('report/midReportCard', [ScoringController::class, 'actionPostMidReportCard'])->name('actionAdminPostMidReportCard');
      Route::post('report/reportCard1', [ScoringController::class, 'actionPostReportCard1'])->name('actionAdminPostReportCard1');
      Route::post('report/reportCard2', [ScoringController::class, 'actionPostReportCard2'])->name('actionAdminPostReportCard2');
      Route::post('report/toddler', [ScoringController::class, 'actionPostReportCardToddler'])->name('actionAdminPostReportCardToddler');
      Route::post('report/nursery', [ScoringController::class, 'actionPostReportCardNursery'])->name('actionAdminPostReportCardNursery');
      Route::post('report/kindergarten', [ScoringController::class, 'actionPostReportCardKindergarten'])->name('actionAdminPostReportCardKindergarten');
      Route::post('report/midkindergarten', [ScoringController::class, 'actionPostMidReportCardKindergarten'])->name('actionAdminPostMidReportCardKindergarten');

      Route::get('midreport/print/{id}', [ReportController::class, 'downloadPDFMidSemester']);
      Route::get('semester1/print/{id}', [ReportController::class, 'downloadPDFSemester1']);
      Route::get('semester2/print/{id}', [ReportController::class, 'downloadPDFSemester2']);
      Route::get('toddler/print/{id}', [ReportController::class, 'downloadPDFToddler']);
      Route::get('nursery/print/{id}', [ReportController::class, 'downloadPDFNursery']);
      Route::get('kindergarten/print/{id}', [ReportController::class, 'downloadPDFKindergarten']);

      Route::get('cardToddler/{id}', [ReportController::class, 'cardToddler']);
      Route::get('mid/cardToddler/{id}', [ReportController::class, 'cardToddlerMid']);
      Route::get('cardNursery/{id}', [ReportController::class, 'cardNursery']);
      Route::get('mid/cardNursery/{id}', [ReportController::class, 'cardNurseryMid']);
      Route::get('cardKindergarten/{id}', [ReportController::class, 'cardKindergarten']);
      Route::get('mid/cardKindergarten/{id}', [ReportController::class, 'cardKindergartenMid']);
   });

   Route::prefix('/schedules')->group(function () {
      Route::get('/all', [ScheduleController::class, 'allScheduleSchools']);
      Route::get('/schools', [ScheduleController::class, 'scheduleSchools']);

      Route::get('/grade/create/{id}', [ScheduleController::class, 'create']);
      Route::post('/scheduleGrade', [ScheduleController::class, 'actionCreate'])->name('actionAdminCreateSchedule');
      Route::get('/grades', [ScheduleController::class, 'scheduleGrades']);
      Route::get('/manage/{id}', [ScheduleController::class, 'managePage']);
      Route::get('/detail/{id}', [ScheduleController::class, 'detail']);
      Route::get('/edit/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPage']);
      Route::put('/schedule/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeSchedule'])->name('actionAdminEditSchedule');
      Route::get('/delete/{id}', [ScheduleController::class, 'delete']);

      Route::get('/midexam/create/{id}', [ScheduleController::class, 'createMidExam']);
      Route::post('/midExam', [ScheduleController::class, 'actionCreateMidExam'])->name('actionAdminCreateMidExam');
      Route::get('/midexams', [ScheduleController::class, 'scheduleMidExams']);
      Route::get('/manage/midexam/{id}', [ScheduleController::class, 'managePageMidExam']);
      Route::get('/detail/midexam/{id}', [ScheduleController::class, 'detailMidExam']);
      Route::get('/edit/midexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageMidExam']);
      Route::put('/midexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateMidExam'])->name('actionAdminEditMidExam');
      Route::put('/manage/midexam/edit/date', [ScheduleController::class, 'actionUpdateDateMidExam'])->name('actionAdminEditDateMidExam');
      Route::get('/delete/midexam/{id}', [ScheduleController::class, 'deleteMidExam']);

      Route::get('/finalexam/create/{id}', [ScheduleController::class, 'createFinalExam']);
      Route::post('/finalExam', [ScheduleController::class, 'actionCreateFinalExam'])->name('actionAdminCreateFinalExam');
      Route::get('/finalexams', [ScheduleController::class, 'scheduleFinalExams']);
      Route::get('/manage/finalexam/{id}', [ScheduleController::class, 'managePageFinalExam']);
      Route::get('/detail/finalexam/{id}', [ScheduleController::class, 'detailFinalExam']);
      Route::get('/edit/finalexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageFinalExam']);
      Route::put('/finalexam/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateFinalExam'])->name('actionAdminEditFinalExam');
      Route::put('/manage/finalexam/edit/date', [ScheduleController::class, 'actionUpdateDateFinalExam'])->name('actionAdminEditDateFinalExam');
      Route::get('/delete/finalexam/{id}', [ScheduleController::class, 'deleteFinalExam']);

      Route::post('/schedules/schools', [ScheduleController::class, 'actionCreateOther'])->name('actionAdminCreateOtherSchedule');

      Route::get('/schools/manage/otherSchedule', [ScheduleController::class, 'manageOtherSchedulePage']);
      Route::get('/editSubtitute/{gradeId}/{scheduleId}', [ScheduleController::class, 'editPageSubtitute']);
      Route::put('/subtitute/{gradeId}/{scheduleId}', [ScheduleController::class, 'actionUpdateGradeScheduleSubtitute'])->name('actionAdminEditScheduleSubtitute');

      Route::get('/deleteSubtitute/{id}', [ScheduleController::class, 'deleteSubtitute']);
      Route::get('/otherSchedule/delete/{id}', [ScheduleController::class, 'deleteOtherSchedule']);
   });

   Route::prefix('/typeExams')->group(function () {
      Route::get('/', [TypeExamController::class, 'index']);
      Route::get('/create', [TypeExamController::class, 'pageCreate']);
      Route::get('/edit/{id}', [TypeExamController::class, 'pageEdit']);
      Route::post('/', [TypeExamController::class, 'actionPost'])->name('actionAdminCreateTypeExam');
      Route::put('/{id}', [TypeExamController::class, 'actionPut'])->name('actionAdminUpdateTypeExam');
      Route::get('/delete/{id}', [TypeExamController::class, 'delete']);
   });

   Route::prefix('/typeSchedules')->group(function () {
      Route::get('/', [TypeScheduleController::class, 'index']);
      Route::get('/create', [TypeScheduleController::class, 'pageCreate']);
      Route::get('/edit/{id}', [TypeScheduleController::class, 'pageEdit']);
      Route::post('/', [TypeScheduleController::class, 'actionPost'])->name('actionAdminCreateTypeSchedule');
      Route::put('/{id}', [TypeScheduleController::class, 'actionPut'])->name('actionAdminUpdateTypeSchedule');
      Route::get('/delete/{id}', [TypeScheduleController::class, 'delete']);
   });

   Route::prefix('/masterAcademics')->group(function () {
      Route::get('/', [MasterAcademicsController::class, 'index']);
      Route::get('/create', [MasterAcademicsController::class, 'pageCreate']);
      Route::get('/edit', [MasterAcademicsController::class, 'pageEdit']);
      Route::post('/', [MasterAcademicsController::class, 'actionPost'])->name('actionAdminCreateMasterAcademic');
      Route::put('/{id}', [MasterAcademicsController::class, 'actionPut'])->name('actionAdminUpdateMasterAcademic');
      Route::get('/delete/{id}', [MasterAcademicsController::class, 'delete']);
   });

   Route::prefix('/attendances')->group(function () {
      Route::get('/', [AttendanceController::class, 'index']);
      Route::get('/subject/{id}', [AttendanceController::class, 'subject']);
      Route::get('/subject/student/{gradeId}/{subjectId}', [AttendanceController::class, 'detailAttend']);
      Route::post('/postScoreAttendance', [ScoringController::class, 'actionPostScoreAttendance'])->name('actionAdminPostScoringAttendance');

      Route::get('/teacher/grade/subject', [AttendanceController::class, 'detailAttendance'])->name('attendance.detail');
   });

   Route::prefix('/majorSubjects')->group(function () {
      Route::get('/', [MajorSubjectController::class, 'index']);
      Route::get('/create', [MajorSubjectController::class, 'pageCreate']);
      Route::post('/', [MajorSubjectController::class, 'actionPost'])->name('actionAdminCreateMajorSubject');
   });

   Route::prefix('/minorSubjects')->group(function () {
      Route::get('/', [MinorSubjectController::class, 'index']);
      Route::get('/create', [MinorSubjectController::class, 'pageCreate']);
      Route::post('/', [MinorSubjectController::class, 'actionPost'])->name('actionAdminCreateMinorSubject');
   });

   Route::prefix('/chineseHigher')->group(function () {
      Route::get('/', [ChineseHigherController::class, 'index']);
      Route::get('/add', [ChineseHigherController::class, 'addStudent']);
      Route::post('/', [ChineseHigherController::class, 'actionPost'])->name('actionAdminAddStudentChineseHigher');
      Route::get('/student/delete/{id}', [ChineseHigherController::class, 'delete']);
   });

   Route::prefix('/chineseLower')->group(function () {
      Route::get('/', [ChineseLowerController::class, 'index']);
      Route::get('/add', [ChineseLowerController::class, 'addStudent']);
      Route::post('/', [ChineseLowerController::class, 'actionPost'])->name('actionAdminAddStudentChineseLower');
      Route::get('/student/delete/{id}', [ChineseLowerController::class, 'delete']);
   });
});

Route::middleware(['auth.login', 'role:teacher'])->prefix('/teacher')->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
      Route::get('/detail/teacher', [TeacherController::class, 'getByIdTeacher']);
      Route::get('/edit/teacher', [TeacherController::class, 'editTeacher']);
      Route::put('/edit/{id}', [TeacherController::class, 'actionEdit'])->name('actionUpdateSelfTeacher');
      Route::post('/change-password/{id}', [UserController::class, 'changePassword'])->name('user.changePassword');

      Route::get('attendance/view/student/{id}/{gradeId}/{subjectId}', [AttendanceController::class, 'detailViewAttendTeacher']);
      Route::get('attendance/edit/detail/{date}/{gradeId}/{teacherId}/{semester}', [AttendanceController::class, 'editDetail']);
      Route::get('attendance/all/{id}/{gradeId}', [AttendanceController::class, 'detailAll'])->name('attendanceAll');
      Route::get('attendance/edit/{id}/{gradeId}', [AttendanceController::class, 'edit']);
      Route::get('attendance/{id}', [AttendanceController::class, 'attendTeacher']);
      Route::get('attendance/class/teacher', [AttendanceController::class, 'gradeTeacher']);
      Route::get('attendance/teacher/grade/subject', [AttendanceController::class, 'detailAttendTeacher'])->name('attendance.detail.teacher');
      Route::get('attendance/{id}/{gradeId}/{date}', [AttendanceController::class, 'detail'])->name('attendanceStudent');
      // Route::get('attendance/decline', [AttendanceController::class, 'detail'])->name('attendanceStudent');

      Route::post('/', [AttendanceController::class, 'postAttendance'])->name('actionUpdateAttendanceStudent');
      Route::post('/editAttendance', [AttendanceController::class, 'postEditAttendance'])->name('actionEditAttendanceStudent');
      Route::post('/postScoreAttendance', [ScoringController::class, 'actionPostScoreAttendance'])->name('actionTeacherPostScoringAttendance');

      Route::get('/grade', [GradeController::class, 'teacherGrade']);
      
      // EXAM
      Route::get('/exam/teacher', [ExamController::class, 'teacherExam'])->name('teacher.dashboard.exam');
      Route::get('exam/create', [ExamController::class, 'createTeacherExam']);
      Route::post('/exam', [ExamController::class, 'actionPost'])->name('actionCreateExamTeacher');
      Route::get('exam/detail/{id}', [ExamController::class, 'getById']);
      Route::get('exam/edit/{id}', [ExamController::class, 'pageEdit']);
      Route::put('exam/edit/{id}', [ExamController::class, 'actionPut'])->name('actionUpdateExamTeacher');
      Route::post('exam/delete', [ExamController::class, 'delete'])->name('delete.exam');
      Route::put('/{id}', [ScoreController::class, 'doneExam'])->name('doneExam');

      Route::get('exam/score/{id}', [ScoreController::class, 'score']);
      Route::put('/', [ScoreController::class, 'actionUpdateScore'])->name('actionUpdateScoreExamTeacher');

      Route::get('report/{id}', [ReportController::class, 'teacherReport']);
      Route::get('report/detail/{id}', [ReportController::class, 'detail']);
      Route::get('report/class/teacher', [ReportController::class, 'classTeacher']);
      Route::get('report/subject/teacher', [ReportController::class, 'subjectTeacher']);
      Route::get('report/detailSubjectKindergarten/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentKindergartenTeacher']);
      Route::get('report/detailSubjectPrimary/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentTeacher']);
      Route::get('report/detailSubjectSecondary/{gradeId}/{subjectId}', [ReportController::class, 'detailSubjectClassStudentSecTeacher']);
      Route::get('report/remedial/teacher', [ReportController::class, 'remedial']);

      Route::post('/scoringKindergarten', [ScoringController::class, 'actionPostKindergarten'])->name('actionTeacherPostScoringKindergarten');
      Route::post('/scoringMajorPrimary', [ScoringController::class, 'actionPostMajorPrimary'])->name('actionTeacherPostScoringMajorPrimary');
      Route::post('/scoringMinorPrimary', [ScoringController::class, 'actionPostMinorPrimary'])->name('actionTeacherPostScoringMinorPrimary');
      Route::post('/scoringSecondary', [ScoringController::class, 'actionPostSecondary'])->name('actionTeacherPostScoringSecondary');

      Route::get('report/acar/detail/{id}', [ReportController::class, 'acarPrimary']);
      Route::post('report/acarPrimary', [ScoringController::class, 'actionPostAcarPrimary'])->name('actionTeacherPostScoringAcarPrimary');
      Route::post('report/acarSecondary', [ScoringController::class, 'actionPostAcarSecondary'])->name('actionTeacherPostScoringAcarSecondary');
      Route::get('report/acar/detailSec/{id}', [ReportController::class, 'acarSecondary']);

      Route::get('report/sooa/detail/{id}', [ReportController::class, 'sooaPrimary']);
      Route::get('report/sooa/detailSec/{id}', [ReportController::class, 'sooaSecondary']);
      Route::post('report/sooaPrimary', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionTeacherPostScoringSooaPrimary');
      Route::post('report/sooaSecondary', [ScoringController::class, 'actionPostSooaSecondary'])->name('actionTeacherPostScoringSooaSecondary');
      Route::post('report/updateSooaPrimary/{id}', [ScoringController::class, 'actionPostSooaPrimary'])->name('actionUpdateSooaPrimary');

      Route::get('report/midcard/semestersatu/{id}', [ReportController::class, 'cardSemesterMid']);
      Route::get('report/card/semestersatu/{id}', [ReportController::class, 'cardSemester1']);
      Route::get('report/card/semesterdua/{id}', [ReportController::class, 'cardSemester2']);
      Route::get('report/cardSec/semestersatu/{id}', [ReportController::class, 'cardSemester1Sec']);
      Route::get('report/cardSec/semesterdua/{id}', [ReportController::class, 'cardSemester2Sec']);
      Route::get('report/cardToddler/{id}', [ReportController::class, 'cardToddler']);
      Route::get('report/mid/cardToddler/{id}', [ReportController::class, 'cardToddlerMid']);
      Route::get('report/cardNursery/{id}', [ReportController::class, 'cardNursery']);
      Route::get('report/mid/cardNursery/{id}', [ReportController::class, 'cardNurseryMid']);
      Route::get('report/cardKindergarten/{id}', [ReportController::class, 'cardKindergarten']);
      Route::get('report/mid/cardKindergarten/{id}', [ReportController::class, 'cardKindergartenMid']);

      Route::get('report/tcop/detail/{id}', [ReportController::class, 'tcopPrimary']);
      Route::get('report/tcop/detailSec/{id}', [ReportController::class, 'tcopSecondary']);

      Route::post('report/tcop', [ScoringController::class, 'actionPostTcop'])->name('actionTeacherPostTcop');

      Route::get('midreport/print/{id}', [ReportController::class, 'downloadPDFMidSemester']);
      Route::get('report/semester1/print/{id}', [ReportController::class, 'downloadPDFSemester1']);
      Route::get('report/semester2/print/{id}', [ReportController::class, 'downloadPDFSemester2']);
      Route::get('report/toddler/print/{id}', [ReportController::class, 'downloadPDFToddler']);
      Route::get('report/mid/toddler/print/{id}', [ReportController::class, 'downloadPDFToddlerMid']);
      Route::get('report/nursery/print/{id}', [ReportController::class, 'downloadPDFNursery']);
      Route::get('report/mid/nursery/print/{id}', [ReportController::class, 'downloadPDFNurseryMid']);
      Route::get('report/kindergarten/print/{id}', [ReportController::class, 'downloadPDFKindergarten']);
      Route::get('report/mid/kindergarten/print/{id}', [ReportController::class, 'downloadPDFKindergartenMid']);

      Route::post('report/midReportCard', [ScoringController::class, 'actionPostMidReportCard'])->name('actionTeacherPostMidReportCard');
      Route::post('report/reportCard1', [ScoringController::class, 'actionPostReportCard1'])->name('actionTeacherPostReportCard1');
      Route::post('report/reportCard2', [ScoringController::class, 'actionPostReportCard2'])->name('actionTeacherPostReportCard2');
      Route::post('report/toddler', [ScoringController::class, 'actionPostReportCardToddler'])->name('actionTeacherPostReportCardToddler');
      Route::post('report/nursery', [ScoringController::class, 'actionPostReportCardNursery'])->name('actionTeacherPostReportCardNursery');
      Route::post('report/kindergarten', [ScoringController::class, 'actionPostReportCardKindergarten'])->name('actionTeacherPostReportCardKindergarten');
      Route::post('report/midkindergarten', [ScoringController::class, 'actionPostMidReportCardKindergarten'])->name('actionTeacherPostMidReportCardKindergarten');

      Route::get('scoring/decline/{gradeId}/{teacherId}/{subjectId}/{semester}', [ReportController::class, 'scoringDecline']);
      Route::get('acar/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'acarDecline']); // Sudah termasuk acar primary dan secondary
      Route::get('sooa/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'sooaPrimaryDecline']);
      Route::get('reportCard/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'reportCardDecline']);
      Route::get('midreportCard/decline/{gradeId}/{teacherId}/{semester}', [ReportController::class, 'midreportCardDecline']);
      Route::get('tcop/decline/{gradeId}/{teacherId}', [ReportController::class, 'tcopDecline']);

      Route::get('schedules/all', [ScheduleController::class, 'allScheduleSchools']);
      Route::get('schedules/grade', [ScheduleController::class, 'scheduleGradeTeacher']);
      Route::get('schedules/gradeOther/{id}', [ScheduleController::class, 'scheduleGradeTeacherOther']);
      Route::get('schedules/subject', [ScheduleController::class, 'scheduleSubjectTeacher']);
      Route::get('schedules/invigilater', [ScheduleController::class, 'scheduleInvillagerTeacher']);
      Route::get('schedules/companion/{id}', [ScheduleController::class, 'scheduleCompanionTeacher']);
      Route::get('schools', [ScheduleController::class, 'scheduleTeacherSchools']);
      Route::get('schedules/detail/{teacherId}/{gradeId}', [ScheduleController::class, 'detailScheduleTeacher']);
   });

   Route::prefix('/course')->group(function () {
      Route::get('/', [CourseController::class, 'index'])->name('course.index.teacher');
      Route::get('/{id}/sections/{grade_id}', [CourseController::class, 'sectionsForTeacher'])->name('course.sections.teacher');  // Changed route name for teacher
      Route::get('/{id}/sections/class/{grade_id}', [CourseController::class, 'sectionsClassForTeacher'])->name('course.sections.class.teacher');  // Changed route name for teacher

      Route::get('/{id}/sections/{grade_id}/create', [CourseController::class, 'createSectionTeacher'])->name('subject.create-section');  // Changed route name
      Route::post('/{id}/sections/{grade_id}/store', [CourseController::class, 'storeSectionTeacher'])->name('subject.store-section.teacher');  // Changed route name
      Route::get('/{id}/sections/{grade_id}/{section_id}/activity/create', [CourseController::class, 'createActivityTeacher'])->name('subject.create-activity.teacher');  // Changed route name
      Route::post('/{id}/sections/{grade_id}/{section_id}/activity/store', [CourseController::class, 'storeActivityTeacher'])->name('subject.store-activity.teacher');  // Changed route name
      Route::get('/grade/{id}/subjects', [CourseController::class, 'showGradeSubjects'])->name('grades.subjects.teacher');

      Route::get('/sections/{id}', [CourseController::class, 'editSectionTeacher'])->name('edit-section.teacher');
      Route::post('/update/{id}', [CourseController::class, 'updateSectionTeacher'])->name('update-section.teacher');
      Route::get('/activity/{id}', [CourseController::class, 'editActivityTeacher'])->name('edit-activity.teacher');
      Route::post('/activity/update/{id}', [CourseController::class, 'updateActivityTeacher'])->name('update-activity.teacher');
      Route::delete('/activity/delete/{id}', [CourseController::class, 'deleteActivityTeacher'])->name('delete-activity.teacher');
   });
});

Route::middleware(['auth.login', 'role:student'])->prefix('/student')->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
      Route::get('/detail/{id}', [TeacherController::class, 'getById']);
      Route::get('/grade/{id}', [GradeController::class, 'studentGrade']);

      Route::get('/exam', [ExamController::class, 'gradeExam'])->name('student.dashboard.exam');
      Route::post('/set-assessment-id-student', [ExamController::class, 'setAssessmentId'])->name('set.assessment.id.student');
      Route::get('exam/detail', [ExamController::class, 'getByIdSession'])->name('exam.detail');
      Route::post('/post', [ExamController::class, 'uploadAnswer'])->name('upload.answer');

      Route::get('relation/{id}', [RelationController::class, 'getById']);

      Route::get('/schools', [ScheduleController::class, 'scheduleStudentSchools']);
      Route::get('schedules/grade', [ScheduleController::class, 'scheduleStudent']);

      // Route::get('/midreport', [ReportController::class, 'midreport']);
      // Route::get('/report', [ReportController::class, 'report']);

      Route::get('/midreport', [ReportController::class, 'midreport']);
      Route::get('/report', [ReportController::class, 'report']);
      Route::get('/check-midreport-access', [ReportController::class, 'checkMidreportAccess']);
      Route::get('/check-report-access', [ReportController::class, 'checkReportAccess']);
   });

   Route::prefix('/course')->group(function () {
      Route::get('/', [CourseController::class, 'index'])->name('course.index.student');
      Route::get('/detail', [CourseController::class, 'course'])->name('course.sections.student');
      Route::post('/set-course-id-student', [CourseController::class, 'setCourseId'])->name('set.course.id.student');
   });

   Route::post('/post-answer', [ScoreController::class, 'actionAnswerQuestionStudent'])->name('action.answer.student');
});

Route::middleware(['auth.login', 'role:parent'])->prefix('/parent')->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
      Route::get('/grade/{id}', [GradeController::class, 'studentGrade']);

      Route::get('/exam', [ExamController::class, 'gradeExam'])->name('parent.dashboard.exam');
      Route::post('/set-assessment-id', [ExamController::class, 'setAssessmentId'])->name('set.assessment.id');
      Route::get('exam/detail', [ExamController::class, 'getByIdSession'])->name('exam.detail.parent');

      Route::get('relation/{id}', [RelationController::class, 'getById']);
      Route::get('/score', [ReportController::class, 'detail']);

      Route::get('/schools', [ScheduleController::class, 'scheduleStudentSchools']);
      Route::get('schedules/grade', [ScheduleController::class, 'scheduleStudent']);

      Route::get('/midreport', [ReportController::class, 'midreport']);
      Route::get('/report', [ReportController::class, 'report']);
      Route::get('/check-midreport-access', [ReportController::class, 'checkMidreportAccess']);
      Route::get('/check-report-access', [ReportController::class, 'checkReportAccess']);
   });

   Route::prefix('/course')->group(function () {
      Route::get('/', [CourseController::class, 'index'])->name('course.index.parent');
      Route::get('/detail', [CourseController::class, 'course'])->name('course.sections.parent');
      Route::post('/set-course-id-parent', [CourseController::class, 'setCourseId'])->name('set.course.id.parent');
   });
});

Route::middleware(['auth.login', 'role:superadmin,admin'])->group(function () {
   Route::prefix('/monthlyActivities')->group(function () {
      Route::get('/', [MonthlyActivitiesController::class, 'index']);
      Route::get('/create', [MonthlyActivitiesController::class, 'pageCreate']);
      Route::get('/edit/{id}', [MonthlyActivitiesController::class, 'pageEdit']);
      Route::get('/pdf/{id}', [MonthlyActivitiesController::class, 'pagePDF']);
      Route::post('/monthlyActivities/gas', [MonthlyActivitiesController::class, 'actionPost'])->name('actionCreateMonthly');
      Route::put('/actionEdit', [MonthlyActivitiesController::class, 'actionPut'])->name('actionUpdateMonthly');
      Route::delete('/deleteMonthly', [MonthlyActivitiesController::class, 'delete'])->name('deleteMonthly');
   });

   Route::post('/exam/delete', [ExamController::class, 'delete'])->name('delete.exams');
   Route::post('report/reportCard1', [ScoringController::class, 'actionPostReportCard1'])->name('actionPostReportCard1');
   Route::get('exams/score/{id}', [ScoreController::class, 'score']);
   Route::get('reports/remedial/{id}', [ReportController::class, 'remedialSuper'])->name('detail.remedial');

   Route::put('/', [ScoreController::class, 'actionUpdateScore'])->name('actionUpdateScoreExam');
   Route::put('/changeMasterAcademic/{id}', [MasterAcademicsController::class, 'changeMasterAcademic'])->name('actionChangeMasterAcademic');
   Route::post('report/midReportCard', [ScoringController::class, 'actionPostMidReportCard'])->name('actionPostMidReportCard');

   Route::prefix('/supplementarySubjects')->group(function () {
      Route::get('/', [SupplementarySubjectController::class, 'index']);
      Route::get('/create', [SupplementarySubjectController::class, 'pageCreate']);
      Route::post('/', [SupplementarySubjectController::class, 'actionPost'])->name('actionSuperCreateSupplementarySubject');
      Route::put('/{id}', [SupplementarySubjectController::class, 'actionPut'])->name('actionSuperUpdateSupplementarySubject');
      Route::get('/delete/{id}', [SupplementarySubjectController::class, 'delete'])->name('delete-supplementarysubject');
   });

   Route::prefix('/eca')->group(function () {
      Route::get('/', [EcaController::class, 'index']);
      Route::get('/create', [EcaController::class, 'pageCreate']);
      Route::get('/add/{id}', [EcaController::class, 'addStudent']);
      Route::get('/view/{id}', [EcaController::class, 'detailStudent']);
      Route::post('/addStudent', [EcaController::class, 'actionAddStudent'])->name('actionAdminAddStudent');
      Route::post('/', [EcaController::class, 'actionPost'])->name('actionAdminCreateEca');
      Route::put('/{id}', [EcaController::class, 'actionPut'])->name('actionAdminUpdateEca');
      Route::get('/delete/{id}', [EcaController::class, 'delete'])->name('delete-eca');
      Route::get('/delete/student/{ecaId}/{studentId}', [EcaController::class, 'deleteStudent'])->name('delete-eca-student');
   });

   Route::prefix('{role}')->group(function () {
      Route::prefix('course')->group(function () {
         Route::get('/', [CourseController::class, 'index'])->name('course.index.super');
         Route::get('/{id}/sections/{grade_id}', [CourseController::class, 'sections'])->name('course.sections');

         // Update route untuk create dan store section
         Route::get('/{id}/sections/{grade_id}/create', [CourseController::class, 'createSection'])->name('subject.create-section.super');
         Route::post('/{id}/sections/{grade_id}/store', [CourseController::class, 'storeSection'])->name('subject.store-section');
         Route::get('/sections/{id}', [CourseController::class, 'editSection'])->name('subject.edit-section.super');
         Route::post('/update/{id}', [CourseController::class, 'updateSection'])->name('subject.update-section.super');

         // Update route untuk activity
         Route::get('/{id}/sections/{grade_id}/{section_id}/activity/create', [CourseController::class, 'createActivity'])->name('subject.create-activity');
         Route::get('/activity/{id}', [CourseController::class, 'editActivity'])->name('subject.edit-activity.super');
         Route::post('/activity/update/{id}', [CourseController::class, 'updateActivity'])->name('subject.update-activity.super');

         Route::post('/{id}/sections/{grade_id}/{section_id}/activity/store', [CourseController::class, 'storeActivity'])->name('subject.store-activity');
         Route::delete('/activity/delete/{id}', [CourseController::class, 'deleteActivity'])->name('delete-activity.super');
      });

      Route::get('/grade/{id}/subjects', [CourseController::class, 'showGradeSubjects'])->name('grades.subjects');
   });

   Route::prefix('/tutorials')->group(function () {
      // Menampilkan daftar tutorial
      Route::get('/', [PageTutorialController::class, 'index'])->name('tutorials.index');
      // Halaman membuat tutorial baru
      Route::get('/create', [PageTutorialController::class, 'create'])->name('tutorials.create');
      // Menyimpan tutorial baru
      Route::post('/store', [PageTutorialController::class, 'store'])->name('tutorials.store');
      // Halaman edit tutorial
      Route::get('/edit/{tutorial}', [PageTutorialController::class, 'edit'])->name('tutorials.edit');
      // Update tutorial
      Route::put('/update/{tutorial}', [PageTutorialController::class, 'update'])->name('tutorials.update');
      // Hapus tutorial
      Route::delete('/delete/{tutorial}', [PageTutorialController::class, 'destroy'])->name('tutorials.destroy');
      // Toggle status aktif tutorial
      Route::put('/toggle/{tutorial}', [PageTutorialController::class, 'toggleActive'])->name('tutorials.toggle');
      Route::post('/pages/store', [PageTutorialController::class, 'storePage'])->name('tutorials.pages.store');
   });

   Route::prefix('/letter')->group(function () {
      Route::get('/', [LetterController::class, 'index'])->name('letter.index');
      
      Route::get('/generate-letter-number/{category}', [LetterController::class, 'generateLetterNumber']);

      Route::post('/store', [LetterController::class, 'store'])->name('letter.store');

      Route::delete('/delete/{id}', [LetterController::class, 'destroy'])->name('letter.destroy');
   });

   Route::post('/change-number-admin', [SuperAdminController::class, 'changePhone'])->name('change.number.phone');
});

Route::middleware(['auth.login', 'role:superadmin,admin,teacher'])->group(function () {
   Route::post('remedial', [ScoreController::class, 'remedial'])->name('remedial');
   Route::post('changeFile', [ExamController::class, 'changeFile'])->name('change.file.exam');
   Route::post('changeEbook', [CourseController::class, 'changeEbook'])->name('change.file.ebook');
   Route::post('changeProfile', [UserController::class, 'changeProfile'])->name('change.profile');
   Route::post('changeIcon', [SubjectController::class, 'changeIcon'])->name('change.icon');
   Route::post('/set-section-id', [CourseController::class, 'setSectionId'])->name('set.section.id');
   Route::post('/uploadImageFroala', [ExamController::class, 'uploadImageFroala'])->name('upload.image.froala');
   Route::post('/set-assessment-id', [ExamController::class, 'setAssessmentId'])->name('set.exam.id');
   Route::get('/edit-question/{id}', [ExamController::class, 'getWorkId'])->name('get.work.id');
   Route::post('/post-edit-question/{id}', [ExamController::class, 'actionUpdateQuestion'])->name('action.update.question');
   Route::post('scoreMCE', [ScoreController::class, 'scoreMCE'])->name('scoreMCE');
   Route::delete('/ebook/delete', [CourseController::class, 'deleteEbook'])->name('delete.ebook');
});

Route::get('/assessment-work', [ExamController::class, 'workplace'])->name('work.place');
Route::get('/view-assessment-work', [ExamController::class, 'detailWorkplace'])->name('detail.work.place');

Route::get('/download-watermark/{fileName}', [DownloadController::class, 'downloadWithWatermark'])->name('download.watermark');

Route::get('/get/{tutorial}', [PageTutorialController::class, 'getTutorial'])->name('tutorials.get');

Route::get('/check-payment-status/{uniqueId}', function ($uniqueId) {
   $billingService = app(BillingService::class);
   return response()->json($billingService->checkPaymentStatus($uniqueId));
});

Route::post('/upload-image-question', function (Request $request) {
   if ($request->hasFile('file')) {
      $file = $request->file('file');
      $path = $file->store('image-questions', 'public'); // Simpan di storage/app/public/image-questions
      return response()->json(['link' => asset('storage/' . $path)]);
   }
   return response()->json(['error' => 'No file uploaded'], 400);
})->name('upload.image');

Route::put('/change-password/user/{id}', [SuperAdminController::class, 'changePassword'])->name('user.password');

Route::get('/cc', [ServiceController::class, 'index'])->name('cc.great.care');
Route::get('/cc/{id}', [ServiceController::class, 'detail'])->name('cc.detail');
Route::get('/customer-service', [ServiceController::class, 'chat'])->name('great.care');
Route::get('/chat-admin', [ServiceController::class, 'directWhatsapp'])->name('chat.wa');

Route::get('/get-subtopics/{topicId}', function ($topicId) {
   $subTopics = Chat_bot::where('page_id', $topicId)->get();
   return response()->json(['subtopics' => $subTopics]);
});

Route::get('/get-answer/{subTopicId}', function ($subTopicId) {
   $answer = Chat_bot::where('id', $subTopicId)->first();
   return response()->json($answer);
});

Route::post('/chat-admin', [ServiceController::class, 'store'])->name('messages');
Route::post('/answer-chat', [ServiceController::class, 'answer'])->name('messages.answer');
Route::get('/get-messages', [ServiceController::class, 'getMessages'])->name('get.messages');
Route::get('/get-messages-admin/{id}', [ServiceController::class, 'getMessagesAdmin'])->name('get.messages.admin');
// Route::get('/get-notification', [ServiceController::class, 'notificationMessage'])->name('get.notification');
Route::get('/create-chat-bot', [ServiceController::class, 'create'])->name('create.chat.bot');
Route::get('/edit-chat-bot/{id}', [ServiceController::class, 'edit'])->name('edit.chat.bot');
Route::post('/action-create-chat-bot', [ServiceController::class, 'actionCreate'])->name('actionCreateChatBot');
Route::post('/action-update-chat-bot', [ServiceController::class, 'actionUpdate'])->name('actionUpdateChatBot');

Route::middleware(['auth.login', 'role:library'])->group(function () {
   Route::prefix('/dashboard')->group(function () {
      Route::get('/', [DashboardController::class, 'index']);
   });

   Route::get('/library', [LibraryController::class, 'index']);
   
   Route::get('/data/library', [LibraryController::class, 'library'])->name('library.add.book');
   Route::get('/data/library/edit/{id}', [LibraryController::class, 'editLibrary'])->name('library.edit.book');
   Route::post('/data/library/update', [LibraryController::class, 'updateLibrary'])->name('library.update.book');
   Route::post('/data/library/post', [LibraryController::class, 'storeLibrary'])->name('store.library');
   Route::delete('/delete/library/{id}', [LibraryController::class, 'deleteBook'])->name('delete.library.book');
   
   Route::get('/data/cd-book', [LibraryController::class, 'cdBook'])->name('library.add.cd.and.book');
   Route::get('/data/cd-book/edit/{id}', [LibraryController::class, 'editCdBook'])->name('library.edit.cd.book');
   Route::post('/data/cd-book/post', [LibraryController::class, 'storeCdBook'])->name('store.cd.book');
   Route::post('/data/cd-book/update', [LibraryController::class, 'updateCdBook'])->name('update.cd.book');
   Route::delete('/delete/cd-book/{id}', [LibraryController::class, 'deleteCdBook'])->name('delete.cd.book');
   
   Route::get('/data/three-level', [LibraryController::class, 'threeLevel'])->name('library.three.level');
   Route::get('/data/three-level/edit/{id}', [LibraryController::class, 'editThreeLevel'])->name('edit.three.level');
   Route::post('/data/three-level/post', [LibraryController::class, 'storeThreeLevel'])->name('store.three.level');
   Route::post('/data/three-level/update', [LibraryController::class, 'updateThreeLevel'])->name('update.three.level');
   Route::delete('/delete/library-three-level/{id}', [LibraryController::class, 'deleteThreeLevel'])->name('delete.library.three.level');

   Route::get('/data/small-warehouse', [LibraryController::class, 'smallWarehouse'])->name('library.small.warehouse');
   Route::get('/data/small-warehouse/edit/{id}', [LibraryController::class, 'editSmallWarehouse'])->name('edit.small.warehouse');
   Route::post('/data/small-warehouse/post', [LibraryController::class, 'storeSmallWarehouse'])->name('store.small.warehouse');
   Route::post('/data/small-warehouse/update', [LibraryController::class, 'updateSmallWarehouse'])->name('update.small.warehouse');
   Route::delete('/delete/small-warehouse/{id}', [LibraryController::class, 'deleteSmallWarehouse'])->name('delete.small.warehouse');
   
   Route::get('/data/reference-book', [LibraryController::class, 'referenceBook'])->name('library.reference.book');
   Route::get('/data/reference-book/edit/{id}', [LibraryController::class, 'editReferenceBook'])->name('edit.reference.book');
   Route::post('/data/reference-book/post', [LibraryController::class, 'storeReferenceBook'])->name('store.reference.book');
   Route::post('/data/reference-book/update', [LibraryController::class, 'updateReferenceBook'])->name('update.reference.book');
   Route::delete('/delete/reference-book/{id}', [LibraryController::class, 'deleteReferenceBook'])->name('delete.reference.book');
   
   Route::get('/data/lemari-cd', [LibraryController::class, 'lemariCD'])->name('library.lemari.cd');
   Route::get('/data/lemari-cd/edit/{id}', [LibraryController::class, 'editLemariCD'])->name('edit.lemari.cd');
   Route::post('/data/lemari-cd/post', [LibraryController::class, 'storeLemariCD'])->name('store.lemari.cd');
   Route::post('/data/lemari-cd/update', [LibraryController::class, 'updateLemariCD'])->name('update.lemari.cd');
   Route::delete('/delete/lemari-cd/{id}', [LibraryController::class, 'deleteLemariCD'])->name('delete.lemari.cd');
   
   Route::get('/data/curriculum-old', [LibraryController::class, 'curriculumOld'])->name('library.curriculum.old');
   Route::get('/data/curriculum-old/edit/{id}', [LibraryController::class, 'editCurriculumOld'])->name('edit.curriculum.old');
   Route::post('/data/curriculum-old/post', [LibraryController::class, 'storeCurriculumOld'])->name('store.curriculum.old');
   Route::post('/data/curriculum-old/update', [LibraryController::class, 'updateCurriculumOld'])->name('update.curriculum.old');
   Route::delete('/delete/curriculum-old/{id}', [LibraryController::class, 'deleteCurriculumOld'])->name('delete.curriculum.old');

   Route::get('/reserve-book', [LibraryController::class, 'reserve'])->name('data.reserve.book');
   Route::post('/done-pick/{id}', [LibraryController::class, 'donePick'])->name('done.pick.book');
   Route::post('/done-return/{id}', [LibraryController::class, 'doneReturn'])->name('done.return.book');
   Route::post('/remind/{id}', [LibraryController::class, 'remind'])->name('remind.book');
  

   Route::get('/visitor', [LibraryController::class, 'visitor'])->name('visitor');
   Route::post('/visit-student', [LibraryController::class, 'visitStudent'])->name('visit.student');
   
   Route::get('/create-article-library',[LibraryController::class, 'articleAdmin'])->name('create.article.library');
   Route::get('/article/edit',[LibraryController::class, 'editArticle'])->name('edit.article.library');
   Route::post('/post-article',[LibraryController::class, 'storeArticle'])->name('store.article.library');
   Route::post('/update-article',[LibraryController::class, 'updateArticle'])->name('update.article.library');
   Route::delete('/delete-article/{id}',[LibraryController::class, 'deleteArticle'])->name('delete.article.library');
   Route::post('/upload-image-article', function (Request $request) {
      if ($request->hasFile('file')) {
         $file = $request->file('file');
         $path = $file->store('image-article', 'public'); // Simpan di storage/app/public/image-questions
         return response()->json(['link' => asset('storage/' . $path)]);
      }
      return response()->json(['error' => 'No file uploaded'], 400);
   })->name('upload.image.article');

   Route::get('/plan-visit', [LibraryController::class, 'dashboardPlanVisit']);
   Route::post('/confirm/plan/visit/{id}', [LibraryController::class, 'confirmPlanVisit'])->name('confirm.plan.visit');
   Route::post('/cancel/plan/visit/{id}', [LibraryController::class, 'cancelPlanVisit'])->name('cancel.plan.visit');
});

Route::get('/library-public',[LibraryController::class, 'libraryPublic'])->name('library.public');
Route::get('/booking',[LibraryController::class, 'booking']);
Route::get('/explore-library',[LibraryController::class, 'explore']);
Route::get('/data/reserve/get/{id}',[LibraryController::class, 'getBook'])->name('get.reserve.book');
Route::post('/reserve-book',[LibraryController::class, 'reserveBook'])->name('reserve.book');
Route::get('/visit',[LibraryController::class, 'visit'])->name('visit.library');
Route::get('/article-library',[LibraryController::class, 'article'])->name('article.library');
Route::get('/facility',[LibraryController::class, 'facility'])->name('facility.library');
Route::post('/search-book',[LibraryController::class, 'search'])->name('search'); 
Route::post('/cancel/{id}', [LibraryController::class, 'cancel'])->name('cancel.book');
Route::post('/plan-visit', [LibraryController::class, 'planVisit'])->name('action.plan.visit');
Route::get('/others', [LibraryController::class, 'others'])->name('others');