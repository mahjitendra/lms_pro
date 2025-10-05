<?php

namespace App\Services\AI\ComputerVision;

use App\Jobs\AI\ProcessImageJob;
use App\Models\Content\Image;
use App\Services\Storage\LocalStorageService;
use Illuminate\Http\UploadedFile;

class ObjectDetectionService
{
    protected $storageService;

    public function __construct(LocalStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Process an object detection request.
     *
     * @param UploadedFile $imageFile
     * @param float $confidenceThreshold
     * @return Image
     */
    public function processObjectDetection(UploadedFile $imageFile, float $confidenceThreshold): Image
    {
        // 1. Store the uploaded file.
        $filePath = $this->storageService->store($imageFile, 'image_analysis/input');

        // 2. Create a database record to track the task.
        // We store the parameters needed for the job in the 'options' column.
        $image = Image::create([
            'file_path' => $filePath,
            'original_filename' => $imageFile->getClientOriginalName(),
            'processing_status' => 'pending',
            'analysis_type' => 'object_detection',
            'options' => ['threshold' => $confidenceThreshold],
        ]);

        // 3. Dispatch the job for asynchronous processing.
        ProcessImageJob::dispatch($image);

        // 4. Return the initial record.
        return $image;
    }
}