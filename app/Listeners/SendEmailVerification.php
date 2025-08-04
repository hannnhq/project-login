<?php

namespace App\Listeners;

use App\Events\UserSignup;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailVerification
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
    public function handle(UserSignup $event)
    {
        $event->user->notify(new VerifyEmailNotification());
    }
}
