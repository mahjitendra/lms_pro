<?php

namespace App\Http\Controllers\API\V1\Analytics;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\Analytics\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get the dashboard data for the authenticated user based on their role.
     *
     * @return JsonResponse
     */
    public function getDashboardData(): JsonResponse
    {
        try {
            $user = Auth::user();
            $dashboardData = $this->dashboardService->getDataForUser($user);

            return ResponseHelper::success($dashboardData, 'Dashboard data retrieved successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred while fetching dashboard data: ' . $e->getMessage());
        }
    }
}