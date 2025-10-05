<?php

namespace App\Services\AI\MachineLearning;

use App\Exceptions\AI\PredictionException;
use App\Helpers\AIHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClusteringService
{
    /**
     * Perform clustering on a set of data points.
     *
     * @param array $dataPoints
     * @param int $numClusters
     * @return array
     * @throws PredictionException
     */
    public function performClustering(array $dataPoints, int $numClusters): array
    {
        $endpoint = AIHelper::getMLServiceEndpoint('perform-clustering'); // Assuming this endpoint exists
        $secretKey = config('ai.services.python_ml.secret');

        $response = Http::withHeaders(['X-Secret-Key' => $secretKey])
            ->timeout(60) // 1-minute timeout for potentially large datasets
            ->post($endpoint, [
                'data_points' => $dataPoints,
                'n_clusters' => $numClusters,
            ]);

        if ($response->failed()) {
            Log::error("Clustering service request failed.", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new PredictionException("The clustering service is currently unavailable.");
        }

        $responseData = $response->json();
        if ($responseData['status'] !== 'success') {
            throw new PredictionException("Clustering analysis failed: " . $responseData['message']);
        }

        return $responseData['data']; // e.g., ['labels' => [...], 'cluster_centers' => [...]]
    }
}