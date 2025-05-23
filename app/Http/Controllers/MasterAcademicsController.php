<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master_academic;

use App\Exports\DataSchoolExport;
use App\Exports\GradeExport;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class MasterAcademicsController extends Controller
{
    Public function index()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'master academic',
            'child' => 'master academic',
            ]);

            // dd(session()->all());

            $data = Master_academic::where('is_use', TRUE)->first();
            $masterAcademic = Master_academic::get();

            // dd($data);
            return view('components.masterAcademic.data-masterAcademic', [
                'data' => $data,
                'masterAcademic' => $masterAcademic,
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
            'page' => 'master academic',
            'child' => 'master academic',
            ]);
            return view('components.masterAcademic.create-masterAcademic');
            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {
        // dd($request);
        DB::beginTransaction();

        try {              
            $checkMaster = Master_academic::get();

            if(count($checkMaster) == 0){
                $post = [
                    'academic_year' => $request->academic_year,
                    'semester1' => $request->semester1,
                    'end_semester1' => $request->end_semester1,
                    'semester2' => $request->semester2,
                    'end_semester2' => $request->end_semester2,
                    'now_semester' => $request->now_semester,
                    'mid_report_card1' => $request->mid_report_card1,
                    'report_card1' => $request->report_card1,
                    'mid_report_card2' => $request->mid_report_card2,
                    'report_card2' => $request->report_card2,
                    'is_use' => TRUE,
                    'created_at' => now(),
                ];
            }
            else{
                $getLastIsUse = $checkMaster->where('is_use', 1)->value('id');
                
                Master_academic::where('id', $getLastIsUse)
                ->update(['is_use' => FALSE]);

                $post = [
                    'academic_year' => $request->academic_year,
                    'semester1' => $request->semester1,
                    'end_semester1' => $request->end_semester1,
                    'semester2' => $request->semester2,
                    'end_semester2' => $request->end_semester2,
                    'now_semester' => $request->now_semester,
                    'mid_report_card1' => $request->mid_report_card1,
                    'report_card1' => $request->report_card1,
                    'mid_report_card2' => $request->mid_report_card2,
                    'report_card2' => $request->report_card2,
                    'is_use' => TRUE,
                    'created_at' => now(),
                ];
            }
            

            $role = session('role');

            session()->flash('after_create_masterAcademic');

            Master_academic::create($post);

            DB::commit();

            $semester = Master_academic::where('is_use', true)->value('now_semester');
            $academic_year = Master_academic::where('is_use', true)->value('academic_year');

            session()->put([
                'semester' => $semester,
                'academic_year' => $academic_year,
            ]);    
            
            return redirect('/'.  $role .'/masterAcademics');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }
    
    public function pageEdit()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'master academic',
                'child' => 'master academic',
            ]);
            
            $data = Master_academic::where('is_use', TRUE)->first();
            
            return view('components.masterAcademic.edit-masterAcademic')->with('data', $data);
            
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
                'page' => 'master academic',
                'child' => 'master academic',
            ]);

            $rules = [
                'academic_year' => $request->academic_year,
                'semester1' => $request->semester1,
                'end_semester1' => $request->end_semester1,
                'semester2' => $request->semester2,
                'end_semester2' => $request->end_semester2,
                'now_semester' => $request->now_semester,
                'mid_report_card1' => $request->mid_report_card1,
                'report_card1' => $request->report_card1,
                'mid_report_card2' => $request->mid_report_card2,
                'report_card2' => $request->report_card2,    
                'updated_at' => now(),
            ];

            $role = session('role');

            Master_academic::where('id', $id)->update($rules);
    
            DB::commit();

            $semester = Master_academic::where('is_use', TRUE)->value('now_semester');
            $academic_year = Master_academic::where('is_use', TRUE)->value('academic_year');

            session()->put([
                'semester' => $semester,
                'academic_year' => $academic_year,
            ]);    

            session()->flash('after_update_masterAcademic');

            return redirect('/'.$role.'/masterAcademics');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete($id)
    {
        session()->flash('after_delete_typeSchedule');

        Master_academic::where('id', $id)->delete();

        return redirect('/superadmin/masterAcademics');
    }

    public function excel()
    {
        // $grades = ['1', '2', '3','4', '5', '6', '7', '8', '9', '10', '11', '12', '13']; 

        // return Excel::download(new GradeExport($grades), 'grades.xlsx');
    }

    public function changeMasterAcademic($id)
    {
        try {
            // Ambil ID master akademik yang sedang aktif
            $getLastIsUse = Master_academic::where('is_use', true)->value('id');

            // Grade_subject::query()->update(['academic_year' => '2024-2025']);
            // Teacher_grade::query()->update(['academic_year' => '2024-2025']);
            // Teacher_subject::query()->update(['academic_year' => '2024-2025']);
    
            // Nonaktifkan master akademik yang lama
            if ($getLastIsUse) {
                Master_academic::where('id', $getLastIsUse)
                    ->update(['is_use' => false]);
            }
    
            // Aktifkan master akademik baru
            Master_academic::where('id', $id)
                ->update(['is_use' => true]);

            // Query ulang untuk mendapatkan data terbaru
            $activeMaster = Master_academic::where('is_use', true)->first();

            // Pastikan data baru masuk ke session
            session()->put([
                'semester' => $activeMaster->now_semester,
                'academic_year' => $activeMaster->academic_year,
            ]);      
    
            return response()->json(['success' => true]);
        } catch (Exception $err) {
            return response()->json(['success' => false, 'message' => $err->getMessage()], 500);
        }
    }   
}
