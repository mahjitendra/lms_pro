<?php

namespace App\Http\Controllers\API\V1\Analytics;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\Analytics\InsightsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InsightsController extends Controller
{
    protected $insightsService;

    public function __construct(InsightsService $insightsService)
    {
        $this->insightsService = $insightsService;
    }

    /**
     * Get actionable insights for a specific context (e.g., a course or the entire platform).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getInsights(Request $request): JsonResponse
    {
        // $this->authorize('viewInsights'); // Authorization check

        $context = $request->input('context', 'platform_wide'); // e.g., 'course_1', 'user_group_3'

        try {
            $insights = $this->insightsService->generateInsights($context);
            return ResponseHelper::success($insights, 'Insights generated successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred while generating insights: ' . $e->getMessage());
        }
    }
}