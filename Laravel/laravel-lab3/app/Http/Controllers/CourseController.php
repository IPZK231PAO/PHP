<?php
namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return Course::with('enrollments.student', 'lecturer')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'lecturer_id' => 'required|exists:lecturers,id',
            'credits' => 'required|integer|min:1',
        ]);

        return Course::create($request->all());
    }

    public function show(Course $course)
    {
        return $course->load('enrollments.student', 'grades.student', 'lecturer');
    }

    public function update(Request $request, Course $course)
    {
        $course->update($request->all());
        return $course;
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(null, 204);
    }
}