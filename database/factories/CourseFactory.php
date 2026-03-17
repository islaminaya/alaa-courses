<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $categories = ['cardiology', 'neurology', 'pediatrics', 'surgery', 'radiology'];
        $price = $this->faker->randomElement([0, 49, 99, 149, 199, 299]);

        return [
            'title' => $this->faker->randomElement([
                'Advanced Cardiovascular Imaging',
                'Pediatric Emergency Medicine',
                'Neurological Assessment Techniques',
                'Surgical Suturing Masterclass',
                'Diagnostic Radiology Essentials',
                'Clinical Cardiology Update',
                'Pediatric Growth & Development',
                'Neurosurgery Fundamentals',
                'Orthopedic Surgery Techniques',
                'MRI Interpretation Course',
            ]),
            'description' => $this->faker->paragraph(3),
            'category' => $this->faker->randomElement($categories),
            'image' => null, // You can add image URLs here
            'price' => $price,
            'original_price' => $price > 0 ? $price + $this->faker->randomElement([20, 50, 100]) : null,
            'duration' => $this->faker->randomElement(['4 weeks', '6 weeks', '8 weeks', '12 hours', '20 hours']),
            'students_count' => $this->faker->numberBetween(50, 5000),
            'rating' => $this->faker->randomFloat(2, 4.0, 5.0),
            'is_new' => $this->faker->boolean(20),
            'status' => 'active',
            'objectives' => json_encode([
                'Understand core medical concepts',
                'Apply theoretical knowledge in practice',
                'Develop clinical decision-making skills',
            ]),
            'requirements' => [
                'Basic medical knowledge',
                'Active medical license (for some courses)',
            ],
        ];
    }
}
