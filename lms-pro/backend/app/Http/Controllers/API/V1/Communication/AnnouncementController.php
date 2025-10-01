<?php

namespace App\Http\Controllers\API\V1\Communication;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\StoreAnnouncementRequest; // To be created
use App\Models\Communication\Announcement;
use App\Services\Communication\AnnouncementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    protected $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
        // Apply authorization middleware
        // $this->authorizeResource(Announcement::class, 'announcement');
    }

    /**
     * Display a listing of the announcements.
     * Can be filtered by course_id.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['course_id']);
        $announcements = $this->announcementService->getAllAnnouncements($filters);
        return ResponseHelper::success($announcements);
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @param StoreAnnouncementRequest $request
     * @return JsonResponse
     */
    public function store(StoreAnnouncementRequest $request): JsonResponse
    {
        $announcement = $this->announcementService->createAnnouncement($request->validated());
        return ResponseHelper::success($announcement, 'Announcement created and dispatched successfully.', 201);
    }

    /**
     * Display the specified announcement.
     *
     * @param Announcement $announcement
     * @return JsonResponse
     */
    public function show(Announcement $announcement): JsonResponse
    {
        return ResponseHelper::success($announcement);
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param StoreAnnouncementRequest $request
     * @param Announcement $announcement
     * @return JsonResponse
     */
    public function update(StoreAnnouncementRequest $request, Announcement $announcement): JsonResponse
    {
        $updatedAnnouncement = $this->announcementService->updateAnnouncement($announcement, $request->validated());
        return ResponseHelper::success($updatedAnnouncement, 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param Announcement $announcement
     * @return JsonResponse
     */
    public function destroy(Announcement $announcement): JsonResponse
    {
        $this->announcementService->deleteAnnouncement($announcement);
        return ResponseHelper::success(null, 'Announcement deleted successfully.', 204);
    }
}