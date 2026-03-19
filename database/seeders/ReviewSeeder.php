<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        foreach (Course::pluck('id') as $course) {
            Review::factory(rand(3, 6))->create([
                'course_id' => $course,
            ]);
        }

        foreach (Course::all() as $course) {
            $course->update([
                'rating' => Review::where('course_id', $course->id)->average('rating'),
            ]);
        }
    }
}
