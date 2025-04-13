<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    
    public function index()
    {
        $grades = Grade::all(); 
        $students = Student::all();
        $courses = Course::all();
        $lecturers = Lecturer::all();
    
        return view('grades', compact('grades', 'students', 'courses', 'lecturers'));
    }


    public function create()
    {
        $students = Student::all();
        $courses = Course::all();
        $lecturers = Lecturer::all();

        return view('grades.create', compact('students', 'courses', 'lecturers'));
    }

 
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

  
    public function edit(Grade $grade)
    {
        return response()->json($grade); 
    }

   
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

   
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully');
    }
}
