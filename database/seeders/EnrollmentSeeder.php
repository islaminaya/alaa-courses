<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id');
        $courses = Course::pluck('id');

        foreach ($users as $userId) {
            foreach ($courses->random(min(3, $courses->count())) as $courseId) {
                Enrollment::create([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                ]);
            }
        }
    }
}
