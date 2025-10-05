<?php

namespace App\Services\AI\MachineLearning;

use App\Exceptions\AI\PredictionException;
use App\Helpers\AIHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClassificationService
{
    /**
     * Predict a class label for a given set of features.
     *
     * @param array $features
     * @return array
     * @throws PredictionException
     */
    public function predictClass(array $features): array
    {
        $endpoint = AIHelper::getMLServiceEndpoint('predict-class'); // Assuming this endpoint exists
        $secretKey = config('ai.services.python_ml.secret');

        $response = Http::withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout(15)
            ->post($endpoint, ['features' => $features]);

        if ($response->failed()) {
            Log::error("Classification service request failed.", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new PredictionException("The classification service is currently unavailable.");
        }

        $responseData = $response->json();
        if ($responseData['status'] !== 'success') {
            throw new PredictionException("Classification failed: " . $responseData['message']);
        }

        return $responseData['data']; // e.g., ['predicted_class' => 'high_risk', 'probabilities' => [...]]
    }
}