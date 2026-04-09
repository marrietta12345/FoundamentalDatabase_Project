<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Add grade columns
            $table->decimal('prelim', 5, 2)->nullable()->after('subject');
            $table->decimal('midterm', 5, 2)->nullable()->after('prelim');
            $table->decimal('prefinal', 5, 2)->nullable()->after('midterm');
            $table->decimal('final_exam', 5, 2)->nullable()->after('prefinal');
            $table->decimal('final_grade', 5, 2)->nullable()->after('final_exam');
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['prelim', 'midterm', 'prefinal', 'final_exam', 'final_grade']);
        });
    }
};