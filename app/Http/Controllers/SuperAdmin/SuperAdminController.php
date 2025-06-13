<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Roles;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Relationship;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Exception;

class SuperAdminController extends Controller
{
   public function index()
   {

      session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
      try {
         //code...
         session()->flash('preloader', false);
         return view('components.dashboard');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }

   public function getUser()
   {
      try {
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         //code...
         $user = Auth::user();

         // $st = Student::where('is_active', true)->get();
         // foreach($st as $student){
         //    User::create([
         //       'username' => $student->unique_id,
         //       'password' => $student->unique_id,
         //       'role_id' => 4,
         //    ]);
         // }

         // $pr = Relationship::leftJoin('student_relations', 'student_relations.relation_id', '=', 'relationships.id')
         //    ->leftJoin('students', 'students.id', '=', 'student_relations.student_id')
         //    ->where('students.is_active', true)
         //    ->select('relationships.*')
         //    ->get();

         // dd($pr);

         // foreach($st as $student){
         //    $name = $student->unique_id;
         //    $check = User::where('username', $name)->first();
         //    if($check){
         //       continue;
         //    }
         //    else{
         //       $userStudent = User::create([
         //          'username' => $name,
         //          'password' => $name,
         //          'role_id' => 4,
         //       ]);
         //    }
         //    Student::where('id', $student->id)->update(['user_id' => $userStudent->id]);
         // }
         
         // foreach($pr as $parent){
         //    $name = strtolower(explode(' ', trim($parent->name))[0]);
         //    $password = str_replace('/', '', trim($parent->date_birth));
         //    $check = User::where('username', $name)->first();
         //    if($check){
         //       continue;
         //    }
         //    else{
         //       $userParent = User::create([
         //          'username' => "{$name}",
         //          'password' => "{$password}",
         //          'role_id' => 5,
         //       ]);
         //    }
         //    Relationship::where('id', $parent->id)->update(['user_id' => $userParent->id]);
         // }

         $data = User::with(['role'])
            ->when(request('role_id') == 3, fn($q) => $q->with('teacher'))
            // ->when(request('role_id') == 4, fn($q) => $q->with(['student.grade' => function ($query) {
            //    $query->select('id', 'name', 'class'); // Ambil hanya name dan class
            // }]))
            // ->when(request('role_id') == 5, fn($q) => $q->with(['relationship.student.grade' => function ($query) {
            //    $query->select('id', 'name', 'class'); // Ambil hanya name dan class dari student
            // }]))
            ->take(10)->paginate(20);

         // Looping untuk menggabungkan name dan class menjadi satu
         $data->getCollection()->transform(function ($user) {
            // if ($user->role_id == 4 && isset($user->student->grade)) {
            //    $user->student->full_grade = $user->student->grade->name . ' - ' . $user->student->grade->class;
            // }
            // if ($user->role_id == 5 && isset($user->relationship->student->grade)) {
            //    $user->relationship->student->full_grade = $user->relationship->student->grade->name . ' - ' . $user->relationship->student->grade->class;
            // }
            return $user;
         });

         // dd($data);

         $students = User::with(['role'])->where('role_id', 4)->take(15)->get();
         // $parents = User::with(['role', 'relationship.student'])
         // ->where('role_id', 5)
         // ->orderBy('username', 'asc')
         // ->get()
         // ->take(10);

         // dd($parents);
         // dd($data);

         return view('components.super.data-user', [
            "data" => $data,
            "students" => $students,
            // "parents" => $parents,
         ]);
      
      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function getById($id)
   {
      try {
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         //code...
         $user = User::where('id', $id)->first();
         
         return $user;  

      } catch (Exception $th) {
         return dd($th);
      }
   }


   public function changePassword(Request $request, $id)
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         $rules = $request->only('password', 'reinputPassword');
         
         $validator = Validator::make($rules, [
            'password' => 'required|min:5', 
            // 'reinputPassword' => 'required|min:5',
         ]);

         // if($request->password !== $request->reinputPassword)
         // {
         //    session()->flash('password.success', false);
         //    session()->flash('error.type.password', 'Make sure your input password is the same !!!');
         //    session()->flash('error.password', false);
            
         //    return redirect('/superadmin/users');
         // }
         
         if($validator->fails())
         {
            session()->flash('password.success', false);
            session()->flash('error.type.password', false);
            session()->flash('error.password', $validator->messages());
            
            return redirect('/superadmin/users');
         }
         
         User::where('id', $id)->update([
            'password' => Hash::make($request->password),
         ]);
         
         session()->flash('password.success');
         session('error.type.password', false);
         session('error.password', false);

         if(session('role') !== 'superadmin'){
            return redirect(session('role') . '/dashboard');
         }
         else{
            return redirect('/superadmin/users');
         }
      } catch (Exception $th) {
         //throw $th;
         return dd($th);
      }
   }


   public function registerUser()
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);

