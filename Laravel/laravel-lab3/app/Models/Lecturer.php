<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    public function up()
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('department');
            $table->timestamps();
        });
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
