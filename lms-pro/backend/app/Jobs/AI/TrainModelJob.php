<?php

namespace App\Jobs\AI;

use App\Events\AI\ModelTrainingCompleted;
use App\Exceptions\AI\PredictionException;
use App\Models\AI\AIModel;
use App\Models\AI\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrainModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The AI model instance.
     *
     * @var \App\Models\AI\AIModel
     */
    public $model;

    /**
     * The Dataset instance.
     *
     * @var \App\Models\AI\Dataset
     */
    public $dataset;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\AI\AIModel $model
     * @param \App\Models\AI\Dataset $dataset
     */
    public function __construct(AIModel $model, Dataset $dataset)
    {
        $this->model = $model;
        $this->dataset = $dataset;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting TrainModelJob for Model ID: {$this->model->id}");

        // Update model status to 'training'
        $this->model->update(['status' => 'training']);

        // This is where you would call the external Python ML service.
        // The URL and endpoint would come from a config file.
        $mlServiceUrl = config('ai.services.python_ml.url');

        $response = Http::timeout(300) // 5-minute timeout
            ->post("{$mlServiceUrl}/train", [
                'model_id' => $this->model->id,
                'model_type' => $this->model->type,
                'dataset_path' => $this->dataset->file_path,
                'hyperparameters' => $this->model->hyperparameters, // Assuming this exists
            ]);

        if ($response->failed()) {
            // The request to the ML service failed.
            // The 'failed' method below will be called.
            throw new PredictionException("Failed to connect to ML service. Status: " . $response->status());
        }

        $responseData = $response->json();

        if ($responseData['status'] === 'success') {
            // Training was successful.
            // Update model with new version, metrics, etc.
            $this->model->update([
                'version' => $responseData['data']['new_version'],
                'metrics' => $responseData['data']['metrics'],
                'status' => 'available',
            ]);

            // Dispatch an event to notify the application
            ModelTrainingCompleted::dispatch($this->model);

            Log::info("TrainModelJob completed successfully for Model ID: {$this->model->id}");
        } else {
            // The ML service returned an error.
            throw new PredictionException("ML service returned an error: " . $responseData['message']);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // The job has failed after all retries.
        // Update the model status to 'failed'.
        $this->model->update(['status' => 'failed']);

        Log::error("TrainModelJob failed for Model ID: {$this->model->id}", [
            'error' => $exception->getMessage(),
        ]);

        // Optionally, send a notification to an administrator.
    }
}