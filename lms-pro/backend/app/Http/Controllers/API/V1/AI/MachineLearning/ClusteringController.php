<?php

namespace App\Http\Controllers\API\V1\AI\MachineLearning;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AI\ClusteringRequest; // To be created
use App\Services\AI\MachineLearning\ClusteringService;
use Illuminate\Http\JsonResponse;

class ClusteringController extends Controller
{
    protected $clusteringService;

    public function __construct(ClusteringService $clusteringService)
    {
        $this->clusteringService = $clusteringService;
    }

    /**
     * Perform clustering on a given set of data points.
     *
     * @param ClusteringRequest $request
     * @return JsonResponse
     */
    public function analyze(ClusteringRequest $request): JsonResponse
    {
        try {
            $dataPoints = $request->input('data_points');
            $numClusters = $request->input('n_clusters', 5); // Default to 5 clusters

            $result = $this->clusteringService->performClustering($dataPoints, $numClusters);

            return ResponseHelper::success($result, 'Clustering analysis completed successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::serverError('An error occurred during clustering analysis: ' . $e->getMessage());
        }
    }
}