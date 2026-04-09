<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->decimal('prelim', 5, 2)->nullable();
            $table->decimal('midterm', 5, 2)->nullable();
            $table->decimal('prefinal', 5, 2)->nullable();
            $table->decimal('final_exam', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->dropColumn(['prelim', 'midterm', 'prefinal', 'final_exam', 'final_grade']);
        });
    }
};