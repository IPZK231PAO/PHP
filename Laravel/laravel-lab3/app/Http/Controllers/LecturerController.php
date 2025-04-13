<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $query = Lecturer::query();

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

        if ($request->has('department') && $request->department != '') {
            $query->where('department', 'like', '%'.$request->department.'%');
        }

        $lecturers = $query->paginate($perPage);
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