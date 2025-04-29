<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{protected $fillable = [
    'first_name', 'last_name', 'email', 'date_of_birth', 'phone',
];
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->date('date_of_birth');
            $table->string('phone');
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
}
