<?php

namespace App\Jobs\AI;

use App\Events\AI\VideoProcessed;
use App\Exceptions\AI\PredictionException;
use App\Models\Content\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * The video instance.
     *
     * @var \App\Models\Content\Video
     */
    public $video;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Content\Video $video
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting ProcessVideoJob for Video ID: {$this->video->id}");

        // Ensure the video file exists before proceeding
        if (!Storage::disk('local')->exists($this->video->file_path)) {
            throw new \Exception("Video file not found at path: {$this->video->file_path}");
        }

        $this->video->update(['processing_status' => 'processing']);

        $mlServiceUrl = config('ai.services.python_ml.url');

        $response = Http::timeout(600) // 10-minute timeout for video processing
            ->post("{$mlServiceUrl}/analyze-video", [
                'video_path' => Storage::disk('local')->path($this->video->file_path),
                'analysis_types' => ['transcription', 'object_detection'], // Example analysis types
            ]);

        if ($response->failed()) {
            throw new PredictionException("Failed to connect to ML service for video analysis. Status: " . $response->status());
        }

        $responseData = $response->json();

        if ($responseData['status'] === 'success') {
            // Processing was successful.
            // Update the video record with the analysis data.
            $this->video->update([
                'analysis_data' => $responseData['data'], // Store transcription, detected objects, etc.
                'processing_status' => 'completed',
            ]);

            // Dispatch an event to notify the application
            VideoProcessed::dispatch($this->video);

            Log::info("ProcessVideoJob completed successfully for Video ID: {$this->video->id}");
        } else {
            throw new PredictionException("ML service returned an error during video analysis: " . $responseData['message']);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // The job has failed after all retries.
        $this->video->update(['processing_status' => 'failed']);

        Log::error("ProcessVideoJob failed for Video ID: {$this->video->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}