<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher_subject;
use App\Models\Grade_subject;
use App\Models\Subject_exam;
use App\Models\Exam;
use App\Models\Grade_exam;
use App\Models\Score;
use App\Models\Minor_subject;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MinorSubjectController extends Controller
{
    public function index()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'subjects',
            'child' => 'database minor subjects',
            ]);

            $data = Minor_subject::with(['subject'])->get();
            
            return view('components.minorSubject.data-minorSubject')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'subjects',
                'child' => 'database minor subjects',
            ]);

            $data = Subject::get();

            return view('components.minorSubject.create-minorSubject')->with('data', $data);
            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {

        DB::beginTransaction();

        try {
            $role = session('role');

            foreach($request->minor_subject as $ns){
                $post = [
                    'subject_id' => $ns,
                    'created_at'   => now(),
                ];
                
                Minor_subject::create($post);
            }

            session()->flash('after_create_minorSubject');

            DB::commit();
            
            return redirect('/'.  $role .'/minorSubjects');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }

    public function delete($id)
    {
        try {

            session()->flash('after_delete_subject');

            $getIdExam = Subject_exam::where('subject_id',$id)->value('id');

            Subject::where('id', $id)->delete();
            Teacher_subject::where('subject_id', $id)->delete();
            Grade_subject::where('subject_id', $id)->delete();
            Subject_exam::where('subject_id',$id)->delete();

        if($getIdExam != null)
        {
            Exam::where('id', $getIdExam)->delete();
            Grade_exam::where('exam_id', $getIdExam)->delete();
            Score::where('exam_id', $getIdExam)->delete();
        }

            return redirect('/superadmin/minorSubjects');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/superadmin/minorSubjects')->with('error', 'Terjadi kesalahan saat menghapus data minor ssubject.');
        }
    }
}
