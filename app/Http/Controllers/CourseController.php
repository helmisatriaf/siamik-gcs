<?php

namespace App\Http\Controllers;

use App\Models\CourseActivities;
use App\Models\Grade;
use App\Models\Grade_subject;
use App\Models\Teacher_subject;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Master_academic;
use App\Models\Schedule;
use App\Models\Ebook;
use App\Models\Exam;
use App\Models\Chinese_higher;
use App\Models\Chinese_lower;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function index()
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'course',
            ]);

            $role = session('role');

            // For students, show their grade's subjects directly
            if ($role == 'student') {
                $studentId = Student::where('user_id', session('id_user'))->value('id');
                $religion = Student::where('user_id', session('id_user'))->value('religion');
                $gradeId = Student::where('id', $studentId)->value('grade_id');
                if ($religion == "Islam") {
                    $cek = 36;
                } elseif ($religion == "Catholic Christianity") {
                $cek = 34;
                } elseif ($religion == "Protestant Christianity") {
                $cek = 37;
                } elseif ($religion == "Buddhism") {
                $cek = 35;
                }

                if($gradeId >= 11){
                    $chineseLower = Chinese_lower::where('student_id', $studentId)->exists();
                    $chineseHigher = Chinese_higher::where('student_id', $studentId)->exists();
                    if($chineseLower == true){
                        $chinese = 38;
                    }
                    if($chineseHigher == true){
                        $chinese = 39;
                    }

                    $grade = Grade::findOrFail($gradeId);
                    $data = Subject::whereHas('grade', function ($query) use ($gradeId) {
                        $query->where('grade_id', $gradeId)
                            ->where('grade_subjects.academic_year', session('academic_year'));
                    })
                    ->where(function ($query) use ($cek) {
                        $query->whereNotIn('subjects.id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                        ->orWhere('subjects.id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
                    })
                    ->where(function ($query) use ($chinese) {
                        $query->whereNotIn('subjects.id', [38, 39]) // Eksklusi semua pelajaran agama
                        ->orWhere('subjects.id', $chinese); // Masukkan hanya pelajaran agama sesuai agama siswa
                    })
                    ->orderBy('name_subject', 'asc')
                    ->paginate(30);
                }
                else{
                    $grade = Grade::findOrFail($gradeId);
                    $data = Subject::whereHas('grade', function ($query) use ($gradeId) {
                        $query->where('grade_id', $gradeId)
                            ->where('grade_subjects.academic_year', session('academic_year'));
                    })
                    ->where(function ($query) use ($cek) {
                        $query->whereNotIn('subjects.id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                        ->orWhere('subjects.id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
                    })
                    ->orderBy('name_subject', 'asc')
                    ->paginate(30);
                }

                if (!$gradeId) {
                    return redirect()->back()->with('error', 'Grade not found for student');
                }

                return view('components.course.list-grade-subjects', compact('data', 'grade'));
            }
            if ($role == 'parent') {
                $gradeId = Student::where('id', session('studentId'))->value('grade_id');
                $religion = Student::where('id', session('studentId'))->value('religion');
                if ($religion == "Islam") {
                    $cek = 36;
                } elseif ($religion == "Catholic Christianity") {
                $cek = 34;
                } elseif ($religion == "Protestant Christianity") {
                $cek = 37;
                } elseif ($religion == "Buddhism") {
                $cek = 35;
                }

                if (!$gradeId) {
                    return redirect()->back()->with('error', 'Grade not found for student');
                }

                if($gradeId >= 11){
                    $chineseLower = Chinese_lower::where('student_id', session('studentId'))->exists();
                    $chineseHigher = Chinese_higher::where('student_id', session('studentId'))->exists();
                    if($chineseLower == true){
                        $chinese = 38;
                    }
                    if($chineseHigher == true){
                        $chinese = 39;
                    }

                    $data = Subject::whereHas('grade', function ($query) use ($gradeId) {
                        $query->where('grade_id', $gradeId)
                            ->where('grade_subjects.academic_year', session('academic_year'));
                    })
                    ->where(function ($query) use ($cek) {
                        $query->whereNotIn('subjects.id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                        ->orWhere('subjects.id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
                    })
                    ->where(function ($query) use ($chinese) {
                        $query->whereNotIn('subjects.id', [38, 39]) // Eksklusi semua pelajaran agama
                        ->orWhere('subjects.id', $chinese); // Masukkan hanya pelajaran agama sesuai agama siswa
                    })
                    ->orderBy('name_subject', 'asc')
                    ->paginate(30);
                }
                else{
                    $data = Subject::whereHas('grade', function ($query) use ($gradeId) {
                        $query->where('grade_id', $gradeId)
                            ->where('grade_subjects.academic_year', session('academic_year'));
                    })
                    ->where(function ($query) use ($cek) {
                        $query->whereNotIn('subjects.id', [34, 35, 36, 37]) // Eksklusi semua pelajaran agama
                        ->orWhere('subjects.id', $cek); // Masukkan hanya pelajaran agama sesuai agama siswa
                    })
                    ->orderBy('name_subject', 'asc')
                    ->paginate(30);
                }

                $grade = Grade::findOrFail($gradeId);

                return view('components.course.list-grade-subjects', compact('data', 'grade'));
            }
            // For teachers, show their assigned subjects
            else if ($role == 'teacher') {
                $teacherId = Teacher::where('user_id', session('id_user'))->value('id');

                // Get the teacher's assigned grade
                $gradeId = DB::table('teacher_subjects')
                    ->where('teacher_id', $teacherId)
                    ->where('academic_year', session('academic_year'))
                    ->value('grade_id');

                if (!$gradeId) {
                    return redirect()->back()->with('error', 'No assigned grade found for teacher');
                }

                $grade = Grade::findOrFail($gradeId);

                $data = Teacher_subject::join('subjects','subjects.id','=','teacher_subjects.subject_id')
                    ->join('grades','grades.id','=','teacher_subjects.grade_id')
                    ->where('teacher_id', $teacherId)
                    ->where('academic_year', session('academic_year'))
                    ->select('subjects.*', DB::raw("CONCAT(grades.name, '-', grades.class) as grade_name"), 'grades.id as grade_id')
                    ->orderBy('subjects.name_subject', 'asc')
                    ->orderBy('teacher_subjects.grade_id', 'asc')
                    ->paginate(30);

                return view('components.course.list-grade-subjects', compact('data', 'grade'));
            }
            // For admin/superadmin, show all courses
            else {
                $data = Grade::with(['student', 'teacher' => function ($query) {
                    $query->where('teacher_grades.academic_year', session('academic_year'));
                }, 'exam' => function ($query) {
                    $query->where('grade_exams.academic_year', session('academic_year'));
                }, 'subject' => function ($query) {
                    $query->where('grade_subjects.academic_year', session('academic_year'));
                }])
                    ->withCount([
                        'student as active_student_count',
                        'teacher as active_teacher_count' => function ($query) {
                            $query->where('teacher_grades.academic_year', session('academic_year'));
                        },
                        'exam as active_exam_count' => function ($query) {
                            $query->where('exams.semester', session('semester'))
                                ->where('grade_exams.academic_year', session('academic_year'));
                        },
                        'subject as active_subject_count' => function ($query) {
                            $query->where('grade_subjects.academic_year', session('academic_year'));
                        }
                    ])
                    ->get();
                return view('components.course.list-allcourse', compact('data'));
            }
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function showGradeSubjects($role, $id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'course',
            ]);

            $grade = Grade::findOrFail($id);

            $data = Subject::whereHas('grade', function ($query) use ($id) {
                $query->where('grade_id', $id)
                    ->where('grade_subjects.academic_year', session('academic_year'));
            })->paginate(30); 

            // dd($data);
            return view('components.course.list-grade-subjects', compact('data', 'grade'));
        } catch (Exception $err) {
            dd($err);
        }
    }


    public function sections($role, $id, $grade_id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
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
                ->where('grade_id', $grade_id)
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

            // Get the grade_subject record
            $gradeSubject = Grade_subject::where('subject_id', $id)
                ->where('grade_id', $grade_id)
                ->where('academic_year', session('academic_year'))
                ->firstOrFail();

            // $ebook = Ebook::where('grade_subject_id', $gradeSubject->id)->get();
            // dd($ebook);

            // Get specific subject data with specific grade
            $subject = Subject::with(['grade' => function ($query) use ($grade_id) {
                $query->where('grades.id', $grade_id)
                    ->where('grade_subjects.academic_year', session('academic_year'));
            }])->findOrFail($id);

            // Get sections for this subject-grade combination
            $sections = Section::where('grade_subject_id', $gradeSubject->id)
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))
                ->orderBy('created_at', 'asc')
                ->paginate(10);

            $ebook = Ebook::where('grade_subject_id', $gradeSubject->id)->first();
            $material = CourseActivities::where('grade_subject_id', $gradeSubject->id)->count();
            
            $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                ->where('subject_exams.subject_id', $id)
                ->where('grade_exams.grade_id', $grade_id)
                ->where('exams.semester', session('semester'))
                ->where('exams.academic_year', session('academic_year'))
                ->count();

            $assessmentActive = $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                ->where('subject_exams.subject_id', $id)
                ->where('grade_exams.grade_id', $grade_id)
                ->where('exams.semester', session('semester'))
                ->where('exams.academic_year', session('academic_year'))
                ->where('exams.is_active', TRUE)
                ->count();

            return view('components.course.sections', compact('subject', 'sections', 'grade_id', 'gradeSubject', 'course', 'ebook',
            'material', 'assessment', 'assessmentActive'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function sectionsForStudent($id, $grade_id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'detail course',
            ]);

            $schedule = Schedule::where('subject_id', $id)
                ->where('grade_id', $grade_id)
                ->value('day');

            $daysMap = [
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday'
            ];

            $day = $daysMap[$schedule] ?? 'Monday'; // Default to Monday if not found

            $masterAcademic = Master_academic::where('is_use', true)->first(); 
            if (session('semester') == 1) {
                $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester1);
                $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester1);
            } elseif (session('semester') == 2) {
                $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester2);
                $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester2);
            }

            $course = [];
            $currentDate = $startSemester->copy();
            $globalWeekNumber = 1;
            $lastMonth = null;
            $weekNumber = 1;

            while ($currentDate <= $endSemester) {
                // Cari tanggal yang sesuai dengan hari yang diinginkan
                while ($currentDate->format('l') !== $day) {
                    $currentDate->addDay();
                }
                
                $month = $currentDate->format('F Y');
                if (!isset($course[$month])) {
                    $course[$month] = [];
                }
                
                $course[$month][] = "Week $weekNumber ({$currentDate->format('j F Y')})";
                
                // Pindah ke minggu berikutnya
                $currentDate->addWeek();
                $weekNumber++;
            }

            // Ambil data grade_subject berdasarkan subject dan grade
            $gradeSubject = Grade_subject::where('subject_id', $id)
                ->where('grade_id', $grade_id)
                ->where('academic_year', session('academic_year'))
                ->firstOrFail();

            // Ambil data subject berdasarkan grade tertentu
            $subject = Subject::with(['grade' => function ($query) use ($grade_id) {
                $query->where('grades.id', $grade_id)
                    ->where('grade_subjects.academic_year', session('academic_year'));
            }])->findOrFail($id);

            // Ambil sections berdasarkan subject dan grade
            $sections = Section::where('grade_subject_id', $gradeSubject->id)
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))
                ->orderBy('created_at', 'asc')
                ->paginate(10);

            return view('components.course.sections', compact('subject', 'sections', 'grade_id', 'gradeSubject', 'course'));
        } catch (Exception $err) {
            return redirect()->back()->with('error', 'Failed to load sections.');
        }
    }

    public function sectionsForTeacher($id, $grade_id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
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
                ->where('grade_id', $grade_id)
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

            // Ambil data grade_subject berdasarkan subject dan grade
            $gradeSubject = Grade_subject::where('subject_id', $id)
                ->where('grade_id', $grade_id)
                ->where('academic_year', session('academic_year'))
                ->firstOrFail();

            $ebook = Ebook::where('grade_subject_id', $gradeSubject->id)->first();
            $material = CourseActivities::where('grade_subject_id', $gradeSubject->id)->count();
            
            $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                ->where('subject_exams.subject_id', $id)
                ->where('grade_exams.grade_id', $grade_id)
                ->where('exams.semester', session('semester'))
                ->where('exams.academic_year', session('academic_year'))
                ->count();

            $assessmentActive = $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                ->where('subject_exams.subject_id', $id)
                ->where('grade_exams.grade_id', $grade_id)
                ->where('exams.semester', session('semester'))
                ->where('exams.academic_year', session('academic_year'))
                ->where('exams.is_active', TRUE)
                ->count();

            // Ambil data subject berdasarkan grade tertentu
            $subject = Subject::with(['grade' => function ($query) use ($grade_id) {
                $query->where('grades.id', $grade_id);
            }])->findOrFail($id);

            // Ambil sections berdasarkan subject dan grade
            $activities = CourseActivities::where('grade_subject_id', $gradeSubject->id)
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))
                ->orderBy('created_at', 'asc')
                ->get();

            return view('components.course.sections', 
            compact('subject', 'activities', 'grade_id', 'gradeSubject', 'course', 'ebook', 'assessment', 'assessmentActive', 'material'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function sectionsClassForTeacher($id, $grade_id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
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
                ->where('grade_id', $grade_id)
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

            // Ambil data grade_subject berdasarkan subject dan grade
            $gradeSubject = Grade_subject::where('subject_id', $id)
                ->where('grade_id', $grade_id)
                ->where('academic_year', session('academic_year'))
                ->firstOrFail();

                
            $ebook = Ebook::where('grade_subject_id', $gradeSubject->id)->first();
            $material = CourseActivities::where('grade_subject_id', $gradeSubject->id)->count();
            
            $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                ->where('subject_exams.subject_id', $id)
                ->where('grade_exams.grade_id', $grade_id)
                ->where('exams.semester', session('semester'))
                ->where('exams.academic_year', session('academic_year'))
                ->count();

            $assessmentActive = $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                ->where('subject_exams.subject_id', $id)
                ->where('grade_exams.grade_id', $grade_id)
                ->where('exams.semester', session('semester'))
                ->where('exams.academic_year', session('academic_year'))
                ->where('exams.is_active', TRUE)
                ->count();

            // dd($assessmentActive);

            // Ambil data subject berdasarkan grade tertentu
            $subject = Subject::with(['grade' => function ($query) use ($grade_id) {
                $query->where('grades.id', $grade_id);
            }])->findOrFail($id);

            // Ambil sections berdasarkan subject dan grade
            $activities = CourseActivities::where('grade_subject_id', $gradeSubject->id)
                ->where('semester', session('semester'))
                ->where('academic_year', session('academic_year'))
                ->orderBy('created_at', 'asc')
                ->get();

            // dd($activities);
            return view('components.course.sections-class', 
            compact('subject', 'activities', 'grade_id', 'gradeSubject', 'course', 'ebook', 'assessment', 'assessmentActive', 'material'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function editSection($role, $id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'sections',
            ]);

            $data = Section::where('id', $id)->first();
            $id = $id;

            // dd($data);
            return view('components.course.edit-section', compact('data', 'id'));
        } catch (Exception $err) {
            dd($err);
        }
    }
    
    public function updateSection(Request $request)
    {
        try {
            $update = Section::where('id', $request->id)->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            if($update == true){
                session()->flash('succes_edit_section');
                $data = Section::where('sections.id', $request->id)
                    ->join('grade_subjects', 'grade_subjects.id', '=', 'sections.grade_subject_id')
                    ->select('grade_subjects.*')
                    ->first();

                session()->flash('succes_edit_section');
                return redirect(session('role') . '/course/' . $data->subject_id . '/sections/' . $data->grade_id );
            }
        }
        catch(Exception $err) {
            dd($err);
        }
    }


    public function createSection($role, $id, $grade_id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'sections',
            ]);

            // Cari subject berdasarkan ID
            $subject = Subject::findOrFail($id);
            $grade   = Grade::findOrFail($grade_id);

            // Ambil grade_subject berdasarkan subject_id dan grade_id
            $gradeSubject = Grade_subject::where('subject_id', $id)
                ->where('grade_id', $grade_id)
                ->where('academic_year', session('academic_year'))
                ->firstOrFail();

            return view('components.course.add-section', compact('grade', 'subject', 'grade_id', 'gradeSubject'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function createSectionTeacher($id, $grade_id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'sections',
            ]);

            $subject = Subject::findOrFail($id);
            $gradeSubject = Grade_subject::where('subject_id', $id)
                ->where('grade_id', $grade_id)
                ->where('academic_year', session('academic_year'))
                ->firstOrFail();

            return view('components.course.add-section', compact('subject', 'grade_id', 'gradeSubject'));
        } catch (Exception $err) {
            dd($err);
        }
    }


    public function storeSection(Request $request, $role, $id, $grade_id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240'
        ]);

        // Get the grade_subject record
        $gradeSubject = Grade_subject::where('subject_id', $id)
            ->where('grade_id', $grade_id)
            ->where('academic_year', session('academic_year'))
            ->firstOrFail();

        $subject = Subject::findOrFail($id);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('subject_sections', 'public');
        }

        // Create new section with grade_subject_id
        $section = new Section([
            'subject_id' => $subject->id,
            'grade_subject_id' => $gradeSubject->id,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'file_path' => $filePath,
            'semester' => session('semester'),
            'academic_year' => session('academic_year'),
        ]);
        $section->save();

        return redirect()->route('course.sections', ['role' => $role, 'id' => $id, 'grade_id' => $grade_id])
            ->with('success', 'Section berhasil ditambahkan');
    }

    public function storeSectionTeacher(Request $request, $id, $grade_id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240'
        ]);

        $gradeSubject = Grade_subject::where('subject_id', $id)
            ->where('grade_id', $grade_id)
            ->where('academic_year', session('academic_year'))
            ->firstOrFail();

        $subject = Subject::findOrFail($id);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('ebooks', 'public');
        }

        $ebook = new Ebook([
            'grade_subject_id' => $gradeSubject->id,
            'title' => $validatedData['title'],
            'file_path' => $filePath,
            'semester' => session('semester'),
            'academic_year' => session('academic_year'),
        ]);
        $ebook->save();

        session()->flash('success_add_ebook');
        return redirect()->route('course.sections.teacher', [  // Updated route name
            'id' => $id,
            'grade_id' => $grade_id
        ])->with('success', 'Section berhasil ditambahkan');
    }

    public function editSectionTeacher($id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'sections',
            ]);

            $data = Section::where('id', $id)->first();
            $id = $id;

            // dd($data);
            return view('components.course.edit-section', compact('data', 'id'));
        } catch (Exception $err) {
            dd($err);
        }
    }
    
    public function updateSectionTeacher(Request $request)
    {
        try {
            $update = Section::where('id', $request->id)->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            if($update == true){
                session()->flash('succes_edit_section');
                $data = Section::where('sections.id', $request->id)
                    ->join('grade_subjects', 'grade_subjects.id', '=', 'sections.grade_subject_id')
                    ->select('grade_subjects.*')
                    ->first();

                session()->flash('succes_edit_section');
                return redirect(session('role') . '/course/' . $data->subject_id . '/sections/' . $data->grade_id );
            }
        }
        catch(Exception $err) {
            dd($err);
        }
    }

    public function createActivity($role, $id, $grade_id, $section_id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'sections',
            ]);

            $subject = Subject::findOrFail($id);
            $section = Section::findOrFail($section_id);

            return view('components.course.add-activity', compact('subject', 'section', 'grade_id'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function editActivity($role, $id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'sections',
            ]);

            $id = $id;
            $data = CourseActivities::where('id', $id)->first();
            $section = Section::findOrFail($data->section_id);

            return view('components.course.edit-activity', compact('data', 'section', 'id'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function updateActivity(Request $request, $role, $id)
    {
        try {

            if($request->upload_file){
                $checkFile = CourseActivities::where('id', $id)->value('file_path');
                if($checkFile !== null){
                    if (Storage::exists($checkFile)) {
                        Storage::delete('public/' . $checkFile);
                    }
                }
                $filePath = $request->file('upload_file')->store('subject_sections', 'public');
                $update = CourseActivities::where('id', $id)->update([
                    'file_path' => $filePath,
                ]);
                if($update == true){
                    session()->flash('succes_edit_file');
                    $data = CourseActivities::where('course_activities.id', $request->id)
                        ->join('sections', 'sections.id', '=', 'course_activities.section_id')
                        ->join('grade_subjects', 'grade_subjects.id', '=', 'sections.grade_subject_id')
                        ->select('grade_subjects.*')
                        ->first();
    
                    session()->flash('succes_edit_activity');
                    return redirect(session('role') . '/course/' . $data->subject_id . '/sections/' . $data->grade_id );
                }
            }
            else{
                $update = CourseActivities::where('id', $request->id)->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'open_time' => $request->open_time,
                    'due_time' => $request->due_time,
                ]);
    
                if($update == true){
                    session()->flash('succes_edit_activity');
                    $data = CourseActivities::where('course_activities.id', $request->id)
                        ->join('sections', 'sections.id', '=', 'course_activities.section_id')
                        ->join('grade_subjects', 'grade_subjects.id', '=', 'sections.grade_subject_id')
                        ->select('grade_subjects.*')
                        ->first();
    
                    session()->flash('succes_edit_activity');
                    return redirect(session('role') . '/course/' . $data->subject_id . '/sections/' . $data->grade_id );
                }
            }
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function deleteActivity(Request $request, $role)
    {
        try {

            $checkFile = CourseActivities::where('id', $request->id)->value('file_path');
            $data = CourseActivities::where('course_activities.id', $request->id)
                ->join('sections', 'sections.id', '=', 'course_activities.section_id')
                ->join('grade_subjects', 'grade_subjects.id', '=', 'sections.grade_subject_id')
                ->select('grade_subjects.*')
                ->first();
            $delete = CourseActivities::Where('id', $request->id)->delete();

            if($delete == true){
                if($checkFile !== null){
                    if (Storage::exists($checkFile)) {
                        Storage::delete('public/' . $checkFile);
                    }
                }
                session()->flash('succes_delete_activity');
                return redirect(session('role') . '/course/' . $data->subject_id . '/sections/' . $data->grade_id );
            }
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function createActivityTeacher($id, $grade_id, $section_id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'sections',
            ]);

            // dd($section_id);
            $subject = Subject::findOrFail($id);
            // $section = Section::findOrFail($section_id);

            return view('components.course.add-activity', compact('subject', 'grade_id', 'section_id'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function storeActivity(Request $request, $role, $id, $grade_id, $section_id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'nullable|file|max:10240', // 10MB max
                'open_time' => 'nullable|date',
                'due_time' => 'nullable|date|after:open_time'
            ]);

            // Ambil section berdasarkan grade_subject_id
            $section = Section::where('id', $section_id)
                ->whereHas('gradeSubject', function ($query) use ($grade_id) {
                    $query->where('grade_id', $grade_id);
                })
                ->firstOrFail();

            // Handle file upload
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('section_activities', 'public');
            }

            // Create new activity
            $activity = new CourseActivities([
                'section_id' => $section->id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? null,
                'file_path' => $filePath,
                'semester' => session('semester'),
                'academic_year' => session('academic_year'),
                'open_time' => $validatedData['open_time'],
                'due_time' => $validatedData['due_time']
            ]);
            $activity->save();

            // Redirect back to sections page with success message
            return redirect()->route('course.sections', [
                'role' => $role,
                'id' => $id,
                'grade_id' => $grade_id
            ])->with('success', 'Aktivitas berhasil ditambahkan');
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function storeActivityTeacher(Request $request, $id, $grade_id, $section_id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'nullable|file|max:10240',
            ]);

            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('section_activities', 'public');
            }

            $gradeSubject = Grade_subject::where('subject_id', $id)
                ->where('grade_id', $grade_id)
                ->where('academic_year', session('academic_year'))
                ->first();

            $activity = new CourseActivities([
                'section_id' => $section_id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? null,
                'file_path' => $filePath,
                'grade_subject_id' => $gradeSubject->id,
                'semester' => session('semester'),
                'academic_year' => session('academic_year'),
            ]);
            $activity->save();

            session()->flash('success_add_activity');
            return redirect()->route('course.sections.teacher', [  // Updated route name
                'id' => $id,
                'grade_id' => $grade_id
            ])->with('success', 'Aktivitas berhasil ditambahkan');
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function editActivityTeacher($id)
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'sections',
            ]);

            $id = $id;
            $data = CourseActivities::where('id', $id)->first();
            return view('components.course.edit-activity', compact('data', 'id'));
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function updateActivityTeacher(Request $request, $id)
    {
        try {

            if($request->upload_file){
                $checkFile = CourseActivities::where('id', $id)->value('file_path');
                if($checkFile !== null){
                    if (Storage::exists($checkFile)) {
                        Storage::delete('public/' . $checkFile);
                    }
                }
                $filePath = $request->file('upload_file')->store('subject_sections', 'public');
                $update = CourseActivities::where('id', $id)->update([
                    'file_path' => $filePath,
                ]);
                if($update == true){
                    session()->flash('succes_edit_file');
                    $data = CourseActivities::where('course_activities.id', $request->id)
                        ->join('grade_subjects', 'grade_subjects.id', '=', 'course_activities.grade_subject_id')
                        ->select('grade_subjects.*')
                        ->first();
    
                    session()->flash('succes_edit_file_activity');
                    return redirect(session('role') . '/course/' . $data->subject_id . '/sections/' . $data->grade_id );
                }
            }
            else{
                $update = CourseActivities::where('id', $request->id)->update([
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
    
                if($update == true){
                    session()->flash('succes_edit_activity');
                    $data = CourseActivities::where('course_activities.id', $request->id)
                        ->join('grade_subjects', 'grade_subjects.id', '=', 'course_activities.grade_subject_id')
                        ->select('grade_subjects.*')
                        ->first();
    
                    session()->flash('succes_edit_activity');
                    return redirect(session('role') . '/course/' . $data->subject_id . '/sections/' . $data->grade_id );
                }
            }
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function deleteActivityTeacher(Request $request)
    {
        try {

            $checkFile = CourseActivities::where('id', $request->id)->value('file_path');
            $data = CourseActivities::where('course_activities.id', $request->id)
                ->join('grade_subjects', 'grade_subjects.id', '=', 'course_activities.grade_subject_id')
                ->select('grade_subjects.*')
                ->first();

            $delete = CourseActivities::Where('id', $request->id)->delete();

            if($delete == true){
                if($checkFile !== null){
                    if (Storage::exists($checkFile)) {
                        Storage::delete('public/' . $checkFile);
                    }
                }
                session()->flash('succes_delete_activity');
                return redirect(session('role') . '/course/' . $data->subject_id . '/sections/' . $data->grade_id );
            }
        } catch (Exception $err) {
            dd($err);
        }
    }

    public function setSectionId(Request $request)
    {
        session(['section_id' => $request->id]);
        session(['grade_subject_id' => $request->gradeSubject]);

        return response()->json(['success' => true]);
    }

    public function course()
    {
        try {
            session()->flash('page', (object)[
                'page' => 'course',
                'child' => 'detail course',
            ]);

            if(session('role') == 'student'){
                $studentId = Student::where('user_id', session('id_user'))->value('id');

                $grade_id = Student::where('id', $studentId)->value('grade_id');

                $gradeSubject = Grade_subject::where('subject_id', session('id_course'))
                    ->where('grade_id', $grade_id)
                    ->where('academic_year', session('academic_year'))
                    ->firstOrFail();

                // Ambil data subject berdasarkan grade tertentu
                $subject = Subject::with(['grade' => function ($query) use ($grade_id) {
                    $query->where('grades.id', $grade_id)
                        ->where('grade_subjects.academic_year', session('academic_year'));
                }])->findOrFail(session('id_course'));

                $schedule = Schedule::where('subject_id', session('id_course'))
                    ->where('grade_id', $grade_id)
                    ->value('day');

                $daysMap = [
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday'
                ];

                $day = $daysMap[$schedule] ?? 'Monday'; // Default to Monday if not found

                $masterAcademic = Master_academic::where('is_use', true)->first(); 
                if (session('semester') == 1) {
                    $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester1);
                    $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester1);
                } elseif (session('semester') == 2) {
                    $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester2);
                    $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester2);
                }

                $course = [];
                $currentDate = $startSemester->copy();
                $globalWeekNumber = 1;
                $lastMonth = null;
                $weekNumber = 1;

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
                    $course[$month][$index] = "Week $weekNumber ({$currentDate->format('j F')})";
                    // Tambah 7 hari ke depan (langsung ke minggu berikutnya)
                    $currentDate->addWeek();
                
                    $weekNumber++;
                }

                $ebook = Ebook::where('grade_subject_id', $gradeSubject->id)->first();
                $material = CourseActivities::where('grade_subject_id', $gradeSubject->id)->count();
                
                $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                    ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                    ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                    ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                    ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                    ->where('subject_exams.subject_id', $subject->id)
                    ->where('grade_exams.grade_id', $grade_id)
                    ->where('exams.semester', session('semester'))
                    ->where('exams.academic_year', session('academic_year'))
                    ->count();

                $assessmentActive = $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                    ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                    ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                    ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                    ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                    ->where('subject_exams.subject_id', $subject->id)
                    ->where('grade_exams.grade_id', $grade_id)
                    ->where('exams.semester', session('semester'))
                    ->where('exams.academic_year', session('academic_year'))
                    ->where('exams.is_active', TRUE)
                    ->count();

            }
            elseif(session('role') == 'parent'){
                $grade_id = Student::where('id', session('studentId'))->value('grade_id');
    
                $gradeSubject = Grade_subject::where('subject_id', session('id_course'))
                    ->where('grade_id', $grade_id)
                    ->where('academic_year', session('academic_year'))
                    ->firstOrFail();
    
                // Ambil data subject berdasarkan grade tertentu
                $subject = Subject::with(['grade' => function ($query) use ($grade_id) {
                    $query->where('grades.id', $grade_id)
                        ->where('grade_subjects.academic_year', session('academic_year'));
                }])->findOrFail(session('id_course'));

                $schedule = Schedule::where('subject_id', session('id_course'))
                    ->where('grade_id', $grade_id)
                    ->value('day');

                $daysMap = [
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday'
                ];

                $day = $daysMap[$schedule] ?? 'Monday'; // Default to Monday if not found

                $masterAcademic = Master_academic::where('is_use', true)->first(); 
                if (session('semester') == 1) {
                    $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester1);
                    $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester1);
                } elseif (session('semester') == 2) {
                    $startSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->semester2);
                    $endSemester = Carbon::createFromFormat('Y-m-d', $masterAcademic->end_semester2);
                }

                $course = [];
                $currentDate = $startSemester->copy();
                $globalWeekNumber = 1;
                $lastMonth = null;
                $weekNumber = 1;

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
                    $course[$month][$index] = "Week $weekNumber ({$currentDate->format('j F')})";
                    // Tambah 7 hari ke depan (langsung ke minggu berikutnya)
                    $currentDate->addWeek();
                
                    $weekNumber++;
                }

                $ebook = Ebook::where('grade_subject_id', $gradeSubject->id)->first();
                $material = CourseActivities::where('grade_subject_id', $gradeSubject->id)->count();
                
                $assessment = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                    ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                    ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                    ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                    ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                    ->where('subject_exams.subject_id', $subject->id)
                    ->where('grade_exams.grade_id', $grade_id)
                    ->where('exams.semester', session('semester'))
                    ->where('exams.academic_year', session('academic_year'))
                    ->count();

                $assessmentActive = $data = Exam::select('exams.*', 'grades.name as grade_name', 'subjects.name_subject')
                    ->join('grade_exams', 'exams.id', '=', 'grade_exams.exam_id')
                    ->join('grades', 'grade_exams.grade_id', '=', 'grades.id')
                    ->join('subject_exams', 'exams.id', '=', 'subject_exams.exam_id')
                    ->join('subjects', 'subject_exams.subject_id', '=', 'subjects.id')
                    ->join('teachers', 'exams.teacher_id', '=', 'teachers.id')
                    ->join('type_exams', 'exams.type_exam', '=', 'type_exams.id')
                    ->where('subject_exams.subject_id', $subject->id)
                    ->where('grade_exams.grade_id', $grade_id)
                    ->where('exams.semester', session('semester'))
                    ->where('exams.academic_year', session('academic_year'))
                    ->where('exams.is_active', TRUE)
                    ->count();
            }         

            return view('components.course.sections', compact('subject', 'grade_id', 'gradeSubject', 'course', 'ebook', 'assessment', 'assessmentActive', 'material'));
        } catch (Exception $err) {
            dd($err);
            return redirect()->back()->with('error', 'Failed to load sections.');
        }
    }

    public function setCourseId(Request $request)
    {
        session(['id_course' => $request->id]);

        return response()->json(['success' => true]);
    }

    public function changeEbook(Request $request){
        try{
            $ebook = Ebook::where('id', $request->ebook_id)->first();
            
            if ($request->hasFile('upload_file')) {
                $filePath = $request->file('upload_file')->store('ebooks', 'public');
            }
    
            $ebook = Ebook::where('id', $request->ebook_id)->first();
     
            if($ebook !== null){
                if (Storage::exists($ebook->file_path)) {
                    Storage::delete('public/' . $ebook->file_path);
                }
            }
        
            $data = [
                'file_path' => $filePath,
            ];
     
            Ebook::where('id', $request->ebook_id)->update($data);
     
           session()->flash('success_change_ebook');
           return redirect()->back();
        }
        catch(Exception $err){
           dd($err);
        }
    }

    public function deleteEbook(Request $request){
        try{
            Ebook::where('id', $request->ebook_id)->delete();
            session()->flash('succes_delete_ebook');
            return redirect()->back();
        }
        catch(Exception $err){
            dd($err);
        }
    }
}
