<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\User;
use App\Models\Relationship;
use App\Models\Roles;

use Exception;

class RelationController extends Controller
{
   public function index(Request $request)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'relations',
         'child' => 'database relations',
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

        $order = $request->sort ? $request->sort : 'desc';
        
        if($request->type && $request->search && $request->order)
        {
            $data = Relationship::where($request->type, 'LIKE', '%' . $request->search . '%')
              ->orderBy($request->order, $order) // Misalnya, $request->order adalah 'created_at' atau 'updated_at'
              ->paginate(15);
        } 
        else if($request->type && $request->search)
        {
            $data = Relationship::where($request->type,'LIKE','%'. $request->search .'%')->paginate(15);
        } 
        else if($request->order) 
        {
            $data = Relationship::get()->orderBy($request->order, $order)->paginate(15);
        } 
        else 
        {
            $data = Relationship::join('student_relationships', 'relationships.id', '=', 'student_relationships.relationship_id')
              ->join('students', 'student_relationships.student_id', '=', 'students.id')
              ->select('relationships.*', 'students.name as student_name')
              ->paginate(15);
        }

        //  dd($data);
        return view('components.relation.data-relation')->with('data', $data)->with('form', $form);

      } catch (Exception $err) {
        return dd($err);
      }
   }

   public function getById($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'relations',
         'child' => 'database relations',
      ]);

      try {

         if(session('role') == 'admin' || session('role') == 'superadmin')
         {
            $dataRelationship = Relationship::join('student_relationships', 'relationships.id', '=', 'student_relationships.relationship_id')
               ->join('students', 'student_relationships.student_id', '=', 'students.id')
               ->join('users', 'users.id', '=', 'relationships.user_id')
               ->where('relationships.id', $id) 
               ->select('relationships.*', 'students.name as student_name', 'users.*')
               ->first();
    
            //  $data = (object) $data->toArray();
    
            $roles = Roles::where('roles.id','=',$dataRelationship->role_id)->select('roles.name')->first();
    
            $data = [
               'dataRelationship' => $dataRelationship,
               'roles' => $roles,
            ];
         }
         elseif(session('role') == 'student')
         {
            $idStudent = Student::where('user_id', $id)->value('id');

            $dataRelationship = Relationship::join('student_relationships', 'relationships.id', '=', 'student_relationships.relationship_id')
               ->join('students', 'student_relationships.student_id', '=', 'students.id')
               ->join('users', 'users.id', '=', 'relationships.user_id')
               ->where('student_relationships.student_id', $idStudent) 
               ->select('relationships.*', 'students.name as student_name', 'users.*')
               ->first();
 
            // dd($dataRelationship);
            if($dataRelationship){
               $roles = Roles::where('roles.id','=',$dataRelationship->role_id)->select('roles.name')->first();
               
               $data = [
                  'dataRelationship' => $dataRelationship,
                  'roles' => $roles,
               ];
            } else {
               $data = null;
            }
         }

         // dd($data);

         return view('components.relation.detail-relation')->with('data', $data);
         // return view('components.teacher.data-teacher')->with('data', $data);
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
         $grade = Grade::all();
         $subject = Subject::all();
         $teacher = Teacher::orderBy('id', 'desc')->get();
         // $idLastUser = DB::table('users')->latest('id')->first()->id;

         // dd($idLastUser);

         $data = (object)[
            'teacher' => $teacher,
            'grade' => $grade,
            'subject' => $subject,
            // 'idLastUser' => $idLastUser+1,
         ];

         return view('components.teacher.register-teacher')->with('data', $data);

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
         // dd($request);

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

         // return $credentials;

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
            return redirect('/admin/teachers/register')->withErrors($validator->messages())->withInput($credentials);
         }

         $data = Teacher::create($credentials);

         $getIdLastTeacher = DB::table('teachers')->latest('id')->value('id');
         
         if(isset($request->grade_taught) && !empty($request->grade_taught)){
            $credentials_teacher_grade = [
               'teacher_id' => $getIdLastTeacher,
               'grade_id' => $request->grade_taught,
               'created_at' => now(),
               'updated_at' => now(),
            ];
            $dataTeacherGrade = Teacher_grade::create($credentials_teacher_grade);
         } 
         else if(isset($request->subject_taught) && !empty($request->subject_taught)){
            $credentials_teacher_subject = [
               'teacher_id' => $getIdLastTeacher,
               'subject_id' => $request->subject_taught,
               'created_at' => now(),
               'updated_at' => now(),
            ];
            $dataTeacherSubject = Teacher_subject::create($credentials_teacher_subject);   
         } 
         else if(isset($request->subject_taught) && isser($request->subject_taught)){
            $credentials_teacher_grade = [
               'teacher_id' => $getIdLastTeacher,
               'grade_id' => $request->grade_taught,
               'created_at' => now(),
               'updated_at' => now(),
            ];
         
            $credentials_teacher_subject = [
               'teacher_id' => $getIdLastTeacher,
               'subject_id' => $request->subject_taught,
               'created_at' => now(),
               'updated_at' => now(),
            ];

            dd($credentials_teacher_grade);
         
            $dataTeacherGrade = Teacher_grade::create($credentials_teacher_grade);
            $dataTeacherSubject = Teacher_subject::create($credentials_teacher_subject);
         } else {
            
         }
          

         session()->flash('after_create_teacher', (object) [
            'name' => $data->name,
         ]);

         if (session('role') == 'superadmin') {
            # code...
            return redirect('/superadmin/teachers/detail/' . $unique_id);
         }
         elseif (session('role') == 'admin') {
            # code...
            return redirect('/admin/teachers/detail/' . $unique_id);
         }
         
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function editPage($id)
   {
      session()->flash('page',  $page = (object)[
         'page' => 'relationship',
         'child' => 'database relationship',
      ]);

      try {

         $getIdRelation = Relationship::where('id', $id)->value('id');

         $role = Relationship::join('users', 'relationships.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('relationships.id', $getIdRelation)
            ->select('users.id','users.username', 'roles.name as role_name')
            ->first();

         $dataRelationship = Relationship::join('users', 'users.id', '=', 'relationships.user_id')
            ->where('relationships.id', $getIdRelation)
            ->select('relationships.*', 'users.username')
            ->first();
        


         $data = [
            'dataRelationship' => $dataRelationship,
            'role' => $role,
         ];

         // dd($data);
         return view('components.relation.edit-relation')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function actionEdit(Request $request, $id)
   {

      session()->flash('page',  $page = (object)[
         'page' => 'relations',
         'child' => 'database relations',
      ]);

      session()->flash('preloader', true);

      try {
         
         $credentials = $request->only(['id', 'name', 'relation', 'place_birth', 'religion', 'date_birth', 'occupation', 'company_name', 'phone', 'company_address', 'home_address', 'telephone', 'mobilephone', 'id_or_passport', 'nationality', 'email']);
         
         $credentials['date_birth'] = $this->handleDate($credentials['date_birth'], 'Y-m-d');

         $validator = Validator::make($credentials, [
            'name' => 'required|min:3|string',
            'relation' => 'required|string',
            'place_birth' => 'required|string',
            'religion' => 'required|string',
            'date_birth' => 'required|date',
            'nationality' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string|max:13|min:9',
            'mobilephone' => 'required|string|max:13|min:9',
            'telephone' => 'required|string|max:13|min:9',
            'home_address' => 'required|string',
         ]);
         
         $checkUniqueEmail = Relationship::where('email', $request->email)->first();

         if($checkUniqueEmail) if($checkUniqueEmail->id != $id) {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/relations/edit/' . $id)->withErrors(['email' => 'email has already been taken.'])->withInput($credentials);
         } 
         
         if($validator->fails())
         {
            $credentials['date_birth'] = $this->handleDate($credentials['date_birth']);
            return redirect('/admin/relations/edit/' . $id)->withErrors($validator->messages())->withInput($credentials);
         }


         // dd($credentials);
         Relationship::where('id', $id)->update($credentials);
         
         session()->flash('after_update_teacher');
         $target = Relationship::where('id', $id)->first();
         
         return redirect('/admin/relations/detail/' . $target->id);
         
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
         //throw $th;
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
}