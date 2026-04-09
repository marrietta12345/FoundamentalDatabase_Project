<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'student',
        ]);

        // Optionally seed sample instructor
        $teacher = User::factory()->create([
            'first_name' => 'Sample',
            'last_name' => 'Teacher',
            'name' => 'Sample Teacher',
            'email' => 'teacher@example.com',
            'role' => 'teacher',
        ]);

        $course = \App\Models\Course::create([
            'code' => 'MATH101',
            'name' => 'Algebra I',
            'description' => 'Foundational algebra concepts',
            'progress' => 72,
            'teacher_id' => $teacher->id,
        ]);

        \App\Models\Assignment::create([
            'course_id' => $course->id,
            'title' => 'Homework 1',
            'description' => 'Complete exercises 1-10',
            'due_date' => now()->addDays(3)->format('Y-m-d'),
            'status' => 'pending',
        ]);

        \App\Models\ClassSchedule::create([
            'course_id' => $course->id,
            'title' => 'Algebra Practice',
            'date' => now()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'location' => 'Room 201',
        ]);

    }
}
