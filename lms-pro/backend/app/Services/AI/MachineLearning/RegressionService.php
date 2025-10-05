<?php

namespace App\Services\AI\MachineLearning;

use App\Exceptions\AI\PredictionException;
use App\Helpers\AIHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegressionService
{
    /**
     * Predict a continuous value for a given set of features.
     *
     * @param array $features
     * @return array
     * @throws PredictionException
     */
    public function predictValue(array $features): array
    {
        $endpoint = AIHelper::getMLServiceEndpoint('predict-value'); // Assuming this endpoint exists
        $secretKey = config('ai.services.python_ml.secret');

        $response = Http::withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout(15)
            ->post($endpoint, ['features' => $features]);

        if ($response->failed()) {
            Log::error("Regression service request failed.", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new PredictionException("The regression service is currently unavailable.");
        }

        $responseData = $response->json();
        if ($responseData['status'] !== 'success') {
            throw new PredictionException("Regression prediction failed: " . $responseData['message']);
        }

        return $responseData['data']; // e.g., ['predicted_value' => 87.5]
    }
}