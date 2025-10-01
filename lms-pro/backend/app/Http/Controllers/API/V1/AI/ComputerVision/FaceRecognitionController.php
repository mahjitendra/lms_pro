<?php

namespace App\Http\Controllers\API\V1\AI\ComputerVision;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\FaceRecognitionRequest; // We will create this later
use App\Services\AI\ComputerVision\FaceRecognitionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaceRecognitionController extends Controller
{
    protected $faceRecognitionService;

    public function __construct(FaceRecognitionService $faceRecognitionService)
    {
        $this->faceRecognitionService = $faceRecognitionService;
    }

    /**
     * Analyze an image to detect and recognize faces.
     *
     * @param FaceRecognitionRequest $request
     * @return JsonResponse
     */
    public function analyze(FaceRecognitionRequest $request): JsonResponse
    {
        try {
            $imageFile = $request->file('image');

            // The service will handle storing the file and dispatching the job
            $result = $this->faceRecognitionService->processFaceRecognition($imageFile);

            return ResponseHelper::success($result, 'Face recognition job dispatched successfully.');

        } catch (\Exception $e) {
            // In a real app, you might want more specific exception handling
            return ResponseHelper::serverError('An error occurred during face recognition processing: ' . $e->getMessage());
        }
    }
}