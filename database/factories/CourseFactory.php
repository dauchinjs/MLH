<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $created_at = fake()->dateTimeBetween('-1 year', 'now');
        $updated_at = fake()->dateTimeBetween($created_at, 'now');
        $title = fake()->sentence(2);

        return [
            'title' => rtrim($title, '.'),
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
