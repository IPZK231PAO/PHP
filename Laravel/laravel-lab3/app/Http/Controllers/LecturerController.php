<?php
namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    // Виведення всіх викладачів
    public function index()
    {
        $lecturers = Lecturer::all();
        return view('lecturers', compact('lecturers')); // Завантажуємо шаблон lecturers.blade.php
    }

    // Створення нового викладача
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:lecturers,email',
            'department' => 'required|string|max:100',
        ]);

        // Створення викладача
        Lecturer::create($request->all());

        // Перенаправлення з повідомленням про успіх
        return redirect()->route('lecturers.index')->with('success', 'Lecturer created successfully');
    }

    // Показати конкретного викладача
    public function show(Lecturer $lecturer)
    {
        return response()->json($lecturer);
    }

    // Оновлення викладача
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

    // Видалення викладача
    public function destroy(Lecturer $lecturer)
    {
        // Видаляємо всі записи з таблиці grades, що посилаються на цього викладача
        $lecturer->grades()->delete(); // Припускається, що у вас є зв'язок grades в моделі Lecturer
    
        // Тепер можна видаляти викладача
        $lecturer->delete();
    
        return redirect()->route('lecturers.index')->with('success', 'Lecturer deleted successfully');
    }
    
}
