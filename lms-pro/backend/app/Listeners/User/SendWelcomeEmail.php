<?php

namespace App\Listeners\User;

use App\Events\User\UserRegistered;
use App\Jobs\Notification\SendEmailJob;
use App\Mail\WelcomeMail; // Assuming this Mailable exists
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener for the UserRegistered event.
 * Implements ShouldQueue to ensure the email is sent asynchronously.
 */
class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\User\UserRegistered  $event
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;
        Log::info("Handling UserRegistered event for User ID: {$user->id}. Preparing to send welcome email.");

        // Create a new Mailable instance for the welcome email
        $welcomeEmail = new WelcomeMail($user);

        // Dispatch our generic SendEmailJob to queue the email.
        // This is better than calling Mail::send() directly in the listener,
        // as it keeps the listener lightweight and leverages the robust job queue.
        SendEmailJob::dispatch($user->email, $welcomeEmail);

        Log::info("Dispatched SendEmailJob for welcome email to User ID: {$user->id}.");
    }
}