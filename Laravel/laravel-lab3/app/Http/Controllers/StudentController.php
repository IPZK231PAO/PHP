<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return Student::with('enrollments.course', 'grades')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:students',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:20',
        ]);

        return Student::create($request->all());
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
        return $student;
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(null, 204);
    }
}