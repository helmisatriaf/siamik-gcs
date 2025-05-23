<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Chinese_higher;
use App\Models\Chinese_lower;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChineseLowerController extends Controller
{
    public function index()
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'subjects',
            'child' => 'database chinese lower',
            ]);

            $data = Chinese_lower::leftJoin('students', 'students.id', '=', 'chinese_lowers.student_id')
                ->leftJoin('grades', 'grades.id', '=', 'chinese_lowers.grade_id')
                ->select('students.id as student_id', 'students.name as student_name', 'grades.name as grade_name', 'grades.class as grade_class')
                ->get();

            $data = [
                'data' => $data,
            ];

            // dd($data);
            return view('components.chineseLower.data-chinese-lower')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function addStudent()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'subjects',
            'child' => 'database chinese lower',
            ]);

            $gradeSecondary = Grade::where('name', '=', 'secondary')->pluck('id')->toArray();
            $chineseHigherStudent = Chinese_higher::pluck('student_id')->toArray();
            $chineseLowerStudent = Chinese_lower::pluck('student_id')->toArray();
            
            // dd($chineseHigherStudent);
            if(!empty($chineseHigherStudent) || !empty($chineseLowerStudent)){
                $data = Student::leftJoin('grades', 'grades.id', '=', 'students.grade_id')
                    ->whereIn('students.grade_id', $gradeSecondary)
                    ->whereNotIn('students.id', $chineseHigherStudent)
                    ->whereNotIn('students.id', $chineseLowerStudent)
                    ->select('students.*','grades.name as grade_name', 'grades.class as grade_class')
                    ->orderByRaw('FIELD(grades.class, "1", "2", "3")')
                    ->get();
            }else{
                $data = Student::leftJoin('grades', 'grades.id', '=', 'students.grade_id')
                    ->whereIn('students.grade_id', $gradeSecondary)
                    ->select('students.*','grades.name as grade_name', 'grades.class as grade_class')
                    ->orderByRaw('FIELD(grades.class, "1", "2", "3")')
                    ->get();
            }

            $subject = Subject::where('name_subject', '=', 'chinese lower')->get();
            
            // dd($data);

            return view('components.chineseLower.add-student')->with('data', $data)->with('subject', $subject);
            
        } catch (Exception $err) 
        {
            dd($err);
            return abort(500);
        }
    }

    public function actionPost(Request $request)
    {
        DB::beginTransaction();

        try {
            foreach($request->student_id as $si){
                $grade = Student::where('id', $si)->value('grade_id');
                
                $post = [
                    'subject_id' => $request->subject_id,
                    'grade_id'   => $grade,
                    'student_id' => $si,
                    'created_at' => now(),
                ];
                
                Chinese_lower::create($post);
            }

            session()->flash('after_add_student_chinese_lower');

            DB::commit();
            
            return redirect('/'. session('role') .'/chineseLower');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }


    public function delete($id)
    {
        try {
            Chinese_lower::where('student_id', $id)->delete();
        
            session()->flash('after_delete_student_chinese_lower');
            
            return redirect('/' . session('role') . '/chineseLower');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/' . session('role') . '/chineseLower')->with('error', 'Terjadi kesalahan saat menghapus data siswa');
        }
    }


}
