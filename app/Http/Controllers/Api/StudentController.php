<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(Student::orderBy('name', 'ASC')->get());
    }
    
    public function student($userId)
    {
        return response()->json(Student::where('user_id', $userId)->first());
    }
}
