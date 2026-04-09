<?php

namespace Database\Factories;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FacultyFactory extends Factory
{
    protected $model = Faculty::class;

    public function definition(): array
    {
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName();
        $departments = ['Computer Science', 'Mathematics', 'Physics', 'Chemistry', 'Biology', 'Engineering'];
        $statuses = ['Active', 'Inactive', 'Pending', 'On Leave'];
        
        return [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'name' => $first_name . ' ' . $last_name,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'department' => $this->faker->randomElement($departments),
            'educational_background' => $this->faker->sentence(),
            'specialization' => $this->faker->words(3, true),
            'qualification' => $this->faker->randomElement(['PhD', 'Master', 'Bachelor']),
            'experience_years' => $this->faker->numberBetween(1, 30),
            'joining_date' => $this->faker->date(),
            'date_hired' => $this->faker->date(),
            'status' => $this->faker->randomElement($statuses),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
            'postal_code' => $this->faker->postcode(),
            'bio' => $this->faker->paragraph(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Indicate that the faculty is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Active',
        ]);
    }

    /**
     * Indicate that the faculty is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Inactive',
        ]);
    }

    /**
     * Indicate that the faculty is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Pending',
        ]);
    }
}