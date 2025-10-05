<?php

namespace App\Services\AI\DeepLearning;

use App\Exceptions\AI\ModelNotFoundException;
use App\Exceptions\AI\PredictionException;
use App\Helpers\AIHelper;
use App\Repositories\Contracts\AIModelRepositoryInterface;
use App\Repositories\Contracts\PredictionRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModelPredictionService
{
    protected $aiModelRepository;
    protected $predictionRepository;

    public function __construct(
        AIModelRepositoryInterface $aiModelRepository,
        PredictionRepositoryInterface $predictionRepository
    ) {
        $this->aiModelRepository = $aiModelRepository;
        $this->predictionRepository = $predictionRepository;
    }

    /**
     * Make a real-time prediction using a specified model.
     *
     * @param int $modelId
     * @param array $inputData
     * @return array The prediction result.
     * @throws ModelNotFoundException
     * @throws PredictionException
     */
    public function makePrediction(int $modelId, array $inputData): array
    {
        $model = $this->aiModelRepository->findById($modelId);
        if (!$model || $model->status !== 'available') {
            throw new ModelNotFoundException("AI Model with ID {$modelId} is not available for predictions.");
        }

        // 1. Call the external Python ML service.
        $endpoint = AIHelper::getMLServiceEndpoint('predict');
        $secretKey = config('ai.services.python_ml.secret');

        $response = Http::withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout(30) // 30-second timeout for predictions
            ->post($endpoint, [
                'model_path' => $model->file_path, // Assuming the model has a path to its file
                'input_data' => $inputData,
            ]);

        if ($response->failed()) {
            Log::error("Prediction service request failed for Model ID: {$modelId}", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new PredictionException("The prediction service is currently unavailable.");
        }

        $responseData = $response->json();
        if ($responseData['status'] !== 'success') {
            throw new PredictionException("Prediction failed: " . $responseData['message']);
        }

        $predictionOutput = $responseData['data'];

        // 2. Log the prediction event for auditing and analytics.
        $this->predictionRepository->create([
            'model_id' => $model->id,
            'user_id' => auth()->id(),
            'input' => $inputData,
            'output' => $predictionOutput,
        ]);

        // 3. Return the prediction output.
        return $predictionOutput;
    }
}