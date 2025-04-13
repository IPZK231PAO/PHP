<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $query = Result::query()->with(['student', 'course', 'lecturer']);

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

        $results = $query->paginate($perPage);
        $students = Student::all();
        $courses = Course::all();
        $lecturers = Lecturer::all();
        
        return view('results', compact('results', 'students', 'courses', 'lecturers'));
    }

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

    public function edit(Result $result)
    {
        return response()->json($result);
    }

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

    public function destroy(Result $result)
    {
        $result->delete();
        return redirect()->route('results.index')->with('success', 'Result deleted successfully');
    }
}