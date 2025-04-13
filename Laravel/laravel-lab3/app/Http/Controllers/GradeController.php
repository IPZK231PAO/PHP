<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $query = Grade::query()->with(['student', 'course', 'lecturer']);

        if ($request->has('student_id') && $request->student_id != '') {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('lecturer_id') && $request->lecturer_id != '') {
            $query->where('lecturer_id', $request->lecturer_id);
        }

        if ($request->has('score_min') && $request->score_min != '') {
            $query->where('score', '>=', $request->score_min);
        }

        if ($request->has('score_max') && $request->score_max != '') {
            $query->where('score', '<=', $request->score_max);
        }

        if ($request->has('exam_date') && $request->exam_date != '') {
            $query->whereDate('exam_date', $request->exam_date);
        }

        $grades = $query->paginate($perPage);
        $students = Student::all();
        $courses = Course::all();
        $lecturers = Lecturer::all();
    
        return view('grades', compact('grades', 'students', 'courses', 'lecturers'));
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