<?php

namespace App\Listeners;

use App\Events\Register;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class SendRegisterMail
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
    public function handle(Register $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }
}
