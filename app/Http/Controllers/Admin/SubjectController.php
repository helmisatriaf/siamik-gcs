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

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function index()
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'subjects',
            'child' => 'database subjects',
            ]);

            $data = Subject::orderBy('name_subject', 'ASC')->get();

            // dd($data);
            return view('components.subject.data-subject')->with('data', $data);

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
            'child' => 'database subjects',
            ]);
            return view('components.subject.create-subject');
            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {

        DB::beginTransaction();

        try {

            $rules = [
                'name_subject' => $request->name_subject,
            ];

            $validator = Validator::make($rules, [
                'name_subject' => 'required|string',
                ],
            );

            $role = session('role');
            
            if($validator->fails())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/subjects/create')->withErrors($validator->messages())->withInput($rules);
            }
            
            if(Subject::where('name_subject', $request->name_subject)->first())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/subjects/create')->withErrors([
                    'name_subject' => 'Subject ' . $request->name_subject .  ' is has been created ',
                ])->withInput($rules);
            }
                
            $post = [
                'name_subject' => $request->name_subject,
                'created_at'   => now(),
            ];

            session()->flash('after_create_subject');

            Subject::create($post);

            DB::commit();
            
            return redirect('/'.  $role .'/subjects');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }

    public function pageEdit($id)
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'subjects',
                'child' => 'database subjects',
            ]);
            
            $data = Subject::where('id', $id)->first();
            
            return view('components.subject.edit-subject')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
            return abort(404);
        }
    }


    public function actionPut(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            session()->flash('page',  $page = (object)[
                'page' => 'subjects',
                'child' => 'database subjects',
            ]);

            $rules = [
                'name_subject' => $request->name_subject,
                'updated_at'   => now(),
            ];

            $validator = Validator::make($rules, [
                'name_subject' => 'required|string',
                ]
            );

            $role = session('role');

            if($validator->fails())
            {
                DB::rollBack();
                return redirect('/'.$role.'/subjects/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
            }
            
            $check = Subject::where('name_subject', $request->name_subject)->first();

            if($check && $check->id != $id)
            {
                DB::rollBack();
                return redirect('/'.$role.'/subjects/edit/' . $id)->withErrors(['name_subject' => ["The subject " . $request->name_subject  ." is already created !!!"]])->withInput($rules);
            }

            Subject::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_subject');

            return redirect('/'.$role.'/subjects');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
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

            return redirect('/' .session('role'). '/subjects');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/'.session('role').'/subjects')->with('error', 'Terjadi kesalahan saat menghapus data subject.');
        }
    }

    public function changeIcon(Request $request){
        try{
            $subject = Subject::where('id', $request->id)->first();

            $file = $request->file;
            $fileName = $subject->name . '.' . $file->getClientOriginalExtension();
            $filePath = $file->store('icons', 'public');

            $checkFile = Subject::where('id', $request->id)
                ->value('icon');

            if($checkFile !== null){
                if (Storage::exists($checkFile)) {
                Storage::delete('public/' . $checkFile);
                }
            }

            $save =  Subject::where('id', $request->id)->update([
                'icon' => $filePath,
            ]);

            if($save){
                return response()->json(['success' => true, 'message' => 'Successfully change icon']);
            }
        }
        catch(Exception $err){
           return dd($err);
        }
    }
}
