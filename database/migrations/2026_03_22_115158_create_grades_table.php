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
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->string('subject');
            $table->string('assignment_name')->nullable();
            $table->enum('grade_type', ['quiz', 'exam', 'assignment', 'project', 'recitation', 'final'])->default('assignment');
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('letter_grade')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('semester', ['1st', '2nd', 'summer'])->default('1st');
            $table->string('academic_year')->nullable();
            $table->foreignId('graded_by')->constrained('users')->onDelete('cascade');
            $table->date('graded_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};