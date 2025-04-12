<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained(); 
            $table->foreignId('course_id')->constrained(); 
            $table->date('enrollment_date');
            $table->string('status')->default('active'); 
            $table->timestamps();
        });
    }
    
};
