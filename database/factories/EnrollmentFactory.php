<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement([1, 2]);

        return [
            'user_id' => User::all()->random()->id,
            'course_id' => Course::all()->random()->id,
            'status' => $status,
        ];
    }
}
