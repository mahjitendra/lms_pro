<?php

namespace App\Http\Controllers\API\V1\AI\MachineLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\AI\MachineLearning\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Get course recommendations for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $limit = $request->input('limit', 10);

            $recommendations = $this->recommendationService->getForUser($user, $limit);

            return ResponseHelper::success($recommendations, 'Recommendations retrieved successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred while fetching recommendations: ' . $e->getMessage());
        }
    }
}