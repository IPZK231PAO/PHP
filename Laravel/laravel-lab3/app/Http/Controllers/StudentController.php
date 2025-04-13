<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Вивести список всіх студентів
    public function index()
    {
        $students = Student::all();
        return view('students', compact('students'));
    }

    // Зберегти нового студента
    public function store(Request $request)
    {
        \Log::info('Received request:', $request->all()); // Логуємо запит

        // Валідація
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:students',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:20',
        ]);

        // Створення нового студента
        Student::create($request->all());

        // Перенаправлення на список студентів з повідомленням
        return redirect()->route('students.index')->with('success', 'Student created successfully');
    }

    // Показати одного студента
    public function show(Student $student)
    {
        return $student->load('enrollments.course', 'grades');
    }

    // Оновлення студента
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'email' => 'sometimes|email|unique:students,email,' . $student->id,
        ]);

        $student->update($request->all());
        return redirect()->route('students.index')->with('success', 'Student updated successfully');
    }

    // Видалити студента
    public function destroy(Student $student)
{
    // Видалити записи в таблиці enrollments, пов'язані з цим студентом
    $student->enrollments()->delete();

    // Тепер можна видалити студента
    $student->delete();

    return redirect()->route('students.index')->with('success', 'Student deleted successfully');
}
}