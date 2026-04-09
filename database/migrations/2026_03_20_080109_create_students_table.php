<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('student_id')->unique()->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('program')->nullable();
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'Graduated', 'Suspended'])->default('Pending');
            $table->text('address')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->text('bio')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('program');
            $table->index('year_level');
            $table->index('status');
            $table->index('student_id');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};