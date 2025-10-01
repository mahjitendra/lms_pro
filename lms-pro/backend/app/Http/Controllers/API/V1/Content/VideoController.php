<?php

namespace App\Http\Controllers\API\V1\Content;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\UploadVideoRequest; // To be created
use App\Models\Content\Video;
use App\Services\Content\VideoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    /**
     * Display a listing of all videos (with pagination).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $videos = $this->videoService->getAllVideos($request->per_page ?? 15);
        return ResponseHelper::success($videos);
    }

    /**
     * Handle the upload of a new video.
     *
     * @param UploadVideoRequest $request
     * @return JsonResponse
     */
    public function store(UploadVideoRequest $request): JsonResponse
    {
        try {
            $videoFile = $request->file('video');
            $metadata = $request->validated();

            $video = $this->videoService->uploadVideo($videoFile, $metadata);

            return ResponseHelper::success($video, 'Video uploaded successfully. Processing has started.', 202);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to upload video: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified video resource.
     *
     * @param Video $video
     * @return JsonResponse
     */
    public function show(Video $video): JsonResponse
    {
        return ResponseHelper::success($video);
    }

    /**
     * Update the metadata for a specified video.
     *
     * @param Request $request
     * @param Video $video
     * @return JsonResponse
     */
    public function update(Request $request, Video $video): JsonResponse
    {
        // Basic validation, a Form Request would be better for a real app
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|nullable',
        ]);

        $updatedVideo = $this->videoService->updateVideo($video, $validatedData);
        return ResponseHelper::success($updatedVideo, 'Video metadata updated successfully.');
    }

    /**
     * Remove the specified video from storage.
     *
     * @param Video $video
     * @return JsonResponse
     */
    public function destroy(Video $video): JsonResponse
    {
        $this->videoService->deleteVideo($video);
        return ResponseHelper::success(null, 'Video deleted successfully.', 204);
    }
}