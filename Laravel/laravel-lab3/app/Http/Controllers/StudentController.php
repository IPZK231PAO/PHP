<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
  
    public function index()
    {
        $students = Student::all();
        return view('students', compact('students'));
    }

 
    public function store(Request $request)
    {
        \Log::info('Received request:', $request->all());

  
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:students',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:20',
        ]);

    
        Student::create($request->all());

      
        return redirect()->route('students.index')->with('success', 'Student created successfully');
    }

  
    public function show(Student $student)
    {
        return $student->load('enrollments.course', 'grades');
    }


    public function update(Request $request, Student $student)
    {
        $request->validate([
            'email' => 'sometimes|email|unique:students,email,' . $student->id,
        ]);

        $student->update($request->all());
        return redirect()->route('students.index')->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
{
  
    $student->enrollments()->delete();

    $student->delete();

    return redirect()->route('students.index')->with('success', 'Student deleted successfully');
}
}