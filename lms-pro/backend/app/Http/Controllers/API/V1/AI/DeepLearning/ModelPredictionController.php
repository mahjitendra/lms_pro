<?php

namespace App\Http\Controllers\API\V1\AI\DeepLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\ModelPredictionRequest; // To be created
use App\Services\AI\DeepLearning\ModelPredictionService;
use Illuminate\Http\JsonResponse;

class ModelPredictionController extends Controller
{
    protected $predictionService;

    public function __construct(ModelPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Make a prediction using a specified model.
     *
     * @param ModelPredictionRequest $request
     * @return JsonResponse
     */
    public function predict(ModelPredictionRequest $request): JsonResponse
    {
        try {
            $modelId = $request->input('model_id');
            $inputData = $request->input('input_data');

            $prediction = $this->predictionService->makePrediction($modelId, $inputData);

            return ResponseHelper::success($prediction, 'Prediction generated successfully.');

        } catch (\App\Exceptions\AI\ModelNotFoundException $e) {
            return ResponseHelper::notFound($e->getMessage());
        } catch (\App\Exceptions\AI\PredictionException $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('An unexpected error occurred during prediction: ' . $e->getMessage());
        }
    }
}