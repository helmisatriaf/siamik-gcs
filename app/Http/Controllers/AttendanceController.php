<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Grade_subject;
use App\Models\Grade_exam;
use App\Models\Teacher_grade;
use App\Models\Teacher_subject;
use App\Models\Attendance;
use App\Models\Score_attendance_status;
use App\Models\Master_academic;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance',
            ]);
            
            $data  = Grade::with(['student', 'subject'])
                ->join('teacher_grades', 'teacher_grades.grade_id', '=', 'grades.id')
                ->leftJoin('teachers', function ($join) {
                    $join->on('teachers.id', '=', 'teacher_grades.teacher_id');
                })
                ->select('grades.id as id', 'grades.name as grade_name', 'grades.class as grade_class',
                'teachers.name as teacher_class')
                ->withCount(['student as active_student_count' => function ($query) {
                    $query->where('is_active', true); // Hitung hanya siswa aktif
                }, 'subject as active_subject_count'])
                ->orderBy('grades.id', 'asc')
                ->get();

            return view('components.attendance.all-attendance')->with('data', $data);
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function detailAttendance(Request $request){
        try {
            $id = $request->id;
            $gradeId = $request->gradeId;

            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance',
            ]);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();
            
            $grade = Grade::where('id', $gradeId)
                ->select('grades.name as grade_name','grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $semester = session('semester');
            $academic_year = session('academic_year');

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $gradeId)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->orderBy('students.name', 'asc')
                ->get();

            // dd($results);

            $totalAttendances = [
                'total' => $results->count(),
                'dates' => $results->pluck('date')->unique()->values()->all(),
                'datesByMonth' => $results->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->date)->format('F');
                })->map(function($group) {
                    return $group->pluck('date')->unique()->values()->all();
                })
            ];

            // dd($totalAttendances);

            $attendancesByStudent = $results->groupBy('student_id')->map(function($attendances) {
                $student = $attendances->first();
                $present = $attendances->where('present', 1)->count() + $attendances->where('late', 1)->count();
                $totalPresent = $attendances->where('present', 1)->count();
                $totalSick = $attendances->where('sick', 1)->count();
                $totalAlpha = $attendances->where('alpha', 1)->count();
                $totalLate = $attendances->where('late', 1)->count();
                $totalPermission = $attendances->where('permission', 1)->count();
                $totalNonPresent = $attendances->where('present', 0)->count();
                $effectiveDays = ($totalPresent + $totalAlpha + $totalSick + $totalLate + $totalPermission);
            
                // Calculate the score ensuring effectiveDays is not zero to avoid division by zero
                if ($effectiveDays > 0) {
                    $totalScore = 
                        ($totalPresent * 100) +    // Hadir dihitung 100 per present
                        ($totalSick * 100) + 
                        ($totalLate * 75) +        // Late dihitung 75 per keterlambatan
                        ($totalAlpha * 0) +        // Alpha dihitung 0
                        ($totalPermission * 0);    // Permission dihitung 0
                    
                    // Hitung rata-rata skor berdasarkan effective days
                    $score = round($totalScore / $effectiveDays);
                } else {
                    $score = 0;
                }
            
                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'total_present' => $totalPresent,
                    'present' => $present,
                    'total_alpha' => $totalAlpha,
                    'total_sick' => $totalSick,
                    'total_late' => $totalLate,
                    'total_pe' => $totalPermission,
                    'total_non_present' => $totalNonPresent,
                    'score' => $score,
                    'effective' => $effectiveDays,
            
                    'attendances' => $attendances->map(function ($attend) {
                        return [
                            'attendances_id' => $attend->id,
                            'attendances_date' => $attend->date,
                            'attendances_present' => $attend->present,
                            'attendances_alpha' => $attend->alpha,
                            'attendances_sick' => $attend->sick,
                            'attendances_late' => $attend->late,
                            'attendances_latest' => $attend->latest,
                            'attendances_permission' => $attend->permission,
                            'attendances_information' => $attend->information,
                        ];
                    })
                ];
            })->values()->all();

                $status = Score_attendance_status::where('grade_id', $grade->grade_id)
                    ->where('semester', $semester)
                    ->where('academic_year', $academic_year)
                    ->where('class_teacher_id', $classTeacher->teacher_id)
                    ->first();

                if (count($attendancesByStudent) > 0) {
                    $data = [
                        'classTeacher' => $classTeacher,
                        'semester' => $semester,
                        'students' => $attendancesByStudent,
                        'attendancesByMonth' => $attendancesByStudent,
                        'grade' => $grade,
                        'totalAttendances' => $totalAttendances,
                        'status' => $status,
                    ];
    
                    return view('components.attendance.detail')->with('data', $data);            
                }
                else {
                    session()->flash('data_is_empty');
                    return redirect()->back();
                }
                
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function subject($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance',
            ]);

            $subject = Grade_subject::join('subjects', 'subjects.id', '=', 'grade_subjects.subject_id')
                ->leftJoin('teacher_subjects', function ($join) {
                    $join->on('teacher_subjects.subject_id', '=', 'grade_subjects.subject_id')
                        ->on('teacher_subjects.grade_id', '=', 'grade_subjects.grade_id');
                })
                ->leftJoin('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->where('grade_subjects.grade_id', $gradeId)
                ->select(
                    'subjects.id as subject_id',
                    'teachers.id as teacher_id',
                    'subjects.name_subject as subject_name',
                    'teachers.name as teacher_name',
                )
                ->get();

            $grade = grade::where('id', $gradeId)->get();


            $data = [
                'grade' => $grade,
                'subject' => $subject,
            ];

            return view('components.attendance.subject')->with('data', $data);
            // dd($data);
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function detailAttend($gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => ' attendances',
            ]);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $subject = Subject::where('id', $subjectId)
                ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                ->first();
            
            $grade = Grade::where('id', $gradeId)
                ->select('grades.name as grade_name','grades.class as grade_class', 'grades.id as grade_id')
                ->first();


            $semester = session('semester');
            $academic_year = session('academic_year');

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $gradeId)
                ->where('attendances.subject_id', $subjectId)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->orderBy('students.name', 'asc')
                ->get();

            $totalAttendances = [
                'total' => $results->count(),
                'dates' => $results->pluck('date')->unique()->values()->all()
            ];

            $attendancesByStudent = $results->groupBy('student_id')->map(function($attendances){
                $student = $attendances->first();
                $totalPresent = $attendances->where('present', 1)->count();
                $totalNonPresent = $attendances->where('present', 0)->count();

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'total_present' => $totalPresent,
                    'total_non_present' => $totalNonPresent,
                    'attendances' => $attendances->map(function ($attend) {
                        return [
                            'attendances_id' => $attend->id,
                            'attendances_date' => $attend->date,
                            'attendances_present' => $attend->present,
                            'attendances_alpha' => $attend->alpha,
                            'attendances_sick' => $attend->sick,
                            'attendances_permission' => $attend->permission,
                            'attendances_information' => $attend->information,
                        ];
                    })
                ];
            })->values()->all();

            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'semester' => $semester,
                'students' => $attendancesByStudent,
                'grade' => $grade,
                'totalAttendances' => $totalAttendances,
            ];

            // dd($data);
            return view('components.attendance.detail')->with('data', $data);
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function pageDetail($gradeId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance',
            ]);

            $gradeStudent = Student::where('grade_id', $gradeId)->where('is_active', true)->get();

        } catch (Exception $err) {
            dd($err);
        }
    }

    // FOR TEACHER 
    public function attendTeacher($id)
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance',
            ]);

            $getIdTeacher = Teacher::where('user_id', $id)->value('id');

            $gradeTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
                ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->select('grades.*',)
                ->get();

                foreach ($gradeTeacher as $gt) {
                    $gt->students = Student::where('grade_id', $gt->id)->where('is_active', 1)->get();
                    $gt->countStudent = count($gt->students);
                }

            $subjectTeacher = Teacher_subject::where('teacher_id', $getIdTeacher)
                ->join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                ->get();
            
            $data = [
                'gradeTeacher' => $gradeTeacher,
                'subjectTeacher' => $subjectTeacher,
            ];

            //  dd($data);

            return view('components.attendance.data-attendance')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function gradeTeacher()
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance class teacher',
            ]);

            $getIdTeacher = Teacher::where('user_id', session('id_user'))->value('id');

            $gradeTeacher = Teacher_grade::where('teacher_id', $getIdTeacher)
                ->join('grades', 'grades.id', '=', 'teacher_grades.grade_id')
                ->select('grades.*',)
                ->orderBy('grades.id', 'asc')
                ->where('academic_year', session('academic_year'))
                ->get();

            $gradeId = Teacher_grade::where('teacher_id', $getIdTeacher)->value('grade_id');

            $subjectTeacher = Grade_subject::where('grade_id', $gradeId)
                ->join('subjects', 'subjects.id', '=', 'grade_subjects.subject_id')
                ->get();
            
            $data = [
                'gradeTeacher' => $gradeTeacher,
                'subjectTeacher' => $subjectTeacher,
            ];

            //  dd($data);

            return view('components.attendance.grade-attendance')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }
    
    public function subjectTeacher($id)
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance subject teacher',
            ]);

            $getIdTeacher = Teacher::where('user_id', $id)->value('id');

            $subjectTeacher = Teacher_subject::where('teacher_id', $getIdTeacher)
                ->join('subjects', 'subjects.id', '=', 'teacher_subjects.subject_id')
                ->join('grades', 'grades.id', '=', 'teacher_subjects.grade_id')
                ->get();
            
            $data = $subjectTeacher;

            // dd($data);

            return view('components.attendance.subject-attendance')->with('data', $data);

        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function detailAttendTeacher(Request $request){
        try {
            $id = $request->id;
            $gradeId = $request->gradeId;

            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance class teacher',
            ]);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();
            
            $grade = Grade::where('id', $gradeId)
                ->select('grades.name as grade_name','grades.class as grade_class', 'grades.id as grade_id')
                ->first();

            $semester = session('semester');
            $academic_year = session('academic_year');

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $gradeId)
                ->where('students.is_active', true)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->orderBy('students.name', 'asc')
                ->get();

                // $totalAttendances = [
                //     'total' => $results->count(),
                //     'dates' => $results->pluck('date')->unique()->values()->all(),
                //     'datesByMonth' => $results->groupBy(function($item) {
                //         return \Carbon\Carbon::parse($item->date)->format('F');
                //     })->map(function($group) {
                //         return $group->pluck('date')->unique()->values()->all();
                //     })
                // ];

                $totalAttendances = [
                    'total' => $results->count(),
                    'dates' => $results->pluck('date')->unique()->sortBy(function($date) {
                        return \Carbon\Carbon::parse($date)->format('Y-m-d'); // Urutkan secara kronologis
                    })->values()->all(),
                    'datesByMonth' => $results->groupBy(function($item) {
                        return \Carbon\Carbon::parse($item->date)->format('F'); // Kelompokkan berdasarkan nama bulan
                    })->sortKeysUsing(function($a, $b) {
                        // Sortir nama bulan berdasarkan urutan kronologis dari Januari hingga Desember
                        $months = [
                            'January', 'February', 'March', 'April', 'May', 'June', 'July',
                            'August', 'September', 'October', 'November', 'December'
                        ];
                        return array_search($a, $months) - array_search($b, $months);
                    })->map(function($group) {
                        return $group->pluck('date')->unique()->sortBy(function($date) {
                            return \Carbon\Carbon::parse($date)->day; // Urutkan tanggal dalam bulan dari 1 hingga akhir bulan
                        })->values()->all();
                    })
                ];
                
                
    
                // dd($totalAttendances);
    
                $attendancesByStudent = $results->groupBy('student_id')->map(function($attendances) {
                    $student = $attendances->first();
                    $present = $attendances->where('present', 1)->count() + $attendances->where('late', 1)->count();
                    $totalPresent = $attendances->where('present', 1)->count();
                    $totalSick = $attendances->where('sick', 1)->count();
                    $totalAlpha = $attendances->where('alpha', 1)->count();
                    $totalLate = $attendances->where('late', 1)->count();
                    $totalPermission = $attendances->where('permission', 1)->count();
                    $totalNonPresent = $attendances->where('present', 0)->count();
                    $effectiveDays = ($totalPresent + $totalAlpha + $totalSick + $totalLate + $totalPermission);
                
                    // Calculate the score ensuring effectiveDays is not zero to avoid division by zero
                    if ($effectiveDays > 0) {
                        $totalScore = 
                            ($totalPresent * 100) +    // Hadir dihitung 100 per present
                            ($totalSick * 100) + 
                            ($totalLate * 75) +        // Late dihitung 75 per keterlambatan
                            ($totalAlpha * 0) +        // Alpha dihitung 0
                            ($totalPermission * 0);    // Permission dihitung 0
                        
                        // Hitung rata-rata skor berdasarkan effective days
                        $score = round($totalScore / $effectiveDays);
                    } else {
                        $score = 0;
                    }
                
                    return [
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'total_present' => $totalPresent,
                        'present' => $present,
                        'total_alpha' => $totalAlpha,
                        'total_sick' => $totalSick,
                        'total_late' => $totalLate,
                        'total_pe' => $totalPermission,
                        'total_non_present' => $totalNonPresent,
                        'score' => $score,
                        'effective' => $effectiveDays,
                
                        'attendances' => $attendances->map(function ($attend) {
                            return [
                                'attendances_id' => $attend->id,
                                'attendances_date' => $attend->date,
                                'attendances_present' => $attend->present,
                                'attendances_alpha' => $attend->alpha,
                                'attendances_sick' => $attend->sick,
                                'attendances_late' => $attend->late,
                                'attendances_latest' => $attend->latest,
                                'attendances_permission' => $attend->permission,
                                'attendances_information' => $attend->information,
                            ];
                        })
                    ];
                })->values()->all();

                $status = Score_attendance_status::where('grade_id', $grade->grade_id)
                    ->where('semester', $semester)
                    ->where('academic_year', $academic_year)
                    ->where('class_teacher_id', $classTeacher->teacher_id)
                    ->first();

                // dd(count($attendancesByStudent));

                if(count($attendancesByStudent) != 0)
                {
                    $data = [
                        'classTeacher' => $classTeacher,
                        'semester' => $semester,
                        'students' => $attendancesByStudent,
                        'attendancesByMonth' => $attendancesByStudent,
                        'grade' => $grade,
                        'totalAttendances' => $totalAttendances,
                        'status' => $status,
                    ];
                // dd($data);
                return view('components.attendance.detail')->with('data', $data);
                }
                else {
                    session()->flash('data_is_empty');
                    return redirect()->back();
                }
                
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function detailViewAttendTeacher($id, $gradeId, $subjectId){
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => ' attendances',
            ]);

            $subjectTeacher = Teacher_subject::where('grade_id', $gradeId)
                ->where('subject_id', $subjectId)
                ->join('teachers', 'teachers.id', '=', 'teacher_subjects.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $classTeacher = Teacher_grade::where('grade_id', $gradeId)
                ->join('teachers', 'teachers.id', '=', 'teacher_grades.teacher_id')
                ->select('teachers.id as teacher_id', 'teachers.name as teacher_name')
                ->first();

            $subject = Subject::where('id', $subjectId)
                ->select('subjects.name_subject as subject_name', 'subjects.id as subject_id')
                ->first();
            
            $grade = Grade::where('id', $gradeId)
                ->select('grades.name as grade_name','grades.class as grade_class', 'grades.id as grade_id')
                ->first();


            $semester = session('semester');
            $academic_year = session('academic_year');

            $results = Grade::join('students', 'students.grade_id', '=', 'grades.id')
                ->leftJoin('attendances', function ($join) {
                    $join->on('attendances.student_id', '=', 'students.id');
                })
                ->select(
                    'students.id as student_id',
                    'students.name as student_name',
                    'attendances.*',
                )
                ->where('grades.id', $gradeId)
                ->where('attendances.subject_id', $subjectId)
                ->where('attendances.semester', $semester)
                ->where('attendances.academic_year', $academic_year)
                ->get();

            $totalAttendances = [
                'total' => $results->count(),
                'dates' => $results->pluck('date')->unique()->values()->all()
            ];

            $attendancesByStudent = $results->groupBy('student_id')->map(function($attendances){
                $student = $attendances->first();
                $totalPresent = $attendances->where('present', 1)->count();
                $totalNonPresent = $attendances->where('present', 0)->count();

                return [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'total_present' => $totalPresent,
                    'total_non_present' => $totalNonPresent,
                    'attendances' => $attendances->map(function ($attend) {
                        return [
                            'attendances_id' => $attend->id,
                            'attendances_date' => $attend->date,
                            'attendances_present' => $attend->present,
                            'attendances_alpha' => $attend->alpha,
                            'attendances_sick' => $attend->sick,
                            'attendances_late' => $attend->late,
                            'attendances_permission' => $attend->permission,
                            'attendances_information' => $attend->information,
                        ];
                    })
                ];
            })->values()->all();

            $data = [
                'subjectTeacher' => $subjectTeacher,
                'classTeacher' => $classTeacher,
                'subject' => $subject,
                'semester' => $semester,
                'students' => $attendancesByStudent,
                'grade' => $grade,
                'totalAttendances' => $totalAttendances,
            ];

            // dd($data);
            return view('components.attendance.detail')->with('data', $data);
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function detail($userId, $gradeId, $date)
    {
        try {
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance class teacher',
            ]);
    
            $getIdTeacher = Teacher::where('user_id', $userId)->value('id');
            $student      = Student::where('grade_id', $gradeId)->where('is_active', 1)->orderBy('name', 'asc')->get();
            $grade        = Grade::where('id', $gradeId)->first();
            $teacher      = Teacher::where('id', $getIdTeacher)->value('name');

            $nameGrade    = "$grade->name - $grade->class";
            $nameTeacher  = $teacher;

            $data = [
                'student'     => $student,
                'teacherId'   => $getIdTeacher,
                'gradeId'     => $gradeId,
                'nameGrade'   => $nameGrade,
                'nameTeacher' => $nameTeacher,
                'date'        => $date,
            ];

            return view('components.attendance.detail-attendance')->with('data', $data);
    
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function detailAll($userId, $gradeId)
    {
        try {
            //code...
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance class teacher',
            ]);
    
            $getIdTeacher = Teacher::where('user_id', $userId)->value('id');
            $student      = Student::where('grade_id', $gradeId)->where('is_active', 1)->orderBy('name', 'asc')->get();
            $grade        = Grade::where('id', $gradeId)->first();
            $teacher      = Teacher::where('id', $getIdTeacher)->value('name');

            $nameGrade    = "$grade->name - $grade->class";
            $nameTeacher  = $teacher;

            $semester = Master_academic::first()->value('now_semester');

            if ($semester == 1) {
                $startSemester = Master_academic::first()->value('semester1');
                $endSemester   = Master_academic::first()->value('end_semester1');
            }
            elseif ($semester == 2) {
                $startSemester = Master_academic::first()->value('semester2');
                $endSemester   = Master_academic::first()->value('end_semester2');
            }

            $data = [
                'student'     => $student,
                'teacherId'   => $getIdTeacher,
                'gradeId'     => $gradeId,
                'nameGrade'   => $nameGrade,
                'nameTeacher' => $nameTeacher,
            ];
    
            $dates = Attendance::where('teacher_id', $getIdTeacher)
                ->where('grade_id', $gradeId)
                ->pluck('date')          // Mengambil hanya kolom date
                ->unique()               // Menghilangkan tanggal yang duplikat
                ->values()               // Mengatur ulang indeks setelah unique
                ->toArray();
            
            // dd($dates);
            // Format array dates menjadi event data untuk FullCalendar
            $calendarData = [];
            foreach ($dates as $date) {
                $calendarData[] = [
                    'title' => 'Done',         // Status kehadiran
                    'start' => $date,          // Tanggal
                    'color' => 'green',        // Warna untuk kehadiran done
                ];
            }

            // Jika Anda ingin menambahkan tanggal not yet (dengan contoh lain)
            $notYetDates = [ /* Tanggal lain dengan status "not yet" */ ];
            foreach ($notYetDates as $date) {
                $calendarData[] = [
                    'title' => 'Not Yet',      // Status kehadiran not yet
                    'start' => $date,          // Tanggal
                    'color' => 'red',          // Warna untuk not yet
                ];
            }

            // dd(json_encode($calendarData));

            return view('components.attendance.detail-attendance-all')->with('data', $data)
            ->with('calendarData', $calendarData)
            ->with('gradeId', $gradeId)
            ->with('startSemester', $startSemester)
            ->with('endSemester', $endSemester);
    
        } catch (Exception $err) {
            return dd($err);
        }
    }

    public function edit($userId, $gradeId)
    {
       try {
        session()->flash('page',  $page = (object)[
            'page' => 'attendance',
            'child' => 'attendance class teacher',
        ]);
 
        $semester      = session('semester');
        $academic_year = session('academic_year');
        $getIdTeacher  = Teacher::where('user_id', $userId)->value('id');

        $grade = Grade::where('id', $gradeId)->first();

        $attendances = Attendance::where('teacher_id', $getIdTeacher)
            ->where('grade_id', $gradeId)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->leftJoin('grades', 'grades.id', '=', 'attendances.grade_id')
            ->select('attendances.date as date', 'grades.id as grade_id')
            ->distinct('date')
            ->get();

        if (count($attendances) != 0) {
            $data = [
               'semester' => $semester,
               'teacher'  => $getIdTeacher,
               'date'     => $attendances,
               'grade'    => $grade,
            ];
     
             return view('components.attendance.date')->with('data', $data);           
        }
        else {
            session()->flash('data_is_empty');
            return redirect()->back();
        }

       } catch (Exception $err) {
         return dd($err);
       }
    }

    public function editDetail($date, $gradeId, $teacherId, $semester)
    {
       try {
        session()->flash('page',  $page = (object)[
            'page' => 'attendance',
            'child' => 'attendance class teacher',
        ]);

        $semester = session('semester');
        $academic_year = session('academic_year');

        $attendances = Attendance::where('teacher_id', $teacherId)
            ->where('date', $date)
            ->where('attendances.grade_id', $gradeId)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->leftjoin('students', 'students.id', '=', 'attendances.student_id')
            ->select('attendances.*','students.name as student_name')
            ->get();

        // dd($attendances);

        return view('components.attendance.edit-attendance')->with('data', $attendances)->with('date', $date);           
        

       } catch (Exception $err) {
         return dd($err);
       }
    }

    public function postAttendance(Request $request){
        try {

            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance subject teacher',
            ]);

            $semester = session('semester');
            $userId = session('id_user');
            $getIdTeacher = Teacher::where('user_id', $userId)->value('id');

            foreach($request->status as $studentId => $status) {

                if(Attendance::where('date', $request->date)
               ->where('grade_id', $request->grade_id)
               ->where('teacher_id', $request->teacher_id)
               ->where('student_id', $studentId)
               ->where('semester', $semester)
               ->where('academic_year', session('academic_year'))
               ->exists()) {
                    return redirect('/teacher/dashboard/attendance/'. $userId . '/' . $request->grade_id)
                    ->with('failed_attend', 'Attendance already recorded for this student.');
                }

                // Skip if no status is selected
                if(empty($status)) continue;
    
                // Initialize attendance array
                $attend = [
                    'student_id'  => $studentId,
                    'grade_id'    => $request->grade_id,
                    'teacher_id'  => $request->teacher_id,
                    'date'        => $request->date,
                    'present'     => $status === 'present' ? 1 : 0,
                    'alpha'       => $status === 'alpha' ? 1 : 0,
                    'sick'        => $status === 'sick' ? 1 : 0,
                    'late'        => $status === 'late' ? 1 : 0,
                    'latest'      => $request->latest[$studentId] ?? 0,
                    'permission'  => $status === 'permission' ? 1 : 0,
                    'information' => $request->comment[$studentId] ?? '',
                    'semester'    => $semester,
                    'academic_year' => session('academic_year'),
                    'created_at'  => now(),
                ];
    
                // Save the attendance to the database
                Attendance::create($attend);
            }

            session()->flash('success_attend');

            if(session('role') == 'superadmin') {
                return redirect('/superadmin/attendances');
            }
            elseif (session('role') == 'admin') {
                return redirect('/admin/attendances');
            }
            elseif (session('role') == 'teacher') {
                return redirect()->route('attendanceAll', ['id' => session('id_user'), 'gradeId' => $request->grade_id]);

            }

        } catch(Exception $err){
            dd($err);
        }
    }

    public function postEditAttendance(Request $request){
        try {

            // dd($request);
            session()->flash('page',  $page = (object)[
                'page' => 'attendance',
                'child' => 'attendance subject teacher',
            ]);

            foreach($request->status as $attendanceId => $status) {
    
                // Initialize attendance array
                $attend = [
                    'present'     => $status === 'present' ? 1 : 0,
                    'alpha'       => $status === 'alpha' ? 1 : 0,
                    'sick'        => $status === 'sick' ? 1 : 0,
                    'late'        => $status === 'late' ? 1 : 0,
                    'latest'      => $request->latest[$attendanceId] ?? 0,
                    'permission'  => $status === 'permission' ? 1 : 0,
                    'information' => $request->comment[$attendanceId] ?? '',
                    'updated_at'  => now(),
                ];
    
                // Save the attendance to the database
                Attendance::updateOrCreate(
                    ['id' => $attendanceId],
                    $attend
                );
            }

            session()->flash('success_edit_attend');

            if(session('role') == 'superadmin') {
                return redirect('/superadmin/attendances');
            }
            elseif (session('role') == 'admin') {
                return redirect('/admin/attendances');
            }
            elseif (session('role') == 'teacher') {
                return redirect()->back();

            }

        } catch(Exception $err){
            dd($err);
        }
    }
}
