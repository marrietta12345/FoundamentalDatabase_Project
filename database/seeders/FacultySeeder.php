<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 faculty members
        $faculty = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@brightsphere.edu',
                'department' => 'Computer Science',
                'educational_background' => 'PhD in Computer Science',
                'specialization' => 'Artificial Intelligence',
                'experience_years' => 10,
                'status' => 'Active',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@brightsphere.edu',
                'department' => 'Mathematics',
                'educational_background' => 'PhD in Mathematics',
                'specialization' => 'Applied Mathematics',
                'experience_years' => 8,
                'status' => 'Active',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Williams',
                'email' => 'michael.williams@brightsphere.edu',
                'department' => 'Physics',
                'educational_background' => 'PhD in Physics',
                'specialization' => 'Quantum Mechanics',
                'experience_years' => 12,
                'status' => 'Active',
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'email' => 'emily.brown@brightsphere.edu',
                'department' => 'Chemistry',
                'educational_background' => 'PhD in Chemistry',
                'specialization' => 'Organic Chemistry',
                'experience_years' => 6,
                'status' => 'Active',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Jones',
                'email' => 'david.jones@brightsphere.edu',
                'department' => 'Biology',
                'educational_background' => 'PhD in Biology',
                'specialization' => 'Molecular Biology',
                'experience_years' => 9,
                'status' => 'Inactive',
            ],
        ];

        foreach ($faculty as $data) {
            // Create user first
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'department' => $data['department'],
            ]);

            // Create faculty record
            Faculty::create(array_merge($data, [
                'user_id' => $user->id,
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'joining_date' => now()->subYears(rand(1, 5)),
                'date_hired' => now()->subYears(rand(1, 5)),
            ]));
        }

        // Create additional random faculty using factory
        Faculty::factory()->count(10)->create();
    }
}