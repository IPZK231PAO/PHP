<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer; 
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function index()
    {
        $courses = Course::all();
        $lecturers = Lecturer::all(); 
        return view('courses', compact('courses', 'lecturers')); 
    }


    public function store(Request $request)
    {
       
        $request->validate([
            'title' => 'required|string|max:100',
            'lecturer_id' => 'required|exists:lecturers,id', 
            'credits' => 'required|integer|min:1',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);


        Course::create([
            'title' => $request->title,
            'lecturer_id' => $request->lecturer_id,
            'credits' => $request->credits,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('courses.store')->with('success', 'Course created successfully');
    }
   
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $lecturers = Lecturer::all(); 
        return view('courses.edit', compact('course', 'lecturers')); 
    }
    


    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'lecturer_id' => 'required|exists:lecturers,id',
            'credits' => 'required|integer|min:1',
            'description' => 'nullable|string', 
            'start_date' => 'required|date',    
            'end_date' => 'required|date|after_or_equal:start_date', 
        ]);
        
    
     
        $course->update([
            'title' => $request->title,
            'lecturer_id' => $request->lecturer_id,
            'credits' => $request->credits,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    
        return redirect()->route('courses.store')->with('success', 'Course created successfully');

    }

    public function destroy(Course $course)
    {
       
        $course->grades()->delete(); 
    
  
        $course->delete();
    
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully');

    }
    
}
