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
use App\Models\Major_subject;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MajorSubjectController extends Controller
{
    public function index()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'subjects',
                'child' => 'database major subjects',
            ]);

            $data = Major_subject::with(['subject'])->get();
            $subjects = subject::get();
            
            // dd($data);
            return view('components.majorSubject.data-majorSubject', [
                'data' => $data,
                'subjects' => $subjects,
            ]);

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
                'child' => 'database major subjects',
            ]);

            $data = Subject::get();

            return view('components.majorSubject.create-majorSubject')->with('data', $data);
            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {

        DB::beginTransaction();

        try {
            $role = session('role');

            foreach($request->major_subject as $ns){
                $post = [
                    'subject_id' => $ns,
                    'created_at'   => now(),
                ];
                
                Major_subject::create($post);
            }

            session()->flash('after_create_majorSubject');

            DB::commit();
            
            return redirect('/'.  $role .'/majorSubjects');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }

    public function delete($id)
    {
        try {
            Major_subject::where('id', $id)->delete();
            return redirect('/'.session('role').'/majorSubjects');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/'.session('role').'/majorSubjects')->with('error', 'Terjadi kesalahan saat menghapus data subject.');
        }
    }
}
