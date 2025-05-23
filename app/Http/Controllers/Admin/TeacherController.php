<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\Grade_subject;
use App\Models\User;

use Exception;

class TeacherController extends Controller
{
   public function index(Request $request)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'database teachers',
      ]);
      
      try {

         $form = (object) [
            'sort' => $request->sort? $request->sort : null,
            'order' => $request->order? $request->order : null,
            'status' => $request->status? $request->status : null,
            'search' => $request->search? $request->search : null,
            'type' => $request->type? $request->type:  null,
         ];

         $data = [];

         $order = $request->sort ? $request->sort : 'asc';
         $status = $request->status? ($request->status == 'true' ? true : false) : true;
         
         if($request->type && $request->search && $request->order)
         {
            $data = Teacher::where($request->type,'LIKE','%'. $request->search .'%')->orderBy($request->order, $order)->paginate(15);
            $count = Teacher::with(['subject', 'grade', 'exam'])
               ->withCount(['subject as active_subject_count', 'grade as active_grade_count', 'exam as active_exam_count'])
               ->orderBy($request->order, $order)
               ->get();
         } 
         else if($request->type && $request->search)
         {
            $data = Teacher::where($request->type,'LIKE','%'. $request->search .'%')->orderBy('created_at', $order)->paginate(15);
            $count = Teacher::with(['subject', 'grade', 'exam'])
               ->withCount(['subject as active_subject_count', 'grade as active_grade_count', 'exam as active_exam_count'])
               ->orderBy('created_at', $order)
               ->get();
         }
         else if($request->order) 
         {
            $data  = Teacher::orderBy($request->order, $order)->paginate(15);
            $count = Teacher::with(['subject', 'grade', 'exam'])
               ->withCount(['subject as active_subject_count', 'grade as active_grade_count', 'exam as active_exam_count'])
               ->orderBy($request->order, $order)
               ->get();
         } 
         else 
         {
            $data = Teacher::orderBy('name', 'asc')->paginate(15);
            $count = Teacher::with(['subject', 'grade', 'exam'])
               ->withCount(['subject as active_subject_count', 'grade as active_grade_count', 'exam as active_exam_count'])
               ->orderBy('name', 'asc')
               ->get();
         }

         return view('components.teacher.data-teacher')->with('data', $data)->with('count', $count)->with('form', $form);

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function getById($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'detail teachers',
      ]);

      try {

         // CHECK ROLE
         if(session('role') == 'admin' || session('role') == 'superadmin')
         {
            $dataTeacher = Teacher::where('unique_id', $id)->first();
            $getIdTeacher = Teacher::where('unique_id', $id)->value('id');
   
            $teacherGrade = DB::table('teacher_grades')
               ->join('grades', 'teacher_grades.grade_id', '=', 'grades.id')
               ->where('teacher_grades.teacher_id', $getIdTeacher)
               ->select('grades.id','grades.name','grades.class')
               ->get();
   
            $teacherSubject = DB::table('teacher_subjects')
               ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
               ->join('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
               ->where('teacher_subjects.teacher_id', $getIdTeacher)
               ->select('teacher_subjects.*','subjects.id', 'subjects.name_subject', 'grades.name', 'grades.class')
               ->get();
   
            $user = DB::table('teachers')
               ->join('users', 'teachers.user_id', '=', 'users.id')
               ->join('roles', 'users.role_id', '=', 'roles.id')
               ->where('teachers.id', $getIdTeacher)
               ->select('users.*', 'roles.name as role_name')
               ->first();
   
            $data = [
               'teacher' => $dataTeacher,
               'teacherGrade' => $teacherGrade,
               'teacherSubject' => $teacherSubject,
               'user' => $user,
            ];
   
            // dd($data);
            return view('components.teacher.detail-teacher')->with('data', $data);
         }
         elseif (session('role') == 'teacher') {
            $dataTeacher = Teacher::where('user_id', $id)->first();
            $getIdTeacher = Teacher::where('user_id', $id)->value('id');

            $teacherGrade = DB::table('teacher_grades')
               ->join('grades', 'teacher_grades.grade_id', '=', 'grades.id')
               ->where('teacher_grades.teacher_id', $getIdTeacher)
               ->select('grades.id','grades.name','grades.class')
               ->get();

            $teacherSubject = DB::table('teacher_subjects')
               ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
               ->join('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
               ->where('teacher_subjects.teacher_id', $getIdTeacher)
               ->select('subjects.id', 'subjects.name_subject', 'grades.name', 'grades.class')
               ->get();

            $user = DB::table('teachers')
               ->join('users', 'teachers.user_id', '=', 'users.id')
               ->join('roles', 'users.role_id', '=', 'roles.id')
               ->where('teachers.id', $getIdTeacher)
               ->select('users.*', 'roles.name as role_name')
               ->first();

            $data = [
               'teacher' => $dataTeacher,
               'teacherGrade' => $teacherGrade,
               'teacherSubject' => $teacherSubject,
               'user' => $user,
            ];

            // dd($data);
            return view('components.teacher.detail-teacher')->with('data', $data);
         }
         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function getByIdTeacher()
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teacher',
         'child' => 'detail teachers',
      ]);

      try {

         $dataTeacher = Teacher::where('user_id', session('id_user'))->first();
         $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');

         $teacherGrade = Teacher_grade::join('grades', 'teacher_grades.grade_id', '=', 'grades.id')
            ->where('teacher_grades.teacher_id', $getIdTeacher)
            ->select('grades.id','grades.name','grades.class')
            ->get();

         $teacherSubject = Teacher_subject::join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->join('grades', 'teacher_subjects.grade_id', '=', 'grades.id')
            ->where('teacher_subjects.teacher_id', $getIdTeacher)
            ->where('teacher_subjects.academic_year', session('academic_year'))
            ->select('subjects.id', 'subjects.name_subject', 'grades.name', 'grades.class')
            ->get();

         $user = Teacher::join('users', 'teachers.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('teachers.id', $getIdTeacher)
            ->select('users.*', 'roles.name as role_name')
            ->first();

         $data = [
            'teacher' => $dataTeacher,
            'teacherGrade' => $teacherGrade,
            'teacherSubject' => $teacherSubject,
            'user' => $user,
         ];

         // dd($data);
         return view('components.teacher.detail-teacher')->with('data', $data);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function pagePost()
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'database teachers',
      ]);

      try {         
         $grade = Grade::orderBy('id', 'asc')->get();
         $subject = Subject::orderBy('id', 'asc')->get();
         $teacher = Teacher::orderBy('id', 'desc')->get();

         $gradeSubject = Grade::with(['subject'])->get();

         $data = $teacher;


         // dd($gradeSubject);
         return view('components.teacher.register-teacher')->with('data', $data)->with('grade', $grade)->with('subject', $subject)->with('gradeSubject', $gradeSubject);

      } catch (Exception $err) {
         return dd($err);
      }
   }
   
   public function actionPost(Request $request)
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'teachers',
            'child' => 'database teachers',
         ]);

         // dd($request->subject_id);

         session()->flash('preloader', true);
         
         $var = DB::table('teachers')->latest('id')->first();
         $unique_id = '';
         
         if(date('Ym') == substr($var->unique_id, 0, 6))
         {
            $unique_id = (string)date('Ym') . str_pad(ltrim(substr($var->unique_id, 7) + 1, '0'), 4, '0', STR_PAD_LEFT);
         } else {
            $unique_id = (string)date('Ym') . str_pad('1', 4, '0', STR_PAD_LEFT);
         }

         $credentials = [
            'user_id' => NULL,
            'name' => $request->name,
            'unique_id' => $unique_id,
            'is_active' => 1,
            'nik' => $request->nik,
            'gender' => $request->gender, 
            'place_birth' => $request->place_birth, 
            'date_birth' => $this->handleDate($request->date_birth, true), 
            'nationality' => $request->nationality, 
            'religion' => $request->religion,
            'home_address' => $request->home_address, 
            'temporary_address' => $request->temporary_address, 
            'handphone' => $request->handphone, 
            'email' => $request->email, 
            'last_education' => $request->last_education, 
            'major' => $request->major, 
         ];

         $validator = Validator::make($credentials, [
            'name' => 'required|min:3|string',
            'nik' => 'required|string|min:9|max:16|unique:teachers',
            'gender' => 'required|string|in:Male,Female',
            'place_birth' => 'required|string',
            'date_birth' => 'required|date',
            'nationality' => 'required|string',
            'home_address' => 'required|string',
            'religion' => 'required|string',
            'temporary_address' => 'required|string', 
            'handphone' => 'required|string|max:13|min:9|unique:teachers', 
            'email' => 'required|email|string|unique:teachers', 
            'last_education' => 'required|string', 
            'major' => 'required|string', 
         ]);

         if($validator->fails())
         {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/'.session('role').'/teachers/register')->withErrors($validator->messages())->withInput($credentials);
         }

         $data = Teacher::create($credentials);

         session()->flash('after_create_teacher', (object) [
            'name' => $data->name,
         ]);

         if (session('role') == 'superadmin') {
            return redirect('/superadmin/teachers/detail/' . $unique_id);
         } 
         elseif (session('role') == 'admin') {
            return redirect('/'.session('role').'/teachers/detail/' . $unique_id);
         }
         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function editPage($id)
   {
      try {

         if(session('role') == 'admin' ||session('role') == 'superadmin')
         {
            session()->flash('page',  $page = (object)[
               'page' => 'teachers',
               'child' => 'database teachers',
            ]);

            $teacher = Teacher::where('id', $id)->first();
   
            $teacherGrade = Teacher_grade::where('teacher_id', $id)->pluck('grade_id')->toArray();
            $teacherSubject = Teacher_subject::where('teacher_id', $id)->select('subject_id', 'grade_id')->get()->toArray();
            $gradeSubject = Grade_subject::get();

            $grade = Grade::orderBy('id', 'asc')->get();
            $subject = Subject::orderBy('id', 'asc')->get();
   
            $data = Teacher::where('unique_id', $id)->first();

            // dd($teacherSubject);

            return view('components.teacher.edit-teacher')->with('data', $data)->with('teacherGrade', $teacherGrade)->with('teacherSubject', $teacherSubject)->with('grade', $grade)->with('subject', $subject)->with('gradeSubject', $gradeSubject);
         }
         elseif (session('role') == 'teacher') 
         {
            session()->flash('page',  $page = (object)[
               'page' => 'teachers',
               'child' => 'spesifik teachers',
            ]);

            $teacher = Teacher::where('user_id', $id)->first();
            $getIdTeacher = $teacher->id;
   
            $teacherGrade = Teacher_grade::where('teacher_id', $getIdTeacher)->pluck('grade_id')->toArray();
            $teacherSubject = Teacher_subject::where('teacher_id', $getIdTeacher)->select('subject_id', 'grade_id')->get()->toArray();
   
            $grade = Grade::orderBy('id', 'asc')->get();
            $subject = Subject::orderBy('id', 'asc')->get();
   
            $data = Teacher::where('user_id', $id)->first();

            // dd($teacherSubject);

            return view('components.teacher.edit-teacher')->with('data', $data)->with('teacherGrade', $teacherGrade)->with('teacherSubject', $teacherSubject)->with('grade', $grade)->with('subject', $subject);
         }
         

      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function editTeacher()
   {
      try {
         session()->flash('page',  $page = (object)[
            'page' => 'teacher',
            'child' => 'spesifik teachers',
         ]);

         $teacher = Teacher::where('user_id', session('id_user'))->first();
         $getIdTeacher = $teacher->id;

         $teacherGrade = Teacher_grade::where('teacher_id', $getIdTeacher)->pluck('grade_id')->toArray();
         $teacherSubject = Teacher_subject::where('teacher_id', $getIdTeacher)->select('subject_id', 'grade_id')->get()->toArray();

         $grade = Grade::orderBy('id', 'asc')->get();
         $subject = Subject::orderBy('id', 'asc')->get();

         $data = Teacher::where('user_id', session('id_user'))->first();

         return view('components.teacher.edit-teacher')->with('data', $data)->with('teacherGrade', $teacherGrade)->with('teacherSubject', $teacherSubject)->with('grade', $grade)->with('subject', $subject);
         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function actionEdit(Request $request, $id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'teachers',
         'child' => 'database teachers',
      ]);

      // dd($id);

      session()->flash('preloader', true);

      try {
         
         $credentials = $request->only(['id', 'name', 'nik', 'gender', 'place_birth', 'date_birth', 'nationality', 'home_address', 'religion', 'temporary_address', 'email', 'handphone', 'last_education', 'major']);
         
         $credentials['date_birth'] = $this->handleDate($credentials['date_birth'], 'Y-m-d');

         $validator = Validator::make($credentials, [
            'name' => 'required|min:3|string',
            'nik' => 'required|string|min:9|max:16',
            'gender' => 'required|string|in:Male,Female',
            'place_birth' => 'required|string',
            'date_birth' => 'required|date',
            'nationality' => 'required|string',
            'email' => 'required|string|email',
            'handphone' => 'required|string|max:13|min:9',
            'last_education' => 'required|string',
            'major' => 'required|string',
            'temporary_address' => 'required|string',
            'home_address' => 'required|string',
            'religion' => 'required|string',
         ]);
         
         $checkUniqueNik = Teacher::where('nik', $request->nik)->first();
         $checkUniqueEmail = Teacher::where('email', $request->email)->first();
         $checkUniqueHandphone = Teacher::where('handphone', $request->handphone)->first();
         
         if($checkUniqueNik) if($checkUniqueNik->id != $id)
         {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/'.session('role').'/teachers' . '/' . $id)->withErrors(['nik' => 'nik or passport has already been taken.'])->withInput($credentials);
         } 
         if($checkUniqueEmail) if($checkUniqueEmail->id != $id) {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/'.session('role').'/teachers' . '/' . $id)->withErrors(['email' => 'email has already been taken.'])->withInput($credentials);
         } 
         
         if($checkUniqueHandphone) if($checkUniqueHandphone->id != $id) {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/'.session('role').'/teachers' . '/' . $id)->withErrors(['handphone' => 'Mobilephone has already been taken.'])->withInput($credentials);
         }
         
         if($validator->fails())
         {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/'.session('role').'/teachers'. '/' . $id)->withErrors($validator->messages())->withInput($credentials);
         }
         
         // dd($credentials);
         Teacher::where('id', $id)->update($credentials);
                  
         DB::commit();

         session()->flash('after_update_teacher');
         $target = Teacher::where('id', $id)->first();
         
         if(session('role') == 'superadmin'){
            return redirect('/superadmin/teachers/detail/' . $target->unique_id);
         }
         elseif(session('role') == 'admin'){
            return redirect('/admin/teachers/detail/' . $target->unique_id);
         }
         elseif (session('role') == 'teacher') {
            return redirect('/teacher/dashboard/detail/teacher');
         }
         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function handleDate($date, $format = false)
   {

      $dateStr = '';

      if($format)
      {
         
         $date_format = explode('/', $date);
         return $date_format[2] . '-' . $date_format[1] .  '-' . $date_format[0];
      } else {
         $date_format = explode('-', $date);
         return $date_format[2] . '/' . $date_format[1] .  '/' . $date_format[0];
      }
   }

   public function deactivated($id)
   {
      try {
         
         if(!Teacher::where('id', $id)->first())
         {
            return response()->json([
               'message' => 'Teacher with id ' . $id . ' not found!!!',
            ], 404);
         }

         Teacher::where('id', $id)->update([
            'is_active' => 0,
         ]);

         return response()->json([
            'success' => true,
         ]);

      } catch (Exception $err) {
         dd($err);
         return response()->json([
            'success' => false,
         ]);
      }
   }

   public function activated($id)
   {
      try {
         
         if(!Teacher::where('id', $id)->first())
         {
            return response()->json([
               'message' => 'Teacher with id ' . $id . ' not found!!!',
            ], 404);
         }

         Teacher::where('id', $id)->update([
            'is_active' => true,
         ]);

         return response()->json([
            'success' => true,
         ]);

      } catch (Exception $err) {
         //throw $th;
         return response()->json([
            'success' => false,
         ]);
      }
   }

   public function delete($id)
   {
      try {
         
         Teacher::where('id', $id)->delete();
         // Hapus data terkait (Teacher_grade, Teacher_subject)
         Teacher_grade::where('teacher_id', $id)->delete();
         Teacher_subject::where('teacher_id', $id)->delete();

         session()->flash('after_delete_teacher');

         return redirect('/superadmin/teachers')->with('success', 'Data guru berhasil dihapus.');
      } catch (\Exception $e) {
         return redirect('/superadmin/teachers')->with('error', 'Terjadi kesalahan saat menghapus data guru.');
      }
   }

   public function deleteGradeSubject($teacherId, $gradeId, $subjectId)
   {
      try {
         Teacher_grade::where('teacher_id', $teacherId)->where('grade_id', $gradeId)->delete();
         Teacher_subject::where('teacher_id', $teacherId)->where('grade_id', $gradeId)->delete();

         return redirect('/superadmin/teachers')->with('success', 'Data guru berhasil dihapus.');
      } catch (\Exception $e) {
         return redirect('/superadmin/teachers')->with('error', 'Terjadi kesalahan saat menghapus data guru.');
      }
   }
}