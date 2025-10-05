<?php

namespace App\Services\AI\ComputerVision;

use App\Jobs\AI\ProcessVideoJob;
use App\Models\Content\Video; // Assuming a generic Video model
use App\Services\Storage\LocalStorageService;
use Illuminate\Http\UploadedFile;

class VideoAnalysisService
{
    protected $storageService;

    public function __construct(LocalStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Process a video analysis request.
     *
     * @param UploadedFile $videoFile
     * @param array $features
     * @return Video
     */
    public function processVideoAnalysis(UploadedFile $videoFile, array $features): Video
    {
        // 1. Store the uploaded video file.
        $filePath = $this->storageService->store($videoFile, 'video_analysis/input');

        // 2. Create a database record to track the analysis task.
        $video = Video::create([
            'file_path' => $filePath,
            'original_filename' => $videoFile->getClientOriginalName(),
            'processing_status' => 'pending',
            'analysis_type' => 'multi_feature_video',
            'options' => ['features' => $features], // Store the requested features
        ]);

        // 3. Dispatch the job for asynchronous processing.
        ProcessVideoJob::dispatch($video);

        // 4. Return the initial record.
        return $video;
    }
}