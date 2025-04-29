<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Grade extends Model


{
    use HasFactory;
    protected $fillable = [
    'student_id',
    'course_id',
    'lecturer_id',
    'score',
    'exam_date',
];
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('lecturer_id')->constrained();
            $table->decimal('score', 5, 2)->comment('Оцінка від 0 до 100');
            $table->date('exam_date');
            $table->timestamps();
        });
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
