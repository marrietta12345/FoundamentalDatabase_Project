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
        Schema::table('courses', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('courses', 'class_code')) {
                $table->string('class_code')->nullable()->after('code');
            }
            
            if (!Schema::hasColumn('courses', 'class_name')) {
                $table->string('class_name')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['class_code', 'class_name']);
        });
    }
};