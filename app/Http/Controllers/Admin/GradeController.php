<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Grade_subject;
use App\Models\Grade_exam;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\Subject_exam;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Constraint;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
   public function index()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         $data = Grade::with(['student', 'teacher' => function($query){
               $query->where('teacher_grades.academic_year', session('academic_year'));
            } , 'exam' => function($query){
               $query->where('exams.semester', session('semester'))
                  ->where('exams.academic_year', session('academic_year'));
            }, 'subject' => function($query){
               $query->where('grade_subjects.academic_year', session('academic_year'));
            }])
            ->withCount([
               'student as active_student_count',
               'teacher as active_teacher_count' => function ($query) {
                  $query->where('teacher_grades.academic_year', session('academic_year')); // Pastikan tabel 'teachers'
               },
               'exam as active_exam_count' => function ($query) {
                  $query->where('exams.semester', session('semester')) // Pastikan tabel 'exams'
                  ->where('exams.academic_year', session('academic_year')); // Pastikan tabel 'exams'
               },
               'subject as active_subject_count' => function ($query) {
                  $query->where('grade_subjects.academic_year', session('academic_year')); // Pastikan tabel 'subjects'
               }
            ])
            ->get();

            // dd($data);
         return view('components.grade.data-grade')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pageCreate()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         $data = [
            'teacher' => Teacher::get(),
            'subject' => Subject::get(),
         ];

         Teacher::orderBy('id', 'ASC')->get();

         return view('components.grade.create-grade')->with('data', $data);
         
      } catch (Exception) {
         return abort(500);
      }
   }

   public function pageAddSubjectTeacher($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         $teacher = Teacher::orderBy('name', 'asc')->get();
         $subject = Subject::orderBy('name_subject', 'asc')->get();
         $grade   = Grade::where('id', $id)->first();

         $data = [
            'teacher' => $teacher,
            'subject' => $subject,
            'grade' => $grade,
         ];

         return view('components.grade.add-subject-grade')->with('data', $data);
         
      } catch (Exception $err) {
         dd($err);
         return abort(500);
      }
   }

   public function pageAddSubjectTeacherMultiple($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         $teacher = Teacher::orderBy('name', 'asc')->get();
         $subject = Subject::orderBy('name_subject', 'asc')->get();
         $grade   = Grade::where('id', $id)->first();

         $data = [
            'teacher' => $teacher,
            'subject' => $subject,
            'grade' => $grade,
         ];

         return view('components.grade.add-subject-grade-multiple')->with('data', $data);
         
      } catch (Exception $err) {
         dd($err);
         return abort(500);
      }
   }
   
   public function actionPost(Request $request)
   {
      try {
         $rules = [
            'name' => $request->name,
            'class' => $request->class,
         ];

         $validator = Validator::make($rules, [
            'name' => 'required|string',
            'class' => 'required|string|max:15',
            ],
         );

         $role = session('role');

         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/'. $role .'/grades/create')->withErrors($validator->messages())->withInput($rules);
         }
         
         if(Grade::where('name', $request->name)->where('class', $request->class)->first())
         {
            DB::rollBack();
            return redirect('/'. $role .'/grades/create')->withErrors([
               'name' => 'Grades ' . $request->name . ' class ' . $request->class . ' is has been created ',
            ])->withInput($rules);
         }
            
         $post = [
            'name' => $request->name,
            'class' => $request->class,
            'created_at' => now(),
         ];
      
         Grade::create($post);
         DB::commit();

         $getIdLastGrade = Grade::latest('id')->value('id');
         // menyimpan class teacher
         $teacher_class = [
            'teacher_id' => $request->teacher_class_id,
            'grade_id'   => $getIdLastGrade,
            'created_at' => now(),
         ];
         $dataTeacherGrade = Teacher_grade::create($teacher_class);


         // menyimpan grade subject & subject teacher
         for($i = 0; $i < count($request->subject_id); $i++){
            // Simpan data subjek dan kelasnya
            $teacher_subject = [
               'teacher_id' => $request->teacher_subject_id[$i],
               'subject_id' => $request->subject_id[$i],
               'grade_id'   => $getIdLastGrade,
               'created_at' => now(),
            ];

            $grade_subject = [
               'grade_id' => $getIdLastGrade,
               'subject_id' => $request->subject_id[$i]
            ];

            $dataTeacherSubject = Teacher_subject::create($teacher_subject);
            $dataGradeSubject = Grade_subject::create($grade_subject);
         } 

      
         session()->flash('after_create_grade');
         return redirect('/' .$role. '/grades');

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   public function actionPostAddSubjectGrade(Request $request)
   {
      try {
         for ($i=0; $i < count($request->subject_id); $i++) { 
            if(Grade_subject::where('grade_id', $request->grade_id)->where('subject_id', $request->subject_id[$i])->where('academic_year', session('academic_year'))->exists())
            {
               DB::rollBack();
               return redirect('/'. session('role').'/grades/manageSubject/addSubject/' . $request->grade_id)
               ->with('sweetalert', [
                  'title' => 'Error',
                  'text' => 'Subject Grade has been created',
                  'icon' => 'error'
               ]);
            }
         
            $teacher_subject = [
               'teacher_id' => $request->teacher_subject_id[$i],
               'subject_id' => $request->subject_id[$i],
               'grade_id'   => $request->grade_id,
               'academic_year' => session('academic_year'),
            ];

            $grade_subject = [
               'grade_id' => $request->grade_id,
               'subject_id' => $request->subject_id[$i],
               'academic_year' => session('academic_year'),
            ];

            Teacher_subject::create($teacher_subject);
            Grade_subject::create($grade_subject);
         }
      
         session()->flash('after_add_subject_grade');
         return redirect('/' .session('role'). '/grades/manageSubject/' . $request->grade_id);

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }

   public function actionPostAddSubjectGradeMultiple(Request $request)
   {
      // dd($request);
      try {
         DB::beginTransaction();

         for ($i=0; $i < count($request->teacher_subject_id_member); $i++) { 
            
            if(Grade_subject::where('grade_id', $request->grade_id)->where('subject_id', $request->subject_id[$i])->where('academic_year', session('academic_year'))->exists())
            {
               DB::rollBack();
               return redirect('/'. session('role').'/grades/manageSubject/addSubject/multiple/' . $request->grade_id)
               ->with('sweetalert', [
                  'title' => 'Error',
                  'text' => 'Subject Grade has been created',
                  'icon' => 'error'
               ]);
            }
            
            $teacher_subject_main = [
               'teacher_id' => $request->teacher_subject_id_main[$i],
               'subject_id' => $request->subject_id[$i],
               'grade_id'   => $request->grade_id,
               'is_lead'    => true,
               'is_group'   => null,
               'academic_year' => session('academic_year'),
               'created_at' => now(),
           ];
           Teacher_subject::create($teacher_subject_main);
   
           foreach ($request->teacher_subject_id_member[$i] as $memberTeacherId) {
               $teacher_subject_member = [
                   'teacher_id' => $memberTeacherId,
                   'subject_id' => $request->subject_id[$i],
                   'grade_id'   => $request->grade_id,
                   'is_lead'    => null,
                   'is_group'  => true,
                   'academic_year' => session('academic_year'),
                   'created_at' => now(),
               ];
               Teacher_subject::create($teacher_subject_member);
           }

            $grade_subject = [
               'grade_id' => $request->grade_id,
               'subject_id' => $request->subject_id[$i],
               'academic_year' => session('academic_year'),
               'created_at' => now(),
            ];
            Grade_subject::create($grade_subject);

            DB::commit();
         }
      
         session()->flash('after_add_subject_grade');
         return redirect('/' .session('role'). '/grades/manageSubject/' . $request->grade_id);

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
      }
   }


   public function detailGrade($id){
      
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);
   
         $grade = Grade::where('id', $id)
            ->select('grades.name as grade_name', 'grades.class as grade_class')
            ->first();
   
         $gradeTeacher = Teacher_grade::where('grade_id',$id)
            ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
            ->where('academic_year', session('academic_year'))
            ->get();
   
         $gradeExam = Grade_exam::join('exams', 'exams.id','=', 'grade_exams.exam_id')
            ->join('type_exams', 'type_exams.id', '=', 'exams.type_exam')
            ->select('exams.*', 'type_exams.name as type_exam_name')
            ->where('grade_exams.grade_id', $id)
            ->where('exams.semester', session('semester'))
            ->where('exams.academic_year', session('academic_year'))
            ->get();
   
         $gradeSubject = Teacher_subject::where('grade_id', $id)
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.*',
               'teacher_subjects.grade_id as grade_id',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
            ->where('academic_year', session('academic_year'))
            ->get();
   
         $subjectTeacher = Teacher_subject::where('grade_id', $id)
            ->join('teachers', 'teachers.id', 'teacher_subjects.teacher_id')
            ->join('subjects', 'subjects.id', 'teacher_subjects.subject_id')
            ->select('subjects.id as subject_id','teachers.name as teacher_name')
            ->get();
   
         // dd($id);
         $gradeStudent = Student::where('grade_id', $id)->where('is_active', true)
            ->orderBy('name', 'ASC')
            ->get();
         // dd($gradeStudent)

         $data = (object)[
            'grade'        => $grade,
            'gradeTeacher' => $gradeTeacher,
            'gradeStudent' => $gradeStudent,
            'gradeSubject' => $gradeSubject,
            'gradeExam'    => $gradeExam, 
            'subjectTeacher' => $subjectTeacher, 
         ];

         // dd($data);
         return view('components.grade.detail-grade')->with('data', $data);
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
            'page' => 'grades',
            'child' => 'grades',
         ]);

         // ambil data teacher yang mengajar di class
         // $teacherGrade = Grade::findOrFail($id)->teacher()->get();
         $teacherGrade = Teacher_grade::where('grade_id', $id)
         ->where('academic_year', session('academic_year'))
         ->pluck('teacher_id')->toArray();
         $subjectGrade = Grade_subject::where('grade_id', $id)
         ->where('academic_year', session('academic_year'))
         ->pluck('subject_id')->toArray();

         // dd(count($subjectGrade));
         $teacher = Teacher::orderBy('name', 'asc')->get();
         $subject = Subject::orderBy('id', 'asc')->get();         
         $data    = Grade::where('id', $id)->first();
         $gradeSubject = Subject::get();
         $allTeacher = Teacher::orderBy('name', 'asc')->get();
         $gradeId = $id;
         
         // dd($subjectGrade);

         // dd($teacher);
         return view('components.grade.edit-grade')->with('data', $data)->with('teacher', $teacher)->with('subject', $subject)->with('teacherGrade', $teacherGrade)->with('subjectGrade', $subjectGrade)->with('allTeacher', $allTeacher)->with('gradeId', $gradeId);
         
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function pageEditSubject($id)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         // ambil data teacher yang mengajar di class
         // $teacherGrade = Grade::findOrFail($id)->teacher()->get();
         $teacherGrade = Teacher_grade::where('grade_id', $id)
         ->where('academic_year', session('academic_year'))
         ->pluck('teacher_id')->toArray();

         $subjectGrade = Teacher_subject::where('grade_id', $id)
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.id as id',
               'teacher_subjects.grade_id as grade_id',
               'teacher_subjects.is_lead', 'teacher_subjects.is_group',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
         ->where('academic_year', session('academic_year'))
         ->orderBy('subjects.name_subject', 'asc')
         ->get();

         $groupSubject = Teacher_subject::where('grade_id', $id)
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.id as id',
               'teacher_subjects.grade_id as grade_id',
               'teacher_subjects.is_lead', 'teacher_subjects.is_group',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
         ->whereNotNull('is_lead')
         ->where('academic_year', session('academic_year'))
         ->distinct()
         ->get();

         // dd(count($subjectGrade));
         $teacher = Teacher::orderBy('id', 'asc')->get();
         $subject = Subject::orderBy('id', 'asc')->get();         
         $data    = Grade::where('id', $id)->get();
         $gradeSubject = Subject::get();
         $allTeacher = Teacher::get();
         $gradeId = $id;


         // dd($groupSubject);
         return view('components.grade.edit-subject')->with('data', $data)->with('teacher', $teacher)->with('subject', $subject)->with('teacherGrade', $teacherGrade)->with('subjectGrade', $subjectGrade)->with('allTeacher', $allTeacher)->with('gradeId', $gradeId)
         ->with('groupSubject', $groupSubject);
         
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function pageEditSubjectTeacher($id, $subjectId, $teacherId)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
               'page' => 'database grades',
               'child' => 'grades',
         ]);
         
         $data = Teacher_subject::where('grade_id', $id)
            ->where('subject_id', $subjectId)
            ->where('teacher_id', $teacherId)
            ->where('academic_year', session('academic_year'))
            ->leftJoin('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.id as teacher_subject_id',
               'teacher_subjects.is_lead', 'teacher_subjects.is_group',
               'grades.name as grade_name', 'grades.id as grade_id', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
            ->first();

         $teacher = Teacher::orderBy('name', 'asc')->get();
         $subject = Subject::orderBy('name_subject', 'asc')->get();
         
         // dd($data);
         return view('components.grade.page-edit-subject')->with('data', $data)->with('subject', $subject)->with('teacher', $teacher);
         
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function pageEditSubjectTeacherMultiple($id, $subjectId)
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
               'page' => 'database grades',
               'child' => 'grades',
         ]);

         $dataMultiple = Teacher_subject::where('grade_id', $id)
            ->where('subject_id', $subjectId)
            ->leftJoin('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.id as teacher_subject_id',
               'teacher_subjects.is_lead', 
               'teacher_subjects.is_group',
               'grades.name as grade_name', 
               'grades.id as grade_id', 
               'grades.class as grade_class',
               'subjects.name_subject as subject_name', 
               'subjects.id as subject_id',
               'teachers.name as teacher_name', 
               'teachers.id as teacher_id',
            )
            ->get();

            $dataMul = [
               'grade_id' => $dataMultiple[0]->grade_id,
               'grade_name' => $dataMultiple[0]->grade_name,
               'grade_class' => $dataMultiple[0]->grade_class,
               'subject_name' => $dataMultiple[0]->subject_name,
               'subject_id' => $dataMultiple[0]->subject_id,
               'is_lead' => [],
               'is_group' => []
            ];
         
         foreach($dataMultiple as $dm) {
            if ($dm->is_lead !== null) {
               $dataMul['is_lead'][] = [
                  'fk' => $dm->teacher_subject_id,
                  'id' => $dm->teacher_id,
                  'name' => $dm->teacher_name
               ];
            } elseif ($dm->is_group !== null) {
               $dataMul['is_group'][] = [
                  'fk' => $dm->teacher_subject_id,
                  'id' => $dm->teacher_id,
                  'name' => $dm->teacher_name
               ];
            }
         }
           

         // Example output for verification:

         
         $data = Teacher_subject::where('grade_id', $id)
            ->where('subject_id', $subjectId)
            ->leftJoin('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.id as teacher_subject_id',
               'teacher_subjects.is_lead', 'teacher_subjects.is_group',
               'grades.name as grade_name', 'grades.id as grade_id', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
            ->first();
         
         $teacher = Teacher::get();
         $teachers = Teacher::get();
         $teacherss = Teacher::get();
         $subject = Subject::get();

         // dd($dataMul);

         return view('components.grade.page-edit-subject-multiple')->with('data', $data)->with('subject', $subject)->with('teacher', $teacher)
         ->with('dataMultiple', $dataMul)->with('teachers', $teachers)->with('teacherss', $teacherss);
         
      } catch (Exception $err) {
         dd($err);
         return abort(404);
      }
   }

   public function actionPut(Request $request, $id)
   {
      DB::beginTransaction();

      // dd($request );
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         $rules = [
            'name' => $request->name,
            'teacher_id' => $request->teacher_id ? $request->teacher_id : null,
            'class' => $request->class,
         ];

         $validator = Validator::make($rules, [
            'name' => 'required|string',
            'class' => 'required|string|max:15',
            ]
         );

         if($validator->fails())
         {
            DB::rollBack();
            return redirect('/'.session('role').'/grades/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
         }

         $data = [
            'teacher_id' => $request->teacher_id,
            'grade_id' => $id,
            'updated_at' => now(),
            'academic_year' => session('academic_year'),
         ];
         
         $check = Grade::where('name', $request->name)->where('class', $request->class)->first();

         if($check && $check->id != $id)
         {
            DB::rollBack();
            return redirect('/'.session('role').'/grades/edit/' . $id)->withErrors(['name' => ["The grade " . $request->name . " with class " . $request->class ." is already created !!!"]])->withInput($rules);
         }

         Teacher_grade::updateOrCreate(
            [
               'grade_id' => $id,
               'academic_year' => session('academic_year')
            ], 
            $data 
        );
         
         DB::commit();

         session()->flash('after_update_grade');

         return redirect('/' . session('role') . '/grades/edit/' . $id);


      } catch (Exception $err) {
         DB::rollBack();
         // return dd($err);
         return abort(500);
      }
   }

   public function actionPutSubjectTeacher(Request $request, $id)
   {
      // dd($request);
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         $rules = [
            'subject_id' => $request->subject,
            'teacher_id' => $request->teacher,
            'grade_id'   => $request->grade_id,
            'updated_at' => now(),
         ];

         $role = session('role');

         // dd($request->teacher);
         $getLatestIdSubjectTeacher = Teacher_subject::where('subject_id', $request->subject)
            ->where('grade_id', $request->grade_id)
            ->value('teacher_id');
         
         Teacher_subject::where('id', $id)->update($rules);

         $getIdExam = Exam::leftJoin('subject_exams', 'subject_exams.exam_id', 'exams.id')
            ->leftJoin('grade_exams', 'grade_exams.exam_id', 'exams.id')
            ->where('exams.teacher_id', $getLatestIdSubjectTeacher)
            ->where('subject_exams.subject_id', $request->subject)
            ->where('grade_exams.grade_id', $request->grade_id)
            ->pluck('exams.id');

         Exam::whereIn('id', $getIdExam)->update(['teacher_id' => $request->teacher, 'updated_at' => now()]);
         
         DB::commit();
         
         session()->flash('after_update_subject_teacher');

         $data = Teacher_subject::where('grade_id', $request->grade_id)
            ->where('subject_id', $request->subject)
            ->where('teacher_id', $request->teacher)
            ->where('academic_year', session('academic_year'))
            ->leftJoin('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.id as teacher_subject_id',
               'grades.name as grade_name', 'grades.id as grade_id', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
            ->first();

         $teacher = Teacher::get();
         $subject = Subject::get();
         
         return view('components.grade.page-edit-subject')->with('data', $data)->with('subject', $subject)->with('teacher', $teacher);
      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         // return abort(500);
      }
   }
   
   public function actionPutSubjectMultiTeacher(Request $request, $id)
   {
      // dd($request);
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         $role = session('role');

         $checkChangeMainTeacher = Teacher_subject::where('id', $id)->value('id');
         if($checkChangeMainTeacher !== $request->main_teacher)
         {
            Teacher_subject::where('id', $id)
            ->update(['teacher_id' => $request->main_teacher]);
         }

         if($request->group_teacher){
            foreach($request->group_teacher as $gt){
               Teacher_subject::create([
                  'grade_id' => $request->grade_id,
                  'subject_id' => $request->subject_id,
                  'teacher_id' => $gt,
                  'is_group' => TRUE,
                  'is_lead' => NULL,
                  'academic_year' => session('academic_year'),
               ]);
            }
         }
         
         DB::commit();
      
         session()->flash('after_update_subject_teacher');

         $data = Teacher_subject::where('teacher_subjects.id', $id)
            ->leftJoin('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
            ->leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
               'teacher_subjects.id as teacher_subject_id',
               'grades.name as grade_name', 'grades.id as grade_id', 'grades.class as grade_class',
               'subjects.name_subject as subject_name', 'subjects.id as subject_id',
               'teachers.name as teacher_name', 'teachers.id as teacher_id'
            )
            ->first();
         
         // dd($data);

         $teacher = Teacher::get();
         $subject = Subject::get();
         
         return redirect()->back();

      } catch (Exception $err) {
         DB::rollBack();
         return dd($err);
         // return abort(500);
      }
   }

   public function actionChangeSubjectMultiTeacher(Request $request)
   {
      // dd($request);
      DB::beginTransaction();

      try {
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'grades',
         ]);

         $rules = [
            'teacher_id' => $request->change_teacher,
            'updated_at' => now(),
         ];

         Teacher_subject::where('id', $request->id)->update($rules);

         DB::commit();

         $get = Teacher_subject::where('id', $request->id)->first();

         return response()->json([
            'success' => true,
            'tes' => $request->id,
         ]);

      } catch (Exception $err) {
         DB::rollBack();
         return response()->json([
            'success' => false
         ]);
         // return dd($err);
         // return abort(500);
      }
   }

   

   
   public function teacherGrade()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'grades',
            'child' => 'class data',
         ]);

         $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');
         $ct = Teacher_grade::where('teacher_id', $getIdTeacher)->value('grade_id');

         $gradeTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
            ->select('grades.*', )
            ->where('academic_year', session('academic_year'))
            ->get();

         $subject = Grade_subject::join('subjects', 'subjects.id', '=', 'grade_subjects.subject_id')
            ->select('subjects.*')
            ->where('grade_id', $ct)
            ->where('academic_year', session('academic_year'))
            ->orderBy('name_subject', 'asc')
            ->get();

         foreach ($gradeTeacher as $gt) {
            $gt->students = Student::where('grade_id', $gt->id)->where('is_active', true)->orderBy('name', 'asc')->get();
         }

         $data = [
            'gradeTeacher' => $gradeTeacher,
         ];

         return view('components.teacher.data-grade-teacher', [
            'data' => $data,
            'subjects' => $subject,
         ]);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function studentGrade($id)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'student grades',
            'child' => 'database student grades',
         ]);

         $getIdTeacher = Teacher::where('user_id', $id)->value('id');

         $gradeTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
            ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
            ->select('grades.*', )
            ->get();

            foreach ($gradeTeacher as $gt) {
               $gt->students = Student::where('grade_id', $gt->id)->where('is_active', true)->get();
           }

         $data = [
            'gradeTeacher' => $gradeTeacher,
         ];

         return view('components.teacher.data-grade-teacher')->with('data', $data);

      } catch (Exception $err) {
         return dd($err);
      }
   }


   public function delete($id)
   {
      try {
         // Hapus data grade
         $teacher = Grade::findOrFail($id);
         $teacher->delete();

         // Hapus data terkait (Teacher_grade, Teacher_subject)
         Teacher_grade::where('teacher_id', $id)
            ->where('academic_year', session('academic_year'))->delete();
         Teacher_subject::where('teacher_id', $id)
            ->where('academic_year', session('academic_year'))->delete();

         session()->flash('after_delete_grade');

         return redirect('/superadmin/grades');
      } catch (Exception $err) {
         dd($err);
         return redirect('/superadmin/grades');
      }
   }

   public function deleteSubjectGrade(Request $request)
   {
      try {
         if($request->type == "singleSubject"){
            $teacherSubject = Teacher_subject::where('id', $request->id)->first();
            $gradeId = $teacherSubject->grade_id;
            $subjectId = $teacherSubject->subject_id;
            
            Grade_subject::where('grade_id', $gradeId)
               ->where('subject_id', $subjectId)
               ->where('academic_year', session('academic_year'))
               ->delete();
            
            Teacher_subject::where('id', $request->id)
               ->delete();

            session()->flash('after_delete_subject_grade');

            return response()->json([
               'success' => true,
            ]);
            // return redirect('/'. session('role') .'/grades/manageSubject/' . $gradeId);
         }
         elseif($request->type == "multipleSubject"){
            $teacherSubject = Teacher_subject::where('id', $request->id)->first();
            $gradeId = $teacherSubject->grade_id;
            $subjectId = $teacherSubject->subject_id;
            
            Grade_subject::where('grade_id', $gradeId)
               ->where('subject_id', $subjectId)
               ->where('academic_year', session('academic_year'))
               ->delete();
            
            Teacher_subject::where('grade_id', $gradeId)
               ->where('subject_id', $subjectId)
               ->where('academic_year', session('academic_year'))
               ->delete();

            return response()->json([
               'success' => true,
               'tes' => $request->id,
            ]);
         }
         elseif($request->type == "member"){
            Teacher_subject::where('id', $request->id)
               ->delete();

            return response()->json([
               'success' => true,
               'tes' => $request->id,
            ]);
         }
         
      } catch (Exception $err) {
         // Log the error for debugging (optional)
         Log::error('Error deleting subject grade: ' . $err->getMessage());
 
         return response()->json([
             'success' => false,
             'message' => 'An error occurred: ' . $err->getMessage()
         ], 500); // Internal Server Error
     }
   }

   public function deleteGroupSubjectGrade($gradeId, $subjectId)
   {
      try {
         Grade_subject::where('grade_id', $gradeId)
            ->where('subject_id', $subjectId)
            ->where('academic_year', session('academic_year'))
            ->delete();
            
         Teacher_subject::where('grade_id', $gradeId)
            ->where('subject_id', $subjectId)
            ->where('academic_year', session('academic_year'))
            ->delete();

         session()->flash('after_delete_subject_grade');

         return redirect('/'. session('role') .'/grades/manageSubject/' . $gradeId);
      } catch (Exception $err) {
         dd($err);
         return redirect('/'. session('role') .'/grades/manageSubject/' . $gradeId);
      }
   }

   public function deleteSubjectGradeMultiple($gradeId, $subjectId, $teacherId)
   {
      try {
         Grade_subject::where('grade_id', $gradeId)
            ->where('subject_id', $subjectId)
            ->delete();

         Teacher_subject::where('teacher_id', $teacherId)
            ->where('grade_id', $gradeId)
            ->where('subject_id', $subjectId)
            ->delete();

         session()->flash('after_delete_subject_grade');

         return redirect('/'. session('role') .'/grades/manageSubject/teacher/multiple/edit/' . $gradeId . '/' . $subjectId);
      } catch (Exception $err) {
         dd($err);
         return redirect('/'. session('role') .'/grades/manageSubject/teacher/multiple/edit/' . $gradeId . '/' . $subjectId);
      }
   }
}