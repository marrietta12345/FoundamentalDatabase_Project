<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'employee_id' => 'TCH001',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@brightsphere.edu',
                'department' => 'Computer Science',
                'specialization' => 'Artificial Intelligence',
                'qualification' => 'PhD in Computer Science',
                'experience_years' => 10,
                'status' => 'active',
            ],
            [
                'employee_id' => 'TCH002',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@brightsphere.edu',
                'department' => 'Mathematics',
                'specialization' => 'Applied Mathematics',
                'qualification' => 'PhD in Mathematics',
                'experience_years' => 8,
                'status' => 'active',
            ],
            [
                'employee_id' => 'TCH003',
                'first_name' => 'Michael',
                'last_name' => 'Williams',
                'email' => 'michael.williams@brightsphere.edu',
                'department' => 'Data Science',
                'specialization' => 'Machine Learning',
                'qualification' => 'MS in Data Science',
                'experience_years' => 5,
                'status' => 'active',
            ],
        ];

        foreach ($teachers as $teacher) {
            // Create user first
            $user = User::create([
                'name' => $teacher['first_name'] . ' ' . $teacher['last_name'],
                'email' => $teacher['email'],
                'password' => bcrypt('password'),
                'role' => 'teacher',
            ]);
            
            // Create teacher with user_id
            Teacher::create(array_merge($teacher, ['user_id' => $user->id]));
        }
    }
}