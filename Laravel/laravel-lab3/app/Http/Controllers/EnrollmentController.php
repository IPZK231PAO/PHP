<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::all();
        $students = Student::all();
        $courses = Course::all();
        return view('enrollments', compact('enrollments', 'students', 'courses')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date',
        ]);

        Enrollment::create($request->all());
        return redirect()->route('enrollments.index')->with('success', 'Enrollment created successfully');
    }

    public function edit(Enrollment $enrollment)
    {
        return response()->json($enrollment);
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date',
        ]);

        $enrollment->update($request->all());
        return redirect()->route('enrollments.index')->with('success', 'Enrollment updated successfully');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('enrollments.index')->with('success', 'Enrollment deleted successfully');
    }
}
