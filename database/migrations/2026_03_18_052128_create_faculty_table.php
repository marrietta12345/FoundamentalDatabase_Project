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
        Schema::create('faculty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('faculty_id')->unique()->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->text('educational_background')->nullable();
            $table->string('specialization')->nullable();
            $table->string('qualification')->nullable();
            $table->integer('experience_years')->default(0);
            $table->date('joining_date')->nullable();
            $table->date('date_hired')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'On Leave', 'Retired', 'Resigned'])->default('Pending');
            $table->string('profile_photo')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('bio')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->enum('employment_type', ['Full-time', 'Part-time', 'Adjunct', 'Visiting', 'Emeritus', 'Contractual'])->default('Full-time');
            $table->string('employee_id')->nullable();
            $table->string('office_location')->nullable();
            $table->string('office_hours')->nullable();
            $table->json('publications')->nullable();
            $table->json('research_interests')->nullable();
            $table->json('awards')->nullable();
            $table->json('certifications')->nullable();
            $table->json('languages')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for faster queries
            $table->index('department');
            $table->index('status');
            $table->index('faculty_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty');
    }
};