<?php
namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'score' => 'required|numeric|min:0|max:100',
            'exam_date' => 'required|date',
        ]);

        return Grade::create($request->all());
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'score' => 'sometimes|numeric|min:0|max:100',
        ]);

        $grade->update($request->all());
        return $grade;
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return response()->json(null, 204);
    }
}