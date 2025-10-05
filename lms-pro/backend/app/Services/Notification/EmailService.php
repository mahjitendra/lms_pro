<?php

namespace App\Services\Notification;

use App\Jobs\Notification\SendEmailJob;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling email notifications.
 */
class EmailService
{
    /**
     * Send an email by dispatching a job to the queue.
     *
     * @param User $recipient The user to send the email to.
     * @param Mailable $mailable The Mailable class instance (e.g., new WelcomeMail($user)).
     * @return void
     */
    public function send(User $recipient, Mailable $mailable): void
    {
        try {
            // Dispatch the generic SendEmailJob to handle the sending asynchronously.
            SendEmailJob::dispatch($recipient->email, $mailable)->onQueue('emails');

            Log::info("Dispatched email job to send " . get_class($mailable) . " to User ID: {$recipient->id}");
        } catch (\Exception $e) {
            Log::error("Failed to dispatch email job for User ID: {$recipient->id}", [
                'error' => $e->getMessage(),
            ]);
        }
    }
}