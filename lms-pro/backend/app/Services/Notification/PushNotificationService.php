<?php

namespace App\Services\Notification;

use App\Jobs\Notification\SendPushNotificationJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling push notifications.
 */
class PushNotificationService
{
    /**
     * Send a push notification by dispatching a job to the queue.
     *
     * @param User $recipient The user to send the notification to.
     * @param string $title The title of the notification.
     * @param string $message The body of the notification.
     * @param array $data An optional data payload to send with the notification.
     * @return void
     */
    public function send(User $recipient, string $title, string $message, array $data = []): void
    {
        // Check if the user has any device tokens registered.
        if ($recipient->deviceTokens()->count() === 0) {
            Log::info("User ID {$recipient->id} has no device tokens. Skipping push notification.");
            return;
        }

        try {
            // Dispatch the generic job to handle the sending asynchronously.
            SendPushNotificationJob::dispatch($recipient, $title, $message, $data)->onQueue('notifications');

            Log::info("Dispatched push notification job for User ID: {$recipient->id}");
        } catch (\Exception $e) {
            Log::error("Failed to dispatch push notification job for User ID: {$recipient->id}", [
                'error' => $e->getMessage(),
            ]);
        }
    }
}