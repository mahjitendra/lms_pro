<?php

namespace App\Services\AI\ComputerVision;

use App\Jobs\AI\ProcessImageJob;
use App\Models\Content\Image; // Assuming a generic Image model for storing the uploaded file
use App\Services\Storage\LocalStorageService; // We'll use a local storage service for now
use Illuminate\Http\UploadedFile;

class FaceRecognitionService
{
    protected $storageService;

    public function __construct(LocalStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Process a face recognition request.
     *
     * @param UploadedFile $imageFile
     * @return Image
     */
    public function processFaceRecognition(UploadedFile $imageFile): Image
    {
        // 1. Store the uploaded file securely.
        // The storage service will handle generating a unique name and returning the path.
        $filePath = $this->storageService->store($imageFile, 'image_analysis/input');

        // 2. Create a database record for the image to track its status.
        // We can use a generic Image model or a dedicated ImageAnalysis model.
        $image = Image::create([
            'file_path' => $filePath,
            'original_filename' => $imageFile->getClientOriginalName(),
            'processing_status' => 'pending',
            'analysis_type' => 'face_recognition',
        ]);

        // 3. Dispatch a job to the queue for asynchronous processing.
        // We pass the Image model instance to the job.
        ProcessImageJob::dispatch($image);

        // 4. Return the initial record. The frontend can use this ID to poll for results.
        return $image;
    }
}