<style>
    .custom-modal {
        padding: ;
        background-color: #ffde9e;
        color: #000;
        border-radius: 86% 64% 74% 76% / 76% 80% 80% 94% ;
        border: 3px solid #ffcc00;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .input-custom {
        padding: 10px 45px;
        width: 100%;
        height: 50px;
        background-color: #fff3c0;
        color: #000;
        border-radius: 70% 60% 80% 70% / 70% 60% 80% 70%;
        border: 3px solid #ffde9e;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }
</style>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-orange elevation-4" style="background-color: #fff3c0">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center" style="background-color: #fff3c0;">
        <img loading="lazy" src="{{ asset('/images') }}/logo-school.png" class="img-fluid img-thumbnail" alt="Sample image" style="background-color: #fff3c0;">
    </a>

    <div class="sidebar">
        <div class="user-panel py-2 d-flex" style="margin-top: 3rem;">
            <div class="image">
                @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'parent' || session('role') == 'library')
                    <img loading="lazy" src="{{ asset('images/admin.png') }}" class="img-circle elevation-2" alt="">
                @else
                    @if ($profile == null)
                        <img loading="lazy" src="{{asset('images/admin.png') }}" class="img-circle elevation-2" alt="" style="width: 40px;height: 40px;">
                    @else
                        <img loading="lazy" src="{{ asset('storage/file/profile/' . $profile) }}" class="img-circle elevation-2"
                            alt="" style="width: 40px;height: 40px;">
                    @endif
                @endif
            </div>
            <div class="info">
                <a class="d-block brand-text" style="font-size: 1.2em;">
                    @if (session('role') == 'library')
                        Admin Perpustakaan
                    @else
                        {{ ucwords(strtolower(Str::words(session('name_user'), 2, ''))) }}
                    @endif
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="true">

                <!-- DASHBOARD -->
                @if (session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="{{ url('/superadmin/dashboard') }}"
                            class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
                            <i class="mr-2">
                                <img loading="lazy" src="{{ asset('images/dashboard.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Dashboard</p>
                        </a>
                    </li>
                @elseif (session('role') == 'admin')
                    <li class="nav-item">
                        <a href="{{ url('/admin/dashboard') }}"
                            class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/dashboard.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Dashboard</p>
                        </a>
                    </li>
                @elseif (session('role') == 'teacher')
                    <li class="nav-item">
                        <a href="{{ url('/teacher/dashboard') }}"
                            class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/dashboard.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Dashboard</p>
                        </a>
                    </li>
                @elseif (session('role') == 'student' || session('role') == 'parent')
                    <li class="nav-item">
                        <a href="{{ url('/' . session('role') . '/dashboard') }}"
                            class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/dashboard.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Dashboard</p>
                        </a>
                    </li>
                @elseif (session('role') == 'library')
                    <li class="nav-item">
                        <a href="{{ url('/' . session('role') . '/dashboard') }}"
                            class="nav-link {{ session('page') && session('page')->page == 'dashboard' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/dashboard.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Dashboard</p>
                        </a>
                    </li>
                @endif
                <!-- END DASHBOARD -->

                <!-- MASTER ACADEMICS -->
                @if (session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="{{ url('/superadmin/masterAcademics') }}"
                            class="nav-link {{ session('page') && session('page')->page == 'master academic' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/academic.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Master Academic</p>
                        </a>
                    </li>
                @elseif (session('role') == 'admin')
                    <li class="nav-item">
                        <a href="{{ url('/admin/masterAcademics') }}"
                            class="nav-link {{ session('page') && session('page')->page == 'master academic' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/academic.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Master Academic</p>
                        </a>
                    </li>
                @endif
                <!-- END MASTER ACADEMIC -->

                <!-- ATTENDENCE STUDENT -->
                @if (session('role') == 'superadmin' || session('role') == 'admin')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/attendances/"
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'attendance' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/attendance.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Attendance</p>
                        </a>
                    </li>
                @elseif (session('role') == 'teacher')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/dashboard/attendance/class/teacher"
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'attendance class teacher' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/attendance.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Attendance</p>
                        </a>
                    </li>
                @endif
                <!-- END ATTENDENCE -->

                <!-- SCHEDULE -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'schedules' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'schedules' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/schedules.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Schedule
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/schedules/all"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'all schedules' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Schedule</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/schedules/schools"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'academic schedule' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>School</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/schedules/grades"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'schedules grade' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Class</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                    <a href="/{{session('role')}}/schedules/midexams" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules mid exam' ? 'active' : '') : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Mid Exams</p>
                                    </a>
                                </li> --}}
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/schedules/finalexams"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'schedules final exam' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Final Exams</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/typeSchedules"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database type schedules' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Type Schedule
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif (session('role') == 'teacher')
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'schedules' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'schedules' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/schedules.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Schedule
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/schedules/all"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'all schedules' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Schedule</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/schools"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'academic schedule' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>School</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/schedules/grade"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'class schedule' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Class Teacher</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/schedules/subject"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'subject schedule' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Subject</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="/{{session('role')}}/dashboard/schedules/invigilater" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'schedules invigilater' ? 'active' : '') : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Invigilater</p>
                                </a>
                                </li> --}}
                                                <!-- <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/schedules/companion/{{ session('id_user') }}" class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'schedules assisstant' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Assisstant</p>
                                </a>
                                </li> -->
                        </ul>
                    </li>
                @elseif (session('role') == 'student' || session('role') == 'parent')
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'schedules' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'schedules' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/schedules.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Schedule
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/schools"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'academic schedule' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>School</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/schedules/grade"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'class schedule' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Class</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <!-- END SCHEDULE -->

                <!-- COURSE -->
                @if (session('role') == 'superadmin' || session('role') == 'admin')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/course/"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'course' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/course.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Course</p>
                        </a>
                    </li>
                @elseif (session('role') == 'teacher')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/course/"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'course' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/course.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Course</p>
                        </a>
                    </li>
                @elseif (session('role') == 'student' || session('role') == 'parent')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/course/"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'course' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/course.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Course</p>
                        </a>
                    </li>
                @endif
                <!-- COURSE -->

                <!-- GRADE -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/grades"
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'grades' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/grades.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold">Class Data</p>
                        </a>
                    </li>
                @elseif (session('role') == 'teacher')
                    <li class="nav-item">
                        <a href="/teacher/dashboard/grade"
                            class="nav-link d-flex align-items-center {{ session('page') && session('page')->child == 'class data' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/grades.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold">Class Data</p>
                        </a>
                    </li>
                @endif
                <!-- END GRADE -->

                <!-- SCORINGS -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/exams"
                            class="nav-link {{ session('page') && session('page')->page == 'scorings' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/scorings.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Assessment
                            </p>
                        </a>
                    </li>
                @elseif (session('role') == 'teacher')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/dashboard/exam/teacher"
                            class="nav-link {{ session('page') && session('page')->page == 'scorings' ? 'active' : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/scorings.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Assessment
                            </p>
                        </a>
                    </li>
                @elseif (session('role') == 'student' || session('role') == 'parent')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/dashboard/exam"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'scorings' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/scorings.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Assessment
                            </p>
                        </a>
                    </li>
                @endif
                <!-- END SCORINGS -->

                <!-- REPORT SCORE -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/reports/"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'reports' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/reports.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Report</p>
                        </a>
                    </li>
                @elseif (session('role') == 'teacher')
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'reports' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'reports' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/reports.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">Report
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/report/class/teacher"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'report class teacher' || session('page')->child == 'academic assessment report' || session('page')->child == 'summary of academic assessment' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Class Teacher</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/report/subject/teacher"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'report subject teacher' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Subject Teacher</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/report/remedial/teacher"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'remedial' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Remedial</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif (session('role') == 'parent' || session('role') == 'student')
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'reports' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'reports' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/reports.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Report
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/midreport" target=""
                                    rel="noopener noreferrer"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'mid report card' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mid Report Card</p>
                                </a>

                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/dashboard/report" target=""
                                    rel="noopener noreferrer"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'report card' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Report Card</p>
                                </a>

                            </li>
                        </ul>
                    </li>
                @endif
                <!-- END REPORT -->

                <!-- TEACHERS -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/teachers"
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database teachers' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/teacher.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Teachers
                            </p>
                        </a>
                    </li>
                @elseif (session('role') == 'teacher')
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'teacher' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'teacher' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/teacher.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            {{-- <i class="nav-icon fa-solid fa-chalkboard-user"></i> --}}
                            <p class="text-bold ">
                                Profile
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/teacher/dashboard/edit/teacher"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'spesifik teachers' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Edit</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/teacher/dashboard/detail/teacher"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'detail teachers' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Detail</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <!-- END TEACHERS -->


                <!-- STUDENTS -->
                @if (session('role') == 'admin')
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'students' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'students' ? 'active' : '') : '' }}">
                            {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                            {{-- <i class="nav-icon fa-solid fa-house"></i> --}}
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/student.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Students
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- <li class="nav-item">
                                <a href="/admin/dashboard" class="nav-link {{session('page') && session('page')->child? (session('page')->child == 'dashboard' ? 'active' : '') : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="text-bold ">Dashboard</p>
                                </a>
                                </li> --}}
                            <li class="nav-item">
                                <a href="/admin/list"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database students' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{-- <i class="fa-regular fa-database"></i> --}}
                                    <p>Data</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif (session('role') == 'superadmin')
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'students' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'students' ? 'active' : '') : '' }}">
                            {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                            {{-- <i class="nav-icon fa-solid fa-house"></i> --}}
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/student.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Students
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/superadmin/register"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'register students' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Register</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/superadmin/register/imports"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'imports' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Import</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/superadmin/list"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database students' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{-- <i class="fa-regular fa-database"></i> --}}
                                    <p>Data</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif (session('role') == 'parent')
                    <!-- <li class="nav-item {{ session('page') && session('page')->page && session('page')->page == 'students' ? 'menu-open' : '' }}">
                            <a href="/parent/dashboard/student/{{ session('id_user') }}" class="nav-link {{ session('page') && session('page')->child && session('page')->child == 'database students' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-person"></i>
                                <p>
                                    Relationships
                                </p>
                            </a>
                        </li> -->
                @endif
                <!-- END STUDENTS -->

                <!-- RELATIONS -->
                <!-- @if (session('role') == 'admin' || session('role') == 'superadmin')    
                    <li class="nav-item">
                            <a href="/{{ session('role') }}/relations" class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database relations' ? 'active' : '') : '' }}">
                            <i class="nav-icon fa-solid fa-person"></i>
                            {{-- <i class="nav-icon fa-solid fa-chalkboard-user"></i> --}}
                            <p>
                                Relations
                                <i class="fas fa-angle-left right"></i>
                            </p>
                            </a>
                    </li>
                @elseif (session('role') == 'student')
                <li class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'relations' ? 'menu-open' : '') : '' }}">
                    <a href="/student/dashboard/relation/{{ session('id_user') }}" class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database relations' ? 'active' : '') : '' }}">
                    <i class="nav-icon fa-solid fa-person"></i>
                    {{-- <i class="nav-icon fa-solid fa-chalkboard-user"></i> --}}
                    <p>
                        Relations
                        <i class="fas fa-angle-left right"></i>
                    </p>
                    </a>
                </li>
                @endif -->
                <!-- END RELATIONS -->

                <!-- SUBJECT -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <!-- <li class="nav-item">
                            <a href="/{{ session('role') }}/subjects" class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database subjects' ? 'active' : '') : '' }}">
                            <i class="">
                            <img loading="lazy" src="{{ asset('images/subject.png') }}"
                                class=""
                                alt="User Image"
                                style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p>Subject</p>
                            </a>
                        </li> -->
                    <li
                        class="nav-item {{ session('page') && session('page')->page ? (session('page')->page == 'subjects' ? 'menu-open' : '') : '' }}">
                        <a href="#"
                            class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'subjects' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/subject.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Subjects
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/subjects"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database subjects' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/majorSubjects"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database major subjects' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Major Subject</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/minorSubjects"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database minor subjects' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Minor Subject</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/supplementarySubjects"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database supplementary subjects' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Supplementary Subject</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/chineseHigher"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database chinese higher' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Chinese Higher</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/{{ session('role') }}/chineseLower"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database chinese lower' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Chinese Lower</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/monthlyActivities"
                                    class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database monthly activities' ? 'active' : '') : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Monthly Activities</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <!-- END SUBJECT -->

                <!-- ECA -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="/eca"
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database eca' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/eca.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Ekstra Culicular Academy
                            </p>
                        </a>
                    </li>
                @endif
                <!-- END ECA -->

                <!-- SCORE IN PARENT -->
                @if (session('role') == 'parent')
                    <!-- <li class="nav-item">
                            <a href="/{{ session('role') }}/dashboard/score" class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'scores' ? 'active' : '') : '' }}">
                            <i class="">
                            <img loading="lazy" src="{{ asset('images/subject.png') }}"
                                class=""
                                alt="User Image"
                                style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p>
                                Report Score
                            </p>
                            </a>
                        </li> -->
                @endif
                {{-- END SCORE PARENT --}}

                <!-- TYPE EXAM -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="/{{ session('role') }}/typeExams"
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database type exams' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/scorings.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold ">
                                Type Assessment
                            </p>
                        </a>
                    </li>
                @endif
                <!-- END TYPE EXAM -->

                <!-- Page Tutorial -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="{{ route('tutorials.index') }}"
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database tutorials' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/video-tutorial.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold">
                                Form Tutorial
                            </p>
                        </a>
                    </li>
                @endif
                <!-- END Page Tutorial -->

                <!-- Page letter -->
                @if (session('role') == 'admin' || session('role') == 'superadmin')
                    <li class="nav-item">
                        <a href="/letter"
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database letter' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/mail-box.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold">
                                Generate Letter
                            </p>
                        </a>
                    </li>
                @endif
                <!-- END Page letter -->


                {{-- @if (session('role') == 'student')
                <li class="nav-item">
                    <a href="/library-public" class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'library-public' ? 'active' : '') : '' }}">
                        <i class="">
                            <img loading="lazy" src="{{ asset('images/library.png') }}" class="" alt="User Image"
                                style="width: 32px; height: 32px; object-fit: cover;">
                        </i>
                        <p class="text-bold">
                            Perpustakaan
                        </p>
                    </a>
                </li>
                @endif --}}
                
                {{-- GREAT CARE --}}
                @if (session('role') != 'teacher' && session('role') != 'library')
                    <li class="nav-item">
                        <a 
                            @if (session('role') == 'admin' || session('role') == 'superadmin')
                                href="/cc"
                            @else
                                href="/customer-service"
                            @endif
                            class="nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'great care' ? 'active' : '') : '' }}">
                            <i class="">
                                <img loading="lazy" src="{{ asset('images/customer-service.png') }}" class="" alt="User Image"
                                    style="width: 32px; height: 32px; object-fit: cover;">
                            </i>
                            <p class="text-bold">
                                Great Care
                            </p>
                        </a>
                    </li>
                @endif

                {{-- CHANGE YEAR --}}
                @if (session('role') == 'superadmin' || session('role') == 'admin' || session('role') == 'teacher')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa-solid fa-academic"></i>
                            <p class="">
                                Change Academic Year
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ($academic_years as $year)
                                <li class="nav-item">
                                    <a class="nav-link {{ session('academic_year') ? ($year == session('academic_year') ? 'active' : '') : '' }}"
                                        onclick="setAcademicYear('{{ $year }}')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ $year }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                {{-- END CHANGE YEAR --}}

                {{-- CHANGE SEMESTER --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa-solid fa-academic"></i>
                            <p class="">
                                Choose Semester
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="javascript:void(0);"
                                    class="nav-link {{ session('semester') ? (session('semester') == '1' ? 'active' : '') : '' }}"
                                    onclick="setSemester(1)">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Semester 1</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0);"
                                    class="nav-link {{ session('semester') ? (session('semester') == '2' ? 'active' : '') : '' }}"
                                    onclick="setSemester(2)">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Semester 2</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- END  --}}

                @if (session('role') == 'parent' || session('role') == 'student')
                    <li class="nav-item">
                    <a href="" data-target="#modal-change-password " data-id="{{session('id_user')}}" data-toggle="modal"
                        class="change-password-btn-user nav-link {{ session('page') && session('page')->child ? (session('page')->child == 'database letter' ? 'active' : '') : '' }}">
                        <i class="">
                            <img loading="lazy" src="{{ asset('images/password.png') }}"
                                style="width: 32px; height: 32px; object-fit: cover;">
                        </i>
                        <p class="text-bold text-danger">
                            Change Password
                        </p>
                    </a>
                    </li>
                @endif

                @if (session('role') == 'superadmin')
                    <li class="nav-header">AUTHENTICATION</li>
                        <li class="nav-item">
                            <a href="/superadmin/users"
                                class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'user' ? 'active' : '') : '' }}">
                                <i class="fa-solid fa-user-secret nav-icon"></i>
                                <p>User</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                                <a href="{{ url('/superadmin/users/change-password') }}" class="nav-link {{ session('page') && session('page')->page ? (session('page')->page == 'admin' ? 'active' : '') : '' }}">
                                <i class="nav-icon fas fa-solid fa-lock"></i>
                                <p>Change my password</p>
                                </a>
                            </li> -->
                    </li>
                @endif
            </ul>
        </nav>
        
    </div>
