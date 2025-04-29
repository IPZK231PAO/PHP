<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{  protected $fillable = [
    'student_id',
    'course_id',
    'enrollment_date',
];
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->date('enrollment_date');
            $table->string('status')->default('active')->comment('active/completed/dropped');
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
}
