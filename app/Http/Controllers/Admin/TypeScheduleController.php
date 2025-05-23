<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type_schedule;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TypeScheduleController extends Controller
{
    Public function index()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'database type schedules',
            ]);

            $data = Type_schedule::get();

            // dd($data);
            return view('components.typeSchedule.data-typeSchedule')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'database type schedules',
            ]);
            return view('components.typeSchedule.create-typeSchedule');
            
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
                'color' => $request->color,
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string',
                ],
            );

            $role = session('role');
            
            if($validator->fails())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/typeSchedules/create')->withErrors($validator->messages())->withInput($rules);
            }
            
            if(Type_schedule::where('name', $request->name)->first())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/typeSchedules/create')->withErrors([
                    'name' => 'Type Exam ' . $request->name .  ' is has been created ',
                ])->withInput($rules);
            }

            if(Type_schedule::where('color', $request->color)->first())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/typeSchedules/create')->withErrors([
                    'color' => 'Color' . $request->color .  ' already used ',
                ])->withInput($rules);
            }
                
            $post = [
                'name' => $request->name,
                'color' => $request->color,
                'created_at' => now(),
            ];

            session()->flash('after_create_typeSchedule');

            Type_schedule::create($post);

            DB::commit();
            
            return redirect('/'.  $role .'/typeSchedules');

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
                'page' => 'type schedules',
                'child' => 'database type schedules',
            ]);
            
            $data = Type_schedule::where('id', $id)->first();
            
            // dd($data);
            return view('components.typeSchedule.edit-typeSchedule')->with('data', $data);
            
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
                'page' => 'type schedules',
                'child' => 'database type schedules',
            ]);

            $rules = [
                'name'       => $request->name,
                'color'      => $request->color,
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
                return redirect('/'.$role.'/typeSchedules/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
            }
            
            $check = Type_schedule::where('name', $request->name)->first();

            if($check && $check->id != $id)
            {
                DB::rollBack();
                return redirect('/'.$role.'/typeSchedules/edit/' . $id)->withErrors(['name' => ["The type exam " . $request->name  ." is already created !!!"]])->withInput($rules);
            }

            if(Type_schedule::where('color', $request->color)->exists()){
                DB::rollBack();
                return redirect('/'.$role.'/typeSchedules/edit/' . $id)->withErrors(['color' => ["The type exam color" . $request->color  ." is already used !!!"]])->withInput($rules);
            }

            Type_schedule::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_typeSchedule');

            return redirect('/'.$role.'/typeSchedules');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete($id)
    {
        session()->flash('after_delete_typeSchedule');

        Type_schedule::where('id', $id)->delete();

        return redirect('/'.session('role'). '/typeSchedules');
    }
}
