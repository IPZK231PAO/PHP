<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
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

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
