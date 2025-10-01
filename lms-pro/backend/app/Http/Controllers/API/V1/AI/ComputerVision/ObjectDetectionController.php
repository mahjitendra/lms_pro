<?php

namespace App\Http\Controllers\API\V1\AI\ComputerVision;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\ObjectDetectionRequest; // To be created
use App\Services\AI\ComputerVision\ObjectDetectionService;
use Illuminate\Http\JsonResponse;

class ObjectDetectionController extends Controller
{
    protected $objectDetectionService;

    public function __construct(ObjectDetectionService $objectDetectionService)
    {
        $this->objectDetectionService = $objectDetectionService;
    }

    /**
     * Analyze an image to detect objects.
     *
     * @param ObjectDetectionRequest $request
     * @return JsonResponse
     */
    public function analyze(ObjectDetectionRequest $request): JsonResponse
    {
        try {
            $imageFile = $request->file('image');
            $confidenceThreshold = $request->input('threshold', 0.5);

            $result = $this->objectDetectionService->processObjectDetection($imageFile, $confidenceThreshold);

            return ResponseHelper::success($result, 'Object detection job dispatched successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during object detection processing: ' . $e->getMessage());
        }
    }
}