<?php

namespace App\Http\Controllers\API\V1\AI\ComputerVision;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\VideoAnalysisRequest; // To be created
use App\Services\AI\ComputerVision\VideoAnalysisService;
use Illuminate\Http\JsonResponse;

class VideoAnalysisController extends Controller
{
    protected $videoAnalysisService;

    public function __construct(VideoAnalysisService $videoAnalysisService)
    {
        $this->videoAnalysisService = $videoAnalysisService;
    }

    /**
     * Analyze a video for a set of specified features.
     *
     * @param VideoAnalysisRequest $request
     * @return JsonResponse
     */
    public function analyze(VideoAnalysisRequest $request): JsonResponse
    {
        try {
            $videoFile = $request->file('video');
            $features = $request->input('features', ['object_detection']); // Default to object detection

            $result = $this->videoAnalysisService->processVideoAnalysis($videoFile, $features);

            return ResponseHelper::success($result, 'Video analysis job dispatched successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during video analysis processing: ' . $e->getMessage());
        }
    }
}