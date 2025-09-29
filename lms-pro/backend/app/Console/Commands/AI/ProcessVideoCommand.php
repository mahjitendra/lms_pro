<?php

namespace App\Console\Commands\AI;

use App\Jobs\AI\ProcessVideoJob;
use App\Models\Content\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessVideoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:process-video {video_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job to process a video for analysis (e.g., transcription, object detection)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $videoId = $this->argument('video_id');

        $this->info("Attempting to dispatch processing job for Video ID: {$videoId}...");

        try {
            // Find the video from the database
            $video = Video::findOrFail($videoId);

            // Dispatch the job to the queue
            ProcessVideoJob::dispatch($video);

            $this->info("Successfully dispatched processing job for video: {$video->title}.");
            Log::info("Dispatched processing job for Video ID: {$video->id}");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->error("Error: Video not found. Please check the provided ID.");
            Log::error("ProcessVideoCommand: Video not found.", ['video_id' => $videoId, 'error' => $e->getMessage()]);
            return 1; // Return a non-zero status code for failure
        } catch (\Exception $e) {
            $this->error("An unexpected error occurred: " . $e->getMessage());
            Log::error("ProcessVideoCommand: Failed to dispatch job.", ['video_id' => $videoId, 'error' => $e->getMessage()]);
            return 1;
        }

        return 0; // Success
    }
}