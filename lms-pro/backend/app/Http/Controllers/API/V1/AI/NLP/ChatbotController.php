<?php

namespace App\Http\Controllers\API\V1\AI\NLP;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\ChatbotRequest; // To be created
use App\Services\AI\NLP\ChatbotService;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Handle a message from a user and get a chatbot response.
     *
     * @param ChatbotRequest $request
     * @return JsonResponse
     */
    public function chat(ChatbotRequest $request): JsonResponse
    {
        try {
            $message = $request->input('message');
            // The conversation history allows for stateful conversation
            $history = $request->input('history', []);

            $result = $this->chatbotService->getResponse($message, $history);

            return ResponseHelper::success($result, 'Response generated successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred in the chatbot service: ' . $e->getMessage());
        }
    }
}