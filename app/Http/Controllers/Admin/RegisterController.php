<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DemoMail;
use App\Mail\SppMail;
use App\Models\Bill;
use App\Models\Brother_or_sister;
use App\Models\Grade;
use App\Models\Installment_Paket;
use App\Models\InstallmentPaket;
use App\Models\Payment_grade;
use App\Models\Payment_student;
use App\Models\Relationship;
use App\Models\Student;
use App\Models\Student_relationship;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
   public function index()
   {
      try {
         //code...
         $grade = Grade::orderBy('id', 'asc')->get();
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'register students',
         ]);
         // return $grade;
         return view('components.register', ['grade'=> $grade]);
      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function register(Request $request)
   {
      try {
         
         //code...
         
         session()->flash('preloader', true);
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'register students',
         ]);

         $var = Student::orderBy('id', 'desc')->first();
         $unique_id = '';
         
         if( $var && date('Ym') == substr($var->unique_id, 0, 6))
         {
            $unique_id = (string)date('Ym') . str_pad(ltrim(substr($var->unique_id, 7) + 1, '0'), 4, '0', STR_PAD_LEFT);
         } else {
            $unique_id = (string)date('Ym') . str_pad('1', 4, '0', STR_PAD_LEFT);
         } 


         $credentials = [
            'is_active' => 1,
            'unique_id' => $unique_id,
            'user_id' => $request->user_id,
            'name' => $request->studentName,
            'grade_id' => (int)$request->gradeId,
            'gender' => $request->studentGender,
            'religion' => $request->studentReligion,
            'nisn' => $request->nisn,
            'place_birth' => $request->studentPlace_birth,
            'date_birth' => $request->studentDate_birth ? $this->changeDateFormat($request->studentDate_birth) : null,
            'id_or_passport' => $request->studentId_or_passport,
            'nationality' => $request->studentNationality,
            'place_of_issue' => $request->studentPlace_of_issue,
            'date_exp' => $request->studentDate_exp ? $this->changeDateFormat($request->studentDate_exp) : null,
            'is_graduate' => false,
            'created_at' => $request->created_at ? date('Y-m-d H:i:s', strtotime($this->changeDateFormat($request->created_at))) : date('Y-m-d H:i:s'),
         ];

         // dd($credentials);
         
         $rules = [
            'name' => $request->studentName,
            'grade_id' => $request->gradeId,
            'gender' => $request->studentGender,
            'religion' => $request->studentReligion,
            'nisn' => $request->nisn,
            'place_birth' => $request->studentPlace_birth,
            'date_birth' => $request->studentDate_birth ? $this->changeDateFormat($request->studentDate_birth) : null,
            'id_or_passport' => $request->studentId_or_passport,
            'nationality' => $request->studentNationality,
            'place_of_issue' => $request->studentPlace_of_issue,
            'date_exp' => $request->studentDate_exp !== '' ? $this->changeDateFormat($request->studentDate_exp) : null,
            'created_at' => $request->created_at ? date('Y-m-d H:i:s', strtotime($this->changeDateFormat($request->created_at))) : date('Y-m-d H:i:s'),
            // Father rules
            'father_relation' => 'father',
            'father_name' => $request->fatherName,
            'father_religion' => $request->fatherReligion,
            'father_place_birth' => $request->fatherPlace_birth,
            'father_date_birth' => $request->fatherBirth_date ? $this->changeDateFormat($request->fatherBirth_date) : null,
            'father_id_or_passport' => $request->fatherId_or_passport,
            'father_nationality' => $request->fatherNationality,
            'father_occupation' => $request->fatherOccupation,
            'father_company_name' => $request->fatherCompany_name,
            'father_company_address' => $request->fatherCompany_address,
            'father_phone' => $request->fatherCompany_phone,
            'father_home_address' => $request->fatherHome_address,
            'father_telephone' => $request->fatherTelephhone,
            'father_mobilephone' => $request->fatherMobilephone,
            'father_email' => $request->fatherEmail,
            // Mother rules
            'mother_relation' => 'mother',
            'mother_name' => $request->motherName,
            'mother_religion' => $request->motherReligion,
            'mother_place_birth' => $request->motherPlace_birth,
            'mother_date_birth' => $request->motherBirth_date ? $this->changeDateFormat($request->motherBirth_date) : null,
            'mother_id_or_passport' => $request->motherId_or_passport,
            'mother_nationality' => $request->motherNationality,
            'mother_occupation' => $request->motherOccupation,
            'mother_company_name' => $request->motherCompany_name,
            'mother_company_address' => $request->motherCompany_address,
            'mother_phone' => $request->motherCompany_phone,
            'mother_home_address' => $request->motherHome_address,
            'mother_telephone' => $request->motherTelephhone,
            'mother_mobilephone' => $request->motherMobilephone,
            'mother_email' => $request->motherEmail,
            //brother and sister

            'brotherOrSisterName1' => $request->brotherOrSisterName1, 
            'brotherOrSisterBirth_date1' => $request->brotherOrSisterBirth_date1? $this->changeDateFormat($request->brotherOrSisterBirth_date1) : null,
            'brotherOrSisterGrade1' => $request->brotherOrSisterGrade1,
            'brotherOrSisterName2' => $request->brotherOrSisterName2, 
            'brotherOrSisterBirth_date2' => $request->brotherOrSisterBirth_date2? $this->changeDateFormat($request->brotherOrSisterBirth_date2) : null,
            'brotherOrSisterGrade2' => $request->brotherOrSisterGrade2,
            'brotherOrSisterName3' => $request->brotherOrSisterName3, 
            'brotherOrSisterBirth_date3' => $request->brotherOrSisterBirth_date3? $this->changeDateFormat($request->brotherOrSisterBirth_date3) : null,
            'brotherOrSisterGrade3' => $request->brotherOrSisterGrade3,
            'brotherOrSisterName4' => $request->brotherOrSisterName4, 
            'brotherOrSisterBirth_date4' => $request->brotherOrSisterBirth_date4? $this->changeDateFormat($request->brotherOrSisterBirth_date4) : null,
            'brotherOrSisterGrade4' => $request->brotherOrSisterGrade4,
            'brotherOrSisterName5' => $request->brotherOrSisterName5, 
            'brotherOrSisterBirth_date5' => $request->brotherOrSisterBirth_date5? $this->changeDateFormat($request->brotherOrSisterBirth_date5) : null,
            'brotherOrSisterGrade5' => $request->brotherOrSisterGrade5,

         ];     
         
         // return $rules;
         
         $validator = Validator::make($rules, [
            'name' => 'string|required|min:3',
            'grade_id' => 'integer|required',
            'gender' => 'string|required',
            'religion' => 'string|required',
            'nisn' => 'string|nullable|min:7|max:12|unique:students',
            'place_birth' => 'string|required',
            'date_birth' => 'date|required',
            'id_or_passport' => 'nullable|string|min:9|max:16|unique:students',
            'nationality' => 'string|required|min:3',
            'place_of_issue' => 'nullable|string',
            'date_exp' => 'nullable|date',
            // father validation 
            'father_name' => 'string|required|min:3',
            'father_religion' => 'string|required',
            'father_place_birth' => 'string|required',
            'father_date_birth' => 'date|required',
            'father_id_or_passport' => 'string|required|min:12|max:16',
            'father_nationality' => 'string|required',
            'father_phone' => 'nullable|string|max:15|min:6',
            'father_home_address' => 'required|string',
            'father_mobilephone' => 'required|string|max:15|min:6',
            'father_telephone' => 'nullable|string|max:15|min:6',
            'father_email' => 'required|string|email',
            //mother validation
            'mother_name' => 'string|required|min:3',
            'mother_religion' => 'string|required',
            'mother_place_birth' => 'string|required',
            'mother_date_birth' => 'date|required',
            'mother_id_or_passport' => 'string|required|min:15|max:16',
            'mother_nationality' => 'string|required',
            'mother_occupation' => 'nullable|string',
            'mother_company_name' => 'nullable|string',
            'mother_company_address' => 'nullable|string',
            'mother_phone' => 'nullable|string|max:15|min:6',
            'mother_home_address' => 'required|string',
            'mother_telephone' => 'nullable|string|max:15|min:6',
            'mother_mobilephone' => 'required|string|max:15|min:6',
            'mother_telephone' => 'nullable|string|max:15|min:6',
            'mother_email' => 'required|string|email',

            'brotherOrSisterName1' => 'nullable|string',
            'brotherOrSisterBirth_date1' => 'nullable|string',
            'brotherOrSisterGrade1' => 'nullable|string',
            'brotherOrSisterName2' =>  'nullable|string',
            'brotherOrSisterBirth_date2'=>'nullable|string',
            'brotherOrSisterGrade2' => 'nullable|string',
            'brotherOrSisterName3' =>  'nullable|string',
            'brotherOrSisterBirth_date3'=>'nullable|string',
            'brotherOrSisterGrade3' => 'nullable|string',
            'brotherOrSisterName4' =>  'nullable|string',
            'brotherOrSisterBirth_date4'=>'nullable|string',
            'brotherOrSisterGrade4' => 'nullable|string',
            'brotherOrSisterName5' =>  'nullable|string',
            'brotherOrSisterBirth_date5'=>'nullable|string',
            'brotherOrSisterGrade5' => 'nullable|string',
         ]);

         
         if($validator->fails())
         {
            // DB::rollBack();
            
            return redirect('/superadmin/register')->withErrors($validator->messages())->withInput($rules);
         }

         $fatherExist = Relationship::where('id_or_passport', $rules['father_id_or_passport'])->first(); 

         if($fatherExist && $fatherExist->relation == 'mother')
         {
            // DB::rollBack();
            return redirect('/superadmin/register')->withErrors([
               'father_id_or_passport' => ['This id or passport has been registered with mother relation.'],
            ])->withInput($rules);
         }

         $motherExist = Relationship::where('id_or_passport', $rules['mother_id_or_passport'])->first(); 

         if($motherExist && $motherExist->relation == 'father')
         {
            // DB::rollBack();
            return redirect('/superadmin/register')->withErrors([
               'mother_id_or_passport' => ['This id or passport has been registered with father relation.'],
            ])->withInput($rules);
         }

         DB::beginTransaction();

         $student = Student::create($credentials);
         $relationship = $this->handleRelationship($request, $student);
         $brotherOrSister = $this->handleBrotherOrSister($request, $student);
         
         // return $relationship;
         if(!$relationship->success){
            DB::rollBack();
            return dd($relationship->error);
         } else if (!$brotherOrSister->success){
            DB::rollBack();
            return dd('error at brother or sisters');
         }

         // return 'post';
         $id = DB::table('students')->latest('id')->value('unique_id');
            
         $student = Student::with(['relationship', 'grade', 'user'])->where('unique_id', $id)->first();
        
         // dd($brotherOrSister);
         $data = (object) [
            'student' => $student,
            'brother_or_sisters' => $brotherOrSister,
            'after_create' => 'Success register student with name ' . $student->name,
         ];
         
         // return $data;
         session()->flash('after_create_student');
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'database students',
         ]);
         
         DB::commit();

         return view('components.student.detailStudent')->with('data', $data);


      } catch (Exception $err) {
         
         DB::rollBack();
         // abort(500);
         return dd($err);
      }
   }


   private function handleRelationship($request, $student)
   {
      
      try {
         //code...

         $credentialsFather = [
            'relation' => 'father',
            'user_id' => null,
            'name' => $request->fatherName,
            'religion' => $request->fatherReligion,
            'place_birth' => $request->fatherPlace_birth,
            'date_birth' => $request->fatherBirth_date ? $this->changeDateFormat($request->fatherBirth_date) : null,
            'id_or_passport' => $request->fatherId_or_passport,
            'nationality' => $request->fatherNationality,
            'occupation' => $request->fatherOccupation,
            'company_name' => $request->fatherCompany_name,
            'company_address' => $request->fatherCompany_address,
            'phone' => $request->fatherCompany_phone,
            'home_address' => $request->fatherHome_address,
            'telephone' => $request->fatherTelephhone,
            'mobilephone' => $request->fatherMobilephone,
            'email' => $request->fatherEmail,
         ];


         $credentialsMother = [
            'relation' => 'mother',
            'user_id' => null,
            'name' => $request->motherName,
            'religion' => $request->motherReligion,
            'place_birth' => $request->motherPlace_birth,
            'date_birth' => $request->motherBirth_date ? $this->changeDateFormat($request->motherBirth_date) : null,
            'id_or_passport' => $request->motherId_or_passport,
            'nationality' => $request->motherNationality,
            'occupation' => $request->motherOccupation,
            'company_name' => $request->motherCompany_name,
            'company_address' => $request->motherCompany_address,
            'phone' => $request->motherCompany_phone,
            'home_address' => $request->motherHome_address,
            'telephone' => $request->motherTelephhone,
            'mobilephone' => $request->motherMobilephone,
            'email' => $request->motherEmail,
         ];


         $checkIdFather = Relationship::where('id_or_passport', $credentialsFather['id_or_passport'])->first();
         $checkIdMother = Relationship::where('id_or_passport', $credentialsMother['id_or_passport'])->first();


         $father = $checkIdFather && $checkIdFather->relation == 'father'? $this->updateRelation($checkIdFather->id, $credentialsFather) : Relationship::create($credentialsFather);
         $mother = $checkIdMother && $checkIdMother->relation == 'mother'? $this->updateRelation($checkIdMother->id, $credentialsMother) : Relationship::create($credentialsMother);

         Student_relationship::create(['student_id' => $student->id,'relationship_id' => $father->id]);
         Student_relationship::create(['student_id' => $student->id,'relationship_id' => $mother->id]);

         return (object)['success' => true, 'dataRelation' => (object)['father' => $father, 'mother' => $mother]];

      } catch (Exception $err) {
         
         return (object) ['success' => false, 'error' => $err];
      }
   }

   private function handleBrotherOrSister($request, $student)
   {
      
      try {
         //code...
         $credentialsBrotherOrSister = [];

         if($request->brotherOrSisterName1 && $request->brotherOrSisterBirth_date1 && $request->brotherOrSisterGrade1)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName1, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date1), 'grade' => $request->brotherOrSisterGrade1, 'student_id' => $student->id]);
         }
         if($request->brotherOrSisterName2 && $request->brotherOrSisterBirth_date2 && $request->brotherOrSisterGrade2)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName2, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date2), 'grade' => $request->brotherOrSisterGrade2, 'student_id' => $student->id]);
         }
         if($request->brotherOrSisterName3 && $request->brotherOrSisterBirth_date3 && $request->brotherOrSisterGrade3)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName3, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date3), 'grade' => $request->brotherOrSisterGrade3, 'student_id' => $student->id]);
         }
         if($request->brotherOrSisterName4 && $request->brotherOrSisterBirth_date4 && $request->brotherOrSisterGrade4)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName4, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date4), 'grade' => $request->brotherOrSisterGrade4, 'student_id' => $student->id]);
         }
         if($request->brotherOrSisterName5 && $request->brotherOrSisterBirth_date5 && $request->brotherOrSisterGrade5)
         {
            array_push($credentialsBrotherOrSister, (array)['name' => $request->brotherOrSisterName5, 'date_birth' => $this->changeDateFormat($request->brotherOrSisterBirth_date5), 'grade' => $request->brotherOrSisterGrade5, 'student_id' => $student->id]);
         }

         Brother_or_sister::insert($credentialsBrotherOrSister);
         
         return (object)['success' => true, 'dataBrotherOrSister' => $credentialsBrotherOrSister];
      } catch (Exception $err) {
         dd($err);
         return (object)['success' => false, 'error' => $err];
      }
   }

   public function changeDateFormat($date)
   {
      try {
         //code...
         $date = explode('/', $date);

                  
         return "$date[2]-$date[1]-$date[0]";
      } catch (Exception) {
         return null;
      }
   }

   public function updateRelation($id, $credential)
   {

      
      try {
         //code...
         
         Relationship::where('id', $id)->update($credential);

         return Relationship::where('id', $id)->first();

      } catch (Exception $err) {
         return dd($err);
      }
   }

}