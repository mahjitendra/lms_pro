<?php

namespace App\Http\Controllers\API\V1\AI\MachineLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\ClassificationRequest; // To be created
use App\Services\AI\MachineLearning\ClassificationService;
use Illuminate\Http\JsonResponse;

class ClassificationController extends Controller
{
    protected $classificationService;

    public function __construct(ClassificationService $classificationService)
    {
        $this->classificationService = $classificationService;
    }

    /**
     * Predict a class label based on input features.
     *
     * @param ClassificationRequest $request
     * @return JsonResponse
     */
    public function predict(ClassificationRequest $request): JsonResponse
    {
        try {
            $features = $request->input('features');
            $result = $this->classificationService->predictClass($features);

            return ResponseHelper::success($result, 'Classification prediction made successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during classification prediction: ' . $e->getMessage());
        }
    }
}