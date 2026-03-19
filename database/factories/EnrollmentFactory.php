<?php

namespace Database\Factories;

use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
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
            'status' => fake()->randomElement(CourseStatus::cases())->value,
            'enrolled_at' => fake()->dateTime(),
            'completed_at' => fake()->dateTime(),
            'progress_percentage' => fake()->numberBetween(0, 100), ,
        ];
    }
}
