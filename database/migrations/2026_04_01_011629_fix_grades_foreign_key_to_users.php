<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing foreign key
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });
        
        // Add new foreign key referencing users table
        Schema::table('grades', function (Blueprint $table) {
            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Drop the new foreign key
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });
        
        // Restore old foreign key (if needed)
        Schema::table('grades', function (Blueprint $table) {
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
        });
    }
};