<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer; // Не забувайте імпортувати модель Lecturer
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Відображення всіх курсів
    public function index()
    {
        $courses = Course::all();
        $lecturers = Lecturer::all(); // Отримуємо всіх лекторів
        return view('courses', compact('courses', 'lecturers')); // Передаємо в шаблон курси та лекторів
    }

    // Створення нового курсу
    public function store(Request $request)
    {
        // Валідація вхідних даних
        $request->validate([
            'title' => 'required|string|max:100',
            'lecturer_id' => 'required|exists:lecturers,id', // Перевірка на існування лектора
            'credits' => 'required|integer|min:1',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Створення нового курсу
        Course::create([
            'title' => $request->title,
            'lecturer_id' => $request->lecturer_id,
            'credits' => $request->credits,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('courses.store')->with('success', 'Course created successfully');
    }
    // Показати конкретний курс
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }
    public function edit(Course $course)
    {
        $lecturers = Lecturer::all(); // Отримуємо всіх лекторів
        return view('courses.edit', compact('course', 'lecturers')); // Передаємо курс та лекторів у шаблон
    }
    

    // Оновлення курсу
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'lecturer_id' => 'required|exists:lecturers,id',
            'credits' => 'required|integer|min:1',
            'description' => 'nullable|string', // <- ЦЬОГО НЕМАЄ В ФОРМІ
            'start_date' => 'required|date',     // <- ЦЬОГО НЕМАЄ
            'end_date' => 'required|date|after_or_equal:start_date', // <- І ЦЬОГО
        ]);
        
    
        // Оновлення курсу
        $course->update([
            'title' => $request->title,
            'lecturer_id' => $request->lecturer_id,
            'credits' => $request->credits,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    
        return redirect()->route('courses.store')->with('success', 'Course created successfully');

    }
    // Видалення курсу
    public function destroy(Course $course)
    {
        // Спочатку видаляємо залежні записи з таблиці grades
        $course->grades()->delete(); // Припускаємо, що є зв'язок між Course та Grade (grades)
    
        // Тепер видаляємо сам курс
        $course->delete();
    
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully');

    }
    
}
