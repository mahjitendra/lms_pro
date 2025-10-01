<?php

namespace App\Http\Controllers\API\V1\Communication;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\Communication\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get the authenticated user's notifications.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 20);
        $notifications = $this->notificationService->getNotificationsForUser(Auth::user(), $limit);
        return ResponseHelper::success($notifications);
    }

    /**
     * Mark a specific notification as read.
     *
     * @param int $notificationId
     * @return JsonResponse
     */
    public function markAsRead(int $notificationId): JsonResponse
    {
        try {
            $this->notificationService->markAsRead(Auth::user(), $notificationId);
            return ResponseHelper::success(null, 'Notification marked as read.');
        } catch (\Exception $e) {
            return ResponseHelper::notFound('Notification not found or you are not authorized to access it.');
        }
    }

    /**
     * Mark all of the user's unread notifications as read.
     *
     * @return JsonResponse
     */
    public function markAllAsRead(): JsonResponse
    {
        $this->notificationService->markAllAsRead(Auth::user());
        return ResponseHelper::success(null, 'All notifications marked as read.');
    }
}