<?php

namespace App\Services\AI\ComputerVision;

use App\Jobs\AI\ProcessImageJob;
use App\Models\Content\Image;
use App\Services\Storage\LocalStorageService;
use Illuminate\Http\UploadedFile;

class ImageClassificationService
{
    protected $storageService;

    public function __construct(LocalStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Process an image classification request.
     *
     * @param UploadedFile $imageFile
     * @param int $topK
     * @return Image
     */
    public function processImageClassification(UploadedFile $imageFile, int $topK): Image
    {
        // 1. Store the uploaded file.
        $filePath = $this->storageService->store($imageFile, 'image_analysis/input');

        // 2. Create a database record to track the task.
        $image = Image::create([
            'file_path' => $filePath,
            'original_filename' => $imageFile->getClientOriginalName(),
            'processing_status' => 'pending',
            'analysis_type' => 'image_classification',
            'options' => ['top_k' => $topK],
        ]);

        // 3. Dispatch the job for asynchronous processing.
        ProcessImageJob::dispatch($image);

        // 4. Return the initial record.
        return $image;
    }
}