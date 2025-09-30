<?php

namespace App\Jobs\Notification;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

// In a real project, you would use a package to interact with FCM or APNs.
// e.g., Kreait\Firebase, or a dedicated Laravel package.

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $user;
    public $title;
    public $message;
    public $data;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\User $user The recipient user.
     * @param string $title The notification title.
     * @param string $message The notification body.
     * @param array $data Optional data payload.
     */
    public function __construct(User $user, string $title, string $message, array $data = [])
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting SendPushNotificationJob for User ID: {$this->user->id}");

        // 1. Get the user's device tokens from the database.
        $deviceTokens = $this->user->deviceTokens()->pluck('token')->toArray();

        if (empty($deviceTokens)) {
            Log::info("User ID: {$this->user->id} has no registered device tokens. Skipping push notification.");
            return;
        }

        // 2. This is where you would integrate with a service like Firebase Cloud Messaging (FCM).
        // For example, using the kreait/laravel-firebase package:
        //
        // $messaging = app('firebase.messaging');
        // $notification = Notification::create($this->title, $this->message);
        //
        // $message = CloudMessage::new()
        //     ->withNotification($notification)
        //     ->withData($this->data);
        //
        // $messaging->sendMulticast($message, $deviceTokens);

        Log::info("Simulating sending push notification to " . count($deviceTokens) . " devices for User ID: {$this->user->id}");
        Log::info("Title: {$this->title} | Message: {$this->message}");

        // Simulate the API call
        sleep(1);

        Log::info("SendPushNotificationJob completed for User ID: {$this->user->id}");
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("SendPushNotificationJob failed for User ID: {$this->user->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}