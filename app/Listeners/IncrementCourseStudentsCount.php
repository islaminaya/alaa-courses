<?php

namespace App\Listeners;

use App\Events\UserEnrolled;

class IncrementCourseStudentsCount
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserEnrolled $event): void
    {
        $event->course->increment('students_count');
    }
}
