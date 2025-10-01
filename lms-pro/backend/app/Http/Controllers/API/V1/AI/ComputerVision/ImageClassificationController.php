<?php

namespace App\Http\Controllers\API\V1\AI\ComputerVision;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\ImageClassificationRequest; // To be created
use App\Services\AI\ComputerVision\ImageClassificationService;
use Illuminate\Http\JsonResponse;

class ImageClassificationController extends Controller
{
    protected $imageClassificationService;

    public function __construct(ImageClassificationService $imageClassificationService)
    {
        $this->imageClassificationService = $imageClassificationService;
    }

    /**
     * Analyze an image to classify its content.
     *
     * @param ImageClassificationRequest $request
     * @return JsonResponse
     */
    public function analyze(ImageClassificationRequest $request): JsonResponse
    {
        try {
            $imageFile = $request->file('image');
            $topK = $request->input('top_k', 5);

            $result = $this->imageClassificationService->processImageClassification($imageFile, $topK);

            return ResponseHelper::success($result, 'Image classification job dispatched successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during image classification processing: ' . $e->getMessage());
        }
    }
}