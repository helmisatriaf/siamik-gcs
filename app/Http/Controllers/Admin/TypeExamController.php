<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type_exam;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TypeExamController extends Controller
{
    public function index()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'type exams',
            'child' => 'database type exams',
            ]);

            $data = Type_exam::orderBy('name')->get();

            // dd($data);
            return view('components.typeExam.data-typeExam')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'type exams',
            'child' => 'database type exams',
            ]);
            return view('components.typeExam.create-typeExam');
            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {

        DB::beginTransaction();

        try {

            $rules = [
                'name' => $request->name,
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string',
                ],
            );

            $role = session('role');
            
            if($validator->fails())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/typeExams/create')->withErrors($validator->messages())->withInput($rules);
            }
            
            if(Type_exam::where('name', $request->name)->first())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/typeExams/create')->withErrors([
                    'name' => 'Type Exam ' . $request->name .  ' is has been created ',
                ])->withInput($rules);
            }
                
            $post = [
                'name' => $request->name,
                'created_at' => now(),
            ];

            session()->flash('after_create_typeExam');

            Type_exam::create($post);

            DB::commit();
            
            return redirect('/'.  $role .'/typeExams');

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
                'page' => 'type exams',
                'child' => 'database type exams',
            ]);
            
            $data = Type_exam::where('id', $id)->first();
            
            return view('components.typeExam.edit-typeExam')->with('data', $data);
            
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
                'page' => 'type exams',
                'child' => 'database type exams',
            ]);

            $rules = [
                'name'       => $request->name,
                'updated_at' => now(),
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string',
                ]
            );

            $role = session('role');

            if($validator->fails())
            {
                DB::rollBack();
                return redirect('/'.$role.'/typeExams/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
            }
            
            $check = Type_exam::where('name', $request->name)->first();

            if($check && $check->id != $id)
            {
                DB::rollBack();
                return redirect('/'.$role.'/typeExams/edit/' . $id)->withErrors(['name' => ["The type exam " . $request->name  ." is already created !!!"]])->withInput($rules);
            }

            Type_exam::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_typeExam');

            return redirect('/'.$role.'/typeExams');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete($id)
    {
        try {
            session()->flash('after_delete_type_exam');

            Type_exam::where('id',$id)->delete();

            return redirect('/'.session('role').'/typeExams');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/'.session('role').'/typeExams')->with('error', 'Terjadi kesalahan saat menghapus type exam.');
        }
    }
}
