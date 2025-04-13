<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $query = Student::query();

        if ($request->has('id') && $request->id != '') {
            $query->where('id', $request->id);
        }

        if ($request->has('first_name') && $request->first_name != '') {
            $query->where('first_name', 'like', '%'.$request->first_name.'%');
        }

        if ($request->has('last_name') && $request->last_name != '') {
            $query->where('last_name', 'like', '%'.$request->last_name.'%');
        }

        if ($request->has('email') && $request->email != '') {
            $query->where('email', 'like', '%'.$request->email.'%');
        }

        if ($request->has('date_of_birth') && $request->date_of_birth != '') {
            $query->whereDate('date_of_birth', $request->date_of_birth);
        }

        if ($request->has('phone') && $request->phone != '') {
            $query->where('phone', 'like', '%'.$request->phone.'%');
        }

        $students = $query->paginate($perPage);
        return view('students', compact('students'));
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