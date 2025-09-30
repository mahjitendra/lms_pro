<?php

namespace App\Listeners\AI;

use App\Events\AI\ModelTrainingCompleted;
use App\Jobs\Notification\SendPushNotificationJob;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener for the ModelTrainingCompleted event.
 * This class implements ShouldQueue to ensure the listener's tasks are handled asynchronously.
 */
class HandleModelTrainingCompleted implements ShouldQueue
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
     * @param \App\Events\AI\ModelTrainingCompleted $event
     */
    public function handle(ModelTrainingCompleted $event): void
    {
        $model = $event->model;
        Log::info("Handling ModelTrainingCompleted event for Model ID: {$model->id}. Model '{$model->name}' is now available.");

        // --- Example Action: Notify an Administrator ---
        // Find all admin users to notify them.
        $admins = User::where('role', 'admin')->get(); // This assumes a simple role column.

        foreach ($admins as $admin) {
            $title = 'Model Training Complete';
            $message = "The AI model '{$model->name}' (v{$model->version}) has finished training and is now available.";

            // Dispatch a job to send a push notification.
            // We could also dispatch a job to send an email.
            SendPushNotificationJob::dispatch($admin, $title, $message);

            Log::info("Dispatched notification to admin {$admin->name} for completed model training.");
        }
    }
}