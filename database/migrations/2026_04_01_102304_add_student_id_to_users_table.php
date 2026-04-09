<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add student_id column if it doesn't exist
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->unique()->after('id');
            }
            
            // Add program column if it doesn't exist
            if (!Schema::hasColumn('users', 'program')) {
                $table->string('program')->nullable()->after('status');
            }
            
            // Add year_level column if it doesn't exist
            if (!Schema::hasColumn('users', 'year_level')) {
                $table->string('year_level')->nullable()->after('program');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'program', 'year_level']);
        });
    }
};