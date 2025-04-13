<?php


namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    // Відображення всіх результатів
    public function index()
    {
        $students = Student::all();
        $courses = Course::all();
        $lecturers = Lecturer::all();
        $results = Result::all();
        return view('results', compact('results','students', 'courses', 'lecturers'));
    }

    // Створення нового результату
    public function create()
    {
        $students = Student::all();
        $courses = Course::all();
        $lecturers = Lecturer::all();
        return view('results.form', compact('students', 'courses', 'lecturers'));
    }

    // Збереження нового результату
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        Result::create($request->all());
        return redirect()->route('results.index')->with('success', 'Result created successfully');
    }

    // Показати результат
    public function show(Result $result)
    {
        return view('results.show', compact('result'));
    }



       // В ResultController замінюємо show на edit для API:
public function edit(Result $result)
{
    return response()->json($result);
}



    // Оновлення результату
    public function update(Request $request, Result $result)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $result->update($request->all());
        return redirect()->route('results.index')->with('success', 'Result updated successfully');
    }

    // Видалення результату
    public function destroy(Result $result)
    {
        $result->delete();
        return redirect()->route('results.index')->with('success', 'Result deleted successfully');
    }
}
