<?php

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use App\Models\Review;

class UpdateAverageRating
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
    public function handle(ReviewSubmitted $event): void
    {
        $event->course->update([
            'rating' => Review::query()
                ->where('course_id', $event->course->id)
                ->average('rating'),
        ]);
    }
}
