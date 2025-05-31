<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Teacher_subject;
use App\Models\Teacher_grade;
use App\Models\Grade_subject;
use App\Models\Grade_exam;
use App\Models\Subject_exam;
use App\Models\Exam_relation;
use App\Models\Type_exam;
use App\Models\Score;
use App\Models\Student_exam;
use App\Models\Relationship;
use App\Models\Student_relationship;
use App\Models\Type_schedule;
use App\Models\Schedule;
use App\Models\Subtitute_teacher;
use App\Models\Master_schedule_academic;
use App\Models\Master_academic;


use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class ScheduleController extends Controller
{

   public function allScheduleSchools()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'all schedules',
         ]);

         $typeSchedule = Type_schedule::where('name', '=', 'lesson')->value('id');
         $typeMidExam = Type_schedule::where('name', '=', 'mid exam')->value('id');
         $typeFinalExam = Type_schedule::where('name', '=', 'final exam')->value('id');

         $masterAcademic = Master_academic::where('is_use', true)->first();
         $semester = Master_academic::first()->value('now_semester');
         $academic_year = Master_academic::first()->value('academic_year');

        $substitutes = Subtitute_teacher::whereDate('date', Carbon::today())
         ->join('teachers', 'subtitute_teachers.teacher_id', '=', 'teachers.id')
         ->select('subtitute_teachers.*', 'teachers.name as substitute_teacher')
         ->get()
         ->keyBy('teacher_id'); 
         // dd($substitutes);

         $exams =  Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.is_active', 1)
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->get();

         $schedules = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->select('schedules.*', 'type_schedules.name as type_schedule')
            ->where('type_schedule_id', '!=', $typeSchedule)
            ->where('type_schedule_id', '!=', $typeMidExam)
            ->where('type_schedule_id', '!=', $typeFinalExam)
            ->get();

         // dd($typeSchedule);

         $gradeSchedules = Schedule::where('type_schedule_id', $typeSchedule)
            ->where('grade_id', '=', 5)
            ->where('semester', '=', session('semester'))
            ->where('academic_year', session('academic_year'))
            ->leftJoin('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'schedules.teacher_id')
            ->select(
               'grades.name as grade_name',
               'grades.id as grade_id',
               'grades.class as grade_class',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name',
               'teachers.id as teacher_id',
               'teachers.name as teacher_name'
            )
            ->orderBy('grades.id')
            ->orderBy('schedules.day')
            ->orderBy('schedules.start_time')
            ->get()
            ->map(function ($item) use ($substitutes) {
               // dd($substitutes->has($item->teacher_id));
               // 3. Cek apakah jadwal ini ada penggantinya
               if ($substitutes->has($item->teacher_id)) {
                     $item->teacher_name = $substitutes[$item->teacher_id]->substitute_teacher; // Ganti nama guru
                     $item->is_substitute = true;
               } else {
                     $item->is_substitute = false;
               }
               return $item;
            })
            ->groupBy(function ($item) {
               return $item->grade_name . '-' . $item->grade_class;
            });

         // dd($gradeSchedules);

         // $gradeSchedulestwo = Schedule::where('type_schedule_id', $typeSchedule)
         //    ->where('semester', '=', session('semester'))
         //    ->where('academic_year', '=', session('academic_year'))
         //    ->leftJoin('grades', 'grades.id', '=', 'schedules.grade_id')
         //    ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
         //    ->leftJoin('teachers', 'teachers.id', '=', 'schedules.teacher_id')
         //    ->select('schedules.*', 'schedules.semester as semester',
         //    'grades.name as grade_name', 'grades.id as grade_id', 'grades.class as grade_class',
         //    'subjects.id as subject_id', 'subjects.name_subject as subject_name',
         //    'teachers.id as teacher_id', 'teachers.name as teacher_name')
         //    ->get()
         //    ->groupBy(function($item) {
         //       return $item->grade_name . '-' . $item->grade_class;
         //    });

         $semester1 = Master_academic::where('is_use', true)->value('semester1');
         $endsemester1 = Master_academic::where('is_use', true)->value('end_semester1');
         $semester2 = Master_academic::where('is_use', true)->value('semester2');
         $endsemester2 = Master_academic::where('is_use', true)->value('end_semester2');

         $grades = Grade::whereNotIn('name', ['IGCSE'])->get();
         $teacher = Teacher::where('is_active', true)->orderBy('name', 'asc')->get();
         $subject = Subject::orderBy('name_subject', 'asc')->get();
         
         $data = [
            'grades' => $grades,
            'teacher' => $teacher,
            'subject' => $subject,
         ];
         
         return view('components.schedule.all-schedule', compact('exams', 'schedules', 'gradeSchedules', 'semester1', 'semester2', 'endsemester1', 'endsemester2'))->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleSchools()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'academic schedule',
         ]);
         
         $exams =  Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            // ->where('exams.is_active', 1)
            ->where('exams.academic_year', session('academic_year'))
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->get();

         $getIdLesson = Type_schedule::where('name', '=', 'Lesson')->value('id');
         $getIdMid = Type_schedule::where('name', '=', 'Mid Exam')->value('id');
         $getFinal = Type_schedule::where('name', '=', 'Final Exam')->value('id');

         $schedules = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->where('type_schedule_id', '!=', $getIdLesson)
            ->where('type_schedule_id', '!=', $getIdMid)
            ->where('type_schedule_id', '!=', $getFinal)
            ->where('schedules.academic_year', session('academic_year'))
            ->select('schedules.*', 'type_schedules.name as type_schedule', 'type_schedules.color as color')
            ->get();

         $midExam = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->where('type_schedule_id', '=', $getIdMid)
            ->where('schedules.academic_year', session('academic_year'))
            ->select('schedules.*', 'type_schedules.name as type_schedule', 'type_schedules.color as color')
            ->get();

         $finalExam = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->where('type_schedule_id', '=', $getFinal)
            ->where('schedules.academic_year', session('academic_year'))
            ->select('schedules.*', 'type_schedules.name as type_schedule', 'type_schedules.color as color')
            ->get();


         $typeSchedule = Type_schedule::where('name', '!=', 'Lesson')->get();

         // dd($finalExam);

         return view('components.schedule.schedule', compact('exams', 'schedules', 'midExam', 'finalExam'))->with('data', $typeSchedule);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleTeacherSchools()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'academic schedule',
         ]);

         $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id'); 
         $academic_year = Master_academic::first()->value('academic_year');        
         
         $exams =  Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            // ->where('exams.is_active', 1)
            ->where('exams.teacher_id', $getIdTeacher)
            ->where('exams.academic_year', $academic_year)
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->get();

         $getIdLesson = Type_schedule::where('name', '=', 'Lesson')->value('id');
         $getIdMid = Type_schedule::where('name', '=', 'Mid Exam')->value('id');
         $getFinal = Type_schedule::where('name', '=', 'Final Exam')->value('id');
         
         $finalExam = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->where('type_schedule_id', '=', $getFinal)
            ->where('schedules.academic_year', session('academic_year'))
            ->select('schedules.*', 'type_schedules.name as type_schedule', 'type_schedules.color as color')
            ->get();
            
         $lesson = Type_schedule::where('name', '!=', 'Lesson')->value('id');
         $getIdMid = Type_schedule::where('name', '=', 'Mid Exam')->value('id');
         $getFinal = Type_schedule::where('name', '=', 'Final Exam')->value('id');

         $typeSchedule = Type_schedule::where('name', '!=', 'Lesson')->get();

         $schedules = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->where('type_schedule_id', '!=', $getIdLesson)
            ->where('type_schedule_id', '!=', $getIdMid)
            ->where('type_schedule_id', '!=', $getFinal)
            ->where('schedules.academic_year', session('academic_year'))
            ->select('schedules.*', 'type_schedules.name as type_schedule', 'type_schedules.color as color')
            ->get();

         return view('components.schedule.schedule', compact('exams', 'schedules', 'finalExam'))->with('data', $typeSchedule);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleStudentSchools()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'academic schedule',
         ]);

         $id = session('id_user');

         if (session('role') == 'parent') {
            $getGradeStudent = Student::where('id', session('studentId'))->value('grade_id');
         }
         elseif (session('role') == 'student') {
            $getGradeStudent = Student::where('user_id', $id)->value('grade_id');
         }
         
         $academic_year = Master_academic::first()->value('academic_year');
         
         $exams =  Exam::join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
            ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
            ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
            ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
            ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
            ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
            ->where('exams.is_active', 1)
            ->where('exams.academic_year', $academic_year)
            ->where('grade_exams.grade_id', $getGradeStudent)
            ->select('exams.*', 'grades.name as grade_name', 'grades.class as grade_class', 'subjects.name_subject as subject_name', 'teachers.name as teacher_name', 'type_exams.name as type_exam')
            ->get();

         $getIdLesson = Type_schedule::where('name', '=', 'Lesson')->value('id');
         
         $schedules = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->where('type_schedule_id', '!=', $getIdLesson)  
            ->where('academic_year', $academic_year) 
            ->select('schedules.*', 'type_schedules.name as type_schedule', 'type_schedules.color as color')
            ->get();

         $typeSchedule = Type_schedule::where('name', '!=', 'Lesson')->get();

         return view('components.schedule.schedule', compact('exams', 'schedules'))->with('data', $typeSchedule);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleGrades()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $dataGrade = Grade::with(['student', 'teacher', 'subject', 'schedule'])
            ->withCount(['student as active_student_count', 
               'teacher as active_teacher_count' => function ($query) {
                  $query->where('teacher_grades.academic_year', session('academic_year'));
               }, 
               'subject as active_subject_count' => function ($query) {
                  $query->where('grade_subjects.academic_year', session('academic_year'));
               }, 
               'schedule as active_schedule_count' => function ($query) {
                  $query->where('schedules.semester', session('semester'))
                     ->where('schedules.academic_year', session('academic_year'));
               }
            ])
            ->get();
         
         $typeSchedule = Type_schedule::get();

         $dataSchedule = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->select('schedules.*', 'type_schedules.name as type_schedule_name')
            ->where('type_schedule_id', '!=', 1)
            ->where('semester', session('semester'))
            ->where('academic_year', session('academic_year'))
            ->get();

         $data = [
            'grade' => $dataGrade,
            'typeSchedule' => $typeSchedule,
            'otherSchedule' => $dataSchedule,
         ];

         return view('components.schedule.data-schedule')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleMidExams()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules mid exam',
         ]);

         $semester = session('semester');
         $academic_year = session('academic_year');

         $dataGrade = Grade::with(['student', 'teacher', 'subject', 'schedule'])
               ->withCount(['student as active_student_count', 
                  'teacher as active_teacher_count' => function ($query) {
                     $query->where('teacher_grades.academic_year', session('academic_year'));
               }, 
                  'subject as active_subject_count' => function ($query) {
                     $query->where('grade_subjects.academic_year', session('academic_year'));
               }, 
                  'schedule as active_schedule_count' => function ($query) {
                     $query->where('schedules.academic_year', session('academic_year'));
               }
            ])
            ->get();
         
         $typeSchedule = Type_schedule::get();

         $dataSchedule = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->select('schedules.*', 'type_schedules.name as type_schedule_name')
            ->where('type_schedule_id', '!=', 1)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->get();

         $data = [
            'grade' => $dataGrade,
            'typeSchedule' => $typeSchedule,
            'otherSchedule' => $dataSchedule,
         ];

         return view('components.schedule.data-schedule-midexam')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleFinalExams()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules final exam',
         ]);

         $semester = session('semester');
         $academic_year = session('academic_year');

         $dataGrade = Grade::with(['student', 'teacher', 'subject', 'schedule'])
            ->withCount(['student as active_student_count', 
               'teacher as active_teacher_count' => function ($query) {
                  $query->where('teacher_grades.academic_year', session('academic_year'));
               }, 
                  'subject as active_subject_count' => function ($query) {
                     $query->where('grade_subjects.academic_year', session('academic_year'));
               }, 
                  'schedule as active_schedule_count' => function ($query) {
                     $query->where('schedules.academic_year', session('academic_year'));
               }
            ])
            ->get();
         
         $typeSchedule = Type_schedule::get();

         $dataSchedule = Schedule::join('type_schedules', 'schedules.type_schedule_id', '=', 'type_schedules.id')
            ->select('schedules.*', 'type_schedules.name as type_schedule_name')
            ->where('type_schedule_id', '!=', 1)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->get();

         $data = [
            'grade' => $dataGrade,
            'typeSchedule' => $typeSchedule,
            'otherSchedule' => $dataSchedule,
         ];

         return view('components.schedule.data-schedule-finalexam')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   // menampilkan schedule tiap class
   public function detail($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $semester = session('semester');
         $academic_year = session('academic_year');

         if ($semester === 1) {
            $startSemester = Master_academic::first()->value('semester1');
            $endSemester = Master_academic::first()->value('end_semester1');
         }
         elseif ($semester === 2) {
            $startSemester = Master_academic::first()->value('semester2');
            $endSemester = Master_academic::first()->value('end_semester2');
         }
         
         $typeSchedule = Type_schedule::where('name','=', 'lesson')->value('id');

         $gradeSchedule = Schedule::where('grade_id', $id)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->where('type_schedule_id', $typeSchedule)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->select(
               'schedules.*',
               'grades.id as grade_id',
               'grades.name as grade_name',
               'grades.class as grade_class',
               't1.name as teacher_name',
               't2.name as teacher_companion',
               't2.id as teacher_companion_id',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name'
            )
            ->get();

         $subtituteTeacher = Subtitute_teacher::where('grade_id', $id)
            ->where('type_schedule_id', $typeSchedule)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
               'grades.id as grade_id',
               'grades.name as grade_name', 
               'grades.class as grade_class', 
               't1.name as teacher_name',
               't2.name as teacher_companion',
               't2.id as teacher_companion_id',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name')
            ->get();

         // dd($gradeSchedule);

         $data = Grade::where('id', $id)
            ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
            ->first();

         $teacher = Teacher::where('is_active', true)->orderBy('name', 'asc')->get();
         $grade   = Grade::get();

         // dd($subtituteTeacher);
         // dd($startSemester);

         if (count($gradeSchedule) != 0) {
            return view('components.schedule.detail-schedule', compact('gradeSchedule', 'subtituteTeacher', 'endSemester', 'startSemester'))->with('data', $data)->with('teacher', $teacher)->with('grade', $grade);
         } 
         elseif (count($gradeSchedule) == 0) {
            session()->flash('schedule_empty');
            return redirect()->back();
         }


      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function detailMidExam($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules mid exam',
         ]);

         $semester = session('semester');
         $academic_year = session('academic_year');

         if ($semester === 1) {
            $startSemester = Master_academic::first()->value('semester1');
            $endSemester = Master_academic::first()->value('end_semester1');
         }
         elseif ($semester === 2) {
            $startSemester = Master_academic::first()->value('semester2');
            $endSemester = Master_academic::first()->value('end_semester2');
         }

         $typeSchedule = Type_schedule::where('name', '=', 'mid exam')->value('id');
         
         $gradeSchedule = Schedule::where('grade_id', $id)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->where('type_schedule_id', $typeSchedule)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('type_schedules', 'type_schedules.id', '=', 'schedules.type_schedule_id')
            ->select(
               'schedules.*',
               'grades.id as grade_id',
               'grades.name as grade_name',
               'grades.class as grade_class',
               't1.name as teacher_name',
               't2.name as teacher_companion',
               't2.id as teacher_companion_id',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name',
               'type_schedules.color as color'
            )
            ->get();

         // dd($gradeSchedule);

         $data = Grade::where('id', $id)
            ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
            ->first();

         $teacher = Teacher::get();
         $grade   = Grade::get();

         if (count($gradeSchedule) != 0) {
            return view('components.schedule.detail-schedule-midexam', compact('gradeSchedule', 'endSemester', 'startSemester'))->with('data', $data)->with('teacher', $teacher)->with('grade', $grade);
         } 
         elseif (count($gradeSchedule) == 0) {
            session()->flash('schedule_empty');
            return redirect()->back();
         }

         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function detailFinalExam($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules final exam',
         ]);

         $semester = session('semester');
         $academic_year = session('academic_year');
         
         if ($semester === 1) {
            $startSemester = Master_academic::first()->value('semester1');
            $endSemester = Master_academic::first()->value('end_semester1');
         }
         elseif ($semester === 2) {
            $startSemester = Master_academic::first()->value('semester2');
            $endSemester = Master_academic::first()->value('end_semester2');
         }

         $typeSchedule = Type_schedule::where('name', '=', 'final exam')->value('id');
         
         $gradeSchedule = Schedule::where('grade_id', $id)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->where('type_schedule_id', $typeSchedule)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('type_schedules', 'type_schedules.id', '=', 'schedules.type_schedule_id')
            ->select(
               'schedules.*',
               'grades.id as grade_id',
               'grades.name as grade_name',
               'grades.class as grade_class',
               't1.name as teacher_name',
               't2.name as teacher_companion',
               't2.id as teacher_companion_id',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name',
               'type_schedules.color as color'
            )
            ->get();

         // dd($gradeSchedule);

         $data = Grade::where('id', $id)
            ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
            ->first();

         $teacher = Teacher::get();
         $grade   = Grade::get();

         if (count($gradeSchedule) != 0) {
            return view('components.schedule.detail-schedule-finalexam', compact('gradeSchedule', 'endSemester', 'startSemester'))->with('data', $data)->with('teacher', $teacher)->with('grade', $grade);
         } 
         elseif (count($gradeSchedule) == 0) {
            session()->flash('schedule_empty');
            return redirect()->back();
         }

         

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function managePage($gradeId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules grade',
      ]);

      // dd($gradeId);

      $semester = session('semester');
      $academic_year = session('academic_year');

      try {
         $data = Schedule::where('grade_id', $gradeId)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
               'grades.id as grade_id',
               'grades.name as grade_name', 
               'grades.class as grade_class', 
               't1.id as teacher_id',
               't1.name as teacher_name',
               't2.id as teacher_companion_id',
               't2.name as teacher_companion_name',   
               'subjects.name_subject as subject_name')
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->get();

         $dataSubtitute = Subtitute_teacher::where('grade_id', $gradeId)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     't1.id as teacher_id',
                     't1.name as teacher_name',
                     't2.id as teacher_companion_id',
                     't2.name as teacher_companion_name',   
                     'subjects.name_subject as subject_name')
            ->get();


         // dd($data);
         if (count($data) != 0) {
            return view('components.schedule.data-schedule-grade')->with('data', $data)->with('subtituteTeacher', $dataSubtitute);
         } 
         elseif (count($data) == 0) {
            session()->flash('schedule_empty');
            return redirect()->back();
         }

         // dd($data);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function managePageMidExam($gradeId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules mid exam',
      ]);

      $semester = session('semester');
      $academic_year = session('academic_year');

      try {
         $typeSchedule = Type_schedule::where('name', '=', 'mid exam')->value('id');

         $data = Schedule::where('grade_id', $gradeId)
            ->where('type_schedule_id', $typeSchedule)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
               'grades.id as grade_id',
               'grades.name as grade_name', 
               'grades.class as grade_class', 
               't1.id as teacher_id',
               't1.name as teacher_name',   
               'subjects.name_subject as subject_name')
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->get();
            
         $semester = Master_academic::first()->value('now_semester');

         $date = Schedule::where('type_schedule_id', $typeSchedule)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->select('schedules.date as date', 'schedules.end_date as end_date')
            ->distinct(['date', 'end_date'])
            ->first();

         // dd($date);

         // dd(count($data));

         if (count($data) != 0) {
            return view('components.schedule.manage-schedule-midexam')->with('data', $data)->with('date', $date);
         } 
         elseif (count($data) == 0) {
            session()->flash('schedule_empty');
            return redirect()->back();
         }
         // dd($data);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function managePageFinalExam($gradeId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules final exam',
      ]);

      try {
         $typeSchedule = Type_schedule::where('name', '=', 'final exam')->value('id');
         $semester = session('semester');
         $academic_year = session('academic_year');

         $data = Schedule::where('grade_id', $gradeId)
            ->where('type_schedule_id', $typeSchedule)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     't1.id as teacher_id',
                     't1.name as teacher_name',  
                     'subjects.name_subject as subject_name')
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->get();

            $date = Schedule::where('type_schedule_id', $typeSchedule)
               ->where('semester', $semester)
               ->where('academic_year', $academic_year)
               ->select('schedules.date as date', 'schedules.end_date as end_date')
               ->distinct(['date', 'end_date'])
               ->first();

            if (count($data) != 0) {
               return view('components.schedule.manage-schedule-finalexam')->with('data', $data)->with('date', $date);
            } 
            elseif (count($data) == 0) {
               session()->flash('schedule_empty');
               return redirect()->back();
            }
         // dd($data);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function manageOtherSchedulePage(){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'academic schedule',
      ]);

      $semester = session('semester');
      $academic_year = session('academic_year');

      try {
         $data = Schedule::where('type_schedule_id', '!=', 11)
            ->leftJoin('type_schedules','type_schedules.id', '=', 'schedules.type_schedule_id')
            ->select('schedules.*', 'type_schedules.name as type_schedule')
            ->where('academic_year', $academic_year)
            ->get();

         $typeSchedule = Type_schedule::get();
         return view('components.schedule.data-other-schedule')->with('data', $data)->with('typeSchedule', $typeSchedule);

      } catch (Exception $err) {
         dd($err);
      }
   }

   public function editPage($gradeId, $scheduleId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules grade',
      ]);

      try {
         $data = Schedule::where('schedules.id', $scheduleId)
            ->leftJoin('type_schedules', 'type_schedules.id', '=', 'schedules.type_schedule_id')
            ->leftJoin('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->select('schedules.*', 
               'type_schedules.id as type_schedules_id', 
               'type_schedules.name as type_schedule_name',
               'grades.id as grade_id',
               'grades.name as grade_name',
               'grades.class as grade_class',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name',
               't1.id as teacher_id',
               't1.name as teacher_name',
               't2.id as teacher_companion_id',
               't2.name as teacher_companion_name')
            ->get();

         // dd($data);
         $teacher = Teacher::get();

         return view('components.schedule.edit-schedule')->with('data', $data)->with('gradeId', $gradeId)->with('teacher', $teacher);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function editPageMidExam($gradeId, $scheduleId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules mid exam',
      ]);

      try {
         $data = Schedule::where('schedules.id', $scheduleId)
            ->leftJoin('type_schedules', 'type_schedules.id', '=', 'schedules.type_schedule_id')
            ->leftJoin('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->select('schedules.*', 
               'type_schedules.id as type_schedules_id', 
               'type_schedules.name as type_schedule_name',
               'grades.id as grade_id',
               'grades.name as grade_name',
               'grades.class as grade_class',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name',
               't1.id as teacher_id',
               't1.name as teacher_name',
               't2.id as teacher_companion_id',
               't2.name as teacher_companion_name')
            ->get();

         // dd($data);
         $teacher = Teacher::get();

         return view('components.schedule.edit-schedule-midexam')->with('data', $data)->with('gradeId', $gradeId)->with('teacher', $teacher);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function editPageFinalExam($gradeId, $scheduleId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules final exam',
      ]);

      try {
         $data = Schedule::where('schedules.id', $scheduleId)
            ->leftJoin('type_schedules', 'type_schedules.id', '=', 'schedules.type_schedule_id')
            ->leftJoin('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->select('schedules.*', 
               'type_schedules.id as type_schedules_id', 
               'type_schedules.name as type_schedule_name',
               'grades.id as grade_id',
               'grades.name as grade_name',
               'grades.class as grade_class',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name',
               't1.id as teacher_id',
               't1.name as teacher_name',
               't2.id as teacher_companion_id',
               't2.name as teacher_companion_name')
            ->get();

         // dd($data);
         $teacher = Teacher::get();

         return view('components.schedule.edit-schedule-finalexam')->with('data', $data)->with('gradeId', $gradeId)->with('teacher', $teacher);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function editPageSubtitute($gradeId, $scheduleId){
      session()->flash('page',  $page = (object)[
         'page' => 'schedules',
         'child' => 'schedules grade',
      ]);

      try {
         $data = Subtitute_teacher::where('subtitute_teachers.id', $scheduleId)
            ->leftJoin('type_schedules', 'type_schedules.id', '=', 'subtitute_teachers.type_schedule_id')
            ->leftJoin('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'subtitute_teachers.subject_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->select('subtitute_teachers.*', 
               'type_schedules.id as type_schedules_id', 
               'type_schedules.name as type_schedule_name',
               'grades.id as grade_id',
               'grades.name as grade_name',
               'grades.class as grade_class',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name',
               't1.id as teacher_id',
               't1.name as teacher_name',
               't2.id as teacher_companion_id',
               't2.name as teacher_companion_name')
            ->get();

         // dd($data);
         $teacher = Teacher::get();
         $grade   = Grade::get();

         return view('components.schedule.edit-schedule-subtitute')->with('data', $data)->with('gradeId', $gradeId)->with('teacher', $teacher)->with('grade', $grade);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function actionUpdateOtherSchedule(Request $request, $scheduleId)
   {
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'academic schedule',
         ]);

         $rules = [
            'type_schedule_id' => 'required',
            'date'             => 'required|date',
            'end_date'         => 'nullable|date',
            'note'             => 'required',
            'updated_at'       => 'nullable',
        ];
        
        $data = [
            'type_schedule_id' => $request->type_schedule,
            'date'             => $request->date,
            'end_date'         => $request->end_date,
            'note'             => $request->notes,
            'updated_at'       => now(),
        ];

         $role = session('role');

         $check = Schedule::where('type_schedule_id', $request->type_schedule)
            ->where('date', $request->date)
            ->where('end_date', $request->end_date)
            ->where('note', $request->notes)
            ->where('id', '!=', $scheduleId)
            ->first();

        if ($check) {
            DB::rollBack();
            return redirect('/' . $role . '/schedules/schools/manage/otherSchedule')
                ->withErrors(['subject_id' => ["The schedule for this subject, grade, start time, and end time is already created."]])
                ->withInput($data);
        }

         Schedule::where('id', $scheduleId)->update($data);
   
         DB::commit();

         session()->flash('after_edit_schedule');

         return redirect('/'.$role.'/schedules/schools/manage/otherSchedule' );

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }
   
   public function actionUpdateGradeSchedule(Request $request, $gradeId, $scheduleId)
   {
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'academic schedule',
         ]);
        

      
         $data = [
            'teacher_id'        => $request->teacher_id,
            'teacher_companion' => $request->teacher_companion,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'day'               => $request->day,
            'note'              => $request->notes,
            'updated_at'        => now(),
         ];

         $role = session('role');

         Schedule::where('id', $scheduleId)->update($data);
   
         DB::commit();

         session()->flash('after_edit_grade_schedule');

         return redirect('/' . $role . '/schedules/manage'. '/' . $gradeId );

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }

   public function actionUpdateMidExam(Request $request, $gradeId, $scheduleId)
   {
      // dd($request);
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules mid exam',
         ]);
        
        $data = [
            'teacher_id' => $request->teacher_id,
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'note'       => $request->notes,
            'updated_at' => now(),
        ];

        if (session('semester') == 1) {
            $exam = "mid exam semester 1";

            // APABILA JADWAL INVIGILATER SUDAH ADA
            if (Schedule::where('note', $exam)->where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)->where('start_time', $request->start_time)->where('end_time', $request->end_time)
            ->where('semester', '=', 1)
            ->exists()) {
               $data = Schedule::where('note', $exam)
                  ->where('teacher_id', $request->teacher_id)
                  ->where('day', $request->day)
                  ->where('start_time', $request->start_time)
                  ->where('end_time', $request->end_time)
                  ->leftJoin('teachers', 'teachers.id', '=', 'schedules.teacher_id')
                  ->leftJoin('grades', 'grades.id', '=', 'schedule.grade_id')
                  ->select('teachers.name as teacher_name', 'schedules.*', 
                  DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name")
                  )
                  ->first();

               session()->flash('schedule_error', "Gagal edit jadwal: Guru {$data->teacher_name} sudah ada jadwal di hari {$data->day} {$data->start_time} hingga {$data->end_time} di kelas {$data->grade_name}");
               return redirect()->back();
            }

            // APABILA JADWAL SUDAH ADA
            if (Schedule::where('note', $exam)->where('subject_id')->where('day', $request->day)->where('start_time', $request->start_time)->where('end_time', $request->end_time)
            ->exists()) {
               # code...
            }
        }

         $role = session('role');

         Schedule::where('id', $scheduleId)->update($data);
   
         DB::commit();

         session()->flash('after_edit_midexam_schedule');

         return redirect('/' . $role . '/schedules/manage/midexam'. '/' . $gradeId );

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }

   public function actionUpdateDateMidExam(Request $request)
   {
      try {
         DB::beginTransaction();
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules mid exam',
         ]);
        
         $data = [
            'date'       => $request->date,
            'end_date'   => $request->end_date,
            'updated_at' => now(),
      ];

         if (session('semester') == 1) {
            Schedule::where('note', '=', 'mid exam semester 1')->update($data);
         }
         elseif (session('semester') == 2) {
            Schedule::where('note', '=', 'mid exam semester 2')->update($data);
         }
   
         DB::commit();

         session()->flash('after_edit_midexam_date_schedule');

         return redirect()->back();

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }

   public function actionUpdateFinalExam(Request $request, $gradeId, $scheduleId)
   {
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules final exam',
         ]);
        
        $data = [
            'teacher_id' => $request->teacher_id,
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'note'       => $request->notes,
            'updated_at' => now(),
        ];

         $role = session('role');

         Schedule::where('id', $scheduleId)->update($data);
   
         DB::commit();

         session()->flash('after_edit_finalexam_schedule');

         return redirect('/' . $role . '/schedules/manage/finalexam'. '/' . $gradeId );

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }

   public function actionUpdateDateFinalExam(Request $request)
   {
      try {
         DB::beginTransaction();
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules final exam',
         ]);
        
         $data = [
            'date'     => $request->date,
            'end_date' => $request->end_date,
            'updated_at'        => now(),
         ];

         if (session('semester') == 1) {
            Schedule::where('note', '=', 'final exam semester 1')->update($data);
         }
         elseif (session('semester') == 2) {
            Schedule::where('note', '=', 'final exam semester 2')->update($data);
         }
   
         DB::commit();

         session()->flash('after_edit_finalexam_date_schedule');

         return redirect()->back();

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }

   public function actionUpdateGradeScheduleSubtitute(Request $request, $gradeId, $scheduleId)
   {
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'academic schedule',
         ]);
        
        $data = [
            'teacher_id'        => $request->teacher_id,
            'teacher_companion' => $request->teacher_companion,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'note'             => $request->notes,
            'updated_at'        => now(),
        ];

         $role = session('role');

         Subtitute_teacher::where('id', $scheduleId)->update($data);
   
         DB::commit();

         session()->flash('after_edit_grade_schedule');

         return redirect('/' . $role . '/schedules/manage'. '/' . $gradeId );

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         return abort(500);
      }
   }

   // Menampilkan page tambah jadwal grade
   public function create($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $gradeSubject = Grade_subject::where('grade_id', $id)
            ->join('subjects', 'subjects.id' , '=', 'grade_subjects.subject_id')
            ->get();
         $gradeTeacher = Teacher_grade::where('grade_id', $id)
            ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
            ->get();

         $grade = Grade::where('id', $id)->get();
         $grades = Grade::whereNotIn('name', ['IGCSE'])->get();
         $teacher = Teacher::orderBy('name', 'asc')->get();
         $subject = Subject::orderBy('name_subject', 'asc')->get();

         $typeSchedule = Type_schedule::where('name', 'lesson')->get();

         $data = [
            'gradeSubject' => $gradeSubject,
            'gradeTeacher' => $gradeTeacher,
            'grade' => $grade,
            'grades' => $grades,
            'teacher' => $teacher,
            'subject' => $subject,
            'typeSchedule' => $typeSchedule,
         ];


         if (strtolower($grade[0]['name']) === "primary" || strtolower($grade[0]['name']) === "secondary") {
            return view('components.schedule.create-schedule')->with('data', $data);
         }
         else {
            return view('components.schedule.create-schedule-except')->with('data', $data);   
         }


      } catch (Exception $err) {
         dd($err);
      }
   }

   public function createMidExam($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules mid exam',
         ]);

         $gradeSubject = Grade_subject::where('grade_id', $id)
            ->join('subjects', 'subjects.id' , '=', 'grade_subjects.subject_id')
            ->get();

         $gradeTeacher = Teacher_grade::where('grade_id', $id)
            ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
            ->get();

         $grade = Grade::where('id', $id)->get();
         $grades = Grade::whereNotIn('name', ['Toddler', 'Nursery', 'Kindergarten', 'IGCSE'])->get();
         $teacher = Teacher::get();
         $subject = Subject::get();

         $typeSchedule = Type_schedule::where('name', 'mid exam')->get();

         $data = [
            'gradeSubject' => $gradeSubject,
            'gradeTeacher' => $gradeTeacher,
            'grade' => $grade,
            'grades' => $grades,
            'teacher' => $teacher,
            'subject' => $subject,
            'typeSchedule' => $typeSchedule,
         ];

         // dd($grades);

         return view('components.schedule.create-schedule-midexam')->with('data', $data);
      } catch (Exception $err) {
         dd($err);
      }
   }

   public function createFinalExam($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules final exam',
         ]);

         $gradeSubject = Grade_subject::where('grade_id', $id)
            ->join('subjects', 'subjects.id' , '=', 'grade_subjects.subject_id')
            ->get();

         $gradeTeacher = Teacher_grade::where('grade_id', $id)
            ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
            ->get();

         $grade = Grade::where('id', $id)->get();
         $grades = Grade::whereNotIn('name', ['Toddler', 'Nursery', 'Kindergarten', 'IGCSE'])->get();
         $teacher = Teacher::get();
         $subject = Subject::get();

         $typeSchedule = Type_schedule::where('name', 'final exam')->get();

         $data = [
            'gradeSubject' => $gradeSubject,
            'gradeTeacher' => $gradeTeacher,
            'grade'        => $grade,
            'grades'       => $grades,
            'teacher'      => $teacher,
            'subject'      => $subject,
            'typeSchedule' => $typeSchedule,
         ];


         return view('components.schedule.create-schedule-finalexam')->with('data', $data);
      } catch (Exception $err) {
         dd($err);
      }
   }

   // Menambahkan jadwal grade
   public function actionCreate(Request $request)
   {
      try {
         
         DB::beginTransaction();
            
         session()->flash('page', (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $role = session('role');
         $academic_year = session('academic_year');

         for ($i=0; $i < count($request->notes) ; $i++) { 
            if ($request->teacher_id[$i] && $request->teacher_companion[$i]) {
               if (Schedule::where('day', $request->day[$i])
               ->where('teacher_id', $request->teacher_id[$i])
               ->where('teacher_companion', $request->teacher_companion[$i])
               ->where('grade_id', $request->grade_id)
               ->where('start_time', $request->start_time[$i])
               ->where('end_time', $request->end_time[$i])
               ->where('semester', $request->semester)
               ->exists()) {
                  session()->flash('same_schedule');
                  return redirect('/' . $role . '/schedules/grade/create/' . $request->grade_id)
                  ->withErrors(['notes' => 'Subject Teacher Or Assistant has same schedules in other grade.'])
                  ->withInput();
               }
            }

            $post = [
               'grade_id' => $request->grade_id,
               'subject_id' => $request->subject_id[$i],
               'teacher_id' => $request->teacher_id[$i],
               'teacher_companion' => $request->teacher_companion[$i],
               'type_schedule_id' => $request->type_schedule,
               'note' => $request->notes[$i],
               'day' => $request->day[$i],
               'semester' => $request->semester,
               'academic_year' => $academic_year,
               'start_time' => $request->start_time[$i],
               'end_time' => $request->end_time[$i],
            ];
         
            Schedule::create($post);
            
            DB::commit();
         } 

         session()->flash('after_create_grade_schedule');
         return redirect()->back();
         // return redirect('/' . $role . '/schedules/detail/' . $request->grade_id);
      } catch (Exception $err) {
         dd($err);
         return redirect()->back()->withErrors(['error' => $err->getMessage()])->withInput();
      }
   }

   public function actionCreateMidExam(Request $request)
   {
      // dd($request);
      try {
         session()->flash('page', (object)[
            'page' => 'schedules',
            'child' => 'schedules mid exam',
         ]);

         $role = session('role');
         $academic_year = session('academic_year');

         for ($i=0; $i < count($request->notes) ; $i++) { 
            if ($request->teacher_id[$i]) {
               if (Schedule::where('day', $request->day[$i])
               ->where('type_schedule_id', $request->type_schedule)
               ->where('teacher_id', $request->teacher_id[$i])
               ->where('start_time', $request->start_time[$i])
               ->where('end_time', $request->end_time[$i])
               ->exists()) {
                  $teacher = Teacher::where('id', $request->teacher_id[$i])->value('name');
                  session()->flash('schedule_same', $teacher);
                  return back();
               }
            }
            
            if(Schedule::where('day', $request->day[$i])
                  ->where('grade_id', $request->grade_id)
                  ->where('type_schedule_id', $request->type_schedule)
                  ->where('subject_id', $request->subject_id[$i])
                  ->where('teacher_id', $request->teacher_id[$i])
                  ->where('start_time', $request->start_time[$i])
                  ->where('end_time', $request->end_time[$i])
                  ->where('semester', $request->semester)
                  ->where('academic_year', $academic_year)
                  ->exists()) {
                  return redirect('/' . $role . '/schedules/midexam/create/' . $request->grade_id)
                     ->withErrors(['notes' => 'Schedules mid exam has already been created for this day.'])
                     ->withInput();
            }

            if ($request->semester == 1) {
               $text = "Mid Exam Semester 1";
            }
            elseif ($request->semester == 2) {
               $text = "Mid Exam Semester 2";
            }

            $post = [
               'grade_id' => $request->grade_id,
               'subject_id' => $request->subject_id[$i],
               'teacher_id' => $request->teacher_id[$i],
               'type_schedule_id' => $request->type_schedule,
               'note' => $request->notes[$i],
               'day' => $request->day[$i],
               'date' => $request->date,
               'end_date' => $request->end_date,
               'semester' => $request->semester,
               'academic_year' => $academic_year,
               'start_time' => $request->start_time[$i],
               'end_time' => $request->end_time[$i],
            ];
            
            DB::beginTransaction();
            
            Schedule::create($post);
            
            DB::commit();
         } 

         session()->flash('after_create_midexam_schedule');

         return redirect('/' . $role . '/schedules/detail/midexam/' . $request->grade_id);
      } catch (Exception $err) {
         dd($err);
         return redirect()->back()->withErrors(['error' => $err->getMessage()])->withInput();
      }
   }

   public function actionCreateFinalExam(Request $request)
   {
      // dd($request);
      try {
         session()->flash('page', (object)[
            'page' => 'schedules',
            'child' => 'schedules final exam',
         ]);

         $role = session('role');

         for ($i=0; $i < count($request->notes) ; $i++) { 
            if ($request->teacher_id[$i]) {
               if (Schedule::where('day', $request->day[$i])
               ->where('type_schedule_id', $request->type_schedule)
               ->where('teacher_id', $request->teacher_id[$i])
               ->where('start_time', $request->start_time[$i])
               ->where('end_time', $request->end_time[$i])
               ->where('academic_year', session('academic_year'))
               ->exists()) {
                  return redirect('/' . $role . '/schedules/finalexam/create/' . $request->grade_id)
                  ->withErrors(['notes' => 'Invigilater has same schedules in other grade.'])
                  ->withInput();
               }
            }
            
            if(Schedule::where('day', $request->day[$i])
                  ->where('grade_id', $request->grade_id)
                  ->where('type_schedule_id', $request->type_schedule)
                  ->where('subject_id', $request->subject_id[$i])
                  ->where('teacher_id', $request->teacher_id[$i])
                  ->where('start_time', $request->start_time[$i])
                  ->where('end_time', $request->end_time[$i])
                  ->where('semester', $request->semester)
                  ->where('academic_year', session('academic_year'))
                  ->exists()) {
                  return redirect('/' . $role . '/schedules/finalexam/create/' . $request->grade_id)
                     ->withErrors(['notes' => 'Schedules final exam has already been created for this day.'])
                     ->withInput();
            }

            if ($request->semester == 1) {
               $text = "Final Exam Semester 1";
            }
            elseif ($request->semester == 2) {
               $text = "Final Exam Semester 2";
            }

            $post = [
               'grade_id' => $request->grade_id,
               'subject_id' => $request->subject_id[$i],
               'teacher_id' => $request->teacher_id[$i],
               'type_schedule_id' => $request->type_schedule,
               'note' => $request->notes[$i],
               'day' => $request->day[$i],
               'date' => $request->date,
               'end_date' => $request->end_date,
               'semester' => $request->semester,
               'academic_year' => session('academic_year'),
               'start_time' => $request->start_time[$i],
               'end_time' => $request->end_time[$i],
            ];
            
            DB::beginTransaction();
            
            Schedule::create($post);
            
            DB::commit();
         } 

         session()->flash('after_create_finalexam_schedule');

         return redirect('/' . $role . '/schedules/detail/finalexam/' . $request->grade_id);
      } catch (Exception $err) {
         dd($err);
         return redirect()->back()->withErrors(['error' => $err->getMessage()])->withInput();
      }
   }

   // Menambahkan jadwal lainnya (event,hari libur)
   public function actionCreateOther(Request $request)
   {
      // dd($request);
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'database schedules',
         ]);

         if(count($request->type_schedule) > 1)
         {
            for ($i=0; $i < count($request->type_schedule) ; $i++) { 
               if($request->end_date[$i])
               {
                  $rules = [
                     'type_schedule_id' => $request->type_schedule[$i],
                     'date' => $request->date[$i],
                     'end_date' => $request->end_date[$i],
                     'note' => $request->notes[$i],
                     'academic_year' => session('academic_year'),
                  ];   
               }
               else {
                  $rules = [
                     'type_schedule_id' => $request->type_schedule[$i],
                     'date' => $request->date[$i],
                     'note' => $request->notes[$i],
                     'academic_year' => session('academic_year'),
                  ];
               }

               Schedule::create($rules);
            }
         }
         else 
         {
            for ($j=0; $j < count($request->type_schedule) ; $j++) { 
               if($request->end_date[$j])
               {
                  $rules = [
                     'type_schedule_id' => $request->type_schedule[$j],
                     'date' => $request->date[$j],
                     'end_date' => $request->end_date[$j],
                     'note' => $request->notes[$j],
                     'academic_year' => session('academic_year'),
                  ];   
               }
               else {
                  $rules = [
                     'type_schedule_id' => $request->type_schedule[$j],
                     'date' => $request->date[$j],
                     'note' => $request->notes[$j],
                     'academic_year' => session('academic_year'),
                  ];
               }

               // dd($rules);
               Schedule::create($rules);
            }
         }


        session()->flash('after_create_otherSchedule');

        DB::commit();

        return redirect()->back();

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   // Menampilkan jadwal guru
   public function scheduleGradeTeacher()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'class schedule',
         ]);

         $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');
         $getGradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->get();
         $lesson = Type_schedule::where('name', '=', 'lesson')->value('id');

         $academic_year = session('academic_year');
         $semester      = session('semester');
         
         if ($semester === 1) {
            $startSemester = Master_academic::first()->value('semester1');
            $endSemester = Master_academic::first()->value('end_semester1');
         }
         elseif ($semester === 2) {
            $startSemester = Master_academic::first()->value('semester2');
            $endSemester = Master_academic::first()->value('end_semester2');
         }


         if (count($getGradeId) > 1) {

            $data = Teacher_grade::where('teacher_id', $getIdTeacher)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
               ->where('academic_year', $academic_year)
               ->get();
            $totalClass = count($getGradeId);
            return view('components.schedule.detail-grade-teacher')->with('data', $data)->with('totalClass', $totalClass);
         } else {
            $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');
            $getGradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->value('grade_id');
            
            $gradeSchedule = Schedule::where('grade_id', $getGradeId)
               ->where('semester', $semester)
               ->where('academic_year', $academic_year)
               ->where('type_schedule_id', $lesson)
               ->join('grades', 'grades.id', '=', 'schedules.grade_id')
               ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
               ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
               ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
               ->select(
                  'schedules.*',
                  'grades.id as grade_id',
                  'grades.name as grade_name',
                  'grades.class as grade_class',
                  't1.name as teacher_name',
                  't2.name as teacher_companion',
                  't2.id as teacher_companion_id',
                  'subjects.id as subject_id',
                  'subjects.name_subject as subject_name'
               )
               ->get();
   
            $subtituteTeacher = Subtitute_teacher::where('grade_id', $getGradeId)
               ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
               ->leftJoin('teachers', function($join) {
                  $join->on('teachers.id', '=', 'subtitute_teachers.teacher_id')
                        ->whereNotNull('subtitute_teachers.teacher_id');
               })
               ->leftJoin('subjects', function($join) {
                  $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                        ->whereNotNull('subtitute_teachers.subject_id');
               })
               ->select('subtitute_teachers.*', 
                  'grades.id as grade_id',
                  'grades.name as grade_name', 
                  'grades.class as grade_class', 
                  'teachers.name as teacher_name', 
                  'subjects.id as subject_id',
                  'subjects.name_subject as subject_name'
               )
               ->get();
   
            $data = Teacher_grade::where('teacher_id', $getIdTeacher)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
               ->first();
   
            $teacher = Teacher::get();
            $totalClass = 1;
            
            return view('components.schedule.detail-grade-teacher', compact('gradeSchedule', 'subtituteTeacher', 'endSemester', 'startSemester'))->with('data', $data)->with('teacher', $teacher)->with('totalClass', $totalClass);
         }


      } catch (Exception $err) {
         dd($err);
      }
   }

   public function scheduleSubjectTeacher()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'subject schedule',
         ]);

         $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');
         $getGradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->value('grade_id');

         $semester = Master_academic::first()->value('now_semester');
         $academic_year = Master_academic::first()->value('academic_year');

         if ($semester === 1) {
            $startSemester = Master_academic::first()->value('semester1');
            $endSemester = Master_academic::first()->value('end_semester1');
         }
         elseif ($semester === 2) {
            $startSemester = Master_academic::first()->value('semester2');
            $endSemester = Master_academic::first()->value('end_semester2');
         }
         $lesson = Type_schedule::where('name', '=', 'Lesson')->value('id');

         $gradeSchedule = Schedule::where('teacher_id', $getIdTeacher)
            ->where('academic_year', $academic_year)
            ->where('semester', $semester)
            ->where('type_schedule_id', $lesson)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
                     'grades.id as grade_id',
                     DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"), 
                     't1.name as teacher_name',
                     't2.name as teacher_companion',
                     't2.id as teacher_companion_id',
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         $subtituteTeacher = Subtitute_teacher::where('teacher_id', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
                     'grades.id as grade_id',
                     DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"), 
                     't1.name as teacher_name',
                     't2.name as teacher_companion',
                     't2.id as teacher_companion_id',
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         $assistSchedule = Schedule::where('teacher_companion', $getIdTeacher)
            ->where('academic_year', $academic_year)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
                     'grades.id as grade_id',
                     DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"), 
                     't1.name as teacher_name',
                     't2.name as teacher_companion',
                     't2.id as teacher_companion_id',
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         $assistSubtituteTeacher = Subtitute_teacher::where('teacher_companion', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
                     'grades.id as grade_id',
                     DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"),  
                     't1.name as teacher_name',
                     't2.name as teacher_companion',
                     't2.id as teacher_companion_id',
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();
         // dd($subtituteTeacher);

         return view('components.schedule.detail-subject-teacher', compact('gradeSchedule', 'subtituteTeacher','assistSchedule', 'assistSubtituteTeacher', 'endSemester', 'startSemester'))->with('data', $gradeSchedule);

      } catch (Exception $err) {
         dd($err);
      }
   }

   public function scheduleInvillagerTeacher()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules invigilater',
         ]);

         $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');
         $getGradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->value('grade_id');

         $semester = Master_academic::first()->value('now_semester');
         $academic_year = Master_academic::first()->value('academic_year');

         if ($semester === 1) {
            $startSemester = Master_academic::first()->value('semester1');
            $endSemester = Master_academic::first()->value('end_semester1');
         }
         elseif ($semester === 2) {
            $startSemester = Master_academic::first()->value('semester2');
            $endSemester = Master_academic::first()->value('end_semester2');
         }

         $midExam = Type_schedule::where('name', '=', 'Mid Exam')->value('id');
         $finalExam = Type_schedule::where('name', '=', 'Final Exam')->value('id');


         $midExam = Schedule::where('teacher_id', $getIdTeacher)
            ->where('academic_year', $academic_year)
            ->where('type_schedule_id', $midExam)
            ->where('semester', $semester)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
               'grades.id as grade_id',
               DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"), 
               't1.name as teacher_name',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name')
            ->get();

         
            $finalExam = Schedule::where('teacher_id', $getIdTeacher)
            ->where('academic_year', $academic_year)
            ->where('type_schedule_id', $finalExam)
            ->where('semester', $semester)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
               'grades.id as grade_id',
               DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"), 
               't1.name as teacher_name',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name')
            ->get();

         return view('components.schedule.detail-invigilater-teacher', compact('midExam','finalExam',  'endSemester', 'startSemester'))->with('data', $midExam);

         // return view('components.maintenance');

         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleCompanionTeacher($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules assistant',
         ]);

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');
         $getGradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->value('grade_id');

         $gradeSchedule = Schedule::where('teacher_companion', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'schedules.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'schedules.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->select('schedules.*', 
                     'grades.id as grade_id',
                     DB::raw("CONCAT(grades.name, ' - ', grades.class) as grade_name"), 
                     't1.name as teacher_name',
                     't2.name as teacher_companion',
                     't2.id as teacher_companion_id',
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         $subtituteTeacher = Subtitute_teacher::where('teacher_companion', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers as t1', 't1.id', '=', 'subtitute_teachers.teacher_id')
            ->leftJoin('teachers as t2', 't2.id', '=', 'subtitute_teachers.teacher_companion')
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     't1.name as teacher_name',
                     't2.name as teacher_companion',
                     't2.id as teacher_companion_id',
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         // dd($subtituteTeacher);

         return view('components.schedule.detail-companion-teacher', compact('gradeSchedule', 'subtituteTeacher'))->with('data', $gradeSchedule);

      } catch (Exception $err) {
         dd($err);
      }
   }

   public function detailScheduleTeacher($teacherId, $gradeId)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

         $getIdTeacher = Teacher::where('user_id', $teacherId)->value('id');

         $gradeSchedule = Schedule::where('teacher_id', $getIdTeacher)
            ->where('grade_id', $gradeId)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->join('teachers', 'teachers.id', '=', 'schedules.teacher_id')
            ->join('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->select('schedules.*', 'grades.name as grade_name', 'grades.class as grade_class', 
            'teachers.name as teacher_name', 'subjects.name_subject as subject_name')
            ->get();

         // dd($gradeSchedule);

         $data = Schedule::where('teacher_id', $getIdTeacher)
            ->where('grade_id', $gradeId)
            ->where('academic_year', session('academic_year'))
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->join('teachers', 'teachers.id', '=', 'schedules.teacher_id')
            ->join('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->select('grades.id as grade_id','grades.name as grade_name', 'grades.class as grade_class' ,'subjects.id as subject_id')
            ->get();

         return view('components.schedule.detail-schedule', compact('gradeSchedule'))->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function scheduleStudent()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'class schedule',
         ]);

         // dd(session('id_user'));

         $checkRole = session('role');
         $getIdStudent  = session('studentId');

         if($checkRole == 'parent'){
            $userId = session('id_user');
            $parentId = Relationship::where('user_id', $userId)->value('id');
            $getIdStudent = session('studentId');
            $getGradeId = Student::where('id', $getIdStudent)->value('grade_id');
            $religion = Student::where('id', $getIdStudent)->value('religion');
         }
         elseif($checkRole == 'student'){
            $userId = session('id_user');
            $getIdStudent  = session('studentId');
            $getGradeId = Student::where('id', $getIdStudent)->value('grade_id');
            $religion = Student::where('id', $getIdStudent)->value('religion');
         }

         $semester = Master_academic::first()->value('now_semester');
         if ($semester === 1) {
            $startSemester = Master_academic::first()->value('semester1');
            $endSemester = Master_academic::first()->value('end_semester1');
         }
         elseif ($semester === 2) {
            $startSemester = Master_academic::first()->value('semester2');
            $endSemester = Master_academic::first()->value('end_semester2');
         }
         
         $typeExam = Type_schedule::where('name', '=', 'lesson')->value('id');
         

         // Tentukan nilai $cek berdasarkan agama siswa
         $cek = null;

         if ($religion == "Islam") {
            $cek[] = 36;
         } elseif ($religion == "Catholic Christianity") {
            $cek[] = 34;
         } elseif ($religion == "Protestant Christianity") {
            $cek[] = 37;
         } elseif ($religion == "Buddhism") {
            $cek[] = 35;
         }

         // Query schedule
         $gradeSchedule = Schedule::where('grade_id', $getGradeId)
            ->where('schedules.type_schedule_id', $typeExam)
            ->join('grades', 'grades.id', '=', 'schedules.grade_id')
            ->leftJoin('teachers', function ($join) {
               $join->on('teachers.id', '=', 'schedules.teacher_id')
                     ->whereNotNull('schedules.teacher_id');
            })
            ->leftJoin('subjects', function ($join) {
               $join->on('subjects.id', '=', 'schedules.subject_id')
                     ->whereNotNull('schedules.subject_id');
            })
            ->where(function ($query) use ($cek) {
               $query->whereNotIn('schedules.subject_id', [34, 35, 36, 37]); // Hapus semua Religion
               if ($cek !== null) {
                  $query->orWhere('schedules.subject_id', null)
                        ->orWhere('schedules.subject_id', $cek); // Tambahkan hanya Religion sesuai agama
               }
            })
            ->select(
               'schedules.*',
               'grades.id as grade_id',
               'grades.name as grade_name',
               'grades.class as grade_class',
               'teachers.name as teacher_name',
               'subjects.id as subject_id',
               'subjects.name_subject as subject_name'
            )
            ->get();


         $subtituteTeacher = Subtitute_teacher::where('grade_id', $getGradeId)
            ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
            ->leftJoin('teachers', function($join) {
               $join->on('teachers.id', '=', 'subtitute_teachers.teacher_id')
                     ->whereNotNull('subtitute_teachers.teacher_id');
            })
            ->leftJoin('subjects', function($join) {
               $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                     ->whereNotNull('subtitute_teachers.subject_id');
            })
            ->select('subtitute_teachers.*', 
                     'grades.id as grade_id',
                     'grades.name as grade_name', 
                     'grades.class as grade_class', 
                     'teachers.name as teacher_name', 
                     'subjects.id as subject_id',
                     'subjects.name_subject as subject_name')
            ->get();

         $data = Grade::where('id', $getGradeId)
            ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
            ->first();

         return view('components.student.detail-grade-student', compact('gradeSchedule', 'subtituteTeacher', 'startSemester', 'endSemester'))->with('data', $data);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   // Menghapus data jadwal grade
   public function delete($id)
   {
      try {
         session()->flash('after_delete_schedule');
         $gradeId = Schedule::where('id', $id)->value('grade_id');
         Schedule::where('id', $id)->delete();

         $schedule = Schedule::where('grade_id', $gradeId)
            ->get();

         if (count($schedule) > 0) {
            session()->flash('after_delete_schedule');
            return redirect('/'. session('role') .'/schedules/manage/'. $gradeId);
         }
         else{
            session()->flash('after_delete_schedule');
            return redirect('/admin/schedules/grades');
         }

         return redirect('/'. session('role') .'/schedules/manage/'. $gradeId);
      } 
      catch (Exception $err) {
         dd($err);
         return redirect('/' . session('role'). '/schedules/manage/'. $gradeId)->with('error', 'Terjadi kesalahan saat menghapus data schedule.');
      }
   }

   public function deleteMidExam($id)
   {
      try {
         $gradeId = Schedule::where('id', $id)->value('grade_id');
         
         Schedule::where('id', $id)->delete();

         if (session('semester') == 1) {
            $exam = "mid exam semester 1";
         }
         elseif (session('semester') == 2) {
            $exam = "mid exam semester 2";
         }

         $schedule = Schedule::where('grade_id', $gradeId)
            ->get();

         if (count($schedule) > 0) {
            session()->flash('after_delete_midexam');
            return redirect('/'. session('role') .'/schedules/manage/midexam/'. $gradeId);
         }
         else{
            session()->flash('after_delete_midexam');
            return redirect('/'. session('role') .'/schedules/midexams');
         }

      } 
      catch (Exception $err) {
         dd($err);
         return redirect('/' . session('role'). '/schedules/manage/midexam/'. $gradeId)->with('error', 'Terjadi kesalahan saat menghapus data schedule.');
      }
   }

   public function deleteFinalExam($id)
   {
      try {
         $gradeId = Schedule::where('id', $id)->value('grade_id');
         
         Schedule::where('id', $id)->delete();
         
         session()->flash('after_delete_finalexam');

         $schedule = Schedule::where('grade_id', $gradeId)
            ->get();

         if (count($schedule) > 0) {
            session()->flash('after_delete_finalexam');
            return redirect('/'. session('role') .'/schedules/manage/finalexam/'. $gradeId);
         }
         else{
            session()->flash('after_delete_finalexam');
            return redirect('/'. session('role') .'/schedules/finalexams');
         }
      } 
      catch (Exception $err) {
         dd($err);
         return redirect('/' . session('role'). '/schedules/manage/finalexam/'. $gradeId)->with('error', 'Terjadi kesalahan saat menghapus data schedule.');
      }
   }

   public function deleteSubtitute($id)
   {
      try {
         session()->flash('after_delete_schedule_subtitute');
         $gradeId = Subtitute_teacher::where('id', $id)->value('grade_id');
         Subtitute_teacher::where('id', $id)->delete();

         return redirect('/'. session('role') .'/schedules/manage/'. $gradeId);
      } 
      catch (Exception $err) {
         dd($err);
         return redirect('/' . session('role'). '/subjects')->with('error', 'Terjadi kesalahan saat menghapus data schedule.');
      }
   }

   public function deleteOtherSchedule($id)
   {
      try {

         session()->flash('after_delete_schedule');
         Schedule::where('id', $id)->delete();

         return redirect('/' . session('role') .'/schedules/schools/manage/otherSchedule');
      } 
      catch (Exception $err) {
         dd($err);
         return redirect('/'. session('role') .'/schedules/schools/manage/otherSchedule')->with('error', 'Terjadi kesalahan saat menghapus data schedule.');
      }
   }

   public function subtituteTeacher(Request $request)
   {
      $getSubjectId = Subject::where('name_subject', $request->subject_id)->value('id');
      $getTypeSchedule = Type_schedule::where('name', 'like', '%lesson%')->value('id');

      $mainTeacher = Schedule::where('grade_id', $request->grade_id)
         ->where('subject_id', $getSubjectId)
         ->where('type_schedule_id', $getTypeSchedule)
         ->where('day', $request->day)
         ->where('start_time', $request->start_time)
         ->where('end_time', $request->end_time)
         ->value('teacher_id');

      $mainAssistant = Schedule::where('grade_id', $request->grade_id)
         ->where('subject_id', $getSubjectId)
         ->where('type_schedule_id', $getTypeSchedule)
         ->where('day', $request->day)
         ->where('start_time', $request->start_time)
         ->where('end_time', $request->end_time)
         ->value('teacher_companion');

      Log::info(['assistant' => $mainAssistant, 'teacher' => $mainTeacher, 'subject_id' => $getSubjectId, 'type_schedule_id' => $getTypeSchedule]);
   
      if($mainAssistant == null){
         $mainAssistant = 0;
      }

      $data = [
         'grade_id' => $request->grade_id,
         'subject_id' => $getSubjectId,
         'teacher_main' => $mainTeacher ,
         'assistant_main' => $mainAssistant,
         'teacher_id' => $request->teacher_id,
         'teacher_companion' => $request->teacher_companion,
         'type_schedule_id' =>  $getTypeSchedule,
         'note' => null,
         'date' => $request->date,
         'day' => $request->day,
         'start_time' => $request->start_time,
         'end_time' => $request->end_time,
         'created_at' => now(),
      ];

      session()->flash('after_subtitute_teacher_schedule');
      
      DB::beginTransaction();
      Subtitute_teacher::create($data);
      DB::commit();  

      return response()->json(['Successfully subtitute teacher']);
   }

   public function scheduleGradeTeacherOther($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'schedules grade',
         ]);

            $getIdTeacher = Teacher_grade::where('grade_id', $id)->value('teacher_id');
            $getGradeId = $id;

            $semester = Master_academic::first()->value('now_semester');
            $academic_year = Master_academic::first()->value('academic_year');
            
            if ($semester === 1) {
               $startSemester = Master_academic::first()->value('semester1');
               $endSemester = Master_academic::first()->value('end_semester1');
            }
            elseif ($semester === 2) {
               $startSemester = Master_academic::first()->value('semester2');
               $endSemester = Master_academic::first()->value('end_semester2');
            }

            $lesson = Type_schedule::where('name', '=', 'Lesson')->value('id');

            
            $gradeSchedule = Schedule::where('grade_id', $getGradeId)
               ->where('academic_year', $academic_year)
               ->where('semester', $semester)
               ->where('type_schedule_id', $lesson)
               ->join('grades', 'grades.id', '=', 'schedules.grade_id')
               ->leftJoin('teachers', function($join) {
                  $join->on('teachers.id', '=', 'schedules.teacher_id')
                        ->whereNotNull('schedules.teacher_id');
               })
               ->leftJoin('subjects', function($join) {
                  $join->on('subjects.id', '=', 'schedules.subject_id')
                        ->whereNotNull('schedules.subject_id');
               })
               ->leftJoin('teachers as assistant', function($join) {
                  $join->on('assistant.id', '=', 'schedules.teacher_companion')
                        ->whereNotNull('schedules.teacher_companion');
               })
               ->select('schedules.*', 
                  'grades.id as grade_id',
                  'grades.name as grade_name', 
                  'grades.class as grade_class', 
                  'teachers.name as teacher_name', 
                  'assistant.name as assisstant',
                  'assistant.id as assisstant_id',
                  'subjects.id as subject_id',
                  'subjects.name_subject as subject_name')
               ->get();

            // dd($gradeSchedule);
   
            $subtituteTeacher = Subtitute_teacher::where('grade_id', $getGradeId)
               ->join('grades', 'grades.id', '=', 'subtitute_teachers.grade_id')
               ->leftJoin('teachers', function($join) {
                  $join->on('teachers.id', '=', 'subtitute_teachers.teacher_id')
                        ->whereNotNull('subtitute_teachers.teacher_id');
               })
               ->leftJoin('subjects', function($join) {
                  $join->on('subjects.id', '=', 'subtitute_teachers.subject_id')
                        ->whereNotNull('subtitute_teachers.subject_id');
               })
               ->select('subtitute_teachers.*', 
                        'grades.id as grade_id',
                        'grades.name as grade_name', 
                        'grades.class as grade_class', 
                        'teachers.name as teacher_name', 
                        'subjects.id as subject_id',
                        'subjects.name_subject as subject_name')
               ->get();
   
            $data = Teacher_grade::where('grade_id', $getGradeId)
               ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
               ->select('grades.name as grade_name', 'grades.class as grade_class', 'grades.id as grade_id')
               ->first();
   
            $teacher = Teacher::get();

            // dd($data);
   
            return view('components.schedule.detail-grade-teacher-other', compact('gradeSchedule', 'subtituteTeacher'))->with('data', $data)->with('teacher', $teacher);

      } catch (Exception $err) {
         dd($err);
      }
   }
}
