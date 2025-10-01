<?php

namespace App\Http\Controllers\API\V1\Analytics;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\GenerateReportRequest; // To be created
use App\Services\Analytics\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get a list of available report types.
     *
     * @return JsonResponse
     */
    public function availableReports(): JsonResponse
    {
        $reports = $this->reportService->getAvailableReportTypes();
        return ResponseHelper::success($reports, 'Available reports retrieved successfully.');
    }

    /**
     * Generate a new report.
     *
     * @param GenerateReportRequest $request
     * @return JsonResponse
     */
    public function generate(GenerateReportRequest $request): JsonResponse
    {
        try {
            $reportType = $request->input('report_type');
            $parameters = $request->input('parameters', []);
            $user = Auth::user();

            $this->reportService->dispatchReportGenerationJob($user, $reportType, $parameters);

            return ResponseHelper::success(
                ['message' => 'Your report is being generated. You will be notified when it is ready.'],
                'Report generation started successfully.',
                202
            );
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to start report generation: ' . $e->getMessage());
        }
    }
}