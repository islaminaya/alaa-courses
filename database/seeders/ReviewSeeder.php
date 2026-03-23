<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach (Course::all() as $course) {
            $selectedUsers = $users->random(rand(3, 6));

            foreach ($selectedUsers as $user) {
                Review::factory()->create([
                    'course_id' => $course->id,
                    'user_id' => $user->id,
                ]);
            }

            $course->update([
                'rating' => Review::where('course_id', $course->id)->avg('rating'),
            ]);
        }
    }
}
