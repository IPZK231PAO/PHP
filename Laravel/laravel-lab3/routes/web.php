<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\LecturerController;
Route::get('/', function () {
    return view('index');
});

Route::resource('students', StudentController::class);
Route::resource('courses', CourseController::class);
Route::resource('enrollments', EnrollmentController::class);
Route::resource('grades', GradeController::class);
Route::resource('results', ResultController::class);
Route::resource('lecturers', LecturerController::class);
