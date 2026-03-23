<?php

namespace App\Listeners;

use App\Mail\UserEnrolled;
use Illuminate\Support\Facades\Mail;

class SendUserEnrolledEmail
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
        Mail::to($event->user)->send(new UserEnrolled($event->course, $event->user));
    }
}
