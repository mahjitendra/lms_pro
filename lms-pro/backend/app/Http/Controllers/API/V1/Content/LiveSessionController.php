<?php

namespace App\Http\Controllers\API\V1\Content;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\StoreLiveSessionRequest; // To be created
use App\Models\Content\LiveSession;
use App\Services\Content\LiveSessionService;
use Illuminate\Http\JsonResponse;

class LiveSessionController extends Controller
{
    protected $liveSessionService;

    public function __construct(LiveSessionService $liveSessionService)
    {
        $this->liveSessionService = $liveSessionService;
    }

    /**
     * Display a listing of upcoming or past live sessions.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $sessions = $this->liveSessionService->getAllSessions();
        return ResponseHelper::success($sessions);
    }

    /**
     * Store a newly created live session in storage.
     *
     * @param StoreLiveSessionRequest $request
     * @return JsonResponse
     */
    public function store(StoreLiveSessionRequest $request): JsonResponse
    {
        $session = $this->liveSessionService->createSession($request->validated());
        return ResponseHelper::success($session, 'Live session scheduled successfully.', 201);
    }

    /**
     * Display the specified live session.
     *
     * @param LiveSession $liveSession
     * @return JsonResponse
     */
    public function show(LiveSession $liveSession): JsonResponse
    {
        return ResponseHelper::success($liveSession);
    }

    /**
     * Update the specified live session in storage.
     *
     * @param StoreLiveSessionRequest $request
     * @param LiveSession $liveSession
     * @return JsonResponse
     */
    public function update(StoreLiveSessionRequest $request, LiveSession $liveSession): JsonResponse
    {
        $updatedSession = $this->liveSessionService->updateSession($liveSession, $request->validated());
        return ResponseHelper::success($updatedSession, 'Live session updated successfully.');
    }

    /**
     * Start the specified live session.
     *
     * @param LiveSession $liveSession
     * @return JsonResponse
     */
    public function startSession(LiveSession $liveSession): JsonResponse
    {
        try {
            $result = $this->liveSessionService->startSession($liveSession);
            return ResponseHelper::success($result, 'Live session started successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to start live session: ' . $e->getMessage());
        }
    }

    /**
     * End the specified live session.
     *
     * @param LiveSession $liveSession
     * @return JsonResponse
     */
    public function endSession(LiveSession $liveSession): JsonResponse
    {
        try {
            $result = $this->liveSessionService->endSession($liveSession);
            return ResponseHelper::success($result, 'Live session ended successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to end live session: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified live session from storage.
     *
     * @param LiveSession $liveSession
     * @return JsonResponse
     */
    public function destroy(LiveSession $liveSession): JsonResponse
    {
        $this->liveSessionService->deleteSession($liveSession);
        return ResponseHelper::success(null, 'Live session cancelled successfully.', 204);
    }
}