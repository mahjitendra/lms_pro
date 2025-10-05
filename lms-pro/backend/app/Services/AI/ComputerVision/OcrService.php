<?php

namespace App\Services\AI\ComputerVision;

use App\Jobs\AI\ProcessImageJob;
use App\Models\Content\Image;
use App\Services\Storage\LocalStorageService;
use Illuminate\Http\UploadedFile;

class OcrService
{
    protected $storageService;

    public function __construct(LocalStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Process an OCR request.
     *
     * @param UploadedFile $imageFile
     * @return Image
     */
    public function processOcr(UploadedFile $imageFile): Image
    {
        // 1. Store the uploaded file.
        $filePath = $this->storageService->store($imageFile, 'image_analysis/input');

        // 2. Create a database record to track the task.
        $image = Image::create([
            'file_path' => $filePath,
            'original_filename' => $imageFile->getClientOriginalName(),
            'processing_status' => 'pending',
            'analysis_type' => 'ocr',
        ]);

        // 3. Dispatch the job for asynchronous processing.
        ProcessImageJob::dispatch($image);

        // 4. Return the initial record.
        return $image;
    }
}