</aside>

<div class="modal fade" id="modal-change-password"
    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal">
            <div class="modal-body">
                <form method="POST" id="change-password-form" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="cspassword" class="text-xl">Change  Your Password</label>
                        <input name="password" type="password" class="form-control input-custom" id="cspassword"
                        oninput="this.type='text'" onblur="this.type='password'">
                    </div>

                    <div class="form-group d-flex justify-content-center align-item-center text-center">
                        <button type="button" class="btn btn-secondary" style="border-radius:16px;" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="border-radius:16px;margin-left:3px;" >Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setSemester(semester) {
        fetch('/save-semester-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                body: JSON.stringify({
                    semester: semester
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to set semester. Please try again.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function setAcademicYear(year) {
        fetch('/save-academicyear-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                body: JSON.stringify({
                    year: year
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to change academic year. Please try again.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>

@if (session('role') == 'parent' || session('role') == 'student')
  <script>
    $(document).ready(function() {
        // Function to handle report link clicks
        function handleReportLink(e) {
            e.preventDefault();

            // Get the target URL
            const originalUrl = $(this).attr('href'); 

            // Determine the check URL based on the clicked link
            let checkUrl;
            if (originalUrl.includes('/midreport')) {
                checkUrl = originalUrl.replace('/midreport', '/check-midreport-access');
            } else if (originalUrl.includes('/report')) {
                checkUrl = originalUrl.replace('/report', '/check-report-access');
            }

            Swal.fire({
            title: 'Checking Access...',
            text: 'Please wait a moment.',
            mageUrl: '/images/happy.png', // pastikan path ini bisa diakses dari browser
            imageWidth: 100,
            imageHeight: 100,
            imageAlt: 'Custom image',
            customClass: {
                popup: 'custom-swal-style'
            },
            allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make an AJAX request to check access
            $.ajax({
                url: checkUrl,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'error') {
                        Swal.close(); 
                        Swal.fire({

                            title: 'Access Denied',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'custom-swal-style'
                            },
                        });
                    } else {
                        Swal.close();
                        window.location.href = originalUrl;
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close(); // Close loading
                    console.error('Error checking report access:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while checking report access. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Attach event handlers to report links
        $('a[href*="/dashboard/midreport"]').click(handleReportLink);
        $('a[href*="/dashboard/report"]').click(handleReportLink);
    });

    $(document).ready(function () {
        $(".change-password-btn-user").click(function () {
            var userId = $(this).data("id");

            // Pastikan route di-encode dengan benar
            var actionUrl = "{{ route('user.password', ':id') }}".replace(':id', userId);

            // Update form action
            $("#change-password-form").attr("action", actionUrl);
        });
    });
  </script>
@endif
