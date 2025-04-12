<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Enrollment::with('student', 'course')->get();
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date',
        ]);
    
        return Enrollment::create($request->all());
    }
    
    public function show(Enrollment $enrollment)
    {
        return $enrollment->load('student', 'course');
    }
    
    public function update(Request $request, Enrollment $enrollment)
    {
        $enrollment->update($request->all());
        return $enrollment;
    }
    
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return response()->json(null, 204);
    }
    
}