         $dataRole = Roles::select('id', 'name')->get()->toArray();
         $dataTeacher = Teacher::select('id', 'name', 'user_id')->get()->toArray();
         $dataStudent = Student::leftJoin('grades', 'grades.id', '=', 'students.grade_id')
         ->select('students.*',
            DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name"))
            ->where('is_active', true)->get()->sortBy('grade_id');
         
         $dataParent = Relationship::leftJoin('student_relations', 'student_relations.relation_id', '=', 'relationships.id')
            ->leftJoin('students', 'students.id', '=', 'student_relations.student_id')
            ->where('students.is_active', true)
            ->orderBy('name', 'asc')
            ->select('relationships.*')
            ->get();

         // dd($dataParent);


         // $dataParent = Relationship::leftJoin('student_relations', 'student_relations.relation_id', '=', 'relationships.id')
         // ->leftJoin('students', 'students.id', '=', 'student_relations.student_id')
         // ->where('students.is_active', true)
         // ->select('relationships.*')
         // ->first();

         // dd($dataStudent);

         $data = [
            'dataRole' => $dataRole,
            'dataTeacher' => $dataTeacher,
            'dataStudent' => $dataStudent,
            'dataParent' => $dataParent,
         ];

         // dd($data);
         
         return view('components.super.register-user')->with('data', $data);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function registerUserAction(Request $request)
   {
      try {
         //code...
         session()->flash('preloader');
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);

         $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'role_id' => $request->role,
            'created_at' => now(),
         ];
            
         // dd($credentials);

         $validator = Validator::make($credentials, [
            'username' => 'required|unique:users|string',
            'password' => 'required|min:5|string',
         ]);
         
         if($validator->fails())
         {
            if (session('role') ==  'superadmin') {
               return redirect('/superadmin/users/register-user')->withErrors($validator->messages())->withInput($credentials);
            }
            elseif (session('role') == 'admin') {
               return redirect('/admin/users/register-user')->withErrors($validator->messages())->withInput($credentials);
            }
         }

         if($request->password !== $request->reinputPassword)
         {
            $error = [
               'password' => ['password does not match'],
               'reinputPassword' => ['password does not match']
            ];
            return redirect('/superadmin/users/register-user')->withErrors($error)->withInput($credentials);
         }

         User::create($credentials);
         $idLastUser = DB::table('users')->latest('id')->first()->id;
         // dd($idLastUser);

         if($request->teacher != null){
            Teacher::where('id', $request->teacher)->update(['user_id' => $idLastUser]);
         }else if($request->student != null){
            Student::where('id', $request->student)->update(['user_id' => $idLastUser]);
         }else if($request->parent != null){
            Relationship::where('id', $request->parent)->update(['user_id' => $idLastUser]);
         }
         
         session()->flash('register.success');
         if (session('role') == 'superadmin') {
            return redirect('/superadmin/users');
         }
         elseif (session('role') == 'admin') {
            return redirect('/admin/users');
         }
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function deleteUser($id)
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);

         if(!User::where('id', $id)->first())
         {
            return response()->json(['errors' => 'user id not found!']);
         }

         Teacher::where('user_id', $id)->update(['user_id' => null]);
         User::where('id', $id)->delete();

         return response()->json(['status' => 201, 
         'response' => 'Delete user success'
      ]);
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function changePhone(Request $request){
      User::where('role_id', '=', '2')->update([
         'phone' => $request->phone,
      ]);

      return redirect()->back();
   }

   public function changeDataAdmin(Request $request){
      User::where('role_id', '=', '2')->update([
         'name' => $request->name,
      ]);

      return redirect()->back();
   }
}