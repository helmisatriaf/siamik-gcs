<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master_schedule_academic;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MasterScheduleController extends Controller
{
    Public function index()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'schedules',
            'child' => 'master schedules',
            ]);

            $data = Master_schedule_academic::get();

            // dd($data);
            return view('components.masterSchedule.data-masterSchedule')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'master schedules',
            'child' => 'master schedules',
            ]);
            return view('components.masterSchedule.create-masterSchedule');
            
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
                'date' => $request->date,
                'end_date' => $request->end_date,
            ];

            $role = session('role');
            
            if(Master_schedule_academic::where('name', $request->name)->first())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/masterSchedules/create')->withErrors([
                    'name' => $request->name .  ' is has been created ',
                ])->withInput($rules);
            }
                
            $post = [
                'name' => $request->name,
                'date' => $request->date,
                'end_date' => $request->end_date,
                'created_at' => now(),
            ];

            session()->flash('after_create_masterSchedule');

            Master_schedule_academic::create($post);

            DB::commit();
            
            return redirect('/'.  $role .'/masterSchedules');

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
                'page' => 'master schedules',
                'child' => 'master schedules',
            ]);
            
            $data = Master_schedule_academic::where('id', $id)->first();
            
            return view('components.masterSchedule.edit-masterSchedule')->with('data', $data);
            
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
                'page' => 'master schedules',
                'child' => 'master schedules',
            ]);

            $rules = [
                'name'       => $request->name,
                'date'       => $request->date,
                'end_date'   => $request->end_date,
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
                return redirect('/'.$role.'/masterSchedules/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
            }
            
            $check = Master_schedule_academic::where('name', $request->name)->first();

            if($check && $check->id != $id)
            {
                DB::rollBack();
                return redirect('/'.$role.'/masterSchedules/edit/' . $id)->withErrors(['name' => ["The master schedule " . $request->name  ." is already created !!!"]])->withInput($rules);
            }

            Master_schedule_academic::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_masterSchedule');

            return redirect('/'.$role.'/masterSchedules');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete($id)
    {
        session()->flash('after_delete_typeSchedule');

        Master_schedule_academic::where('id', $id)->delete();

        return redirect('/superadmin/masterSchedules');
    }
}
