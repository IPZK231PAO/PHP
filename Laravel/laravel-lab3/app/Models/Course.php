<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{protected $fillable = [
    'title', 
    'description', 
    'credits', 
    'start_date', 
    'end_date', 
    'lecturer_id' 
];
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('credits');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

   // Модель Course
        public function grades()
        {
            return $this->hasMany(Grade::class, 'course_id'); // зв'язок з таблицею grades
        }


    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
