<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Add class_name if missing
            if (!Schema::hasColumn('grades', 'class_name')) {
                $table->string('class_name')->nullable()->after('subject');
            }
            
            // Add status if missing
            if (!Schema::hasColumn('grades', 'status')) {
                $table->enum('status', ['passed', 'failed', 'pending'])->nullable()->after('final_grade');
            }
            
            // Add academic_year if missing
            if (!Schema::hasColumn('grades', 'academic_year')) {
                $table->string('academic_year')->nullable()->after('status');
            }
            
            // Add semester if missing
            if (!Schema::hasColumn('grades', 'semester')) {
                $table->string('semester')->nullable()->after('academic_year');
            }
            
            // Add graded_by if missing
            if (!Schema::hasColumn('grades', 'graded_by')) {
                $table->foreignId('graded_by')
                    ->nullable()
                    ->after('semester')
                    ->constrained('users')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn([
                'class_name',
                'status',
                'academic_year',
                'semester'
            ]);
            
            if (Schema::hasColumn('grades', 'graded_by')) {
                $table->dropForeign(['graded_by']);
                $table->dropColumn('graded_by');
            }
        });
    }
};