<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixCourseStudentStatusColumn extends Migration
{
    public function up()
    {
        Schema::table('course_student', function (Blueprint $table) {
            // If it's an enum, modify to string
            $table->string('status', 20)->default('active')->change();
            // Or if you want to keep as enum, add the values
            // $table->enum('status', ['active', 'pending', 'completed', 'dropped'])->default('active')->change();
        });
    }

    public function down()
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->string('status', 20)->change();
        });
    }
}