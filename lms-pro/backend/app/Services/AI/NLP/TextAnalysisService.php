<?php

namespace App\Services\AI\NLP;

use App\Exceptions\AI\PredictionException;
use App\Helpers\AIHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextAnalysisService
{
    /**
     * Perform text analysis on a given text.
     *
     * @param string $text
     * @param array $features The specific analyses to perform (e.g., ['tokens', 'entities'])
     * @return array
     * @throws PredictionException
     */
    public function analyze(string $text, array $features): array
    {
        $endpoint = AIHelper::getMLServiceEndpoint('analyze-text'); // Assuming this endpoint exists
        $secretKey = config('ai.services.python_ml.secret');

        $response = Http::withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout(20)
            ->post($endpoint, [
                'text' => $text,
                'features' => $features,
            ]);

        if ($response->failed()) {
            Log::error("Text analysis service request failed.", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new PredictionException("The text analysis service is currently unavailable.");
        }

        $responseData = $response->json();
        if ($responseData['status'] !== 'success') {
            throw new PredictionException("Text analysis failed: " . $responseData['message']);
        }

        return $responseData['data'];
    }
}