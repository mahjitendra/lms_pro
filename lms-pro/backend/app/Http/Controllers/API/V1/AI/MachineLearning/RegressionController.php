<?php

namespace App\Http\Controllers\API\V1\AI\MachineLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\RegressionRequest; // To be created
use App\Services\AI\MachineLearning\RegressionService;
use Illuminate\Http\JsonResponse;

class RegressionController extends Controller
{
    protected $regressionService;

    public function __construct(RegressionService $regressionService)
    {
        $this->regressionService = $regressionService;
    }

    /**
     * Predict a continuous value based on input features.
     *
     * @param RegressionRequest $request
     * @return JsonResponse
     */
    public function predict(RegressionRequest $request): JsonResponse
    {
        try {
            $features = $request->input('features');
            $result = $this->regressionService->predictValue($features);

            return ResponseHelper::success($result, 'Regression prediction made successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during regression prediction: ' . $e->getMessage());
        }
    }
}