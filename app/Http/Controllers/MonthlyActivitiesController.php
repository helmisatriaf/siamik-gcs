<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\MonthlyActivity;
use App\Models\Master_academic;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use PDO;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class MonthlyActivitiesController extends Controller
{
    public function index()
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'subjects',
            'child' => 'database monthly activities',
            ]);

            $data = MonthlyActivity::get();
            $monthActive = Master_academic::where('is_use', 1)->first();
            $monthActivity = [];

            if (session('semester') == 1) {
                $startSemester = Carbon::parse($monthActive->semester1);
                $endSemester = Carbon::parse($monthActive->end_semester1);
            } elseif (session('semester') == 2) {
                $startSemester = Carbon::parse($monthActive->semester2);
                $endSemester = Carbon::parse($monthActive->end_semester2);
            }

            $period = \Carbon\CarbonPeriod::create($startSemester->copy()->startOfMonth(), '1 month', $endSemester->copy()->startOfMonth());

            foreach ($period as $date) {
                // Format nama bulan dalam Bahasa Indonesia
                $monthActivity[] = ucfirst($date->translatedFormat('F'));
            }

            return view('components.monthlyActivities.data-monthly-activities', [
                'data' => $data,
                'monthActivity' => $monthActivity,
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
                'child' => 'database monthly activities',
            ]);

            return view('components.monthlyActivities.create-monthly-activities');            
        } catch (Exception) {
            return abort(500);
        }
    }
   
    public function actionPost(Request $request)
    {
        DB::beginTransaction();
        try {

            MonthlyActivity::where('name', session('semester'))
                ->where('academic_year', session('academic_year'))
                ->delete();

            // dd($request->monthly_activities);
            foreach($request->monthly_activities as $ma){
                // dd($ma); 
                MonthlyActivity::create([
                    'name' => $ma['name'],
                    'grades' => $ma['grades'],
                    'month' => $ma['month'],
                    'semester' => session('semester'),
                    'academic_year' => session('academic_year'),
                ]);
            }
            
            session()->flash('after_create_subject');
            DB::commit();
            
            return redirect('/monthlyActivities');

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
                'child' => 'database monthly activities',
            ]);
            
            $data = MonthlyActivity::where('id', $id)->first();
            
            return view('components.subject.edit-subject')->with('data', $data);
            
        } catch (Exception $err) {
            dd($err);
            return abort(404);
        }
    }


    public function actionPut(Request $request)
    {
        DB::beginTransaction();

        try {
            session()->flash('page',  $page = (object)[
                'page' => 'subjects',
                'child' => 'database monthly activities',
            ]);

            $role = session('role');
            MonthlyActivity::where('id', $request->id)->update([
                'name' => $request->change_name,
                'month' => $request->month,
            ]);
    
            DB::commit();
            
            return response()->json([
                'success' => true,
            ]);

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete(Request $request)
    {
        try {
            MonthlyActivity::where('id', $request->id)->delete();
            return response()->json(['success' => true]);
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/'.session('role').'/monthlyActivities')->with('error', 'Terjadi kesalahan saat menghapus data monthly activities.');
        }
    }
}
