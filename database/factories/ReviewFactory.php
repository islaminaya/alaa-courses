<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomElement(User::pluck('id')->toArray()),
            'course_id' => fake()->randomElement(Course::pluck('id')->toArray()),
            'rating' => fake()->numberBetween(1, 5),
            'review' => fake()->randomElement([null, fake()->realText()]),
        ];
    }
}
