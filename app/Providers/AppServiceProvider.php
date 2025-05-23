<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\Master_academic;
use App\Models\Student;
use App\Models\Teacher;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin.sidebar', function ($view) {
            $academic_years = Master_academic::pluck('academic_year');
            if(session('role') == 'student') {
                $students = Student::where('user_id', session('id_user'))->value('profil');
                $view->with([
                    'academic_years' => $academic_years,
                    'profile' => $students,
                ]);
            } elseif(session('role') == 'teacher') {
                $teachers = Teacher::where('user_id', session('id_user'))->value('profil');
                $view->with([
                    'academic_years' => $academic_years,
                    'profile' => $teachers,
                ]);
            } else{
                $view->with([
                    'academic_years' => $academic_years,
                ]);
            }
        });

        Paginator::useBootstrap();
    }
}
