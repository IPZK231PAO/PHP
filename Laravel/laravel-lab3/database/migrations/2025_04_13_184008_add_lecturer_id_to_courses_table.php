<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDescriptionInCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('description')->default('')->change(); // Додаємо значення за замовчуванням
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('description')->nullable()->change(); // Відновлюємо nullable, якщо потрібно
        });
    }
}