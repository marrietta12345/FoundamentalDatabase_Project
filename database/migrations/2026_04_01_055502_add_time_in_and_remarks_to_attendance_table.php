<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance', 'time_in')) {
                $table->time('time_in')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('attendance', 'remarks')) {
                $table->text('remarks')->nullable()->after('time_in');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropColumn(['time_in', 'remarks']);
        });
    }
};