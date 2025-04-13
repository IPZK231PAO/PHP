<?php
namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{

    public function index()
    {
        $lecturers = Lecturer::all();
        return view('lecturers', compact('lecturers')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:lecturers,email',
            'department' => 'required|string|max:100',
        ]);

        
        Lecturer::create($request->all());

        return redirect()->route('lecturers.index')->with('success', 'Lecturer created successfully');
    }

    public function show(Lecturer $lecturer)
    {
        return response()->json($lecturer);
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:lecturers,email,' . $lecturer->id,
            'department' => 'required|string|max:100',
        ]);

        $lecturer->update($request->all());
        return redirect()->route('lecturers.index')->with('success', 'Lecturer updated successfully');
    }

 
    public function destroy(Lecturer $lecturer)
    {
    
        $lecturer->grades()->delete(); 
    
        $lecturer->delete();
    
        return redirect()->route('lecturers.index')->with('success', 'Lecturer deleted successfully');
    }
    
}
