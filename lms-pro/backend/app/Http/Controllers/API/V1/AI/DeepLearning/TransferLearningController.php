<?php

namespace App\Http\Controllers\API\V1\AI\DeepLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\TransferLearningRequest; // To be created
use App\Services\AI\DeepLearning\TransferLearningService;
use Illuminate\Http\JsonResponse;

class TransferLearningController extends Controller
{
    protected $transferLearningService;

    public function __construct(TransferLearningService $transferLearningService)
    {
        $this->transferLearningService = $transferLearningService;
    }

    /**
     * Initiate a transfer learning job.
     *
     * @param TransferLearningRequest $request
     * @return JsonResponse
     */
    public function fineTune(TransferLearningRequest $request): JsonResponse
    {
        try {
            $baseModelId = $request->input('base_model_id');
            $customDatasetId = $request->input('custom_dataset_id');
            $newModelName = $request->input('new_model_name');

            $result = $this->transferLearningService->startFineTuningJob(
                $baseModelId,
                $customDatasetId,
                $newModelName
            );

            return ResponseHelper::success($result, 'Transfer learning job has been successfully dispatched.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ResponseHelper::notFound('The specified base model or dataset could not be found.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred while dispatching the transfer learning job: ' . $e->getMessage());
        }
    }
}