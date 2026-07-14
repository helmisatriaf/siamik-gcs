<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Eca;
use App\Models\Student;
use App\Models\Student_eca;
use App\Models\Master_academic;
use App\Models\Schedule;
use App\Models\Eca_activity;
use App\Models\Student_eca_activity;
use App\Models\AttendanceEca;

use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EcaController extends Controller
{
    public function index()
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'eca',
            'child' => 'database eca',
            ]);

            $data = Eca::get();

            $data = [
                'data' => $data,
            ];

            return view('components.eca.data-eca')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function section()
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'eca',
            'child' => 'Ekstracuricular Academic',
            ]);

            if(session('role') == 'student'){
                $studentId = Student::where('user_id', session('id_user'))->value('id');
                $ecaId = Student_eca::where('student_id', $studentId)->pluck('eca_id')->toArray();

                $data = Eca::whereIn('id', $ecaId)->get();
                
                return view('components.eca.list-eca', [
                    'data' => $data,
                ]);
            }
            elseif(session('role') == 'parent'){
                $studentId = Student::where('id', session('studentId'))->value('id');
                $ecaId = Student_eca::where('student_id', $studentId)->pluck('eca_id')->toArray();
    
                $data = Eca::whereIn('id', $ecaId)->get();
                
                return view('components.eca.list-eca', [
                    'data' => $data,
                ]);
            }
            elseif(session('role') == 'teacher'){
                $data = Eca::get();

                return view('components.eca.list-eca', [
                    'data' => $data,
                ]);
            }
            elseif(session('role') == 'parent'){

            }
            

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function sectionActivity($id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'eca',
                'child' => 'detail course',
            ]);
            $masterAcademic = Master_academic::where('is_use', true)->first(); 

            if(session('semester') == 1){
                $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester1);
                $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester1);
            }
            elseif(session('semester') == 2){
                $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester2);
                $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester2);
            }
            
            // Ambil hari dari database
            $schedule = Schedule::where('subject_id', $id)
                ->value('day');
            
            // Mapping angka ke nama hari dalam format Carbon
            $daysOfWeek = [
                1 => "Monday",
                2 => "Tuesday",
                3 => "Wednesday",
                4 => "Thursday",
                5 => "Friday"
            ];
            
            $day = $daysOfWeek[$schedule] ?? "Monday"; // Default ke Monday jika tidak ditemukan
            
            $course = [];
            $currentDate = $startSemester->copy();
            
            // Cari tanggal pertama yang sesuai dengan hari dari database
            while ($currentDate->format('l') !== $day) {
                $currentDate->addDay();
            }
            
            while ($currentDate <= $endSemester) {
                $month = $currentDate->format('F Y'); // Nama Bulan dan Tahun
                if (!isset($course[$month])) {
                    $course[$month] = []; // Inisialisasi bulan baru
                    $weekNumber = 1; // Reset nomor minggu saat bulan berganti
                }
            
                // Simpan data dengan format yang sesuai
                // $course[$month][] = "Week$weekNumber{$currentDate->format('jFY')}";
                $index = $currentDate->format('dmY');
                $course[$month][$index] = "Week $weekNumber ({$currentDate->format('j F Y')})";
                // Tambah 7 hari ke depan (langsung ke minggu berikutnya)
                $currentDate->addWeek();
            
                $weekNumber++;
            }

            $subject = Eca::where('id', $id)->first();
            $material = Eca_activity::where('eca_id', $id)
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))
                ->count();
            $student = Student_eca::with('student.grade')->where('eca_id', $id)->get();     

            // dd($student);

            return view('components.eca.eca-activity', 
            compact('subject', 'id', 'course', 'material', 'student'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function sectionActivityStudent()
    {
        try {
            session()->flash('page', (object)[
                'page' => 'eca',
                'child' => 'detail course',
            ]);
            $masterAcademic = Master_academic::where('is_use', true)->first(); 

            if(session('semester') == 1){
                $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester1);
                $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester1);
            }
            elseif(session('semester') == 2){
                $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester2);
                $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester2);
            }
            
            // Ambil hari dari database
            $schedule = Schedule::where('subject_id', session('id_course'))
                ->value('day');
            
            // Mapping angka ke nama hari dalam format Carbon
            $daysOfWeek = [
                1 => "Monday",
                2 => "Tuesday",
                3 => "Wednesday",
                4 => "Thursday",
                5 => "Friday"
            ];
            
            $day = $daysOfWeek[$schedule] ?? "Monday"; // Default ke Monday jika tidak ditemukan
            
            $course = [];
            $currentDate = $startSemester->copy();
            
            // Cari tanggal pertama yang sesuai dengan hari dari database
            while ($currentDate->format('l') !== $day) {
                $currentDate->addDay();
            }
            
            while ($currentDate <= $endSemester) {
                $month = $currentDate->format('F Y'); // Nama Bulan dan Tahun
                if (!isset($course[$month])) {
                    $course[$month] = []; // Inisialisasi bulan baru
                    $weekNumber = 1; // Reset nomor minggu saat bulan berganti
                }
            
                // Simpan data dengan format yang sesuai
                // $course[$month][] = "Week$weekNumber{$currentDate->format('jFY')}";
                $index = $currentDate->format('dmY');
                $course[$month][$index] = "Week $weekNumber ({$currentDate->format('j F Y')})";
                // Tambah 7 hari ke depan (langsung ke minggu berikutnya)
                $currentDate->addWeek();
            
                $weekNumber++;
            }

            $subject = Eca::where('id', session('id_course'))->first();
            $material = Eca_activity::where('eca_id', session('id_course'))
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))
                ->count();
            $student = Student_eca::with('student.grade')->where('eca_id', session('id_course'))->get();     

            // dd($student);
            $id = session('id_course');

            return view('components.eca.eca-activity', 
            compact('subject', 'id', 'course', 'material', 'student'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function detailStudent($id)
    {
        try {
            session()->flash('page',  $page = (object)[
            'page' => 'eca',
            'child' => 'database eca',
            ]);

            $eca = Eca::where('id', $id)->get();

            $student = Eca::where('ecas.id', $id)
                ->leftJoin('student_ecas', function($join) {
                    $join->on('student_ecas.eca_id', '=', 'ecas.id');
                })
                ->leftJoin('students', 'students.id', '=', 'student_ecas.student_id')
                ->leftJoin('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.id as student_id', 'students.name as student_name', 'grades.name as grade_name', 'grades.class as grade_class', 
                'ecas.name as eca_name', 'ecas.id as eca_id')
                ->orderBy('grades.id', 'asc')
                ->get();
        
            $data = [
                'student' => $student,
                'eca' => $eca,
            ];

            // dd($data);

            return view('components.eca.detail-eca')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function pageCreate()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'eca',
            'child' => 'database eca',
            ]);
            return view('components.eca.create-eca');
            
        } catch (Exception) {
            return abort(500);
        }
    }

    public function addStudent($id)
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
            'page' => 'eca',
            'child' => 'database eca',
            ]);

            $data = Student::leftJoin('grades', 'grades.id', '=', 'students.grade_id')
                ->select('students.*','grades.name as grade_name', 'grades.class as grade_class')
                ->where('students.is_active', TRUE)
                ->orderBy('grade_id', 'asc')
                ->get();

            $eca = Eca::where('id', $id)->get();
            
            // dd($data);

            return view('components.eca.add-student')->with('data', $data)->with('eca', $eca);
            
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
                return redirect('/'.  $role .'/eca/create')->withErrors($validator->messages())->withInput($rules);
            }
            
            if(Eca::where('name', $request->name)->first())
            {
                DB::rollBack();
                return redirect('/'.  $role .'/eca/create')->withErrors([
                    'name' => 'Eca ' . $request->name .  ' is has been created ',
                ])->withInput($rules);
            }
                
            $post = [
                'name' => $request->name,
                'created_at'   => now(),
            ];

            session()->flash('after_create_eca');

            Eca::create($post);

            DB::commit();
            
            return redirect('/eca');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }

    public function actionAddStudent(Request $request)
    {
        DB::beginTransaction();
        try {

            // CHECK APABILA STUDENT SUDAH MENGAMBIL ECA YANG SAMA
            for ($i=0; $i < count($request->student_id); $i++) { 
                $rules = [
                    'eca_id' => $request->eca,
                    'student_id' => $request->student_id[$i],
                    'created_at' => now(),
                ];

                if(Student_eca::where('eca_id', $request->eca)->where('student_id', $request->student_id[$i])->first())
                {
                    DB::rollBack();
                    return redirect('/'.  session('role') .'/eca/add' . '/' . $request->eca)->withErrors([
                        'student_id' => 'Student ' . $request->student_id[$i] .  ' is has been created ',
                    ])->withInput($rules);
                }
            }

            for ($i=0; $i < count($request->student_id); $i++) { 
                $post = [
                    'eca_id' => $request->eca,
                    'student_id' => $request->student_id[$i],
                    'created_at'   => now(),
                ];

                Student_eca::create($post);
                DB::commit();
            }
            
            session()->flash('after_add_student_eca');
            
            return redirect('/eca/view' . '/' . $request->eca);

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
                'page' => 'eca',
                'child' => 'database eca',
            ]);
            
            $data = Eca::where('id', $id)->first();
            
            return view('components.eca.edit-eca')->with('data', $data);
            
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
                'page' => 'eca',
                'child' => 'database eca',
            ]);

            $rules = [
                'name' => $request->name,
                'updated_at'   => now(),
            ];

            $validator = Validator::make($rules, [
                'name' => 'required|string',
                ]
            );

            $role = session('role');

            if($validator->fails())
            {
                DB::rollBack();
                return redirect('/'.$role.'/eca/edit/' . $id)->withErrors($validator->messages())->withInput($rules);
            }
            
            $check = Eca::where('name', $request->name)->first();

            if($check && $check->id != $id)
            {
                DB::rollBack();
                return redirect('/'.$role.'/eca/edit/' . $id)->withErrors(['name' => ["The eca " . $request->name  ." is already created !!!"]])->withInput($rules);
            }

            Eca::where('id', $id)->update($rules);
    
            DB::commit();

            session()->flash('after_update_eca');

            return redirect('/'.$role.'/eca');

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
            // return abort(500);
        }
    }

    public function delete($id)
    {
        try {

            session()->flash('after_delete_eca');

            Eca::where('id', $id)->delete();

            return redirect('/eca');
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/'.session('role').'/eca')->with('error', 'Terjadi kesalahan saat menghapus data eca.');
        }
    }

    public function deleteStudent($ecaId, $studentId)
    {
        try {
            session()->flash('after_delete_student_eca');

            Student_eca::where('eca_id', $ecaId)
            ->where('student_id', $studentId)
            ->delete();

            return redirect('/eca/view' . '/' . $ecaId);
        } 
        catch (Exception $err) {
            dd($err);
            return redirect('/eca/view' . '/' . $ecaId)->with('error', 'Terjadi kesalahan saat menghapus data student eca.');
        }
    }

    public function changeIcon(Request $request){
        try{
            $subject = Eca::where('id', $request->id)->first();

            $file = $request->file;
            $fileName = $subject->name . '.' . $file->getClientOriginalExtension();
            $filePath = $file->store('icons', 'public');

            $checkFile = Eca::where('id', $request->id)
                ->value('icon');

            if($checkFile !== null){
                if (Storage::exists($checkFile)) {
                Storage::delete('public/' . $checkFile);
                }
            }

            $save =  Eca::where('id', $request->id)->update([
                'icon' => $filePath,
            ]);

            if($save){
                return response()->json(['success' => true, 'message' => 'Successfully change icon']);
            }
        }
        catch(Exception $err){
            Log::error('error : ' . $err);
            return dd($err);
        }
    }

    public function storeActivity(Request $request, $id){
        // File PDF
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('eca_activities', 'public');
            $embed = false;
        }

        $activity = [
            'section_id' => $request->section_id,
            'title' => $request->title,
            'description' => $request->description,
            'eca_id' => $request->eca_id,
            'file_path' => $filePath,
            'semester' => session('semester'),
            'academic_year' => session('academic_year'),
        ];

        Eca_activity::create($activity);

        session()->flash('success_add_activity');
        return redirect()->back();
    }   

    public function deleteActivity(Request $request)
    {
        try {

            $checkFile = Eca_activity::where('id', $request->id)->value('file_path');
            $delete = Eca_activity::Where('id', $request->id)->delete();

            if($delete == true){
                if($checkFile !== null){
                    if (Storage::exists($checkFile)) {
                        Storage::delete('public/' . $checkFile);
                    }
                }

                session()->flash('success_delete_activity');
                return redirect()->back();
            }
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function storeActivityStudent(Request $request){
        // File PDF
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('eca_activities', 'public');
        }

        $activity = [
            'section_id' => $request->section_id,
            'eca_id' => $request->eca_id,
            'student_id' => Student::where('user_id', session('id_user'))->value('id'),
            'description' => $request->description,
            'eca_id' => $request->eca_id,
            'file_path' => $filePath,
            'semester' => session('semester'),
            'academic_year' => session('academic_year'),
        ];

        Student_eca_activity::create($activity);

        session()->flash('success_add_activity');
        return redirect()->back();
    }   

    public function deleteActivityStudent(Request $request, $id)
    {
        try {
            $checkFile = Student_eca_activity::where('id', $request->id)->value('file_path');
            $delete = Student_eca_activity::Where('id', $request->id)->delete();

            if($delete == true){
                if($checkFile !== null){
                    if (Storage::exists($checkFile)) {
                        Storage::delete('public/' . $checkFile);
                    }
                }

                session()->flash('success_delete_activity');
                return redirect()->back();
            }
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function postAttendance(Request $request)
    {
        try {
            foreach($request->status as $studentId => $status) {

                // Skip if no status is selected
                if(empty($status)) continue;
    
                // Initialize attendance array
                $attend = [
                    'section_id'  => $request->section_id,
                    'eca_id'      => $request->eca_id,
                    'student_id'  => $studentId,
                    'present'     => $status === 'present' ? 1 : 0,
                    'alpha'       => $status === 'alpha' ? 1 : 0,
                    'permission'  => $status === 'permission' ? 1 : 0,
                    'information' => $request->comment[$studentId] ?? '',
                    'semester'    => session('semester'),
                    'academic_year' => session('academic_year'),
                ];
    
                // Save the attendance to the database
                AttendanceEca::create($attend);
            }
            
            session()->flash('success_post_attendance');

            return redirect()->back();

        } catch (Exception $err) {
            DB::rollBack();
            return dd($err);
        }
    }
}
