<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Add multiple users at once
        $users = [
            [
                'first_name' => 'John',
                'middle_name' => 'M',
                'last_name' => 'Doe',
                'name' => 'John M Doe',
                'email' => 'john.doe@example.com',
                'role' => 'student',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jane',
                'middle_name' => 'D',
                'last_name' => 'Smith',
                'name' => 'Jane D Smith',
                'email' => 'jane.smith@example.com',
                'role' => 'student',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Robert',
                'middle_name' => 'J',
                'last_name' => 'Johnson',
                'name' => 'Robert J Johnson',
                'email' => 'robert.j@example.com',
                'role' => 'instructor',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Maria',
                'middle_name' => 'L',
                'last_name' => 'Garcia',
                'name' => 'Maria L Garcia',
                'email' => 'maria.g@example.com',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'David',
                'middle_name' => 'W',
                'last_name' => 'Brown',
                'name' => 'David W Brown',
                'email' => 'david.b@example.com',
                'role' => 'student',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
        
        $this->command->info('5 users added successfully!');
    }
}