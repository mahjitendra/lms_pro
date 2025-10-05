<?php

namespace App\Services\AI\NLP;

use App\Exceptions\AI\PredictionException;
use App\Helpers\AIHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuestionAnsweringService
{
    /**
     * Find an answer to a question within a given context.
     *
     * @param string $question
     * @param string $context
     * @return array
     * @throws PredictionException
     */
    public function findAnswerInContext(string $question, string $context): array
    {
        $endpoint = AIHelper::getMLServiceEndpoint('find-answer'); // Assuming this endpoint exists
        $secretKey = config('ai.services.python_ml.secret');

        $response = Http::withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout(20)
            ->post($endpoint, [
                'question' => $question,
                'context' => $context,
            ]);

        if ($response->failed()) {
            Log::error("Question Answering service request failed.", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new PredictionException("The Question Answering service is currently unavailable.");
        }

        $responseData = $response->json();
        if ($responseData['status'] !== 'success') {
            throw new PredictionException("Failed to find an answer: " . $responseData['message']);
        }

        return $responseData['data']; // e.g., ['answer' => '...', 'score' => 0.92, ...]
    }
}