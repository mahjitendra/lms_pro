<?php

namespace App\Jobs\Course;

use App\Jobs\AI\ProcessVideoJob;
use App\Models\Content\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

// Example library for video processing. In a real project, you'd use something like FFMpeg.
// use FFMpeg;

class ProcessVideoUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
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
        Log::info("Starting ProcessVideoUploadJob for Video ID: {$this->video->id}");
        $this->video->update(['processing_status' => 'encoding']);

        // --- Video Encoding Logic ---
        // In a real application, you would use a library like FFMpeg to create
        // different versions of the video for adaptive streaming.
        // This is a complex process, so we will simulate it here.

        Log::info("Simulating video encoding for Video ID: {$this->video->id}...");
        sleep(10); // Simulate a long-running encoding process

        // After encoding, you would update the video record with paths to the different formats.
        $this->video->update([
            'formats' => [
                '1080p' => $this->video->file_path . '_1080p.m3u8',
                '720p' => $this->video->file_path . '_720p.m3u8',
                '480p' => $this->video->file_path . '_480p.m3u8',
            ],
            'processing_status' => 'encoded',
        ]);
        Log::info("Video encoding complete for Video ID: {$this->video->id}");

        // --- Dispatch AI Analysis Job ---
        // Now that the video is technically ready, we can dispatch another job for AI analysis.
        // This creates a nice, decoupled processing pipeline.
        Log::info("Dispatching AI analysis job for Video ID: {$this->video->id}");
        ProcessVideoJob::dispatch($this->video);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->video->update(['processing_status' => 'failed']);
        Log::error("ProcessVideoUploadJob failed for Video ID: {$this->video->id}", [
            'error' => $exception->getMessage(),
        ]);
    }
}