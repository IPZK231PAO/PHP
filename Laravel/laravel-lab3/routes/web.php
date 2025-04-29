<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth.web'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    Route::resource('students', StudentController::class)->only(['index', 'show']);
    Route::resource('courses', CourseController::class)->only(['index', 'show']);
    Route::resource('enrollments', EnrollmentController::class)->only(['index', 'show']);
    Route::resource('results', ResultController::class)->only(['index', 'show']);

    Route::middleware(['role:manager,admin'])->group(function () {
        Route::resource('students', StudentController::class)->except(['index', 'show', 'destroy']);
        Route::resource('courses', CourseController::class)->except(['index', 'show', 'destroy']);
        Route::resource('enrollments', EnrollmentController::class)->except(['index', 'show', 'destroy']);
        Route::resource('grades', GradeController::class)->except(['destroy']);
        Route::resource('results', ResultController::class)->except(['index', 'show', 'destroy']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::delete('enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
        Route::delete('grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
        Route::delete('results/{result}', [ResultController::class, 'destroy'])->name('results.destroy');
        Route::resource('lecturers', LecturerController::class);
    });

    Route::get('results/course/{course}', [ResultController::class, 'byCourse'])->name('results.by_course');
});