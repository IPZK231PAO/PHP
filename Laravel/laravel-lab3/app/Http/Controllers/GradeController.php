<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    // Виведення всіх оцінок
    public function index()
    {
        $grades = Grade::all(); // Отримуємо всі оцінки
        $students = Student::all();
        $courses = Course::all();
        $lecturers = Lecturer::all();
    
        return view('grades', compact('grades', 'students', 'courses', 'lecturers'));
    }

    // Форма для додавання нової оцінки
    public function create()
    {
        $students = Student::all();
        $courses = Course::all();
        $lecturers = Lecturer::all();

        return view('grades.create', compact('students', 'courses', 'lecturers'));
    }

    // Збереження нової оцінки
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'score' => 'required|numeric|min:0|max:100',
            'exam_date' => 'required|date',
        ]);

        Grade::create($request->all());
        return redirect()->route('grades.index')->with('success', 'Grade created successfully');
    }

    // Отримання оцінки для редагування
    public function edit(Grade $grade)
    {
        return response()->json($grade); // Повертаємо оцінку у форматі JSON
    }

    // Оновлення оцінки
    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'score' => 'required|numeric|min:0|max:100',
            'exam_date' => 'required|date',
        ]);

        $grade->update($request->all());
        return redirect()->route('grades.index')->with('success', 'Grade updated successfully');
    }

    // Видалення оцінки
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully');
    }
}
