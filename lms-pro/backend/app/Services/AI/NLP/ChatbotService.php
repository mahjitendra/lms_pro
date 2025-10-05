<?php

namespace App\Services\AI\NLP;

use App\Exceptions\AI\PredictionException;
use App\Helpers\AIHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Get a response from the chatbot.
     *
     * @param string $message The user's new message.
     * @param array $history The previous conversation history.
     * @return array
     * @throws PredictionException
     */
    public function getResponse(string $message, array $history): array
    {
        $endpoint = AIHelper::getMLServiceEndpoint('chatbot-response'); // Assuming this endpoint exists
        $secretKey = config('ai.services.python_ml.secret');

        $response = Http::withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout(25)
            ->post($endpoint, [
                'message' => $message,
                'history' => $history,
            ]);

        if ($response->failed()) {
            Log::error("Chatbot service request failed.", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new PredictionException("The chatbot service is currently unavailable.");
        }

        $responseData = $response->json();
        if ($responseData['status'] !== 'success') {
            throw new PredictionException("Chatbot failed to generate a response: " . $responseData['message']);
        }

        return $responseData['data']; // e.g., ['response_text' => '...', 'conversation_history' => [...]]
    }
}