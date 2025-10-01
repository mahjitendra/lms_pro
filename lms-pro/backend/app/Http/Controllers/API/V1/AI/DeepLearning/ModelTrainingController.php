<?php

namespace App\Http\Controllers\API\V1\AI\DeepLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\ModelTrainingRequest; // To be created
use App\Services\AI\DeepLearning\ModelTrainingService;
use Illuminate\Http\JsonResponse;

class ModelTrainingController extends Controller
{
    protected $trainingService;

    public function __construct(ModelTrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    /**
     * Initiate a model training job.
     *
     * @param ModelTrainingRequest $request
     * @return JsonResponse
     */
    public function train(ModelTrainingRequest $request): JsonResponse
    {
        try {
            $modelId = $request->input('model_id');
            $datasetId = $request->input('dataset_id');
            $hyperparameters = $request->input('hyperparameters', []);

            $result = $this->trainingService->startTrainingJob($modelId, $datasetId, $hyperparameters);

            return ResponseHelper::success($result, 'Model training job has been successfully dispatched.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ResponseHelper::notFound('The specified model or dataset could not be found.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred while dispatching the training job: ' . $e->getMessage());
        }
    }
}