<?php

namespace App\Listeners\AI;

use App\Events\AI\PredictionGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener for the PredictionGenerated event.
 * Implements ShouldQueue to handle tasks asynchronously.
 */
class HandlePredictionGenerated implements ShouldQueue
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
     * @param  \App\Events\AI\PredictionGenerated  $event
     */
    public function handle(PredictionGenerated $event): void
    {
        $prediction = $event->prediction;
        Log::info("Handling PredictionGenerated event for Prediction ID: {$prediction->id}.");

        // --- Example Action 1: Detailed Logging ---
        // Log the full prediction details for auditing or debugging.
        Log::channel('predictions')->info('New prediction generated', [
            'prediction_id' => $prediction->id,
            'model_id' => $prediction->model_id,
            'user_id' => $prediction->user_id,
            'input' => $prediction->input,
            'output' => $prediction->output,
        ]);

        // --- Example Action 2: Analytics Hook ---
        // Here you could dispatch another job or call a service to update an analytics dashboard
        // to track model usage and performance in real-time.
        // AnalyticsService::track('prediction_made', [
        //     'model_id' => $prediction->model_id,
        //     'user_id' => $prediction->user_id,
        // ]);
    }
}