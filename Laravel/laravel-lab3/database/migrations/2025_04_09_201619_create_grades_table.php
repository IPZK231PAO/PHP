<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('grades', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained();
        $table->foreignId('course_id')->constrained();
        $table->foreignId('lecturer_id')->constrained();  
        $table->decimal('score', 5, 2); 
        $table->date('exam_date'); 
        $table->timestamps();
    });
}

};
