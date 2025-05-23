<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [UserController::class, 'login']);

Route::get('/get-data-students', [StudentController::class, 'index']);
Route::get('/get-detail-students/{userId}', [StudentController::class, 'student']);
Route::get('/get-reserve-book-students/{reserve}', [StudentController::class, 'studentReserve']);
