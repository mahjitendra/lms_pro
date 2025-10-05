<?php

namespace App\Services\AI\MachineLearning;

use App\Exceptions\AI\PredictionException;
use App\Helpers\AIHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SentimentAnalysisService
{
    /**
     * Analyze the sentiment of a given text by calling the Python ML service.
     *
     * @param string $text
     * @return array
     * @throws PredictionException
     */
    public function analyze(string $text): array
    {
        $endpoint = AIHelper::getMLServiceEndpoint('analyze-sentiment'); // Assuming this endpoint exists
        $secretKey = config('ai.services.python_ml.secret');

        $response = Http::withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout(15) // 15-second timeout
            ->post($endpoint, ['text' => $text]);

        if ($response->failed()) {
            Log::error("Sentiment analysis service request failed.", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new PredictionException("The sentiment analysis service is currently unavailable.");
        }

        $responseData = $response->json();
        if ($responseData['status'] !== 'success') {
            throw new PredictionException("Sentiment analysis failed: " . $responseData['message']);
        }

        return $responseData['data']; // e.g., ['sentiment' => 'positive', 'confidence' => 0.98]
    }
}