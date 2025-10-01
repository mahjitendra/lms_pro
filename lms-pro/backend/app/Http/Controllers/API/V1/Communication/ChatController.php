<?php

namespace App\Http\Controllers\API\V1\Communication;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\SendMessageRequest; // To be created
use App\Services\Communication\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Get the message history for a specific chat or conversation.
     * The `chat_id` could be a user ID for direct messages or a group ID.
     *
     * @param Request $request
     * @param int $chatId
     * @return JsonResponse
     */
    public function getHistory(Request $request, int $chatId): JsonResponse
    {
        try {
            // $this->authorize('view', Chat::find($chatId)); // Authorization
            $messages = $this->chatService->getMessageHistory(Auth::id(), $chatId);
            return ResponseHelper::success($messages);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Could not retrieve chat history: ' . $e->getMessage());
        }
    }

    /**
     * Send a new message.
     *
     * @param SendMessageRequest $request
     * @return JsonResponse
     */
    public function sendMessage(SendMessageRequest $request): JsonResponse
    {
        try {
            $recipientId = $request->input('recipient_id');
            $messageText = $request->input('message');
            $file = $request->file('attachment');

            $message = $this->chatService->sendMessage(Auth::user(), $recipientId, $messageText, $file);

            // The service should dispatch an event that gets broadcasted to the recipient
            return ResponseHelper::success($message, 'Message sent successfully.', 201);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to send message: ' . $e->getMessage());
        }
    }
}