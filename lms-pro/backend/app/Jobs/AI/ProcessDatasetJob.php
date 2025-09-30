<?php

namespace App\Jobs\AI;

use App\Exceptions\AI\PredictionException;
use App\Models\AI\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessDatasetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $dataset;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\AI\Dataset $dataset
     */
    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting ProcessDatasetJob for Dataset ID: {$this->dataset->id}");

        if (!Storage::disk('local')->exists($this->dataset->file_path)) {
            throw new \Exception("Dataset file not found at path: {$this->dataset->file_path}");
        }

        $this->dataset->update(['status' => 'processing']);

        $mlServiceUrl = config('ai.services.python_ml.url');

        $response = Http::timeout(300) // 5-minute timeout
            ->post("{$mlServiceUrl}/process-dataset", [
                'dataset_path' => Storage::disk('local')->path($this->dataset->file_path),
            ]);

        if ($response->failed()) {
            throw new PredictionException("Failed to connect to ML service for dataset processing. Status: " . $response->status());
        }

        $responseData = $response->json();

        if ($responseData['status'] === 'success') {
            // Update the dataset record with metadata from the processing step
            $this->dataset->update([
                'metadata' => $responseData['data']['metadata'], // e.g., column info, stats, validation results
                'status' => 'ready', // The dataset is now ready for training
            ]);

            Log::info("ProcessDatasetJob completed successfully for Dataset ID: {$this->dataset->id}");
        } else {
            throw new PredictionException("ML service returned an error during dataset processing: " . $responseData['message']);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->dataset->update(['status' => 'failed']);

        Log::error("ProcessDatasetJob failed for Dataset ID: {$this->dataset->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}