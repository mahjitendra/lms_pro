<?php

namespace App\Jobs\AI;

use App\Exceptions\AI\PredictionException;
use App\Models\Content\Image; // Assuming an Image model exists
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $image;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Content\Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting ProcessImageJob for Image ID: {$this->image->id}");

        if (!Storage::disk('local')->exists($this->image->file_path)) {
            throw new \Exception("Image file not found at path: {$this->image->file_path}");
        }

        $this->image->update(['processing_status' => 'processing']);

        $mlServiceUrl = config('ai.services.python_ml.url');

        $response = Http::timeout(120) // 2-minute timeout
            ->post("{$mlServiceUrl}/analyze-image", [
                'image_path' => Storage::disk('local')->path($this->image->file_path),
                'analysis_types' => ['classification', 'ocr'], // Example analysis types
            ]);

        if ($response->failed()) {
            throw new PredictionException("Failed to connect to ML service for image analysis. Status: " . $response->status());
        }

        $responseData = $response->json();

        if ($responseData['status'] === 'success') {
            $this->image->update([
                'analysis_data' => $responseData['data'],
                'processing_status' => 'completed',
            ]);

            Log::info("ProcessImageJob completed successfully for Image ID: {$this->image->id}");
        } else {
            throw new PredictionException("ML service returned an error during image analysis: " . $responseData['message']);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->image->update(['processing_status' => 'failed']);

        Log::error("ProcessImageJob failed for Image ID: {$this->image->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}