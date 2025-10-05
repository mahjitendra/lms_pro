<?php

namespace App\Services\Notification;

use App\Jobs\Notification\SendSmsJob; // Assuming this job would be created
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Service for handling SMS notifications via a third-party provider like Twilio.
 * This class is a placeholder to demonstrate a multi-channel notification strategy.
 */
class SMSService
{
    public function __construct()
    {
        // In a real application, you would initialize the SDK for your SMS provider here,
        // e.g., Twilio, using credentials from config('services.twilio').
    }

    /**
     * Send an SMS message by dispatching a job.
     *
     * @param User $recipient The user to send the SMS to.
     * @param string $message The text message to send.
     * @return void
     */
    public function send(User $recipient, string $message): void
    {
        // Check if the user has a verified phone number.
        if (empty($recipient->phone_number)) {
            Log::info("User ID {$recipient->id} has no phone number. Skipping SMS.");
            return;
        }

        try {
            // Dispatch a job to handle the sending asynchronously.
            // SendSmsJob::dispatch($recipient, $message)->onQueue('sms');

            Log::info("Dispatching SMS job to User ID: {$recipient->id} (simulation).");
        } catch (Exception $e) {
            Log::error("Failed to dispatch SMS job for User ID: {$recipient->id}", [
                'error' => $e->getMessage(),
            ]);
        }
    }
}