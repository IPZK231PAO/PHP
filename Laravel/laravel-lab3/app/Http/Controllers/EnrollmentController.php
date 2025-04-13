<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $query = Enrollment::query()->with(['student', 'course']);

        if ($request->has('id') && $request->id != '') {
            $query->where('id', $request->id);
        }

        if ($request->has('student_id') && $request->student_id != '') {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('enrollment_date') && $request->enrollment_date != '') {
            $query->whereDate('enrollment_date', $request->enrollment_date);
        }

        $enrollments = $query->paginate($perPage);
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